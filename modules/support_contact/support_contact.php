<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: AIO Support Contact Module
Description: Support contact module.
Version: 1.0
Requires at least: 2.3.*
Author: ESTACK TECHNOLOGIES
Author URI: https://estacktechnologies.com/
*/

define('support_contact_MODULE_NAME', 'support_contact');

$CI = &get_instance();

/**
 * Load the module helper
 */
$CI->load->helper(support_contact_MODULE_NAME . '/support_contact');

/**
 * Register activation module hook
 */
register_activation_hook(support_contact_MODULE_NAME, 'support_contact_activation_hook');

function support_contact_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(support_contact_MODULE_NAME, [support_contact_MODULE_NAME]);

/**
 * Actions for inject the custom styles
 */
hooks()->add_action('app_admin_footer', 'support_contact_admin_area_head');
hooks()->add_action('app_customers_head', 'support_contact_clients_area_head');
hooks()->add_filter('module_support_contact_action_links', 'module_support_contact_action_links');
hooks()->add_action('admin_init', 'support_contact_init_menu_items');

if(get_option('aio_support_frontend') == 1) { 
    hooks()->add_action('app_customers_head', 'support_contact_assets');
}

if(get_option('aio_support_backend') == 1) {
    hooks()->add_action('app_admin_footer', 'support_contact_assets');
}


/**
 * Load assets and necessary language texts
 * @return stylesheet / script
 */
function support_contact_assets()
{
    
    if(checkMobile()){
        $whatsappservice = 'api.whatsapp.com';
    }
    else {
        $whatsappservice = 'web.whatsapp.com';
    }
    
    $support_msgs = _l('support_msgs');
    $support_msgs_escaped = htmlspecialchars_decode($support_msgs);
    echo '<link href="' . base_url('modules/support_contact/assets/jquery.contactus.css') . '"  rel="stylesheet" type="text/css" >';
    echo '<script src="' . base_url('/assets/plugins/jquery/jquery.min.js') . '" async></script>';
    echo '<script src="' . base_url('modules/support_contact/assets/jquery.contactus.js') . '" async></script>';
    echo '<script src="' . base_url('modules/support_contact/assets/main.js') . '" async></script>';
    echo '<script>';
    if (get_option('support_contact_messenger_username') == '') { echo 'var messengerstyle = " hide";'; } else { echo 'var messengerstyle = "";'; }
    if (get_option('support_contact_whatsapp') == '') { echo 'var whatsappstyle = " hide";'; } else { echo 'var whatsappstyle = "";'; }
    if (get_option('support_contact_viber') == '') { echo 'var viberstyle = " hide";'; } else { echo 'var viberstyle = "";'; }
    if (get_option('support_contact_email_address') == '') { echo 'var emailstyle = " hide";'; } else { echo 'var emailstyle = "";'; }
    echo '
        var contactText = "' . _l('contact_text') . '";
        var MessengerText = "' . _l('messenger_text') . '";
        var WhatsAppText = "' . _l('whatsapp_text') . '";
        var ViberText = "' . _l('viber_text') . '";
        var EmailText = "' . _l('email_text') . '";
        var MessengerUsernameLink = "http://m.me/' . get_option('support_contact_messenger_username') . '";
        var WhatsAppPhoneLink = "https://' . $whatsappservice . '/send?phone=' . get_option('support_contact_whatsapp') . '";
        var ViberPhoneLink = "viber://chat?number=' . get_option('support_contact_viber') . '";
        var EmailAddressLink = "mailto:' . get_option('support_contact_email_address') . '";
        var MessengerUsername = "' . get_option('support_contact_messenger_username') . '";
        var WhatsAppPhone = "' . get_option('support_contact_whatsapp') . '";
        var ViberPhone = "' . get_option('support_contact_viber') . '";
        var EmailAddress = "' . get_option('support_contact_email_address') . '";
        var aioMessages = [' . $support_msgs_escaped . '];';
    echo '</script>';
}

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
function module_support_contact_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('support_contact') . '">' . _l('settings') . '</a>';
    return $actions;
}


/**
 * Load in clients area
 * @return null
 */
function support_contact_clients_area_head()
{
    if(is_client_logged_in()) { 
        support_contact_script('support_contact_clients_area');
    }
}

/**
 * Load in admin area
 * @return null
 */
function support_contact_admin_area_head()
{
        support_contact_script('support_contact_clients_area');
}

/**
 * Detect mobile users and provide a different link URL, related to the app
 * @return null
 */
function checkMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

/**
 * Necessary checks for fields and devices
 * @param  string $main_area clients or admin area options
 * @return null
 */
function support_contact_script() {

if (get_option('support_contact') == 'enable') {
    $support_contact_whatsapp = get_option('support_contact_whatsapp');
    $support_contact_viber = get_option('support_contact_viber');
    $messenger_username = get_option('support_contact_messenger_username');
    $email_address = get_option('support_contact_email_address');
        if (!empty($support_contact_viber) || !empty($support_contact_whatsapp)) {
            $support_contact_viber = html_entity_decode(clear_textarea_breaks($support_contact_viber));
            $support_contact_whatsapp = html_entity_decode(clear_textarea_breaks($support_contact_whatsapp));
            $support_contact_viber = str_replace("+", "", $support_contact_viber);
            $support_contact_whatsapp = str_replace("+", "", $support_contact_whatsapp);
            echo '<div id="contact"></div>';
        }
    }
}

/**
 * Init theme style module menu items in setup in admin_init hook
 * @return null
 */
function support_contact_init_menu_items()
{
    if (is_admin()) {
        $CI = &get_instance();
        /**
         * If the logged in user is administrator, add custom menu in Setup
         */
        $CI->app_menu->add_setup_menu_item('support-contact', [
            'href'     => admin_url('support_contact'),
            'name'     => _l('support_contact'),
            'position' => 66,
        ]);
    }
}