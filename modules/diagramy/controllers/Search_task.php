<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Search_task extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('custom_model');
    }

    public function index()
    {
        if ($this->input->post()) {
            $type = $this->input->post('type');
            $q    = '';
            if ($this->input->post('q')) {
                $q = $this->input->post('q');
                $q = trim($q);
            }
            $search = $this->custom_model->_search_task($q);
            $data   = $search['result'];
            if ($this->input->post('rel_id')) {
                $rel_id = $this->input->post('rel_id');
            } else {
                $rel_id = '';
            }

            $relOptions = init_relation_options($data, $type, $rel_id);
            echo json_encode($relOptions);
            die;
        }
    }
}

// End of file Search_task.php
// Location: ./application/controllers/Search_task.php
