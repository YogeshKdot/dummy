<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Blocks extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('landingpage_model');
    }

     /* List all blocks */
    public function index()
    {
        if (!has_permission('landingpages-blocks', '', 'view')) {
            access_denied('landingpages-blocks');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('zillapage', 'blocks/table'));
        }
        $data['title']                 = _l('admin_blocks');

        $this->load->view('blocks/index', $data);
    }

    public function delete($id)
    {
        if (!has_permission('landingpages-blocks', '', 'delete')) {
            access_denied('landingpages-blocks');
        }
        if (!$id) {
            redirect(admin_url('zillapage/blocks/index'));
        }
        $item = $this->landingpage_model->get_block($id);

        $response = $this->landingpage_model->delete_blocks($item);
        if ($response == true) {
            set_alert('success', _l('deleted'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(admin_url('zillapage/blocks/index'));
    }


    /* Edit client or add new client*/
    public function block($id = '')
    {
        if (!has_permission('landingpages-blocks', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('landingpages-blocks');
            }
        }

        if ($this->input->post()) {

            if ($id == '') {
                if (!has_permission('landingpages-blocks', '', 'create')) {
                    access_denied('landingpages-blocks');
                }

                $data = $this->input->post();
                $id = $this->landingpage_model->add_block($data);
                
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('block')));
                    redirect(admin_url('zillapage/blocks/block/' . $id));
                }
            } else {
                if (!has_permission('landingpages-blocks', '', 'edit')) {
                    access_denied('landingpages-blocks');
                }
                $item = $this->landingpage_model->get_block($id);

                $success = $this->landingpage_model->update_block($this->input->post(), $item);
                if ($success == true) {
                    set_alert('success', _l('updated_successfully', _l('client')));
                }
                redirect(admin_url('zillapage/blocks/block/' . $id));
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('block'));
        } else {

            $data['item'] = $this->landingpage_model->get_block($id);

            $title = _l('edit', _l('block'));
        }

        $data['title']     = $title;

        $this->load->view('zillapage/blocks/block', $data);
    }
   

    
}
