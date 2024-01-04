<?php

defined('BASEPATH') || exit('No direct script access allowed');
class Onboarding_email_template extends App_mail_template
{
    public $email;
    public $client;
    public $slug     = 'onboarding-email';
    public $rel_type = 'tenants';

    public function __construct($email, $client)
    {
        parent::__construct();
        $this->email  = $email;
        $this->client = $client;
    }

    public function build()
    {
        $this->to($this->email)
        ->set_rel_id($this->client->id)
        ->set_merge_fields('client_merge_fields', $this->client->userid, $this->client->id);
    }
}
