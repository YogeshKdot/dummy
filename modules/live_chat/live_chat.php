<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Live Chat
Description: Live chat module for Perfex CRM
Version: 1.0
Requires at least: 2.3.*
*/

define('live_chat_MODULE_NAME', 'live_chat');

$CI = &get_instance();

/**
 * Load the module helper
 */
$CI->load->helper(live_chat_MODULE_NAME . '/live_chat');

/**
 * Register activation module hook
 */
register_activation_hook(live_chat_MODULE_NAME, 'live_chat_activation_hook');

function live_chat_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(live_chat_MODULE_NAME, [live_chat_MODULE_NAME]);

/**
 * Actions for inject the custom styles
 */
hooks()->add_action('app_admin_footer', 'live_chat_admin_head');
hooks()->add_action('app_customers_footer', 'live_chat_clients_area_head');
hooks()->add_filter('module_live_chat_action_links', 'module_live_chat_action_links');
hooks()->add_action('admin_init', 'live_chat_init_menu_items');

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
function module_live_chat_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('live_chat') . '">' . _l('settings') . '</a>';

    return $actions;
}
/**
 * Admin area applied styles
 * @return null
 */
function live_chat_admin_head()
{
    live_chat_script('live_chat_admin_area');
}

/**
 * Clients area theme applied styles
 * @return null
 */
function live_chat_clients_area_head()
{
    live_chat_script('live_chat_clients_area');
}

/**
 * Custom CSS
 * @param  string $main_area clients or admin area options
 * @return null
 */
function live_chat_script($main_area)
{
    $clients_or_admin_area             = get_option($main_area);
    if (get_option('live_chat') == 'enable') {
        $live_chat_admin_and_clients_area = get_option('live_chat_clients_and_admin_area');
        if (!empty($clients_or_admin_area) || !empty($live_chat_admin_and_clients_area)) {
            //echo '<script>' . PHP_EOL;
            if (!empty($clients_or_admin_area)) {
                $clients_or_admin_area = html_entity_decode(clear_textarea_breaks($clients_or_admin_area));
                echo $clients_or_admin_area . PHP_EOL;
            }
            if (!empty($live_chat_admin_and_clients_area)) {
                $live_chat_admin_and_clients_area = html_entity_decode(clear_textarea_breaks($live_chat_admin_and_clients_area));
                echo $live_chat_admin_and_clients_area . PHP_EOL;
            }
            //echo '</script>' . PHP_EOL;
        }
    }
}

/**
 * Init theme style module menu items in setup in admin_init hook
 * @return null
 */
function live_chat_init_menu_items()
{
    if (is_admin()) {
        $CI = &get_instance();
        /**
         * If the logged in user is administrator, add custom menu in Setup
         */
        $CI->app_menu->add_setup_menu_item('live-chat', [
            'href'     => admin_url('live_chat'),
            'name'     => _l('live_chat'),
            'position' => 66,
        ]);
    }
}