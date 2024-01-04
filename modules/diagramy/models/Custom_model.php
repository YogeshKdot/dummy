<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Custom_model extends App_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function _search_task($q)
    {
        $result = [
            'result'         => [],
            'type'           => 'tasks',
            'search_heading' => _l('tasks'),
        ];

        if (has_permission('tasks', '', 'view')) {
            // Staff
            $this->db->select();
            $this->db->from(db_prefix().'tasks');
            $this->db->like('name', $q);
            $this->db->or_like('description', $q);

            $this->db->order_by('name', 'ASC');
            $result['result'] = $this->db->get()->result_array();
        }

        return $result;
    }
}

// End of file Custom_model.php
// Location: ./application/models/Custom_model.php
