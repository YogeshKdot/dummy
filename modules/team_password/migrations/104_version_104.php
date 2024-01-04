<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_104 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        
        
        //Version 1.0.4

        if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_normal')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
          ADD COLUMN `add_from` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_bank_account')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
          ADD COLUMN `add_from` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_credit_card')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
          ADD COLUMN `add_from` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_email')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
          ADD COLUMN `add_from` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_server')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
          ADD COLUMN `add_from` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_software_license')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
          ADD COLUMN `add_from` INT(11) NOT NULL');
        }

        if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_bank_account')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
          CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
        }

        if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_normal')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
          CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
        }

        if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_credit_card')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
          CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
        }

        if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_email')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
          CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
        }

        if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_server')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
          CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
        }

        if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_software_license')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
          CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
        }

        //logs who see/change password
        if (!$CI->db->table_exists(db_prefix() . 'tp_logs')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_logs` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `staff` int(11) null,
            `type` varchar(150) null,
            `time` datetime null,
            `rel_id` int(11) null,
            `rel_type` varchar(150) NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

     }
}
