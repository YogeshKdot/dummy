<?php

defined('BASEPATH') or exit('No direct script access allowed');

class support_contact extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!is_admin()) {
            access_denied('AIO Support Contact');
        }
        
        $this->load->helper('/support_contact');
    }

    public function index()
    {
        $data['title'] = _l('support_contact');
        $this->load->view('support_contact', $data);
    }

    public function reset()
    {
        update_option('support_contact', 'enable');
        redirect(admin_url('support_contact'));
    }

    public function save()
    {
        hooks()->do_action('before_save_support_contact');
        
        foreach(['support_contact_viber','support_contact_whatsapp','messenger_username','email_address'] as $css_area) {
            // Also created the variables
            $$css_area = $this->input->post($css_area, FALSE);
            $$css_area = trim($$css_area);
            $$css_area = nl2br($$css_area);
        }
        
        update_option('support_contact_viber', $support_contact_viber);
        update_option('support_contact_whatsapp', $support_contact_whatsapp);
        update_option('support_contact_messenger_username', $messenger_username);
        update_option('support_contact_email_address', $email_address);
    }
    
    public function enable()
    {
        hooks()->do_action('before_save_support_contact');
        update_option('support_contact', 'enable');
    }
    
    public function enablefrontend()
    {
        hooks()->do_action('before_save_support_contact');
        update_option('aio_support_frontend', '1');
    }
    
    public function enablebackend()
    {
        hooks()->do_action('before_save_support_contact');
        update_option('aio_support_backend', '1');
    }
    
    public function disable()
    {
        hooks()->do_action('before_save_support_contact');
        update_option('support_contact', 'disable');
    }
    
    public function disablefrontend()
    {
        hooks()->do_action('before_save_support_contact');
        update_option('aio_support_frontend', '0');
    }
    
    public function disablebackend()
    {
        hooks()->do_action('before_save_support_contact');
        update_option('aio_support_backend', '0');
    }
}