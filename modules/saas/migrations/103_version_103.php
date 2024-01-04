<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_103 extends App_module_migration
{
    public function up()
    {
        if (table_exists('plan_management')) {
            if (!get_instance()->db->field_exists('allowed_modules', db_prefix() . 'plan_management')) {
                get_instance()->db->query('ALTER TABLE `' . db_prefix() . 'plan_management` ADD `allowed_modules` text');
            }
        }

        if (table_exists('client_plan')) {
            if (!get_instance()->db->field_exists('allowed_modules', db_prefix() . 'client_plan')) {
                get_instance()->db->query('ALTER TABLE `' . db_prefix() . 'client_plan` ADD `allowed_modules` text');
            }
        }

        _maybe_create_upload_path(FCPATH . 'tenant_modules');

        $my_files_list = [
            APPPATH . 'helpers/my_functions_helper.php' => module_dir_path(SUPERADMIN_MODULE, '/resources/application/helpers/my_functions_helper.php'),
            VIEWPATH . 'admin/modules/my_list.php'      => module_dir_path(SUPERADMIN_MODULE, '/resources/application/views/admin/modules/my_list.php')
        ];

        // Copy each file in $my_files_list to its actual path if it doesn't already exist
        foreach ($my_files_list as $actual_path => $resource_path) {
            if (file_exists($actual_path)) {
                copy($resource_path, $actual_path);
            }
        }

        // An array of files to backup
        $backup_files_list = [
            APPPATH . 'config/constants.php'       => module_dir_path(SUPERADMIN_MODULE, '/resources/application/config/constants.php'),
            APPPATH . 'third_party/MX/Modules.php' => module_dir_path(SUPERADMIN_MODULE, '/resources/application/third_party/MX/Modules.php'),
            APPPATH . 'libraries/App_modules.php'  => module_dir_path(SUPERADMIN_MODULE, '/resources/application/libraries/App_modules.php'),
        ];

        // Backup each file in $backup_files_list by renaming it with a '.backup' suffix if it exists, then copy the new version from the resources directory
        foreach ($backup_files_list as $actual_path => $resource_path) {
            if (file_exists($actual_path)) {
                rename($actual_path, $actual_path . '.backup');
            }
            if (!file_exists($actual_path)) {
                copy($resource_path, $actual_path);
            }
        }
    }
}
