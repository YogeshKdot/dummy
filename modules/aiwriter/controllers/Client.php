<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Client extends ClientsController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('aiwriter_model');
    }

    public function index()
    {
        if(get_option('aiwriter_allow_for_client') !='1'):
            access_denied('aiwriter');
        endif;

        if (!is_client_logged_in()) {
            if(get_option('aiwriter_allow_for_client_without_login') !='1'):
                redirect(site_url('authentication/login'));
            endif;
        }

        $data['title'] = _l('aiwriter');
        $this->view('client/writer');
        $this->data($data);
        $this->layout();
    }

    public function ajaxAiContent(){

        if(get_option('aiwriter_allow_for_client') !='1'):
            echo json_encode(['status'=>false, 'message'=>_l('something_went_wrong')]);
            exit();
        endif;

        if (!is_client_logged_in()) {
            if(get_option('aiwriter_allow_for_client_without_login') !='1'):
                echo json_encode(['status'=>false, 'message'=>_l('something_went_wrong')]);
                exit();
            endif;
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

    
}
