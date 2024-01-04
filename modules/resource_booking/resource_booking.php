<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Staff Booking Module
Description: Management of company's resources (outsource members etc)
Version: 1.0.0
Requires at least: 2.3.*
Author: Themesic Interactive
Author URI: https://codecanyon.net/user/themesic/portfolio
*/

define('RESOURCE_BOOKING_MODULE_NAME', 'resource_booking');
define('RESOURCE_BOOKING_MODULE_UPLOAD_FOLDER', module_dir_path(RESOURCE_BOOKING_MODULE_NAME, 'uploads'));
hooks()->add_action('admin_init', 'resource_booking_permissions');
hooks()->add_action('admin_init', 'resource_booking_module_init_menu_items');
hooks()->add_filter('get_dashboard_widgets', 'resource_booking_add_dashboard_widget');
hooks()->add_action('app_admin_head', 'resource_booking_add_head_components');

function resource_booking_add_dashboard_widget($widgets)
{
    $widgets[] = [
            'path'      => 'resource_booking/widget',
            'container' => 'left-8',
        ];

    return $widgets;
}
/**
* Register activation module hook
*/
register_activation_hook(RESOURCE_BOOKING_MODULE_NAME, 'resource_booking_module_activation_hook');
/**
* Load the module helper
*/
$CI = & get_instance();
$CI->load->helper(RESOURCE_BOOKING_MODULE_NAME . '/resource_booking');

function resource_booking_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(RESOURCE_BOOKING_MODULE_NAME, [RESOURCE_BOOKING_MODULE_NAME]);

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function resource_booking_module_init_menu_items()
{
    
    $CI = &get_instance();
    if (has_permission('resource_booking', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('resource-booking', [
            'name'     => _l('resource_booking'),
            'icon'     => 'fa fa-calendar-check-o',
            'position' => 50,
        ]);
        $CI->app_menu->add_sidebar_children_item('resource-booking', [
            'slug'     => 'booking',
            'name'     => _l('booking'),
            'icon'     => 'fa fa-edit',
            'href'     => admin_url('resource_booking/manage_booking'),
            'position' => 1,
        ]);

        $CI->app_menu->add_sidebar_children_item('resource-booking', [
            'slug'     => 'resource',
            'name'     => _l('resource'),
            'icon'     => 'fa fa-cube',
            'href'     => admin_url('resource_booking/resources'),
            'position' => 3,
        ]);

        $CI->app_menu->add_sidebar_children_item('resource-booking', [
            'slug'     => 'resource-group',
            'name'     => _l('resource_group'),
            'icon'     => 'fa fa-cubes',
            'href'     => admin_url('resource_booking/resource_group'),
            'position' => 2,
        ]);

        $CI->app_menu->add_sidebar_children_item('resource-booking', [
            'slug'     => 'statisticals',
            'name'     => _l('statistical'),
            'icon'     => 'fa fa-list-ul',
            'href'     => admin_url('resource_booking/statistical'),
            'position' => 4,
        ]);
    }
    
}

function resource_booking_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('resource_booking', $capabilities, _l('resource_booking'));
}

function resource_booking_add_head_components(){
        $CI = &get_instance();
        echo '<link href="' . module_dir_url('resource_booking', 'assets/css/resource_booking.css') .'?v=' . $CI->app_scripts->core_version(). '"  rel="stylesheet" type="text/css" />';
}


hooks()->add_action('app_init',RESOURCE_BOOKING_MODULE_NAME.'_actLib');
function resource_booking_actLib()
{
	//LYMODFIX
	return;
	//
    $CI = & get_instance();
    $CI->load->library(RESOURCE_BOOKING_MODULE_NAME.'/Envapi');
    $envato_res = $CI->envapi->validatePurchase(RESOURCE_BOOKING_MODULE_NAME);
    if (!$envato_res) {
        set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
        redirect(admin_url('modules'));
    }
}

hooks()->add_action('pre_activate_module', RESOURCE_BOOKING_MODULE_NAME.'_sidecheck');
function resource_booking_sidecheck($module_name)
{
	//LYMODFIX
	return;
	//
	
    if ($module_name['system_name'] == RESOURCE_BOOKING_MODULE_NAME) {
        if (!option_exists(RESOURCE_BOOKING_MODULE_NAME.'_verified') && empty(get_option(RESOURCE_BOOKING_MODULE_NAME.'_verified')) && !option_exists(RESOURCE_BOOKING_MODULE_NAME.'_verification_id') && empty(get_option(RESOURCE_BOOKING_MODULE_NAME.'_verification_id'))) {
            $CI = & get_instance();
            $data['submit_url'] = $module_name['system_name'].'/env_ver/activate';
            $data['original_url'] = admin_url('modules/activate/'.RESOURCE_BOOKING_MODULE_NAME);
            $data['module_name'] = RESOURCE_BOOKING_MODULE_NAME;
            $data['title'] = "Module activation";
            echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
            exit();
        }
    }
}

hooks()->add_action('pre_deactivate_module', RESOURCE_BOOKING_MODULE_NAME.'_deregister');
function resource_booking_deregister($module_name)
{
    if ($module_name['system_name'] == RESOURCE_BOOKING_MODULE_NAME) {
        delete_option(RESOURCE_BOOKING_MODULE_NAME."_verified");
        delete_option(RESOURCE_BOOKING_MODULE_NAME."_verification_id");
        delete_option(RESOURCE_BOOKING_MODULE_NAME."_last_verification");
        if(file_exists(__DIR__."/config/token.php")){
            unlink(__DIR__."/config/token.php");
        }
    }
}