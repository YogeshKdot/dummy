<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Saas_log_details extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->app_modules->is_inactive('saas') ? access_denied() : '';
        !is_admin() ? access_denied() : '';
    }

    public function index()
    {
        $data['title'] = _l('saas_log_details');
        $this->load->view('saas_log', $data);
    }

    public function saas_log_details_table()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        $this->app->get_table_data(module_views_path(SUPERADMIN_MODULE, 'tables/saas_log_details_table'));
    }

    public function clear_saas_log($value='')
    {
        $this->load->model(SUPERADMIN_MODULE.'/superadmin_model');
        if ($this->superadmin_model->clear_saas_log()) {
            set_alert('success', _l('deleted', _l('saas_log')));
        } else {
            set_alert('danger', _l('problem_deleting', _l('saas_log')));
        }
        redirect(admin_url(SUPERADMIN_MODULE.'/saas_log_details'));

        return true;
    }
}

/* End of file Plan.php */
/* Location: saas/controllers/Plan.php */
