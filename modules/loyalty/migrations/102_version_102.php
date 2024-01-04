<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();

        if (!$CI->db->table_exists(db_prefix() . 'loy_voucher_inv_log')) {
		    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_voucher_inv_log` (
		  `id` INT(11) NOT NULL AUTO_INCREMENT,
		  `client` INT(11) NOT NULL,
		  `voucher_code` TEXT NULL,
		  `invoice` INT(11) NULL,
		  `time` DATETIME NULL,
		  `value` DECIMAL(15,2) NULL,
		  PRIMARY KEY (`id`));');
		}

        if (!$CI->db->field_exists('create_account_point' ,db_prefix() . 'loy_rule')) {
		    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
		    ADD COLUMN `create_account_point` INT(11) NULL');
		}

		if (!$CI->db->field_exists('birthday_point' ,db_prefix() . 'loy_rule')) {
		    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
		    ADD COLUMN `birthday_point` INT(11) NULL');
		}

		if (!$CI->db->table_exists(db_prefix() . 'loy_program_discount_log')) {
		    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_program_discount_log` (
		  `id` INT(11) NOT NULL AUTO_INCREMENT,
		  `client` INT(11) NOT NULL,
		  `mbs_program` INT(11) NULL,
		  `invoice` INT(11) NULL,
		  `time` DATETIME NULL,
		  `value` DECIMAL(15,2) NULL,
		  `note` TEXT NULL,
		  PRIMARY KEY (`id`));');
		}

		add_custom_field_loy([
		  'fieldto' => 'customers',
		  'name' => 'Birthday',
		  'required' => 1,
		  
		  'type' => 'date_picker',
		  'bs_column' => 12,
		  'show_on_table' => 1,
		  'show_on_client_portal' => 1,
		]);

		create_email_template('New account bonus point', '<span style=\"font-size: 12pt;\"> Hello {contact_name}. </span><br /><br /><span style=\"font-size: 12pt;\"> You get {points_received} points from our loyalty program for new account. Hope you have the best shopping experience on our system!</span><br /><br />', 'loyalty', 'Loyalty new account bonus point (Sent to contact)', 'loyalty-new-account-bonus-point');
		
		create_email_template('Birthday bonus point', '<span style=\"font-size: 12pt;\"> Hello {contact_name}. </span><br /><br /><span style=\"font-size: 12pt;\"> Happy birthday to you! You get {points_received} points from our loyalty program for your bithday. Hope you have the best shopping experience on our system!</span><br /><br />', 'loyalty', 'Loyalty birthday bonus point (Sent to contact)', 'loyalty-birthday-bonus-point');
     }
}
