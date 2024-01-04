<?php

    defined('BASEPATH') || exit('No direct script access allowed');

    function get_all_staff_members()
    {
        $CI = &get_instance();
        $CI->db->where('admin', 0);

        return $CI->db->get(db_prefix().'staff')->result_array();
    }

    /* End of file "extended_email_helper.".php */
