<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
class Idea_hub_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    /*
    * Get all category
    */
    public function get_categories()
    {
        $query = $this->db->get(db_prefix() . 'idea_hub_category');
        return $query->result_array();
    }
    /*
    * Add new category
    */
    public function add_category($data)
    {
        $this->db->insert(db_prefix() . 'idea_hub_category', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }
    /*
    * Update category
    */
    public function update_category($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'idea_hub_category', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /*
    * Remove category
    */
    public function remove_category($id)
    {
        if (is_reference_in_table('category_id', db_prefix() . 'idea_hub_challenges', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'idea_hub_category');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /*
    * New challenge
    */
    public function add_challenge($data)
    {
        $this->db->insert(db_prefix() . 'idea_hub_challenges', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }
    /*
    * Update challenge by id
    */
    public function update_challenge($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'idea_hub_challenges', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /*
    * Get challenge by id
    */
    public function get_challenge_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get(db_prefix() . 'idea_hub_challenges');
        return $query->row();
    }
    /*
    * All stages
    */
    public function get_stages()
    {
        $stages = $this->db->get(db_prefix() . 'idea_hub_stages')->result_array();
        return $stages;
    }
    /*
    * Add new stage
    */
    public function add_stage($data)
    {
        $this->db->insert(db_prefix() . 'idea_hub_stages', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }
    /*
    * Update stage by id
    */
    public function update_stage($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'idea_hub_stages', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /*
    * Remove stage by id
    */
    public function remove_stage($id)
    {
        if (is_reference_in_table('stage_id', db_prefix() . 'idea_hub_ideas', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'idea_hub_stages');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /*
    * Get stage by id
    */
    public function get_stage_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get(db_prefix() . 'idea_hub_stages');
        return $query->row();
    }
    /*
    * All status
    */
    public function get_statuses()
    {
        $statuses = $this->db->get(db_prefix() . 'idea_hub_status')->result_array();
        return $statuses;
    }
    /*
    * Add new status
    */
    public function add_status($data)
    {
        $this->db->insert(db_prefix() . 'idea_hub_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }
    /*
    * update status
    */
    public function update_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'idea_hub_status', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /*
    * Remove status
    */
    public function remove_status($id)
    {
        if (is_reference_in_table('status_id', db_prefix() . 'idea_hub_ideas', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'idea_hub_status');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
        /*
    * Add new idea
    */
    public function add_idea($data)
    {
        $tags = explode(',', $data['tags']);
        unset($data['tags']);
        unset($data['clients']);
        $this->db->insert(db_prefix() . 'idea_hub_ideas', $data);
        $insert_id = $this->db->insert_id();
        if($insert_id) {
            if(count(array_filter($tags)) == count($tags)){
                $countTags = count($tags);
                if($countTags > 0) {
                    for($i=0; $i < $countTags; $i++) { 
                        $idea_tag_id = $this->add_idea_tags(array('idea_id' => $insert_id, 'tag_name' => $tags[$i]));
                    }
                }    
            }
            return $insert_id;
        }
        return false;
    }
    /*
    * Get idea by id
    */
    public function get_idea_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get(db_prefix() . 'idea_hub_ideas');
        return $query->row();
    }
    /*
    * Update idea by id
    */
    public function update_idea($data, $id)
    {
        $tag = false;
        $tags = null;
        if(isset($data['tags']) && !empty($data['tags'])){
            $tags = explode(',', $data['tags']);
            $tag = true;
        }
        unset($data['tags']);
        unset($data['clients']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'idea_hub_ideas', $data);
        if($tag){
            $this->db->where('idea_id', $id);
            $this->db->delete(db_prefix() . 'idea_hub_ideas_tags'); 
            $countTags = count($tags);
            if ($countTags > 0) {
                for ($i=0; $i < $countTags; $i++) { 
                    $idea_tag_id = $this->add_idea_tags(array('idea_id' => $id, 'tag_name' => $tags[$i]));
                }
            }
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /*
    * Get All ideas
    */
    public function get_ideas($where)
    {
        if($where != '') {
            $this->db->where($where);
        }
        $query = $this->db->get(db_prefix() . 'idea_hub_ideas');
        return $query->result_array();
    }
    public function add_idea_tags($data)
    {
        $this->db->insert(db_prefix() . 'idea_hub_ideas_tags', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }
    public function add_idea_attachments($data)
    {
        if(!empty($data)){
            $countAtt = count($data);
            if ($countAtt > 0) {
                for ($i=0; $i < $countAtt; $i++) { 
                    $this->db->insert(db_prefix() . 'idea_hub_attachments', $data[$i]);
                }  
                return true; 
            }else{
                return false;
            }
        }
    }
    public function getCoverImagePath($id){
        $url = '';
        $this->db->where('id', $id);
        $query = $this->db->get(db_prefix() . 'idea_hub_ideas');
        $data = $query->row();
        if($data->cover_type ==  'image'){
            $url = base_url('modules/idea_hub/uploads/ideas/'.$data->image);
        }elseif($data->cover_type ==  'video'){
            $url = base_url('modules/idea_hub/uploads/ideas/v_thumbnails/'.$data->video_thumbnail);
        }
        return $url;
    }
    public function get_idea_total_rank($idea_id)
    {
        $result = 0;
        if ($idea_id) {
            $result = $this->db->select_sum('rank')->from(db_prefix() . 'idea_hub_ideas_votes')->where('idea_id', $idea_id)->get()->row();
        }
        return html_escape($result && $result->rank ? $result->rank : 0);
    }
    public function get_idea_rank($idea_id)
    {
        $result = null;
        if ($idea_id) {
            $user_id = get_staff_user_id();
            if(!$user_id){
                $user_id = get_client_user_id();
            }
            $result = $this->db->select('rank')->from(db_prefix() . 'idea_hub_ideas_votes')->where(array('idea_id' => $idea_id, 'user_id' => $user_id))->get()->row();
        }
        return html_escape($result ? $result->rank : 0);
    }
    public function get_idea_vote_count($idea_id)
    {
        $result = null;
        if ($idea_id) {
            $result = $this->db->select('count(user_id) as votes')->from(db_prefix() . 'idea_hub_ideas_votes')->where('idea_id', $idea_id)->get()->row();
        }
        return html_escape(isset($result) &&  $result->votes ? $result->votes : 0);
    }
    public function get_idea_comments($id, $type)
    {
        $this->db->where('idea_id', $id);
        $this->db->where('discussion_type', $type);
        $comments = $this->db->get(db_prefix() . 'idea_hub_ideas_comments')->result_array();
        $i                    = 0;
        $allCommentsIDS       = [];
        $allCommentsParentIDS = [];
        foreach ($comments as $comment) {                       
            $str = '';
            $allCommentsIDS[] = $comment['id'];
            if (!empty($comment['parent'])) {
                $allCommentsParentIDS[] = $comment['parent'];
            }
            if ($comment['contact_id'] != 0) {
                if (is_client_logged_in()) {
                    if ($comment['contact_id'] == get_contact_user_id()) {
                        $comments[$i]['created_by_current_user'] = true;
                    } else {
                        $comments[$i]['created_by_current_user'] = false;
                    }
                } else {
                    $comments[$i]['created_by_current_user'] = false;
                }
                $comments[$i]['profile_picture_url'] = contact_profile_image_url($comment['contact_id']);
            } else {
                if (is_client_logged_in()) {
                    $comments[$i]['created_by_current_user'] = false;
                } else {
                    if (is_staff_logged_in()) {
                        if ($comment['user_id'] == get_staff_user_id()) {
                            $comments[$i]['created_by_current_user'] = true;
                        } else {
                            $comments[$i]['created_by_current_user'] = false;
                        }
                    } else {
                        $comments[$i]['created_by_current_user'] = false;
                    }
                }
                if (is_admin($comment['user_id'])) {
                    $comments[$i]['created_by_admin'] = true;
                } else {
                    $comments[$i]['created_by_admin'] = false;
                }
                $comments[$i]['profile_picture_url'] = staff_profile_image_url($comment['user_id']);
            }
            if (!is_null($comment['file_name'])) {
                $comments[$i]['file_url'] = site_url('modules/idea_hub/uploads/ideas/discussions/attachment/' . $id . '/' . $comment['file_name']);
            }
            $comments[$i]['created'] = (strtotime($comment['created']) * 1000);
            if (!empty($comment['modified'])) {
                $comments[$i]['modified'] = (strtotime($comment['modified']) * 1000);
            }
            $i++;
        }
        foreach ($allCommentsParentIDS as $parent_id) {
            if (!in_array($parent_id, $allCommentsIDS)) {
                foreach ($comments as $key => $comment) {
                    if ($comment['parent'] == $parent_id) {
                        $comments[$key]['parent'] = null;
                    }
                }
            }
        }
        return $comments;
    }
    public function idea_rank_update($data)
    {
        $result = $this->db->get_where(db_prefix() . 'idea_hub_ideas_votes', array('idea_id' => $data['idea_id'], 'user_id' => $data['user_id']));
        $post_data = $data;
        if($result->num_rows() > 0){
            unset($post_data['user_id']);
            unset($post_data['idea_id']);
            $this->db->where(array('user_id' => $data['user_id'], 'idea_id' => $data['idea_id']));
            $this->db->update(db_prefix() . 'idea_hub_ideas_votes', $post_data);
            return $this->db->affected_rows();
        }else{
            $this->db->insert(db_prefix() . 'idea_hub_ideas_votes', $data);
            return $this->db->insert_id();
        }
    }
    public function add_idea_custom_visibility($data, $idea_id, $action='')
    {
        $post_data = array();
        if($action == 'edit'){
            $this->delete_idea_visibility($idea_id);
        }
        if(isset($data) && !empty($data)){
            $i = 0;
            foreach ($data as $userId) {
                $post_data[$i]['customer_id'] = $userId;
                $post_data[$i]['idea_id'] = $idea_id;
                $i++;
            }
        }
        if($this->db->insert_batch(db_prefix() . 'idea_hub_ideas_visibility', $post_data)){
            return true;
        }
    }
    public function delete_idea_visibility($idea_id)
    {
        $this->db->where('idea_id', $idea_id);
        $this->db->delete(db_prefix() . 'idea_hub_ideas_visibility');
        if ($this->db->affected_rows() > 0) {
            return true;
        }     
    }
    public function do_kanban_query($status, $search = '', $page = 1, $count = false, $where = [])
    {
        $tasks_where = '';
        $this->db->select('*, '.db_prefix().'idea_hub_ideas.user_id as user_id, '.db_prefix().'idea_hub_status.name as status_name, '.db_prefix().'idea_hub_stages.name as stage_name,'.db_prefix().'staff.firstname, '.db_prefix().'staff.lastname,'.db_prefix().'idea_hub_ideas.id as idea_id');
        $this->db->join(db_prefix() . 'idea_hub_stages', db_prefix() . 'idea_hub_stages.id = '.db_prefix() . 'idea_hub_ideas.stage_id', 'LEFT');
        $this->db->join(db_prefix() . 'idea_hub_status', db_prefix() . 'idea_hub_status.id = '.db_prefix() . 'idea_hub_ideas.status_id', 'LEFT');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = '.db_prefix() . 'idea_hub_ideas.user_id', 'LEFT');
        $this->db->from(db_prefix() . 'idea_hub_ideas');
        $this->db->where('stage_id', $status);
        $this->db->where($where);
        if ($tasks_where != '') {
            $this->db->where($tasks_where);
        }
        if ($search != '') {
            if (!startsWith($search, '#')) {
                $this->db->where('(' . db_prefix() . 'idea_hub_ideas.title LIKE "%' . $this->db->escape_like_str($search) . '%" ESCAPE \'!\'  OR ' . db_prefix() . 'idea_hub_ideas.description LIKE "%' . $this->db->escape_like_str($search) . '%" ESCAPE \'!\')');
            } else {
            }
        }
        $this->db->order_by(db_prefix().'idea_hub_ideas.id', 'asc');
        if ($count == false) {
            if ($page > 1) {
                $page--;
                $position = ($page * get_option('tasks_kanban_limit'));
                $this->db->limit(get_option('tasks_kanban_limit'), $position);
            } else {
                $this->db->limit(get_option('tasks_kanban_limit'));
            }
        }
        if ($count == false) {
            return $this->db->get()->result_array();
        }
        return $this->db->count_all_results();
    }
	public function get_visible_customers_ids($id)
    {
        $data = array();
        if($id){
            $result = $this->db->get_where(db_prefix() . 'idea_hub_ideas_visibility', array('idea_id' => $id));
            $i = 0;
            foreach ($result->result_array() as $col) {
                $data[$i] = $col['customer_id'];
                $i++;
            }
            return implode(',', $data);
        }
    }
    public function challenge_vote_like_dislike($data)
    {
        $result = $this->db->get_where(db_prefix() . 'idea_hub_challenges_votes', array('challenge_id' => $data['challenge_id'], 'user_id' => $data['user_id'], 'user_type' => $data['user_type']));
        $post_data = $data;
        if($result->num_rows() > 0){
            unset($post_data['user_id']);
            unset($post_data['user_type']);
            unset($post_data['challenge_id']);
            $this->db->where(array('user_id' => $data['user_id'], 'user_type' => $data['user_type'], 'challenge_id' => $data['challenge_id']));
            $this->db->update(db_prefix() . 'idea_hub_challenges_votes', $post_data);
            return $this->count_challenge_votes($data['challenge_id']);
        }else{
            if($this->db->insert(db_prefix() . 'idea_hub_challenges_votes', $data)){
                return $this->count_challenge_votes($data['challenge_id']);
            }
        }
    }
    public function count_challenge_votes($challenge_id)
    {
        $query = $this->db->query("SELECT COUNT(id) as total, (SELECT COUNT(id) FROM ".db_prefix() . 'idea_hub_challenges_votes'." WHERE vote = 'up' and challenge_id = ".$challenge_id.") as up, (SELECT COUNT(id) FROM ".db_prefix() . 'idea_hub_challenges_votes'." WHERE vote = 'down' and challenge_id = ".$challenge_id.") as down  FROM ".db_prefix() . 'idea_hub_challenges_votes'." WHERE challenge_id = ".$challenge_id);
        $result = $query->result_array();
        return $result[0];
    }
    public function delete_challenge($id)
    {
        $this->db->where('id',$id);
        if($this->db->delete(db_prefix().'idea_hub_challenges')){
            $this->delete_challenge_uploads($id);
            $ideaIds = $this->get_idea_id_by_challenge_id($id);
            if(isset($ideaIds) && !empty($ideaIds)){
                $this->delete_idea_uploads($ideaIds);
                $this->db->where_in('id',$ideaIds);
                if($this->db->delete(db_prefix().'idea_hub_ideas')){
                    $this->db->where_in('idea_id',$ideaIds);
                    $this->db->delete(db_prefix().'idea_hub_ideas_comments');
                    $this->db->where_in('idea_id',$ideaIds);
                    $this->db->delete(db_prefix().'idea_hub_ideas_visibility');
                    $this->db->where_in('idea_id',$ideaIds);
                    $this->db->delete(db_prefix().'idea_hub_attachments');
                    $this->db->where_in('idea_id',$ideaIds);
                    $this->db->delete(db_prefix().'idea_hub_ideas_tags');
                    $this->db->where_in('idea_id',$ideaIds);
                    $this->db->delete(db_prefix().'idea_hub_ideas_votes');
                    return true;
                }    
            }
        }
    }
    public function delete_challenge_uploads($id)
    {
        $result = $this->db->select('cover_image')->from(db_prefix() . 'idea_hub_challenges')->where('id',$id)->get()->row();
        if(isset($result) && !empty($result)){
            $path = 'challenges/'.$result->cover_image;
            $success = delete_uploaded_files($path);
        }
    }
    public function get_idea_id_by_challenge_id($id)
    {
        $result = $this->db->select('concat(id) as ids')->from(db_prefix().'idea_hub_ideas')->where('challenge_id',$id)->get()->row();
        return html_escape(isset($result) &&  $result->ids ? $result->ids : null);
    }
    /*
    * Delete idea by id
    */
    public function delete_idea($idea_id)
    {
        if(isset($idea_id) && !empty($idea_id)){
            $this->delete_idea_uploads($idea_id);
            $this->db->where('id',$idea_id);
            if($this->db->delete(db_prefix().'idea_hub_ideas')){
                $this->db->where('idea_id',$idea_id);
                $this->db->delete(db_prefix().'idea_hub_ideas_comments');
                $this->db->where('idea_id',$idea_id);
                $this->db->delete(db_prefix().'idea_hub_ideas_visibility');
                $this->db->where('idea_id',$idea_id);
                $this->db->delete(db_prefix().'idea_hub_attachments');
                $this->db->where('idea_id',$idea_id);
                $this->db->delete(db_prefix().'idea_hub_ideas_tags');
                $this->db->where('idea_id',$idea_id);
                $this->db->delete(db_prefix().'idea_hub_ideas_votes');
                return true;
            }    
        }
    }
    public function delete_idea_uploads($allids)
    {
        $ids = explode(',', $allids);
        foreach ($ids as $id) {
            $result = $this->db->select('image')->from(db_prefix() . 'idea_hub_ideas')->where('id',$id)->get()->row();
            if(isset($result) && !empty($result)){
                $path = 'ideas/'.$result->image;
                $success = file_exists($path) ? unlink_file($path) : false;
            }
            $result = $this->db->select('file_name')->from(db_prefix() . 'idea_hub_ideas_comments')->where('idea_id',$id)->get()->result_array();
            if(isset($result) && !empty($result)){
                foreach ($result as $value) {
                    if(array_key_exists('idea_id',$value)){
                        $path = 'ideas/discussions/attachment/'.$value['idea_id'];
                        $success = file_exists($path) ? unlink_file($path) : false;
                    }
                }
            }
            $result = $this->db->select('file_name')->from(db_prefix() . 'idea_hub_attachments')->where('idea_id',$id)->get()->result_array();
            if(isset($result) && !empty($result)){
                foreach ($result as $value) {
                    if(array_key_exists('idea_id',$value)){
                        $path = 'ideas/attachment/'.$value['idea_id'];
                        $success = file_exists($path) ? unlink_file($path) : false;    
                    }
                }
            }
        }
    }
    public function add_discussion_comment($data, $idea_id, $type)
    {
        $_data['idea_id']        = $idea_id;
        $_data['discussion_type']   = $type;
        if (isset($data['content'])) {
            $_data['content'] = $data['content'];
        }
        if (isset($data['parent']) && $data['parent'] != null) {
            $_data['parent'] = $data['parent'];
        }
        if (is_client_logged_in()) {
            $_data['user_id']    = get_client_user_id();
            $_data['contact_id'] = get_contact_user_id();
            $_data['user_type']  =  'customer';
            $_data['fullname']   = get_contact_full_name($_data['contact_id']);
        } else {
            $_data['user_id']    = get_staff_user_id();
            $_data['contact_id'] = 0;
            $_data['user_type']  = 'staff';
            $_data['fullname']   = get_staff_full_name($_data['user_id']);
        }
        $_data                   = handle_idea_comment_attachments($idea_id, $data, $_data);
        $_data['created']        = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'idea_hub_ideas_comments', $_data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if ($type == 'regular') {
            } else {
                $discussion                   = $this->get_file($idea_id);
                $discussion->show_to_customer = $discussion->visible_to_customer;
            }
            return $this->get_discussion_comment($insert_id);
        }
        return false;
    }
    public function update_discussion_comment($data)
    {
        $comment = $this->get_discussion_comment($data['id']);
        $this->db->where('id', $data['id']);
        $this->db->update(db_prefix() . 'idea_hub_ideas_comments', [
            'modified' => date('Y-m-d H:i:s'),
            'content'  => $data['content'],
        ]);
        if ($this->db->affected_rows() > 0) {
        }
        return $this->get_discussion_comment($data['id']);
    }
    public function get_discussion_comment($id)
    {
        $this->db->where('id', $id);
        $comment = $this->db->get(db_prefix() . 'idea_hub_ideas_comments')->row();
        if ($comment->contact_id != 0) {
            if (is_client_logged_in()) {
                if ($comment->contact_id == get_contact_user_id()) {
                    $comment->created_by_current_user = true;
                } else {
                    $comment->created_by_current_user = false;
                }
            } else {
                $comment->created_by_current_user = false;
            }
            $comment->profile_picture_url = contact_profile_image_url($comment->contact_id);
        } else {
            if (is_client_logged_in()) {
                $comment->created_by_current_user = false;
            } else {
                if (is_staff_logged_in()) {
                    if ($comment->user_id == get_staff_user_id()) {
                        $comment->created_by_current_user = true;
                    } else {
                        $comment->created_by_current_user = false;
                    }
                } else {
                    $comment->created_by_current_user = false;
                }
            }
            if (is_admin($comment->user_id)) {
                $comment->created_by_admin = true;
            } else {
                $comment->created_by_admin = false;
            }
            $comment->profile_picture_url = staff_profile_image_url($comment->user_id);
        }
        $comment->created = (strtotime($comment->created) * 1000);
        if (!empty($comment->modified)) {
            $comment->modified = (strtotime($comment->modified) * 1000);
        }
        if (!is_null($comment->file_name)) {
            $comment->file_url = site_url('modules/idea_hub/uploads/ideas/discussions/attachment/' . $comment->idea_id . '/' . $comment->file_name);
        }
        return $comment;
    }
    public function get_idea_comment_count($idea_id)
    {
        if ($idea_id) {
            $result = $this->db
                        ->select('count(content) as content')
                        ->from(db_prefix() . 'idea_hub_ideas_comments')
                        ->where('idea_id', $idea_id)
                        ->get()
                        ->row();
            return html_escape(isset($result) &&  $result->content ? $result->content : 0);
        }else{
            return false;
        }
    }
    public function delete_discussion_comment($id, $logActivity = true)
    {
        $comment = $this->get_discussion_comment($id);
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'idea_hub_ideas_comments');
        if ($this->db->affected_rows() > 0) {
            $this->delete_discussion_comment_attachment($comment->file_name, $comment->idea_id);
        }
        $this->db->where('parent', $id);
        $this->db->update(db_prefix() . 'idea_hub_ideas_comments', [
            'parent' => null,
        ]);
        return true;
    }
    public function delete_discussion_comment_attachment($file_name, $idea_id)
    {
        $path = CONIMP_DISCUSSION_ATTACHMENT_FOLDER . $idea_id;
        if (!is_null($file_name)) {
            if (file_exists($path . '/' . $file_name)) {
                unlink($path . '/' . $file_name);
            }
        }
        if (is_dir($path)) {
            $other_attachments = list_files($path);
            if (count($other_attachments) == 0) {
                delete_dir($path);
            }
        }
    }
	public function update_idea_stage($data)
    {
        $this->db->where('id', $data['idea_id']);
        $this->db->update(db_prefix() . 'idea_hub_ideas', ['stage_id' => $data['stage_id']]);
        if($this->db->affected_rows() > 0){
            return true;
        }
    }
}