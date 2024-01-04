<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Landing_page_editor extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Superadmin_model', 'saas_model');

        $this->app_modules->is_inactive('saas') ? access_denied() : '';
        !is_admin() ? access_denied() : '';
    }

    public function index()
    {
        $viewData          = [];
        $viewData['title'] = _l('landing_page_editor');

        if ($this->input->is_ajax_request()) {
            $posted_data = $this->input->post();
            $res         = $this->saas_model->save_legal_settings($posted_data);
            echo json_encode($res);

            return;
        }

        $this->load->view('landing_page_editor', $viewData);
    }
}

/* End of file Landing_page_editor.php */
/* Location: saas/controllers/Landing_page_editor.php */
