<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(['products_model', 'Reports_model', 'taxes_model']);
    }

    public function index()
    {
        if (!has_permission('products', '', 'view')) {
            access_denied('products View');
        }
        close_setup_menu();

        $data['title'] = _l('products');
        if (has_permission('products', '', 'view')) {
            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('products', 'products'));
            }
            $this->load->model(['currencies_model']);
            $data['base_currency'] = $this->currencies_model->get_base_currency();
            $data['title']         = _l('products');
            $this->load->view('list_products', $data);
        } else {
            access_denied('products');
        }
    }

    public function add_product()
    {
        if (!has_permission('products', '', 'view')) {
            access_denied('products View');
        }
        close_setup_menu();
        if (has_permission('products', '', 'view')) {
            $data['taxes'] = $this->taxes_model->get();
            $post          =$this->input->post();
            if (!empty($post)) {
                $this->form_validation->set_rules('product_name', 'product name', 'required|is_unique[product_master.product_name]');
                $this->form_validation->set_rules('product_description', 'product description', 'required');
                $this->form_validation->set_rules('product_category_id', 'product category', 'required');
                $this->form_validation->set_rules('rate', 'product rate', 'required');
                $this->form_validation->set_rules('quantity_number', 'product quantity', 'required');
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } else {
                    $data = [
                        'product_name'        => $post['product_name'],
                        'product_description' => $post['product_description'],
                        'product_category_id' => $post['product_category_id'],
                        'rate'                => $post['rate'],
                        'quantity_number'     => $post['quantity_number'],
                        'taxes'               => (!empty($post['taxes'])) ? serialize($post['taxes']) : 0,
                        'cycles'              => $post['cycles'] ?? 0,
                    ];
                    if (isset($post['recurring'])) {
                        if ('custom' == $post['recurring']) {
                            $data['recurring_type']   = $post['repeat_type_custom'];
                            $data['custom_recurring'] = 1;
                            $data['recurring']        = $post['repeat_every_custom'];
                        } else {
                            $data['recurring']        = $post['recurring'];
                        }
                    } else {
                        $data['custom_recurring'] = 0;
                        $data['recurring']        = 0;
                    }
                    $data['taxes']  = (!empty($post['taxes'])) ? serialize($post['taxes']) : '';
                    $inserted_id    =$this->products_model->add_product($data);
                    if ($inserted_id) {
                        handle_product_upload($inserted_id);
                        set_alert('success', 'Product Added successfully');
                        redirect(admin_url('products'), 'refresh');
                    } else {
                        set_alert('warning', _l('Error Found - Product not inserted'));
                    }
                }
            }
            $this->load->model(['currencies_model', 'product_category_model']);
            $data['title']              = _l('add_new', 'product');
            $data['action']             = _l('products');
            $data['product_categories'] = $this->product_category_model->get();
            $data['currencies']         = $this->currencies_model->get();
            $data['base_currency']      = $this->currencies_model->get_base_currency();

            $this->load->view('products/add_product', $data);
        } else {
            access_denied('products');
        }
    }

    public function edit($id)
    {
        if (!has_permission('products', '', 'view')) {
            access_denied('products View');
        }
        close_setup_menu();
        if (has_permission('products', '', 'view')) {
            $original_product = $data['product'] = $this->products_model->get_by_id_product($id);
            if (empty($original_product)) {
                set_alert('danger', _l('not_found_products'));
                redirect(admin_url('products'), 'refresh');
            }
            $post=$this->input->post();

            if (!empty($post)) {
                $this->form_validation->set_rules('product_name', 'product name', 'required');
                if ($original_product->product_name != $post['product_name']) {
                    $this->form_validation->set_rules('product_name', 'product name', 'required|is_unique[product_master.product_name]');
                }
                $this->form_validation->set_rules('product_description', 'product description', 'required');
                $this->form_validation->set_rules('product_category_id', 'product category', 'required');
                $this->form_validation->set_rules('rate', 'product rate', 'required');
                $this->form_validation->set_rules('quantity_number', 'product quantity', 'required');
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } else {
                    $data = [
                        'product_name'        => $post['product_name'],
                        'product_description' => $post['product_description'],
                        'product_category_id' => $post['product_category_id'],
                        'rate'                => $post['rate'],
                        'quantity_number'     => $post['quantity_number'],
                        'cycles'              => $post['cycles'] ?? 0,
                    ];
                    if (0 != $original_product->recurring && 0 == $post['recurring']) {
                        $data['cycles']              = 0;
                    }
                    if (isset($post['recurring'])) {
                        if ('custom' == $post['recurring']) {
                            $data['recurring_type']   = $post['repeat_type_custom'];
                            $data['custom_recurring'] = 1;
                            $data['recurring']        = $post['repeat_every_custom'];
                        } else {
                            $data['recurring']        = $post['recurring'];
                            $data['recurring_type']   = null;
                            $data['custom_recurring'] = 0;
                        }
                    } else {
                        $data['custom_recurring'] = 0;
                        $data['recurring']        = 0;
                        $data['recurring_type']   = null;
                    }
                    $data['taxes']  = (!empty($post['taxes'])) ? serialize($post['taxes']) : '';

                    $result=$this->products_model->edit_product($data, $id);
                    handle_product_upload($id);
                    if ($result) {
                        set_alert('success', 'Product Updated successfully');
                        redirect(admin_url('products'), 'refresh');
                    } else {
                        set_alert('warning', _l('Error Found Or You Have not made any changes'));
                    }
                }
            }
            $this->load->model(['currencies_model', 'product_category_model']);
            $data['title']              = _l('edit', 'product');
            $data['product_categories'] = $this->product_category_model->get();
            $data['currencies']         = $this->currencies_model->get();
            $data['base_currency']      = $this->currencies_model->get_base_currency();
            $data['taxes']              = $data['product']->taxes;
            $this->load->view('products/add_product', $data);
        } else {
            access_denied('products');
        }
    }

    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Product');
        }
        if (!$id) {
            redirect(admin_url('products'));
        }
        $response = $this->products_model->delete_by_id_product($id);
        if (true == $response) {
            set_alert('success', _l('deleted', _l('products')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('products')));
        }
        redirect(admin_url('products'));
    }

    public function order_history()
    {
        if (!is_admin()) {
            access_denied('Order History');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('products', 'tables/order_history'));
        }
        $this->load->model(['currencies_model']);
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['title']         = _l('order_history');
        $this->load->view('order_history', $data);
    }

    public function order_report()
    {
        $chart_week_data= $this->Reports_model->chart_orders_of_the_week();
        $this->load->vars('categories', toPlainArray($chart_week_data['days']));
        if (!empty($chart_week_data['series'])) {
            $this->load->vars('week_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_week_data['series'])));
        } else {
            $this->load->vars('week_series', '[]');
        }
        $chart_month_data= $this->Reports_model->chart_orders_per_month();
        $this->load->vars('month_categories', toPlainArray($chart_month_data['months']));
        if (!empty($chart_month_data['months'])) {
            $this->load->vars('month_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_month_data['series'])));
        } else {
            $this->load->vars('month_series', '[]');
        }
        $chart_year_data= $this->Reports_model->chart_orders_per_year();
        $this->load->vars('year_categories', toPlainArray($chart_year_data['years']));
        if (!empty($chart_year_data['years'])) {
            $this->load->vars('year_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_year_data['series'])));
        } else {
            $this->load->vars('year_series', '[]');
        }
        $this->load->vars('products', $this->products_model->get_by_id_product());
        $this->load->view('reports/order_report');
    }

    public function custom_report()
    {
        $posted_data       = $this->input->post();
        $selected_products = implode('", "', $posted_data['products_name']);
        $from_date         = $posted_data['from'];
        $to_date           = $posted_data['to'];
        $from_date_st      =strtotime($from_date);
        $to_date_st        =strtotime($to_date);
        if ($from_date_st > $to_date_st) {
            $return_data['status'] = 'error';
        } else {
            $custom_chart_data             = $this->Reports_model->chart_custom_date_range($selected_products, $from_date, $to_date);
            $return_data['date_series']    = null;
            $return_data['date_categories']= null;
            if (count($custom_chart_data['date_range']) > 0) {
                $return_data['date_series']    = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($custom_chart_data['series']));
                $return_data['date_categories']= toPlainArray($custom_chart_data['date_range']);
                $return_data['date_series']    = $custom_chart_data['series'];
                $return_data['date_categories']= $custom_chart_data['date_range'];
            }
        }
        echo json_encode($return_data);
    }

    public function quantities_report()
    {
        if (!has_permission('quantities', '', 'view')) {
            access_denied('quantities View');
        }
        close_setup_menu();
        $data['title'] = _l('quantities_report');
        if (has_permission('quantities', '', 'view')) {
            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('products', 'quantities'));
            }
            $data['title'] = _l('quantities');
            $this->load->model(['currencies_model']);
            $data['base_currency'] = $this->currencies_model->get_base_currency();
            $this->load->view('reports/quantities_report', $data);
        } else {
            access_denied('quantities');
        }
    }
}
