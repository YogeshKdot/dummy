<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client_ideas extends ClientsController
{

	/** Define class variables**/
   private $CI;

   /*
   * Loading models and library
   */
   public function __construct()
   {
		parent::__construct();
		$CI = &get_instance();
		$this->load->model('clients_model');
		$this->load->model('idea_hub_model');
		$CI->load->library('app');
		if ((!is_client_logged_in() && !is_staff_logged_in()) || !get_option('client_view_ih_menu')) {
			redirect_after_login_to_current_url();
			redirect(site_url('authentication/login'));
		}
	}

	/*
    * Setting params in index function
    */
	public function _remap($method){
		$param_offset = 2;

		// Default to index
		if ( ! method_exists($this, $method))
		{
		// We need one more param
		$param_offset = 1;
		$method = 'index';
		}

		// Since all we get is $method, load up everything else in the URI
		$params = array_slice($this->uri->rsegment_array(), $param_offset);

		// Call the determined method with all params
		call_user_func_array(array($this, $method), $params);
	}

	/*
    * Loading ideas grid screen
    */
	public function index($challenge_id)
	{
		$data['switch_grid'] = true;

		$data['categories']     = $this->idea_hub_model->get_categories();
		$data['title']          = _l('idea_hub');
		$data['stages']         = $this->idea_hub_model->get_stages();
		$data['statuses']       = $this->idea_hub_model->get_statuses();
		$data['challenge']     = $this->idea_hub_model->get_challenge_by_id($challenge_id);
		$data['challenge_id']  = $challenge_id;
		$this->data($data);
		$this->view('client/ideas/manage', $data);
		$this->layout();
	}

	/*
    * Loading ideas grid data by ajax
    */
	public function ideas_grid($challenge_id)
	{
		$data = [];
		$challenge_id = !empty($this->input->post('challenge_id')) ? $this->input->post('challenge_id') : 0;
		$data['challenge_id'] = $challenge_id;
		if(!empty($this->input->post('catids'))) {
			$catids = json_decode($this->input->post('catids'));
			$data['catids'] = $catids;
		}
		$this->data($data);

		echo $this->load->view('client/ideas/grid', $data, true); exit;
	}

   	/*
    * idea details page
    */
	public function idea_detail($id='')
	{
		if(!is_numeric($id)){
			access_denied();
		}
	  	if(!empty($id)){
	  		$visibility_for_clients = $this->idea_hub_model->get_visible_customers_ids($id);
	  		$visibility_for_clients = explode(',',$visibility_for_clients);
	  		if (!in_array(get_client_user_id(), $visibility_for_clients)) {
	  			access_denied();
	  		}
			$idea = $this->idea_hub_model->get_idea_by_id($id);
			if (!$idea) {
				echo _l('idea_not_found'); die;
			}
			$data['idea']      = $idea;
			$data['title']        = _l('ideas');
			$data['ranking']      = $this->idea_hub_model->get_idea_total_rank($idea->id);
			$data['rank']         = $this->idea_hub_model->get_idea_rank($idea->id);
			$data['vote_count']   = $this->idea_hub_model->get_idea_vote_count($idea->id);
			$this->data($data);
			$this->view('client/ideas/idea_detail', $data);
			$this->layout();
	  	}else{
	      die(_l('no_ideas_found'));
	  	}
	}

	/*
    * Adding or updating idea votes
    */
	public function add_update_idea_rank()
	{
	    if($this->input->post()) {
			$post_data['user_id'] 	 =  get_client_user_id();
			$post_data['user_type']  =  'customer';
			$post_data['rank'] 		 =  $this->input->post('rank');
			$post_data['idea_id'] =  $this->input->post('idea_id');
			$this->idea_hub_model->idea_rank_update($post_data);
			$rank['points'] = $this->idea_hub_model->get_idea_total_rank($post_data['idea_id']);
            $rank['vote'] = $this->idea_hub_model->get_idea_vote_count($post_data['idea_id']);
            echo json_encode($rank); exit;
	    }
	}

	/*
    * Getting idea all comments by idea id
    */
	public function get_discussion_comments($id, $type)
	{
	  echo json_encode($this->idea_hub_model->get_idea_comments($id, $type));
	}

	/*
    * Adding comment to idea
    */
	public function add_discussion_comment($idea_id, $type)
	{
		echo json_encode($this->idea_hub_model->add_discussion_comment(
		  $this->input->post(null, false),
		  $idea_id,
		  $type
		));
	  	exit;
	}

	/*
    * Updating idea comment 
    */
	public function update_discussion_comment()
	{
	  echo json_encode($this->idea_hub_model->update_discussion_comment($this->input->post(null, false)));
	}

	/*
    * Deleting idea comment 
    */
	public function delete_discussion_comment($id)
	{
	  echo json_encode($this->idea_hub_model->delete_discussion_comment($id));
	}
}