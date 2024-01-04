<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Task_bookmarks extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('task_bookmarks_model');
        $this->load->model('staff_model');
        $this->load->model('tasks_model');

    }
    
	public function index(){
        $this->load->model('staff_model');

        /*$staff_id = get_staff_user_id();
        $list = [];
        $staff_parent = $this->db->query('select * from tblstaff where staffid = '.get_staff_user_id())->result_array();
        $list = $staff_parent;
        
        $list_staff = $this->staff_model->get();*/
        /*$data['staff'] = $this->task_bookmarks_model->get_list_children_by_staffid(get_staff_user_id(),$list,$list_staff);*/
        /*$data['projects'] = $this->projects_model->get();
        $data['milestones'] = $this->projects_model->get_milestones(0);*/
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('task_bookmarks', 'table'));
        }
        $data['id'] = 0;
        $data['title'] = _l('new_task_bookmarks');
        $this->load->view('task_bookmarks', $data);
    }

    public function task_bookmarks_list_task_filter()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                'name',
                '(SELECT tblmilestones.name from tblmilestones where tblmilestones.id = tblstafftasks.milestone) as milestone_name',
                get_sql_select_task_asignees_full_names() . ' as assignees',
                'startdate',
                'duedate',
                '(SELECT SUM(base_hour + ot_hour) FROM tbltaskstimers WHERE task_id=tblstafftasks.id) as total_logged_time',
                'status',
            ];
            $where = [];
            if ($this->input->post('rel_id')) {
                $rel_id  = $this->input->post('rel_id');
                array_push($where, 'AND rel_id="'.htmlspecialchars($rel_id).'"');
            }
            if ($this->input->post('rel_type')) {
                $rel_type  = $this->input->post('rel_type');
                array_push($where, 'AND rel_type="'.htmlspecialchars($rel_type).'"');
            }
            
            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = 'tblstafftasks';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'tblstafftasks.id',
                'datefinished',
        'rel_type',
        'rel_id',
        'recurring',
        tasks_rel_name_select_query() . ' as rel_name',
        'billed',
        '(SELECT staffid FROM tblstafftaskassignees WHERE taskid=tblstafftasks.id AND staffid=' . get_staff_user_id() . ') as is_assigned',
        get_sql_select_task_assignees_ids() . ' as assignees_ids',
        '(SELECT MAX(id) FROM tbltaskstimers WHERE task_id=tblstafftasks.id and staff_id=' . get_staff_user_id() . ' and end_time IS NULL) as not_finished_timer_by_current_staff',
        '(SELECT staffid FROM tblstafftaskassignees WHERE taskid=tblstafftasks.id AND staffid=' . get_staff_user_id() . ') as current_user_is_assigned',
        '(SELECT CASE WHEN addedfrom=' . get_staff_user_id() . ' AND is_added_from_contact=0 THEN 1 ELSE 0 END) as current_user_is_creator',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
            $row = [];
            $outputName = '';

            if ($aRow['not_finished_timer_by_current_staff']) {
                $outputName .= '<span class="pull-left text-danger"><i class="fa fa-clock-o fa-fw"></i></span>';
            }

            $outputName .= '<a href="' . admin_url('tasks/view/' . htmlspecialchars($aRow['id'])) . '" class="display-block main-tasks-table-href-name' . (!empty($aRow['rel_id']) ? ' mbot5' : '') . '" onclick="init_task_modal(' . htmlspecialchars($aRow['id']) . '); return false;">' . htmlspecialchars($aRow['name']) . '</a>';
            $outputName .= '<div class="row-options">';
            $outputName .= '</div>';

            $row[] = $outputName;

            $row[] = $aRow['milestone_name'];

            $row[] = format_members_by_ids_and_names($aRow['assignees_ids'], $aRow['assignees']);
            

            $row[] = _dt($aRow['startdate']);

            $row[] = _dt($aRow['duedate']);
            if($aRow['total_logged_time'] != ''){
                $row[] = _format_number($aRow['total_logged_time']);
            }else{
                $row[] = 0;
            }
        
            $status          = get_task_status_by_id($aRow['status']);
            $outputStatus    = '';

            $status_color = $status['color'];
            $status_name = $status['name'];
            if(($aRow['status'] != 5 && $aRow['duedate'] < date('Y-m-d')) || ($aRow['status'] === 5 && $aRow['duedate'] < $aRow['datefinished'])){
                $status_name .= '<span class="text-danger">('._l('late').')</span>';
            }
            $outputStatus .= '<span class="inline-block label" style="color:' . htmlspecialchars($status_color) . ';border:1px solid ' . htmlspecialchars($status_color) . '" task-status-table="' . htmlspecialchars($aRow['status']) . '">';

            $outputStatus .= $status_name;
            

        $outputStatus .= '</span>';

        $row[] = $outputStatus;

        $row[] = icon_btn('#', 'external-link', 'btn-success', ["data-toggle"=>"tooltip", "title"=>"Add to Task Bookmarks", "onclick" => "add_list_tasks(".htmlspecialchars($aRow['id']).")"]);

        /*$hook_data = do_action('tasks_table_row_data', [
            'output' => $row,
            'row'    => $aRow,
        ]);

        $row = $hook_data['output'];*/

        $row['DT_RowClass'] = 'has-row-options';
        if ((!empty($aRow['duedate']) && $aRow['duedate'] < date('Y-m-d')) && $aRow['status'] != 5) {
            $row['DT_RowClass'] .= ' text-danger';
        }

        $output['aaData'][] = $row;
    }

            echo json_encode($output);
            die();
        }
    }

    public function task_bookmarks_list_task_add()
    {

        if ($this->input->is_ajax_request()) {

            $select = [
                'name',
                '(SELECT tblmilestones.name from tblmilestones where tblmilestones.id = tbltasks.milestone) as milestone_name',
                get_sql_select_task_asignees_full_names() . ' as assignees',
                'startdate',
                'duedate',
                'status',
            ];
            $where = [];
           
            if ($this->input->post('list_tasks')) {
                $list_tasks  = $this->input->post('list_tasks');
                $list_tasks = explode( ',',$list_tasks);
                if(is_array($list_tasks)){
                $_where = '';
                foreach ($list_tasks as $taskid) {
                    if($_where == ''){
                        $_where .= 'AND (';
                    }else{
                        $_where .= ' or ';
                    }
                    $_where .= 'id = '.htmlspecialchars($taskid);
                }
                 if($_where != ''){
                        $_where .= ')';
                    }
                array_push($where, $_where);
                }else{
                    array_push($where, 'AND id = '.htmlspecialchars($list_tasks));
                }
            }else{
                array_push($where, 'AND id = 0');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = 'tbltasks';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'tbltasks.id',
                'datefinished',
        'rel_type',
        'rel_id',
        'recurring',
        tasks_rel_name_select_query() . ' as rel_name',
        'billed',
        get_sql_select_task_assignees_ids() . ' as assignees_ids',
        '(SELECT MAX(id) FROM tbltaskstimers WHERE task_id=tbltasks.id and staff_id=' . get_staff_user_id() . ' and end_time IS NULL) as not_finished_timer_by_current_staff',
        '(SELECT CASE WHEN addedfrom=' . get_staff_user_id() . ' AND is_added_from_contact=0 THEN 1 ELSE 0 END) as current_user_is_creator',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
            $row = [];
            $outputName = '';

            if ($aRow['not_finished_timer_by_current_staff']) {
                $outputName .= '<span class="pull-left text-danger"><i class="fa fa-clock-o fa-fw"></i></span>';
            }

            $outputName .= '<a href="' . admin_url('tasks/view/' . htmlspecialchars($aRow['id'])) . '" class="display-block main-tasks-table-href-name' . (!empty($aRow['rel_id']) ? ' mbot5' : '') . '" onclick="init_task_modal(' . htmlspecialchars($aRow['id']) . '); return false;">' . htmlspecialchars($aRow['name']) . '</a>';
            $outputName .= '<div class="row-options">';
            $outputName .= '</div>';

            $row[] = $outputName;

            $row[] = $aRow['milestone_name'];

            $row[] = format_members_by_ids_and_names($aRow['assignees_ids'], $aRow['assignees']);
            

            $row[] = _dt($aRow['startdate']);

            $row[] = _dt($aRow['duedate']);
            $sum = 0;
            $time = $this->tasks_model->get_timesheeets($aRow['id']);
            
            foreach($time as $tm){
                if($tm['time_spent'] == NULL){

                   $str = sec2qty(time() - $tm['start_time']);
                  } else {
                   
                   $str =  sec2qty($tm['time_spent']);
                  }
                $sum += $str;
            }

            $row[] = _format_number($sum);
        
            $status          = get_task_status_by_id($aRow['status']);
            $outputStatus    = '';

            $status_color = $status['color'];
            $status_name = $status['name'];
            if(($aRow['status'] != 5 && $aRow['duedate'] < date('Y-m-d')) || ($aRow['status'] === 5 && $aRow['duedate'] < $aRow['datefinished'])){
                $status_name .= '<span class="text-danger">('._l('late').')</span>';
            }
            $outputStatus .= '<span class="inline-block label" style="color:' . htmlspecialchars($status_color) . ';border:1px solid ' . htmlspecialchars($status_color) . '" task-status-table="' . htmlspecialchars($aRow['status']) . '">';

            $outputStatus .= $status_name;
            

        $outputStatus .= '</span>';

        $row[] = $outputStatus;

        $row[] = icon_btn('#', 'compress', 'btn-danger', ["data-toggle"=>"tooltip", "title"=>"Remove to Task Bookmarks", "onclick" => "remove_list_tasks(".htmlspecialchars($aRow['id']).")"]);

        $row['DT_RowClass'] = 'has-row-options';
        if ((!empty($aRow['duedate']) && $aRow['duedate'] < date('Y-m-d')) && $aRow['status'] != 5) {
            $row['DT_RowClass'] .= ' text-danger';
        }

        $output['aaData'][] = $row;
    }

    

            echo json_encode($output);
            die();
        }
    }

    public function view_task_bookmarks_list_task()
    {

        if ($this->input->is_ajax_request()) {

            $select = [
                'name',
                '(SELECT tblmilestones.name from tblmilestones where tblmilestones.id = tbltasks.milestone) as milestone_name',
                get_sql_select_task_asignees_full_names() . ' as assignees',
                'startdate',
                'duedate',
                '(SELECT SUM(base_hour + ot_hour) FROM tbltaskstimers WHERE task_id=tbltasks.id) as total_logged_time',
                'status',
            ];
            $where = [];
           
            if ($this->input->post('list_tasks')) {
                $list_tasks  = $this->input->post('list_tasks');
                $list_tasks = explode( ',',$list_tasks);
                if(is_array($list_tasks)){
                $_where = '';
                foreach ($list_tasks as $taskid) {
                    if($_where == ''){
                        $_where .= 'AND (';
                    }else{
                        $_where .= ' or ';
                    }
                    $_where .= 'id = '.htmlspecialchars($taskid);
                }
                 if($_where != ''){
                        $_where .= ')';
                    }
                array_push($where, $_where);
                }else{
                    array_push($where, 'AND id = '.htmlspecialchars($list_tasks));
                }
            }else{
                array_push($where, 'AND id = 0');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = 'tbltasks';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'tbltasks.id',
                'datefinished',
        'rel_type',
        'rel_id',
        'recurring',
        tasks_rel_name_select_query() . ' as rel_name',
        'billed',
        '(SELECT staffid FROM tblstafftaskassignees WHERE taskid=tbltasks.id AND staffid=' . get_staff_user_id() . ') as is_assigned',
        get_sql_select_task_assignees_ids() . ' as assignees_ids',
        '(SELECT MAX(id) FROM tbltaskstimers WHERE task_id=tbltasks.id and staff_id=' . get_staff_user_id() . ' and end_time IS NULL) as not_finished_timer_by_current_staff',
        '(SELECT staffid FROM tblstafftaskassignees WHERE taskid=tbltasks.id AND staffid=' . get_staff_user_id() . ') as current_user_is_assigned',
        '(SELECT CASE WHEN addedfrom=' . get_staff_user_id() . ' AND is_added_from_contact=0 THEN 1 ELSE 0 END) as current_user_is_creator',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
            $row = [];
            $outputName = '';

            if ($aRow['not_finished_timer_by_current_staff']) {
                $outputName .= '<span class="pull-left text-danger"><i class="fa fa-clock-o fa-fw"></i></span>';
            }

            $outputName .= '<a href="' . admin_url('tasks/view/' . htmlspecialchars($aRow['id'])) . '" class="display-block main-tasks-table-href-name' . (!empty($aRow['rel_id']) ? ' mbot5' : '') . '" onclick="init_task_modal(' . htmlspecialchars($aRow['id']) . '); return false;">' . htmlspecialchars($aRow['name']) . '</a>';
            $outputName .= '<div class="row-options">';
            $outputName .= '</div>';

            $row[] = $outputName;

            $row[] = $aRow['milestone_name'];

            $row[] = format_members_by_ids_and_names($aRow['assignees_ids'], $aRow['assignees']);
            

            $row[] = _dt($aRow['startdate']);

            $row[] = _dt($aRow['duedate']);
            if($aRow['total_logged_time'] != ''){
                $row[] = _format_number($aRow['total_logged_time']);
            }else{
                $row[] = 0;
            }
        
            $status          = get_task_status_by_id($aRow['status']);
            $outputStatus    = '';

            $status_color = $status['color'];
            $status_name = $status['name'];
            if(($aRow['status'] != 5 && $aRow['duedate'] < date('Y-m-d')) || ($aRow['status'] === 5 && $aRow['duedate'] < $aRow['datefinished'])){
                $status_name .= '<span class="text-danger">('._l('late').')</span>';
            }
            $outputStatus .= '<span class="inline-block label" style="color:' . htmlspecialchars($status_color) . ';border:1px solid ' . htmlspecialchars($status_color) . '" task-status-table="' . htmlspecialchars($aRow['status']) . '">';

            $outputStatus .= $status_name;
            

        $outputStatus .= '</span>';

        $row[] = $outputStatus;

        

        /*$hook_data = do_action('tasks_table_row_data', [
            'output' => $row,
            'row'    => $aRow,
        ]);

        $row = $hook_data['output'];*/

        $row['DT_RowClass'] = 'has-row-options';
        if ((!empty($aRow['duedate']) && $aRow['duedate'] < date('Y-m-d')) && $aRow['status'] != 5) {
            $row['DT_RowClass'] .= ' text-danger';
        }

        $output['aaData'][] = $row;
    }

            echo json_encode($output);
            die();
        }
    }

    public function task_bookmark(){
        if ($this->input->post()){
            $data                = $this->input->post();
            $data['name'] = $data['task_bookmarks_name'];
            unset($data['task_bookmarks_name']);
            $data['creator'] = get_staff_user_id();
            if (!$this->input->post('id')) {
                $id = $this->task_bookmarks_model->add_task_bookmarks($data);
                if($id){
                    set_alert('success', _l('added_successfully', _l('task_bookmarks')));
                    redirect(admin_url('task_bookmarks'));
                }
            }else{
                $id = $data['id'];
                unset($data['id']);
                $success = $this->task_bookmarks_model->update_task_bookmarks($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('task_bookmarks')));
                }
                 redirect(admin_url('task_bookmarks'));
            }

        }
    }

    public function get_task_bookmarks($id){
        $task_bookmarks = $this->task_bookmarks_model->get_task_bookmarks($id);

        echo json_encode([
            'task_bookmarks' => $task_bookmarks
        ]);
    }

    public function delete_task_bookmarks($id = ''){
        if (!$id) {
            redirect(admin_url('task_bookmarks'));
        }
        $response = $this->task_bookmarks_model->delete_task_bookmarks($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('task_bookmarks')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('task_bookmarks')));
        }
        redirect(admin_url('task_bookmarks'));
    }

    public function view_task_bookmarks($id){
        $task_bookmarks = $this->task_bookmarks_model->get_task_bookmarks($id);
        $list_tasks = $this->task_bookmarks_model->get_task_bookmarks_list_task($id, true);
        if($task_bookmarks['creator'] == get_staff_user_id()){
            $data = [];
            $data['task_bookmarks'] = $task_bookmarks;
            $data['list_tasks'] = $list_tasks;
            $data['id'] = $id;
            $data['title'] = $task_bookmarks['name'];
            $this->load->view('task_bookmarks/view_task_bookmarks', $data);
        }else{
            access_denied('task_bookmarks');
        }
        
    }

    public function add_task_bookmarks_widget($id = ''){
        if($id != ''){
            $data['rel_id'] = $id;
            $data['rel_type'] = 'task_bookmarks';
            $data['add_from'] = get_staff_user_id();
            $success = $this->task_bookmarks_model->add_task_filter_widget($data);
        echo json_encode([
            'success' => $success,
            'message' => _l('added_successfully', _l('widget'))
        ]);
        die(); 
        }
    }

    public function remove_task_bookmarks_widget($id = ''){
        if($id != ''){
            $success = $this->task_bookmarks_model->remove_task_filter_widget($id);
            echo json_encode([
            'success' => $success,
            'message' => _l('deleted', _l('widget'))
            ]);
            die(); 
        }
        echo json_encode([
            'success' => false,
            'message' => _l('deleted', _l('widget'))
            ]);
        die();
    }

    public function change_task_bookmarks($task_bookmarks_id, $id)
    {
        if (has_permission('tasks', '', 'edit')) {
            $this->db->where('task_id', $id);
            $this->db->update('tbltask_bookmarks_detail', ['task_bookmarks_id' => $task_bookmarks_id]);

            $success = $this->db->affected_rows() > 0 ? true : false;
            // Don't do this query if the action is not performed via task single
            $taskHtml = $this->input->get('single_task') === 'true' ? $this->get_task_data($id, true) : '';
            echo json_encode([
                'success'  => $success,
                'taskHtml' => $taskHtml,
            ]);
        } else {
            echo json_encode([
                'success'  => false,
                'taskHtml' => $taskHtml,
            ]);
        }
    }

    public function add_task_bookmarks($task_bookmarks_id, $id)
    {
        if (has_permission('tasks', '', 'edit')) {
            $this->db->insert('tbltask_bookmarks_detail', ['task_bookmarks_id' => $task_bookmarks_id, 'task_id' => $id]);

            $success = $this->db->affected_rows() > 0 ? true : false;
            // Don't do this query if the action is not performed via task single
            $taskHtml = $this->input->get('single_task') === 'true' ? $this->get_task_data($id, true) : '';
            echo json_encode([
                'success'  => $success,
                'taskHtml' => $taskHtml,
            ]);
        } else {
            echo json_encode([
                'success'  => false,
                'taskHtml' => $taskHtml,
            ]);
        }
    }

    /**
     * Task ajax request modal
     * @param  mixed $taskid
     * @return mixed
     */
    public function get_task_data($taskid, $return = false)
    {
        $tasks_where = [];

        if (!has_permission('tasks', '', 'view')) {
            $tasks_where = get_tasks_where_string(false);
        }

        $task = $this->tasks_model->get($taskid, $tasks_where);

        if (!$task) {
            header('HTTP/1.0 404 Not Found');
            echo 'Task not found';
            die();
        }

        $data['checklistTemplates'] = $this->tasks_model->get_checklist_templates();
        $data['task']               = $task;
        $data['id']                 = $task->id;
        $data['staff']              = $this->staff_model->get('', ['active' => 1]);
        $data['reminders']          = $this->tasks_model->get_reminders($taskid);

        $data['staff_reminders'] = $this->tasks_model->get_staff_members_that_can_access_task($taskid);

        $data['project_deadline'] = null;
        if ($task->rel_type == 'project') {
            $data['project_deadline'] = get_project_deadline($task->rel_id);
        }

        if ($return == false) {
            $this->load->view('admin/tasks/view_task_template', $data);
        } else {
            return $this->load->view('admin/tasks/view_task_template', $data, true);
        }
    }

    public function remove_task_bookmarks($task_bookmarks_id, $id)
    {
        if (has_permission('tasks', '', 'edit')) {
            $this->db->where('task_bookmarks_id', $task_bookmarks_id);
            $this->db->where('task_id',$id);
            $this->db->delete('tbltask_bookmarks_detail');

            $success = $this->db->affected_rows() > 0 ? true : false;
            // Don't do this query if the action is not performed via task single
            $taskHtml = $this->input->get('single_task') === 'true' ? $this->get_task_data($id, true) : '';
            echo json_encode([
                'success'  => $success,
                'taskHtml' => $taskHtml,
            ]);
        } else {
            echo json_encode([
                'success'  => false,
                'taskHtml' => $taskHtml,
            ]);
        }
    }
}