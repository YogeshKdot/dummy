<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
	public function up()
	{        
		$CI = &get_instance();
		if (!$CI->db->field_exists('maintenance' ,db_prefix() . 'fe_audit_detail_requests')) {
			$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_audit_detail_requests`
				ADD COLUMN `maintenance` INT(11) NOT NULL DEFAULT 0
				');
		}
		if (!$CI->db->field_exists('maintenance_id' ,db_prefix() . 'fe_audit_detail_requests')) {
			$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_audit_detail_requests`
				ADD COLUMN `maintenance_id` INT(11) NOT NULL DEFAULT 0
				');
		}
	}
}


