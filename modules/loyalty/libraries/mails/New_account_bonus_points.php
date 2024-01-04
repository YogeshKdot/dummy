<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_account_bonus_points extends App_mail_template
{
    protected $for = 'contact';

    protected $loyalty_data;

    public $slug = 'loyalty-new-account-bonus-point';

    public function __construct($loyalty_data)
    {
        parent::__construct();

        $this->loyalty_data = $loyalty_data;
        // For SMS and merge fields for email
        $this->set_merge_fields('new_account_bonus_points_merge_fields', $this->loyalty_data);
    }
    public function build()
    {
        $this->to($this->loyalty_data->mail_to);
    }
}
