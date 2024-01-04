<?php

defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');
class Aiwriter extends AdminController
{
        public function __construct()
        {
            parent::__construct();
            $this->load->model('aiwriter_model');
        }
        public function index()
        {
            if (!has_permission('spagreen', '', 'view')) {
                access_denied('spagreen');
            }

            $data['title']                 = _l('spagreen_dashboard');
            $this->load->view('dashboard', $data);
        }


    public function setting()
    {
        if (!has_permission('aiwriter', '', 'setting')) {
            access_denied('aiwriter');
        }

        //$this->load->model('epc_model');
        $data['title'] = _l('aiwriter_setting');
        $this->load->view('setting', $data);
    }

    public function case()
    {
        if (!has_permission('aiwriter', '', 'setting')) {
            access_denied('aiwriter');
        }

        //$this->load->model('epc_model');
        $usage_cases = array();
        $data['title'] = _l('aiwriter_case');
        $this->load->view('case', $data);
    }

    public function add_case($id = '')
    {
        if (!has_permission('aiwriter', '', 'setting')) {
            access_denied('aiwriter');
        }
        if ($this->input->post()):
            $_post                  = $this->input->post();
            $data['usage_case']     = $this->input->post('usage_case');
            $data['is_default']     = $this->input->post('is_default');
            if($data['is_default'] =='1'):
                $this->db->update(db_prefix() . 'aiwriter_usage_cases',array('is_default'=>0));
            else:
                $data['is_default'] =0;
            endif;
            $data['usage_case_key'] = $this->camelCase2UnderScore($data['usage_case']);
            if ($id == ''):
                if ($this->db->insert(db_prefix() . 'aiwriter_usage_cases', $data)):
                    set_alert('success', _l('added_successfully', _l('spagreen')));
                    redirect(admin_url('aiwriter/case'));
                endif;
            else:
                $query = $this->db->get_where(db_prefix() . 'aiwriter_usage_cases', array('id' => $id));
                if ($query->num_rows() == 0):
                    blank_page('Item Not Found', 'danger');
                else:
                    $this->db->where('id', $id);
                    if ($this->db->update(db_prefix() . 'aiwriter_usage_cases', $data)):
                        set_alert('success', _l('updated_successfully', _l('spagreen')));
                        redirect(admin_url('aiwriter/case'));
                    endif;
                endif;
                redirect(admin_url('aiwriter/case'));
            endif;
        endif;
    }
    public function delete_case($id = ''){
        $query = $this->db->get_where(db_prefix() . 'aiwriter_usage_cases', array('id' => $id));
        if ($query->num_rows() == 0):
            blank_page('Item Not Found', 'danger');
        else:
            $this->db->where('id', $id);
            if ($this->db->delete(db_prefix() . 'aiwriter_usage_cases')):
                set_alert('success', _l('deleted_successfully', _l('spagreen')));
                redirect(admin_url('aiwriter/case'));
            endif;
        endif;
        redirect(admin_url('aiwriter/case'));
    }

    public function edit_case($id = '')
    {
        if (!has_permission('aiwriter', '', 'setting')) {
            access_denied('aiwriter');
        }
        if ($id != ''):
            $query = $this->db->get_where(db_prefix() . 'aiwriter_usage_cases', array('id' => $id));
            if ($query->num_rows() == 0):
                blank_page('Item Not Found', 'danger');
            else:
                $query = $this->db->get_where(db_prefix() . 'aiwriter_usage_cases', array('id' => $id));
                $data['title'] = _l('edit_case');
                $data['case_info'] = $query->first_row();
                $this->load->view('edit_case', $data);
            endif;
        else:
            redirect(admin_url('aiwriter/case'));
        endif;
    }

    public function reset_case(){
        if (!has_permission('aiwriter', '', 'setting')) {
            access_denied('aiwriter');
        }
        ;
        if ($this->aiwriter_model->reset_usage_case()):
            set_alert('success', _l('reset_successfully', _l('spagreen')));
        endif;
        redirect(admin_url('aiwriter/case'));
    }

    public function writer()
    {
        if (!has_permission('aiwriter', '', 'use')) {
            access_denied('aiwriter');
        }

        //$this->load->model('epc_model');
        $data['title'] = _l('aiwriter');
        $this->load->view('writer', $data);
    }


    public function save_setting()
    {
        if((get_option('aiwriter_demo_mode') == '1') ):
            echo json_encode(['status'=>false, 'message'=>_l('change_not_allow_on_demo')]);
            exit();
        endif;
        if (!has_permission('aiwriter', '', 'setting')) {
            access_denied('aiwriter');
        }
        if($this->input->post()):
            update_option('aiwriter_openai_api_key',$this->input->post('aiwriter_openai_api_key'));
            update_option('aiwriter_openai_limit_text',$this->input->post('aiwriter_openai_limit_text'));
            update_option('aiwriter_allow_for_client',$this->input->post('aiwriter_allow_for_client'));
            update_option('aiwriter_allow_for_client_without_login',$this->input->post('aiwriter_allow_for_client_without_login'));
            update_option('aiwriter_autoreply_on_opening_ticket',$this->input->post('aiwriter_autoreply_on_opening_ticket'));
            update_option('aiwriter_replay_from_name',$this->input->post('aiwriter_replay_from_name'));
            update_option('aiwriter_autoreply_staffid',$this->input->post('aiwriter_autoreply_staffid'));
            echo json_encode(['status'=>true, 'message'=> _l('setting_updated')]);
        else:
            echo json_encode(['status'=>false, 'message'=>_l('something_went_wrong')]);
        endif;
    }

    public function ajaxAiContent(){
        if (!has_permission('aiwriter', '', 'use')) {
            access_denied('aiwriter');
        }
        $option['keyword']            = $this->input->post('primary_keyword');
        $option['usage_case']         = $this->input->post('usage_case');
        $option['numberVariant']      = $this->input->post('no_of_varient');
        $text = $this->aiwriter_model->get_ajax_ai_content($option);
        if($text === false):
            echo json_encode(['status'=>false, 'message'=>_l('something_went_wrong')]);
        else:
            echo json_encode(['status'=>true, 'message'=> _l('content_generated'),'data'=>$text]);
        endif;

    }

    function camelCase2UnderScore($str, $separator = "_")
    {
        if (empty($str)) {
            return $str;
        }
        $str = lcfirst($str);
        $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
        $str = preg_replace("/[A-Z]/", $separator . "$0", $str);
        return strtolower($str);
    }

}
