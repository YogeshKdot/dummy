<?php

/*
 * Register custom merger field
*/
register_merge_fields(SUPERADMIN_MODULE . '/merge_fields/email_verification_merge_fields');
register_merge_fields(SUPERADMIN_MODULE . '/merge_fields/tenant_expiration_merge_fields');
register_merge_fields(SUPERADMIN_MODULE . '/merge_fields/tenant_is_deactivated_merge_fields');

/*
 * Inject email template for saas
*/
hooks()->add_action('after_email_templates', 'add_email_template_for_saas');
function add_email_template_for_saas()
{
    $data['hasPermissionEdit']    = has_permission('email_templates', '', 'edit');
    $data['superadmin']           = get_instance()->emails_model->get([
        'type'     => 'superadmin',
        'language' => 'english',
    ]);
    get_instance()->load->view(SUPERADMIN_MODULE.'/mail_lists/superadmin_email_templates_list', $data, false);
}

hooks()->add_filter('available_merge_fields', 'use_client_merge_fields');
function use_client_merge_fields($fields)
{
    foreach ($fields as $key => $value) {
        if (isset($value['other'])) {
            foreach ($value['other'] as $s_key => $s_value) {
                if (!empty($value['other'][$s_key]['available'])) {
                    array_push($value['other'][$s_key]['available'], 'superadmin', 'tenants');
                }
            }
        }
        if (isset($value['client'])) {
            foreach ($value['client'] as $s_key => $s_value) {
                if (!empty($value['client'][$s_key]['available'])) {
                    array_push($value['client'][$s_key]['available'], 'superadmin', 'tenants');
                }
            }
        }
        $final_fields[$key] = $value;
    }

    return $final_fields;
}

/*
 * Create Email verification template
 */
$WeFoundYourTenantURLContent = nl2br('Hello {contact_firstname} {contact_lastname} <br><br> we found your tenant details. <br><br> tenant login url : {TENANTS_LOGIN_URL} <br><br> tenant email : {TENANT_EMAIL} <br><br> thank you', false);

create_email_template('We Found Your Tenant URL', $WeFoundYourTenantURLContent, 'superadmin', 'We Found Your Tenant URL', 'we-found-your-tenant-url');

/*
 * Create Tenant plan Expiration Reminder mail template
 */
create_email_template('Tenant Expiration Reminder', 'Email Template Content', 'superadmin', 'Tenant Expiration Reminder', 'tenant-expiration-reminder');
