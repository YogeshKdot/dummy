<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_links_model extends App_Model
{
    private $select = FALSE;
    private $sorted = FALSE;
    private $where_condition_defined = FALSE;

    public function __construct(){
        parent::__construct();
    }

    private function reset_vars(){
        $this->select = FALSE;
        $this->sorted = FALSE;
        $this->where_condition_defined = FALSE;
    }

    public function flush_cache(){
        $this->db->flush_cache();
        self::reset_vars();
    }

    public function insert($insert){
        $result = $this->db->insert(CUSTOM_LINKS_TABLE_NAME, $insert);
        self::flush_cache();
        if($result){
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function update($update, $primary_id = FALSE){
        if(!$this->where_condition_defined && !$primary_id){
            return FALSE;
        }
        if($primary_id)
            $this->db->where(CUSTOM_LINKS_TABLE_NAME.".id", $primary_id);
        $result = $this->db->update(CUSTOM_LINKS_TABLE_NAME, $update);
        self::flush_cache();
        return $result;
    }

    public function filter_added_by($user_id){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where(CUSTOM_LINKS_TABLE_NAME.".added_by", $user_id);
        $this->db->stop_cache();
    }

    public function filter_by_type(array $type){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where_in(CUSTOM_LINKS_TABLE_NAME.".main_setup", $type);
        $this->db->stop_cache();
    }

    public function filter_parent_id($id){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where(CUSTOM_LINKS_TABLE_NAME.".parent_id", $id);
        $this->db->stop_cache();
    }

    public function filter_unique_id($id){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where(CUSTOM_LINKS_TABLE_NAME.".unique_id", $id);
        $this->db->stop_cache();
    }

    public function sort_by($column, $direction = "ASC"){
        $this->sorted = TRUE;
        $this->db->order_by($column, $direction);
    }

    public function select($col){
        $this->select = TRUE;
        $this->db->select($col);
    }

    public function count_rows($flush_cache = FALSE){
        $count = $this->db->count_all_results(CUSTOM_LINKS_TABLE_NAME);
        if($flush_cache)
            self::flush_cache();
        return $count;
    }

    public function all_rows($limit = "", $start = ""){
        return self::_get_data(TRUE, $limit, $start);
    }

    public function get_detail($primary_id = FALSE){
        $limit = 1;
        if(!$this->where_condition_defined && !$primary_id)
            return FALSE;
        if($primary_id !== FALSE){
            if(is_array($primary_id)){
                $this->db->where_in(CUSTOM_LINKS_TABLE_NAME.".id", $primary_id);
                $limit = "";
            }
            else
                $this->db->where(CUSTOM_LINKS_TABLE_NAME.".id", $primary_id);
        }
        return self::_get_data(($limit === ""), $limit);
    }

    private function _get_data($all_rows = TRUE, $limit = "", $start = ""){
        if(!$this->select)
            $this->db->select([
                CUSTOM_LINKS_TABLE_NAME.".*"
            ]);
        if(!$this->sorted)
            $this->db->order_by(CUSTOM_LINKS_TABLE_NAME.".position", "ASC");
        $result = $this->db->get(CUSTOM_LINKS_TABLE_NAME, $limit, $start);
        self::flush_cache();
        if($all_rows)
            return $result->result_array();
        else if($result->num_rows() > 0) {
            return $result->row_array();
        }
        else
            return FALSE;
    }

    public function delete($primary_id = FALSE){
        if(!$this->where_condition_defined && !$primary_id){
            return FALSE;
        }
        if($primary_id){
            if(is_array($primary_id))
                $this->db->where_in("id", $primary_id);
            else
                $this->db->where("id", $primary_id);
        }
        $result = $this->db->delete(CUSTOM_LINKS_TABLE_NAME);
        self::flush_cache();
        return $result;
    }

    public function get_main_menu_links(){
        if(has_permission('custom_links', '', 'view_own')){
            $staff_id = get_staff_user_id();
            $this->Custom_links_model->filter_added_by($staff_id);
        }
        $this->Custom_links_model->filter_by_type(["0"]);
        $this->Custom_links_model->sort_by(CUSTOM_LINKS_TABLE_NAME.".parent_id");
        $this->Custom_links_model->sort_by(CUSTOM_LINKS_TABLE_NAME.".position");
        $_main_links = $this->Custom_links_model->all_rows();
        $main_links =[];
        foreach ($_main_links as $link){
            if(!empty($link['parent_id']) && isset($data['main_links'][$link['parent_id']])){
                $main_links[$link['parent_id']]['children'][] = $link;
            }
            else{
                $main_links[$link['unique_id']] = $link;
            }
        }
        return $main_links;
    }

    public function get_setup_menu_links(){
        if(has_permission('custom_links', '', 'view_own')){
            $staff_id = get_staff_user_id();
            $this->Custom_links_model->filter_added_by($staff_id);
        }
        $this->Custom_links_model->filter_by_type(["1"]);
        $this->Custom_links_model->sort_by(CUSTOM_LINKS_TABLE_NAME.".parent_id");
        $this->Custom_links_model->sort_by(CUSTOM_LINKS_TABLE_NAME.".position");
        $_setup_links = $this->Custom_links_model->all_rows();
        $setup_links = [];
        foreach ($_setup_links as $link){
            if(!empty($link['parent_id']) && isset($data['setup_links'][$link['parent_id']])){
                $setup_links[$link['parent_id']]['children'][] = $link;
            }
            else{
                $setup_links[$link['unique_id']] = $link;
            }
        }
        return $setup_links;
    }

    public function get_clients_menu_links(){
        if(has_permission('custom_links', '', 'view_own')){
            $staff_id = get_staff_user_id();
            $this->Custom_links_model->filter_added_by($staff_id);
        }
        $this->Custom_links_model->filter_by_type(["2"]);
        $this->Custom_links_model->sort_by(CUSTOM_LINKS_TABLE_NAME.".parent_id");
        $this->Custom_links_model->sort_by(CUSTOM_LINKS_TABLE_NAME.".position");
        $client_links = $this->Custom_links_model->all_rows();
        return $client_links;
    }

    public function get_staff_to_filter($link){
        $data['staff_ajax'] = false;
        $data['staff'] = [];
        if(total_rows(db_prefix().'staff', ['active' => "1"]) > 1){
            $data['staff_ajax'] = true;
            if(isset($link) && !empty($link['users'])){
                $users = explode(",", $link['users']);
                $data['staff'] = $this->db->select(['staffid', 'firstname', 'lastname'])->where_in("staffid", $users)->get(db_prefix()."staff")->result_array();
            }
        }
        else{
            $this->load->model("Staff_model");
            $data['staff'] = $this->db->select(['staffid', 'firstname', 'lastname'])->get(db_prefix()."staff")->result_array();
        }
        return $data;
    }

    public function get_clients_to_filter($link){
        $data['client_ajax'] = false;
        $data['clients'] = [];
        if(total_rows(db_prefix().'clients', ['active' => "1"]) > 10){
            $data['client_ajax'] = true;
            if(isset($link) && !empty($link['clients'])){
                $clients = explode(",", $link['clients']);
                $data['clients'] = $this->db->select(['userid', get_sql_select_client_company()])->where_in("userid", $clients)->get(db_prefix()."clients")->result_array();
            }
        }
        else{
            $data['clients'] = $this->db->select(['userid', get_sql_select_client_company()])->get(db_prefix()."clients")->result_array();
        }
        return $data;
    }
}
