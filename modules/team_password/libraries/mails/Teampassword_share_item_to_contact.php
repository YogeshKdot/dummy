<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Teampassword_share_item_to_contact extends App_mail_template
{
    protected $for = 'contact';

    protected $share;

    public $slug = 'teampassword-share-link-to-contact';

    public function __construct($share)
    {
        parent::__construct();

        $this->share = $share;

        // For SMS and merge fields for email
        $this->set_merge_fields('teampassword_merge_fields', $this->share->id);
    }
    public function build()
    {
        if($this->share->not_in_the_system == 'off'){
            $this->to($this->share->client);
        }else{
            $this->to($this->share->email);
        }
    }
}
