<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Staff_order extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['currencies_model', 'Products_model', 'invoices_model', 'products/order_model']);
    }

    public function index()
    {
        if (!has_permission('products', '', 'create')) {
            access_denied('products');
        }
        if (!empty($this->input->post())) {
            $post             = $this->input->post();
            $post['newitems'] = $post['product_items'] = array_column($post['product_items'], null, 'product_id');
            $data['products'] = $product = $this->products_model->get_by_id_product(array_column($post['product_items'], 'product_id'));
            $message          = '';
            foreach ($product as $key => $value) {
                unset($post['newitems'][$value->id]['product_id']);
                $post['newitems'][$value->id]['unit']             = '';
                $post['newitems'][$value->id]['order']            = $key + 1;
                $post['newitems'][$value->id]['description']      = $value->product_name;
                $post['newitems'][$value->id]['long_description'] = $value->product_description;
                $post['newitems'][$value->id]['taxname']          = unserialize($value->taxes);
                $post['newitems'][$value->id]['rate']             = $value->rate;

                $post['product_items'][$value->id]['rate']        = $value->rate;

                $post['newitems'][$value->id]['recurring']        = $value->recurring;
                $post['newitems'][$value->id]['recurring_type']   = $value->recurring_type;
                $post['newitems'][$value->id]['custom_recurring'] = $value->custom_recurring;
                $post['newitems'][$value->id]['cycles']           = $value->cycles;
                if ((int) $value->quantity_number < 1) {
                    $message = $value->product_name.' is out of stock <br>';
                    continue;
                }
                if ((int) $post['product_items'][$value->id]['qty'] > (int) $value->quantity_number) {
                    $message = $value->product_name.' is only '.$value->quantity_number.' in stock <br>';
                }
            }
            $order_data = $post;
            if (!empty($message)) {
                redirect('products/client/place_order', 'refresh');
            }
            $billing_shipping = $this->clients_model->get_customer_billing_and_shipping_details($post['clientid']);
            $post             = array_merge($post, reset($billing_shipping));
            unset($post['billing_country']);
            unset($post['shipping_country']);
            $post['show_shipping_on_invoice'] = 'on';
            $post['number']                   = get_option('next_invoice_number');
            $order_data['order_date']         = $post['date']           = _d(date('Y-m-d'));
            $post['duedate']                  = _d(date('Y-m-d', strtotime('+'.get_option('invoice_due_after').' DAY', strtotime(date('Y-m-d')))));
            $post['show_quantity_as']         = 1;
            $this->load->model('payment_modes_model');
            $payment_modes = $this->payment_modes_model->get();
            foreach ($payment_modes as $modes) {
                if ($modes['selected_by_default']) {
                    $post['allowed_payment_modes'][] = $modes['id'];
                }
            }
            unset($order_data['newitems']);
            unset($post['product_items']);
            $post['currency'] = $this->currencies_model->get_base_currency()->id;

            $invoice_insert_items = [];
            $invoice_order_items  = [];
            $result               = [];
            $init_tax             = [];
            $total                = $subtotal                = 0;
            foreach ($post['newitems'] as $key => $items) {
                if (0 != $items['recurring']) {
                    $invoice_insert_items[$key] = $items;
                    $invoice_order_items[$key]  = $order_data['product_items'][$key];
                    unset($post['newitems'][$key]);
                    unset($order_data['product_items'][$key]);
                    continue;
                }
                $subtotal += $items['rate'] * $items['qty'];
                $total = $subtotal;
                if (!empty($items['taxname'])) {
                    foreach ($items['taxname'] as $tax) {
                        if (!is_array($tax)) {
                            $tmp_taxname = $tax;
                            $tax_array   = explode('|', $tax);
                        } else {
                            $tax_array   = explode('|', $tax['taxname']);
                            $tmp_taxname = $tax['taxname'];
                            if ('' == $tmp_taxname) {
                                continue;
                            }
                        }
                        $total += ($items['rate'] * $items['qty']) / 100 * $tax_array[1];
                    }
                }

                unset($post['newitems'][$key]['recurring']);
                unset($post['newitems'][$key]['recurring_type']);
                unset($post['newitems'][$key]['custom_recurring']);
                unset($post['newitems'][$key]['cycles']);
            }
            $order_data['subtotal'] = $post['subtotal'] = $subtotal;
            $order_data['total']    = $post['total']    = $total;

            $count = 0;
            if (!empty($post['newitems'])) {
                $count            = 1;
                $id               = $this->invoices_model->add($post);
                if ($id) {
                    $result[]                 = true;
                    $res                      = $this->invoices_model->get($id);
                    $order_data['status']     = $res->status;
                    $order_data['invoice_id'] = $id;
                    $this->order_model->add_order($order_data);
                }
            }
            if (!empty($invoice_insert_items)) {
                foreach ($invoice_insert_items as $product_id => $new_invoice_item) {
                    $total = $subtotal = 0;

                    $post['newitems']            = [];
                    $order_data['product_items'] = [];
                    $post['recurring']           = $new_invoice_item['recurring'];
                    $post['recurring_type']      = $new_invoice_item['recurring_type'];
                    $post['custom_recurring']    = $new_invoice_item['custom_recurring'];
                    $post['cycles']              = $new_invoice_item['cycles'];

                    unset($new_invoice_item['recurring']);
                    unset($new_invoice_item['recurring_type']);
                    unset($new_invoice_item['custom_recurring']);
                    unset($new_invoice_item['cycles']);

                    $post['number'] = get_option('next_invoice_number');

                    $post['newitems'][$product_id]            = $new_invoice_item;
                    $order_data['product_items'][$product_id] = $invoice_order_items[$product_id];

                    $subtotal += $new_invoice_item['rate'] * $new_invoice_item['qty'];
                    $total = $subtotal;
                    if (!empty($new_invoice_item['taxname'])) {
                        foreach ($new_invoice_item['taxname'] as $tax) {
                            if (!is_array($tax)) {
                                $tmp_taxname = $tax;
                                $tax_array   = explode('|', $tax);
                            } else {
                                $tax_array   = explode('|', $tax['taxname']);
                                $tmp_taxname = $tax['taxname'];
                                if ('' == $tmp_taxname) {
                                    continue;
                                }
                            }
                            $total += ($new_invoice_item['rate'] * $new_invoice_item['qty']) / 100 * $tax_array[1];
                        }
                    }

                    $order_data['subtotal'] = $post['subtotal'] = $subtotal;
                    $order_data['total']    = $post['total']    = $total;

                    $id               = $this->invoices_model->add($post);
                    if ($id) {
                        $result[]                 = true;
                        $res                      = $this->invoices_model->get($id);
                        $order_data['status']     = $res->status;
                        $order_data['invoice_id'] = $id;
                        $this->order_model->add_order($order_data);
                    }
                }
            }
            if (count($invoice_insert_items) + $count == count($result)) {
                $this->session->unset_userdata('cart_data');
                set_alert('success', _l('order_success'));
                if (1 == count($result)) {
                    redirect(admin_url('invoices/list_invoices/'.$id), 'refresh');
                }
                redirect(admin_url('invoices'), 'refresh');
            }
            set_alert('error', _l('order_fail'));
        }
        $data['products']       = $this->products_model->get_by_id_product();
        $data['title']          = _l('add_new_order');
        $this->load->view('add_new_order', $data);
    }
}
