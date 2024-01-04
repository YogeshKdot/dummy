<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'fe_depreciations')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "fe_depreciations` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` varchar(200) NOT NULL,
		`term` double NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_locations')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "fe_locations` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`location_name` varchar(200) NOT NULL,
		`parent` int(11) NULL,
		`manager` int(11) NULL,
		`location_currency` varchar(50) NULL,
		`address` text NULL,
		`city` varchar(50) NULL,
		`state` varchar(50) NULL,
		`country` varchar(50) NULL,
		`zip` varchar(50) NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_suppliers')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "fe_suppliers` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`supplier_name` varchar(200) NOT NULL,
	`address` text NULL,
	`city` varchar(50) NULL,
	`state` varchar(50) NULL,
	`country` varchar(50) NULL,
	`zip` varchar(50) NULL,
	`contact_name` varchar(200) NULL,
	`phone` varchar(50) NULL,
	`fax` varchar(100) NULL,
	`email` varchar(100) NULL,
	`url` text NULL,
	`note` text NULL,
	`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_asset_manufacturers')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "fe_asset_manufacturers` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(200) NOT NULL,
	`url` text NULL,
	`support_url` text NULL,
	`support_phone` varchar(50) NULL,
	`support_email` varchar(100) NULL,
	`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


if (!$CI->db->table_exists(db_prefix() . 'fe_categories')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "fe_categories` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`category_name` varchar(200) NOT NULL,
		`type` varchar(30) NULL,
		`category_eula` text NULL,
		`primary_default_eula` bit NOT NULL DEFAULT 0,
		`confirm_acceptance` bit NOT NULL DEFAULT 0,
		`send_mail_to_user` bit NOT NULL DEFAULT 0,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_models')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "fe_models` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`model_name` varchar(200) NOT NULL,
	`manufacturer` int(11) NULL,
	`category` int(11) NULL,
	`model_no` varchar(200) NULL,
	`depreciation` int(11) NULL,
	`eol` int(11) NULL,
	`note` text NULL,
	`custom_field` text NULL,
	`may_request` bit NOT NULL DEFAULT 0,
	`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->table_exists(db_prefix() . 'fe_status_labels')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "fe_status_labels` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(200) NOT NULL,
	`status_type` varchar(30) NOT NULL,
	`chart_color` varchar(30) NOT NULL,
	`note` text NULL,
	`show_in_side_nav` bit NOT NULL DEFAULT 0,
	`default_label` bit NOT NULL DEFAULT 0,
	`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_assets')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_assets` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`assets_code` VARCHAR(20) NULL,
		`assets_name` VARCHAR(255) NULL,
		`series` VARCHAR(200) NULL,
		`asset_group` INT(11) NULL,
		`asset_location` INT(11) NULL,
		`model_id` INT(11) NULL,
		`date_buy` DATE NULL,
		`warranty_period` INT(11) NULL,
		`unit_price` DECIMAL(15,2) NULL,
		`depreciation` INT(11) NULL,
		`supplier_id` INT(11) NULL,
		`order_number` VARCHAR(150) NULL,
		`description` TEXT NULL,
		`requestable` INT(11) NULL DEFAULT 0,
		`qr_code` VARCHAR(300) NULL,
		`type` VARCHAR(50) NOT NULL DEFAULT "asset",
		`status` INT(11) NOT NULL DEFAULT "1",
		`checkin_out` INT(11) NOT NULL DEFAULT "1",
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('quantity' ,db_prefix() . 'fe_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_assets`
	ADD COLUMN `quantity` INT(11) NULL,
	ADD COLUMN `min_quantity` INT(11) NULL
	');
}

if (!$CI->db->field_exists('category_id' ,db_prefix() . 'fe_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_assets`
	ADD COLUMN `category_id` INT(11) NULL,
	ADD COLUMN `product_key` text NULL,
	ADD COLUMN `seats` varchar(50) NULL,
	ADD COLUMN `model_no` varchar(80) NULL,
	ADD COLUMN `location_id` INT(11) NULL,
	ADD COLUMN `manufacturer_id` INT(11) NULL,
	ADD COLUMN `licensed_to_name` text NULL,
	ADD COLUMN `licensed_to_email` text NULL,
	ADD COLUMN `reassignable` INT(11) NOT NULL DEFAULT 0,
	ADD COLUMN `termination_date` DATE NULL,
	ADD COLUMN `expiration_date` DATE NULL,
	ADD COLUMN `purchase_order_number` VARCHAR(150) NULL,
	ADD COLUMN `maintained` INT(11) NOT NULL DEFAULT 0              
	');
}

if (!$CI->db->field_exists('item_no' ,db_prefix() . 'fe_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_assets`
	ADD COLUMN `item_no` varchar(80) NULL
	');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_log_assets')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_log_assets` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`admin_id` INT(11) NULL,
		`action` VARCHAR(200) NULL,
		`item_id` INT(11) NULL,
		`target` VARCHAR(200) NULL,
		`changed` VARCHAR(200) NULL,
		`to` VARCHAR(20) NULL,
		`to_id` INT(11) NULL,
		`notes` text NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_checkin_assets` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`model` VARCHAR(200) NULL,
		`asset_name` VARCHAR(200) NULL,
		`item_id` INT(11) NULL,
		`status` INT(11) NULL,
		`quantity` INT NULL,
		`checkout_to` VARCHAR(20) NULL,
		`location_id` INT(11) NULL,
		`asset_id` INT(11) NULL,
		`staff_id` INT(11) NULL,
		`checkin_date` date NULL,
		`expected_checkin_date` date NULL,
		`type` VARCHAR(50) NULL,
		`notes` text NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_seats')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_seats` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`seat_name` VARCHAR(200) NULL,
		`to` VARCHAR(20) NULL,
		`to_id` INT(11) NULL,
		`license_id` INT(11) NOT NULL,
		`status` INT(11) NOT NULL DEFAULT 1,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_model_predefined_kits')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_model_predefined_kits` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`parent_id` INT(11) NULL,
		`model_id` INT(11) NULL,
		`quantity` INT NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_asset_maintenances')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_asset_maintenances` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`asset_id` INT(11) NULL,
		`supplier_id` INT(11) NULL,
		`maintenance_type` varchar(30) NULL,
		`title` varchar(250) NULL,
		`start_date` DATE NULL,
		`completion_date` DATE NULL,
		`cost` DECIMAL(15,2) NULL,
		`notes` text NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('warranty_improvement' ,db_prefix() . 'fe_asset_maintenances')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_asset_maintenances`
	ADD COLUMN `warranty_improvement` INT(11) NOT NULL DEFAULT 0
	');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_approval_setting')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_approval_setting` (
		`id` INT NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(255) NOT NULL,
		`related` VARCHAR(255) NOT NULL,
		`setting` LONGTEXT NOT NULL,
		`choose_when_approving` INT NOT NULL DEFAULT 0,
		`notification_recipient` LONGTEXT  NULL,
		`number_day_approval` INT(11) NULL,
		`departments` TEXT NULL,
		`job_positions` TEXT NULL,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_approval_details')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_approval_details` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`rel_id` INT(11) NOT NULL,
		`rel_type` VARCHAR(45) NOT NULL,
		`staffid` VARCHAR(45) NULL,
		`approve` VARCHAR(45) NULL,
		`note` TEXT NULL,
		`date` DATETIME NULL,
		`approve_action` VARCHAR(255) NULL,
		`reject_action` VARCHAR(255) NULL,
		`approve_value` VARCHAR(255) NULL,
		`reject_value` VARCHAR(255) NULL,
		`staff_approve` INT(11) NULL,
		`action` VARCHAR(45) NULL,
		`sender` INT(11) NULL,
		`date_send` DATETIME NULL,
		`notification_recipient` LONGTEXT NULL,
		`approval_deadline` DATE NULL,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('check_status' ,db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_checkin_assets`
	ADD COLUMN `check_status` INT(11) NOT NULL DEFAULT 2
	');
}

if (!$CI->db->field_exists('requestable' ,db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_checkin_assets`
	ADD COLUMN `requestable` INT(11) NOT NULL DEFAULT 0
	');
}

if (!$CI->db->field_exists('request_status' ,db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_checkin_assets`
	ADD COLUMN `request_status` INT(11) NOT NULL DEFAULT 0
	');
}

if (!$CI->db->field_exists('checkin_out_id' ,db_prefix() . 'fe_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_assets`
	ADD COLUMN `checkin_out_id` INT(11) NULL
	');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_fieldsets')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_fieldsets` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(300) NULL,
		`notes` text NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_custom_fields')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_custom_fields` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`title` varchar(300) NULL,
		`type` varchar(30) NULL,
		`option` text NULL,
		`required` INT NOT NULL DEFAULT 1,
		`default_value` text NULL,
		`fieldset_id` INT(11) NOT NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('fieldset_id' ,db_prefix() . 'fe_models')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_models`
	ADD COLUMN `fieldset_id` INT(11) NULL
	');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_custom_field_values')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_custom_field_values` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`title` varchar(300) NULL,
		`type` varchar(30) NULL,
		`option` text NULL,
		`required` INT NOT NULL DEFAULT 1,
		`value` text NULL,
		`fieldset_id` INT(11) NOT NULL,
		`custom_field_id` INT(11) NOT NULL,
		`asset_id` INT(11) NOT NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_audit_requests')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_audit_requests` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`title` varchar(300) NULL,
		`audit_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`auditor` INT(11) NULL,
		`asset_location` INT(11) NULL,
		`model_id` INT(11) NULL,
		`asset_id` text NULL,
		`checkin_checkout_status` INT(11) NULL,
		`status` INT NOT NULL DEFAULT 0,
		`closed` INT NOT NULL DEFAULT 0,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_audit_detail_requests')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_audit_detail_requests` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`asset_id` INT(11) NULL,
		`asset_name` varchar(300) NULL,
		`type` varchar(30) NULL,
		`quantity` INT NULL,
		`adjusted` INT NULL,
		`accept` INT NOT NULL DEFAULT 0,
		`audit_id` INT(11) NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}
if (!$CI->db->field_exists('active' ,db_prefix() . 'fe_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_assets`
	ADD COLUMN `active` INT NOT NULL DEFAULT 1
	');
}
if (!$CI->db->field_exists('request_title' ,db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_checkin_assets`
	ADD COLUMN `request_title` varchar(300) NULL
	');
}
add_option('fe_googlemap_api_key', '');

if (!$CI->db->field_exists('predefined_kit_id' ,db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_checkin_assets`
	ADD COLUMN `predefined_kit_id` INT(11) NULL
	');
}

if (!$CI->db->field_exists('item_type' ,db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_checkin_assets`
	ADD COLUMN `item_type` varchar(30) NULL
	');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_sign_documents')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_sign_documents` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`checkin_out_id` text NULL,
		`status` INT NOT NULL DEFAULT 1,
		`check_to_staff` INT(11) NULL,
		`reference` varchar(30) NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'fe_signers')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() .'fe_signers` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`sign_document_id` INT(11) NULL,
		`staff_id` INT(11) NULL,
		`ip_address` varchar(100) NULL,
		`date_of_signing` datetime NULL,
		`date_creator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`));');
}

if (!$CI->db->field_exists('firstname' ,db_prefix() . 'fe_signers')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_signers`
	ADD COLUMN `firstname` varchar(50) NULL,
	ADD COLUMN `lastname` varchar(50) NULL,
	ADD COLUMN `email` varchar(100) NULL
	');
}

if ($CI->db->table_exists(db_prefix() . 'fe_checkin_assets')) {
	$CI->db->query('UPDATE '.db_prefix().'fe_checkin_assets INNER JOIN '.db_prefix().'fe_assets ON  '.db_prefix().'fe_assets.checkin_out_id = '.db_prefix().'fe_checkin_assets.id set item_type = '.db_prefix().'fe_assets.type');
}

if (!$CI->db->field_exists('maintenance' ,db_prefix() . 'fe_audit_detail_requests')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_audit_detail_requests`
	ADD COLUMN `maintenance` INT(11) NOT NULL DEFAULT 0
	');
}
if (!$CI->db->field_exists('maintenance_id' ,db_prefix() . 'fe_audit_detail_requests')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_audit_detail_requests`
	ADD COLUMN `maintenance_id` INT(11) NOT NULL DEFAULT 0
	');
}

