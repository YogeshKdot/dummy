<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class Call_logs extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('call_logs_model');
    }

    /* List all call_logs */
    public function index()
    {
        if (!has_permission('call_logs', '', 'view')) {
            access_denied('call_logs');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('call_logs', 'table'));
        }
        $data['switch_grid'] = false;

        if ($this->session->userdata('cl_grid_view') == 'true') {
            $data['switch_grid'] = true;
        }

        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();

        $this->load->model('leads_model');
        $data['leads'] = $this->leads_model->get();

        $this->load->model('clients_model');
        $data['clcustomers'] = $this->clients_model->get();

        $data['rel_types'] = get_related_to_types();

        $data['title']                 = _l('call_logs_tracking');

        $this->app_scripts->add('mindmap-js','modules/call_logs/assets/js/call_logs.js');
        $this->load->view('manage', $data);
    }
    /* Prepare the table function to display the records in table format. */
    public function table($clientid = '')
    {
        if (!has_permission('call_logs', '', 'view')) {
            access_denied('call_logs');
        }

        $data['clientid'] = $clientid;

        $this->app->get_table_data(module_views_path('call_logs', 'table'), $data);
    }
    /* Get the data ready for grid view. */
    public function grid()
    {
        echo $this->load->view('call_logs/grid', [], true);
    }
    /* Make a relationship with client and customer tables. */
    public function call_log_relations($clientid, $customer_type)
    {
        $data['clientid'] = $clientid;
        $data['customer_type'] = $customer_type;

        $this->app->get_table_data(module_views_path('call_logs', 'call_log_relations'), $data);
    }

    /**
     * Task ajax request modal
     * @param  mixed $id
     * @return mixed
     */
    public function get_call_log_data($id)
    {
        $call_log = $this->call_logs_model->get($id);

        if (!$call_log) {
            header('HTTP/1.0 404 Not Found');
            echo 'Call Log not found';
            die();
        }

        $data['call_log']  = $call_log;

        $html =  $this->load->view('view_call_log_template', $data, true);
        echo $html;
    }

    /* Call log function to handle create, view, edit views. */
    public function call_log($id = '')
    {
        if (!has_permission('call_logs', '', 'view')) {
            access_denied('call_logs');
        }
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('call_logs', '', 'create')) {
                    access_denied('call_logs');
                }
                    $id = $this->call_logs_model->add($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('call_log')));
                    redirect(admin_url('call_logs/call_log/' . $id));
                }
            } else {
                if (!has_permission('call_logs', '', 'edit')) {
                    access_denied('call_logs');
                }
                $success = $this->call_logs_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('call_log')));
                }
                redirect(admin_url('call_logs/call_log/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('call_log_lowercase'));
        } else {
            $data['call_log']        = $this->call_logs_model->get($id);
            $title = _l('edit', _l('call_log_lowercase'));
        }

        $data['owner']         = $this->staff_model->get(get_staff_user_id());
        $data['staff']         = $this->staff_model->get('',["staffid <> " => get_staff_user_id()]);
        $this->load->model('staff_model');
        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active'=>1]);

        $this->load->model('contracts_model');
        $data['contract_types']        = $this->contracts_model->get_contract_types();
        $data['title']                 = $title;

        $this->load->view('call_log', $data);
    }


    /* Call log function to handle preview views. */
    public function preview($id = 0)
    {
        if (!has_permission('call_logs', '', 'view')) {
            access_denied('call_logs');
        }
        $data['call_log']        = $this->call_logs_model->get($id);

        if (!$data['call_log']) {
            blank_page(_l('cl_not_found'), 'danger');
        }

        $title = _l('preview_call_log');

        $data['owner']         = $this->staff_model->get(get_staff_user_id());
        $data['staff']         = $this->staff_model->get('',["staffid <> " => get_staff_user_id()]);
        $this->load->model('staff_model');
        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active'=>1]);

        $data['title']                 = $title;

        $this->load->view('preview', $data);
    }

    /* Delete from database */
    public function delete($id)
    {
        if (!has_permission('call_logs', '', 'delete')) {
            access_denied('call_logs');
        }
        if (!$id) {
            redirect(admin_url('call_logs'));
        }
        $response = $this->call_logs_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('call_log')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('call_log_lowercase')));
        }
        redirect(admin_url('call_logs'));
    }

    /*********call logs types**********/
    public function category()
    {
        if (!is_admin() && get_option('staff_members_create_inline_expense_categories') == '0') {
            access_denied('call_logs');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->call_logs_model->add_category($this->input->post());
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $id ? _l('added_successfully', _l('call_log_category')) : '',
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->expenses_model->update_category($data, $id);
                $message = _l('updated_successfully', _l('call_logs_category'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }

    /* Get the relationship of Types. */
    public function get_relation_data()
    {
        if ($this->input->post()) {
            $type = $this->input->post('type');
            $data = get_relation_data_for_cl($type);
            if ($this->input->post('rel_id')) {
                $rel_id = $this->input->post('rel_id');
            } else {
                $rel_id = '';
            }

            $relOptions = init_relation_options($data, $type, $rel_id);
            echo json_encode($relOptions);
            die;
        }
    }
    /* Prepare Data for the Overview tab/graphs. */
    public function overview(){
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');

        $start_of_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_of_month = Carbon::now()->endOfMonth()->format('Y-m-d');

        $data['daily_count']        = $this->call_logs_model->count_inbound_outbound_calls(Carbon::now()->format("Y-m-d"), Carbon::now()->format("Y-m-d"));
        $data['week_count']        = $this->call_logs_model->count_inbound_outbound_calls($weekStartDate, $weekEndDate);
        $data['month_count']        = $this->call_logs_model->count_inbound_outbound_calls($start_of_month, $end_of_month);

        $data['weekly_chart_Date']        = json_encode($this->call_logs_model->get_inbound_outbound_report($weekStartDate, $weekEndDate));
        $data['monthly_chart_Date']        = json_encode($this->call_logs_model->get_inbound_outbound_report($start_of_month, $end_of_month));

        $this->load->view('gantt', $data);
    }
    /* Switch functionality between list and grid view. */
    public function switch_grid($set = 0, $manual = false)
    {
        if ($set == 1) {
            $set = 'false';
        } else {
            $set = 'true';
        }

        $this->session->set_userdata([
            'cl_grid_view' => $set,
        ]);
        if ($manual == false) {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
