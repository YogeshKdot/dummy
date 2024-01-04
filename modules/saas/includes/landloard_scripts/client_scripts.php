<?php

hooks()->add_action('clients_authentication_constructor', function ($obj) {
    if ('1' == get_option('tenants_landing')) {
        $plan        = !empty($obj->input->get('plan')) ? $obj->input->get('plan') : get_instance()->session->userdata('selectedPlan');
        $planDetails = getSaasPlans($plan);
        if ($plan && !empty($planDetails)) {
            get_instance()->session->set_userdata('selectedPlan', $plan);
            if (!empty($obj->input->get('plan'))) {
                set_alert('success', 'You have selected Plan: <b>'.$planDetails['plan_name'].'</b>');
            }
            if (!in_array('register', $obj->uri->segment_array())) {
                $obj->session->set_flashdata('message-success', '');
                $obj->session->unset_userdata('selectedPlan');
            }
        }
    } else {
        $obj->session->set_flashdata('message-success', '');
        $obj->session->unset_userdata('selectedPlan');
    }
});

hooks()->add_filter('before_client_added', function ($data) {
    if ('' != get_option('mysql_verification_message')) {
        $redUrl = admin_url('clients');
        if (defined('CONTACT_REGISTERING')) {
            $redUrl = site_url('authentication/login');
        }
        set_alert('warning', _l('mysql_details_not_verified'));
        redirect($redUrl);
    }

    return $data;
});

hooks()->add_action('after_client_register', function ($clientID) {
    $tenant_plan = get_instance()->session->userdata('selectedPlan');
    $contactId   = get_primary_contact_user_id($clientID);

    if (!empty($tenant_plan) && $contactId) {
        $data = [
            'contactid'    => $contactId,
            'tenant_plan'  => $tenant_plan,
            'tenants_name' => get_instance()->input->post('tenants_name'),
        ];
        get_instance()->superadmin_lib->assignPlanToClientAndInstall($data, $clientID);
        get_instance()->session->unset_userdata('selectedPlan');
    }
});

/*
 * After tenant register : Mail send log entry
 */
hooks()->add_action('email_template_sent', function ($data) {
    $isStaff = null;
    if (!is_client_logged_in() && is_staff_logged_in()) {
        $isStaff = get_staff_user_id();
    }

    $log  = _l('email_send_to', $data['email'])._l('templates_name', $data['template']->name);

    saas_activity_log($log, $isStaff);
});

hooks()->add_action('after_client_deleted', 'markClientAsDeleted');
function markClientAsDeleted($clientID)
{
    $clientPlan = get_instance()->db->get_where(db_prefix() . 'client_plan', ['userid' => $clientID])->row();
    if (!empty($clientPlan)) {
        get_instance()->db->update(db_prefix() . 'client_plan', ['is_deleted' => 1], ['userid' => $clientID]);
    }
}
