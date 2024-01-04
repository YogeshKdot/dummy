<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_links extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // IF MODULE DISABLED THEN SHOW 404
        if(!defined('CUSTOM_LINKS_MODULE_NAME'))
            show_404();
        $this->load->model("Custom_links_model");
        $this->load->helper("security");
        $this->load->library("form_validation");

// ADDING SCRIPT FILES
        hooks()->add_action('before_compile_scripts_assets', 'add_custom_links_scripts');
// ADDING CSS FILES
        hooks()->add_action('before_compile_css_assets', 'add_custom_links_css');
    }

    public function index(){
        $this->link();
    }

    public function link($id = ''){
        if (! (has_permission('custom_links', '', 'view') || has_permission('custom_links', '', 'view_own') || has_permission('custom_links', '', 'create') || has_permission('custom_links', '', 'edit'))) {
            access_denied('custom_links');
        }
        $link = null;
        if(!empty($id)){
            if (! has_permission('custom_links', '', 'edit')) {
                access_denied('custom_links');
            }
            $link = $this->Custom_links_model->get_detail($id);
            if(!$link)
                show_404();

            $data['link'] = $link;
        }
        $staff_id = get_staff_user_id();

        if(!empty($this->input->post())) {
            $this->form_validation->set_rules("main_setup", _l('mcl_select_menu'), 'trim|required');
            $this->form_validation->set_rules("title", _l('mcl_link_title'), 'trim|required');
            $this->form_validation->set_rules("href", _l('mcl_link'), 'trim|required');
            if (!$this->form_validation->run()) {
                set_alert("danger", _l("mcl_validation_error"));
                redirect(admin_url("custom_links"));
            }
            $insert['main_setup'] = intval($this->input->post('main_setup'));
            $insert['parent_id'] = empty($this->input->post('parent_id')) ? "" : $this->input->post('parent_id');
            $insert['title'] = $this->input->post('title');
            $insert['href'] = $this->input->post('href');
            $insert['position'] = $this->input->post('position');
            $insert['icon'] = $this->input->post('icon');
            $insert['badge'] = $this->input->post('badge');
            $insert['badge_color'] = $this->input->post('badge_color');
            $insert['external_internal'] = $this->input->post('external_internal') > 2 ? 0 : $this->input->post('external_internal');
            $insert['http_protocol'] = $this->input->post('http_protocol') == 1 ? 1 : 0;
            $insert['show_in'] = $this->input->post('show_in') > 2 ? 0 : $this->input->post('show_in');
            $insert['require_login'] = $this->input->post('require_login') == 1 ? 1 : 0;
            $roles = $this->input->post('roles');
            $insert['roles'] = NULL;
            if(is_array($roles) && count($roles) > 0){
                $insert['roles'] = implode(",", $roles);
            }
            $users = $this->input->post('users');
            $insert['users'] = NULL;
            if(is_array($users) && count($users) > 0){
                $insert['users'] = implode(",", $users);
            }
            $clients = $this->input->post('clients');
            $insert['clients'] = NULL;
            if(is_array($clients) && count($clients) > 0){
                $insert['clients'] = implode(",", $clients);
            }
            if(isset($link)){
                $inserted = $this->Custom_links_model->update($insert, $id);
                $message = _l("mcl_link_updated_msg");
            }
            else{
                if(!has_permission('custom_links', '', 'create')){
                    access_denied('custom_links');
                }
                $insert['added_at'] = date("Y-m-d H:i:s");
                $insert['added_by'] = $staff_id;
                $inserted = $this->Custom_links_model->insert($insert);
                $update['unique_id'] = custom_links_slug($this->input->post('title')).$inserted;
                $inserted = $this->Custom_links_model->update($update, $inserted);
                $message = _l("mcl_link_added_msg");
            }
            if(!empty($insert['parent_id'])){
                $update_parent['href'] = "#";
                $update_parent['show_in'] = "0";
                $update_parent['external_internal'] = "2";
                $this->Custom_links_model->filter_unique_id($insert['parent_id']);
                $updated = $this->Custom_links_model->update($update_parent);
            }
            if ($inserted) {
                set_alert("success", $message);
                if(isset($link))
                    redirect(admin_url("custom_links/link/".$id));
                else
                    redirect(admin_url("custom_links"));
            } else {
                set_alert("warning", _l("mcl_link_failed_msg"));
            }
        }

        $data['main_menu_items'] = $this->app_menu->get_sidebar_menu_items();

        $data['setup_menu_items'] = $this->app_menu->get_setup_menu_items();

        $data['main_links'] = $this->Custom_links_model->get_main_menu_links();

        $data['setup_links'] = $this->Custom_links_model->get_setup_menu_links();

        $data['client_links'] = $this->Custom_links_model->get_clients_menu_links();

        $this->load->model("Roles_model");
        $data['staff_roles'] = $this->Roles_model->get();

        $staffs = $this->Custom_links_model->get_staff_to_filter($link);
        $data['staff_ajax'] = $staffs['staff_ajax'];
        $data['staff'] = $staffs['staff'];

        $clients = $this->Custom_links_model->get_clients_to_filter($link);
        $data['client_ajax'] = $clients['client_ajax'];
        $data['clients'] = $clients['clients'];

        $data['title'] = _l('mcl_custom_links');
        $this->load->view('index', $data);
    }

    public function delete($id){
        if (! has_permission('custom_links', '', 'delete')) {
            access_denied('custom_links');
        }
        $link = $this->Custom_links_model->get_detail($id);
        if(!$link)
            show_404();

        $deleted = $this->Custom_links_model->delete($id);
        if ($deleted) {
            set_alert("success", _l('mcl_link_deleted_msg'));
        } else {
            set_alert("warning", _l("mcl_link_delete_failed_msg"));
        }
        redirect(admin_url("custom_links"));
    }

    public function iframe($id){
        $this->Custom_links_model->filter_by_type([0, 1]);
        $link = $this->Custom_links_model->get_detail($id);
        if(!$link){
            show_404();
        }

        if($link['external_internal'] == "0"){
            $href = base_url($link['href']);
        }
        else{
            if($link['http_protocol'] == "0"){
                $href = 'http://'.$link['href'];
            }
            else{
                $href = 'https://'.$link['href'];
            }
        }

        $data['href'] = $href;
        $data['link'] = $link;
        $data['title'] = _l('mcl_custom_links')." - ".$link['title'];
        $this->load->view('iframe', $data);
    }

    public function filter_staff($id = '')
    {
        if (!(has_permission('custom_links', '', 'view') || has_permission('custom_links', '', 'view_own') || has_permission('custom_links', '', 'create') || has_permission('custom_links', '', 'edit'))) {
            access_denied('custom_links');
        }

        if ($this->input->post()) {
            $type = $this->input->post('type');
            if ($this->input->post('rel_id')) {
                $rel_id = $this->input->post('rel_id');
            } else {
                $rel_id = '';
            }
            $q = $this->input->post('q');
            $roles = $this->input->post('roles');

            if(!empty(trim($roles))){
                $this->db->where_in("role", explode(",", $roles));
            }

            if ($rel_id != '') {
                $this->load->model('staff_model');
                $data = $this->staff_model->get($rel_id);
            } else {
                $result = [];
                if (has_permission('staff', '', 'view')) {
                    // Staff
                    $this->db->select();
                    $this->db->from(db_prefix() . 'staff');
                    $this->db->group_start();
                    $this->db->like('firstname', $q);
                    $this->db->or_like('lastname', $q);
                    $this->db->or_like("CONCAT(firstname, ' ', lastname)", $q, false);
                    $this->db->or_like("CONCAT(lastname, ' ', firstname)", $q, false);
                    $this->db->or_like('phonenumber', $q);
                    $this->db->or_like('email', $q);
                    $this->db->group_end();
                    $this->db->order_by('firstname', 'ASC');
                    $result = $this->db->get()->result_array();
                }
                $data = $result;
            }

            $relOptions = init_relation_options($data, $type, $rel_id);
            echo json_encode($relOptions);
            die;
        }
    }
}
