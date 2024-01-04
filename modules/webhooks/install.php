<?php

defined('BASEPATH') || exit('No direct script access allowed');
add_option('webhooks_enabled', 1);
if (!$CI->db->table_exists(db_prefix().'webhooks_master')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'webhooks_master` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `name` VARCHAR(200) NOT NULL ,
    `webhook_for` VARCHAR(50) NOT NULL ,
    `webhook_action` JSON NOT NULL ,
    `request_url` TEXT NOT NULL ,
    `active` TINYINT NOT NULL DEFAULT "1",
    `request_method` VARCHAR(100) NOT NULL ,
    `request_format` VARCHAR(20) NOT NULL ,
    `request_header` JSON NOT NULL ,
    `request_body` JSON NOT NULL ,
    `debug_mode` TINYINT NOT NULL DEFAULT "0",
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'webhooks_debug_log')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'webhooks_debug_log` (
        `id` INT NOT NULL AUTO_INCREMENT ,
        `webhook_action_name` VARCHAR(200) NOT NULL ,
        `request_url` TEXT NOT NULL ,
        `webhook_for` VARCHAR(50) NOT NULL ,
        `webhook_action` JSON NOT NULL ,
        `request_method` VARCHAR(100) NOT NULL ,
        `request_format` VARCHAR(20) NOT NULL ,
        `request_header` JSON NOT NULL ,
        `request_body` JSON NOT NULL ,
        `response_code` VARCHAR(4) Not NULL,
        `response_data` text Not NULL,
        `recorded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

    /*End of file install.php */
