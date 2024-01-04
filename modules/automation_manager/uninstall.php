<?php

$CI = &get_instance();
$CI->db->query('SET foreign_key_checks = 0;');
$CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'automations`');
$CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'automation_triggers`');
$CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'automation_actions`');
$CI->db->query('SET foreign_key_checks = 1;');
