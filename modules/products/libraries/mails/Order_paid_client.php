<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Order_paid_client extends App_mail_template
{
    public $slug     = 'order-to-client';
    public $rel_type = 'order';
    protected $for   = 'staff';
    protected $order_data;

    public function __construct($order_data)
    {
        parent::__construct();
        $this->order_data = $order_data;
    }

    public function build()
    {
        $this->set_merge_fields('order_merge_fields', $this->order_data->id);
        $this->set_merge_fields('client_merge_fields', $this->order_data->clientid);
        $this->set_merge_fields('invoice_merge_fields', $this->order_data->invoice_id);
        $contact_id = get_primary_contact_user_id($this->order_data->clientid);
        $this->ci->db->where('userid', $this->order_data->clientid);
        $this->ci->db->where('id', $contact_id);
        $contact = $this->ci->db->get(db_prefix().'contacts')->row();
        $this->to($contact->email)
        ->set_rel_id($this->order_data->id);
    }
}
