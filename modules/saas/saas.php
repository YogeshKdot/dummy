<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
  Module Name: SaaS Module - Multitenancy support for Perfex CRM
  Module URI: https://codecanyon.net/item/saas-module-for-perfex-crm-multitenancy-support/45902802
  Description: Turn your Perfex CRM installation into a multitenant SaaS business/product
  Version: 1.0.5
  Requires at least: 3.0.*
*/

require_once __DIR__.'/vendor/autoload.php';

/*
 * Define module name
 * Module constant name must be in CAPITAL LETTERS
 */
define('SUPERADMIN_MODULE', 'saas');

// DB prefix for tenant's Database
define('TENANT_DB_PREFIX', 'tenant_');

define('SAAS_MODULE_UPLOAD_FOLDER', module_dir_path(SUPERADMIN_MODULE, 'uploads/'));

define('SUPERADMIN_REGISTRATION_FOLDER', FCPATH . 'uploads/register/superadmin' . '/');
define('TENANT_REGISTRATION_FOLDER', FCPATH . 'uploads/register/tenant' . '/');

// Making company field required in settings if admin updated
update_option('company_is_required', 1);

// Get codeigniter instance
get_instance()->config->load('saas/config', false);

register_language_files(SUPERADMIN_MODULE, [SUPERADMIN_MODULE]);

/*
 * Register deactivation module hook
 */
register_deactivation_hook(SUPERADMIN_MODULE, 'saas_module_deactivation_hook');
function saas_module_deactivation_hook()
{
    update_option('superadmin_enabled', 0);

    $my_files_list = [
        VIEWPATH.'themes/perfex/views/my_register.php',
        VIEWPATH.'admin/modules/my_list.php',
        VIEWPATH.'admin/clients/modals/my_contact.php',
    ];

    foreach ($my_files_list as $actual_path) {
        if (file_exists($actual_path)) {
            @unlink($actual_path);
        }
    }

    $backup_files_list = [
        APPPATH.'helpers/clients_helper.php',
        APPPATH.'helpers/files_helper.php',
        APPPATH.'helpers/staff_helper.php',
        APPPATH.'helpers/upload_helper.php',
        APPPATH.'helpers/modules_helper.php',
        APPPATH.'config/config.php',
        APPPATH.'config/constants.php',
        APPPATH.'libraries/App_modules.php',
        APPPATH.'controllers/Authentication.php',
        APPPATH.'controllers/Tasks.php'
    ];

    foreach ($backup_files_list as $actual_path) {
        if (file_exists($actual_path) && file_exists($actual_path.'.backup')) {
            @unlink($actual_path);
        }
        if (!file_exists($actual_path)) {
            rename($actual_path.'.backup', $actual_path);
        }
    }
}

register_deactivation_hook(SUPERADMIN_MODULE, 'saas_module_uninstallation_hook');
function saas_module_uninstallation_hook()
{
    $my_files_list = [
        APPPATH.'helpers/my_functions_helper.php',
    ];

    foreach ($my_files_list as $actual_path) {
        if (file_exists($actual_path)) {
            @unlink($actual_path);
        }
    }
}

get_instance()->load->helper([SUPERADMIN_MODULE.'/superadmin', SUPERADMIN_MODULE.'/tenant']);
get_instance()->load->library(SUPERADMIN_MODULE.'/superadmin_lib');
get_instance()->load->model(SUPERADMIN_MODULE.'/custom_model');

$tenant = getSubDomain();
define('IS_TENANT', (bool) $tenant);
if ($tenant) {
    define('TENANT_NAME', $tenant);
}

if (!IS_TENANT) {
    require_once __DIR__.'/includes/landloard_scripts/scripts.php';
    require_once __DIR__.'/includes/landloard_scripts/sidemenu.php';
    require_once __DIR__.'/includes/landloard_scripts/email_templates.php';
    require_once __DIR__.'/includes/landloard_scripts/client_scripts.php';

    // Include install.php only when activating saas module from superadmin side
    if (APP_DB_NAME == get_instance()->db->database) {
        register_activation_hook(SUPERADMIN_MODULE, 'superadmin_module_activation_hook');
        function superadmin_module_activation_hook()
        {
            require_once __DIR__.'/install.php';
            _maybe_create_upload_path(FCPATH . 'tenant_modules');
            _maybe_create_upload_path(FCPATH . 'uploads/register');
            _maybe_create_upload_path(FCPATH . 'uploads/register/superadmin');
            _maybe_create_upload_path(FCPATH . 'uploads/register/tenant');
        }
    }

    // Inject widget on dashboard for superadmin module
    hooks()->add_filter('get_dashboard_widgets', 'tenants_add_dashboard_widget');
    function tenants_add_dashboard_widget($widgets)
    {
        $new_widgets[] = [
            'path'      => SUPERADMIN_MODULE.'/widgets/superadmin-widget',
            'container' => 'top-12',
        ];

        return array_merge($new_widgets, $widgets);
    }

    // Add SaaS Pricing Menu In Customer Side
    hooks()->add_action('customers_navigation_start', 'add_saas_menu');
    function add_saas_menu()
    {
        if (!is_client_logged_in()) {
            if ('1' == get_option('tenants_landing')) {
                echo '<li class="customers-nav-item-contracts">
                <a href="'.site_url(SUPERADMIN_MODULE.'/pricing').'">'._l('pricing_menu').'</a>
                </li>';
            }
            echo '<li class="customers-nav-item-contracts">
            <a href="'.site_url(SUPERADMIN_MODULE.'/tenants/find').'">'._l('find_my_tenant').'</a>
            </li>';
        }
    }

    // Cron job logic
    hooks()->add_filter('before_invoice_added', function ($data) {
        if (!empty($data['data']['is_recurring_from'])) {
            if (isset($data['items'][2])) {
                if($data['items'][2]['order'] == 2 && $data['items'][2]['description'] == "Adjustment" && strpos($data['items'][2]['long_description'] , "Adjustment for plan update from invoice") !== false) {
                    $data['data']['total'] = $data['data']['total'] - $data['items'][2]['rate'];
                    $data['data']['subtotal'] = $data['data']['subtotal'] - $data['items'][2]['rate'];
                    unset($data['items'][2]);
                }
            }
            $plan = getClientPlan($data['data']['clientid']);
            $planDetail = getSaasPlans($plan->plan_id);
            $planInvoices = json_decode($plan->invoices);
            if(in_array($data['data']['is_recurring_from'], $planInvoices)){
                $adjustmentAmount = $plan->adjustmentAmount;
                $newPlanPrice = $planDetail['price_' . $data['data']['currency']] ?? $planDetail['price'];
                $adjustmentAmountForInvoice = 0;
                $planUpdateData['adjustmentAmount'] = 0;
                if ($adjustmentAmount > 0) {
                    $adjustmentAmountForInvoice = ($adjustmentAmount > $newPlanPrice) ? -$newPlanPrice : $adjustmentAmount;
                    $planUpdateData['adjustmentAmount'] = abs($adjustmentAmount) - $newPlanPrice;
                }

                if (!empty($adjustmentAmountForInvoice)) {
                    $settlementItem = [
                        'order'            => '2',
                        'description'      => _l("invoice_adjustment"),
                        'long_description' => _l("invoice_adjustment") . " for plan update from invoice " . format_invoice_number($data['data']['is_recurring_from']),
                        'qty'              => '1',
                        'unit'             => '',
                        'rate'             => $adjustmentAmountForInvoice,
                        'taxname'          => [],
                    ];
                    $data['items'][] = $settlementItem;
                    get_instance()->superadmin_model->updateRow('client_plan', $planUpdateData, ['userid' => $data['data']['clientid']]);
                }
            }
        }
        return $data;
    });
    hooks()->add_filter('after_invoice_added', function ($id) {
        get_instance()->load->model('invoices_model');
        $invoice = get_instance()->invoices_model->get($id);
        if (!empty($invoice->is_recurring_from)) {
            $plan = getClientPlan($invoice->clientid);
            $planInvoices = json_decode($plan->invoices);
            if(in_array($invoice->is_recurring_from, $planInvoices)){
                array_push($planInvoices, $id);
                $planUpdateData['invoices'] = json_encode($planInvoices);
                get_instance()->superadmin_model->updateRow('client_plan', $planUpdateData, ['userid' => $invoice->clientid]);
            }
        }
    });
    hooks()->add_action('after_cron_run', function ($manually) {
        /* Create Plan invoice after trial ends */
        $clientPlans = getClientPlan();
        foreach ($clientPlans as $client) {
            if (!$client->is_invoiced) {
                $date = \Carbon\Carbon::create($client->trial_start_time);
                $date->addDays($client->trial_days);
                $diff = getRemainingDays($date);
                if ($diff >= 0) {
                    get_instance()->superadmin_lib->createPlanInvoice($client->userid);
                }
            }
        }

        /* RUN TENANT'S CRON */
        get_instance()->load->library(SUPERADMIN_MODULE.'/Tenants_CronManagement_lib');
        get_instance()->tenants_cronmanagement_lib->init_tenants_cron();

        /* DELETE */
        $inactive_tenants_limit_in_days = get_option('inactive_tenants_limit');
        $inactive_tenants               =  get_instance()->db->get_where(db_prefix().'client_plan', ['is_active' => '0', 'is_deleted' => '0'])->result_array();
        if (!empty($inactive_tenants)) {
            foreach ($inactive_tenants as $tenant_data) {
                $inactive_date = \Carbon\Carbon::createFromTimestamp($tenant_data['inactive_date']);
                $difference    = $inactive_date->diffInDays(\Carbon\Carbon::now());

                // check if invative teanants delete limit days are crossed
                if ($inactive_tenants_limit_in_days <= $difference) {
                    /* Delete database and user */
                    $host = get_option('mysql_host');
                    $port = get_option('mysql_port');
                    $user = get_option('mysql_root_username');
                    $pass = get_instance()->encryption->decrypt(get_option('mysql_password'));

                    switchDatabase('', $user, $pass, $host, $port);

                    get_instance()->db->query('DROP DATABASE '.$tenant_data['tenants_db'].';');
                    get_instance()->db->query('DROP USER '.$tenant_data['tenants_db_username'].'@'.$host.';');

                    switchDatabase();

                    get_instance()->load->model('saas/superadmin_model');
                    get_instance()->superadmin_model->deleteRow('client_plan', ['id' => $tenant_data['id']]);

                    if (get_instance()->db->affected_rows() > 0) {
                        $log = _l('tenant_delete', $tenant_data['id']).' '._l('tenant_name', $tenant_data['tenants_name']);
                        saas_activity_log($log);
                    }

                    /* remove dir */
                    remove_tenant_directory(FCPATH.'uploads', $tenant_data['tenants_name']);
                }
            }
        }
    });

    hooks()->add_action('invoice_due_reminder_sent', function ($data) {

        $invoice = get_instance()->invoices_model->get($data['invoice_id']);

        $first_invoice_id = $data['invoice_id'];
        if(!empty($invoice->is_recurring_from)){
            $first_invoice_id = $invoice->is_recurring_from;
        }

        $plan = getClientPlan($invoice->clientid);
        if (!empty($plan)) {
            $invoice_id = json_decode($plan->invoices);
            if (in_array($first_invoice_id, $invoice_id)) {
                $contactEmail = get_instance()->db->get_where(db_prefix() . 'contacts', ['is_primary' => '1', 'userid' => $invoice->clientid])->row()->email;

                send_mail_template('tenant_expiration_email', SUPERADMIN_MODULE, $invoice->clientid, $contactEmail);
            }
        }
    });


    hooks()->add_action('invoice_overdue_reminder_sent', function ($data) {
        get_instance()->load->model('invoices_model');
        get_instance()->load->model('saas/superadmin_model');
        $invoice = get_instance()->invoices_model->get($data['invoice_id']);

        $invoice_data['recurring']           = 0;
        $invoice_data['cycles']              = 0;
        $invoice_data['total_cycles']        = 0;
        $invoice_data['last_recurring_date'] = null;
        $first_invoice_id = $data['invoice_id'];
        if(!empty($invoice->is_recurring_from)){
            $first_invoice_id = $invoice->is_recurring_from;
        }
        $plan = getClientPlan($invoice->clientid);
        if (!empty($plan)) {
            $invoice_id = json_decode($plan->invoices);
            if (in_array($first_invoice_id, $invoice_id)) {
                get_instance()->superadmin_model->updateRow('invoices', $invoice_data, ['id' => $first_invoice_id]);

                get_instance()->superadmin_model->updateRow('client_plan', ['is_active'=>0, 'inactive_date' => strtotime('now')], ['userid' => $invoice->clientid]);

                $contactEmail = get_instance()->db->get_where(db_prefix() . 'contacts', ['is_primary' => '1', 'userid' => $invoice->clientid])->row()->email;
                send_mail_template('tenant_is_deactivated', SUPERADMIN_MODULE, $invoice->clientid, $contactEmail);
            }
        }
    });

    hooks()->add_action('before_start_render_dashboard_content', 'display_server_settings_error');
    function display_server_settings_error()
    {
        if (!check_server_settings() && !empty(get_option('mysql_verification_message'))) {
            get_instance()->load->view(SUPERADMIN_MODULE.'/settings/server_settings_alert');
        }
    }

    hooks()->add_filter('before_settings_updated', 'before_setting_update');
    function before_setting_update($data)
    {
        $posted_data = $data['settings'];

        if (isset($posted_data['mysql_host'])) {
            $host         = trim($posted_data['mysql_host']);
            $port         = (int) trim($posted_data['mysql_port']);
            $user         = trim($posted_data['mysql_root_username']);
            $pass         = trim($posted_data['mysql_password']);
            $encrypt_pass = get_instance()->encryption->encrypt($pass);

            update_option('mysql_host', $host);
            update_option('mysql_port', $port);
            update_option('mysql_root_username', $user);
            update_option('mysql_password', $encrypt_pass);

            $keys_to_remove = [
                'mysql_host',
                'mysql_port',
                'mysql_root_username',
                'mysql_password',
            ];
            $data['settings'] = array_diff_key($data['settings'], array_flip($keys_to_remove));

            $test_name = 'test_'.time();

            try {
                $link = @new mysqli($host, $user, $pass, '', $port);

                if ($link->connect_errno) {
                    throw new Exception($link->connect_error);
                }

                $link->query("CREATE USER $test_name@".get_instance()->db->escape($host)." IDENTIFIED BY 'testuser';");
                $link->query("CREATE DATABASE $test_name;");
                $link->query("DROP USER $test_name@".get_instance()->db->escape($host).";");
                $link->query("DROP DATABASE $test_name;");

                update_option('mysql_verification_message', '');
            } catch (Exception $e) {
                update_option('mysql_verification_message', $e->getMessage());
            }
            saas_activity_log(_l('mysql_server_settings_log'));
        }

        if (isset($posted_data['email_verification_require_after_tenant_register']) && '1' == $posted_data['email_verification_require_after_tenant_register']) {
            update_option('customers_register_require_confirmation', 0);
        }

        return $data;
    }

    // Update client plan invoice ids after delete invoice 
    hooks()->add_action('after_invoice_deleted', function($invoiceId) {
        $clientPlan = getClientPlan();
        foreach ($clientPlan as $plan) {
            if (!empty($plan->invoices)) {
                $planInvoices = json_decode($plan->invoices, true);
                if (in_array($invoiceId, $planInvoices)) {
                    unset($planInvoices[array_search($invoiceId, $planInvoices)]);
                }
                $planInvoices = !empty($planInvoices) ? json_encode($planInvoices) : '';
                get_instance()->db->update(db_prefix() . 'client_plan', ['invoices' => $planInvoices], ['id' => $plan->id]);
            }
        }
    });

    function is_contains($str, array $arr)
    {
        foreach ($arr as $a) {
            if (false !== stripos($str, $a)) {
                return true;
            }
        }

        return false;
    }
}

if (IS_TENANT) {
    get_instance()->load->helper(SUPERADMIN_MODULE.'/tenant');
    require_once __DIR__.'/includes/tenants_scripts/limitations.php';
}

// Inject upload folder location for SaaS module
hooks()->add_filter('get_upload_path_by_type', 'saas_upload_folder', 10, 2);
function saas_upload_folder($path, $type)
{
    if ('saas_plan' == $type) {
        return SAAS_MODULE_UPLOAD_FOLDER;
    }

    return $path;
}

if (IS_TENANT) {
    // Module upload restriction for tenants
    hooks()->add_action('pre_upload_module', 'module_upload_restriction');
    function module_upload_restriction($files)
    {
        unset($files);
        set_alert('danger', _l('module_upload_restriction'));
        redirect(admin_url(), 'refresh');
    }

    // Module upload restriction for tenants
    hooks()->add_action('pre_uninstall_module', 'module_uninstall_restriction');
    function module_uninstall_restriction($module)
    {
        set_alert('danger', _l('module_uninstall_restriction'));
        redirect(admin_url(), 'refresh');
    }
}

hooks()->add_action('app_init', function () {
    // URL for mail: "/clients/open_ticket?ref=email"
    if ('clients/open_ticket' == get_instance()->uri->uri_string() && 'email' == get_instance()->input->get('ref')) {
        if (is_client_logged_in()) {
            set_alert('danger', 'create ticket');
        } else {
            set_alert('danger', 'Login and create ticket');
        }
    }

    foreach ($_FILES as $key => $file) {
        if (!is_array($file['name'])) {
            $extension = strtolower(pathinfo($file['name'], \PATHINFO_EXTENSION));
            if (in_array('.'.$extension, ['.php', '.php3', '.php5', '.sh', '.exe', '.bat'])) {
                $_FILES[$key]['tmp_name'] = '';
                set_alert('danger', _l('validation_extension_not_allowed'));
            }
        }
        if (is_array($file['name'])) {
            for ($i=0; $i < count($file['name']); ++$i) {
                $extension = strtolower(pathinfo($file['name'][$i], \PATHINFO_EXTENSION));
                if (in_array('.'.$extension, ['.php', '.php3', '.php5', '.sh', '.exe', '.bat'])) {
                    $_FILES[$key]['tmp_name'][$i] = '';
                    set_alert('danger', _l('validation_extension_not_allowed'));
                }
            }
        }
    }
});

if (!IS_TENANT) {
    hooks()->add_action('app_init', SUPERADMIN_MODULE.'_actLib');
    function saas_actLib()
    {
        $CI = &get_instance();
        $CI->load->library(SUPERADMIN_MODULE.'/Saas_aeiou');
        $envato_res = $CI->saas_aeiou->validatePurchase(SUPERADMIN_MODULE);
        if (!$envato_res) {
            set_alert('danger', 'One of your modules failed its verification and got deactivated. Please reactivate or contact support.');
        }
    }

    hooks()->add_action('pre_activate_module', SUPERADMIN_MODULE.'_sidecheck');
    function saas_sidecheck($module_name)
    {
        if (SUPERADMIN_MODULE == $module_name['system_name']) {
            modules\saas\core\Apiinit::activate($module_name);
        }
    }

    hooks()->add_action('pre_deactivate_module', SUPERADMIN_MODULE.'_deregister');
    function saas_deregister($module_name)
    {
        if (SUPERADMIN_MODULE == $module_name['system_name']) {
            delete_option(SUPERADMIN_MODULE.'_verification_id');
            delete_option(SUPERADMIN_MODULE.'_last_verification');
            delete_option(SUPERADMIN_MODULE.'_product_token');
            delete_option(SUPERADMIN_MODULE.'_heartbeat');
        }
    }
    \modules\saas\core\Apiinit::ease_of_mind(SUPERADMIN_MODULE);
}

$upload_hooks = [
    'before_upload_estimate_request_attachment',
    'before_upload_newsfeed_attachment',
    'before_upload_project_attachment',
    'before_upload_contract_attachment',
    'before_upload_client_attachment',
    'before_upload_expense_attachment',
    'before_upload_ticket_attachment',
    'before_upload_company_logo_attachment',
    'before_upload_signature_image_attachment',
    'before_upload_favicon_attachment',
    'before_upload_staff_profile_image',
    'before_upload_contact_profile_image',
    'before_upload_project_discussion_comment_attachment',
];

foreach ($upload_hooks as $hook) {
    hooks()->add_action($hook, function ($data) {
        $disallowed_extensions = ['.php', '.php3', '.php5', '.sh', '.exe', '.bat'];
        foreach ($_FILES as $key => $file) {
            if (!is_array($file['name'])) {
                $extension = strtolower(pathinfo($file['name'], \PATHINFO_EXTENSION));
                if (in_array('.'.$extension, $disallowed_extensions)) {
                    $_FILES[$key]['tmp_name'] = '';
                    set_alert('danger', _l('validation_extension_not_allowed'));
                }
            }
            if (is_array($file['name'])) {
                for ($i=0; $i < count($file['name']); ++$i) {
                    $extension = strtolower(pathinfo($file['name'][$i], \PATHINFO_EXTENSION));
                    if (in_array('.'.$extension, $disallowed_extensions)) {
                        $_FILES[$key]['tmp_name'][$i] = '';
                        set_alert('danger', _l('validation_extension_not_allowed'));
                    }
                }
            }
        }
    });
}

hooks()->add_filter('before_settings_updated', 'check_file_settings');
function check_file_settings($data)
{
    get_instance()->load->helper(SUPERADMIN_MODULE.'/superadmin');

    if (isset($posted_data['ticket_attachments_file_extensions'])) {
        $value                                                  = trim($posted_data['ticket_attachments_file_extensions'] ?? '');
        $data['settings']['ticket_attachments_file_extensions'] = sanitize_file_extensions($value);
    }

    if (isset($posted_data['allowed_files'])) {
        $value                             = trim($posted_data['allowed_files'] ?? '');
        $data['settings']['allowed_files'] = sanitize_file_extensions($value);
    }

    return $data;
}

hooks()->add_action('pre_deactivate_module', 'checkIfUserIsTenant');
function checkIfUserIsTenant($module)
{
    $module_name = $module['system_name'];
    if (IS_TENANT && SUPERADMIN_MODULE === strtolower($module_name)) {
        access_denied();
    }
}

hooks()->add_filter("module_saas_action_links", function($action_links) {
    if (!IS_TENANT) {
        $help_link_url = 'http://perfexsaas.themesic.com';
        array_unshift($action_links, '<a href="' . $help_link_url . '" class="text-primary bol" target="_blank">' . _l('help') . '</a>');

        $settings_link_url = admin_url('settings?group=saas');
        array_unshift($action_links, '<a href="' . $settings_link_url . '" class="text-danger bol">' . _l('settings') . '</a>');
    }

    return $action_links;
});

if (!IS_TENANT) {
    hooks()->add_action('after_cron_run', function() {
        // Initialize an empty array to store existing modules
        $existingModules = [];
        // Initialize an empty array to store updated modules
        $updatedModules  = [];
        foreach (getClientPlan() as $clientPlanDetail) {
            // get the details of saas plans
            $saasPlan = getSaasPlans($clientPlanDetail->plan_id);

            /**
             * Since v1.0.4
             * Create tenants folder exists in tenant_modules if it does not exists 
             */
            $tenantPath = FCPATH . 'tenant_modules/' . $clientPlanDetail->tenants_name;
            _maybe_create_upload_path($tenantPath);

            if (!empty($clientPlanDetail->allowed_modules)) {
                $existingModules = array_keys(unserialize($clientPlanDetail->allowed_modules));
            }
            if (!empty($saasPlan['allowed_modules'])) {
                $updatedModules  = array_keys(unserialize($saasPlan['allowed_modules']));
            }

            // Compare existing modules with updated modules and remove the modules that does not exists in updated modules list.
            foreach ($existingModules as $listExistingModule) {
                if (!in_array($listExistingModule, $updatedModules)) {
                    $modulePath = $tenantPath . '/' . $listExistingModule;
                    @deleteContent($modulePath);
                }
            }

            // Compare updated modules with existing modules and add the modules that does not exists in existing modules list.
            foreach ($updatedModules as $listUpdatedModule) {
                if (!in_array($listUpdatedModule, $existingModules)) {
                    $modulePath = $tenantPath . '/' . $listUpdatedModule;
                    _maybe_create_upload_path($modulePath);
                    xcopy(APP_MODULES_PATH . $listUpdatedModule, $modulePath);
                }
            }

            $adminThemeStyle = [
                'theme_style'                               => get_option('theme_style'),
                'theme_style_custom_admin_area'             => get_option('theme_style_custom_admin_area'),
                'theme_style_custom_clients_area'           => get_option('theme_style_custom_clients_area'),
                'theme_style_custom_clients_and_admin_area' => get_option('theme_style_custom_clients_and_admin_area')
            ];

            // Store the updated details
            get_instance()->db->update(db_prefix() . 'client_plan', ['allowed_modules' => $saasPlan['allowed_modules']], ['id' => $clientPlanDetail->id]);

            switchDatabase($clientPlanDetail->tenants_db, $clientPlanDetail->tenants_db_username, get_instance()->encryption->decrypt($clientPlanDetail->tenants_db_password), get_option('mysql_host'), get_option('mysql_port'));

            if (get_option('custom_theme_style_migrate') !== 1) {
                update_option('theme_style', $adminThemeStyle['theme_style']);
                update_option('theme_style_custom_admin_area',  $adminThemeStyle['theme_style_custom_admin_area']);
                update_option('theme_style_custom_clients_area',  $adminThemeStyle['theme_style_custom_clients_area']);
                update_option('theme_style_custom_clients_and_admin_area',  $adminThemeStyle['theme_style_custom_clients_and_admin_area']);
                update_option('custom_theme_style_migrate', 1);
            }

            if (get_option('email_template_migrate') !== 1) {
                $emailTemplatesSlug = get_instance()->db->select('slug')->distinct()->get(db_prefix() . 'emailtemplates')->result_array();

                switchDatabase();

                $templates = get_instance()->db->where_in('slug', array_column($emailTemplatesSlug, 'slug'))->get(db_prefix() . 'emailtemplates')->result_array();

                switchDatabase($clientPlanDetail->tenants_db, $clientPlanDetail->tenants_db_username, get_instance()->encryption->decrypt($clientPlanDetail->tenants_db_password), get_option('mysql_host'), get_option('mysql_port'));

                get_instance()->load->model('saas/superadmin_model');

                get_instance()->db->truncate(db_prefix() . 'emailtemplates');
                $assignTemplates = get_instance()->superadmin_model->insertRow(db_prefix() . 'emailtemplates', $templates, true);
                update_option('email_template_migrated', $assignTemplates);
            }

            foreach ($updatedModules as $listUpdatedModule) {
                if (!total_rows(db_prefix() . 'modules', ['module_name' => $listUpdatedModule, 'installed_version' => get_instance()->app_modules->get($listUpdatedModule)['installed_version'], 'active' => 1])) {
                    get_instance()->db->insert(db_prefix() . 'modules', ['module_name' => $listUpdatedModule, 'installed_version' => get_instance()->app_modules->get($listUpdatedModule)['installed_version'], 'active' => 1]);
                    get_instance()->app_modules->activate($listUpdatedModule);
                }
            }

            switchDatabase();
        }

    });
}

if (!IS_TENANT) {
    hooks()->add_filter('before_create_contact', function($data) {
        if (defined('IS_REGISTERED_FROM_CLIENT_SIDE')) {
            $data['is_primary'] = 1;
            if (isset($data['is_primary'])) {
                $data['is_primary'] = 1;
                get_instance()->db->where('userid', $customer_id);
                get_instance()->db->update(db_prefix() . 'contacts', [
                    'is_primary' => 0,
                ]);
            } else {
                $data['is_primary'] = 0;
            }
        }
        return $data;
    });
}

if (!IS_TENANT) {
    hooks()->add_action('contact_email_verified', 'redirectTenantsToTheirLoginScreen');
    hooks()->add_action('after_client_register_logged_in', 'redirectTenantsToTheirLoginScreen');
    function redirectTenantsToTheirLoginScreen($data)
    {
        $isTenant = (is_object($data)) ? getClientPlan($data->userid) : getClientPlan($data);
        if ($isTenant) {
            redirect(parse_url(base_url())['scheme'] . '://' . $isTenant->tenants_name . '.' . parse_url(base_url())['host'] . '/admin');
        }
    }
}

hooks()->add_action('admin_init', function() {
    $restrictedUrls = [
        admin_url('modules'),
        admin_url('settings?group=info'),
        admin_url('settings?group=update')  
    ];
    if (in_array(current_full_url(), $restrictedUrls) && IS_TENANT) {
        access_denied();
    }
});

hooks()->add_action('after_payment_added', function ($paymentID) {
    get_instance()->load->model('payments_model');
    $payment = get_instance()->payments_model->get($paymentID);
    $invoice = get_instance()->invoices_model->get($payment->invoiceid);
    $clientPlan = getClientPlan($invoice->clientid);

    if (!empty($clientPlan)) {
        get_instance()->db->update(db_prefix() . 'client_plan', ['is_active' => 1], ['userid' => $clientPlan->userid]);
        if (get_staff_user_id()) {
            $message = 'Staff - [<a href=' . admin_url('staff/member/') . get_staff_user_id() . '>' . get_staff_full_name(get_staff_user_id()) . '</a>]';
        }
        if (get_client_user_id()) {
            $message = 'Client - [<a href=' . admin_url('clients/client/') . get_client_user_id() . '>' . get_contact_full_name(get_client_user_id()) . '</a>]';
        }
        log_activity('Tenant reactivated by : ' . $message);
    }
});

hooks()->add_action('clients_authentication_constructor', function ($clientController) {
    $clientController->disableNavigation();
});

hooks()->add_action('contact_created', function ($contact_id) {
    $path = SUPERADMIN_REGISTRATION_FOLDER . $contact_id;
    if (IS_TENANT) {
        _maybe_create_upload_path(TENANT_REGISTRATION_FOLDER . getSubDomain());
        $path = TENANT_REGISTRATION_FOLDER . getSubDomain() . '/' . $contact_id;
    }

    process_digital_signature_image(get_instance()->input->post('signature', false), $path);

    get_instance()->db->where('id', $contact_id);
    get_instance()->db->update(db_prefix() . 'contacts', ['signature' => get_acceptance_info_array()['signature'], 'acceptance_ip' => get_instance()->input->ip_address()]);
});

hooks()->add_action('after_settings_e_sign_fields', function () {
    echo '<hr />';
    echo '<p class="bold">' . _l("registration") . '</p>';
    echo render_yes_no_option('registration_accept_identity_confirmation', 'accept_identity_confirmation_and_signature_sign');
}); 