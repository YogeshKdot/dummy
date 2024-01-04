<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Task_bookmarks_model extends App_Model
{
	public function __construct()
    {
        parent::__construct();
    }

    public function add_task_bookmarks($data){
        
        $this->db->insert('tbltask_bookmarks', $data);
        $task_bookmarks_id = $this->db->insert_id();

        if($task_bookmarks_id){
            return $task_bookmarks_id;
        }
        return false;
    }

    public function update_task_bookmarks($data, $id){
        
        $this->db->where('id', $id);
        $this->db->update('tbltask_bookmarks', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_task_bookmarks($id = ''){
        if($id != ''){
            $this->db->where('id',$id);
            $task_bookmarks = $this->db->get('tbltask_bookmarks')->result_array();
            $task_bookmarks[0]['list_task'] = $this->get_task_bookmarks_list_task($id);
            return $task_bookmarks[0];
        }else{
            $this->db->where('creator',get_staff_user_id());
            $task_bookmarks = $this->db->get('tbltask_bookmarks')->result_array();
            return $task_bookmarks;
        }
        
    }

    public function get_task_bookmarks_by_task_id($taskid){

        $this->db->where('task_id',$taskid);
        $task_bookmarks = $this->db->get('tbltask_bookmarks_detail')->result_array();
        
        if(count($task_bookmarks) > 0){
            $value_return = [];
            foreach ($task_bookmarks as $value) {
                $value_return[] = $this->get_task_bookmarks($value['task_bookmarks_id']);
            }
          return $value_return;
        }
        return false;
    }

    public function get_task_bookmarks_list_task($id, $is_string = false){
        $data = [];
        $this->db->where('task_bookmarks_id',$id);
        $list_task = $this->db->get('tbltask_bookmarks_detail')->result_array();

        foreach($list_task as $row){
            $data[] = $row['task_id'];
        }
        if($is_string == true){
            $data = implode(',', $data);
        }
        return $data;
    }
    
    public function delete_task_bookmarks($id){
        $this->db->where('task_bookmarks_id', $id);
        $this->db->delete('tbltask_bookmarks_detail');
        $this->db->where('id', $id);
        $this->db->delete('tbltask_bookmarks');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function add_task_filter_widget($data){
        $this->db->insert('tbllist_widget', $data);
        $filter_id = $this->db->insert_id();
        return $filter_id;
    }

    public function remove_task_filter_widget($id){
        $this->db->where('id', $id);
        $this->db->delete('tbllist_widget');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_filter_widget($staff, $type = ''){
        return $this->db->query('select * from tbllist_widget where add_from = '.htmlspecialchars($staff).' and rel_type = "'.htmlspecialchars($type).'"')->result_array();
    }

    public function view_task_bookmarks_helper($id){
        $task_bookmarks = $this->get_task_bookmarks($id);
        $list_tasks = $this->get_task_bookmarks_list_task($id, true);
        
        $data = [];
        $data['task_bookmarks'] = $task_bookmarks;
        $data['list_tasks'] = $list_tasks;
        $data['id'] = $id;
        $data['title'] = $task_bookmarks['name'];
        
        return $data;
    }
}