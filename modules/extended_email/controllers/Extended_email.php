<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Extended_email extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['extended_email_model', 'staff_model']);
        $this->load->helper('extended_email');
    }

    /*
    * load staff email setting form
    */
    public function index()
    {
        $email_settings = [];
        $data['title']  = _l('extended_email');

        // below code gives staff email setting when staff logged_in
        if (!is_admin()) {
            $data['email_settings'] = $this->extended_email_model->get_staff_extended_email_settings(get_staff_user_id());
        }
        $this->load->view('smtp_extended_email', $data);
    }

    /*
    * save and update staff email settings
    */
    public function save_staff_email_settings()
    {
        $posted_data = $this->input->post();

        $check_user_email_settings = $this->db->where(['userid' => $posted_data['userid']])->count_all_results(db_prefix().'extended_email_settings');

        if (isset($posted_data['test_email'])) {
            unset($posted_data['test_email']);
        }
        if (1 == $check_user_email_settings) {
            $where['userid'] = $posted_data['userid'];
            $this->extended_email_model->update($posted_data, $where);
            $this->email_log_activity($posted_data['userid'], 'email_settings_updated');
        } else {
            $inserted_id = $this->extended_email_model->save($posted_data);
            if ($inserted_id) {
                $this->email_log_activity($inserted_id, 'email_settings_added');
            }
        }
        redirect(admin_url('extended_email'), 'refresh');
    }

    /*
    * get email settings for selected staff members
    * this method is for admin
    */
    public function get_email_settings()
    {
        $posted_data = $this->input->post();

        $data = $this->extended_email_model->get_staff_extended_email_settings($posted_data['staff_id']);

        if (!empty($data->smtp_password)) {
            $data->smtp_password = $this->encryption->decrypt($data->smtp_password);
        }
        echo json_encode($data);
    }

    /*
    * change active status for staff
    * allow admin to give permission to set staff email settings
    */
    public function change_active_status()
    {
        if ($this->input->is_ajax_request()) {
            $staffid         = $this->input->post('staffid');
            $where['userid'] = $staffid;
            $data['active']  = $this->input->post('active');

            $count = $this->db->where('userid', $staffid)->count_all_results(db_prefix().'extended_email_settings');
            if (0 == $count) {
                $staff_data = ['userid' => $staffid];
                $this->extended_email_model->save($staff_data);
            }
            if ($this->extended_email_model->update($data, $where)) {
                $this->email_log_activity($this->input->post('staffid'), 'email_settings_active_status_changed');
                echo json_encode([
                    'success' => true,
                    'message' => _l('email_settings_active_status_changed'),
                ]);
            }
        }
    }

    /*
    * send smtp test mail
    */
    public function sent_smtp_test_email()
    {
        if ($this->input->post()) {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('userid'))) {
                    $check_settings = $this->extended_email_model->get_staff_extended_email_settings($this->input->post('userid'));

                    /*
                    * if email settings not properly set then give error message
                    */
                    if (!empty($check_settings)) {
                        if ('' == $check_settings->smtp_host || '' == $check_settings->smtp_password) {
                            echo json_encode([
                                'status'  => 'danger',
                                'message' => 'Your SMTP settings are not set correctly.',
                            ]);
                            exit;
                        }
                    }
                }
                /*
                * if not select any staff member then give error message
                */
                else {
                    echo json_encode([
                        'status'  => 'danger',
                        'message' => _l('please_select_at_least_one_staff'),
                    ]);
                    exit;
                }

                $this->session->set_userdata('override_staff_id', $this->input->post('userid'));
                $this->config->load('extended_email/email', true);

                $settings = $this->config->item('email');
                if ($settings['has_setting']) {
                    $this->load->library('email');
                    $this->email->initialize($this->config->item('email'));
                }
            }

            $template           = new stdClass();
            $template->message  = get_option('email_header').'This is test SMTP email. <br />If you received this message that means that your SMTP settings is set correctly.'.get_option('email_footer');
            $template->fromname = '' != get_option('companyname') ? get_option('companyname') : 'TEST';
            $template->subject  = 'SMTP Setup Testing';

            $template = parse_email_template($template);

            if ('phpmailer' == get_option('mail_engine')) {
                $this->email->set_debug_output(function ($err) {
                    if (!isset($GLOBALS['debug'])) {
                        $GLOBALS['debug'] = '';
                    }
                    $GLOBALS['debug'] .= $err.'<br />';

                    return $err;
                });

                $this->email->set_smtp_debug(3);
            }

            $this->email->set_newline(config_item('newline'));
            $this->email->set_crlf(config_item('crlf'));

            $this->email->from(config_item('smtp_user'), $template->fromname);
            $this->email->to($this->input->post('test_email'));

            $systemBCC = get_option('bcc_emails');

            if ('' != $systemBCC) {
                $this->email->bcc($systemBCC);
            }

            $this->email->subject($template->subject);
            $this->email->message($template->message);
            if ($this->email->send(true)) {
                $message = 'Seems like your SMTP settings is set correctly. Check your email now.';
                $status  = 'success';
                hooks()->do_action('smtp_test_email_success');
            } else {
                $message = 'Your SMTP settings are not set correctly.';
                $status  = 'danger';
                hooks()->do_action('smtp_test_email_failed');
            }

            $this->session->unset_userdata('override_staff_id');
            $this->email_log_activity($this->input->post('userid'), 'test_mail_sended');
            echo json_encode([
                'status'  => $status,
                'message' => $message,
            ]);
        }
    }

    /*
    * add log activity in database
    */
    public function email_log_activity($email_userid, $description)
    {
        $log_data = [
            'staffid'      => get_staff_user_id(),
            'email_userid' => $email_userid,
            'description'  => $description,
            'datetime'     => date('Y-m-d H:i:s'),
        ];

        $this->extended_email_model->add_log_activity($log_data);
    }

    /*
    * load log activity table
    */
    public function extended_email_log_history()
    {
        $data['title'] = _l('extended_email_log_history');
        $this->load->view('extended_email_log_history', $data);
    }

    /*
    * datatable load
    */
    public function extended_email_log_history_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('extended_email', 'tables/extended_email_log_history_table'));
        }
    }

    /*
    * this methos for clear log
    */
    public function extended_email_clear_log()
    {
        $result = $this->db->truncate(db_prefix().'extended_email_log_activity');
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => _l('extended_email_activity_log_clear'),
            ]);
        }
    }
}

/* End of file Extended_email.php */
