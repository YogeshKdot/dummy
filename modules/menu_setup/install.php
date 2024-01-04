<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('aside_menu_active', aside_menu_active_json());
add_option('setup_menu_active', setup_menu_active_json());

$my_files_list = [
    VIEWPATH . 'admin/settings/my_all.php'        => module_dir_path(MENU_SETUP_MODULE_NAME, '/resources/application/views/admin/settings/my_all.php'),
    VIEWPATH . 'admin/includes/my_setup_menu.php' => module_dir_path(MENU_SETUP_MODULE_NAME, '/resources/application/views/admin/includes/my_setup_menu.php'),
];

// Copy each file in $my_files_list to its actual path if it doesn't already exist
foreach ($my_files_list as $actual_path => $resource_path) {
    if (!file_exists($actual_path)) {
        copy($resource_path, $actual_path);
    }
}


