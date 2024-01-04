<?php

class Products_lib
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->model('products/products_model');
    }
}
