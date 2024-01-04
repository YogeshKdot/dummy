<?php

defined('BASEPATH') || exit('No direct script access allowed');
/*
    Module Name: Advanced Email System Module
    Description: Expand built-in possibilities of Perfex CRM's email system
    Version: 1.0.0
    Requires at least: 2.3.*
*/

/*
* Define module name
* Module Name Must be in CAPITAL LETTERS
*/
define('EXTENDED_EMAIL_MODULE', 'extended_email');

// get codeigniter instance
$CI = &get_instance();

/*
 *  Register activation module hook
 */
register_activation_hook(EXTENDED_EMAIL_MODULE, 'extended_email_module_activation_hook');
function extended_email_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__.'/install.php';
}

/*
*  Register language files, must be registered if the module is using languages
*/
register_language_files(EXTENDED_EMAIL_MODULE, [EXTENDED_EMAIL_MODULE]);

/*
     *  Load module helper file
    */
$CI->load->helper(EXTENDED_EMAIL_MODULE.'/extended_email');

hooks()->add_action('module_activated', 'mark_as_activated');
function mark_as_activated($module)
{
    update_option('extended_email_module_activated', 1);
}

hooks()->add_action('module_deactivated', 'mark_as_de_activated');
function mark_as_de_activated($module)
{
    update_option('extended_email_module_activated', 0);
}

// inject permissions Feature and Capabilities for extended_email module

if ($CI->db->table_exists(db_prefix().'extended_email_settings')) {
    hooks()->add_action('admin_init', 'extended_email_module_init_menu_items');
    function extended_email_module_init_menu_items()
    {
        $CI    = &get_instance();
        $staff = get_staff();

        $CI->app_menu->add_setup_menu_item('extended_email', [
            'slug'     => 'extended_email',
            'name'     => _l('extended_email'),
            'position' => 30,
        ]);

        $CI->app_menu->add_setup_children_item('extended_email', [
            'slug'     => 'extended_email_form',
            'name'     => _l('extended_email_form'),
            'href'     => admin_url('extended_email'),
            'position' => 2,
        ]);

        if (is_admin()) {
            $CI->app_menu->add_setup_children_item('extended_email', [
                'slug'     => 'extended_email_log_history',
                'name'     => _l('extended_email_log_history'),
                'href'     => admin_url('extended_email/extended_email_log_history'),
                'position' => 3,
            ]);
        }
    }

    $CI->config->load('extended_email/email', true);
    $settings = $CI->config->item('email');
    if ($settings['has_setting']) {
        $CI->load->library('email');
        $CI->email->initialize($CI->config->item('email'));
    }
}

if (!is_admin()) {
    hooks()->add_filter('before_send_simple_email', 'add_config_for_simple_mail');
    function add_config_for_simple_mail($conf)
    {
        $CI = &get_instance();
        $CI->config->load('extended_email/email', true);
        $config_item        = $CI->config->item('email');
        $staff              = $CI->staff_model->get(get_staff_user_id());
        $conf['reply_to']   = $config_item['smtp_user'];
        $conf['from_email'] = $config_item['smtp_user'];
        $conf['from_name']  = get_staff_full_name(get_staff_user_id());

        return $conf;
    }
}

hooks()->add_action('app_init',EXTENDED_EMAIL_MODULE.'_actLib');
function extended_email_actLib()
{
	//LYMODFIX
	return true;
	//
    $CI = & get_instance();
    $CI->load->library(EXTENDED_EMAIL_MODULE.'/Env2api');
    $envato_res = $CI->env2api->validatePurchase(EXTENDED_EMAIL_MODULE);
    if (!$envato_res) {
        set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
        redirect(admin_url('modules'));
    }
}

hooks()->add_action('pre_activate_module', EXTENDED_EMAIL_MODULE.'_sidecheck');
function extended_email_sidecheck($module_name)
{
    if ($module_name['system_name'] == EXTENDED_EMAIL_MODULE) {
        modules\extended_email\core\Apiinit::activate($module_name);
    }
}

hooks()->add_action('pre_deactivate_module', EXTENDED_EMAIL_MODULE.'_deregister');
function extended_email_deregister($module_name)
{
    if ($module_name['system_name'] == EXTENDED_EMAIL_MODULE) {
        delete_option(EXTENDED_EMAIL_MODULE."_verification_id");
        delete_option(EXTENDED_EMAIL_MODULE."_last_verification");
        delete_option(EXTENDED_EMAIL_MODULE . "_product_token");
        delete_option(EXTENDED_EMAIL_MODULE."_heartbeat");
    }
}
