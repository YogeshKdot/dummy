<?php

defined('BASEPATH') || exit('No direct script access allowed');

add_option('extended_email_enabled', 1);

// get codeigniter instance
$CI = &get_instance();

if (!$CI->db->table_exists(db_prefix().'extended_email_settings')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'extended_email_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` int(11) NOT NULL,
        `mail_engine` varchar(150) NOT NULL,
        `email_protocol` varchar(150) NOT NULL,
        `smtp_encryption` varchar(150) NOT NULL,
        `smtp_host` varchar(150) NOT NULL,
        `smtp_port` varchar(150) NOT NULL,
        `email` varchar(150) NOT NULL,
        `smtp_username` varchar(150) NOT NULL,
        `smtp_password` TEXT NOT NULL,
        `email_charset` varchar(150) NOT NULL,
        `active` TINYINT(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'extended_email_log_activity')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'extended_email_log_activity` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `staffid` int(11) NOT NULL,
        `email_userid` int(11) NOT NULL,
        `description` varchar(255) NOT NULL,
        `datetime` DATETIME NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

/* End of file install.php */
