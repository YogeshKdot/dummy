<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Delivery_pdf extends App_pdf
{
    protected $pur_order;
    protected $pur_order_detail;
    protected $list_approve_status;
    protected $vendor;
    protected $tax_data;

    public function __construct($data)
    {
        parent::__construct();
        $this->SetTitle($data['title']);
        $this->pur_order        = $data['pur_order'];
        $this->pur_order_detail = $data['pur_order_detail'];
        $this->list_approve_status = $data['list_approve_status'];
        $this->vendor           = $data['vendor'];
        $this->tax_data         = $data['tax_data'];
    }

    public function prepare()
    {
        $this->set_view_vars([
            'pur_order'         => $this->pur_order,
            'pur_order_detail'  => $this->pur_order_detail,
            'list_approve_status'  => $this->list_approve_status,
            'vendor'            => $this->vendor,
            'tax_data'          => $this->tax_data,
        ]);  
        return $this->build();
    }

    protected function type()
    {
        return 'delivery_pdf';
    }

    protected function file_path()
    {        
        $customPath = module_views_path(PURCHASE_MODULE_NAME,'purchase_order/pdf/my_delivery_pdf_template.php');
        $actualPath = module_views_path(PURCHASE_MODULE_NAME,'purchase_order/pdf/delivery_pdf_template.php');
        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }
        return $actualPath;
    }
}
