<?php

defined('BASEPATH') || exit('No direct script access allowed');

$CI = &get_instance();
$config['has_setting']  = false;
if ($CI->db->table_exists(db_prefix().'extended_email_settings')) {
    $CI->load->model('extended_email/extended_email_model');
    $CI->load->library('app_object_cache');

    $override_staff_id = $CI->session->userdata('override_staff_id') ?? '';
    $email_settings    = $CI->extended_email_model->get_staff_extended_email_settings(get_staff_user_id(), $override_staff_id);

    $config['has_setting']  = false;
    if (!empty($email_settings)) {
        /* set only smtp_pass & smtp_host proper set */
        if ('' != $email_settings->smtp_password && '' != $email_settings->smtp_host) {
            if (1 == $email_settings->active || is_admin()) {
                $config['has_setting']  = true;
                $config['useragent']    = $email_settings->mail_engine;
                $config['protocol']     = $email_settings->email_protocol;
                $config['smtp_host']    = trim($email_settings->smtp_host);

                if ('' == $email_settings->smtp_username) {
                    $config['smtp_user'] = trim($email_settings->email);
                } else {
                    $config['smtp_user'] = trim($email_settings->smtp_username);
                }

                $config['smtp_pass']    = $CI->encryption->decrypt($email_settings->smtp_password);
                $config['smtp_port']    = trim($email_settings->smtp_port);
                $config['smtp_crypto']  = $email_settings->smtp_encryption;
                $charset                = strtoupper($email_settings->email_charset);
                $charset                = trim($charset);
                if ('' == $charset || 'utf8' == strcasecmp($charset, 'utf8')) {
                    $charset = 'utf-8';
                }
                $config['mailtype'] = 'html';
                $config['charset']  = $charset;
            }
        }
    }
}
