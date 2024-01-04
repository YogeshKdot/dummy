<?php

defined('BASEPATH') or exit('No direct script access allowed');

class inject_javascript extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!is_admin()) {
            access_denied('Custom JavaScript');
        }
        
        $this->load->helper('/inject_javascript');
    }

    public function index()
    {
        $data['title'] = _l('inject_javascript');
        $this->load->view('inject_javascript', $data);
    }

    public function reset()
    {
        update_option('inject_javascript', 'enable');
        redirect(admin_url('inject_javascript'));
    }

    public function save()
    {
        hooks()->do_action('before_save_inject_javascript');
        
        foreach(['admin_area','clients_area','clients_and_admin'] as $css_area) {
            // Also created the variables
            $$css_area = $this->input->post($css_area, FALSE);
            $$css_area = trim($$css_area);
            $$css_area = nl2br($$css_area);
        }
        
        update_option('inject_javascript_admin_area', $admin_area);
        update_option('inject_javascript_clients_area', $clients_area);
        update_option('inject_javascript_clients_and_admin_area', $clients_and_admin);
    }
    
    public function enable()
    {
        hooks()->do_action('before_save_inject_javascript');

        update_option('inject_javascript', 'enable');
    }
    
    public function disable()
    {
        hooks()->do_action('before_save_inject_javascript');

        update_option('inject_javascript', 'disable');
    }
}
