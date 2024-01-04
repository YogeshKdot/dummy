<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Telegram_notification extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Telegram_notification_model');
      
    }
    public function index()
    {  
        
    }

