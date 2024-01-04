<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
	public function up()
	{        
		$CI = &get_instance();
		if (!$CI->db->field_exists('item_type' ,db_prefix() . 'fe_checkin_assets')) {
			$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_checkin_assets`
				ADD COLUMN `item_type` varchar(30) NULL
				');
		}
		
		if (!$CI->db->table_exists(db_prefix() . 'fe_sign_documents')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_sign_documents` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`checkin_out_id` text NULL,
				`status` INT NOT NULL DEFAULT 1,
				`check_to_staff` INT(11) NULL,
				`reference` varchar(30) NULL,
				`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`));');
		}

		if (!$CI->db->table_exists(db_prefix() . 'fe_signers')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_signers` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`sign_document_id` INT(11) NULL,
				`staff_id` INT(11) NULL,
				`ip_address` varchar(100) NULL,
				`date_of_signing` datetime NULL,
				`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`));');
		}
		if (!$CI->db->field_exists('firstname' ,db_prefix() . 'fe_signers')) {
			$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_signers`
				ADD COLUMN `firstname` varchar(50) NULL,
				ADD COLUMN `lastname` varchar(50) NULL,
				ADD COLUMN `email` varchar(100) NULL
				');
		}
		if ($CI->db->table_exists(db_prefix() . 'fe_checkin_assets')) {
			$CI->db->query('UPDATE '.db_prefix().'fe_checkin_assets INNER JOIN '.db_prefix().'fe_assets ON  '.db_prefix().'fe_assets.checkin_out_id = '.db_prefix().'fe_checkin_assets.id set item_type = '.db_prefix().'fe_assets.type');
		}
	}
}


