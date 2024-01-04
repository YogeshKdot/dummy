<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Superadmin extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin_model');
        $this->load->library(['superadmin_lib', 'encryption']);
        $this->load->helper('superadmin');

        $this->app_modules->is_inactive('saas') ? access_denied() : '';
    }

    public function validateTenantsName()
    {
        if ($this->input->is_ajax_request()) {
            $posted_data = $this->input->post();
            $where       = [];
            if (!empty($posted_data['userid'])) {
                $where['userid!='] = $posted_data['userid'];
            }
            if (isset($posted_data['tenants_name'])) {
                $where['tenants_name'] = trim($posted_data['tenants_name']);
                $check                 =  $this->superadmin_model->validateTenantsName($where);
            }
            echo json_encode($check ?? true);
        }
    }

    public function resetCustomerPlan()
    {
        echo json_encode($this->session->unset_userdata('selectedPlan'));
    }

    /* Change tenant status / active / inactive */
    public function change_tenant_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->superadmin_model->change_tenant_status($id, $status);
        }
    }
}

/* End of file Superadmin.php */
