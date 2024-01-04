<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_order($data)
    {
        $data['datecreated'] = date('Y-m-d H:i:s');
        $product_items       = $data['product_items'];
        unset($data['product_items']);
        $this->db->insert(db_prefix().'order_master', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $product_items = array_map(function ($arr) use ($insert_id) {
                return $arr + ['order_id' => $insert_id];
            }, $product_items);
            $this->db->insert_batch(db_prefix().'order_items', $product_items);
            $this->db->select('staffid, email');
            $this->db->where('admin', 1);
            $this->db->where('active', 1);
            $system_admin = $this->db->get(db_prefix().'staff')->result_array();
            foreach ($system_admin as $staff) {
                add_notification([
                    'description' => 'Order added',
                    'touserid'    => $staff['staffid'],
                    'link'        => 'products/order_history/',
                ]);
            }

            return $insert_id;
        }

        return false;
    }

    public function update_status($invoice_id, $status)
    {
        $this->db->update(db_prefix().'order_master', ['status' => $status], ['invoice_id'=>$invoice_id]);
    }

    public function update_quantity_on_invoice($invoice_id)
    {
        $res         = null;
        $order_items = $this->get_order_items_from_invoice($invoice_id);
        if (empty($order_items)) {
            $this->load->model('invoices_model');
            $recurring_invoice = $this->invoices_model->get($invoice_id);
            if (!empty($recurring_invoice)) {
                $recurring_invoice_id = $recurring_invoice->is_recurring_from;
                $order_items          = $this->get_order_items_from_invoice($recurring_invoice_id);
            }
        }
        if (!empty($order_items)) {
            $order_id    = reset($order_items)['order_id'];
            $order_items = array_map(function ($arr) {
                $quantity_arr['quantity_number'] = 'quantity_number - '.$arr['qty'];
                $quantity_arr['id']				 = $arr['product_id'];

                return $quantity_arr;
            }, $order_items);
            $this->db->set_update_batch($order_items, 'id', false);
            $res = $this->db->update_batch(db_prefix().'product_master', null, 'id');
            if ($res) {
                $data = $this->order_model->get_by_id_order($order_id);
                $this->db->select('staffid, email');
                $this->db->where('admin', 1);
                $this->db->where('active', 1);
                $system_admin = $this->db->get(db_prefix().'staff')->result_array();
                foreach ($system_admin as $staff) {
                    send_mail_template('order_paid_admin', 'products', $data, $staff);
                }
                send_mail_template('Order_paid_client', 'products', $data);
            }
        }

        return $res;
    }

    public function get_order_items_from_invoice($invoice_id)
    {
        $this->db->where(db_prefix().'order_master.invoice_id', $invoice_id);
        $this->db->join('order_master', db_prefix().'order_master.id='.db_prefix().'order_items.order_id', 'LEFT');
        $result      = $this->db->get(db_prefix().'order_items');

        return $order_items = $result->result_array();
    }

    public function get_by_id_order($id = false)
    {
        if ($id) {
            $this->db->where_in(db_prefix().'order_master.id', $id);
            if (is_array($id)) {
                $product = $this->db->get(db_prefix().'order_master')->result();
            } else {
                $product = $this->db->get(db_prefix().'order_master')->row();
            }

            return $product;
        }
        $products = $this->db->get(db_prefix().'order_master')->result_array();

        return $products;
    }

    public function get_order_with_items($id = false)
    {
        if (!empty($id)) {
            $this->db->where(db_prefix().'order_master.id', $id);
        }
        $this->db->join('order_master', db_prefix().'order_master.id='.db_prefix().'order_items.order_id', 'LEFT');
        $this->db->join('product_master', db_prefix().'product_master.id='.db_prefix().'order_items.product_id', 'LEFT');
        $result      = $this->db->get(db_prefix().'order_items');

        return $order_items = $result->result();
    }
}
