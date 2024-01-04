<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_106 extends App_module_migration
{
     public function up()
     {  
     	$CI = &get_instance();
     	      
     	if (!$CI->db->field_exists('parent' ,db_prefix() . 'team_password_category')) {
		    $CI->db->query('ALTER TABLE `' . db_prefix() . 'team_password_category`
		  ADD COLUMN `parent` INT(11) NULL default "0" ');
		}
     }
}
