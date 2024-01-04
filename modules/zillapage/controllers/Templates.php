<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Templates extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('landingpage_model');
    }

     /* List all templates */
    public function index()
    {
        if (!has_permission('landingpages-templates', '', 'view')) {
            access_denied('landingpages-templates');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('zillapage', 'templates/table'));
        }
        $data['title']                 = _l('admin_templates');

        $this->load->view('templates/index', $data);
    }

    public function previewtemplate($id)
    {
        if (!has_permission('landingpages-templates', '', 'view')) {
            access_denied('landingpages-templates');
        }
        $item = $this->landingpage_model->get_template($id);
        $item = replaceVarContentStyle($item);

        $data['title']                 = _l('admin_landing_pages');
        $data['item'] = $item;

        $this->load->view('templates/preview_template', $data);
    }

    public function framemainpage($id){
        
        if ($id) {
            $item = $this->landingpage_model->get_template($id);
            $data['item'] = $item;
            $this->load->view('templates/frame_main_page', $data);
            
        }
    }
    public function framethankyoupage($id){
        
        if ($id) {
            $item = $this->landingpage_model->get_template($id);
            $data['item'] = $item;
            $this->load->view('templates/frame_thank_you_page', $data);
            
        }
    }

    public function gettemplatejson($id)
    {
        $template = $this->landingpage_model->get_template($id);
        $template = replaceVarContentStyle($template);
        $blocks_css = $this->landingpage_model->get_landing_page_setting('blockscss');
        $blockscss = replaceVarContentStyle($blocks_css->value);
        header('Content-Type: application/json');
        echo json_encode([
            'blockscss'=>$blockscss, 
            'style' => $template->style,
            'content'=>$template->content, 
            'thank_you_page' => $template->thank_you_page,
        ]); die;
    }

    /* Edit client or add new client*/
    public function template($id = '')
    {
        if (!has_permission('landingpages-templates', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('landingpages-templates');
            }
        }

        if ($this->input->post()) {

            if ($id == '') {
                if (!has_permission('landingpages-templates', '', 'create')) {
                    access_denied('landingpages-templates');
                }

                $data = $this->input->post();
                $id = $this->landingpage_model->add_template($data);
                
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('template')));
                    redirect(admin_url('zillapage/templates/template/' . $id));
                }
            } else {
                if (!has_permission('landingpages-templates', '', 'edit')) {
                    access_denied('landingpages-templates');
                }
                $item = $this->landingpage_model->get_template($id);

                $success = $this->landingpage_model->update_template($this->input->post(), $item);
                if ($success == true) {
                    set_alert('success', _l('updated_successfully', _l('client')));
                }
                redirect(admin_url('zillapage/templates/template/' . $id));
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('template'));
        } else {

            $data['item'] = $this->landingpage_model->get_template($id);

            $title = _l('edit', _l('template'));
        }

        $data['title']     = $title;

        $this->load->view('zillapage/templates/template', $data);
    }
   
    public function delete($id)
    {
        if (!has_permission('landingpages-templates', '', 'delete')) {
            access_denied('landingpages-templates');
        }
        if (!$id) {
            redirect(admin_url('zillapage/templates/index'));
        }
        $item = $this->landingpage_model->get_template($id);

        $response = $this->landingpage_model->delete_template($item);
        if ($response == true) {
            set_alert('success', _l('deleted'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(admin_url('zillapage/templates/index'));
    }
}

