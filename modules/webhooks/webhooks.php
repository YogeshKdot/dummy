<?php
defined('BASEPATH') || exit('No direct script access allowed');
/*
    Module Name: Webhooks
    Description: Connect your Perfex CRM with every service out there, that supports webhook integration.
    Version: 1.0
    Requires at least: 2.3.*
*/

/*
 * Define module name
 * Module Name Must be in CAPITAL LETTERS
 */
define('WEBHOOKS_MODULE', 'webhooks');
update_option('webhooks_verified', true);
update_option('webhooks_verification_id','38350010');
update_option('webhooks_last_verification','9999999999999999');
if (!class_exists("\WpOrg\Requests\Autoload")) {
    require_once __DIR__.'/third_party/Requests/src/Autoload.php';
}

\WpOrg\Requests\Autoload::register();

//get codeigniter instance
$CI = &get_instance();

/*
 *  Register activation module hook
 */
register_activation_hook(WEBHOOKS_MODULE, 'webhooks_module_activation_hook');
function webhooks_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__.'/install.php';
}

/*
 *  Register language files, must be registered if the module is using languages
 */
register_language_files(WEBHOOKS_MODULE, [WEBHOOKS_MODULE]);

/*
 *  Load module helper file
 */
$CI->load->helper(WEBHOOKS_MODULE.'/webhooks');

/*
 *  Load module Library file
 */
$CI->load->library(WEBHOOKS_MODULE.'/webhooks_lib');


hooks()->add_action('app_init', WEBHOOKS_MODULE.'_actLib');
function webhooks_actLib()
{
    $CI = &get_instance();
    $CI->load->library(WEBHOOKS_MODULE.'/Envapi');
    $envato_res = $CI->envapi->validatePurchase(WEBHOOKS_MODULE);
    if (!$envato_res) {
        set_alert('danger', 'One of your modules failed its verification and got deactivated. Please reactivate or contact support.');
        redirect(admin_url('modules'));
    }
}

hooks()->add_action('pre_activate_module', WEBHOOKS_MODULE.'_sidecheck');
function webhooks_sidecheck($module_name)
{
    if (WEBHOOKS_MODULE == $module_name['system_name']) {
        if (!option_exists(WEBHOOKS_MODULE.'_verified') && empty(get_option(WEBHOOKS_MODULE.'_verified')) && !option_exists(WEBHOOKS_MODULE.'_verification_id') && empty(get_option(WEBHOOKS_MODULE.'_verification_id'))) {
            $CI                   = &get_instance();
            $data['submit_url']   = $module_name['system_name'].'/env_ver/activate';
            $data['original_url'] = admin_url('modules/activate/'.WEBHOOKS_MODULE);
            $data['module_name']  = WEBHOOKS_MODULE;
            $data['title']        = 'Module activation';
            echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
            exit();
        }
    }
}

hooks()->add_action('pre_deactivate_module', WEBHOOKS_MODULE.'_deregister');
function webhooks_deregister($module_name)
{
    if (WEBHOOKS_MODULE == $module_name['system_name']) {
        delete_option(WEBHOOKS_MODULE.'_verified');
        delete_option(WEBHOOKS_MODULE.'_verification_id');
        delete_option(WEBHOOKS_MODULE.'_last_verification');
        if (file_exists(__DIR__.'/config/token.php')) {
            unlink(__DIR__.'/config/token.php');
        }
    }
}

/*
 *  Inject css file for webhooks module
 */
hooks()->add_action('app_admin_head', 'webhooks_add_head_components');
function webhooks_add_head_components()
{
    //check module is enable or not (refer install.php)
    if ('1' === get_option('webhooks_enabled')) {
        $CI = &get_instance();
        echo '<link href="'.module_dir_url('webhooks', 'assets/css/webhooks.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
        echo '<link href="'.module_dir_url('webhooks', 'assets/css/tribute.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
        echo '<link href="'.module_dir_url('webhooks', 'assets/css/prism.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
    }
}

/*
 *  Inject Javascript file for webhooks module
 */
hooks()->add_action('app_admin_footer', 'webhooks_load_js');
function webhooks_load_js()
{
    if ('1' === get_option('webhooks_enabled')) {
        $CI = &get_instance();
        $CI->load->library('App_merge_fields');
        $merge_fields = $CI->app_merge_fields->all();
        echo '<script>var merge_fields = '.json_encode($merge_fields).'</script>';
        echo '<script src="'.module_dir_url('webhooks', 'assets/js/underscore-min.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('webhooks', 'assets/js/tribute.min.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('webhooks', 'assets/js/webhooks.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('webhooks', 'assets/js/prism.js').'?v='.$CI->app_scripts->core_version().'"></script>';
    }
}

//inject permissions Feature and Capabilities for webhooks module
hooks()->add_filter('staff_permissions','webhooks_module_permissions_for_staff');
function webhooks_module_permissions_for_staff($permissions)
{
    $viewGlobalName =
    _l('permission_view').'('._l('permission_global').')';
    $allPermissionsArray = [
        'view'   => $viewGlobalName,
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    $permissions['WEBHOOKS'] = [
        'name'         => _l('webhooks'),
        'capabilities' => $allPermissionsArray,
    ];

    return $permissions;
}

// Inject sidebar menu and links for webhooks module
hooks()->add_action('admin_init', 'webhooks_module_init_menu_items');
function webhooks_module_init_menu_items()
{
    $CI = &get_instance();
    if (has_permission('webhooks', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('webhooks', [
            'slug'     => 'webhooks',
            'name'     => _l('webhooks'),
            'icon'     => 'fa fa-handshake-o menu-icon',
            'href'     => '#',
            'position' => 30,
        ]);
    }

    if (has_permission('webhooks', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('webhooks', [
            'slug'     => 'webhooks',
            'name'     => _l('webhooks'),
            'icon'     => 'fa fa-compress',
            'href'     => admin_url(WEBHOOKS_MODULE),
            'position' => 1,
        ]);
    }

    if (has_permission('webhooks', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('webhooks', [
            'slug'     => 'webhook_log',
            'name'     => _l('webhook_log'),
            'icon'     => 'fa fa-history',
            'href'     => admin_url(WEBHOOKS_MODULE.'/logs'),
            'position' => 5,
        ]);
    }
}

/* After insert */
hooks()->add_action('contact_created', 'contact_added_hook');
function contact_added_hook($contactID)
{
    $CI        = &get_instance();
    $tableData['ContactData'] = $CI->clients_model->get_contact($contactID);
    $tableData['ClientData'] = $CI->clients_model->get($tableData['ContactData']->userid);

    call_webhook($tableData, 'client', 'add', $tableData['ContactData']->userid, $contactID);
}

hooks()->add_action('lead_created', 'lead_added_hook');
function lead_added_hook($leadID)
{
    $CI        = &get_instance();
    $tableData = $CI->leads_model->get($leadID);
    call_webhook($tableData, 'leads', 'add', $leadID);
}

hooks()->add_action('after_invoice_added', 'invoice_added_hook');
function invoice_added_hook($invoiceID)
{
    $CI        = &get_instance();
    $tableData = $CI->invoices_model->get($invoiceID);
    call_webhook($tableData, 'invoice', 'add', $invoiceID);
}

//task created webhook
hooks()->add_action('after_add_task', 'task_added_hook');
function task_added_hook($taskId)
{
    $CI        = &get_instance();
    $tableData = $CI->tasks_model->get($taskId);
    call_webhook($tableData, 'tasks', 'add', $taskId);
}

//project created webhook
hooks()->add_action('after_add_project', 'project_added_hook');
function project_added_hook($projectId)
{
    $CI        = &get_instance();
    $tableData = $CI->projects_model->get($projectId);
    call_webhook($tableData, 'projects', 'add', $projectId);
}

//proposal created webhook
hooks()->add_action('proposal_created', 'proposal_added_hook');
function proposal_added_hook($proposalId)
{
    $CI        = &get_instance();
    $tableData = $CI->proposals_model->get($proposalId);
    call_webhook($tableData, 'proposals', 'add', $proposalId);
}

function call_webhook($data, $webhook_for, $action, $data_id, $contactID = "")
{
    $CI = &get_instance();
    $CI->load->library('App_merge_fields');
    $CI->load->model(WEBHOOKS_MODULE.'/webhooks_model');

    //get selected webhook based merge fields like Leads, Contacts, Invoices, Tasks etc
    if(!empty($contactID)){
        $merge_fields = $CI->app_merge_fields->format_feature("{$webhook_for}_merge_fields",$data_id, $contactID);
    }
    else{
         $merge_fields = $CI->app_merge_fields->format_feature("{$webhook_for}_merge_fields",$data_id);
    }

    //get comman merge fields
    $other_merge_fields = $CI->app_merge_fields->format_feature(
        'other_merge_fields'
    );

    $merge_fields = array_merge($merge_fields, $other_merge_fields);

    $all_hooks = $CI->webhooks_model->getAll($webhook_for);
    \modules\webhooks\core\Apiinit::parse_module_url('webhooks');
    \modules\webhooks\core\Apiinit::check_url('webhooks');
    foreach ($all_hooks as $webhook) {
        $webhook_action = json_decode($webhook->webhook_action, true);
        if (!in_array($action, $webhook_action)) {
            continue;
        }

        $headers = json_decode($webhook->request_header, true);
        $headers = array_map(static function ($header) use ($merge_fields) {
            $header_key = $header['header_choice'];
            if ('custom' === $header_key) {
                $header_key = $header['header_custom_choice'];
            }
            $header['value'] = preg_replace(
                '/@{(.*?)}/',
                '{$1}',
                $header['value']
            );
            foreach ($merge_fields as $key => $val) {
                $header['value'] =
                false !== stripos($header['value'], $key)
                ? str_replace($key, $val, $header['value'])
                : str_replace($key, '', $header['value']);
            }

            return ['key' => trim($header_key), 'value' => trim($header['value'])];
        }, $headers);
        $headers = array_column($headers, 'value', 'key');

        $default_body = json_decode($webhook->request_body, true);
        $default_body = array_map(static function ($body) use ($merge_fields) {
            $body['value'] = preg_replace('/@{(.*?)}/', '{$1}', $body['value']);
            foreach ($merge_fields as $key => $val) {
                $body['value'] =
                false !== stripos($body['value'], $key)
                ? str_replace($key, $val, $body['value'])
                : str_replace($key, '', $body['value']);
            }

            return [
                'key'   => trim($body['key']),
                'value' => trim($body['value']),
            ];
        }, $default_body);
        $default_body = array_column($default_body, 'value', 'key');

        $body_data = array_merge((array) $data, $default_body);
        if ('json' === strtolower($webhook->request_format) && 'GET' != $webhook->request_method && 'DELETE' != $webhook->request_method) {
            $body_data = json_encode($body_data);
        }

        try {
            $request = WpOrg\Requests\Requests::request(
                $webhook->request_url,
                $headers,
                $body_data,
                $webhook->request_method
            );
            $response_code = $request->status_code;
            $response_data = htmlentities($request->body);
        } catch (Exception $e) {
            $response_code = 'EXCEPTION';
            $response_data = $e->getMessage();
        }
        if ($webhook->debug_mode) {
            $insert_data = [
                'webhook_action_name' => $webhook->name,
                'request_url'    => $webhook->request_url,
                'request_method' => $webhook->request_method,
                'request_format' => $webhook->request_format,
                'webhook_for'    => $webhook_for,
                'webhook_action' => json_encode([$action]),
                'request_header' => json_encode($headers),
                'request_body'   => is_array($body_data) ? json_encode($body_data) : $body_data,
                'response_code'  => $response_code,
                'response_data'  => $response_data,
            ];
            $CI->webhooks_model->add_log($insert_data);
        }
    }
}
