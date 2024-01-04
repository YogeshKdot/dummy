<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Asset_qr_code extends App_pdf
{
    protected $assets_qr_code;

    public function __construct($assets_qr_code)
    {
        $assets_qr_code                = hooks()->apply_filters('request_html_pdf_data', $assets_qr_code);
        $GLOBALS['assets_qr_code_pdf'] = $assets_qr_code;

        parent::__construct();

        $this->assets_qr_code = $assets_qr_code;

        //$this->load_language($this->request->client);
        $this->SetTitle('assets_qr_code');
        //var_dump($this->request);
        # Don't remove these lines - important for the PDF layout
        $this->assets_qr_code = $this->fix_editor_html($this->assets_qr_code);
    }

    public function prepare()
    {

        $this->set_view_vars('asset_qr_code', $this->assets_qr_code);

        return $this->build();
    }

    protected function type()
    {
        return 'asset_qr_code';
    }

    protected function file_path()
    {


        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . 'assets/asset_qr_code.php';
        var_dump(file_exists($customPath));

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}