<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Menu Setup
Description: Default module to apply changes to the menus
Version: 2.3.0
Requires at least: 2.3.*
*/

define('MENU_SETUP_MODULE_NAME', 'menu_setup');

$CI = &get_instance();

hooks()->add_filter('sidebar_menu_items', 'app_admin_sidebar_custom_options', 999);
hooks()->add_filter('sidebar_menu_items', 'app_admin_sidebar_custom_positions', 998);

hooks()->add_filter('setup_menu_items', 'app_admin_setup_menu_custom_options', 999);
hooks()->add_filter('setup_menu_items', 'app_admin_setup_menu_custom_positions', 998);
hooks()->add_filter('module_menu_setup_action_links', 'module_menu_setup_action_links');
hooks()->add_action('admin_init', 'menu_setup_init_menu_items');

/**
* Add additional settings for this module in the module list area
* @param  array $actions current actions
* @return array
*/
function module_menu_setup_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('menu_setup/main_menu') . '">' . _l('main_menu') . '</a>';
    $actions[] = '<a href="' . admin_url('menu_setup/setup_menu') . '">' . _l('setup_menu') . '</a>';

    return $actions;
}
/**
* Load the module helper
*/
$CI->load->helper(MENU_SETUP_MODULE_NAME . '/menu_setup');

/**
* Register activation module hook
*/
register_activation_hook(MENU_SETUP_MODULE_NAME, 'menu_setup_activation_hook');

function menu_setup_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}

register_deactivation_hook(MENU_SETUP_MODULE_NAME, 'menu_setup_deactivation_hook');
function menu_setup_deactivation_hook()
{
    // set_alert('danger', _l('cannot_deactivate_menu_setup_module'));
    // redirect(admin_url('modules'), 'refresh');

    $my_files_list = [
        VIEWPATH . 'admin/settings/my_all.php',
        VIEWPATH . 'admin/settings/my_setup_menu.php'
    ];

    foreach ($my_files_list as $actual_path) {
        if (file_exists($actual_path)) {
            @unlink($actual_path);
        }
    }
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(MENU_SETUP_MODULE_NAME, [MENU_SETUP_MODULE_NAME]);

/**
 * Init menu setup module menu items in setup in admin_init hook
 * @return null
 */
function menu_setup_init_menu_items()
{
    /**
    * If the logged in user is administrator, add custom menu in Setup
    */
    if (is_admin()) {
        $CI = &get_instance();
        $CI->app_menu->add_setup_menu_item('menu-options', [
            'collapse' => true,
            'name'     => _l('menu_builder'),
            'position' => 60,
        ]);

        $CI->app_menu->add_setup_children_item('menu-options', [
            'slug'     => 'main-menu-options',
            'name'     => _l('main_menu'),
            'href'     => admin_url('menu_setup/main_menu'),
            'position' => 5,
        ]);

        $CI->app_menu->add_setup_children_item('menu-options', [
            'slug'     => 'setup-menu-options',
            'name'     => _l('setup_menu'),
            'href'     => admin_url('menu_setup/setup_menu'),
            'position' => 10,
        ]);
    }
}

hooks()->add_filter("setup_menu_items", 'addTooltipToMenuItems');
hooks()->add_filter("sidebar_menu_items", 'addTooltipToMenuItems');
function addTooltipToMenuItems($items)
{
    if (get_instance()->app_modules->is_active('saas')) {
        switchDatabase();
    }

    $newArray = (hooks()->current_filter() == 'sidebar_menu_items') ? json_decode(get_instance()->db->get_where(db_prefix() . 'options', ['name' => 'aside_menu_active'])->row()->value ?? '', true) : json_decode(get_instance()->db->get_where(db_prefix() . 'options', ['name' => 'setup_menu_active'])->row()->value ?? '', true);

    foreach ($items as $key => &$value) {
        $keyData = $newArray[$key] ?? null;

        if (!empty($keyData)) {
            $value['tooltip'] = $keyData['tooltip'] ?? "";

            if ($value['slug'] == 'settings') {
                $settingsTabs = get_instance()->app_tabs->get_settings_tabs();
                $settingsTabsValues = array_values($settingsTabs);

                foreach ($settingsTabsValues as $index => &$subArray) {
                    $subArray['icon'] = '';
                    $subArray['parent_slug'] = 'settings';
                    $subArray['href'] = admin_url('settings?group=' . $subArray['slug']);
                }

                $value['children'] = $settingsTabsValues;
            }

            foreach ($value['children'] as &$submenu) {
                $slug = $submenu['slug'];
                $submenuData = $keyData['children'][$slug] ?? null;
                $submenu['tooltip'] = $submenuData['tooltip'] ?? '';
            }
        }
    }

    if (get_instance()->app_modules->is_active('saas')) {
        if (getSubDomain()) {
            $clientPlan = get_instance()->db->get_where(db_prefix() . 'client_plan', ['tenants_name' => getSubDomain()])->row_array();
            switchDatabase(
                $clientPlan['tenants_db'],
                $clientPlan['tenants_db_username'],
                get_instance()->encryption->decrypt($clientPlan['tenants_db_password']),
                get_instance()->db->get_where(db_prefix() . 'options', ['name' => 'mysql_host'])->row()->value,
                get_instance()->db->get_where(db_prefix() . 'options', ['name' => 'mysql_port'])->row()->value
            );
        }
    }

    return $items;
}

hooks()->add_filter('admin_area_auto_loaded_vars', 'modifySettingsMenu');
function modifySettingsMenu($vars)
{
    $settingsTabsValues = moveSettingsTabToSidebarMenu();
    $vars['setup_menu']['settings']['children'] = $settingsTabsValues;
    return $vars;
}

hooks()->add_action('aside_menu_resetted', function() {
    update_option('aside_menu_active', aside_menu_active_json());
});

hooks()->add_action('setup_menu_resetted', function() {
   update_option('setup_menu_active', setup_menu_active_json());
});
