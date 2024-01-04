<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Revoke_pdf extends App_pdf
{
    protected $revoke;

    public function __construct($revoke)
    {
        $revoke                = hooks()->apply_filters('request_html_pdf_data', $revoke);
        $GLOBALS['revoke_pdf'] = $revoke;

        parent::__construct();

        $this->revoke = $revoke;

        $this->SetTitle(_l('revoke'));
        # Don't remove these lines - important for the PDF layout
        $this->revoke = $this->fix_editor_html($this->revoke);
    }

    public function prepare()
    {
        $this->set_view_vars('revoke', $this->revoke);

        return $this->build();
    }

    protected function type()
    {
        return 'revoke';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . 'assets/views/revoke_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }
        return $actualPath;
    }
}