<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client_challenges extends ClientsController
{

   /** Class variables**/
   private $CI;

   /*
   * Loading model and library
   */
   public function __construct()
   {
        parent::__construct();
        $CI = &get_instance();
        $this->load->model('staff_model');
        $this->load->model('clients_model');
        $this->load->model('idea_hub_model');
        $CI->load->library('app');
		if ((!is_client_logged_in() && !is_staff_logged_in()) || !get_option('client_view_ih_menu')) {
			redirect_after_login_to_current_url();
			redirect(site_url('authentication/login'));
		}
    }

    /*
    * Main page for challenge grid screen
    */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('idea_hub', 'table'));
        }
        $data['switch_grid'] = true;
        $data['categories']    = $this->idea_hub_model->get_categories();
        $data['title']         = _l('idea_hub');
        $this->data($data);
        $this->view('client/challenges/manage', $data);
        $this->layout();
    }

    /*
    * Loading grid screen when ajax call
    */
    public function grid()
    {
        if ( !empty($this->input->post('catids')) ) {
            $catids = json_decode($this->input->post('catids'));
            echo $this->load->view('client/challenges/challenge_grid', ['catids' => $catids], true);
        } else {
            echo $this->load->view('client/challenges/challenge_grid', [], true);
        }
    }


    /*
    * challenge like dislike updating
    */
    public function challenge_like_dislike()
    {
        if ($this->input->post()) {
            $post_data['user_id']       =  get_client_user_id();
            $post_data['user_type']     =  'customer';
            $post_data['challenge_id'] =  $this->input->post('challenge_id');
            $post_data['vote']          =  $this->input->post('thumb_value');
            $voteCount                  = $this->idea_hub_model->challenge_vote_like_dislike($post_data);
            echo json_encode($voteCount); exit();
        }
    }
}