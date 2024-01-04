<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Repair_pdf extends App_pdf
{

    protected $repair;

    public function __construct($repair)
    {
        $repair                = hooks()->apply_filters('request_html_pdf_data', $repair);
        $GLOBALS['repair_pdf'] = $repair;

        parent::__construct();

        $this->repair = $repair;

        $this->SetTitle(_l('repair'));
        # Don't remove these lines - important for the PDF layout
        $this->repair = $this->fix_editor_html($this->repair);
    }

    public function prepare()
    {
        $this->set_view_vars('repair', $this->repair);

        return $this->build();
    }

    protected function type()
    {
        return 'repair';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . 'assets/views/repair_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }
        return $actualPath;
    }
}