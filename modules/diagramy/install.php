<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI_OBJECT = &get_instance();

add_option('staff_members_create_inline_diagramy_group', 1);

if (!$CI_OBJECT->db->table_exists(db_prefix().'diagramy')) {
    $CI_OBJECT->db->query('CREATE TABLE `'.db_prefix()."diagramy` (
    `id` int(11) NOT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` text,
    `staffid` int(11) DEFAULT '0' ,
    `diagramy_group_id` int(11) DEFAULT '0' ,
    `diagramy_content` text,
    `diagramy_slug` varchar(255) DEFAULT NULL,
    `dateadded` datetime DEFAULT NULL,
    `dateaupdated` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=".$CI_OBJECT->db->char_set.';');

    $CI_OBJECT->db->query('ALTER TABLE `'.db_prefix().'diagramy`
    ADD PRIMARY KEY (`id`),
    ADD KEY `staffid` (`staffid`),
    ADD KEY `diagramy_group_id` (`diagramy_group_id`);');

    $CI_OBJECT->db->query('ALTER TABLE `'.db_prefix().'diagramy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if (!$CI_OBJECT->db->table_exists(db_prefix().'diagramy_groups')) {
    $CI_OBJECT->db->query('CREATE TABLE `'.db_prefix().'diagramy_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET='.$CI_OBJECT->db->char_set.';');

    $CI_OBJECT->db->query('ALTER TABLE `'.db_prefix().'diagramy_groups`
  ADD PRIMARY KEY (`id`);');

    $CI_OBJECT->db->query('ALTER TABLE `'.db_prefix().'diagramy_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

      //add related_to field in diagramy table
      if (!$CI_OBJECT->db->field_exists('related_to', db_prefix().'diagramy')) {
          $CI_OBJECT->db->query('ALTER TABLE `'.db_prefix().'diagramy`
        ADD `related_to` VARCHAR(255) NOT NULL AFTER `description`');
      }

      //add rel_id field in diagramy table
      if (!$CI_OBJECT->db->field_exists('rel_id', db_prefix().'diagramy')) {
          $CI_OBJECT->db->query('ALTER TABLE `'.db_prefix().'diagramy`
        ADD `rel_id` INT(11) NOT NULL AFTER `related_to`');
      }
