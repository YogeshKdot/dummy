<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Tenants extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['encryption']);
        $this->app_modules->is_inactive('saas') ? access_denied() : '';
    }

    public function find()
    {
        if (is_client_logged_in()) {
            redirect(site_url());
        }

        $this->form_validation->set_rules('tenants_name', _l('tenants_name'), 'trim|required');

        if (show_recaptcha_in_customers_area()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }

        if (false !== $this->form_validation->run()) {
            $this->load->model('Clients_model');

            $tenants_name          = $this->input->post('tenants_name');
            $where['tenants_name'] = $tenants_name;

            $this->load->model(SUPERADMIN_MODULE.'/superadmin_model');
            $client = $this->superadmin_model->getSingleRow('client_plan', $where);
            if (!empty($client)) {
                redirect(parse_url(base_url())['scheme'].'://'.$client->tenants_name.'.'.parse_url(base_url())['host'].'/admin');
            }

            set_alert('danger', _l('tenant_not_found'));
        }

        $data['bodyclass'] = 'customers_login';
        $data['title']     = _l('find_my_tenant');

        $this->data($data);
        $this->view('find_my_tenant');
        $this->layout();
    }

    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }

    public function email_verification()
    {
        $this->form_validation->set_rules('email_address', _l('email_address'), 'trim|required|valid_email');

        if (false !== $this->form_validation->run()) {
            $this->load->model('custom_model');
            $contact = $this->custom_model->getSingleRow(db_prefix().'contacts', ['email' => $this->input->post('email_address')], 'array');

            if (!empty($contact)) {
                $res = send_mail_template('we_found_your_tenant_url', SUPERADMIN_MODULE, $contact['userid'], $contact['email']);
                set_alert('success', _l('mail_send_successfully'));
            } else {
                set_alert('danger', _l('email_not_exist'));
            }
            redirect($this->uri->uri_string());
        }

        $data['title']     = _l('email_verification');
        $this->data($data);
        $this->view('email_verification');
        $this->layout();
    }
}

/* End of file Find_my_tenant.php */
/* Location: ./modules/saas/controllers/Find_my_tenant.php */
