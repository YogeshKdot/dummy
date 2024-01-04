<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Multi-factor authentication
Description: Multi-factor authentication is a process where a user is prompted during the sign-in process for an additional form of identification, such as to enter a code on their cellphone or whatsapp or google authenticator.
Version: 1.0.1
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
 */

define('MFA_MODULE_NAME', 'mfa');
define('MFA_REVISION', 101);
define('MFA_PATH', 'modules/mfa/');
hooks()->add_action('admin_init', 'mfa_module_init_menu_items');

hooks()->add_action('app_admin_footer', 'mfa_head_components');
hooks()->add_action('app_admin_footer', 'mfa_add_footer_components');

//Crontab for delete history login & send security code with MFA module.
hooks()->add_action('after_cron_run', 'auto_delete_logs');

//Login hook
hooks()->add_action('mfa_staff_login', 'mfa_login_action');
/**
 * Register activation module hook
 */
register_activation_hook(MFA_MODULE_NAME, 'mfa_module_activation_hook');
/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(MFA_MODULE_NAME . '/mfa');

function mfa_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(MFA_MODULE_NAME, [MFA_MODULE_NAME]);


/**
 * Init mfa module menu items in setup in admin_init hook
 * @return null
 */
function mfa_module_init_menu_items() {

	$CI = &get_instance();

	$CI->app_menu->add_sidebar_menu_item('mfa', [
		'name' => _l('mfa'),
		'icon' => 'fa fa-lock',
		'position' => 20,
	]);

	$CI->app_menu->add_sidebar_children_item('mfa', [
        'slug'     => 'mfa-management',
        'name'     => _l('mfa_management'),
        'icon'     => 'fa fa-lock menu-icon',
        'href'     => admin_url('mfa/mfa_management'),
        'position' => 1,
        ]);
  
    
    $CI->app_menu->add_sidebar_children_item('mfa', [
        'slug'     => 'mfa-report',
        'name'     => _l('mfa_report'),
        'icon'     => 'fa fa-line-chart menu-icon',
        'href'     => admin_url('mfa/mfa_reports'),
        'position' => 2,
    ]);

    if(is_admin()){
	    $CI->app_menu->add_sidebar_children_item('mfa', [
	        'slug'     => 'mfa-setting',
	        'name'     => _l('mfa_setting'),
	        'icon'     => 'fa fa-cogs menu-icon',
	        'href'     => admin_url('mfa/settings'),
	        'position' => 3,
	    ]);
	}
}

/**
 * add head components
 */
function mfa_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if(!(strpos($viewuri, '/admin/mfa/settings') === false)){
        echo '<link href="' . module_dir_url(MFA_MODULE_NAME, 'assets/css/manage_setting.css') . '?v=' . MFA_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/admin/mfa/mfa_management') === false)){
        echo '<link href="' . module_dir_url(MFA_MODULE_NAME, 'assets/css/manage_setting.css') . '?v=' . MFA_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

}

/**
 * add footer components
 * @return
 */
function mfa_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if(!(strpos($viewuri, '/admin/mfa/settings') === false)){
		echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/js/settings.js') . '?v=' . MFA_REVISION . '" ></script>';
	}

	if(!(strpos($viewuri, '/admin/mfa/mfa_management') === false)){
		echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/js/mfa_management.js') . '?v=' . MFA_REVISION . '" ></script>';
	}

	if (!(strpos($viewuri, '/admin/mfa/mfa_reports') === false)) {
	    echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
	    echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
	    echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
	    echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
	    echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
	    echo '<script src="' . module_dir_url(MFA_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>'; 
	}
}

/**
 * { mfa login action }
 *
 * @param        $user   The user
 */
function mfa_login_action($user){
	$CI = &get_instance();
	$CI->load->model('mfa/mfa_model');
	if(get_mfa_option('enable_mfa') == 1){
		$CI->db->where('email', $user);
		$staff = $CI->db->get(db_prefix().'staff')->row();

		// Check admin setting
		if((get_mfa_option('enable_google_authenticator') == 1 || get_mfa_option('enable_whatsapp') == 1 || get_mfa_option('enable_sms') == 1) && isset($staff->staffid)){
			// Check user setting
			if($staff->mfa_google_ath_enable == 1 || $staff->mfa_whatsapp_enable == 1 || $staff->mfa_sms_enable == 1){

				if($staff->mfa_whatsapp_enable == 1 && get_mfa_option('enable_whatsapp') == 1){
					if(generate_security_code($staff->staffid, 'whatsapp')){
						$CI->mfa_model->send_security_code($staff, 'whatsapp');
					}
				}

				if($staff->mfa_sms_enable == 1 && get_mfa_option('enable_sms') == 1){
					if(generate_security_code($staff->staffid, 'sms')){
						$CI->mfa_model->send_security_code($staff, 'sms');
					}
				}

				redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/'.$staff->staffid));
			}
		}
	}
}

/**
 * { auto delete logs }
 */
function auto_delete_logs(){
	$CI = &get_instance();
	$CI->load->model('mfa/mfa_model');
	$cur_hour = date('H');
	$m = get_mfa_option('delete_history_after_months');
	if($m > 0){
		if($cur_hour == 8){
			$check_date = date('Y-m-d', strtotime('-'.$m.' months', strtotime(date('Y-m-d H:i:s'))));
			$CI->db->where('time', $check_date);
			$CI->db->delete(db_prefix().'mfa_history_login');
		}
	}
}