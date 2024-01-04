<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI_OBJECT = &get_instance();
delete_option('staff_members_create_inline_diagramy_group');

$CI_OBJECT->db->query('DROP TABLE `'.db_prefix().'diagramy`');
$CI_OBJECT->db->query('DROP TABLE `'.db_prefix().'diagramy_groups`');
