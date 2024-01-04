<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('landingpage_model');
    }

    public function index()
    {
        
        if ($this->input->post()) {

            if (!has_permission('landingpages-settings', '', 'edit')) {
                    access_denied('landingpages-settings');
            }
            $item = $this->landingpage_model->get_landing_page_setting('blockscss');

            $success = $this->landingpage_model->update_settings($this->input->post(),$item->id);
            if ($success == true) {
                set_alert('success', _l('updated_successfully', _l('client')));
            }
            redirect(admin_url('zillapage/settings'));

        }

        $data['blockscss'] = $this->landingpage_model->get_landing_page_setting('blockscss');
        $title = _l('edit', _l('setting'));
        $data['title']     = $title;

        $this->load->view('zillapage/settings/index', $data);
    }
   
    
}
