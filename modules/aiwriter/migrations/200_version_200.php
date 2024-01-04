<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_200 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        if (!$CI->db->table_exists(db_prefix() . 'aiwriter_usage_cases')) {
            $CI->db->query('CREATE TABLE `'.db_prefix() . 'aiwriter_usage_cases` (
      `id` int NOT NULL AUTO_INCREMENT,
      `usage_case` varchar(191) DEFAULT NULL, 
      `usage_case_key` varchar(191) DEFAULT NULL, 
      `is_default` int NOT NULL DEFAULT 0,
      `status` int NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`));');
            $CI->load->model(SPAGREEN_AIWRITER_MODULE_NAME.'/aiwriter_model');
            $CI->aiwriter_model->reset_usage_case();
        }
    }
}