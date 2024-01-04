<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Twilio\Rest\Client;
use Clickatell\ClickatellException;
use modules\mfa\helpers\Rest;

/**
 * Marketing Automation model
 */
class Ma_model extends App_Model
{
	public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new category
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_category($data)
    {
        $data['description'] = nl2br($data['description']);
        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'ma_categories', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * update new category
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_category($data, $id)
    {
        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_categories', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete category from database, if used return array with key referenced
     */
    public function delete_category($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_categories');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get category
     * @param  mixed $id category id (Optional)
     * @return mixed     object or array
     */
    public function get_category($id = '', $type = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'ma_categories')->row();
        }

        if ($type != '') {
            $this->db->where('type', $type);
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_categories')->result_array();
    }

    /**
     * Add new stage
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_stage($data)
    {
        $data['description'] = nl2br($data['description']);
        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'ma_stages', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * update new stage
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_stage($data, $id)
    {
        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_stages', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return boolean
     * Delete stage from database
     */
    public function delete_stage($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_stages');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('stage_id', $id);
            $this->db->delete(db_prefix() . 'ma_lead_stages');

            return true;
        }

        return false;
    }

    /**
     * Get stage
     * @param  mixed $id stage id (Optional)
     * @return mixed     object or array
     */
    public function get_stage($id = '', $where = [], $count = false, $is_kanban = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $stage = $this->db->get(db_prefix() . 'ma_stages')->row();

            return $stage;
        }

        $this->db->where($where);

        if($is_kanban == false){
            $this->db->where('published', 1);
        }
        $this->db->order_by('name', 'asc');

        if($count == true){
            return $this->db->count_all_results(db_prefix() . 'ma_stages');
        }else{
            return $this->db->get(db_prefix() . 'ma_stages')->result_array();
        }
    }

    /**
     * Add new segment
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_segment($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        if (isset($data['type'])) {
            $type = $data['type'];
            unset($data['type']);
        }

        if (isset($data['sub_type_1'])) {
            $sub_type_1 = $data['sub_type_1'];
            unset($data['sub_type_1']);
        }

        if (isset($data['sub_type_2'])) {
            $sub_type_2 = $data['sub_type_2'];
            unset($data['sub_type_2']);
        }

        if (isset($data['value'])) {
            $value = $data['value'];
            unset($data['value']);
        }

        $data['description'] = nl2br($data['description']);
        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'ma_segments', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if($type){
                foreach($type as $k => $t){
                    $node = [];
                    $node['segment_id'] = $insert_id;
                    $node['type'] = $t;
                    $node['sub_type_1'] = $sub_type_1[$k];
                    $node['sub_type_2'] = $sub_type_2[$k];
                    $node['value'] = $value[$k];

                    $this->db->insert(db_prefix() . 'ma_segment_filters', $node);
                }
            }

            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get segment
     * @param  mixed $id segment id (Optional)
     * @return mixed     object or array
     */
    public function get_segment($id = '', $where = [], $count = false, $is_kanban = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $segment = $this->db->get(db_prefix() . 'ma_segments')->row();

            if($segment){
                $this->db->where('segment_id', $id);
                $segment->filters = $this->db->get(db_prefix() . 'ma_segment_filters')->result_array();
            }

            return $segment;
        }

        $this->db->where($where);

        if($is_kanban == false){
            $this->db->where('published', 1);
        }
        $this->db->order_by('name', 'asc');

        if($count == true){
            return $this->db->count_all_results(db_prefix() . 'ma_segments');
        }else{
            return $this->db->get(db_prefix() . 'ma_segments')->result_array();
        }
    }

    /**
     * Add new segment
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_segment($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        if (isset($data['type'])) {
            $type = $data['type'];
            unset($data['type']);
        }

        if (isset($data['sub_type_1'])) {
            $sub_type_1 = $data['sub_type_1'];
            unset($data['sub_type_1']);
        }

        if (isset($data['sub_type_2'])) {
            $sub_type_2 = $data['sub_type_2'];
            unset($data['sub_type_2']);
        }

        if (isset($data['value'])) {
            $value = $data['value'];
            unset($data['value']);
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_segments', $data);

        $this->db->where('segment_id', $id);
        $this->db->delete(db_prefix() . 'ma_segment_filters');

        if($type){
            foreach($type as $k => $t){
                $node = [];
                $node['segment_id'] = $id;
                $node['type'] = $t;
                $node['sub_type_1'] = $sub_type_1[$k];
                $node['sub_type_2'] = $sub_type_2[$k];
                $node['value'] = $value[$k];

                $this->db->insert(db_prefix() . 'ma_segment_filters', $node);
            }
        }

        return true;
    }

    /**
     * delete segment
     * @param  integer ID
     * @return mixed
     */
    public function delete_segment($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_segments');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('segment_id', $id);
            $this->db->delete(db_prefix() . 'ma_segment_filters');

            $this->db->where('segment_id', $id);
            $this->db->delete(db_prefix() . 'ma_lead_segments');

            return true;
        }

        return false;
    }

    /**
     * add form
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_form($data)
    {
        $data                       = $this->_do_lead_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);
        $data['form_key']           = app_generate_hash();

        $data['create_task_on_duplicate'] = (int) isset($data['create_task_on_duplicate']);
        $data['mark_public']              = (int) isset($data['mark_public']);

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate']           = 1;
            $data['track_duplicate_field']     = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate']  = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        $data['dateadded'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'ma_forms', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {

            return $insert_id;
        }

        return false;
    }

    /**
     * update form
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_form($id, $data)
    {
        $data                       = $this->_do_lead_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);

        $data['create_task_on_duplicate'] = (int) isset($data['create_task_on_duplicate']);
        $data['mark_public']              = (int) isset($data['mark_public']);

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate']           = 1;
            $data['track_duplicate_field']     = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate']  = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_forms', $data);

        return ($this->db->affected_rows() > 0 ? true : false);
    }

    /**
     * @param  integer ID
     * @return boolean
     * Delete stage from database
     */
    public function delete_form($id)
    {   
        $affected_rows = 0;
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_forms');
        if ($this->db->affected_rows() > 0) {
            $affected_rows++;
        }

        $this->db->where('from_ma_form_id', $id);
        $this->db->update(db_prefix() . 'leads', [
            'from_ma_form_id' => 0,
        ]);

        if ($this->db->affected_rows() > 0) {
            $affected_rows++;
        }

        if ($affected_rows > 0) {
            return true;
        }

        return false;
    }

    /**
     *  do lead form responsibles
     * @param  array
     * @return array
     */
    private function _do_lead_form_responsibles($data)
    {
        if (isset($data['notify_lead_imported'])) {
            $data['notify_lead_imported'] = 1;
        } else {
            $data['notify_lead_imported'] = 0;
        }

        if ($data['responsible'] == '') {
            $data['responsible'] = 0;
        }
        if ($data['notify_lead_imported'] != 0) {
            if ($data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids']  = serialize([]);
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        return $data;
    }

    /**
     * get form
     * @param  array or String
     * @return object
     */
    public function get_form($where)
    {
        $this->db->where($where);

        return $this->db->get(db_prefix() . 'ma_forms')->row();
    }

    /**
     * get forms
     * @param  array
     * @return array
     */
    public function get_forms($where = [])
    {
        $this->db->where($where);

        return $this->db->get(db_prefix() . 'ma_forms')->result_array();
    }

    /**
     * Add new asset
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_asset($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'ma_assets', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get asset
     * @param  mixed $id asset id (Optional)
     * @return mixed     object or array
     */
    public function get_asset($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $asset = $this->db->get(db_prefix() . 'ma_assets')->row();

            if($asset){
                $asset->attachment            = '';
                $asset->filetype              = '';
                $asset->attachment_added_from = 0;

                $this->db->where('rel_id', $id);
                $this->db->where('rel_type', 'ma_asset');
                $file = $this->db->get(db_prefix() . 'files')->row();

                if ($file) {
                    $asset->attachment            = $file->file_name;
                    $asset->filetype              = $file->filetype;
                    $asset->attachment_added_from = $file->staffid;
                }

            }

            return $asset;
        }
        $this->db->where('published', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_assets')->result_array();
    }

    /**
     * Add new asset
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_asset($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_assets', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete asset from database, if used return array with key referenced
     */
    public function delete_asset($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_assets');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('asset_id', $id);
            $this->db->delete(db_prefix() . 'ma_asset_download_logs');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'ma_asset');
            $this->db->delete(db_prefix().'files');

            if (is_dir(MA_MODULE_UPLOAD_FOLDER .'/assets/'. $id)) {
                delete_dir(MA_MODULE_UPLOAD_FOLDER .'/assets/'. $id);
            }

            return true;
        }

        return false;
    }

    /**
     * Add new point_action
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_point_action($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'ma_point_actions', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get point_action
     * @param  mixed $id point_action id (Optional)
     * @return mixed     object or array
     */
    public function get_point_action($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $point_action = $this->db->get(db_prefix() . 'ma_point_actions')->row();

            return $point_action;
        }

        $this->db->where('published', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_point_actions')->result_array();
    }

    /**
     * Add new point_action
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_point_action($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_point_actions', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete point_action from database, if used return array with key referenced
     */
    public function delete_point_action($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_point_actions');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Add new point_trigger
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_point_trigger($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'ma_point_triggers', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get point_trigger
     * @param  mixed $id point_trigger id (Optional)
     * @return mixed     object or array
     */
    public function get_point_trigger($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $point_trigger = $this->db->get(db_prefix() . 'ma_point_triggers')->row();

            return $point_trigger;
        }
        
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_point_triggers')->result_array();
    }

    /**
     * Add new point_trigger
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_point_trigger($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_point_triggers', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete point_trigger from database, if used return array with key referenced
     */
    public function delete_point_trigger($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_point_triggers');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Add new marketing_message
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_marketing_message($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'ma_marketing_messages', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get marketing_message
     * @param  mixed $id marketing_message id (Optional)
     * @return mixed     object or array
     */
    public function get_marketing_message($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $marketing_message = $this->db->get(db_prefix() . 'ma_marketing_messages')->row();

            return $marketing_message;
        }
        
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_marketing_messages')->result_array();
    }

    /**
     * Add new marketing_message
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_marketing_message($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_marketing_messages', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete marketing_message from database, if used return array with key referenced
     */
    public function delete_marketing_message($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_marketing_messages');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Add new email
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_email($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        if ($data['email_template'] != '') {
            $email_template = $this->get_email_template($data['email_template']);

            $data['data_design'] = $email_template->data_design;
            $data['data_html'] = $email_template->data_html;
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'ma_emails', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get email
     * @param  mixed $id email id (Optional)
     * @return mixed     object or array
     */
    public function get_email($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $email = $this->db->get(db_prefix() . 'ma_emails')->row();

            return $email;
        }
        
        $this->db->where('published', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_emails')->result_array();
    }

    /**
     * Add new email
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_email($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        if ($data['email_template'] != '') {
            $email = $this->get_email($id);

            if($email->email_template != $data['email_template']){
                $email_template = $this->get_email_template($data['email_template']);

                $data['data_design'] = $email_template->data_design;
                $data['data_html'] = $email_template->data_html;
            }
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_emails', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete email from database
     */
    public function delete_email($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_emails');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('email_id', $id);
            $this->db->delete(db_prefix() . 'ma_email_logs');

            return true;
        }

        return false;
    }

    /**
     * Add new text_message
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_text_message($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'ma_text_messages', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get text_message
     * @param  mixed $id text_message id (Optional)
     * @return mixed     object or array
     */
    public function get_text_message($id = '')
    {
        if (is_numeric($id)) {

            $this->db->where('id', $id);
            $text_message = $this->db->get(db_prefix() . 'ma_text_messages')->row();

            return $text_message;
        }
        
        $this->db->where('published', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_text_messages')->result_array();
    }

    /**
     * Add new text_message
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_text_message($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_text_messages', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete text_message from database
     */
    public function delete_text_message($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_text_messages');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Change segment published
     * @param  mixed $id     segment id
     * @param  mixed $status status(0/1)
     */
    public function change_segment_published($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_segments', [
            'published' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function get_data_segment_pie_chart()
    {
        $where = '';

        $categories = $this->get_category('', 'segment');
        $categoryIds = [];

        $where = '';
        foreach ($categories as $category) {
            if ($this->input->post('segment_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        $data_chart = [];
        foreach($categories as $category){
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }

            $this->db->where('category', $category['id']);
            $segment = $this->db->count_all_results(db_prefix().'ma_segments');

            $data_chart[] = ['name' => $category['name'], 'y' => $segment, 'color' => $category['color']];
        }

        return $data_chart;
    }

    /**
     * @return array
     */
    public function get_data_segment_column_chart()
    {
        $categoryIds = [];

        $categories = $this->get_category('', 'segment');
        $categoryIds = [];
        $where = '';
        foreach ($categories as $category) {
            if ($this->input->post('segment_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        if (count($categoryIds) > 0) {
            $where = 'category IN (' . implode(', ', $categoryIds) . ')';
        }

        $header = [];
        $data_chart = [];
        foreach($categories as $category){
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }

            $header[] = $category['name'];

            $this->db->where('category', $category['id']);
            $segment = $this->db->count_all_results(db_prefix().'ma_segments');

            $data_chart[] = ['name' => $category['name'], 'y' => $segment, 'color' => $category['color']];
        }

        return ['header' => $header, 'data' => $data_chart];
    }

    /**
     * Does a segment kanban query.
     *
     * @param      int   $staff_id  The staff identifier
     * @param      integer  $page      The page
     * @param      array    $where     The where
     * @param      boolean  $count     The count
     *
     * @return     object
     */
    public function do_segment_kanban_query($category, $page = 1, $where = [], $count = false)
    {
        if ($count == false) {
            if ($page > 1) {
                $page--;
                $position = ($page * 10);
                $this->db->limit(10, $position);
            } else {
                $this->db->limit(10);
            }
        }

        return $this->get_segment('', $where, $count, true);
    }

    /**
     * update segment category
     *
     * @param      object  $data   The data
     */
    public function update_segment_category($data)
    {
        $this->db->where('id', $data['segment_id']);
        $this->db->update(db_prefix() . 'ma_segments', ['category' => $data['category']]);
    }

    /**
     * Add new campaign
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_campaign($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $data['start_date'] = to_sql_date($data['start_date']);
        $data['end_date'] = to_sql_date($data['end_date']);

        $this->db->insert(db_prefix() . 'ma_campaigns', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * Add new campaign
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_campaign($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['description'] = nl2br($data['description']);
        $data['start_date'] = to_sql_date($data['start_date']);
        $data['end_date'] = to_sql_date($data['end_date']);
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_campaigns', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get campaign
     * @param  mixed $id campaign id (Optional)
     * @return mixed     object or array
     */
    public function get_campaign($id = '', $where = [], $count = false, $is_kanban = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $campaign = $this->db->get(db_prefix() . 'ma_campaigns')->row();

            return $campaign;
        }

        $this->db->where($where);
        if($is_kanban == false){
            $this->db->where('published', 1);
        }
        $this->db->order_by('name', 'asc');

        if($count == true){
            return $this->db->count_all_results(db_prefix() . 'ma_campaigns');
        }else{
            return $this->db->get(db_prefix() . 'ma_campaigns')->result_array();
        }
    }
    /**
     * @param  array
     * @return boolean
     */
    public function workflow_builder_save($data){
        if(isset($data['campaign_id']) && $data['campaign_id'] != ''){
            $this->db->where('id', $data['campaign_id']);
            $this->db->update(db_prefix() . 'ma_campaigns', ['workflow' => json_encode($data['workflow'])]);

            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }

        return false;
    }


    /**
     * Change campaign published
     * @param  mixed $id     campaign id
     * @param  mixed $status status(0/1)
     */
    public function change_campaign_published($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_campaigns', [
            'published' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function get_data_campaign_pie_chart()
    {
        $where = '';

        $categories = $this->get_category('', 'campaign');
        $categoryIds = [];

        $where = '';
        foreach ($categories as $category) {
            if ($this->input->post('campaign_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        $data_chart = [];
        foreach($categories as $category){
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }

            $this->db->where('category', $category['id']);
            $campaign = $this->db->count_all_results(db_prefix().'ma_campaigns');

            $data_chart[] = ['name' => $category['name'], 'y' => $campaign, 'color' => $category['color']];
        }

        return $data_chart;
    }

    /**
     * @return array
     */
    public function get_data_campaign_column_chart()
    {
        $categoryIds = [];

        $categories = $this->get_category('', 'campaign');
        $categoryIds = [];
        $where = '';
        foreach ($categories as $category) {
            if ($this->input->post('campaign_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        if (count($categoryIds) > 0) {
            $where = 'category IN (' . implode(', ', $categoryIds) . ')';
        }

        $header = [];
        $data_chart = [];
        foreach($categories as $category){
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }

            $header[] = $category['name'];

            $this->db->where('category', $category['id']);
            $campaign = $this->db->count_all_results(db_prefix().'ma_campaigns');

            $data_chart[] = ['name' => $category['name'], 'y' => $campaign, 'color' => $category['color']];
        }

        return ['header' => $header, 'data' => $data_chart];
    }

    /**
     * Does a campaign kanban query.
     *
     * @param      int   $staff_id  The staff identifier
     * @param      integer  $page      The page
     * @param      array    $where     The where
     * @param      boolean  $count     The count
     *
     * @return     object
     */
    public function do_campaign_kanban_query($category, $page = 1, $where = [], $count = false)
    {
        if ($count == false) {
            if ($page > 1) {
                $page--;
                $position = ($page * 10);
                $this->db->limit(10, $position);
            } else {
                $this->db->limit(10);
            }
        }

        return $this->get_campaign('', $where, $count, true);
    }

    /**
     * update campaign category
     *
     * @param      object  $data   The data
     */
    public function update_campaign_category($data)
    {
        $this->db->where('id', $data['campaign_id']);
        $this->db->update(db_prefix() . 'ma_campaigns', ['category' => $data['category']]);
    }

    /**
     * Change stage published
     * @param  mixed $id     stage id
     * @param  mixed $status status(0/1)
     */
    public function change_stage_published($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_stages', [
            'published' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function get_data_stage_pie_chart()
    {
        $where = '';

        $categories = $this->get_category('', 'stage');
        $categoryIds = [];

        $where = '';
        foreach ($categories as $category) {
            if ($this->input->post('stage_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        $data_chart = [];
        foreach($categories as $category){
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }

            $this->db->where('category', $category['id']);
            $stage = $this->db->count_all_results(db_prefix().'ma_stages');

            $data_chart[] = ['name' => $category['name'], 'y' => $stage, 'color' => $category['color']];
        }

        return $data_chart;
    }

    /**
     * @return array
     */
    public function get_data_stage_column_chart()
    {
        $categoryIds = [];

        $categories = $this->get_category('', 'stage');
        $categoryIds = [];
        $where = '';
        foreach ($categories as $category) {
            if ($this->input->post('stage_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        if (count($categoryIds) > 0) {
            $where = 'category IN (' . implode(', ', $categoryIds) . ')';
        }

        $header = [];
        $data_chart = [];
        foreach($categories as $category){
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }

            $header[] = $category['name'];

            $this->db->where('category', $category['id']);
            $stage = $this->db->count_all_results(db_prefix().'ma_stages');

            $data_chart[] = ['name' => $category['name'], 'y' => $stage, 'color' => $category['color']];
        }

        return ['header' => $header, 'data' => $data_chart];
    }

    /**
     * Does a stage kanban query.
     *
     * @param      int   $staff_id  The staff identifier
     * @param      integer  $page      The page
     * @param      array    $where     The where
     * @param      boolean  $count     The count
     *
     * @return     object
     */
    public function do_stage_kanban_query($category, $page = 1, $where = [], $count = false)
    {
        if ($count == false) {
            if ($page > 1) {
                $page--;
                $position = ($page * 10);
                $this->db->limit(10, $position);
            } else {
                $this->db->limit(10);
            }
        }

        return $this->get_stage('', $where, $count, true);
    }

    /**
     * update stage category
     *
     * @param      object  $data   The data
     */
    public function update_stage_category($data)
    {
        $this->db->where('id', $data['stage_id']);
        $this->db->update(db_prefix() . 'ma_stages', ['category' => $data['category']]);
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete category from database
     */
    public function delete_campaign($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_campaigns');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * email template design save
     * @param  array
     * @return boolean
     */
    public function email_template_design_save($data){
        if(isset($data['email_template_id']) && $data['email_template_id'] != ''){
            $this->db->where('id', $data['email_template_id']);
            $this->db->update(db_prefix() . 'ma_email_templates', ['data_html' => json_encode($data['data_html']), 'data_design' => json_encode($data['data_design'])]);

            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add new email_template
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_email_template($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'ma_email_templates', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get email_template
     * @param  mixed $id email_template id (Optional)
     * @return mixed     object or array
     */
    public function get_email_template($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $email_template = $this->db->get(db_prefix() . 'ma_email_templates')->row();

            return $email_template;
        }
        
        $this->db->where('published', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_email_templates')->result_array();
    }

    /**
     * Add new email_template
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_email_template($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_email_templates', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete email template from database
     */
    public function delete_email_template($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_email_templates');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @param  string
     * @return array or boolean
     */
    public function get_lead_by_segment($id, $return_type = 'leads'){
        $segment = $this->get_segment($id);

        $where = '';
        if($segment){
            foreach ($segment->filters as $filter) {
                if($where != ''){
                    $where .= ' '. strtoupper($filter['sub_type_1']).' ';
                }

                if($filter['type'] == 'tag'){
                    $where .= db_prefix() . 'taggables.rel_type = "lead" AND ';
                }

                switch ($filter['sub_type_2']) {
                    case 'equals':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name = '.$this->db->escape($filter['value']) : db_prefix().'leads.'.$filter['type'].' = "'.$filter['value'].'"';
                        break;
                    case 'not_equal':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name != ' . $this->db->escape($filter['value']) : db_prefix().'leads.'.$filter['type'].' != "'.$filter['value'].'"'; 
                        break;
                    case 'greater_than':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name > ' . $this->db->escape($filter['value']) : db_prefix().'leads.'.$filter['type'].' > "'.$filter['value'].'"';
                        break;
                    case 'greater_than_or_equal':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name >= ' . $this->db->escape($filter['value']) : db_prefix().'leads.'.$filter['type'].' >= "'.$filter['value'].'"';
                        break;
                    case 'less_than':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name < ' . $this->db->escape($filter['value']) : db_prefix().'leads.'.$filter['type'].' < "'.$filter['value'].'"';
                        break;
                    case 'less_than_or_equal':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name <= ' . $this->db->escape($filter['value']) : db_prefix().'leads.'.$filter['type'].' <= "'.$filter['value'].'"';
                        break;
                    case 'empty':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name = ""' : db_prefix().'leads.'.$filter['type'].' = ""';
                        break;
                    case 'not_empty':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name != ""' : db_prefix().'leads.'.$filter['type'].' != ""';
                        break;
                    case 'like':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name LIKE "%'.$this->db->escape($filter['value']).'%"' : db_prefix().'leads.'.$filter['type'].' LIKE "%'.$filter['value'].'%"';
                        break;
                    case 'not_like':
                        $where .= ($filter['type'] == 'tag') ? db_prefix() . 'tags.name NOT LIKE "%'.$this->db->escape($filter['value']).'%"' : db_prefix().'leads.'.$filter['type'].' NOT LIKE "%'.$filter['value'].'%"';
                        break;
                    default:
                        break;
                }
            }
        }

        $this->db->where('segment_id', $id);
        $this->db->where('deleted', 0);
        $lead_segments = $this->db->get(db_prefix().'ma_lead_segments')->result_array();
        $where_lead_segment = '';
        foreach ($lead_segments as $value) {
            if($where_lead_segment != ''){
              $where_lead_segment .= ','.$value['lead_id'];
            }else{
              $where_lead_segment .= $value['lead_id'];
            }
        }

        if($where_lead_segment != ''){
            $where .= ' OR '.db_prefix().'leads.id in ('.$where_lead_segment.')';
        }

        if($where != ''){
          $where = '('.$where.')';
        }else{
          $where = '1=0';
        }
       
        if($return_type == 'leads'){
            $this->db->join(db_prefix() . 'taggables', db_prefix() . 'taggables.rel_id = '. db_prefix() . 'leads.id', 'left');
            $this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = '. db_prefix() . 'taggables.tag_id', 'left');
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();
            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_campaign_by_segment($id){
        $where = 'workflow LIKE \'%\\\\\\\\"segment\\\\\\\\":\\\\\\\\"'.$id.'\\\\\\\\"%\'';
        $this->db->where('start_date <= "'.date('Y-m-d').'" AND end_date >= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        $this->db->where('end_date <= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $old_campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        return ['campaigns' => count($campaigns), 'old_campaigns' => count($old_campaigns)];
    }

    /**
     * @param  integer
     * @param  string
     * @return array or string
     */
    public function get_lead_by_campaign($id, $return_type = 'leads'){
        $campaign = $this->get_campaign($id);
        $where = '';

        if($campaign->workflow != ''){
            $workflow = json_decode(json_decode($campaign->workflow), true);

            foreach($workflow['drawflow']['Home']['data'] as $data){
                if($data['class'] == 'flow_start'){
                    if(!isset($data['data']['lead_data_from']) || $data['data']['lead_data_from'] == 'segment'){
                        $where = $this->get_lead_by_segment($data['data']['segment'], 'where');
                    }else{
                        $where .= 'from_ma_form_id = '.$data['data']['form'];
                    }
                }
            }
        }   

        $this->db->where('campaign_id', $id);
        $lead_exception = $this->db->get(db_prefix().'ma_campaign_lead_exceptions')->result_array();
        $lead_exception_where = '';

        foreach($lead_exception as $lead){
            if($lead_exception_where == ''){
                $lead_exception_where = $lead['lead_id'];
            }else{
                $lead_exception_where .= ','.$lead['lead_id'];
            }
        }

        if($lead_exception_where != ''){
            if($where != ''){
                $where .= ' AND '.db_prefix().'leads.id not in ('.$lead_exception_where.')';
            }else{
                $where .= db_prefix().'leads.id not in ('.$lead_exception_where.')';
            }
        }

        if($where == ''){
            $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->select(db_prefix() . 'leads.*');
            $this->db->join(db_prefix() . 'taggables', db_prefix() . 'taggables.rel_id = '. db_prefix() . 'leads.id', 'left');
            $this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = '. db_prefix() . 'taggables.tag_id', 'left');
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();
            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return boolean
     */
    public function run_campaigns($id){
        $campaign = $this->get_campaign($id);
        $workflow = json_decode(json_decode($campaign->workflow), true);

        $workflow = $workflow['drawflow']['Home']['data'];
        $data_flow = [];

        $leads = $this->get_lead_by_campaign($id);
        
        $data = [];
        $data['campaign'] = $campaign;
        $data['workflow'] = $workflow;

        foreach($leads as $lead){
            if($this->check_lead_exception($campaign->id, $lead['id'])){
                continue;
            }

            $data['lead'] = $lead;
            foreach($workflow as $data_workflow){
                $data['node'] = $data_workflow;

                if($data_workflow['class'] == 'flow_start'){
                    if(!$this->check_workflow_node_log($data)){
                        $this->save_workflow_node_log($data);
                    }

                    foreach ($data_workflow['outputs']['output_1']['connections'] as $connection) {
                        $data['node'] = $workflow[$connection['node']];
                        $this->run_workflow_node($data);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function run_workflow_node($data){
        $output = $this->check_workflow_node_log($data);

        if(!$output){
            switch ($data['node']['class']) {
                case 'email':
                    $success = $this->handle_email_node($data);

                    if($success){
                        $this->save_workflow_node_log($data);

                        foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
                            $data['node'] = $data['workflow'][$connection['node']];
                            $this->run_workflow_node($data);
                        }
                    }

                    break;

                case 'sms':
                    $success = $this->handle_sms_node($data);

                    if($success){
                        $this->save_workflow_node_log($data);

                        foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
                            $data['node'] = $data['workflow'][$connection['node']];
                            $this->run_workflow_node($data);
                        }
                    }

                    break;

                case 'action':
                    $success = $this->handle_action_node($data);

                    if($success){
                        $this->save_workflow_node_log($data);

                        foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
                            $data['node'] = $data['workflow'][$connection['node']];
                            $this->run_workflow_node($data);
                        }
                    }

                    break;

                case 'condition':
                    $success = $this->handle_condition_node($data);
                    if($success == 'output_1'){
                        $this->save_workflow_node_log($data);

                        foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
                            $data['node'] = $data['workflow'][$connection['node']];
                            $this->run_workflow_node($data);
                        }

                    }elseif($success == 'output_2'){
                        $this->save_workflow_node_log($data, 'output_2');

                        foreach ($data['node']['outputs']['output_2']['connections'] as $connection) {
                            $data['node'] = $data['workflow'][$connection['node']];
                            $this->run_workflow_node($data);
                        }
                    }

                    break;

                case 'filter':
                    $success = $this->handle_filter_node($data);
                    if($success == 'output_1'){
                        $this->save_workflow_node_log($data);

                        foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
                            $data['node'] = $data['workflow'][$connection['node']];
                            $this->run_workflow_node($data);
                        }

                    }elseif($success == 'output_2'){
                        $this->save_workflow_node_log($data, 'output_2');

                        foreach ($data['node']['outputs']['output_2']['connections'] as $connection) {
                            $data['node'] = $data['workflow'][$connection['node']];
                            $this->run_workflow_node($data);
                        }
                    }
                    break;

                default:
                    // code...
                    break;
            }
        }else{
            foreach ($data['node']['outputs'][$output]['connections'] as $connection) {
                $data['node'] = $data['workflow'][$connection['node']];
                $this->run_workflow_node($data);
            }
        }

        return true;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function handle_email_node($data){
        highlight_string("<?php\n" . var_export($data['lead']['email'], true) . ";\n?>");

        if(isset($data['node']['data']['email']) && $data['lead']['email'] != ''){
            if(!isset($data['node']['data']['complete_action'])){
                $data['node']['data']['complete_action'] = 'right_away';
            }

            switch ($data['node']['data']['complete_action']) {
                case 'right_away':
                    $email = $this->get_email($data['node']['data']['email']);
                    $log_id = $this->save_email_log(['lead_id' => $data['lead']['id'], 'email_id' => $email->id, 'email_template_id' => $email->email_template, 'campaign_id' => $data['campaign']->id]);
                    
                    $this->ma_send_email($data['lead']['email'], $email, $data['lead']['id'], $log_id);

                    return true;

                    break;
                case 'after':
                    if(!isset($data['node']['data']['waiting_number'])){
                        $data['node']['data']['waiting_number'] = 1;
                    }
                    
                    if(!isset($data['node']['data']['waiting_type'])){
                        $data['node']['data']['waiting_type'] = 'minutes';
                    }

                    foreach ($data['node']['inputs']['input_1']['connections'] as $connection) {
                        $this->db->where('campaign_id', $data['campaign']->id);
                        $this->db->where('lead_id', $data['lead']['id']);
                        $this->db->where('node_id', $connection['node']);
                        $logs = $this->db->get(db_prefix().'ma_campaign_flows')->row();

                        if($logs){
                            $time = date('Y-m-d H:i:s', strtotime($logs->dateadded." +".$data['node']['data']['waiting_number']." ".$data['node']['data']['waiting_type']));

                            if(date('Y-m-d H:i:s') >= $time){
                                $email = $this->get_email($data['node']['data']['email']);
                                $log_id = $this->save_email_log(['lead_id' => $data['lead']['id'], 'email_id' => $email->id, 'email_template_id' => $email->email_template, 'campaign_id' => $data['campaign']->id]);

                                $this->ma_send_email($data['lead']['email'], $email, $data['lead']['id'], $log_id);

                                return true;
                            }
                        }
                    }

                    break;
                case 'exact_time':
                    $time = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$data['node']['data']['exact_time']));

                    if(date('Y-m-d H:i:s') >= $time){
                        $email = $this->get_email($data['node']['data']['email']);
                        $log_id = $this->save_email_log(['lead_id' => $data['lead']['id'], 'email_id' => $email->id, 'email_template_id' => $email->email_template, 'campaign_id' => $data['campaign']->id]);

                        $this->ma_send_email($data['lead']['email'], $email, $data['lead']['id'], $log_id);

                        return true;
                    }

                    break;
                case 'exact_time_and_date':
                    $time = $data['node']['data']['exact_time_and_date'];

                    if(date('Y-m-d H:i:s') >= $time){
                        $email = $this->get_email($data['node']['data']['email']);
                        $log_id = $this->save_email_log(['lead_id' => $data['lead']['id'], 'email_id' => $email->id, 'email_template_id' => $email->email_template, 'campaign_id' => $data['campaign']->id]);

                        $this->ma_send_email($data['lead']['email'], $email, $data['lead']['id'], $log_id);

                        return true;
                    }
                    
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        return false;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function handle_sms_node($data){
        if(isset($data['node']['data']['sms']) && $data['lead']['phonenumber'] != ''){
            if(!isset($data['node']['data']['complete_action'])){
                $data['node']['data']['complete_action'] = 'right_away';
            }

            switch ($data['node']['data']['complete_action']) {
                case 'right_away':
                    $sms = $this->get_sms($data['node']['data']['sms']);

                    $this->sendSMS($sms->content, $data['lead']['phonenumber'], $data['lead']['id']);
                    $this->save_sms_log(['lead_id' => $data['lead']['id'], 'sms_id' => $sms->id, 'text_message_id' => $sms->sms_template, 'campaign_id' => $data['campaign']->id]);

                    return true;

                    break;
                case 'after':
                    if(!isset($data['node']['data']['waiting_number'])){
                        $data['node']['data']['waiting_number'] = 1;
                    }
                    
                    if(!isset($data['node']['data']['waiting_type'])){
                        $data['node']['data']['waiting_type'] = 'minutes';
                    }

                    foreach ($data['node']['inputs']['input_1']['connections'] as $connection) {
                        $this->db->where('campaign_id', $data['campaign']->id);
                        $this->db->where('lead_id', $data['lead']['id']);
                        $this->db->where('node_id', $connection['node']);
                        $logs = $this->db->get(db_prefix().'ma_campaign_flows')->row();

                        if($logs){
                            $time = date('Y-m-d H:i:s', strtotime($logs->dateadded." +".$data['node']['data']['waiting_number']." ".$data['node']['data']['waiting_type']));

                            if(date('Y-m-d H:i:s') >= $time){
                                $sms = $this->get_sms($data['node']['data']['sms']);

                                $this->sendSMS($sms->content, $data['lead']['phonenumber'], $data['lead']['id']);
                                $this->save_sms_log(['lead_id' => $data['lead']['id'], 'sms_id' => $sms->id, 'text_message_id' => $sms->sms_template, 'campaign_id' => $data['campaign']->id]);

                                return true;
                            }
                        }
                    }

                    break;
                case 'exact_time':
                    $time = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$data['node']['data']['exact_time']));

                    if(date('Y-m-d H:i:s') >= $time){
                        $sms = $this->get_sms($data['node']['data']['sms']);

                        $this->sendSMS($sms->content, $data['lead']['phonenumber'], $data['lead']['id']);
                        $this->save_sms_log(['lead_id' => $data['lead']['id'], 'sms_id' => $sms->id, 'text_message_id' => $sms->sms_template, 'campaign_id' => $data['campaign']->id]);

                        return true;
                    }

                    break;
                case 'exact_time_and_date':
                    $time = $data['node']['data']['exact_time_and_date'];

                    if(date('Y-m-d H:i:s') >= $time){
                        $sms = $this->get_sms($data['node']['data']['sms']);

                        $this->sendSMS($sms->content, $data['lead']['phonenumber'], $data['lead']['id']);
                        $this->save_sms_log(['lead_id' => $data['lead']['id'], 'sms_id' => $sms->id, 'text_message_id' => $sms->sms_template, 'campaign_id' => $data['campaign']->id]);

                        return true;
                    }
                    
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        return false;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function handle_action_node($data){
        if(!isset($data['node']['data']['action'])){
            $data['node']['data']['action'] = 'change_segments';
        }

        switch ($data['node']['data']['action']) {
            case 'change_segments':
                if(isset($data['node']['data']['segment'])){
                    $this->change_segment($data['lead']['id'], $data['node']['data']['segment'], $data['campaign']->id);

                    return true;
                }

                break;
            case 'change_stages':
                if(isset($data['node']['data']['stage'])){
                    $this->change_stage($data['lead']['id'], $data['node']['data']['stage'], $data['campaign']->id);

                    return true;
                }

                break;
            case 'change_points':
                if(isset($data['node']['data']['point'])){
                    $this->db->where('id', $data['lead']['id']);
                    $lead = $this->db->get(db_prefix().'leads')->row();

                    $this->db->where('id', $data['lead']['id']);
                    $this->db->update(db_prefix().'leads', ['ma_point' => $lead->ma_point + $data['node']['data']['point']]);

                    $this->db->insert(db_prefix().'ma_point_action_logs', [
                        'campaign_id' => $data['campaign']->id, 
                        'lead_id' => $data['lead']['id'], 
                        'point_action_id' => 0, 
                        'point' => $data['node']['data']['point'],
                        'dateadded' => date('Y-m-d H:i:s'), 
                    ]);

                    return true;
                }

                break;

            case 'point_action':
                if(isset($data['node']['data']['point_action'])){
                    $point_action = $this->get_point_action($data['node']['data']['point_action']);
                    $this->db->where('id', $data['lead']['id']);
                    $lead = $this->db->get(db_prefix().'leads')->row();

                    $this->db->where('id', $data['lead']['id']);
                    $this->db->update(db_prefix().'leads', ['ma_point' => $lead->ma_point + $point_action->change_points]);

                    $this->db->insert(db_prefix().'ma_point_action_logs', [
                        'campaign_id' => $data['campaign']->id, 
                        'lead_id' => $data['lead']['id'], 
                        'point_action_id' => $point_action->id, 
                        'point' => $point_action->change_points,
                        'dateadded' => date('Y-m-d H:i:s'), 
                    ]);

                    return true;
                }

                break;

            case 'delete_lead':
                $this->load->model('leads_model');
                $this->leads_model->delete($data['lead']['id']);
                
                return true;

                break;

            case 'remove_from_campaign':
                
                $this->remove_from_campaign($data['campaign']->id, $data['lead']['id']);

                return true;

                break;

            case 'convert_to_customer':

                $this->convert_lead_to_customer($data['lead']);
                return true;
                
                break; 
            
            default:
                // code...
                break;
        }
    }

    /**
     * @param  array
     * @return boolean
     */
    public function handle_filter_node($data){
        if(!isset($data['node']['data']['complete_action'])){
            $data['node']['data']['complete_action'] = 'right_away';
        }

        switch ($data['node']['data']['complete_action']) {
            case 'right_away':
                if($this->check_lead_filter($data['lead'], $data['node'])){
                    return 'output_1';
                }else{
                    return 'output_2';
                }

                break;
            case 'after':
                if(!isset($data['node']['data']['waiting_number'])){
                    $data['node']['data']['waiting_number'] = 1;
                }
                
                if(!isset($data['node']['data']['waiting_type'])){
                    $data['node']['data']['waiting_type'] = 'minutes';
                }

                foreach ($data['node']['inputs']['input_1']['connections'] as $connection) {
                    $this->db->where('campaign_id', $data['campaign']->id);
                    $this->db->where('lead_id', $data['lead']['id']);
                    $this->db->where('node_id', $connection['node']);
                    $logs = $this->db->get(db_prefix().'ma_campaign_flows')->row();

                    if($logs){
                        $time = date('Y-m-d H:i:s', strtotime($logs->dateadded." +".$data['node']['data']['waiting_number']." ".$data['node']['data']['waiting_type']));

                        if(date('Y-m-d H:i:s') >= $time){
                            if($this->check_lead_filter($data['lead'], $data['node'])){
                                return 'output_1';
                            }else{
                                return 'output_2';
                            }
                        }
                    }
                }
            
                break;
            default:
                // code...
                break;
        }

        return false;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function handle_condition_node($data){


        foreach ($data['node']['inputs']['input_1']['connections'] as $connection) {
            $this->db->where('campaign_id', $data['campaign']->id);
            $this->db->where('lead_id', $data['lead']['id']);
            $this->db->where('node_id', $connection['node']);
            $logs = $this->db->get(db_prefix().'ma_campaign_flows')->row();

            if($logs){
                if(!isset($data['node']['data']['waiting_number'])){
                    $data['node']['data']['waiting_number'] = 1;
                }

                if(!isset($data['node']['data']['waiting_type'])){
                    $data['node']['data']['waiting_type'] = 'minutes';
                }

                $time = date('Y-m-d H:i:s', strtotime($logs->dateadded." +".$data['node']['data']['waiting_number']." ".$data['node']['data']['waiting_type']));

                if(date('Y-m-d H:i:s') >= $time){

                    if(!isset($data['node']['data']['track'])){
                        $data['node']['data']['track'] = 'delivery';
                    }

                    switch ($data['node']['data']['track']) {
                        case 'delivery':
                            foreach ($data['node']['inputs']['input_1']['connections'] as $connection) {
                                $node = $data['workflow'][$connection['node']];
                                if($node['class'] == 'email'){
                                    if(isset($node['data']['email'])){
                                        if($this->check_condition_email($data, $node['data']['email'], 'delivery')){
                                            return 'output_1';
                                        }else{
                                            return 'output_2';
                                        }
                                    }
                                }
                            }
                            break;

                        case 'opens':
                            foreach ($data['node']['inputs']['input_1']['connections'] as $connection) {
                                $node = $data['workflow'][$connection['node']];
                                if($node['class'] == 'email'){
                                    if(isset($node['data']['email'])){
                                        if($this->check_condition_email($data, $node['data']['email'], 'open')){
                                            return 'output_1';
                                        }else{
                                            return 'output_2';
                                        }
                                    }
                                }
                            }
                            break;

                        case 'clicks':
                            foreach ($data['node']['inputs']['input_1']['connections'] as $connection) {
                                $node = $data['workflow'][$connection['node']];
                                if($node['class'] == 'email'){
                                    if(isset($node['data']['email'])){
                                        if($this->check_condition_email($data, $node['data']['email'], 'click')){
                                            return 'output_1';
                                        }else{
                                            return 'output_2';
                                        }
                                    }
                                }
                            }
                            break;
                        
                        default:
                            
                            break;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param  array
     * @param  string
     * @return boolean
     */
    public function save_workflow_node_log($data, $output = 'output_1'){
        $this->db->where('campaign_id', $data['campaign']->id);
        $this->db->where('lead_id', $data['lead']['id']);
        $this->db->where('node_id', $data['node']['id']);
        $logs = $this->db->get(db_prefix().'ma_campaign_flows')->row();

        if(!$logs){
            $this->db->insert(db_prefix().'ma_campaign_flows', [
                'campaign_id' => $data['campaign']->id, 
                'lead_id' => $data['lead']['id'], 
                'node_id' => $data['node']['id'], 
                'output' => $output, 
                'dateadded' => date('Y-m-d H:i:s'), 
            ]);
        }

        return true;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function check_workflow_node_log($data){
        $this->db->where('campaign_id', $data['campaign']->id);
        $this->db->where('lead_id', $data['lead']['id']);
        $this->db->where('node_id', $data['node']['id']);
        $logs = $this->db->get(db_prefix().'ma_campaign_flows')->row();

        if($logs){
            return $logs->output;
        }

        return false;
    }

    //send sms with setting
    /**
     * sendSMS
     * @param  [type] $request 
     * @return [type]          
     */
    public function sendSMS($request, $to, $lead_id = '') {
        $content = $this->parse_content_merge_fields($request, $lead_id);

        if (get_option('sms_twilio_active') == 1) {
            return $this->twilioSms($content,$to);
        }
        else if (get_option('sms_clickatell_active') == 1) {

            return $this->clickatellSms($content,$to);
            
        }
        else if (get_option('sms_msg91_active') == 1) {
            return $this->msg91Sms($content,$to);
        }
    }

    /**
     * twilioSms
     * @param  [type] $request 
     * @param  [type] $to      
     * @return [type]          
     */
    public function twilioSms($mess,$to) {
    /*$request: message, to : phonenumber */

        $account_sid   = get_option('sms_twilio_account_sid');
        $auth_token   = get_option('sms_twilio_auth_token');
        $twilio_number   = get_option('sms_twilio_phone_number');

        $client = new Client($account_sid, $auth_token);

        $message = $client->messages->create(
            $to,
            array(
                'from' => $twilio_number,
                'body' => $mess
            )
        );

        if ($message->sid) {
            return true;
        }
       
        return false;
    }

    /**
     * msg91Sms
     * @param  [type] $request 
     * @param  [type] $to      
     * @return [type]          
     */
    public function msg91Sms($message,$to) {

        $authKey = get_option('sms_msg91_auth_key');
                    
        $mobileNumber = $to;

        $senderId =  get_option('sms_msg91_sender_id');

        $message = urlencode($message);

        $route = "define";

        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        $url="http://world.msg91.com/api/sendhttp.php";

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ));

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);

        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        if ($output !== null) {
            return true;
            
        }
        return false;
    }

    /**
     * clickatellSms
     * @param  [type] $request 
     * @param  [type] $to      
     * @return [type]          
     */
    public function clickatellSms($message,$to) {
    

        $clickatell = new Rest(get_option('sms_clickatell_api_key'));
        try {

            $result = $clickatell->sendMessage(['to' => [$to], 'content' => $message]);
  
            return true;
            
        } catch (ClickatellException $e) {

            return false;

        }
    }

    /**
     * @param  integer
     * @param  integer
     * @param  integer
     * @return boolean
     */
    public function change_segment($lead_id, $segment_id, $campaign_id){
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where('lead_id', $lead_id);
        $this->db->where('segment_id', $segment_id);
        $logs = $this->db->get(db_prefix().'ma_lead_segments')->row();

        if(!$logs){
            $segment = $this->get_segment($segment_id);
            $segments = $this->get_segment('', 'category = '.$segment->category);

            foreach ($segments as $value) {
                $this->db->where('segment_id', $value['id']);
                $this->db->where('lead_id', $lead_id);
                $this->db->update(db_prefix().'ma_lead_segments', [
                    'deleted' => 1, 
                    'date_delete' => date('Y-m-d H:i:s'), 
                ]);               
            }

            $this->db->insert(db_prefix().'ma_lead_segments', [
                'campaign_id' => $campaign_id, 
                'lead_id' => $lead_id, 
                'segment_id' => $segment_id, 
                'dateadded' => date('Y-m-d H:i:s'), 
            ]);
        }

        return true;
    }

    /**
     * @param  integer
     * @param  integer
     * @param  integer
     * @return boolean
     */
    public function change_stage($lead_id, $stage_id, $campaign_id){
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where('lead_id', $lead_id);
        $this->db->where('stage_id', $stage_id);
        $logs = $this->db->get(db_prefix().'ma_lead_stages')->row();

        if(!$logs){
            $stage = $this->get_stage($stage_id);
            $stages = $this->get_stage('', 'category = '.$stage->category);

            foreach ($stages as $value) {
                $this->db->where('stage_id', $value['id']);
                $this->db->where('lead_id', $lead_id);
                $this->db->update(db_prefix().'ma_lead_stages', [
                    'deleted' => 1, 
                    'date_delete' => date('Y-m-d H:i:s'), 
                ]);               
            }

            $this->db->insert(db_prefix().'ma_lead_stages', [
                'campaign_id' => $campaign_id, 
                'lead_id' => $lead_id, 
                'stage_id' => $stage_id, 
                'dateadded' => date('Y-m-d H:i:s'), 
            ]);
        }

        return true;
    }

    /**
     * Convert lead to client
     * @since  version 1.0.1
     * @return mixed
     */
    public function convert_lead_to_customer($lead)
    {
        $default_country  = get_option('customer_default_country');

        if(mb_strpos($lead['name'],' ') !== false){
           $_temp = explode(' ',$lead['name']);
           $firstname = $_temp[0];
           if(isset($_temp[2])){
             $lastname = $_temp[1] . ' ' . $_temp[2];
          } else {
             $lastname = $_temp[1];
          }
       } else {
          $lastname = '';
          $firstname = $lead->name;
       }

        $data             = [
            'leadid' => $lead['id'],
            'password' => '1',
            'firstname' => $firstname,
            'lastname' => $lastname,
            'title' => $lead['title'],
            'email' => $lead['email'],
            'company' => $lead['company'],
            'phonenumber' => $lead['phonenumber'],
            'website' => $lead['website'],
            'address' => $lead['address'],
            'city' => $lead['city'],
            'state' => $lead['state'],
            'country' => $lead['country'],
            'zip' => $lead['zip'],
            'fakeusernameremembered' => '',
            'fakepasswordremembered' => '',
        ];

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
            $this->load->model('leads_model');
            
            $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted', false, serialize([
                get_staff_full_name(),
            ]));
            $default_status = $this->leads_model->get_status('', [
                'isdefault' => 1,
            ]);
            $this->db->where('id', $data['leadid']);
            $this->db->update(db_prefix() . 'leads', [
                'date_converted' => date('Y-m-d H:i:s'),
                'status'         => $default_status[0]['id'],
                'junk'           => 0,
                'lost'           => 0,
            ]);
            // Check if lead email is different then client email
            $contact = $this->clients_model->get_contact(get_primary_contact_user_id($id));
          
            // set the lead to status client in case is not status client
            $this->db->where('isdefault', 1);
            $status_client_id = $this->db->get(db_prefix() . 'leads_status')->row()->id;
            $this->db->where('id', $data['leadid']);
            $this->db->update(db_prefix() . 'leads', [
                'status' => $status_client_id,
            ]);

            set_alert('success', _l('lead_to_client_base_converted_success'));

            if (is_gdpr() && get_option('gdpr_after_lead_converted_delete') == '1') {
                // When lead is deleted
                // move all proposals to the actual customer record
                $this->db->where('rel_id', $data['leadid']);
                $this->db->where('rel_type', 'lead');
                $this->db->update('proposals', [
                    'rel_id'   => $id,
                    'rel_type' => 'customer',
                ]);

                $this->leads_model->delete($data['leadid']);

                $this->db->where('userid', $id);
                $this->db->update(db_prefix() . 'clients', ['leadid' => null]);
            }

            log_activity('Created Lead Client Profile [LeadID: ' . $data['leadid'] . ', ClientID: ' . $id . ']');
            hooks()->do_action('lead_converted_to_customer', ['lead_id' => $data['leadid'], 'customer_id' => $id]);
        }
    }

    /**
     * @param  array
     * @param  array
     * @return boolean
     */
    public function check_lead_filter($lead, $node){

        if(!isset($node['data']['name_of_variable'])){
            $node['data']['name_of_variable'] = 'name';
        }
        
        if(!isset($node['data']['condition'])){
            $node['data']['condition'] = 'equals';
        }

        if(!isset($node['data']['value_of_variable'])){
            $node['data']['value_of_variable'] = '';
        }

        if($node['data']['name_of_variable'] == 'tag'){
            return false;
        }

        switch ($node['data']['condition']) {
            case 'equals':
                if($node['data']['value_of_variable'] == $lead[$node['data']['name_of_variable']]){
                    return true;
                }
                break;
            case 'not_equal':
                if($node['data']['value_of_variable'] != $lead[$node['data']['name_of_variable']]){
                    return true;
                }
                break;
            case 'greater_than':
                if($node['data']['value_of_variable'] = $lead[$node['data']['name_of_variable']]){
                    return true;
                }
                break;
            case 'greater_than_or_equal':
                if($node['data']['value_of_variable'] <= $lead[$node['data']['name_of_variable']]){
                    return true;
                }
                break;
            case 'less_than':
                if($node['data']['value_of_variable'] > $lead[$node['data']['name_of_variable']]){
                    return true;
                }
                break;
            case 'less_than_or_equal':
                if($node['data']['value_of_variable'] <= $lead[$node['data']['name_of_variable']]){
                    return true;
                }
                break;
            case 'empty':
                if($lead[$node['data']['name_of_variable']] == ''){
                    return true;
                }
                break;
            case 'not_empty':
                if($lead[$node['data']['name_of_variable']] != ''){
                    return true;
                }
                $where .= db_prefix().'leads.'.$filter['type'].' != ""';
                break;
            case 'like':
                if (!(strpos($lead[$node['data']['name_of_variable']], $node['data']['value_of_variable']) === false)) {
                    return true;
                }
                break;
            case 'not_like':
                if (!(strpos($lead[$node['data']['name_of_variable']], $node['data']['value_of_variable']) !== false)) {
                    return true;
                }
                break;
            default:
                break;
        }

        return false;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function save_email_log($data){
        
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix().'ma_email_logs', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function save_sms_log($data){
        
        $data['delivery'] = 1;
        $data['delivery_time'] = date('Y-m-d H:i:s');
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix().'ma_sms_logs', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * download asset
     * @param  string $hash_share
     * @return boolean
     */
    public function download_asset($asset_id) {
        $browser = $this->getBrowser();

        $this->db->insert(db_prefix() . 'ma_asset_download_logs', [
            'ip' => $this->get_client_ip(),
            'browser_name' => $browser['name'],
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'asset_id' => $asset_id,
            'time' => date('Y-m-d H:i:s'),
        ]);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return true;
        }

        return false;
    }

    /**
     * get Browser info
     * @return array
     */
    public function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/coc_coc_browser/i', $u_agent)) {
            $bname = 'Cốc Cốc';
            $ub = "coc_coc_browser";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {$version = "?";}

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern,
        );
    }

    /**
     * Function to get the client IP address
     * @return string
     */
    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_asset_download_chart($asset_id = '')
    {
        $this->db->select('date_format(time, \'%Y-%m-%d\') as time, COUNT(*) as count_download');
        if($asset_id != ''){
            $this->db->where('asset_id', $asset_id);
        }
        $this->db->group_by('date_format(time, \'%Y-%m-%d\')');
        $asset_download = $this->db->get(db_prefix().'ma_asset_download_logs')->result_array();
        $data_asset_download = [];
        foreach($asset_download as $download){
            $data_asset_download[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_download']];
        }
        
        return $data_asset_download;
    }

    /**
     * @param  integer
     * @param  string
     * @return array
     */
    public function get_lead_by_email_template($id, $return_type = 'leads'){
        
        $this->db->select('lead_id');
        $this->db->where('email_template_id', $id);
        $this->db->group_by('lead_id');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();

        $where = '';
        foreach ($email_logs as $key => $value) {
            if($where != ''){
                $where .= ','. $value['lead_id'];
            }else{
                $where .= $value['lead_id'];
            }
        }

        if($where != ''){
          $where = '('.db_prefix().'leads.id in ('.$where.'))';
        }else{
          $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();

            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_campaign_by_email_template($id){
        $where = 'workflow LIKE \'%\\\\\\\\"email_template\\\\\\\\":\\\\\\\\"'.$id.'\\\\\\\\"%\'';
        $this->db->where('start_date <= "'.date('Y-m-d').'" AND end_date >= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        $this->db->where('end_date <= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $old_campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        return ['campaigns' => count($campaigns), 'old_campaigns' => count($old_campaigns)];
    }

    /**
     * @param  integer
     * @param  array
     * @return array
     */
    public function get_data_email_template_chart($email_template_id = '', $data_filter = [])
    {
        $where = $this->get_where_report_period('date_format(dateadded, \'%Y-%m-%d\')');

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($email_template_id != ''){
            $this->db->where('email_template_id', $email_template_id);
        }
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_delivery = [];
        foreach($email_logs as $download){
            $data_delivery[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($email_template_id != ''){
            $this->db->where('email_template_id', $email_template_id);
        }
        $this->db->where('open', 1);
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_open = [];
        foreach($email_logs as $download){
            $data_open[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($email_template_id != ''){
            $this->db->where('email_template_id', $email_template_id);
        }
        $this->db->where('click', 1);
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_click = [];
        foreach($email_logs as $download){
            $data_click[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('delivery'), 'data' => $data_delivery, 'color' => '#008ece'];
        $data_return[] = ['name' => _l('read'), 'data' => $data_open, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('click'), 'data' => $data_click, 'color' => '#ff6f00'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_email_template_by_campaign_chart($email_template_id)
    {
        $this->db->select('campaign_id');
        $this->db->where('email_template_id', $email_template_id);
        $this->db->group_by('campaign_id');
        $campaign_ids = $this->db->get(db_prefix().'ma_email_logs')->result_array();

        $data_header = [];
        $data_delivery = [];
        $data_open = [];
        $data_click = [];

        foreach($campaign_ids as $value){
            $campaign = $this->get_campaign($value['campaign_id']);
            $data_header[] = $campaign->name;

            $this->db->where('email_template_id', $email_template_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $count_delivery = $this->db->count_all_results(db_prefix().'ma_email_logs');
            $data_delivery[] = $count_delivery;

            $this->db->where('email_template_id', $email_template_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $this->db->where('open', 1);
            $count_open = $this->db->count_all_results(db_prefix().'ma_email_logs');
            $data_open[] = $count_open;

            $this->db->where('email_template_id', $email_template_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $this->db->where('click', 1);
            $count_click = $this->db->count_all_results(db_prefix().'ma_email_logs');
            $data_click[] = $count_click;
        }

        $data_return = [];
        $data_return[] = ['name' => _l('delivery'), 'data' => $data_delivery, 'color' => '#008ece'];
        $data_return[] = ['name' => _l('read'), 'data' => $data_open, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('click'), 'data' => $data_click, 'color' => '#ff6f00'];

        return ['header' => $data_header, 'data' => $data_return];
    }

    /**
     * @param  integer
     * @param  string
     * @return array
     */
    public function get_lead_by_point_action($id, $return_type = 'leads'){
        
        $this->db->select('lead_id');
        $this->db->where('point_action_id', $id);
        $this->db->group_by('lead_id');
        $point_action_logs = $this->db->get(db_prefix().'ma_point_action_logs')->result_array();

        $where = '';
        foreach ($point_action_logs as $key => $value) {
            if($where != ''){
                $where .= ','. $value['lead_id'];
            }else{
                $where .= $value['lead_id'];
            }
        }

        if($where != ''){
            $where = '('.db_prefix().'leads.id in ('.$where.'))';
        }else{
            $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();

            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_point_action_chart($point_action_id = '')
    {
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_action');
        if($point_action_id != ''){
            $this->db->where('point_action_id', $point_action_id);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $point_action = $this->db->get(db_prefix().'ma_point_action_logs')->result_array();

        $data_point_action = [];
        foreach($point_action as $action){
            $data_point_action[] = [strtotime($action['time'].' 00:00:00') * 1000, (int)$action['count_action']];
        }
        
        return $data_point_action;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_point_action_by_campaign_chart($point_action_id)
    {
        $this->db->select('campaign_id');
        $this->db->where('point_action_id', $point_action_id);
        $this->db->group_by('campaign_id');
        $campaign_ids = $this->db->get(db_prefix().'ma_point_action_logs')->result_array();

        $data_header = [];
        $data_action = [];
        $data_open = [];
        $data_click = [];

        foreach($campaign_ids as $value){
            $campaign = $this->get_campaign($value['campaign_id']);
            $data_header[] = $campaign->name;

            $this->db->where('point_action_id', $point_action_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $count_action = $this->db->count_all_results(db_prefix().'ma_point_action_logs');
            $data_action[] = $count_action;
        }

        $data_return = [];
        $data_return[] = ['name' => _l('point_action'), 'data' => $data_action, 'color' => '#008ece'];

        return ['header' => $data_header, 'data' => $data_return];
    }

    /**
     * Gets the where report period.
     *
     * @param      string  $field  The field
     *
     * @return     string  The where report period.
     */
    private function get_where_report_period($field = 'date')
    {
        $months_report      = $this->input->get('date_filter');
        
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = '(' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'last_30_days') {
                $custom_date_select = '(' . $field . ' BETWEEN "' . date('Y-m-d', strtotime('today - 30 days')) . '" AND "' . date('Y-m-d') . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = '(' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'last_month') {
                $this_month = date('m') - 1;
                $custom_date_select = '(' . $field . ' BETWEEN "' . date("Y-m-d", strtotime("first day of previous month")) . '" AND "' . date("Y-m-d", strtotime("last day of previous month")) . '")';
            }elseif ($months_report == 'this_quarter') {
                $current_month = date('m');
                  $current_year = date('Y');
                  if($current_month>=1 && $current_month<=3)
                  {
                    $start_date = date('Y-m-d', strtotime('1-January-'.$current_year));  // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM means end of 31 March
                  }
                  else  if($current_month>=4 && $current_month<=6)
                  {
                    $start_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM means end of 30 June
                  }
                  else  if($current_month>=7 && $current_month<=9)
                  {
                    $start_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-October-'.$current_year));  // timestamp or 1-October 12:00:00 AM means end of 30 September
                  }
                  else  if($current_month>=10 && $current_month<=12)
                  {
                    $start_date = date('Y-m-d', strtotime('1-October-'.$current_year));  // timestamp or 1-October 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-January-'.($current_year+1)));  // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                  }
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                $start_date .
                '" AND "' .
                $end_date . '")';

            }elseif ($months_report == 'last_quarter') {
                $current_month = date('m');
                    $current_year = date('Y');

                  if($current_month>=1 && $current_month<=3)
                  {
                    $start_date = date('Y-m-d', strtotime('1-October-'.($current_year-1)));  // timestamp or 1-October Last Year 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-January-'.$current_year));  // // timestamp or 1-January  12:00:00 AM means end of 31 December Last year
                  } 
                  else if($current_month>=4 && $current_month<=6)
                  {
                    $start_date = date('Y-m-d', strtotime('1-January-'.$current_year));  // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM means end of 31 March
                  }
                  else  if($current_month>=7 && $current_month<=9)
                  {
                    $start_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM means end of 30 June
                  }
                  else  if($current_month>=10 && $current_month<=12)
                  {
                    $start_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-October-'.$current_year));  // timestamp or 1-October 12:00:00 AM means end of 30 September
                  }
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                $start_date .
                '" AND "' .
                $end_date . '")';

            }elseif ($months_report == 'this_year') {
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = '' . $field . ' = "' . $from_date . '"';
                } else {
                    $custom_date_select = '(' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            } elseif(!(strpos($months_report, 'financial_year') === false)){
                $year = explode('financial_year_', $months_report);

                $custom_date_select = '(' . $field . ' BETWEEN "' . date($year[1].'-01-01') . '" AND "' . date(($year[1]).'-12-t') . '")';
            }
        }

        return $custom_date_select;
    }

    /**
     * @param  string
     * @param  array
     * @return array
     */
    public function get_data_form_submit_chart($form_id = '', $data_filter = [])
    {
        $where = $this->get_where_report_period('date_format(dateadded, \'%Y-%m-%d\')');

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_submit');
        $this->db->where('from_ma_form_id != 0');
        if($form_id != ''){
            $this->db->where('from_ma_form_id', $form_id);
        }

        if($where != ''){
            $this->db->where($where);
        }

        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $form_submit = $this->db->get(db_prefix().'leads')->result_array();
        $data_form_submit = [];
        foreach($form_submit as $submit){
            $data_form_submit[] = [strtotime($submit['time'].' 00:00:00') * 1000, (int)$submit['count_submit']];
        }
        
        return $data_form_submit;
    }

    /**
     * @param  array
     * @return array
     */
    public function get_data_lead_chart($data_filter = [])
    {
        $where = $this->get_where_report_period('date_format(dateadded, \'%Y-%m-%d\')');

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_lead');
       
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $lead_created = $this->db->get(db_prefix().'leads')->result_array();

        $data_created = [];
        foreach($lead_created as $lead){
            $data_created[] = [strtotime($lead['time'].' 00:00:00') * 1000, (int)$lead['count_lead']];
        }

        $where = $this->get_where_report_period('date_format(date_converted, \'%Y-%m-%d\')');

        $this->db->select('date_format(date_converted, \'%Y-%m-%d\') as time, COUNT(*) as count_lead');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(date_converted, \'%Y-%m-%d\')');
        $lead_converted = $this->db->get(db_prefix().'leads')->result_array();
        $data_converted = [];
        foreach($lead_converted as $lead){
            $data_converted[] = [strtotime($lead['time'].' 00:00:00') * 1000, (int)$lead['count_lead']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('created'), 'data' => $data_created, 'color' => '#008ece'];
        $data_return[] = ['name' => _l('converted'), 'data' => $data_converted, 'color' => '#84c529'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @param  string
     * @return array
     */
    public function get_lead_by_stage($id, $return_type = 'leads'){
        
        $this->db->select('lead_id');
        $this->db->where('stage_id', $id);
        $this->db->where('deleted', 0);
        $this->db->group_by('lead_id');
        $lead_stages = $this->db->get(db_prefix().'ma_lead_stages')->result_array();

        $where = '';
        foreach ($lead_stages as $key => $value) {
            if($where != ''){
                $where .= ','. $value['lead_id'];
            }else{
                $where .= $value['lead_id'];
            }
        }

        if($where != ''){
          $where = '('.db_prefix().'leads.id in ('.$where.'))';
        }else{
          $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();

            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_campaign_by_stage($id){
        $where = 'workflow LIKE \'%\\\\\\\\"stage\\\\\\\\":\\\\\\\\"'.$id.'\\\\\\\\"%\'';

        $this->db->where('start_date <= "'.date('Y-m-d').'" AND end_date >= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        $this->db->where('end_date <= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $old_campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        return ['campaigns' => count($campaigns), 'old_campaigns' => count($old_campaigns)];
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_segment_detail_chart($segment_id){

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        $this->db->where('segment_id', $segment_id);
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_segments')->result_array();
        $data_added = [];
        foreach($email_logs as $download){
            $data_added[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(date_delete, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        $this->db->where('segment_id', $segment_id);
        $this->db->where('deleted', 1);
        $this->db->group_by('date_format(date_delete, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_segments')->result_array();
        $data_removed = [];
        foreach($email_logs as $download){
            $data_removed[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('added'), 'data' => $data_added, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('removed'), 'data' => $data_removed, 'color' => '#ff6f00'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_segment_by_campaign_chart($segment_id)
    {
        $this->db->select('campaign_id');
        $this->db->where('segment_id', $segment_id);
        $this->db->group_by('campaign_id');
        $campaign_ids = $this->db->get(db_prefix().'ma_lead_segments')->result_array();

        $data_header = [];
        $data_lead = [];

        foreach($campaign_ids as $value){
            $campaign = $this->get_campaign($value['campaign_id']);
            $data_header[] = $campaign->name;

            $this->db->where('segment_id', $segment_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $count_lead = $this->db->count_all_results(db_prefix().'ma_lead_segments');
            $data_lead[] = $count_lead;
        }

        $data_return = [];
        $data_return[] = ['name' => _l('lead'), 'data' => $data_lead, 'color' => '#008ece'];

        return ['header' => $data_header, 'data' => $data_return];
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_stage_detail_chart($stage_id){

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        $this->db->where('stage_id', $stage_id);
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_stages')->result_array();
        $data_added = [];
        foreach($email_logs as $download){
            $data_added[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(date_delete, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        $this->db->where('stage_id', $stage_id);
        $this->db->where('deleted', 1);
        $this->db->group_by('date_format(date_delete, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_stages')->result_array();
        $data_removed = [];
        foreach($email_logs as $download){
            $data_removed[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('added'), 'data' => $data_added, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('removed'), 'data' => $data_removed, 'color' => '#ff6f00'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_stage_by_campaign_chart($stage_id)
    {
        $this->db->select('campaign_id');
        $this->db->where('stage_id', $stage_id);
        $this->db->group_by('campaign_id');
        $campaign_ids = $this->db->get(db_prefix().'ma_lead_stages')->result_array();

        $data_header = [];
        $data_lead = [];

        foreach($campaign_ids as $value){
            $campaign = $this->get_campaign($value['campaign_id']);
            $data_header[] = $campaign->name;

            $this->db->where('stage_id', $stage_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $count_lead = $this->db->count_all_results(db_prefix().'ma_lead_stages');
            $data_lead[] = $count_lead;
        }

        $data_return = [];
        $data_return[] = ['name' => _l('point_lead'), 'data' => $data_lead, 'color' => '#008ece'];

        return ['header' => $data_header, 'data' => $data_return];
    }

    /**
     * @param  integer
     * @param  integer
     * @return boolean
     */
    public function remove_from_campaign($campaign_id, $lead_id){
        $this->db->insert(db_prefix().'ma_campaign_lead_exceptions' , ['campaign_id' => $campaign_id, 'lead_id' => $lead_id, 'dateadded' => date('Y-m-d H:i:s')]);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * @param  integer
     * @param  integer
     * @return boolean
     */
    public function check_lead_exception($campaign_id, $lead_id){
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where('lead_id', $lead_id);
        $lead_exception = $this->db->get(db_prefix().'ma_campaign_lead_exceptions')->row();

        if ($lead_exception) {
            return true;
        }

        return false;
    }

    /**
     * @param  integer
     * @param  string
     * @param  string
     * @return mixed
     */
    public function get_object_by_campaign($campaign_id, $type = '', $return = 'id'){
        $campaign = $this->get_campaign($campaign_id);

        $workflow = explode('\"'.$type.'\":\"',$campaign->workflow);

        $where = '';
        $object = [];
        if(isset($workflow[1])){
            foreach($workflow as $k => $data){
                if($k != 0){
                    $_workflow = explode('\"',$data);
                    if(isset($_workflow[1]) && !in_array($_workflow[0], $object)){
                        $object[] = $_workflow[0];
                    }
                }
            }
        }

        $data_return = [];
        if($return == 'object'){
            foreach($object as $id){
                switch ($type) {
                    case 'point_action':
                        $point_action = $this->get_point_action($id);
                        if($point_action){
                            $this->db->where('point_action_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $point_action->total = $this->db->count_all_results(db_prefix().'ma_point_action_logs');
                            $data_return[] = $point_action;
                        }
                        break;
                    case 'email':
                        $email_template = $this->get_email($id);
                        if($email_template){
                            $this->db->where('email_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $email_template->total = $this->db->count_all_results(db_prefix().'ma_email_logs');
                            $data_return[] = $email_template;
                        }
                        break;
                    case 'segment':
                        $segment = $this->get_segment($id);
                        if($segment){
                            $this->db->where('segment_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $segment->total = $this->db->count_all_results(db_prefix().'ma_lead_segments');
                            $data_return[] = $segment;
                        }
                        break;
                    case 'stage':
                        $stage = $this->get_stage($id);
                        if($stage){
                            $this->db->where('stage_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $stage->total = $this->db->count_all_results(db_prefix().'ma_lead_stages');

                            $data_return[] = $stage;
                        }
                        break;
                    case 'sms':
                        $sms = $this->get_sms($id);
                        if($sms){
                            $this->db->where('sms_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $sms->total = $this->db->count_all_results(db_prefix().'ma_sms_logs');
                            
                            $data_return[] = $sms;
                        }
                        break;
                    
                    default:
                        // code...
                        break;
                }
            }

            return $data_return;
        }
        
        return $object;
    }

    /**
     * @return boolean
     */
    public function ma_cron_campaign(){
        $where = 'start_date <= "'.date('Y-m-d').'" AND end_date >= "'.date('Y-m-d').'" AND published = 1';
        $campaigns = $this->get_campaign('', $where);

        foreach($campaigns as $campaign){
            $this->run_campaigns($campaign['id']);
        }

        return true;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_campaign_email_chart($campaign_id = '')
    {

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_delivery = [];
        foreach($email_logs as $download){
            $data_delivery[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->where('open', 1);
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_open = [];
        foreach($email_logs as $download){
            $data_open[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->where('click', 1);
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_click = [];
        foreach($email_logs as $download){
            $data_click[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('delivery'), 'data' => $data_delivery, 'color' => '#008ece'];
        $data_return[] = ['name' => _l('read'), 'data' => $data_open, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('click'), 'data' => $data_click, 'color' => '#ff6f00'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_campaign_segment_chart($campaign_id = ''){

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }

        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_segments')->result_array();
        $data_added = [];
        foreach($email_logs as $download){
            $data_added[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(date_delete, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->where('deleted', 1);
        $this->db->group_by('date_format(date_delete, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_segments')->result_array();
        $data_removed = [];
        foreach($email_logs as $download){
            $data_removed[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('added'), 'data' => $data_added, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('removed'), 'data' => $data_removed, 'color' => '#ff6f00'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_campaign_stage_chart($campaign_id = ''){

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_stages')->result_array();
        $data_added = [];
        foreach($email_logs as $download){
            $data_added[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(date_delete, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->where('deleted', 1);
        $this->db->group_by('date_format(date_delete, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_lead_stages')->result_array();
        $data_removed = [];
        foreach($email_logs as $download){
            $data_removed[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('added'), 'data' => $data_added, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('removed'), 'data' => $data_removed, 'color' => '#ff6f00'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @param  string
     * @return array
     */
    public function get_lead_by_text_message($id, $return_type = 'leads'){
        
        $this->db->select('lead_id');
        $this->db->where('text_message_id', $id);
        $this->db->group_by('lead_id');
        $email_logs = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $where = '';
        foreach ($email_logs as $key => $value) {
            if($where != ''){
                $where .= ','. $value['lead_id'];
            }else{
                $where .= $value['lead_id'];
            }
        }

        if($where != ''){
          $where = '('.db_prefix().'leads.id in ('.$where.'))';
        }else{
          $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();

            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_campaign_by_text_message($id){
        $where = 'workflow LIKE \'%\\\\\\\\"text_message\\\\\\\\":\\\\\\\\"'.$id.'\\\\\\\\"%\'';

        $this->db->where('start_date <= "'.date('Y-m-d').'" AND end_date >= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        $this->db->where('end_date <= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $old_campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        return ['campaigns' => count($campaigns), 'old_campaigns' => count($old_campaigns)];
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_text_message_by_campaign_chart($text_message_id)
    {
        $this->db->select('campaign_id');
        $this->db->where('text_message_id', $text_message_id);
        $this->db->group_by('campaign_id');
        $campaign_ids = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $data_header = [];
        $data_action = [];
        $data_open = [];
        $data_click = [];

        foreach($campaign_ids as $value){
            $campaign = $this->get_campaign($value['campaign_id']);
            $data_header[] = $campaign->name;

            $this->db->where('text_message_id', $text_message_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $count_action = $this->db->count_all_results(db_prefix().'ma_sms_logs');
            $data_action[] = $count_action;
        }

        $data_return = [];
        $data_return[] = ['name' => _l('text_message'), 'data' => $data_action, 'color' => '#008ece'];

        return ['header' => $data_header, 'data' => $data_return];
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_text_message_chart($text_message_id = '')
    {
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_action');
        if($text_message_id != ''){
            $this->db->where('text_message_id', $text_message_id);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $text_message = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $data_text_message = [];
        foreach($text_message as $action){
            $data_text_message[] = [strtotime($action['time'].' 00:00:00') * 1000, (int)$action['count_action']];
        }
        
        return $data_text_message;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_campaign_text_message_chart($campaign_id = '')
    {
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_action');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $text_message = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $data_text_message = [];
        foreach($text_message as $action){
            $data_text_message[] = [strtotime($action['time'].' 00:00:00') * 1000, (int)$action['count_action']];
        }
        
        return $data_text_message;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_campaign_point_action_chart($campaign_id = '')
    {
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_action');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $point_action = $this->db->get(db_prefix().'ma_point_action_logs')->result_array();

        $data_point_action = [];
        foreach($point_action as $action){
            $data_point_action[] = [strtotime($action['time'].' 00:00:00') * 1000, (int)$action['count_action']];
        }
        
        return $data_point_action;
    }

    /**
     * @param  string
     * @param  integer
     * @param  integer
     * @return string
     */
    public function parse_content_merge_fields($content, $lead_id = '', $log_id = ''){
        if (!class_exists('other_merge_fields', false)) {
            $this->load->library('merge_fields/other_merge_fields');
            $this->load->library('merge_fields/leads_merge_fields');
        }

        $merge_fields = [];
        $merge_fields = array_merge($merge_fields, $this->other_merge_fields->format());

        foreach ($merge_fields as $key => $val) {
            $content = stripos($content, $key) !== false
            ? str_replace($key, $val, $content)
            : str_replace($key, '', $content);
        }

        if($lead_id != ''){

            $merge_fields = array_merge($merge_fields, $this->leads_merge_fields->format($lead_id));

            foreach ($merge_fields as $key => $val) {
                $content = stripos($content, $key) !== false
                ? str_replace($key, $val, $content)
                : str_replace($key, '', $content);
            }
        }

        if($log_id != ''){
            $this->db->where('id', $log_id);
            $email_log = $this->db->get(db_prefix().'ma_email_logs')->row();
            $content = str_replace('href="', 'href="'.site_url('ma/ma_public/tracking_click?email='.$email_log->email_id.'&lead='.$email_log->lead_id.'&campaign='.$email_log->campaign_id.'&href='), $content);
            $content .= '<img alt="" src="'.base_url('modules/ma/email_tracking.php?log='.$log_id.'&image=tracking.gif').'" width="1" height="1" />';
        }

        return $content;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_form_chart($form_id = '')
    {
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_submit');
        if($form_id != ''){
            $this->db->where('form_id', $form_id);
        }
        $this->db->where('(from_ma_form_id != "" or from_ma_form_id is not null)');
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $point_action = $this->db->get(db_prefix().'leads')->result_array();

        $data_point_action = [];
        foreach($point_action as $action){
            $data_point_action[] = [strtotime($action['time'].' 00:00:00') * 1000, (int)$action['count_submit']];
        }
        
        return $data_point_action;
    }

    /**
     * { mfa setting by admin }
     *
     * @param         $data   The data
     *
     * @return     boolean  
     */
    public function ma_sms_setting($data){
        
        $affected_rows = 0;

        $setting_dt = []; 
        if(isset($data['settings'])){
            $setting_dt['settings'] = $data['settings'];
            unset($data['settings']);
        }

        if(count($setting_dt) > 0){
            $this->load->model('payment_modes_model');
            $this->load->model('settings_model');
            $succ = $this->settings_model->update($setting_dt);
            if($succ > 0){
                $affected_rows++;
            }
        }

        if($affected_rows > 0){
            return true;
        }
        return false;
    }

    /**
     * @param  integer
     * @param  string
     * @return array
     */
    public function get_lead_by_email($id, $return_type = 'leads'){
        
        $this->db->select('lead_id');
        $this->db->where('email_id', $id);
        $this->db->group_by('lead_id');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();

        $where = '';
        foreach ($email_logs as $key => $value) {
            if($where != ''){
                $where .= ','. $value['lead_id'];
            }else{
                $where .= $value['lead_id'];
            }
        }

        if($where != ''){
          $where = '('.db_prefix().'leads.id in ('.$where.'))';
        }else{
          $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();

            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_campaign_by_email($id){
        $where = 'workflow LIKE \'%\\\\\\\\"email\\\\\\\\":\\\\\\\\"'.$id.'\\\\\\\\"%\'';

        $this->db->where('start_date <= "'.date('Y-m-d').'" AND end_date >= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        $this->db->where('end_date <= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $old_campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        return ['campaigns' => count($campaigns), 'old_campaigns' => count($old_campaigns)];
    }

    /**
     * @param  integer
     * @param  array
     * @return array
     */
    public function get_data_email_chart($email_id = '', $data_filter = [])
    {
        $where = $this->get_where_report_period('date_format(dateadded, \'%Y-%m-%d\')');
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($email_id != ''){
            $this->db->where('email_id', $email_id);
        }
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_delivery = [];
        foreach($email_logs as $download){
            $data_delivery[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($email_id != ''){
            $this->db->where('email_id', $email_id);
        }
        $this->db->where('open', 1);
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_open = [];
        foreach($email_logs as $download){
            $data_open[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_email');
        if($email_id != ''){
            $this->db->where('email_id', $email_id);
        }
        $this->db->where('click', 1);
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $email_logs = $this->db->get(db_prefix().'ma_email_logs')->result_array();
        $data_click = [];
        foreach($email_logs as $download){
            $data_click[] = [strtotime($download['time'].' 00:00:00') * 1000, (int)$download['count_email']];
        }

        $data_return = [];
        $data_return[] = ['name' => _l('delivery'), 'data' => $data_delivery, 'color' => '#008ece'];
        $data_return[] = ['name' => _l('read'), 'data' => $data_open, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('click'), 'data' => $data_click, 'color' => '#ff6f00'];
        
        return $data_return;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_email_by_campaign_chart($email_id)
    {
        $this->db->select('campaign_id');
        $this->db->where('email_id', $email_id);
        $this->db->group_by('campaign_id');
        $campaign_ids = $this->db->get(db_prefix().'ma_email_logs')->result_array();

        $data_header = [];
        $data_delivery = [];
        $data_open = [];
        $data_click = [];

        foreach($campaign_ids as $value){
            $campaign = $this->get_campaign($value['campaign_id']);
            if($campaign){
                $data_header[] = $campaign->name;

                $this->db->where('email_id', $email_id);
                $this->db->where('campaign_id', $value['campaign_id']);
                $count_delivery = $this->db->count_all_results(db_prefix().'ma_email_logs');
                $data_delivery[] = $count_delivery;

                $this->db->where('email_id', $email_id);
                $this->db->where('campaign_id', $value['campaign_id']);
                $this->db->where('open', 1);
                $count_open = $this->db->count_all_results(db_prefix().'ma_email_logs');
                $data_open[] = $count_open;

                $this->db->where('email_id', $email_id);
                $this->db->where('campaign_id', $value['campaign_id']);
                $this->db->where('click', 1);
                $count_click = $this->db->count_all_results(db_prefix().'ma_email_logs');
                $data_click[] = $count_click;
            }
        }

        $data_return = [];
        $data_return[] = ['name' => _l('delivery'), 'data' => $data_delivery, 'color' => '#008ece'];
        $data_return[] = ['name' => _l('read'), 'data' => $data_open, 'color' => '#84c529'];
        $data_return[] = ['name' => _l('click'), 'data' => $data_click, 'color' => '#ff6f00'];

        return ['header' => $data_header, 'data' => $data_return];
    }

    /**
     * @param  array
     * @return boolean
     */
    public function email_design_save($data){
        if(isset($data['email_id']) && $data['email_id'] != ''){
            $this->db->where('id', $data['email_id']);
            $this->db->update(db_prefix() . 'ma_emails', ['data_html' => json_encode($data['data_html']), 'data_design' => json_encode($data['data_design'])]);

            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add new sms
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_sms($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        if ($data['sms_template'] != '') {
            $sms_template = $this->get_text_message($data['sms_template']);

            $data['content'] = $sms_template->description;
        }

        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'ma_sms', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
    
    /**
     * Get sms
     * @param  mixed $id sms id (Optional)
     * @return mixed     object or array
     */
    public function get_sms($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $sms = $this->db->get(db_prefix() . 'ma_sms')->row();

            return $sms;
        }
        
        $this->db->where('published', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'ma_sms')->result_array();
    }

    /**
     * Add new sms
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_sms($data, $id)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        if ($data['sms_template'] != '') {
            $sms_template = $this->get_text_message($data['sms_template']);
            $data['content'] = $sms_template->description;
        }

        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ma_sms', $data);

        if($this->db->affected_rows() > 0){ 
            return true;
        }
       
        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete sms from database
     */
    public function delete_sms($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ma_sms');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('sms_id', $id);
            $this->db->delete(db_prefix() . 'ma_sms_logs');

            return true;
        }

        return false;
    }

    /**
     * @param  integer
     * @param  string
     * @return array
     */
    public function get_lead_by_sms($id, $return_type = 'leads'){
        
        $this->db->select('lead_id');
        $this->db->where('sms_id', $id);
        $this->db->group_by('lead_id');
        $email_logs = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $where = '';
        foreach ($email_logs as $key => $value) {
            if($where != ''){
                $where .= ','. $value['lead_id'];
            }else{
                $where .= $value['lead_id'];
            }
        }

        if($where != ''){
          $where = '('.db_prefix().'leads.id in ('.$where.'))';
        }else{
          $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();

            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_campaign_by_sms($id){
        $where = 'workflow LIKE \'%\\\\\\\\"sms\\\\\\\\":\\\\\\\\"'.$id.'\\\\\\\\"%\'';

        $this->db->where('start_date <= "'.date('Y-m-d').'" AND end_date >= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        $this->db->where('end_date <= "'.date('Y-m-d').'"');
        $this->db->where($where);
        $old_campaigns = $this->db->get(db_prefix().'ma_campaigns')->result_array();

        return ['campaigns' => count($campaigns), 'old_campaigns' => count($old_campaigns)];
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_sms_by_campaign_chart($sms_id)
    {
        $this->db->select('campaign_id');
        $this->db->where('sms_id', $sms_id);
        $this->db->group_by('campaign_id');
        $campaign_ids = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $data_header = [];
        $data_action = [];
        $data_open = [];
        $data_click = [];

        foreach($campaign_ids as $value){
            $campaign = $this->get_campaign($value['campaign_id']);
            $data_header[] = $campaign->name;

            $this->db->where('sms_id', $sms_id);
            $this->db->where('campaign_id', $value['campaign_id']);
            $count_action = $this->db->count_all_results(db_prefix().'ma_sms_logs');
            $data_action[] = $count_action;
        }

        $data_return = [];
        $data_return[] = ['name' => _l('sms'), 'data' => $data_action, 'color' => '#008ece'];

        return ['header' => $data_header, 'data' => $data_return];
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_sms_chart($sms_id = '')
    {
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_action');
        if($sms_id != ''){
            $this->db->where('sms_id', $sms_id);
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $sms = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $data_sms = [];
        foreach($sms as $action){
            $data_sms[] = [strtotime($action['time'].' 00:00:00') * 1000, (int)$action['count_action']];
        }
        
        return $data_sms;
    }

    /**
     * @param  integer
     * @return array
     */
    public function get_data_campaign_sms_chart($campaign_id = '')
    {
        $this->db->select('date_format(dateadded, \'%Y-%m-%d\') as time, COUNT(*) as count_action');
        if($campaign_id != ''){
            $this->db->where('campaign_id', $campaign_id);
        }else{
            $this->db->where('campaign_id > 0');
        }
        $this->db->group_by('date_format(dateadded, \'%Y-%m-%d\')');
        $sms = $this->db->get(db_prefix().'ma_sms_logs')->result_array();

        $data_sms = [];
        foreach($sms as $action){
            $data_sms[] = [strtotime($action['time'].' 00:00:00') * 1000, (int)$action['count_action']];
        }
        
        return $data_sms;
    }

    /**
     * Send email - No templates used only simple string
     * @since Version 1.0.2
     * @param  string $email   email
     * @param  string $ma_email_object email object
     * @param  integer $log_id   email log ID
     * @return boolean
     */
    public function ma_send_email($email, $ma_email_object, $lead_id = '',$log_id = '')
    {   
        $this->load->model('emails_model');

        $subject = $ma_email_object->subject;
        $message = $this->parse_content_merge_fields(json_decode($ma_email_object->data_html), $lead_id, $log_id);

        $from_name = get_option('companyname');
        if($ma_email_object->from_name != ''){
            $from_name = $ma_email_object->from_name;
        }

        $from_email = get_option('smtp_email');
        if($ma_email_object->from_address != ''){
            $from_name = $ma_email_object->from_address;
        }

        $bcc_address = '';
        if($ma_email_object->bcc_address != ''){
            $bcc_address = $ma_email_object->bcc_address;
        }

        $reply_to = '';
        if($ma_email_object->reply_to_address != ''){
            $reply_to = $ma_email_object->reply_to_address;
        }

        $cnf = [
            'from_email' => $from_email,
            'from_name'  => $from_name,
            'email'      => $email,
            'subject'    => $subject,
            'message'    => $message,
            'bcc'    => $bcc_address,
            'reply_to'    => $reply_to,
        ];

        $cnf['message'] = check_for_links($cnf['message']);

        $this->load->config('email');
        $this->email->clear(true);
        $this->email->set_newline(config_item('newline'));
        $this->email->from($cnf['from_email'], $cnf['from_name']);
        $this->email->to($cnf['email']);

        $bcc = '';
        // Used for action hooks
        if (isset($cnf['bcc']) && $cnf['bcc'] != '') {
            $bcc = $cnf['bcc'];
            if (is_array($bcc)) {
                $bcc = implode(', ', $bcc);
            }
        }

        $systemBCC = get_option('bcc_emails');
        if ($systemBCC != '') {
            if ($bcc != '') {
                $bcc .= ', ' . $systemBCC;
            } else {
                $bcc .= $systemBCC;
            }
        }
        if ($bcc != '') {
            $this->email->bcc($bcc);
        }

        if (isset($cnf['cc'])) {
            $this->email->cc($cnf['cc']);
        }

        if (isset($cnf['reply_to']) && $cnf['reply_to'] != '') {
            $this->email->reply_to($cnf['reply_to']);
        }

        $this->email->subject($cnf['subject']);
        $this->email->message($cnf['message']);

        $this->email->set_alt_message(strip_html_tags($cnf['message'], '<br/>, <br>, <br />'));

        $success = $this->email->send();

        if ($success) {
            if($log_id != ''){
                log_activity('Email sent to: ' . $cnf['email'] . ' Subject: ' . $cnf['subject']);
                $this->db->where('id', $log_id);
                $this->db->update(db_prefix().'ma_email_logs', ['delivery' => 1, 'delivery_time' => date('Y-m-d H:i:s')]);
            }

            return true;
        }else{
            if($log_id != ''){
                $this->db->where('id', $log_id);
                $this->db->update(db_prefix().'ma_email_logs', ['failed' => 1, 'failed_time' => date('Y-m-d H:i:s')]);
            }
        }

        return false;
    }

    /**
     * @param  array
     * @param  integer
     * @param  string
     * @return boolean
     */
    public function check_condition_email($data, $email_id, $type){
        $this->db->where('lead_id', $data['lead']['id']);
        $this->db->where('campaign_id', $data['campaign']->id);
        $this->db->where('email_id', $email_id);
        $this->db->where($type, 1);
        $check = $this->db->get(db_prefix().'ma_email_logs')->row();
        if($check){
            return true;
        }

        return false;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function clone_email_template($data){
        
        $email_template = $this->get_email_template($data['id']);
        $data_insert = (array)$email_template;

        unset($data_insert['id']);
        $data_insert['name'] = $data['name'];
        $data_insert['addedfrom'] = get_staff_user_id();
        $data_insert['dateadded'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix().'ma_email_templates', $data_insert);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }
}