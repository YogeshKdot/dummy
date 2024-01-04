<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (0 != get_option('product_menu_disabled')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }
        $this->load->model('product_category_model');
        $data['title']              = _l('products');
        $data['products']           = $this->products_model->get_by_id_product();
        $data['product_categories'] = $this->product_category_model->get();
        $this->data($data);
        $this->view('clients/products');
        $this->layout();
    }

    public function filter()
    {
        $p_category_id = $this->input->post('p_category_id');
        $cart_data     = $this->session->cart_data;
        $products      = $this->products_model->get_category_filter($p_category_id);
        $base_currency = $this->currencies_model->get_base_currency();
        foreach ($products as $key => $value) {
            $products[$key]['cart_data']          = $cart_data[$value['id']] ?? [];
            $products[$key]['product_image_url']  = module_dir_url('products', 'uploads').'/'.$value['product_image'];
            $products[$key]['no_image_url']       = module_dir_url('products', 'uploads').'/image-not-available.png';
            $products[$key]['base_currency_name'] = $base_currency->name;
            $taxes                                = unserialize($value['taxes']);
            $total_tax                            = 0;
            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
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
                    $total_tax += $tax_array[1];
                }
            }
            $products[$key]['total_tax'] = $total_tax;
        }
        echo json_encode($products);
    }

    public function add_cart()
    {
        $product_id           = $this->input->post('product_id');
        $quantity             = $this->input->post('quantity');
        $newdata['cart_data'] = $this->session->cart_data;
        if (empty($cart_data)) {
            $newdata['cart_data'][$product_id] = ['product_id'=>$product_id, 'quantity'=>$quantity];
            $this->session->set_userdata($newdata);
        } else {
            $newdata['cart_data'][$product_id] = ['product_id'=>$product_id, 'quantity'=>$quantity];
            $this->session->set_userdata($newdata);
        }
        $cart_data = $this->session->cart_data;
    }

    public function remove_cart($product_id = null, $return = false)
    {
        if (empty($product_id)) {
            $product_id = $this->input->post('product_id');
        }
        $newdata['cart_data'] = $this->session->cart_data;
        unset($newdata['cart_data'][$product_id]);
        $this->session->set_userdata($newdata);
        if (empty($newdata['cart_data'])) {
            set_alert('danger', _l('Cart is empty'));
            $res['status'] = false;
            if ($return) {
                return json_encode($res);
            }
            echo json_encode($res);

            return;
        }
        $res['status'] = true;
        if ($return) {
            return json_encode($res);
        }
        echo json_encode($res);
    }

    public function get_currency($id)
    {
        echo json_encode(get_currency($id));
    }

    public function place_order($product_id=false)
    {
        if (0 != get_option('product_menu_disabled')) {
            $this->session->unset_userdata('cart_data');
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }
        $this->load->model('products/order_model');
        if (!is_client_logged_in()) {
            set_alert('warning', _l('clients_login_heading_no_register'));
            redirect(site_url(''));
        }
        $order_data = $post = $this->input->post();
        if (!empty($post)) {
            $data['products'] = $product = $this->products_model->get_by_id_product(array_column($post['product_items'], 'product_id'));
            $message          = '';
            foreach ($product as $value) {
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
                    redirect(site_url('invoice/'.$id.'/'.$res->hash), 'refresh');
                }
                redirect(site_url('clients/invoices'), 'refresh');
            }
            set_alert('error', _l('order_fail'));
        }
        $cart_data = $this->session->cart_data;
        if (empty($cart_data)) {
            set_alert('danger', _l('Cart is empty'));
            redirect(site_url('products/client/'));
        }
        $data['products'] = $product = $this->products_model->get_by_id_product(array_keys($cart_data));
        if (empty($product)) {
            set_alert('danger', _l('Products in Cart not found'));
            redirect(site_url('products/client/'));
        }
        $message          = '';
        $all_taxes        = [];
        $init_tax         = [];
        foreach ($product as $value) {
            if ((int) $value->quantity_number < 1) {
                $this->remove_cart($value->id, true);
                $message = $value->product_name.' is out of stock so removed from cart <br>';
                continue;
            }
            if ((int) $cart_data[$value->id]['quantity'] > (int) $value->quantity_number) {
                $cart_data[$value->id]['quantity'] = $value->quantity_number;
                $message                           = $value->product_name.' is only '.$value->quantity_number.' in stock so quantity reduced to that quantity <br>';
            }
            $value->quantity = $cart_data[$value->id]['quantity'];
            $taxes_arr       = [];
            $value->taxname  = $taxes  = unserialize($value->taxes);
            if ($taxes) {
                foreach ($taxes as $tax) {
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
                    $init_tax[$tmp_taxname][]  = ($value->rate * $value->quantity) / 100 * $tax_array[1];
                    $all_taxes[$tmp_taxname]   = $taxes_arr[]   = ['name' => $tmp_taxname, 'taxrate' => $tax_array[1], 'taxname' => $tax_array[0]];
                }
            }
            $value->taxes = $taxes_arr;
        }
        $data['all_taxes']        = $all_taxes;
        $data['init_tax']         = $init_tax;
        $data['message']          = $message;
        $data['title']            = _l('confirm').' '._l('place_order');
        $data['base_currency']    = $this->currencies_model->get_base_currency();
        $this->data($data);
        $this->view('clients/place_order');
        $this->layout();
    }
}
