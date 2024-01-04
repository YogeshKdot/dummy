<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('task_bookmarks_enabled', 1);

if (!$CI->db->table_exists(db_prefix() . 'list_widget')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'list_widget` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `add_from` INT(11) NOT NULL,
    `rel_id` INT(11) NULL,
    `rel_type` VARCHAR(45) NULL,
    `layout` VARCHAR(45) NULL,
    PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'task_bookmarks')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'task_bookmarks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `creator` INT(11) NOT NULL,
  `icon` VARCHAR(255) NULL,
  `color` VARCHAR(255) NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'task_bookmarks_detail')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'task_bookmarks_detail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `task_bookmarks_id` INT(11) NOT NULL,
  `task_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`));');
}