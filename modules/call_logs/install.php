<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* Generate the table in the database. */
if (!$CI->db->table_exists(db_prefix() . 'call_logs')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "call_logs` (
  `id` int(11) NOT NULL,
  `call_purpose` varchar(255) DEFAULT NULL,
  `call_summary` text,
  `call_start_time` datetime NOT NULL,
  `call_end_time` datetime NOT NULL,
  `call_duration` varchar(255) DEFAULT NULL,
  `has_follow_up` tinyint(4) DEFAULT '0',
  `follow_up_schedule` datetime NULL DEFAULT NULL,
  `is_important` tinyint(4) DEFAULT '0',
  `is_completed` tinyint(4) DEFAULT '0',
  `staffid` int(11) DEFAULT '0',
  `call_with_staffid` int(11) DEFAULT '0',
  `call_direction` int(11) DEFAULT '0',
  `notified` tinyint(4) DEFAULT '0',
  
  `customer_type` varchar(255) DEFAULT NULL,
  `clientid` int(11) DEFAULT '0',
  
  `rel_type` varchar(255) DEFAULT NULL,
  `rel_id` int(11) DEFAULT '0'
  
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'call_logs`
  ADD PRIMARY KEY (`id`),ADD KEY `clientid` (`clientid`),
  ADD KEY `staffid` (`staffid`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if ($CI->db->table_exists(db_prefix() . 'call_logs')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'call_logs`
  ADD COLUMN `dateadded` datetime DEFAULT NULL;');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'call_logs`
  ADD COLUMN  `dateaupdated` datetime DEFAULT NULL ;');
}