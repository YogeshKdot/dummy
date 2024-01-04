<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Automation_manager extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_admin()) {
            redirect(admin_url());
        }
        $this->load->model('Automation_model');
    }

    /**
     * Display page with table of automations
     */
    public function index()
    {
        $data['title'] = _l("Automation manager ");

        $this->load->view('automation_manager/index', $data);
    }

    /**
     * Prepare automations table data and return as SST
     */
    public function table()
    {
        return $this->app->get_table_data(module_views_path('automation_manager', 'tables/automations'));
    }

    /**
     * Display page to create automation
     */
    public function create()
    {
        $data = $this->getSelectsData();
        $data['title'] = _l("Add new automation ");


        $this->load->view('automation_manager/create', $data);
    }

    /**
     * Define triggers and actions
     * Get staffs, statuses and priorities
     */
    private function getSelectsData()
    {
        $data = [];
        $data['triggers'] = [
            ['value' => 'status', 'label' => _l('Task status changed to')],
            ['value' => 'start_date', 'label' => _l('Start date is today')],
            // ['value' => 'finish_date', 'label' => _l('Finish date is today')],
            ['value' => 'due_date', 'label' => _l('Due date is today')],
            ['value' => 'priority', 'label' => _l('Priority changed to')],
            // ['value' => 'custom_field', 'label' => _l('Custom field changed to')],
            ['value' => 'task_created', 'label' => _l('Task created')],
            ['value' => 'due_date_changed', 'label' => _l('Due date changed')],
            ['value' => 'start_date_changed', 'label' => _l('Start date changed')],
            ['value' => 'inactive', 'label' => _l('Inactive for days')],
        ];

        $data['actions'] = [
            ['value' => 'change_status', 'label' => _l('Change status to')],
            ['value' => 'add_comment', 'label' => _l('Add comment')],
            ['value' => 'add_timer', 'label' => _l('Add timer')],
            ['value' => 'change_priority', 'label' => _l('Change priority to')],
            ['value' => 'set_follower', 'label' => _l('Change follower')],
            ['value' => 'set_assignee', 'label' => _l('Change assignee')],
            ['value' => 'add_reminder', 'label' => _l('Add reminder')],
            ['value' => 'set_custom_field', 'label' => _l('Set custom field')],
            ['value' => 'add_tag', 'label' => _l('Add tag')],
            ['value' => 'change_due_date', 'label' => _l('Change due date')],
        ];

        $this->load->model('Tasks_model');
        $data['statuses'] = $this->Tasks_model->get_statuses();
        $this->load->model('Staff_model');
        $data['staff'] = $this->Staff_model->get();
        $data['priorities'] = get_tasks_priorities();
        $data['customFields'] = $this->db->where('fieldto', 'tasks')->get(db_prefix() . 'customfields')->result_array();
        return $data;
    }

    /**
     * Store new automation from request
     */
    public function store()
    {
        $data = $this->input->post();
        if (!$this->validate($data)) {
            redirect(admin_url('automation_manager/create'));
        }
        $this->Automation_model->store($data);
        redirect(admin_url('automation_manager'));
    }

    /**
     * Validate if data has name, join, triggers, actions fields
     */
    private function validate($data)
    {
        return $data['name'] && $data['join'] && $data['triggers'] && $data['actions'];
    }

    /**
     * Disaplay edit form for automation
     */
    public function edit($id)
    {
        $data = $this->getSelectsData();
        $automation = $this->Automation_model->get($id);
        if (!$automation) {
            redirect(admin_url('automation_manager'));
        }
        $data['automation'] = $automation;

        $this->load->view('automation_manager/update',  $data);
    }

    /**
     * Update automation by id
     */
    public function update($id)
    {
        $data = $this->input->post();

        if (!$this->validate($data)) {
            redirect(admin_url('automation_manager/edit/' . $id));
        }

        $this->Automation_model->update($id, $data);
        redirect(admin_url('automation_manager'));
    }

    /**
     * Delete automation by id
     */
    public function delete($id)
    {

        $this->Automation_model->delete($id);
        redirect(admin_url('automation_manager'));
    }

    /**
     * Activate automation
     */
    public function activate($id)
    {
        $this->Automation_model->activate($id);
        redirect(admin_url('automation_manager'));
    }

    /**
     * Deactivate automation
     */
    public function deactivate($id)
    {
        $this->Automation_model->activate($id, false);
        redirect(admin_url('automation_manager'));
    }
}
