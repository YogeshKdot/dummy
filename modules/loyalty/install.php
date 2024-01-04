<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (row_loyalty_options_exist('"loyalty_setting"') == 0){
  $CI->db->query('INSERT INTO `tbloptions` (`name`, `value`, `autoload`) VALUES ("loyalty_setting", "1", "1");
');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_card')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_card` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `add_from` INT(11) NOT NULL,
  `date_create` DATE NULL,
  `subject_card` INT(2) NULL DEFAULT "0",
  `client_name` INT(2) NULL DEFAULT "0",
  `membership` INT(2) NULL DEFAULT "0",
  `company_name` INT(2) NULL DEFAULT "0",
  `member_since` INT(2) NULL DEFAULT "0",
  `custom_field` INT(2) NULL DEFAULT "0",
  `custom_field_content` VARCHAR(200) NULL,
  `text_color` VARCHAR(25) NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_rule')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_rule` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `subject` VARCHAR(200) NOT NULL,
  `add_from` INT(11) NOT NULL,
  `date_create` DATE NULL,
  `enable` INT(2) NULL DEFAULT "0",
  `redeemp_type` VARCHAR(15) NULL,
  `min_poin_to_redeem` DECIMAL(15,0) NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `rule_base` VARCHAR(30) NULL,
  `minium_purchase` DECIMAL(15,0) NULL,
  `poin_awarded` DECIMAL(15,0) NULL,
  `purchase_value` DECIMAL(15,0) NULL,
  `note` TEXT NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_rule_detail')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_rule_detail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `loy_rule` INT(11) NOT NULL,
  `rel_type` VARCHAR(30) NOT NULL,
  `rel_id` TEXT NOT NULL,
  `loyalty_point` DECIMAL(15,0) NULL,
 
  PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_redemp_detail')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_redemp_detail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `loy_rule` INT(11) NOT NULL,
  `rule_name` VARCHAR(200) NOT NULL,
  `point_from` DECIMAL(15,0) NULL,
  `point_to` DECIMAL(15,0) NULL,
  `point_weight` DECIMAL(15,0) NULL,
  `status` VARCHAR(20) NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_mbs_rule')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_mbs_rule` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `add_from` INT(11) NOT NULL,
  `date_create` DATE NULL,
  `client_group` INT(11) NULL,
  `client` TEXT NOT NULL,
  `loyalty_point_from` DECIMAL(15,0) NULL,
  `loyalty_point_to` DECIMAL(15,0) NULL,
  `card` INT(11) NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_mbs_program')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_mbs_program` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `program_name` VARCHAR(200) NOT NULL,
  `add_from` INT(11) NOT NULL,
  `date_create` DATE NULL,
  `voucher_code` TEXT NOT NULL,
  `discount` VARCHAR(30) NULL,
  `discount_percent` INT(5) NULL,
  `loyalty_point_from` DECIMAL(15,0) NULL,
  `loyalty_point_to` DECIMAL(15,0) NULL,
  `membership` TEXT NOT NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `note` TEXT NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_program_detail')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_program_detail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `mbs_program` INT(11) NOT NULL,
  `rel_type` VARCHAR(30) NOT NULL,
  `rel_id` TEXT NOT NULL,
  `percent` INT(5) NULL,
 
  PRIMARY KEY (`id`));');
}


if (!$CI->db->table_exists(db_prefix() . 'loy_transation')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_transation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reference` VARCHAR(30) NOT NULL,
  `invoice` INT(11) NULL,
  `client` INT(11) NOT NULL,
  `add_from` INT(11) NULL,
  `date_create` DATETIME NULL,
  `loyalty_point` DECIMAL(15,0) NULL,
  `type` VARCHAR(30) NULL,
  `note` TEXT NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('loy_point' ,db_prefix() . 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients`
    ADD COLUMN `loy_point` DECIMAL NULL DEFAULT "0"');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_redeem_log')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_redeem_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `client` INT(11) NOT NULL,
  `cart` INT(11) NULL,
  `invoice` INT(11) NULL,
  `time` DATETIME NULL,
  `old_point` DECIMAL(10,0) NULL,
  `new_point` DECIMAL(10,0) NULL,
  `redeep_from` DECIMAL(10,0) NULL,
  `redeep_to` DECIMAL(15,2) NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('redeem_portal' ,db_prefix() . 'loy_rule')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
    ADD COLUMN `redeem_portal` INT(1) NULL DEFAULT "0"');
}

if (!$CI->db->field_exists('redeem_pos' ,db_prefix() . 'loy_rule')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
    ADD COLUMN `redeem_pos` INT(1) NULL DEFAULT "0"');
}

if (!$CI->db->field_exists('voucher_value' ,db_prefix() . 'loy_mbs_program')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_mbs_program`
    ADD COLUMN `voucher_value` DECIMAL(15,2) NULL DEFAULT "0"');
}

if (!$CI->db->field_exists('formal' ,db_prefix() . 'loy_mbs_program')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_mbs_program`
    ADD COLUMN `formal` INT(1) NULL DEFAULT "1"');
}

if (!$CI->db->field_exists('minium_purchase' ,db_prefix() . 'loy_mbs_program')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_mbs_program`
    ADD COLUMN `minium_purchase`  DECIMAL(15,2) NULL DEFAULT "0"');
}

if ($CI->db->field_exists('point_weight' ,db_prefix() . 'loy_redemp_detail')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_redemp_detail`
    CHANGE COLUMN `point_weight` `point_weight` DECIMAL(15,2) NULL DEFAULT NULL ;');
}

if (!$CI->db->field_exists('client_group' ,db_prefix() . 'loy_rule')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
    ADD COLUMN `client_group` INT(11) NULL DEFAULT "0"');
}

if (!$CI->db->field_exists('client' ,db_prefix() . 'loy_rule')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
    ADD COLUMN `client` TEXT NOT NULL');
}

if (!$CI->db->field_exists('max_amount_received' ,db_prefix() . 'loy_rule')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
    ADD COLUMN `max_amount_received` INT(3) NOT NULL');
}

// Ver 1.0.2
if (!$CI->db->table_exists(db_prefix() . 'loy_voucher_inv_log')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_voucher_inv_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `client` INT(11) NOT NULL,
  `voucher_code` TEXT NULL,
  `invoice` INT(11) NULL,
  `time` DATETIME NULL,
  `value` DECIMAL(15,2) NULL,
  PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('create_account_point' ,db_prefix() . 'loy_rule')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
    ADD COLUMN `create_account_point` INT(11) NULL');
}

if (!$CI->db->field_exists('birthday_point' ,db_prefix() . 'loy_rule')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'loy_rule`
    ADD COLUMN `birthday_point` INT(11) NULL');
}

if (!$CI->db->table_exists(db_prefix() . 'loy_program_discount_log')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() .'loy_program_discount_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `client` INT(11) NOT NULL,
  `mbs_program` INT(11) NULL,
  `invoice` INT(11) NULL,
  `time` DATETIME NULL,
  `value` DECIMAL(15,2) NULL,
  `note` TEXT NULL,
  PRIMARY KEY (`id`));');
}

add_custom_field_loy([
  'fieldto' => 'customers',
  'name' => 'Birthday',
  'required' => 1,
  
  'type' => 'date_picker',
  'bs_column' => 12,
  'show_on_table' => 1,
  'show_on_client_portal' => 1,
]);

create_email_template('New account bonus point', '<span style=\"font-size: 12pt;\"> Hello {contact_name}. </span><br /><br /><span style=\"font-size: 12pt;\"> You get {points_received} points from our loyalty program for new account. Hope you have the best shopping experience on our system!</span><br /><br />', 'loyalty', 'Loyalty new account bonus point (Sent to contact)', 'loyalty-new-account-bonus-point');

create_email_template('Birthday bonus point', '<span style=\"font-size: 12pt;\"> Hello {contact_name}. </span><br /><br /><span style=\"font-size: 12pt;\"> Happy birthday to you! You get {points_received} points from our loyalty program for your bithday. Hope you have the best shopping experience on our system!</span><br /><br />', 'loyalty', 'Loyalty birthday bonus point (Sent to contact)', 'loyalty-birthday-bonus-point');