<?php

defined('BASEPATH') || exit('No direct script access allowed');

use Carbon\Carbon;

if (!function_exists('installSuperadminEmailTemplates')) {
    /**
     * Add email templates for superadmin.
     */
    function installSuperadminEmailTemplates()
    {
        if (!total_rows(db_prefix() . 'emailtemplates', ['slug' => 'tenant-is-deactivated'])) {
            create_saas_email_template('Tenant is deactivated', 'Email Template Content', 'superadmin', 'Tenant is deactivated', 'tenant-is-deactivated');
        }
        if (!total_rows(db_prefix() . 'emailtemplates', ['slug' => 'onboarding-email'])) {
            create_saas_email_template('Onboarding Email', 'Email Template Content', 'superadmin', 'Onboarding Email', 'onboarding-email');
        }
    }
}

if (!function_exists('create_saas_email_template')) {
    function create_saas_email_template($subject, $message, $type, $name, $slug, $active = 1)
    {
        if (total_rows('emailtemplates', ['slug' => $slug]) > 0) {
            return false;
        }

        $data['subject']   = $subject;
        $data['message']   = $message;
        $data['type']      = $type;
        $data['name']      = $name;
        $data['slug']      = $slug;
        $data['language']  = 'english';
        $data['active']    = $active;
        $data['plaintext'] = 0;
        $data['order']     = 0;
        $data['fromname']  = '{companyname} | CRM';

        $CI                = &get_instance();
        $CI->load->model('emails_model');

        return $CI->emails_model->add_template($data);
    }
}

if (!function_exists('switchDatabase')) {
    /**
     * Switch database.
     *
     * @param string $group    Database name
     * @param mixed  $username
     * @param mixed  $password
     * @param mixed  $hostname
     * @param mixed  $port
     */
    function switchDatabase($group = 'super_admin', $username = APP_DB_USERNAME, $password = APP_DB_PASSWORD, $hostname = APP_DB_HOSTNAME, $port = '')
    {
        $workspace_config = [
            'hostname', 'username', 'password', 'database', 'dbdriver', 'dbprefix', 'db_debug', 'char_set', 'dbcollat', 'encrypt',
        ];
        foreach ($workspace_config as $value) {
            $database_array[$value] = get_instance()->db->{$value};
        }
        $database_array['database'] = $group;
        $database_array['hostname'] = $hostname;
        $database_array['username'] = $username;
        $database_array['password'] = $password;

        if (!empty($port)) {
            $database_array['port'] = $port;
        }
        if ('super_admin' == $group) {
            $database_array['dbprefix'] = db_prefix();
            $database_array['database'] = APP_DB_NAME;
            unset($database_array['port']);
        }
        get_instance()->db = get_instance()->load->database($database_array, true);
    }
}

if (!function_exists('getTenantDbNameByClientID')) {
    /**
     * Get tenant's database name by client ID.
     *
     * @param string $clientid Client ID
     *
     * @return mixed Client's database name | false
     */
    function getTenantDbNameByClientID($clientid)
    {
        get_instance()->load->dbutil();
        if (!empty($clientid) && 0 != $clientid) {
            $client_data = getClientPlan($clientid);

            $host = get_option('mysql_host');
            $port = get_option('mysql_port');
            $user = get_option('mysql_root_username');
            get_instance()->load->library('encryption');
            $pass = get_instance()->encryption->decrypt(get_option('mysql_password'));
            switchDatabase('', $user, $pass, $host, $port);

            if (get_instance()->dbutil->database_exists($client_data->tenants_db)) {
                switchDatabase();

                return $client_data->tenants_db;
            }
            switchDatabase();
        }

        return false;
    }
}

if (!function_exists('getTotalCountOfClientContact')) {
    /**
     * Get total no. of company's contact.
     *
     * @param string $id Client ID
     *
     * @return mixed Total no. of contacts of a company/client
     */
    function getTotalCountOfClientContact($id)
    {
        $count = get_instance()->db->where('userid', $id)->count_all_results(db_prefix() . 'contacts');

        return $count;
    }
}

if (!function_exists('getAllTenantsWidgetData')) {
    /**
     * Get the count of (projects, client, staff) etc.. for specific tenants.
     *
     * @param string $clientid Client ID
     *
     * @return array Count of data such as projects, client, staff etc...
     */
    function getAllTenantsWidgetData($clientid)
    {
        $count = [];
        get_instance()->load->dbutil();

        $client_data           = getClientPlan($clientid);
        $tenant_db             = $client_data->tenants_db;
        $tenants_db_username   = $client_data->tenants_db_username;
        $tenants_db_password   = get_instance()->encryption->decrypt($client_data->tenants_db_password);
        if (get_instance()->dbutil->database_exists($tenant_db)) {
            switchDatabase($tenant_db, $tenants_db_username, $tenants_db_password, get_option('mysql_host'), get_option('mysql_port'));
            $count['project']  = get_instance()->db->count_all_results('tblprojects');
            $count['client']   = get_instance()->db->count_all_results('tblclients');
            $count['staff']    = get_instance()->db->count_all_results('tblstaff');
            $count['task']     = get_instance()->db->count_all_results('tbltasks');
            $count['invoice']  = get_instance()->db->count_all_results('tblinvoices');
            $count['proposal'] = get_instance()->db->count_all_results('tblproposals');
            $count['contract'] = get_instance()->db->count_all_results('tblcontracts');
            $count['lead']     = get_instance()->db->count_all_results('tblleads');
            switchDatabase();
        }

        return $count;
    }
}

if (!function_exists('getAllTenantsDashboardWidgetData')) {
    /**
     * Get the count of (projects, client, staff) etc.. for all tenants.
     *
     * @return array Count of data such as projects, client, staff etc...
     */
    function getAllTenantsDashboardWidgetData()
    {
        $count = [];

        get_instance()->load->model('clients_model');
        get_instance()->load->dbutil();
        $client_data = get_instance()->clients_model->get();

        if (!empty($client_data)) {
            foreach ($client_data as $key => $value) {
                $client_plan         = getClientPlan($value['userid']);
                $tenants_db_password = get_instance()->encryption->decrypt($client_plan->tenants_db_password);
                // Check if database is not exist then we should avoid so error will be skip
                if ($client_plan && getTenantDbNameByClientID($value['userid'])) {
                    switchDatabase($client_plan->tenants_db, $client_plan->tenants_db_username, $tenants_db_password, get_option('mysql_host'), get_option('mysql_port'));
                    $count['project'][]  = get_instance()->db->count_all_results('tblprojects');
                    $count['client'][]   = get_instance()->db->count_all_results('tblclients');
                    $count['staff'][]    = get_instance()->db->count_all_results('tblstaff');
                    $count['task'][]     = get_instance()->db->count_all_results('tbltasks');
                    $count['invoice'][]  = get_instance()->db->count_all_results('tblinvoices');
                    $count['proposal'][] = get_instance()->db->count_all_results('tblproposals');
                    $count['contract'][] = get_instance()->db->count_all_results('tblcontracts');
                    $count['lead'][]     = get_instance()->db->count_all_results('tblleads');
                    switchDatabase();
                }
            }
        }

        return $count;
    }
}

if (!function_exists('getTenantLastActivity')) {
    /**
     * Get the tenants last activity.
     *
     * @param string $clientid Client ID
     *
     * @return array Returns all the activites of specific tenant
     */
    function getTenantLastActivity($clientid)
    {
        $data = [];
        get_instance()->load->dbutil();

        $client_plan = getClientPlan($clientid);
        if (getTenantDbNameByClientID($clientid)) {
            $tenant_password = get_instance()->encryption->decrypt($client_plan->tenants_db_password);
            switchDatabase($client_plan->tenants_db, $client_plan->tenants_db_username, $tenant_password, get_option('mysql_host'), get_option('mysql_port'));
            $data['total_activities'] = get_instance()->custom_model->getRowsSorted('tblactivity_log', [], [], 'date', 'DESC', 10);
            switchDatabase();
        }

        return $data;
    }
}

if (!function_exists('getPlanExpiryDate')) {
    /**
     * Get plan expiry date.
     *
     * @param string $created_date Creation date
     * @param string $plan_name    Name of the plan
     * @param mixed  $trial_days
     *
     * @return string Plan expiry date
     */
    function getPlanExpiryDate($created_date, $trial_days)
    {
        $date = \Carbon\Carbon::create($created_date);
        $date->addDays($trial_days);

        return $date->format('Y-m-d H:i:s');
    }
}

if (!function_exists('getRemainingDays')) {
    /**
     * Get reamining days.
     *
     * @param string $duedate Duedate
     *
     * @return string Returns the difference in days
     */
    function getRemainingDays($duedate)
    {
        return Carbon::parse($duedate)->diffInDays(null, false);
    }
}

if (!function_exists('handlePlanImageUpload')) {
    /**
     * Handle plan image upload and update the database if successful.
     *
     * @param int $plan_image_id the ID of the plan image
     *
     * @return bool whether the upload was successful
     */
    function handlePlanImageUpload($plan_image_id)
    {
        if (!empty($_FILES['plan_image']['name'])) {
            $path        = get_upload_path_by_type('saas_plan');
            $tmpFilePath = $_FILES['plan_image']['tmp_name'];
            if (!empty($tmpFilePath)) {
                $path_parts  = pathinfo($_FILES['plan_image']['name']);
                $extension   = strtolower($path_parts['extension']);
                $filename    = 'plan_image_' . $plan_image_id . '.' . $extension;
                $newFilePath = $path . $filename;
                _maybe_create_upload_path($path);
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    get_instance()->saas_model->edit_plan_image(['plan_image' => $filename], $plan_image_id);

                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('getSaasPlans')) {
    /**
     * Get saas plan details.
     *
     * @param string $id Saas plan ID
     *
     * @return array Saas plan details
     */
    function getSaasPlans($id = '')
    {
        get_instance()->load->model(SUPERADMIN_MODULE . '/superadmin_model');
        $plans = get_instance()->superadmin_model->get_saas_plan($id);

        return json_decode(json_encode($plans), true); // object to array
    }
}

if (!function_exists('getClientPlan')) {
    /**
     * Get the client plan for a given client ID.
     *
     * @param int|null $clientId The ID of the client. If null, return all client plans.
     *
     * @return array|object|null the client plan data in an array, or null if no plans found
     */
    function getClientPlan($clientId = null)
    {
        $ci = get_instance();
        $ci->load->model(SUPERADMIN_MODULE . '/superadmin_model');
        $where = [];
        if (!empty($clientId)) {
            $where = ['client_plan.userid' => $clientId];
        }
        $tenants = get_instance()->superadmin_model->getRowsWhereJoin(db_prefix() . 'client_plan', $where, ['clients'], ['client_plan.userid = clients.userid']);

        if (!empty($clientId)) {
            return !empty($tenants) ? $tenants[array_key_first($tenants)] : []; // Return the first item as an array
        }

        return $tenants ?: null;
    }
}

if (!function_exists('check_server_settings')) {
    /**
     * [check_server_settings this function will check mysql server settings are stored or not].
     *
     * @return [bool] [if settings are found then return true, otherwise false]
     */
    function check_server_settings()
    {
        $settings = ['mysql_host', 'mysql_port', 'mysql_root_username', 'mysql_password'];
        foreach ($settings as $setting) {
            if (empty(get_option($setting))) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('getDomain')) {
    /**
     * [getDomain this function will return main domain name only].
     *
     * @return [string] [main domain]
     */
    function getDomain()
    {
        return preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", '$1', get_instance()->config->slash_item('base_url'));
    }
}

if (!function_exists('randomPassword')) {
    function randomPassword()
    {
        $alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass        = []; // remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; // put the length -1 in cache
        for ($i = 0; $i < 12; ++$i) {
            $n      = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode('', $pass); // turn the array into a string
    }
}

if (!function_exists('getStatsForSuperadmin')) {
    function getStatsForSuperadmin()
    {
        // Count the number of rows in the client_plan table
        $total_subs = get_instance()->db->where('is_deleted', 0)->count_all_results(db_prefix() . 'client_plan');

        // Count the number of active and inactive subscriptions
        $active_subs   = get_instance()->db->where(['is_active' => '1', 'is_deleted' => 0])->count_all_results(db_prefix() . 'client_plan');
        $inactive_subs = $total_subs - $active_subs;

        // Get all client invoices
        $clientPlan = getClientPlan();

        $invoices_awaiting_payment['total'] = 0;

        if ($clientPlan) {
            $clientInvoices = array_filter(array_column($clientPlan, 'invoices'));

            $invoices_model = get_instance()->load->model('invoices_model');
            $allInvoice     = [];
            foreach ($clientInvoices as $firstInvoice) {
                $firstInvoice = json_decode($firstInvoice);
                $planInvoices   = get_instance()->invoices_model->get("", db_prefix() . 'invoices' . ".id IN(" . implode(",", $firstInvoice) . ")");
                $allInvoice       = array_merge($allInvoice, $planInvoices);
            }

            $availableRecords = array_filter($allInvoice);

            if (!empty($availableRecords)) {
                // Count the number of paid and awaiting invoices
                $paidInvoices = array_filter($availableRecords, function ($invoice) {
                    return 2 == $invoice['status'];
                });

                $invoices_awaiting_payment = array_filter($availableRecords, function ($invoice) {
                    return !in_array($invoice['status'], [2, 5, 6]);
                });

                // Count the number of expiring subscriptions
                $startDate             = Carbon::now()->startOfMonth();
                $endDate               = Carbon::now()->endOfMonth();
                $invoice_to_be_created = 0;

                foreach ($availableRecords as $invoice) {
                    $last_recurring_date = $invoice['last_recurring_date'] ? date('Y-m-d', strtotime($invoice['last_recurring_date'])) : date('Y-m-d', strtotime($invoice['date']));
                    $re_create_at        = date('Y-m-d', strtotime('+' . $invoice['recurring'] . ' ' . strtoupper($invoice['recurring_type'] ?? ''), strtotime($last_recurring_date)));
                    $date                = \Carbon\Carbon::create($re_create_at);
                    $check               = $date->between($startDate, $endDate);
                    if ($check) {
                        ++$invoice_to_be_created;
                    }
                }
            }

            if (isset($paidInvoices)) {
                // Calculate sales revenue
                $sales_revenue = app_format_money(array_sum(array_column($paidInvoices, 'total')), get_base_currency()->name);
            }
        }

        return [
            'total_subs'                => $total_subs,
            'active_subs'               => $active_subs,
            'inactive_subs'             => $inactive_subs,
            'expiring_subs'             => $invoice_to_be_created ?? 0,
            'sales_revenue'             => $sales_revenue ?? 0,
            'invoices_awaiting_payment' => app_format_money(array_sum(array_column($invoices_awaiting_payment, 'total')), get_base_currency()->name),
        ];
    }
}

/*
 * Recursively removes a directory if it exists.
 *
 * @param string $dir_path The path to the directory.
 * @param string $dir_name The name of the directory to remove.
 *
 * @return void
 */
if (!function_exists('remove_tenant_directory')) {
    function remove_tenant_directory(string $dir_path, string $dir_name)
    {
        // Check if the directory exists before trying to remove it
        if (!is_dir($dir_path . \DIRECTORY_SEPARATOR . $dir_name)) {
            return;
        }
        $dir_contents = directory_map($dir_path, 1);
        foreach ($dir_contents as $content) {
            if (in_array($content, ['.', '..'])) {
                continue;
            }
            $content_path = $dir_path . \DIRECTORY_SEPARATOR . $content;
            if (is_dir($content_path)) {
                remove_tenant_directory($content_path, $dir_name);
            }
            if (is_dir($content_path) && $content == $dir_name) {
                delete_dir($content_path);
            }
        }
    }
}

/*
 * Inserts a new log record into the saas_activity_log database table.
 * If the client is not logged in but a staff user is logged in, the staff user's ID is added to the log data.
 * The recorded_at field is set to the current date and time.
 *
 * @param mixed $data Optional. An associative array of data to log.
 * @return bool True on success, false on failure.
 */

if (!function_exists('saas_activity_log')) {
    function saas_activity_log($description, $staffid = null)
    {
        $log = [
            'description' => $description,
            'recorded_at' => date('Y-m-d H:i:s'),
        ];
        if (!defined('CRON')) {
            if (null != $staffid && is_numeric($staffid)) {
                $log['staffid'] = get_staff_full_name($staffid);
            } else {
                if (!is_client_logged_in()) {
                    if (is_staff_logged_in()) {
                        $log['staffid'] = get_staff_full_name(get_staff_user_id());
                    } else {
                        $log['staffid'] = null;
                    }
                } else {
                    $log['staffid'] = get_contact_full_name(get_contact_user_id());
                }
            }
        } else {
            // manually invoked cron
            if (is_staff_logged_in()) {
                $log['staffid'] = get_staff_full_name(get_staff_user_id());
            } else {
                $log['staffid'] = '[CRON]';
            }
        }

        return get_instance()->db->insert(db_prefix() . 'saas_activity_log', $log);
    }
}

if (!function_exists('sanitize_file_extensions')) {
    function sanitize_file_extensions($value)
    {
        $res        = in_array($value, ['.php', '.php3', '.php5', '.sh', '.exe', '.bat']);
        $finalValue = preg_replace('/\.(php|php3|php5|sh|exe|bat),?/', '', $value);

        return $res ? trim(str_replace(',,', ',', $finalValue), ',') : $value;
    }
}

if (!function_exists('listChangeSaaSPlans')) {
    function listChangeSaaSPlans($clientID)
    {
        $saasPlan = get_instance()->db->where_not_in(db_prefix() . 'plan_management.id', 'SELECT plan_id FROM ' . db_prefix() . 'client_plan WHERE userid = ' . $clientID, FALSE)->get(db_prefix() . 'plan_management')->result_array();

        return (!empty($saasPlan)) ? $saasPlan : [];
    }
}

if (!function_exists('deleteContent')) {
    function deleteContent($path)
    {
        try {
            $iterator = new DirectoryIterator($path);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isDot()) continue;
                if ($fileinfo->isDir()) {
                    if (deleteContent($fileinfo->getPathname()))
                        @rmdir($fileinfo->getPathname());
                }
                if ($fileinfo->isFile()) {
                    @unlink($fileinfo->getPathname());
                }
            }
            if (rmdir($path)) {
                return true;
            }
        } catch (Exception $e) {
            // write log
            return false;
        }
        return true;
    }
}

/**
 * Get the path and url of the theme.
 * Path first the http url.
 *
 * @return array Path first the http url.
 */
if (!function_exists('get_theme_path_url')) {
    function get_theme_path_url()
    {
        $themePath = module_dir_path(SUPERADMIN_MODULE, 'public/landingpage/themes');
        $themeUrl = module_dir_url(SUPERADMIN_MODULE, 'public/landingpage/themes');
        return [$themePath, $themeUrl];
    }
}

/**
 * Get all html pages in the landing pages theme and custom theme folder.
 * The use can select which page to use as the landing page.
 *
 * @return array
 */
if (!function_exists('get_landing_pages')) {
    function get_landing_pages()
    {
        $pages = [];
        list($themePath, $themeUrl) = get_theme_path_url();
        $htmlFiles = [];
        $patterns = [$themePath . '/*/*.html', $themePath . '/*.html'];
        // Get all files matching the patterns
        foreach ($patterns as $pattern) {
            $htmlFiles = array_merge($htmlFiles, glob($pattern));
        }
        //$htmlFiles = glob($themePath . '/*\/*.html');
        $activeTheme = get_option('perfex_saas_landing_page_theme');
        $activeThemeIndex = 0;
        foreach ($htmlFiles as $index => $file) {
            if (stripos($file, 'new-page-blank-template.html') !== false) continue; //skip template files
            $pathInfo = pathinfo($file);
            $extension = $pathInfo['extension'];
            if ($extension !== 'html') continue;

            $basePath = str_ireplace($themePath, '', $pathInfo['dirname']);
            $realFilename = $filename = $pathInfo['filename'];
            $folder = preg_replace('@/.+?$@', '', $basePath);
            $subfolder = preg_replace('@^.+?/@', '', $pathInfo['dirname']);
            if ($subfolder) {
                if ($filename == 'index')
                    $filename = basename($subfolder);
                else if ($folder !== basename($subfolder))
                    $filename = basename($subfolder) . '/' . $filename;
            }


            $url = str_ireplace($themePath, $themeUrl, $pathInfo['dirname'] . '/' . $pathInfo['basename']);

            $page = [
                "name" => ucfirst($filename),
                "file" => str_ireplace($themePath, '', $file),
                "title" => ucfirst($filename),
                "url" => $url,
                "folder" => empty($folder) ? 'themes' : $folder,
                "base_path_url" => str_ireplace(basename($realFilename) . '.' . $extension, '', $url)
            ];
            $pages[$index] = $page;

            if ($activeTheme == $page['file'])
                $activeThemeIndex = $index;
        }

        if ($activeThemeIndex) {
            // sort make acitve theme first one 
            $activeTheme = $pages[$activeThemeIndex];
            unset($pages[$activeThemeIndex]);
            $pages = array_merge([$activeTheme], $pages);
        }

        return $pages;
    }
}

/**
 * Generate a form label hint.
 *
 * @param string $hint_lang_key  The language key for the hint text.
 * @param string|string[] $params The language key sprint_f variables.
 * @return string                The HTML code for the form label hint.
 */
if (!function_exists('perfex_saas_form_label_hint')) {
    function perfex_saas_form_label_hint($hint_lang_key, $params = null)
    {
        return '<span class="tw-ml-2" data-toggle="tooltip" data-title="' . _l($hint_lang_key, $params) . '"><i class="fa fa-question-circle"></i></span>';
    }
}

if(!function_exists('starts_with')){

	function starts_with($haystack, $needles){
		foreach( (array)$needles as $n){
			if($n !== '' && stripos($haystack, $n) === 0){
				return true;
			}
		}

		return false;
	}
}

/**
 * This file contain php8+ polyfill methods
 */

if (!function_exists('str_starts_with')) {

    function str_starts_with(string $haystack, string $needle): bool
    {
        return 0 === strncmp($haystack, $needle, \strlen($needle));
    }
}

if (!function_exists('str_ends_with')) {

    function str_ends_with(string $haystack, string $needle): bool
    {
        if ('' === $needle || $needle === $haystack) {
            return true;
        }

        if ('' === $haystack) {
            return false;
        }

        $needleLength = \strlen($needle);

        return $needleLength <= \strlen($haystack) && 0 === substr_compare($haystack, $needle, -$needleLength);
    }
}

/**
 * Remove directory recursively including hidder directories and files.
 * This is preferable to perfex delete_dir function as that does not handle hidden directories well.
 *
 * @param      string  $target  The directory to remove
 * @return     bool
 */
function perfex_saas_remove_dir($target)
{
    try {
        if (is_dir($target)) {
            $dir = new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $filename => $file) {
                if (is_file($filename)) {
                    unlink($filename);
                } else {
                    perfex_saas_remove_dir($filename);
                }
            }
            return rmdir($target); // Now remove target folder
        }
    } catch (\Exception $e) {
    }
    return false;
}



/* End of file "tenants_helper.".php */
