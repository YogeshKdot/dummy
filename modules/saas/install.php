<?php

// Prevent direct access to the script
defined('BASEPATH') || exit('No direct script access allowed');

// Enable superadmin
update_option('superadmin_enabled', 1);

// Get CodeIgniter instance
$CI = &get_instance();
if (APP_DB_NAME != $CI->db->database) {
    return false;
}

// Enable tenants' landing page
add_option('tenants_landing', 1);

// Install email templates for superadmin
installSuperadminEmailTemplates();

// Allow registration of new tenants
add_option('allow_registration', 0);

// Enable newly registered tenants by default
add_option('email_verification_require_after_tenant_register', 0);

// Enable customers register require confirmation by default
update_option('customers_register_require_confirmation', 0);

// Set default lading page
add_option('saas_default_landing_page', 1);

/*
 * Create table section
 */
if (!$CI->db->table_exists('plan_management')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'plan_management` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `plan_name` varchar(255) NOT NULL,
            `plan_description` text ,
            `plan_image` text NOT NULL,
            `price` decimal(10,'.get_decimal_places().') NOT NULL,
            `trial` TINYINT(1) NOT NULL DEFAULT "0",
            `most_popular` TINYINT(1) NOT NULL DEFAULT "0",
            `limitations` TEXT NOT NULL,
            `allowed_payment_modes` mediumtext,
            `taxes` varchar(255) NOT NULL,
            `recurring` int(11) NOT NULL DEFAULT "0",
            `recurring_type` varchar(10) NOT NULL,
            `custom_recurring` tinyint(1) NOT NULL DEFAULT "0",
            `cycles` int(11) NOT NULL DEFAULT "0",
            `allowed_modules` text,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'cron_data')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'cron_data` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `called_url` VARCHAR(255) NOT NULL,
            `response` TEXT NOT NULL,
            `tenant_id` INT NOT NULL ,
            `execution_time` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'client_plan')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'client_plan` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `userid` int(11) NOT NULL COMMENT "client id",
            `tenants_name` varchar(191) NOT NULL,
            `tenants_db` varchar(100) NOT NULL,
            `tenants_db_username` varchar(100) NOT NULL,
            `tenants_db_password` varchar(255) NOT NULL,
            `tenants_admin` int(11) NOT NULL COMMENT "client contact",
            `plan_id` int(11) NOT NULL,
            `plan_details_json` longtext NOT NULL,
            `trial_days` int(3) NOT NULL,
            `trial_start_time` DATETIME NOT NULL,
            `is_invoiced` TINYINT(1) NOT NULL DEFAULT "0" COMMENT "if invoiced it will store first invoice id",
            `is_active` TINYINT(1) NOT NULL DEFAULT "0",
            `inactive_date` varchar(150) DEFAULT NULL,
            `is_deleted` TINYINT(1) NOT NULL DEFAULT "0",
            `invoices` longtext DEFAULT NULL CHECK (json_valid(`invoices`)),
            `adjustmentAmount` DECIMAL(15,2) DEFAULT NULL,
            `allowed_modules` text,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'saas_activity_log')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'saas_activity_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `description` text NOT NULL,
            `recorded_at` datetime NOT NULL,
            `staffid` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if ($CI->db->table_exists(db_prefix() . 'contacts')) {
    if (!$CI->db->field_exists('acceptance_ip', db_prefix() . 'contacts')) {
        $CI->db->query('ALTER TABLE `' . db_prefix() . 'contacts` ADD `acceptance_ip` VARCHAR(40) NULL DEFAULT NULL;');
    }
    if (!$CI->db->field_exists('signature', db_prefix() . 'contacts')) {
        $CI->db->query('ALTER TABLE `' . db_prefix() . 'contacts` ADD `signature` VARCHAR(40) NULL DEFAULT NULL;');
    }
}

if (table_exists('contacts')) {
    if (!$CI->db->field_exists('expiration_reminder_mail', db_prefix() . 'contacts')) {
        $CI->db->query('ALTER TABLE `' . db_prefix() . 'contacts` ADD `expiration_reminder_mail` timestamp NULL DEFAULT NULL');
    }
}

/*since version 1.0.3*/
if (table_exists('plan_management')) {
    if (!get_instance()->db->field_exists('allowed_modules', db_prefix() . 'plan_management')) {
        get_instance()->db->query('ALTER TABLE `' . db_prefix() . 'plan_management` ADD `allowed_modules` text');
    }
}

/*since version 1.0.3*/
if (table_exists('client_plan')) {
    if (!get_instance()->db->field_exists('allowed_modules', db_prefix() . 'client_plan')) {
        get_instance()->db->query('ALTER TABLE `' . db_prefix() . 'client_plan` ADD `allowed_modules` text');
    }
}

$my_files_list = [
    APPPATH.'helpers/my_functions_helper.php'      => module_dir_path(SUPERADMIN_MODULE, '/resources/application/helpers/my_functions_helper.php'),
    VIEWPATH.'themes/perfex/views/my_register.php' => module_dir_path(SUPERADMIN_MODULE, '/resources/application/views/themes/perfex/views/my_register.php'),
    VIEWPATH.'admin/modules/my_list.php'           => module_dir_path(SUPERADMIN_MODULE, '/resources/application/views/admin/modules/my_list.php'),
    VIEWPATH.'admin/clients/modals/my_contact.php' => module_dir_path(SUPERADMIN_MODULE, '/resources/application/views/admin/clients/modals/my_contact.php')
];

// Copy each file in $my_files_list to its actual path if it doesn't already exist
foreach ($my_files_list as $actual_path => $resource_path) {
    if (!file_exists($actual_path)) {
        copy($resource_path, $actual_path);
    }
}

// An array of files to backup
$backup_files_list = [
    APPPATH.'helpers/clients_helper.php'      => module_dir_path(SUPERADMIN_MODULE, '/resources/application/helpers/clients_helper.php'),
    APPPATH.'helpers/files_helper.php'        => module_dir_path(SUPERADMIN_MODULE, '/resources/application/helpers/files_helper.php'),
    APPPATH.'helpers/staff_helper.php'        => module_dir_path(SUPERADMIN_MODULE, '/resources/application/helpers/staff_helper.php'),
    APPPATH.'helpers/upload_helper.php'       => module_dir_path(SUPERADMIN_MODULE, '/resources/application/helpers/upload_helper.php'),
    APPPATH.'helpers/modules_helper.php'       => module_dir_path(SUPERADMIN_MODULE, '/resources/application/helpers/modules_helper.php'),
    APPPATH.'config/config.php'               => module_dir_path(SUPERADMIN_MODULE, '/resources/application/config/config.php'),
    APPPATH.'config/constants.php'            => module_dir_path(SUPERADMIN_MODULE, '/resources/application/config/constants.php'),
    APPPATH.'libraries/App_modules.php'       => module_dir_path(SUPERADMIN_MODULE, '/resources/application/libraries/App_modules.php'),
    APPPATH.'controllers/Authentication.php'  => module_dir_path(SUPERADMIN_MODULE, '/resources/application/controllers/Authentication.php'),
    APPPATH.'controllers/admin/Tasks.php'     => module_dir_path(SUPERADMIN_MODULE, '/resources/application/controllers/admin/Tasks.php')
];

// Backup each file in $backup_files_list by renaming it with a '.backup' suffix if it exists, then copy the new version from the resources directory
foreach ($backup_files_list as $actual_path => $resource_path) {
    if (file_exists($actual_path) && !file_exists($actual_path . '.backup')) {
        rename($actual_path, $actual_path.'.backup');
    }
    if (!file_exists($actual_path)) {
        copy($resource_path, $actual_path);
    }
}

// Revert these files to it's original state.
$revertFiles = [
    APPPATH . 'third_party/MX/Loader.php' => APPPATH . 'third_party/MX/Loader.php.backup',
    APPPATH . 'third_party/MX/Modules.php' => APPPATH . 'third_party/MX/Modules.php.backup',
];

foreach ($revertFiles as $actualPath => $backupFilePath) {
    if (file_exists($actualPath) && file_exists($backupFilePath)) {
        @unlink($actualPath);
    }
    if (!file_exists($actualPath)) {
        rename($backupFilePath, $actualPath);
    }
}
/* End of file install.php */
