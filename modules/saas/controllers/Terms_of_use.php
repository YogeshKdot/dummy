<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Terms_of_use extends ClientsController
{
    public function index()
    {
        $this->app_modules->is_inactive('saas') ? access_denied() : '';
        
        $data['terms'] = get_option('terms_of_use');
        $data['title'] = _l('terms_of_use').' - '.get_option('companyname');
        $this->data($data);
        $this->view('terms_of_use');
        $this->layout();
    }
}
