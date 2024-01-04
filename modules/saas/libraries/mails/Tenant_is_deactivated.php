<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Tenant_is_deactivated extends App_mail_template
{
    public $email;

    public $client_id;
    
    public $contact_email;

    public $slug     = 'tenant-is-deactivated';

    public $rel_type = 'tenants';

    public function __construct($client_id, $email)
    {
        parent::__construct();
        $this->client_id         = $client_id;
        $this->contact_email     = $email;
    }

    public function build()
    {
        $data = $this->ci->load->library(SUPERADMIN_MODULE . '/merge_fields/tenant_is_deactivated_merge_fields');

        $res = $this->to($this->contact_email)
        ->set_rel_id($this->client_id)
        ->set_merge_fields('tenant_is_deactivated_merge_fields', $this->client_id)
        ->set_merge_fields('client_merge_fields', $this->client_id);
    }
}
