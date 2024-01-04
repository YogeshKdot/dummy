<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Telegram Notification
Description: Send Notifications to your telegram channel
Version: 1.0.0
Author: ABHIAN_Dev
Author URI: https://codecanyon.net/user/ABHIAN_Dev
Requires at least: 2.3.*
*/


define('Telegram_Notification', 'telegram_notification');


$CI = &get_instance();


$CI->load->helper(Telegram_Notification . '/telegram_notification');


hooks()->add_action('admin_init', 'telegram_notification_setup_init_menu_items');
hooks()->add_action('notification_created', 'telegram_notification_send');


register_activation_hook(Telegram_Notification, 'telegram_notification_module_activation_hook');

function telegram_notification_module_activation_hook()
{
    $CI = &get_instance();
   
}

register_language_files(Telegram_Notification, [Telegram_Notification]);

function telegram_notification_setup_init_menu_items()
{
   
    if (is_admin()) {
        $CI = &get_instance();
        $CI->app_tabs->add_settings_tab('telegram_notification', [
            'name'     => _l('settings_group_telegram_notification'),
            'view'     => Telegram_Notification . '/admin/settings/telegram_notification_settings',
            'position' => 90,
        ]);
    }
}


function telegram_notification_send($notification_id)
{
    $CI = &get_instance();
    $CI->load->library('session');
    $CI->load->model(Telegram_Notification . '/Telegram_notification_model');
    $notification = $CI->Telegram_notification_model->get_notification($notification_id);
    $telegram_sent_link = $CI->session->userdata('telegram_sent_link');
    if ($telegram_sent_link != $notification['link']) {
        send_telegram_notification($notification_id);
    }
}
