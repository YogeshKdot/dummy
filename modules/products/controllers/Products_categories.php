<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Products_categories extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products/product_category_model');
    }

    public function index()
    {
        if (!is_admin()) {
            access_denied('Product Category');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('products', 'tables/product_category'));
        }
        $data['title'] = _l('products_categories');
        $this->load->view('products/products_categories', $data);
    }

    public function category()
    {
        if (!is_admin()) {
            access_denied('Product Category');
        }
        $this->load->library('form_validation');
        if ($this->input->is_ajax_request()) {
            $data              = $this->input->post();
            $original_category = (object) [];
            if (!empty($data['p_category_id'])) {
                $original_category = $this->products_model->get_by_id_product($data['p_category_id']);
                if ($original_category->p_category_name != $data['p_category_name']) {
                    $this->form_validation->set_rules('p_category_name', 'Category name', 'required|is_unique[product_categories.p_category_name]');
                }
            } else {
                $this->form_validation->set_rules('p_category_name', 'Category name', 'required|is_unique[product_categories.p_category_name]');
            }
            $this->form_validation->set_rules('p_category_description', 'Description', 'required');
            if (false == $this->form_validation->run()) {
                echo json_encode([
                    'success' => false,
                    'message' => validation_errors(),
                ]);

                return;
            }
            if ('' == $data['p_category_id']) {
                $id      = $this->product_category_model->add($data);
                $message = $id ? _l('added_successfully', _l('products_categories')) : '';
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $message,
                    'id'      => $id,
                    'name'    => $data['p_category_name'],
                ]);
            } else {
                $success = $this->product_category_model->edit($data);
                $message = '';
                if (true == $success) {
                    $message = _l('updated_successfully', _l('products_categories'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function delete_category($id)
    {
        if (!is_admin()) {
            access_denied('Delete Product Category');
        }
        if (!$id) {
            redirect(admin_url('products/products_categories'));
        }
        $response = $this->product_category_model->delete($id);
        if (true == $response) {
            set_alert('success', _l('deleted', _l('products_categories')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('products_categories')));
        }
        redirect(admin_url('products/products_categories'));
    }
}
