<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Superadmin_lib
{
    public $ci;
    public $link;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function installTenant($database, $tenant_password)
    {
        $version = $this->ci->app->get_current_db_version();
        $dbFile          = module_dir_path(SUPERADMIN_MODULE, 'sql/database_' . $version . '.sql');
        $host            = get_option('mysql_host');
        $port            = get_option('mysql_port');
        $user            = get_option('mysql_root_username');
        $pass            = $this->ci->encryption->decrypt(get_option('mysql_password'));
        $tenant_password = $this->ci->encryption->decrypt($tenant_password);

        $tenant_database = $database;
        $tenant_user     = $database;
        $tenant_port     = $port;

        switchDatabase('', $user, $pass, $host, $port);

        $this->ci->load->dbforge();
        $this->ci->load->dbutil();

        if ($this->ci->dbutil->database_exists($database)) {
            /* if we drop database then it will drop the existing database and install fresh setup each time when new contact will insert. */
            $this->ci->dbforge->drop_database($database);
        }

        // Create new DB
        $this->ci->dbforge->create_database($database);

        // Create Mysql User
        $this->ci->db->query('CREATE USER ' . $this->ci->db->escape($tenant_user) . '@' . $this->ci->db->escape("%") . ' IDENTIFIED BY ' . $this->ci->db->escape($tenant_password));

        // Give access to newly created user on newly created DB.
        $this->ci->db->query('GRANT ALL PRIVILEGES ON `' . $tenant_database . '`.* TO ' . $this->ci->db->escape($tenant_user) . '@' . $this->ci->db->escape("%") . ' WITH GRANT OPTION');

        $this->link = new mysqli($host, $tenant_user, $tenant_password, $tenant_database, $tenant_port);

        $this->link->begin_transaction();
        try {
            $this->ci->load->library(SUPERADMIN_MODULE . '/SqlScriptParser');
            $parser        = new SqlScriptParser();
            $sqlStatements = $parser->parse($dbFile);
            foreach ($sqlStatements as $statement) {
                $distilled = $parser->removeComments($statement);
                if (!empty($distilled)) {
                    $this->link->query($distilled);
                }
            }
            $this->link->commit();

            $this->ci->load->config('migration');
            $updateToVersion     = $this->ci->config->item('migration_version');

            switchDatabase($tenant_database, $tenant_user, $tenant_password, $host, $tenant_port);

            $this->ci->load->library('migration', [
                'migration_enabled'     => true,
                'migration_type'        => $this->ci->config->item('migration_type'),
                'migration_table'       => $this->ci->config->item('migration_table'),
                'migration_auto_latest' => $this->ci->config->item('migration_auto_latest'),
                'migration_version'     => $updateToVersion,
                'migration_path'        => $this->ci->config->item('migration_path'),
            ]);

            return true;
        } catch (mysqli_sql_exception $exception) {
            $this->link->rollback();
            throw $exception;

            return false;
        }
    }

    public function assignPlanToClientAndInstall($data, $userid)
    {
        $client      = $this->ci->clients_model->get($userid);
        $contact     = $this->ci->clients_model->get_contact($data['contactid']);
        $tenantsName = !empty($data['tenants_name']) ? $data['tenants_name'] : preg_replace('/\s+/', '', $client->company);
        $tenantsName = strtolower(preg_replace('/[^a-z\d]+$/', '', $tenantsName));
        $planDetails = getSaasPlans($data['tenant_plan']);
        $dbName      = TENANT_DB_PREFIX . url_title($tenantsName);
        $password    = $this->ci->encryption->encrypt(randomPassword());

        $clientPlanData = [
            'userid'              => $userid,
            'tenants_name'        => $tenantsName,
            'tenants_db_username' => $dbName,
            'tenants_db_password' => $password,
            'tenants_db'          => $dbName,
            'tenants_admin'       => $data['contactid'],
            'plan_id'             => $data['tenant_plan'],
            'plan_details_json'   => json_encode($planDetails),
            'trial_days'          => empty($planDetails['trial']) ? 0 : get_option('trial_period_days'),
            'trial_start_time'    => date('Y-m-d H:i:s'),
            'is_active'           => 1,
            'allowed_modules'     => $planDetails['allowed_modules'],
        ];

        $this->ci->load->model(SUPERADMIN_MODULE . '/superadmin_model');

        $is_exist = $this->ci->superadmin_model->getSingleRow('client_plan', [
            'userid'        => $userid,
            'tenants_name'  => $tenantsName,
            'tenants_admin' => $data['contactid'],
        ]);

        if (!empty($is_exist)) {
            return false;
        }

        /* Adjust some of the default settings for tenants: start */
        // Get the superadmin's theme
        $adminThemeStyle = [
            'theme_style'                               => get_option('theme_style'),
            'theme_style_custom_admin_area'             => get_option('theme_style_custom_admin_area'),
            'theme_style_custom_clients_area'           => get_option('theme_style_custom_clients_area'),
            'theme_style_custom_clients_and_admin_area' => get_option('theme_style_custom_clients_and_admin_area')
        ];
        /* Adjust some of the default settings for tenants: end */

        $this->ci->superadmin_model->insertRow('client_plan', $clientPlanData);
        $log = _l('tenant_register', $userid) . ' ' . _l('contactId', $data['contactid']);

        if ('' == $log && isset($data['contactid'])) {
            $log = get_contact_full_name($data['contactid']);
        }

        $isStaff = null;
        if (!is_client_logged_in() && is_staff_logged_in()) {
            $isStaff = get_staff_user_id();
        }
        saas_activity_log($log, $isStaff);
        try {
            $installed = $this->installTenant($dbName, $clientPlanData['tenants_db_password']);
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        switchDatabase($clientPlanData['tenants_db'], $clientPlanData['tenants_db_username'], $this->ci->encryption->decrypt($password), get_option('mysql_host'), get_option('mysql_port'));

        /* Adjust some of the default settings for tenants: start */
        // Prepare an array with unique slug from 'tblemailtemplates'
        $emailTemplatesSlug = $this->ci->db->select('slug')->distinct()->get(db_prefix() . 'emailtemplates')->result_array();
        /* Adjust some of the default settings for tenants: end */
        
        if ($installed) {
            $installData = [
                'email'       => $contact->email,
                'firstname'   => $contact->firstname,
                'lastname'    => $contact->lastname,
                'password'    => $contact->password,
                'admin'       => 1,
                'active'      => 1,
                'datecreated' => date('Y-m-d H:i:s'),
            ];
            // insert the first contact as tenant admin
            $this->ci->db->insert(db_prefix() . 'staff', $installData);

            // insert row in module table for tenant_management module and make it active by default.
            if (!total_rows(db_prefix() . 'modules', ['module_name' => 'saas', 'installed_version' => $this->ci->app_modules->get('saas')['installed_version'], 'active' => 1])) {
                $this->ci->db->insert(db_prefix() . 'modules', ['module_name' => 'saas', 'installed_version' => $this->ci->app_modules->get('saas')['installed_version'], 'active' => 1]);

                // force enable tenant management module in branch
                add_option('superadmin_enabled', 1);

                /* Adjust some of the default settings for tenants: start */
                // Do not display help menu
                update_option('show_help_on_setup_menu', 0);

                // Default language should be the one which is selected during the client registration.
                update_option('active_language', !empty($client->default_language) ? $client->default_language : get_option('active_language'));

                // Disable the option "Delete invoice allowed only on last invoice ?"
                update_option('delete_only_on_last_invoice', 0);

                // Set the default currency to euros.
                $updateDefaultCurrency = "UPDATE " . db_prefix() . "currencies SET isdefault = CASE WHEN id = 1 THEN 0 WHEN id = 2 THEN 1 END WHERE id IN (1, 2)";
                $this->ci->db->query($updateDefaultCurrency);

                // Set default tax to VAT.
                $this->ci->db->insert(db_prefix() . 'taxes', [
                    'name'    => 'VAT',
                    'taxrate' => '21.00'
                ]);
                $defaultTax = ['VAT|21.00'];
                update_option('default_tax', serialize($defaultTax));

                // Disable the cloudflare notice
                update_option('show_cloudflare_notice', 0);

                // Set the thousand seperator
                update_option('thousand_separator', ',');

                // Disable the option "Remove decimals on numbers/money with zero decimals (2.00 will become 2, 2.25 will stay 2.25)"
                update_option('remove_decimals_on_zero', 0);

                // Disable all email templates.
                $this->ci->db->update(db_prefix() . 'emailtemplates', ['active' => 0]);

                // Set default them same as "superadmin's theme"
                update_option('theme_style', $adminThemeStyle['theme_style']);
                update_option('theme_style_custom_admin_area', $adminThemeStyle['theme_style_custom_admin_area']);
                update_option('theme_style_custom_clients_area', $adminThemeStyle['theme_style_custom_clients_area']);
                update_option('theme_style_custom_clients_and_admin_area', $adminThemeStyle['theme_style_custom_clients_and_admin_area']);
                update_option('custom_theme_style_migrate', 1);
                /* Adjust some of the default settings for tenants: end */

                /* Adjust some of the default settings for tenants: start */
                update_option('calendar_first_day', '1');
                /* Adjust some of the default settings for tenants: end */

                /* Adjust some of the default settings for tenants: start */
                $departments = [
                    ['name' => _l('marketing') ],
                    ['name' => _l('comercial') ],
                    ['name' => _l('compras') ],
                    ['name' => _l('contabilidad') ],
                    ['name' => _l('soporte') ],
                    ['name' => _l('proyectos') ],
                ];
                $this->ci->superadmin_model->insertRow(db_prefix() . 'departments', $departments, true);
                /* Adjust some of the default settings for tenants: end */

                $this->ci->app_modules->activate('saas');

                if ($this->ci->db->table_exists(db_prefix() . 'contacts')) {
                    if (!$this->ci->db->field_exists('acceptance_ip', db_prefix() . 'contacts')) {
                        $this->ci->db->query('ALTER TABLE `' . db_prefix() . 'contacts` ADD `acceptance_ip` VARCHAR(40) NULL DEFAULT NULL;');
                    }
                    if (!$this->ci->db->field_exists('signature', db_prefix() . 'contacts')) {
                        $this->ci->db->query('ALTER TABLE `' . db_prefix() . 'contacts` ADD `signature` VARCHAR(40) NULL DEFAULT NULL;');
                    }
                }
            }
        }

        switchDatabase();

        /* Adjust some of the default settings for tenants: start */
        // Get email templates in all languages from superadmin's DB.
        $templates = $this->ci->db->where_in('slug', array_column($emailTemplatesSlug, 'slug'))->get(db_prefix() . 'emailtemplates')->result_array();
        /* Adjust some of the default settings for tenants: end */

        /* Add allowed modules inside tenant-wise in tenant_modules folder - start */
        // Fetch selected SaaS Plan details
        $planDetails = getSaasPlans($data['tenant_plan']);
        // Get the list of allowed modules
        $allowedModules = unserialize($planDetails['allowed_modules']);
        $tenantModulesPath = FCPATH . 'tenant_modules/' . $tenantsName;

        // Create tenant's folder inside tenant_modules if it doesn't exist.
        _maybe_create_upload_path($tenantModulesPath);
        foreach ($allowedModules as $moduleName => $value) {
            // Create tenant's module folder inside tenant's if it doesn't exist.
            $modulePath = $tenantModulesPath . '/' . $moduleName;
            _maybe_create_upload_path($modulePath);
            // Copy module files to the corresponding tenant's module path
            xcopy(APP_MODULES_PATH . $moduleName, $modulePath);
        }
        /* Add allowed modules inside tenant-wise in tenant_modules folder - end */

        if (empty($clientPlanData['trial_days'])) {
            $this->createPlanInvoice($userid, $planDetails);
        }

        switchDatabase($clientPlanData['tenants_db'], $clientPlanData['tenants_db_username'], $this->ci->encryption->decrypt($password), get_option('mysql_host'), get_option('mysql_port'));
        
        /* Adjust some of the default settings for tenants: start */
        // Add email templates in all languages from superadmin to tenants DB.
        $this->ci->db->truncate(db_prefix() . 'emailtemplates');
        $assignTemplates = $this->ci->superadmin_model->insertRow(db_prefix() . 'emailtemplates', $templates, true);
        update_option('email_template_migrated', $assignTemplates);
        /* Adjust some of the default settings for tenants: end */

        foreach ($allowedModules as $moduleName => $value) {
            if (!total_rows(db_prefix() . 'modules', ['module_name' => $moduleName, 'installed_version' => $this->ci->app_modules->get($moduleName)['installed_version'], 'active' => 1])) {
                $this->ci->db->insert(db_prefix() . 'modules', ['module_name' => $moduleName, 'installed_version' => $this->ci->app_modules->get($moduleName)['installed_version'], 'active' => 1]);
                $this->ci->app_modules->activate($moduleName);
                /* Adjust some of the default settings for tenants: start */
                if ($moduleName == 'status_manager') {
                    $task_statuses = get_tenant_task_status();
                    foreach ($task_statuses as $key => $task_status) {
                        $this->ci->db->insert(db_prefix() . 'task_statuses', $task_status);
                    }
                }
                /* Adjust some of the default settings for tenants: end */

                /* Adjust some of the default settings for tenants: start */
                if ($moduleName == 'purchase') {
                    $this->ci->db->update(db_prefix() . 'purchase_option', ['option_val' => 'pur_order'], ['option_name' => 'create_invoice_by']);

                    // Add default units
                    $default_units = [
                        'unit_code'   => 'paquete',
                        'unit_name'   => 'paquete',
                        'display'     => '1',
                        'unit_symbol' => '0',
                        'order'       => '0',
                        'note'        => '0',
                    ];
                    $this->ci->db->insert(db_prefix() . 'ware_unit_type', $default_units);

                    // Add default groups
                    $default_group = [
                        'name'                 => 'compras',
                        'commodity_group_code' => 'compras',
                    ];
                    $this->ci->db->insert(db_prefix() . 'items_groups', $default_group);

                    // Add default sub groups
                    $default_sub_group = [
                        'sub_group_code' => 'compras',
                        'sub_group_name' => 'compras',
                        'order'          => '0',
                        'display'        => '0',
                        'note'           => '0',
                        'group_id'       => '0',
                    ];
                    $this->ci->db->insert(db_prefix() . 'wh_sub_group', $default_sub_group);
                }
                /* Adjust some of the default settings for tenants: end */
            }
        }

        /* Adjust some of the default settings for tenants: start */
        // Add default statuses for leads in spanish
        $defaultLeadsStatuses = [
            [ 'name' => 'Sin contactar' ],
            [ 'name' => 'Tomar accion' ],
            [ 'name' => 'Esperando respuesta' ],
            [ 'name' => 'Seguimiento (30 dias)' ],
            [ 'name' => 'Seguimiento (360 dias)' ]
        ];

        foreach ($defaultLeadsStatuses as $key => $value) {
            if (!total_rows(db_prefix() . 'leads_status', ['name' => $value['name']])) {
                $this->ci->db->insert(db_prefix() . 'leads_status', $value);
            }
        }
        /* Adjust some of the default settings for tenants: end */

        switchDatabase();

        // we are not checking anything here onboard email will only send when branch is created
        send_mail_template('onboarding_email_template', SUPERADMIN_MODULE, $contact->email, $contact);
    }

    public function createPlanInvoice($clientID, $planDetails = null)
    {
        if (empty($planDetails)) {
            $clientPlan  = getClientPlan($clientID);
            $planDetails = getSaasPlans($clientPlan->plan_id); // Get current and updated plan details before invoicing
        }

        $billingShipping = $this->ci->clients_model->get_customer_billing_and_shipping_details($clientID);

        $clientData = $billingShipping[array_key_first($billingShipping)];

        $clientData['currency']  = $this->ci->clients_model->get_customer_default_currency($clientID);

        $rate = (get_base_currency()->id == $clientData['currency'] || $clientData['currency'] == 0) ? $planDetails['price'] : $planDetails['price_' . $clientData['currency']];

        $clientData['currency']  = (!empty($planDetails['price_' . $clientData['currency']])) ? $clientData['currency'] : get_base_currency()->id;

        $duedate = _d(date('Y-m-d', strtotime('+7 DAY', strtotime(date('Y-m-d')))));
        if (0 != get_option('invoice_due_after')) {
            $duedate = _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d')))));
        }

        $invoiceData = [
            'clientid'                 => $clientID,
            'show_shipping_on_invoice' => 'on',
            'number'                   => str_pad(get_option('next_invoice_number'), get_option('number_padding_prefixes'), '0', \STR_PAD_LEFT),
            'date'                     => date('Y-m-d'),
            'duedate'                  => $duedate ?? date('Y-m-d'),
            'allowed_payment_modes'    => unserialize($planDetails['allowed_payment_modes']),
            'recurring'                => ($planDetails['custom_recurring']) ? 'custom' : $planDetails['recurring'],
            'repeat_every_custom'      => ($planDetails['custom_recurring']) ? $planDetails['custom_recurring'] : '',
            'repeat_type_custom'       => ($planDetails['custom_recurring']) ? $planDetails['recurring_type'] : '',
            'discount_type'            => '',
        ];

        $invoiceData = array_merge($clientData, $invoiceData);

        $invoiceItem = [
            'order'            => '1',
            'description'      => $planDetails['plan_name'],
            'long_description' => $planDetails['plan_description'],
            'qty'              => '1',
            'unit'             => '',
            'rate'             => $rate,
            'taxname'          => unserialize($planDetails['taxes']),
        ];

        $invoiceData['newitems'][] = $invoiceItem;

        $total = $subtotal = 0;
        foreach ($invoiceData['newitems'] as $items) {
            $subtotal += $items['rate'] * $items['qty'];
            $total = $subtotal;
            if (!empty($items['taxname'])) {
                foreach ($items['taxname'] as $tax) {
                    if (!is_array($tax)) {
                        $tmp_taxname = $tax;
                        $tax_array   = explode('|', $tax);
                    } else {
                        $tax_array   = explode('|', $tax['taxname']);
                        $tmp_taxname = $tax['taxname'];
                        if ('' == $tmp_taxname) {
                            continue;
                        }
                    }
                    $total += ($items['rate'] * $items['qty']) / 100 * $tax_array[1];
                }
            }
        }
        $invoiceData['subtotal']      = $subtotal;
        $invoiceData['total']         = $total;
        $invoiceData['save_and_send'] = true;

        $this->ci->load->model('invoices_model');
        $id = $this->ci->invoices_model->add($invoiceData);

        if ($id) {
            // Only Add log if trial plan is there
            if (empty($planDetails['trial'])) {
                $log =  _l('trial_plan_over', $id);
                saas_activity_log($log);
            }

            $this->ci->superadmin_model->updateRow('client_plan', ['is_invoiced' => 1, 'invoices' => json_encode([$id])], ['userid' => $clientID]);
        }
    }

    public function updatePlan($clientID, $newPlanId)
    {
        $clientPlan = getClientPlan($clientID);

        if (empty($clientPlan)) {
            echo json_encode(["status" => true, "message" => "No Plan selected"]);
            return;
        }

        $currPlanDetail = getSaasPlans($clientPlan->plan_id);
        if ($currPlanDetail['custom_recurring'] == 0) {
            $currPlanDetail['recurring_type'] = 'MONTH';
        }
        $days = date("Y-m-d", strtotime('+' . $currPlanDetail['recurring'] . ' ' . strtoupper($currPlanDetail['recurring_type'])));

        // get plan days
        $date = \Carbon\Carbon::create($days);
        $plan_days = \Carbon\Carbon::parse($date)->diffInDays(null);

        $newPlanDetail = getSaasPlans($newPlanId);

        $planUpdateData = ["plan_id" => $newPlanId, "plan_details_json" => json_encode($newPlanDetail)];
        if(empty($newPlanDetail['trial']) && $currPlanDetail['trial']){
            $planUpdateData['trial_days'] = 0;
        }
        if($newPlanDetail['trial'] && empty($currPlanDetail['trial'])){
            $planUpdateData['trial_days'] = get_option('trial_period_days');
        }

        if (!$clientPlan->is_invoiced) {

            $this->ci->superadmin_model->updateRow('client_plan', $planUpdateData, ['userid' => $clientID]);
            echo json_encode(["status" => true, "message" => "Planned updated successfully"]);
            return;
        }

        $allInvoice = [];

        get_instance()->load->model('invoices_model');
        $allinvoicesId = json_decode($clientPlan->invoices);
        $planInvoices   = get_instance()->invoices_model->get("", db_prefix() . 'invoices' . ".id IN(".implode(",", $allinvoicesId).")");
        $allInvoice       = array_merge($allInvoice, $planInvoices);
        $invoiceDetail = $allInvoice[array_key_first($allInvoice)];

        $planPrice = $currPlanDetail['price_' . $invoiceDetail['currency']] ?? $currPlanDetail['price'];
        $planPricePerDay = round($planPrice / $plan_days, 2);

        $allInvoiceById  = array_column($allInvoice, null, "id");
        $allInvoiceByDate  = array_column($allInvoice, "date", "id");
        arsort($allInvoiceByDate);
        $last_invoice_date = $allInvoiceByDate[array_key_first($allInvoiceByDate)];
        $last_invoice = $allInvoiceById[array_key_first($allInvoiceByDate)];

        $date = \Carbon\Carbon::create($last_invoice_date);
        $remaining_days = \Carbon\Carbon::parse($date)->diffInDays(null, false);

        $amountShouldBe = $planPricePerDay * $remaining_days;


        $totalPaid = array_count_values(array_column($allInvoice, "status"))[Invoices_model::STATUS_PAID] ?? 0;

        if($totalPaid != count($allInvoice)){
            echo json_encode(["status" => false, "message" => "Some invoices are not paid"]);
            return;
        }

        $billingShipping = $this->ci->clients_model->get_customer_billing_and_shipping_details($clientID);

        $clientData = $billingShipping[array_key_first($billingShipping)];

        $clientData['currency']  = $this->ci->clients_model->get_customer_default_currency($clientID);
        $clientData['currency']  = (!empty($newPlanDetail['price_' . $clientData['currency']])) ? $clientData['currency'] : get_base_currency()->id;

        $duedate = _d(date('Y-m-d', strtotime('+7 DAY', strtotime(date('Y-m-d')))));
        if (0 != get_option('invoice_due_after')) {
            $duedate = _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d')))));
        }

        $adjustmentAmount = $amountShouldBe - ($currPlanDetail['price_' . $clientData['currency']] ?? $currPlanDetail['price']);
        $adjustmentAmountForInvoice = $adjustmentAmount;
        $newPlanPrice = $newPlanDetail['price_' . $clientData['currency']] ?? $newPlanDetail['price'];
        $planUpdateData['adjustmentAmount'] = 0;
        if ($adjustmentAmount < 0) {
            $adjustmentAmountForInvoice = ($newPlanPrice < abs($adjustmentAmount)) ? -$newPlanPrice : $adjustmentAmount;
            $planUpdateData['adjustmentAmount'] = abs($adjustmentAmount) - $newPlanPrice;
        }

        $invoiceData = [
            'clientid'                 => $clientID,
            'show_shipping_on_invoice' => 'on',
            'number'                   => str_pad(get_option('next_invoice_number'), get_option('number_padding_prefixes'), '0', \STR_PAD_LEFT),
            'date'                     => date('Y-m-d'),
            'duedate'                  => $duedate ?? date('Y-m-d'),
            'allowed_payment_modes'    => unserialize($newPlanDetail['allowed_payment_modes']),
            'recurring'                => ($newPlanDetail['custom_recurring']) ? 'custom' : $newPlanDetail['recurring'],
            'repeat_every_custom'      => ($newPlanDetail['custom_recurring']) ? $newPlanDetail['custom_recurring'] : '',
            'repeat_type_custom'       => ($newPlanDetail['custom_recurring']) ? $newPlanDetail['recurring_type'] : '',
            'discount_type'            => '',
        ];

        $invoiceData = array_merge($clientData, $invoiceData);

        $invoiceItem = [
            'order'            => '1',
            'description'      => $newPlanDetail['plan_name'],
            'long_description' => $newPlanDetail['plan_description'],
            'qty'              => '1',
            'unit'             => '',
            'rate'             => $newPlanPrice,
            'taxname'          => unserialize($newPlanDetail['taxes']),
        ];

        $invoiceData['newitems'][] = $invoiceItem;

        if (!empty($adjustmentAmountForInvoice)) {
            $settlementItem = [
                'order'            => '2',
                'description'      => _l("invoice_adjustment"),
                'long_description' => _l("invoice_adjustment") . " for plan update from invoice " . format_invoice_number($last_invoice['id']),
                'qty'              => '1',
                'unit'             => '',
                'rate'             => $adjustmentAmountForInvoice,
                'taxname'          => [],
            ];
            $invoiceData['newitems'][] = $settlementItem;
        }

        $total = $subtotal = 0;
        foreach ($invoiceData['newitems'] as $items) {
            $subtotal += $items['rate'] * $items['qty'];
            $total = $subtotal;
            if (!empty($items['taxname'])) {
                foreach ($items['taxname'] as $tax) {
                    if (!is_array($tax)) {
                        $tmp_taxname = $tax;
                        $tax_array   = explode('|', $tax);
                    } else {
                        $tax_array   = explode('|', $tax['taxname']);
                        $tmp_taxname = $tax['taxname'];
                        if ('' == $tmp_taxname) {
                            continue;
                        }
                    }
                    $total += ($items['rate'] * $items['qty']) / 100 * $tax_array[1];
                }
            }
        }
        $invoiceData['subtotal'] = $subtotal;
        $invoiceData['total']    = $total;

        $this->ci->load->model('invoices_model');
        $id = $this->ci->invoices_model->add($invoiceData);

        $invoices = json_decode($clientPlan->invoices);
        array_push($invoices, $id);
        $planUpdateData['invoices'] = json_encode($invoices);

        $this->ci->superadmin_model->updateRow('client_plan', $planUpdateData, ['userid' => $clientID]);

        echo json_encode(["status" => true, "message" => "Planned updated successfully"]);
        return;


    }
}
