<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ma_public extends ClientsController
{
    public function index()
    {
        show_404();
    }

    public function download_file($folder_indicator, $attachmentid = '')
    {   
        $this->load->helper('download');
        $this->load->model('ma_model');

        $path = '';
        if ($folder_indicator == 'ma_asset') {
            $this->db->where('rel_id', $attachmentid);
            $this->db->where('rel_type', 'ma_asset');
            $file = $this->db->get(db_prefix() . 'files')->row();
            $path = MA_MODULE_UPLOAD_FOLDER . '/assets/' . $file->rel_id . '/' . $file->file_name;

            $this->ma_model->download_asset($attachmentid);
        }else {
            die('folder not specified');
        }

        force_download($path, null);
    }

    public function tracking_click(){
        $email = $this->input->get('email');
        $campaign = $this->input->get('campaign');
        $lead = $this->input->get('lead');
        $url = $this->input->get('href');
        $this->db->where('email_id', $email);
        $this->db->where('campaign_id', $campaign);
        $this->db->where('lead_id', $lead);
        $this->db->update(db_prefix() . 'ma_email_logs', ['click' => 1]);

        $this->db->insert(db_prefix() . 'ma_email_click_logs', [
            'lead_id' => $lead,
            'campaign_id' => $campaign,
            'email_id' => $email,
            'url' => $url,
            'time' => date('Y-m-d H:i:s'),
        ]);

        header("Location: ".$url, TRUE, 301);
        exit();
    }
}