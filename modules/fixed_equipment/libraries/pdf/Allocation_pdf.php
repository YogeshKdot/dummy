<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Allocation_pdf extends App_pdf
{
    protected $allocation;

    public function __construct($allocation)
    {
        $allocation                = hooks()->apply_filters('request_html_pdf_data', $allocation);
        $GLOBALS['allocation_pdf'] = $allocation;

        parent::__construct();

        $this->allocation = $allocation;

        $this->SetTitle(_l('allocation'));
        # Don't remove these lines - important for the PDF layout
        $this->allocation = $this->fix_editor_html($this->allocation);
    }

    public function prepare()
    {
        $this->set_view_vars('allocation', $this->allocation);

        return $this->build();
    }

    protected function type()
    {
        return 'allocation';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . '/assets/views/allocation_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }
        return $actualPath;
    }
}