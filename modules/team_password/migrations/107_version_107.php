<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_107 extends App_module_migration
{
     public function up()
     {  
     	$CI = &get_instance();

		// hide password from client area settings
		if (row_tp_options_exist('"hide_password_from_client_area"') == 0){
		  $CI->db->query('INSERT INTO `' . db_prefix() . 'options` (`name`, `value`, `autoload`) VALUES ("hide_password_from_client_area", "0", "1");
		');
		}
     }
}
