<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        //add related_to field in diagramy table
        if (!$CI->db->field_exists('related_to', db_prefix().'diagramy')) {
            $CI->db->query('ALTER TABLE `'.db_prefix().'diagramy`
		   	ADD `related_to` VARCHAR(255) NOT NULL AFTER `description`');
        }

        //add rel_id field in diagramy table
        if (!$CI->db->field_exists('rel_id', db_prefix().'diagramy')) {
            $CI->db->query('ALTER TABLE `'.db_prefix().'diagramy`
		   	ADD `rel_id` INT(11) NOT NULL AFTER `related_to`');
        }
    }
}
