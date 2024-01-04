<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leads extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('landingpage_model');
        $this->load->model('leads_model');
    }

     /* List all leads */
    public function index()
    {
        if (!has_permission('landingpages-leads', '', 'view')) {
            access_denied('landingpages-leads');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('zillapage', 'leads/table'));
        }
        $data['title']                 = _l('form_leads');
        $landingpages         = $this->landingpage_model->get_all_landing_pages();
        $data['landingpages'] = $landingpages;

        $this->load->view('leads/index', $data);
    }
    public function delete($id)
    {
        if (!has_permission('landingpages-leads', '', 'delete')) {
            access_denied('landingpages');
        }
        if (!$id) {
            redirect(admin_url('zillapage/leads/index'));
        }
        $response = $this->landingpage_model->delete_lead($id);
        if ($response == true) {
            set_alert('success', _l('deleted'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(admin_url('zillapage/leads/index'));
    }

    public function get_convert_data_landingpage_leads($id)
    {
        if (!has_permission('landingpages-leads', '', 'edit')) {
            ajax_access_denied();
        }
        if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
            $this->load->model('gdpr_model');
            $data['purposes'] = $this->gdpr_model->get_consent_purposes($id, 'lead');
        }

        $data['lead'] = $this->landingpage_model->get_form_lead($id);
        $this->load->view('leads/convert_landingpage_lead_to_customer', $data);
    }

    public function get_convert_form_data_to_leads($id)
    {
        if (!has_permission('landingpages-leads', '', 'edit')) {
            ajax_access_denied();
        }
        $data['members'] = $this->staff_model->get('', [
            'active'       => 1,
            'is_not_staff' => 0,
        ]);
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources']  = $this->leads_model->get_source();
        $data['lead'] = $this->landingpage_model->get_form_lead($id);
        $this->load->view('leads/convert_landingpage_form_data_to_lead', $data);
    }

    public function convert_to_lead()
    {
        if (!has_permission('landingpages-leads', '', 'edit')) {
            access_denied('Landingpages leads Convert to Customer');

        }

        if ($this->input->post()) {
            $data             = $this->input->post();
            $formdata_id = $data['formdata_id'];
            unset($data['formdata_id']);

            $insert_id      = $this->landingpage_model->add_convert_to_lead($data);
            $message = $insert_id ? _l('added_successfully', _l('lead')) : '';

            if ($insert_id) {
               
                handle_tags_save($tags, $insert_id, 'lead');

                if (isset($custom_fields)) {
                    handle_custom_fields_post($insert_id, $custom_fields);
                }
                
                if (is_gdpr() && get_option('gdpr_after_lead_converted_delete') == '1') {
                    // When lead is deleted
                    $this->landingpage_model->delete_lead($formdata_id);

                }

                $this->leads_model->lead_assigned_member_notification($insert_id, $data['assigned']);
                hooks()->do_action('lead_created', $insert_id);
                log_activity('Created Lead [Landing Page Form Data: ' . $formdata_id . ', Lead: ' . $insert_id . ']');
                
                set_alert('success', _l('form_data_landingpage_to_lead_converted_success'));
                redirect(admin_url('leads'));
                
            }

        }
    }

    public function convert_to_customer()
    {
        if (!has_permission('landingpages-leads', '', 'edit')) {
            access_denied('Landingpages leads Convert to Customer');
        }

        if ($this->input->post()) {
            $default_country  = get_option('customer_default_country');
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            $formdata_id = $data['formdata_id'];
            $original_lead_email = $data['original_lead_email'];
            unset($data['original_lead_email']);
            unset($data['formdata_id']);

            

            if ($data['country'] == '' && $default_country != '') {
                $data['country'] = $default_country;
            }

            $data['billing_street']  = $data['address'];
            $data['billing_city']    = $data['city'];
            $data['billing_state']   = $data['state'];
            $data['billing_zip']     = $data['zip'];
            $data['billing_country'] = $data['country'];

            $data['is_primary'] = 1;
            $id                 = $this->clients_model->add($data, true);
            if ($id) {
                $primary_contact_id = get_primary_contact_user_id($id);

                if (!has_permission('customers', '', 'view') && get_option('auto_assign_customer_admin_after_lead_convert') == 1) {
                    $this->db->insert(db_prefix() . 'customer_admins', [
                        'date_assigned' => date('Y-m-d H:i:s'),
                        'customer_id'   => $id,
                        'staff_id'      => get_staff_user_id(),
                    ]);
                }

                set_alert('success', _l('lead_to_client_base_converted_success'));

                if (is_gdpr() && get_option('gdpr_after_lead_converted_delete') == '1') {
                    // When lead is deleted
                    $this->landingpage_model->delete_lead($formdata_id);

                }
                log_activity('Created Lead Client Profile [Landing Page Form Data: ' . $formdata_id . ', ClientID: ' . $id . ']');
                redirect(admin_url('clients/client/' . $id));
            }
        }
    }
   

    
}
