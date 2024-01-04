<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'team_password_category')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "team_password_category` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `category_name` varchar(150) NOT NULL,
      `icon` varchar(30) NOT NULL,
      `color` varchar(10) NOT NULL,
      `description` text NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'tp_normal')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_normal` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	  `name` varchar(150) null,
	  `url` varchar(300) null,
	  `user_name` varchar(80) null,
	  `notice` text NULL,
	  `password` varchar(200) null,
	  `custom_field` text NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->field_exists('enable_log' ,db_prefix() . 'tp_normal')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
  ADD COLUMN `enable_log` varchar(5) NOT NULL');
}
if (!$CI->db->field_exists('mgt_id' ,db_prefix() . 'tp_normal')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
  ADD COLUMN `mgt_id` int(11) NOT NULL');
}

if (!$CI->db->table_exists(db_prefix() . 'permission')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "permission` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	  `staff` int(11) null,
	  `r` varchar(5) null default 'off',
	  `w` varchar(5) null default 'off',
	
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->field_exists('type' ,db_prefix() . 'permission')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'permission`
  ADD COLUMN `type` varchar(25) NOT NULL,
  ADD COLUMN `mgt_id` int(11) NOT NULL'
  );
}
if (!$CI->db->table_exists(db_prefix() . 'tp_share')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_share` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `not_in_the_system` varchar(5) null,
    `mgt_id` int(11) null,
    `type` varchar(25) null,
    `client` varchar(100) null,
    `email` varchar(100) null,
    `effective_time` datetime null,
    `r` varchar(5) null default 'off',
    `w` varchar(5) null default 'off',
    `creator` int(11) null,
    `datecreator` datetime NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->field_exists('hash' ,db_prefix() . 'tp_share')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_share`
  ADD COLUMN `hash` varchar(300) NULL'
  );
}
if (!$CI->db->field_exists('share_id' ,db_prefix() . 'tp_share')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_share`
  ADD COLUMN `share_id` int(11) NULL'
  );
}

if (!$CI->db->table_exists(db_prefix() . 'tp_bank_account')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_bank_account` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(150) null,
    `url` varchar(300) null,
    `user_name` varchar(80) null,
    `pin` varchar(100) null,
    `bank_name` varchar(200) null,
    `bank_code` varchar(200) null,
    `account_holder` varchar(200) null,
    `account_number` varchar(200) null,
    `iban` varchar(200) null,
    `notice` text NULL,
    `password` varchar(1500) null,
    `enable_log` varchar(5) NOT NULL,
    `mgt_id` int(11) NOT NULL,
    `custom_field` text NULL,
    `datecreator` datetime NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->table_exists(db_prefix() . 'tp_credit_card')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_credit_card` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(150) null,
    `pin` varchar(100) null,    
    `credit_card_type` varchar(150) null,
    `card_number` varchar(150) null,
    `card_cvc` varchar(150) null,
    `valid_from` date NOT NULL,
    `valid_to` date NOT NULL,
    `notice` text NULL,
    `password` varchar(1500) null,
    `enable_log` varchar(5) NOT NULL,
    `mgt_id` int(11) NOT NULL,
    `custom_field` text NULL,
    `datecreator` datetime NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->field_exists('obj_id' ,db_prefix() . 'permission')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'permission`
  ADD COLUMN `obj_id` int(11) NOT NULL');
}

if (!$CI->db->table_exists(db_prefix() . 'tp_email')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_email` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(150) null,
    `pin` varchar(100) null,    
    `credit_card_type` varchar(150) null,
    `card_number` varchar(150) null,
    `card_cvc` varchar(150) null,
    `valid_from` date NOT NULL,
    `valid_to` date NOT NULL,
    `notice` text NULL,
    `email_type` varchar(150) null,
    `auth_method` varchar(150) null,
    `host` varchar(150) null,
    `port` varchar(10) null,
    `smtp_auth_method` varchar(150) null,
    `smtp_host` varchar(150) null,
    `smtp_port` varchar(150) null,
    `smtp_user_name` varchar(150) null,
    `smtp_password` varchar(1500) null,
    `password` varchar(1500) null,
    `enable_log` varchar(5) NOT NULL,
    `mgt_id` int(11) NOT NULL,
    `custom_field` text NULL,
    `datecreator` datetime NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


if (!$CI->db->field_exists('obj_id' ,db_prefix() . 'permission')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'permission`
  ADD COLUMN `user_name` varchar(150) null,');
}
if (!$CI->db->table_exists(db_prefix() . 'tp_server')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_server` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(200) null,
    `user_name` varchar(150) null,
    `notice` text NULL,
    `host` varchar(150) null,
    `port` varchar(10) null,
    `password` varchar(1500) null,
    `enable_log` varchar(5) NOT NULL,
    `mgt_id` int(11) NOT NULL,
    `custom_field` text NULL,
    `datecreator` datetime NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->table_exists(db_prefix() . 'tp_software_license')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_software_license` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(200) null,
    `version` varchar(150) null,
    `url` varchar(150) null,
    `license_key` varchar(150) null,
    `notice` text NULL,
    `host` varchar(150) null,
    `port` varchar(10) null,
    `password` varchar(1500) null,
    `enable_log` varchar(5) NOT NULL,
    `mgt_id` int(11) NOT NULL,
    `custom_field` text NULL,
    `datecreator` datetime NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->field_exists('user_name' ,db_prefix() . 'tp_email')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
  ADD COLUMN `user_name` varchar(150) null;');
}

//Version 1.0.1

if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_normal')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
  ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
}

if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_normal')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
  ADD COLUMN `relate_id` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_bank_account')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
  ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
}

if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_bank_account')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
  ADD COLUMN `relate_id` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_credit_card')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
  ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
}

if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_credit_card')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
  ADD COLUMN `relate_id` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_email')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
  ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
}

if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_email')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
  ADD COLUMN `relate_id` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_server')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
  ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
}

if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_server')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
  ADD COLUMN `relate_id` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('relate_to' ,db_prefix() . 'tp_software_license')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
  ADD COLUMN `relate_to` VARCHAR(50) NOT NULL');
}

if (!$CI->db->field_exists('relate_id' ,db_prefix() . 'tp_software_license')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
  ADD COLUMN `relate_id` INT(11) NOT NULL');
}

create_email_template('Share the link', '<span style=\"font-size: 12pt;\"> Hello {contact_name}. </span><br /><br /><span style=\"font-size: 12pt;\"> We would like to share with you a link of {type} information with the name {obj_name} </span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {share_link}
  </span><br /><br />', 'teampassword', 'Teampassword share the link (Sent to contact)', 'teampassword-share-link-to-contact');

if (row_tp_options_exist('"team_password_security"') == 0){
  $CI->db->query('INSERT INTO `tbloptions` (`name`, `value`, `autoload`) VALUES ("team_password_security", "g8934fuw9843hwe8rf9*5bhv", "1");
');
}

 //Version 1.0.4

if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_normal')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
  ADD COLUMN `add_from` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_bank_account')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
  ADD COLUMN `add_from` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_credit_card')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
  ADD COLUMN `add_from` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_email')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
  ADD COLUMN `add_from` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_server')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
  ADD COLUMN `add_from` INT(11) NOT NULL');
}

if (!$CI->db->field_exists('add_from' ,db_prefix() . 'tp_software_license')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
  ADD COLUMN `add_from` INT(11) NOT NULL');
}

if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_bank_account')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_bank_account`
  CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
}

if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_normal')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_normal`
  CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
}

if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_credit_card')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_credit_card`
  CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
}

if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_email')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_email`
  CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
}

if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_server')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_server`
  CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
}

if ($CI->db->field_exists('relate_id' ,db_prefix() . 'tp_software_license')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tp_software_license`
  CHANGE COLUMN `relate_id` `relate_id` TEXT NULL ;');
}

//logs who see/change password
if (!$CI->db->table_exists(db_prefix() . 'tp_logs')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tp_logs` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `staff` int(11) null,
    `type` varchar(150) null,
    `time` datetime null,
    `rel_id` int(11) null,
    `rel_type` varchar(150) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// version 1.0.6
if (!$CI->db->field_exists('parent' ,db_prefix() . 'team_password_category')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'team_password_category`
  ADD COLUMN `parent` INT(11) NULL default "0" ');
}

// hide password from client area settings
if (row_tp_options_exist('"hide_password_from_client_area"') == 0){
  $CI->db->query('INSERT INTO `' . db_prefix() . 'options` (`name`, `value`, `autoload`) VALUES ("hide_password_from_client_area", "0", "1");
');
}