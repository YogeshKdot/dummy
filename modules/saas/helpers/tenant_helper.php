<?php

if (!function_exists("getSubDomain")) {
    function getSubDomain() {
        $scheme          = $_SERVER['REQUEST_SCHEME'] ?? $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? (('on' == strtolower($_SERVER['HTTPS'])) ? 'https' : 'http');
        $url             = $scheme."://{$_SERVER['HTTP_HOST']}".str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $urlSegments     = parse_url($url);

        // remove www from url;
        $urlHostSegments = explode('.', preg_replace('/^www\./', '', $urlSegments['host']));

        return (count($urlHostSegments) > 2) ? $urlHostSegments[0] : false;
    }
}

validate_tenant();

function validate_tenant()
{
    $tenant = getSubDomain();
    if ($tenant) {
        get_instance()->load->database();

        /* Get options */
        get_instance()->db->select('name,value');
        get_instance()->db->where_in('name', ['mysql_host', 'mysql_port', 'active_language']);
        $row     = get_instance()->db->get(db_prefix().'options')->result();
        $options = array_column($row, 'value', 'name');

        get_instance()->lang->load('saas/saas', $options['active_language']);

        $query       = get_instance()->db->select('*')->from('modules')->where(['module_name' => 'saas'])->get();
        $saas_active = $query->row('active');

        if ('0' == $saas_active) {
            show_404();
        }

        $where['tenants_name'] = $tenant;

        $query  = get_instance()->db->select('*')->from('client_plan')->where($where)->get();
        $client = $query->row();

        if (empty($client)) {
            $data['title']   =  _l('tenant_not_registered');
            $data['message'] = _l('not_registered');
            echo get_instance()->load->view('saas/inactive', $data, true);
            exit;
        }

        $where = [
            'userid'     => $client->userid,
            'is_primary' => '1',
        ];
        $contact = get_instance()->db->get_where(db_prefix().'contacts', $where)->row();

        $data['get_intouch_link'] = base_url().'clients/open_ticket';

        // if teanant is not active then redirect to inactive page with message
        if (!$client->is_active) {
            $data['title']   =  _l('tenant_inactive');
            $data['message'] = _l('installation_is_inactive');
            echo get_instance()->load->view('saas/inactive', $data, true);
            exit;
        }

        // if teanant is not email verify then redirect to email verification page with message
        if ($client->is_active && null == $contact->email_verified_at) {
            $data['title']   =  _l('email_verification');
            $data['message'] = _l('email_verification_is_require');
            echo get_instance()->load->view('saas/inactive', $data, true);
            exit;
        }

        get_instance()->load->helper('saas/superadmin');
        get_instance()->load->library('encryption');

        $tenant_password = get_instance()->encryption->decrypt($client->tenants_db_password);

        switchDatabase($client->tenants_db, $client->tenants_db_username, $tenant_password, $options['mysql_host'], $options['mysql_port']);

        // insert row in module table for tenant_management module and make it active by default.
        if (!total_rows(db_prefix().'modules', ['module_name' => 'saas', 'active' => 1])) {

            // force enable tenant management module in branch
            add_option('superadmin_enabled', 1);

            // remove help menu
            add_option('show_help_on_setup_menu', 0);

            get_instance()->load->library('app');
            get_instance()->load->library('app_modules');
            get_instance()->app_modules->activate('saas');
        }
    }
}

function get_limitations($tenant = null)
{
    $tenant = empty($tenant) ? getSubDomain() : $tenant;
    if ($tenant) {
        switchDatabase();
        $where['tenants_name'] = $tenant;
        get_instance()->load->model('saas/superadmin_model');
        $client               = get_instance()->superadmin_model->getSingleRow('client_plan', $where);
        $planDetails          = getSaasPlans($client->plan_id);
        $selected_limitations = json_decode($planDetails['limitations'], true);

        get_instance()->load->library('encryption');
        $tenant_password = get_instance()->encryption->decrypt($client->tenants_db_password);

        switchDatabase($client->tenants_db, $client->tenants_db_username, $tenant_password, get_option('mysql_host'), get_option('mysql_port'));

        return $selected_limitations;
    }
}
