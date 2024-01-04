<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        
        if (!$CI->db->field_exists('gg_auth_secret_key' ,db_prefix() . 'staff')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`
            ADD COLUMN `gg_auth_secret_key` text NULL DEFAULT ""');
        }

        if (!$CI->db->field_exists('enable_gg_auth' ,db_prefix() . 'roles')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'roles`
            ADD COLUMN `enable_gg_auth` int(1) NULL DEFAULT "0"');
        }

        if (row_mfa_options_exist('"enable_gg_auth_for_users_have_not_role"') == 0){
            $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("enable_gg_auth_for_users_have_not_role", "0", "1");');
        }
     }
}
