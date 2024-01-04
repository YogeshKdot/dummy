<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
    Module Name: Custom Changes
    Description: This Custom Module is developed to implement custom changes in core files of the CRM.
    Version: 1.0.1
    Requires at least: 3.0.*
    Author: <a href="https://corbitaltechnologies.com" target="_blank">Corbital Technologies<a/>
*/

/*
 * Define module name
 * Module Name Must be in CAPITAL LETTERS
 */
define('CUSTOM_CHANGES_MODULE', 'custom_changes');


// require_once __DIR__.'/vendor/autoload.php';

/*
 * Register activation module hook
 */
register_activation_hook(CUSTOM_CHANGES_MODULE, 'custom_changes_module_activate_hook');
function custom_changes_module_activate_hook()
{
    require_once __DIR__.'/install.php';
}

// Register language files, must be registered if the module is using languages
register_language_files(CUSTOM_CHANGES_MODULE, [CUSTOM_CHANGES_MODULE]);

/*
 * Register deactivation module hook
 */
register_deactivation_hook(CUSTOM_CHANGES_MODULE, 'custom_changes_module_deactivate_hook');
function custom_changes_module_deactivate_hook()
{
    $my_files_list = [
        VIEWPATH.'admin/clients/my_import.php',
    ];

    foreach ($my_files_list as $actual_path) {
        if (file_exists($actual_path)) {
            @unlink($actual_path);
        }
    }

    if (file_exists(APPPATH.'controllers/admin/Tasks.php') && file_exists(APPPATH.'controllers/admin/Tasks.php.backup')) {
        @unlink(APPPATH.'controllers/admin/Tasks.php');
    }

    if (!file_exists(APPPATH.'controllers/admin/Tasks.php')) {
        rename(APPPATH.'controllers/admin/Tasks.php.backup', APPPATH.'controllers/admin/Tasks.php');
    }
}

hooks()->add_filter('not_importable_clients_fields', function($data) {
    array_push($data, 'expiration_reminder_mail');
    return $data;
});
