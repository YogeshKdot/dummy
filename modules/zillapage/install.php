<?php

defined('BASEPATH') or exit('No direct script access allowed');

define('ZILLAPAGE_SQL_PATH', 'modules/zillapage/sql');

// create form data table 
if (!$CI->db->table_exists(db_prefix() . 'landing_page_form_data')) {

    $CI->db->query('CREATE TABLE `' . db_prefix() . "landing_page_form_data` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `landing_page_id` int(10) UNSIGNED NOT NULL,
      `field_values` text COLLATE utf8mb4_unicode_ci NOT NULL,
      `browser` text COLLATE utf8mb4_unicode_ci,
      `os` text COLLATE utf8mb4_unicode_ci,
      `device` text COLLATE utf8mb4_unicode_ci,
      `created_at` DATETIME NULL,
      `updated_at` DATETIME NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_form_data`
      ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_form_data`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
}

// create templates data table 
if (!$CI->db->table_exists(db_prefix() . 'landing_page_templates')) {

    $CI->db->query('CREATE TABLE `' . db_prefix() . "landing_page_templates` (
      `id` int(10) UNSIGNED NOT NULL,
      `name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
      `thumb` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `thank_you_page` longtext COLLATE utf8mb4_unicode_ci,
      `content` longtext COLLATE utf8mb4_unicode_ci,
      `style` longtext COLLATE utf8mb4_unicode_ci,
      `active` tinyint(1) NOT NULL DEFAULT '0',
      `created_at` DATETIME NULL,
      `updated_at` DATETIME NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_templates`
      ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_templates`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

    // Default Data
    $CI->db->query('INSERT INTO `' . db_prefix() . 'landing_page_templates`'. file_get_contents(ZILLAPAGE_SQL_PATH.'/landing_page_templates.sql'));
    
}
// create landing_page_blocks table 
if (!$CI->db->table_exists(db_prefix() . 'landing_page_blocks')) {

    $CI->db->query('CREATE TABLE `' . db_prefix() . "landing_page_blocks` (
      `id` int(10) UNSIGNED NOT NULL,
      `block_category` varchar(190) NOT NULL,
      `thumb` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
      `content` longtext COLLATE utf8mb4_unicode_ci,
      `style` longtext COLLATE utf8mb4_unicode_ci,
      `active` tinyint(1) NOT NULL DEFAULT '0',
      `created_at` DATETIME NULL,
      `updated_at` DATETIME NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_blocks`
      ADD PRIMARY KEY (`id`);
    ');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_blocks`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

    // Default Data
    $CI->db->query('INSERT INTO `' . db_prefix() . 'landing_page_blocks`'. file_get_contents(ZILLAPAGE_SQL_PATH.'/landing_page_blocks.sql'));
    
}
// create landing_pages data table 
if (!$CI->db->table_exists(db_prefix() . 'landing_pages')) {

    $CI->db->query('CREATE TABLE `' . db_prefix() . "landing_pages` (
      `id` int(10) UNSIGNED NOT NULL,
      `code` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
      `html` longtext COLLATE utf8mb4_unicode_ci,
      `css` longtext COLLATE utf8mb4_unicode_ci,
      `components` longtext COLLATE utf8mb4_unicode_ci,
      `styles` longtext COLLATE utf8mb4_unicode_ci,
      `main_page_script` longtext COLLATE utf8mb4_unicode_ci,
      `thank_you_page_html` longtext COLLATE utf8mb4_unicode_ci,
      `thank_you_page_css` longtext COLLATE utf8mb4_unicode_ci,
      `thank_you_page_components` longtext COLLATE utf8mb4_unicode_ci,
      `thank_you_page_styles` longtext COLLATE utf8mb4_unicode_ci,
      `favicon` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `seo_title` text COLLATE utf8mb4_unicode_ci,
      `seo_description` text COLLATE utf8mb4_unicode_ci,
      `seo_keywords` text COLLATE utf8mb4_unicode_ci,
      `social_title` text COLLATE utf8mb4_unicode_ci,
      `social_image` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `social_description` text COLLATE utf8mb4_unicode_ci,
      `custom_header` longtext COLLATE utf8mb4_unicode_ci,
      `custom_footer` longtext COLLATE utf8mb4_unicode_ci,
      `type_form_submit` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'thank_you_page',
      `redirect_url` text COLLATE utf8mb4_unicode_ci,
      `type_payment_submit` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'thank_you_page',
      `redirect_url_payment` text COLLATE utf8mb4_unicode_ci,
      `is_publish` tinyint(1) NOT NULL DEFAULT '0',
      `is_trash` tinyint(1) NOT NULL DEFAULT '0',
      `notify_lead_imported` int(11) NOT NULL DEFAULT '1',
      `notify_type` varchar(20) DEFAULT 'assigned',
      `notify_ids` mediumtext,
      `responsible` int(11) NOT NULL DEFAULT '0',
      `created_at` DATETIME NULL,
      `updated_at` DATETIME NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_pages`
      ADD PRIMARY KEY (`id`);
    ');


    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_pages`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
}


// create landing_page_settings 
if (!$CI->db->table_exists(db_prefix() . 'landing_page_settings')) {

    $CI->db->query('CREATE TABLE `' . db_prefix() . "landing_page_settings` (
      `id` int(10) UNSIGNED NOT NULL,
      `key` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
      `value` text COLLATE utf8mb4_unicode_ci
   ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_settings`
      ADD PRIMARY KEY (`id`);
    ');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_settings`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

    // Default Data
    $CI->db->query('INSERT INTO `' . db_prefix() . 'landing_page_settings`'. file_get_contents(ZILLAPAGE_SQL_PATH.'/landing_page_settings.sql'));
}

