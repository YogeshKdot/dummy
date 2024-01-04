<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Tenants_CronManagement_lib
{
    public $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->model(SUPERADMIN_MODULE.'/custom_model');
    }

    public function init_tenants_cron()
    {
        $this->run_tenants_cron();
    }

    public function file_get_contents_curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, \CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, \CURLOPT_HEADER, 0);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, \CURLOPT_URL, $url);
        curl_setopt($ch, \CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);

        return $error_msg ?? $data;
    }

    private function run_tenants_cron()
    {
        $tenants = $this->ci->custom_model->getRowsWhereJoin(db_prefix().'client_plan', ['active'=>1], ['clients'], ['client_plan.userid = clients.userid']);

        foreach ($tenants as $key => $value) {
            $insert_data[$key]['called_url']    = $called_url = parse_url(base_url())['scheme'].'://'.$value->tenants_name.'.'.parse_url(base_url())['host'].'/cron/index';

            $arrContextOptions = [
               'ssl' => [
                   'verify_peer'      => false,
                   'verify_peer_name' => false,
               ],
            ];

            $response = $this->file_get_contents_curl($called_url);

            $insert_data[$key]['response']       = (empty($response)) ? 'Done' : $response;
            $insert_data[$key]['tenant_id']      = $value->userid;
            $insert_data[$key]['execution_time'] = date('Y-m-d H:i:s');
        }

        if (!empty($insert_data)) {
            $this->ci->custom_model->insertRow('cron_data', $insert_data ?? [], true);
        }
    }
}

/* End of file Tenants_CronManagement_lib.php */
/* Location: modules/saas/libraries/Tenants_CronManagement_lib.php */
