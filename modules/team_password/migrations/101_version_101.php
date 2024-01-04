<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        
        
        //Version 1.0.1

        if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_normal')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
          ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_normal')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
          ADD COLUMN `relate_id` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_bank_account')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
          ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_bank_account')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
          ADD COLUMN `relate_id` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_credit_card')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
          ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_credit_card')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
          ADD COLUMN `relate_id` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_email')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
          ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_email')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
          ADD COLUMN `relate_id` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_server')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
          ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_server')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
          ADD COLUMN `relate_id` INT(11) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_software_license')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
          ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
        }

        if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_software_license')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
          ADD COLUMN `relate_id` INT(11) NOT NULL');
        }

        create_email_template('Share the link', '<span style=\"font-size: 12pt;\"> Hello {contact_name}. </span><br /><br /><span style=\"font-size: 12pt;\"> We would like to share with you a link of {type} information with the name {obj_name} </span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {share_link}
  </span><br /><br />', 'teampassword', 'Teampassword share the link (Sent to contact)', 'teampassword-share-link-to-contact');
        if (row_tp_options_exist('"team_password_security"') == 0){
          $CI->db->query('INSERT INTO `tbloptions` (`name`, `value`, `autoload`) VALUES ("team_password_security", "g8934fuw9843hwe8rf9*5bhv", "1");
        ');
        }
        
     }
}
