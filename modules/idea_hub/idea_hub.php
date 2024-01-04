<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Idea Hub
Description: Ideas and Challenges
Version: 1.0.0
Author: Zonvoir
Author URI: https://zonvoir.com/
Requires at least: 2.3.*
*/
define('IDEA_HUB_MODULE_NAME', 'idea_hub');
define('IDEA_HUB_UPLOADS_FOLDER', FCPATH . 'modules/idea_hub/uploads' . '/');
define('IDEA_HUB_UPLOADS_CHALLENGES_FOLDER', FCPATH . 'modules/idea_hub/uploads/challenges' . '/');
define('IDEA_HUB_UPLOADS_IDEAS_FOLDER', FCPATH . 'modules/idea_hub/uploads/ideas' . '/');
define('IDEA_HUB_IDEAS_ATTACHMENT_FOLDER', FCPATH . 'modules/idea_hub/uploads/ideas/attachment' . '/');
define('IDEA_HUB_IDEAS_DISCUSSION_FOLDER', FCPATH . 'modules/idea_hub/uploads/ideas/discussions' . '/');
define('IDEA_HUB_DISCUSSION_ATTACHMENT_FOLDER', FCPATH . 'modules/idea_hub/uploads/ideas/discussions/attachment' . '/');
define('IDEA_HUB_V_THUMBNAILS_FOLDER', FCPATH . 'modules/idea_hub/uploads/ideas/v_thumbnails' . '/');

$CI = &get_instance();

/**
 * Load the module helper file
 */
$CI->load->helper(IDEA_HUB_MODULE_NAME . '/idea_hub');

/**
* Register activation module hook
*/
register_activation_hook(IDEA_HUB_MODULE_NAME, 'idea_hub_activation_hook');
function idea_hub_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__ . '/install.php');
}
/**
 * Register uninstall module hook
 */
register_uninstall_hook(IDEA_HUB_MODULE_NAME, 'idea_hub_module_uninstall_hook');
function idea_hub_module_uninstall_hook()
{
	$CI = &get_instance();
	require_once(__DIR__ . '/uninstall.php');
}
hooks()->add_action('admin_init', 'idea_hub_init_menu_items');
hooks()->add_action('admin_init', 'idea_hub_register_user_permissions');
/**
 * Hook for assigning staff permissions for
 *
 * @return void
 */
function idea_hub_register_user_permissions()
{
	$capabilities = [];
	$capabilities['capabilities'] = [
			'view_own' => _l('permission_view_own'),
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];
	register_staff_capabilities('idea_hub', $capabilities, _l('idea_hub'));
}
/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(IDEA_HUB_MODULE_NAME, [IDEA_HUB_MODULE_NAME]);
function idea_hub_init_menu_items()
{
	$CI = &get_instance();
	if(global_module_permission()){
		$CI->app_menu->add_sidebar_menu_item('idea_hub', [
			'name' => _l('idea_hub'), // The name if the item
			'href' => admin_url('idea_hub'), // URL of the item
			'position' => 10, // The menu position, see below for default positions.
			'icon' => 'fa fa-lightbulb-o', // Font awesome icon
		]);
	}
	if (is_admin()) {
		// The first paremeter is the parent menu ID/Slug
		$CI->app_menu->add_setup_menu_item('idea_hub', [
            'collapse' => true,
            'name' => _l('idea_hub'),
            'position' => 10,
        ]);
        $CI->app_menu->add_setup_children_item('idea_hub', [
            'slug' => 'idea_hub-groups',
            'name' => _l('category'),
            'href' => admin_url('idea_hub/category'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('idea_hub', [
            'slug' => 'idea_hub-groups',
            'name' => _l('stage'),
            'href' => admin_url('idea_hub/stages'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('idea_hub', [
            'slug' => 'idea_hub-groups',
            'name' => _l('status'),
            'href' => admin_url('idea_hub/status'),
            'position' => 5,
        ]);
		if (staff_can('view', 'settings')) {
			$CI->app_tabs->add_settings_tab('idea_hub', [
				'name'     => '' . _l('idea_hub') . '',
				'view'     => 'idea_hub/setting',
				'position' => 36,
			]);
		}
       
	}
}
hooks()->add_action('clients_init', 'idea_hub_module_init_menu_items');
function idea_hub_module_init_menu_items()
{
	$CI = &get_instance();
	if(is_client_logged_in() && get_option('client_view_ih_menu')) { 
		add_theme_menu_item('idea_hub', [
			'name'     => _l('idea_hub'),
			'href'     => site_url('idea_hub/client_challenges'),
			'position' => 4,
		]);
	}
}