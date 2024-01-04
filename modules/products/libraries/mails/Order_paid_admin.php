<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Order_paid_admin extends App_mail_template
{
    public $slug     = 'order-to-admin';
    public $rel_type = 'order';
    protected $for   = 'staff';
    protected $order_data;
    protected $staff_data;

    public function __construct($order_data, $staff_data)
    {
        parent::__construct();
        $this->order_data = $order_data;
        $this->staff_data = $staff_data;
    }

    public function build()
    {
        $this->set_merge_fields('order_merge_fields', $this->order_data->id);
        $this->set_merge_fields('client_merge_fields', $this->order_data->clientid);
        $this->set_merge_fields('invoice_merge_fields', $this->order_data->invoice_id);
        $this->to($this->staff_data['email'])
        ->set_rel_id($this->order_data->id);
    }
}
