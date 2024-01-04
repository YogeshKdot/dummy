<?php

defined('BASEPATH') || exit('No direct script access allowed');

class We_found_your_tenant_url extends App_mail_template
{
    public $slug = 'we-found-your-tenant-url';

    public $rel_type = 'contact';
    protected $for   = 'customer';

    protected $contact_email;

    protected $client_id;

    public function __construct($client_id, $contact_email)
    {
        parent::__construct();
        $this->client_id     = $client_id;
        $this->contact_email = $contact_email;
    }

    public function build()
    {
        $data = $this->ci->load->library(SUPERADMIN_MODULE.'/merge_fields/email_verification_merge_fields');

        $res = $this->to($this->contact_email)
        ->set_rel_id($this->client_id)
        ->set_merge_fields('email_verification_merge_fields', $this->client_id, $this->contact_email)
        ->set_merge_fields('client_merge_fields', $this->client_id);
    }
}
