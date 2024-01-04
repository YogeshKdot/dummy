<?php
defined('BASEPATH') or exit('No direct script access allowed');


// Create database schema with relations 

if (!$CI->db->table_exists(db_prefix() . 'automations')) {
  $CI->db->query(
    "CREATE TABLE `" . db_prefix() . "automations` (
      `id` int PRIMARY KEY AUTO_INCREMENT,
      `name` varchar(255),
      `type` ENUM ('task', 'ticket', 'project'),
      `join` ENUM ('and', 'or'),
      `active` BOOLEAN NOT NULL DEFAULT TRUE)
       DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
  );
}

if (!$CI->db->table_exists(db_prefix() . 'automation_triggers')) {
  $CI->db->query(
    "CREATE TABLE `" . db_prefix() . "automation_triggers` (
      `id` int PRIMARY KEY AUTO_INCREMENT,
      `automation_id` int,
      `type` ENUM (
        'status',
        'start_date',
        'finish_date',
        'due_date',
        'priority',
        'custom_field',
        'inactive',
        'task_created',
        'due_date_changed',
        'start_date_changed'
      ),
      `value` varchar(255),
      `additional_argument` varchar(255),
      `last_triggered` DATE NULL DEFAULT NULL,
      `last_triggered_by` INT NULL)
      DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;"
  );

  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'automation_triggers`
        ADD FOREIGN KEY (`automation_id`) 
        REFERENCES `' . db_prefix() . 'automations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
  );
}

if (!$CI->db->table_exists(db_prefix() . 'automation_actions')) {
  $CI->db->query(
    "CREATE TABLE `" . db_prefix() . "automation_actions` (
      `id` int PRIMARY KEY AUTO_INCREMENT,
      `automation_id` int,
      `type` ENUM (
        'change_status',
        'add_comment',
        'add_timer',
        'change_priority',
        'set_follower',
        'set_assignee',
        'add_reminder',
        'set_custom_field',
        'add_tag',
        'change_due_date'
      ),
      `value` varchar(255),
      `additional_argument` varchar(255),
      `additional_argument_2` varchar(255))
      DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;"
  );

  $CI->db->query(
    'ALTER TABLE `' . db_prefix() . 'automation_actions`
        ADD FOREIGN KEY (`automation_id`) 
        REFERENCES `' . db_prefix() . 'automations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
  );
}
