<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Idea_hub extends AdminController
{
    /*
    * Loading models and global things for this module
    */
    public function __construct()
    {
        parent::__construct();
		if(!global_module_permission()){
			access_denied('idea_hub');
		}
        $this->load->model('idea_hub_model');
        $this->app_css->add('challenge-css','modules/idea_hub/assets/css/challenge-style.css');
        $this->app_scripts->add('challenge-js','modules/idea_hub/assets/js/challenge.js');
    }

    /*
    * Main page for module and loading challenges
    */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $data['clientid'] = 0;
            if(!empty($this->input->post('catids'))){
                $catids = json_decode($this->input->post('catids'));
                $data['catids'] = $catids;
            }
            $this->app->get_table_data(module_views_path('idea_hub', 'admin/challenges/challenge_table'));
        }
        $view = $this->input->get('view');    
        if(!empty($view)){
            $this->session->set_userdata([
                'view_type_i' => $view,
            ]);
        }
        $view_type = $this->session->userdata('view_type_i') ?? 'list';
        $data['view_type']  = $view_type;
        $data['categories'] = $this->idea_hub_model->get_categories();
        $data['title'] = _l('idea_hub');
        $this->load->view('admin/challenges/manage', $data);
    }

       /*
    * challenge grid load
    */
    public function challenge_grid()
    {
        if ( !empty($this->input->post('catids')) ) {
            $catids = json_decode($this->input->post('catids'));
            echo $this->load->view('admin/challenges/challenge_grid', ['catids' => $catids], true);
        } else {
            echo $this->load->view('admin/challenges/challenge_grid', [], true);
        }
    }

    /*
    * challenge new/edit form
    */

    public function challenge($id = ''){
        if (!has_permission('idea_hub', '', 'view')) {
            access_denied('idea_hub');
        }
        if ($this->input->post()) {
            $post_data     		   = $this->input->post();
			if($post_data['status']=='inactive'){
				$post_data['deadline'] = '';
			}else{
				$post_data['deadline'] = to_sql_date($post_data['deadline'],true);
			}
            if ($id == '') {
                $post_data['user_id'] = get_staff_user_id();
                if (!has_permission('idea_hub', '', 'create')) {
                    access_denied('idea_hub');
                }
                $post_data['cover_image'] = handle_challenge_cover_image_upload(FCPATH .'modules/idea_hub/uploads/challenges');
                $id = $this->idea_hub_model->add_challenge($post_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('challenge')));
                    redirect(admin_url('idea_hub/'));
                }
            } else {
                if (!has_permission('idea_hub', '', 'edit')) {
                    access_denied('idea_hub');
                }
               
                $post_data['cover_image'] = handle_challenge_cover_image_upload(FCPATH .'modules/idea_hub/uploads/challenges');
                $success = $this->idea_hub_model->update_challenge($post_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('challenge')));
                }
                redirect(admin_url('idea_hub/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('challenge'));
        } else {
            $data['challenge']  = $this->idea_hub_model->get_challenge_by_id($id);
            $title = _l('edit', _l('challenge'));
        }
        $data['title']                 = $title;
        $this->load->view('admin/challenges/challenge', $data);
    }

    /*
    * challenge details by id
    */
    public function challenge_detail($id='')
    {
        if(!empty($id)){
            $challenge = $this->idea_hub_model->get_challenge_by_id($id);
            if (!$challenge) {
                echo _l('challenge_not_found'); die;
            }
            $data['challenge']        = $challenge;
            $data['challenge_id']     = $id;
            $data['members']           = $this->staff_model->get('', ['active' => 1]);
            $data['ranking']           = 0;
            $data['vote_count']        = 0;
            $this->load->view('admin/challenges/challenge_detail', $data);
        }else{
            die('No challenge found');
        }
    }

    /*
    * ideas by challenge id
    */
    public function ideas($challenge_id = '')
    {
        if(!is_numeric($challenge_id)){
            access_denied();
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('idea_hub', 'admin/ideas/table'));
        }
        
        $this->load->model('staff_model');
        $this->load->model('clients_model');
        
        $data['categories']     = $this->idea_hub_model->get_categories();
        $data['title']          = _l('idea_hub');
        $data['stages']         = $this->idea_hub_model->get_stages();
        $data['statuses']       = $this->idea_hub_model->get_statuses();
        $data['challenge_id']  = $challenge_id;
        $data['challenge']     = $this->idea_hub_model->get_challenge_by_id($challenge_id);
        if($data['challenge']->status == 'inactive' && get_staff_user_id() != $data['challenge']->user_id){
            set_alert('danger',_l('challenge_time_expired'));
            redirect(admin_url('idea_hub'));
        }
        $data['ranking']        = 0;
        $data['vote_count']     = 0;
        $data['ivoteCount']     = $this->idea_hub_model->count_challenge_votes($challenge_id);
        $view = $this->input->get('view');    
        if(!empty($view)){
            $this->session->set_userdata([
                'view_type_c' => $view,
            ]);
        }
        $view_type = $this->session->userdata('view_type_c') ?? 'list';
        $data['view_type']  = $view_type;
        if ($view_type == 'tree') {
			$where = ('challenge_id ='.$challenge_id);
			if(is_admin()){
				$where .=(' AND (visibility = "public" OR ' .db_prefix(). 'idea_hub_ideas.user_id = '.get_staff_user_id().')');
			}else{
				if(!has_permission('idea_hub', '', 'view')){
					$where .=(' AND (visibility = "public" OR ' .db_prefix(). 'idea_hub_ideas.user_id = '.get_staff_user_id().')');
				}else{
					$where .=(' AND (visibility = "public" OR visibility = "custom")');
				}
			}
			$data['ideas']       = $this->idea_hub_model->get_ideas($where);
            $this->load->view('admin/ideas/tree_view', $data);
        }elseif($view_type == 'grid'){
            $this->load->view('admin/ideas/grid_view', $data);
        }elseif($view_type == 'kanban'){
            $this->load->view('admin/ideas/kanban_view', $data);
        }else{
            $this->load->view('admin/ideas/manage', $data);
        }
    }

    /*
    * idea Kanban View
    */
    public function idea_kanban_view($challenge_id)
    {
        $data['stages']         = $this->idea_hub_model->get_stages();
        $data['challenge_id']   = $challenge_id;
        echo $this->load->view('idea_hub/admin/ideas/kanban', $data, true);
    }
      
    public function grid_view()
    {
        $this->load->view('admin/challenges/grid_view');
    }
    
    /*
    * idea grid
    */
    public function ideas_grid()
    {
        $data = [];
        $challenge_id = !empty($this->input->post('challenge_id')) ? $this->input->post('challenge_id') : 0;
        $data['challenge_id'] = $challenge_id;

        if(!empty($this->input->post('catids')) ) {
            $catids = json_decode($this->input->post('catids'));
            $data['catids'] = $catids;
        }
        echo $this->load->view('admin/ideas/grid', $data, true); die();
    }

    /*
    * idea table view
    */
    public function idea_table($challenge_id, $clientid = '')
    {
        $data['clientid']       = $clientid;
        $data['challenge_id']   = $challenge_id;

        if ( !empty($this->input->post('catids')) ) {
            $catids = json_decode($this->input->post('catids'));
            $data['catids'] = $catids;
        }
        
        $this->app->get_table_data(module_views_path('idea_hub', 'admin/ideas/table'), $data);
    }

    public function idea_detail($id='')
    {
        if(!is_numeric($id)){
            access_denied();
        }
        $this->app_css->add('jquery-comments-css',base_url('assets/plugins/jquery-comments/css/jquery-comments.css'));  

        $this->app_scripts->add('jquery-comments-js',base_url('assets/plugins/jquery-comments/js/jquery-comments.js'));
        if(!empty($id)){
            $idea = $this->idea_hub_model->get_idea_by_id($id);
            if (!$idea) {
                echo _l('idea_not_found'); die;
            }
            $data['idea']      = $idea;
            $data['title']        = _l('ideas');
            $data['members']      = $this->staff_model->get('', ['active' => 1]);
            $data['ranking']      = $this->idea_hub_model->get_idea_total_rank($idea->id);
            $data['rank']         = $this->idea_hub_model->get_idea_rank($idea->id);
            $data['vote_count']   = $this->idea_hub_model->get_idea_vote_count($idea->id);
            $data['challenge']   = $this->idea_hub_model->get_challenge_by_id($idea->challenge_id);
            $this->load->view('admin/ideas/grid_details', $data);
        }else{
            die('No idea found');
        }   
    }

    /*
    * Add/edit idea
    */
    public function idea($challenge_id = '', $id = '')
    {
        if(!is_numeric($challenge_id)){
            access_denied();
        }
        $challenge = $this->idea_hub_model->get_challenge_by_id($challenge_id);
        if(!ci_check_deadline_is_greater($challenge->deadline)){
            set_alert('danger', _l('challenge_time_expired'));
           redirect(admin_url('idea_hub/ideas/'.$challenge_id));
        }
        if ($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['user_id'] = get_staff_user_id();
            if ($id == '') {
				if (!has_permission('idea_hub', '', 'create')) {
					access_denied('idea_hub');
				}
                $this->add_idea($post_data,$challenge_id);
            } else {
				if (!has_permission('idea_hub', '', 'edit')) {
					access_denied('idea_hub');
				}
                $this->update_idea($post_data,$challenge_id,$id);
            }
        }
        $this->load->model('clients_model');
        $data['clients'] = $this->clients_model->get();
        if($id == ''){
			if (!has_permission('idea_hub', '', 'create')) {
				access_denied('idea_hub');
			}
            $title = _l('add_new', _l('idea'));
        }else{
			if (!has_permission('idea_hub', '', 'edit')) {
				access_denied('idea_hub');
			}
            $data['idea']        = $this->idea_hub_model->get_idea_by_id($id);
            if ($data['idea']->visibility == 'custom') {
                $data['custom_visible']        = $this->idea_hub_model->get_visible_customers_ids($id);
            }
            $title = _l('edit', _l('idea'));
        }
        $data['title']                 = $title;
        $data['challenge_id']         = $challenge_id;
        $this->app_scripts->add('idea_hub-js','modules/idea_hub/assets/js/idea_hub.js');
        $this->load->view('admin/ideas/idea', $data);
    }

    /*
    * Add new idea
    */
    public function add_idea($post_data,$challenge_id){
        if($this->input->post('cover_type') == 'video'){
            $post_data['video_thumbnail'] = handle_idea_cover_thumbnail_upload(FCPATH .'modules/idea_hub/uploads/ideas/v_thumbnails');
        }
        $post_data['image'] = handle_idea_cover_image_upload(FCPATH .'modules/idea_hub/uploads/ideas');
        $id = $this->idea_hub_model->add_idea($post_data);
        if ($id) {
            $attachments = handle_idea_attachments_upload(FCPATH .'modules/idea_hub/uploads/ideas/attachment/', $id);
            $this->idea_hub_model->add_idea_attachments($attachments);
            if($this->input->post('visibility') == 'custom'){
                $this->idea_hub_model->add_idea_custom_visibility($this->input->post('clients'), $id, 'add');
            }
            set_alert('success', _l('added_successfully', _l('idea')));
            redirect(admin_url('idea_hub/ideas/'.$challenge_id));
        }
    }

    /*
    * Update idea
    */
    public function update_idea($post_data,$challenge_id,$id){
        $attachments = handle_idea_attachments_upload(FCPATH .'modules/idea_hub/uploads/ideas/attachment/', $id);
        $this->idea_hub_model->add_idea_attachments($attachments);
        if($this->input->post('visibility') == 'custom'){
            $this->idea_hub_model->add_idea_custom_visibility($this->input->post('clients'), $id, 'edit');
        }else{
            $this->idea_hub_model->delete_idea_visibility($id);
        }
        $data = $this->input->post();
        if($this->input->post('cover_type') == 'video'){
            if(!empty($_FILES['video_thumbnail']['name'])) {
                $data['video_thumbnail'] = handle_idea_cover_thumbnail_upload(FCPATH .'modules/idea_hub/uploads/ideas/v_thumbnails');
            }
        }
        if(!empty($_FILES['image']['name'])) {
            $data['image'] = handle_idea_cover_image_upload(FCPATH .'modules/idea_hub/uploads/ideas');
        }
        $success = $this->idea_hub_model->update_idea($data, $id);
        if ($success) {
            set_alert('success', _l('updated_successfully', _l('idea')));
        }
        redirect(admin_url('idea_hub/ideas/'.$challenge_id));
    }

    public function tree_view()
    {
        $this->load->view('admin/challenges/tree_view');
    }
    
    public function grid_details()
    {
        $this->app_css->add('jquery-comments-css',base_url('assets/plugins/jquery-comments/css/jquery-comments.css'));  

        $this->app_scripts->add('jquery-comments-js',base_url('assets/plugins/jquery-comments/js/jquery-comments.js'));
        $this->load->view('admin/challenges/grid_details');
    }

    /*
    *  Setup category
    */
    public function category()
    {
        if (!is_admin()) {
            access_denied('idea challenge Category');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('idea_hub', 'admin/setup/table/category'));
        }
        $data['title'] = _l('idea_hub_category');
        $this->load->view('admin/setup/manage/category', $data);
    }

    /*
    * Category add/update
    */
    public function categories()
    {
         if (!is_admin()) {
            access_denied('categories');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->idea_hub_model->add_category($this->input->post());
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $id ? _l('added_successfully', _l('category')) : '',
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->idea_hub_model->update_category($data, $id);
                $message = _l('updated_successfully', _l('category'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }

    /*
    * Remove category by id
    */
    public function remove_category($id)
    {
        if (!$id) {
            redirect(admin_url('idea_hub/category'));
        }
        $response = $this->idea_hub_model->remove_category($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('categories')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('category')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('category')));
        }
        redirect(admin_url('idea_hub/category'));
    }

    /*
    *  Setup stage
    */
    public function stages()
    {
        if (!is_admin()) {
            access_denied('Stages');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('idea_hub', 'admin/setup/table/stages'));
        }
        $data['title'] = _l('stage');
        $this->load->view('admin/setup/manage/stages', $data);
    }

    /*
    * Add or update stage
    */
    public function stage()
    {
        if (!is_admin()) {
            access_denied('Stages');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->idea_hub_model->add_stage($this->input->post());
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $id ? _l('added_successfully', _l('stage')) : '',
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->idea_hub_model->update_stage($data, $id);
                $message = _l('updated_successfully', _l('stage'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }

    /*
    * Remove or delete stage by id
    */
    public function remove_stage($id)
    {
        if (!$id) {
            redirect(admin_url('idea_hub/stages'));
        }
        $response = $this->idea_hub_model->remove_stage($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('stage')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('stage')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('stage')));
        }
        redirect(admin_url('idea_hub/stages'));
    }

    /*
    * Setup status
    */
    public function status()
    {
        if (!is_admin()) {
            access_denied('Status');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('idea_hub', 'admin/setup/table/status'));
        }
        
        $data['title'] = _l('status');
        $this->load->view('admin/setup/manage/status', $data);
    }

    /*
    * Get idea comments by id
    */
    public function get_idea_comments($id, $type)
    {
        echo json_encode($this->idea_hub_model->get_idea_comments($id, $type));
    }

    /*
    * idea rank update
    */
    public function add_update_idea_rank()
    {
        if ($this->input->post()) {
            $post_data['user_id']       =  get_staff_user_id();
            $post_data['user_type']     =  'staff';
            $post_data['rank']          =  $this->input->post('rank');
            $post_data['idea_id']    =  $this->input->post('idea_id');
            $this->idea_hub_model->idea_rank_update($post_data);
            $rank['points'] = $this->idea_hub_model->get_idea_total_rank($post_data['idea_id']);
            $rank['vote'] = $this->idea_hub_model->get_idea_vote_count($post_data['idea_id']);
            echo json_encode($rank);
        }
    }

    /*
    * challenge like/dislike
    */
    public function challengeVote()
    {
        if($this->input->post()) {
            $post_data['user_id']       =  get_staff_user_id();
            $post_data['user_type']     =  'staff';
            $post_data['challenge_id'] =  $this->input->post('challenge_id');
            $post_data['vote']          =  $this->input->post('thumb_value');
            $voteCount = $this->idea_hub_model->challenge_vote_like_dislike($post_data);
            echo json_encode($voteCount); exit;
        }
    }

    /*
    * Delete challenge by id
    */
    public function delete_challenge($id = '')
    {
		if (!has_permission('idea_hub', '', 'delete')) {
			access_denied('idea_hub');
		}
        if (isset($id) && !empty($id)) {
            $success = $this->idea_hub_model->delete_challenge($id);
            if ($success) {
                set_alert('success', _l('deleted_successfully', _l('challenge')));
            }
            redirect(admin_url('idea_hub/'));
        }
    }

    /*
    * Delete idea by id
    */
    public function delete_idea($id = '', $challenge_id='')
    {
		if (!has_permission('idea_hub', '', 'delete')) {
			access_denied('idea_hub');
		}
        if (isset($id) && !empty($id)) {
            $success = $this->idea_hub_model->delete_idea($id);
            if ($success) {
                set_alert('success', _l('deleted', _l('idea')));
            }
            redirect(admin_url('idea_hub/ideas/'.$challenge_id));
            
        }
    }

    /*
    * Add discussion comment
    */
    public function add_discussion_comment($idea_id, $type)
    {
        echo json_encode($this->idea_hub_model->add_discussion_comment(
            $this->input->post(null, false),
            $idea_id,
            $type
        )); exit;
    }

    /*
    * Update discussion comment
    */
    public function update_discussion_comment()
    {
        echo json_encode($this->idea_hub_model->update_discussion_comment($this->input->post(null, false)));
    }

    /*
    * Delete discussion comment
    */
    public function delete_discussion_comment($id)
    {
        echo json_encode($this->idea_hub_model->delete_discussion_comment($id));
    }

    /*
    * Add and update status
    */
    public function statuses()
    {
        if (!is_admin()) {
            access_denied('Status');
        }

        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->idea_hub_model->add_status($this->input->post());
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $id ? _l('added_successfully', _l('status')) : '',
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->idea_hub_model->update_status($data, $id);
                $message = _l('updated_successfully', _l('status'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }

    /*
    * Remove or delete status by id
    */
    public function remove_status($id)
    {
        if (!$id) {
            redirect(admin_url('idea_hub/status'));
        }
        $response = $this->idea_hub_model->remove_status($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('status')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('status')));
        }
        redirect(admin_url('idea_hub/status'));
    }
	
	/*
    *  Update stage from kanban view
    */
    public function update_idea_stage_kanban()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            return $this->idea_hub_model->update_idea_stage($this->input->post());
        }
    }
	
	/*
    * Load More for kanban
    */
    public function idea_kanban_load_more()
    {
        $status = $this->input->get('status');
        $page   = $this->input->get('page');

        $where = [];
        if ($this->input->get('project_id')) {
            $where['rel_id']   = $this->input->get('project_id');
            $where['rel_type'] = 'project';
        }

        $ideas = $this->idea_hub_model->do_kanban_query($status, $this->input->get('search'), $page, false, $where);

        foreach ($ideas as $idea) {
            $this->load->view('idea_hub/ideas/kanban_card', [
                'idea'   => $idea,
                'status' => $status,
            ]);
        }
    }

    public function deactivate_challenge($id)
    {
        if($this->input->is_ajax_request()){
            $data = $this->input->post();
            echo $this->idea_hub_model->update_challenge($data, $id);
        }
    }
    public function delete($id=''){
        if(is_numeric($id)){
            $response = $this->idea_hub_model->delete_challenge($id);   
             set_alert('success', _l('Challenge has been deleted!'));
             redirect(admin_url('idea_hub'));
        }
    }
}