<?php
defined('BASEPATH') or exit('No direct script access allowed');


// Create database schema with relations 

if (!$CI->db->table_exists(db_prefix() . 'task_statuses')) {
  $CI->db->query(
    'CREATE TABLE `' . db_prefix() . 'task_statuses` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `order` INT(11) NOT NULL,
        `name` TEXT NOT NULL,
        `color` TEXT NOT NULL,
        `filter_default` BOOLEAN,
        PRIMARY KEY (`id`) )
        DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
  );
}

if (!$CI->db->table_exists(db_prefix() . 'task_status_dont_have_staff')) {
  $CI->db->query(
    'CREATE TABLE `' . db_prefix() . 'task_status_dont_have_staff` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `task_status_id` INT(11) NOT NULL,
        `staff_id` INT(11) NOT NULL,
        PRIMARY KEY (`id`))
        DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;'
  );

  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'task_status_dont_have_staff`
        ADD CONSTRAINT `task_status_dont_have_staff_task_status_id` FOREIGN KEY (`task_status_id`) 
        REFERENCES `' . db_prefix() . 'task_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
  );

  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'task_status_dont_have_staff`
        ADD CONSTRAINT `task_status_dont_have_staff_staff_id` FOREIGN KEY (`staff_id`) 
        REFERENCES `' . db_prefix() . 'staff` (`staffid`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
  );
}

if (!$CI->db->table_exists(db_prefix() . 'task_status_can_change')) {
  $CI->db->query(
    'CREATE TABLE `' . db_prefix() . 'task_status_can_change` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `task_status_id` INT(11) NOT NULL,
        `task_status_id_can_change_to` INT(11) NOT NULL,
        PRIMARY KEY (`id`))
        DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
  );

  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'task_status_can_change`
        ADD CONSTRAINT `task_status_can_change_task_status_id` FOREIGN KEY (`task_status_id`) 
        REFERENCES `' . db_prefix() . 'task_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
  );

  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'task_status_can_change`
        ADD CONSTRAINT `task_status_can_change_task_status_id_2` FOREIGN KEY (`task_status_id_can_change_to`) 
        REFERENCES `' . db_prefix() . 'task_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
  );
}

$CI->load->model('Tasks_model');

$statuses = $CI->tasks_model->get_statuses();

// Create default statuses
foreach ($statuses as $status) {
  $CI->db->query("INSERT INTO " . db_prefix() . "task_statuses (`id`, `name`, `color` ,`order`, `filter_default`) VALUES ({$status['id']},'{$status['name']}','{$status['color']}',{$status['order']}," . intval($status['filter_default']) . ") ON DUPLICATE KEY UPDATE id={$status['id']}");
}


// -------------- Project Statuses ------------------

if (!$CI->db->table_exists(db_prefix() . 'project_statuses')) {
  $CI->db->query(
    'CREATE TABLE `' . db_prefix() . 'project_statuses` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `order` INT(11) NOT NULL,
        `name` TEXT NOT NULL,
        `color` TEXT NOT NULL,
        `filter_default` BOOLEAN,
        PRIMARY KEY (`id`) )
  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
  );
}

if (!$CI->db->table_exists(db_prefix() . 'project_status_can_change')) {
  $CI->db->query(
    'CREATE TABLE `' . db_prefix() . 'project_status_can_change` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `project_status_id` INT(11) NOT NULL,
        `project_status_id_can_change_to` INT(11) NOT NULL,
        PRIMARY KEY (`id`))
  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
  );
  
  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'project_status_can_change`
  ADD CONSTRAINT `project_status_can_change_project_status_id` FOREIGN KEY (`project_status_id`) 
  REFERENCES `' . db_prefix() . 'project_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  '
  );

  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'project_status_can_change`
  ADD CONSTRAINT `project_status_can_change_project_status_id_2` FOREIGN KEY (`project_status_id_can_change_to`) 
  REFERENCES `' . db_prefix() . 'project_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  '
  );
}



$CI->load->model('Projects_model');

$statuses = $CI->Projects_model->get_project_statuses();

// Create default statuses
foreach ($statuses as $status) {
  $CI->db->query("INSERT INTO " . db_prefix() . "project_statuses (`id`, `name`, `color` ,`order`, `filter_default`) VALUES ({$status['id']},'{$status['name']}','{$status['color']}',{$status['order']}," . intval($status['filter_default']) . ") ON DUPLICATE KEY UPDATE id={$status['id']}");
}
