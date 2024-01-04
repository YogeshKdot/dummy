<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Table for settings mfa options
if (!$CI->db->table_exists(db_prefix() . 'mfa_options')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "mfa_options` (
      `option_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `option_name` varchar(200) NOT NULL,
      `option_val` longtext NULL,
      `auto` tinyint(1) NULL,
      PRIMARY KEY (`option_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (row_mfa_options_exist('"enable_mfa"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("enable_mfa", "0", "1");
');
}

if (row_mfa_options_exist('"enable_google_authenticator"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("enable_google_authenticator", "0", "1");
');
}

if (row_mfa_options_exist('"enable_whatsapp"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("enable_whatsapp", "0", "1");
');
}

if (row_mfa_options_exist('"google_authenticator_secret_key"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("google_authenticator_secret_key", "", "1");
');
}

if (row_mfa_options_exist('"enable_sms"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("enable_sms", "0", "1");
');
}

if (row_mfa_options_exist('"twilio_account_sid"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("twilio_account_sid", "", "1");
');
}

if (row_mfa_options_exist('"twilio_auth_token"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("twilio_auth_token", "", "1");
');
}

if (row_mfa_options_exist('"twilio_phone_number"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("twilio_phone_number", "", "1");
');
}

if (row_mfa_options_exist('"twilio_phone_number_for_sms"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("twilio_phone_number_for_sms", "", "1");
');
}

if (row_mfa_options_exist('"delete_history_after_months"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("delete_history_after_months", "6", "1");
');
}

if (row_mfa_options_exist('"whatsapp_message_template"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("whatsapp_message_template", "Your login code for {{1}} is {{2}}", "1");
');
}

// Additional fields tblstaff
if (!$CI->db->field_exists('mfa_google_ath_enable' ,db_prefix() . 'staff')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`
    ADD COLUMN `mfa_google_ath_enable` TINYINT(1) NULL DEFAULT "0"');
}

if (!$CI->db->field_exists('mfa_whatsapp_enable' ,db_prefix() . 'staff')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`
    ADD COLUMN `mfa_whatsapp_enable` TINYINT(1) NULL DEFAULT "0"');
}

if (!$CI->db->field_exists('mfa_sms_enable' ,db_prefix() . 'staff')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`
    ADD COLUMN `mfa_sms_enable` TINYINT(1) NULL DEFAULT "0"');
}

if (!$CI->db->field_exists('whatsapp_number' ,db_prefix() . 'staff')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`
    ADD COLUMN `whatsapp_number` text NULL DEFAULT ""');
}

// Table for mangae security code of staff
if (!$CI->db->table_exists(db_prefix() . 'mfa_security_code')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "mfa_security_code` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `staff` int(11) NOT NULL,
      `code` text NOT NULL,
      `created_at` DATETIME NOT NULL,
      `type` varchar(50) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// Table for history login with MFA module
if (!$CI->db->table_exists(db_prefix() . 'mfa_history_login')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "mfa_history_login` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `staff` int(11) NOT NULL,
      `type` varchar(100) NOT NULL,
      `status` varchar(50) NOT NULL,
      `mess` text NULL,
      `time` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// Table history send security code
if (!$CI->db->table_exists(db_prefix() . 'mfa_send_code_logs')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "mfa_send_code_logs` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `staff` int(11) NOT NULL,
      `type` varchar(100) NOT NULL,
      `status` varchar(50) NOT NULL,
      `mess` text NULL,
      `time` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('gg_auth_secret_key' ,db_prefix() . 'staff')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`
    ADD COLUMN `gg_auth_secret_key` text NULL DEFAULT ""');
}

if (!$CI->db->field_exists('enable_gg_auth' ,db_prefix() . 'roles')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'roles`
    ADD COLUMN `enable_gg_auth` int(1) NULL DEFAULT "0"');
}

if (row_mfa_options_exist('"enable_gg_auth_for_users_have_not_role"') == 0){
  $CI->db->query('INSERT INTO `'.db_prefix().'mfa_options` (`option_name`, `option_val`, `auto`) VALUES ("enable_gg_auth_for_users_have_not_role", "0", "1");
');
}