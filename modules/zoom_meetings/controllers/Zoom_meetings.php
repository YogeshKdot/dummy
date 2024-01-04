<?php

defined('BASEPATH') or exit('No direct script access allowed');
set_time_limit(0);

class Zoom_meetings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('zoom_model');
        $this->load->library('ZoomAPIWrapper');
    }

   
    public function index()
    {
        
        $settings=$this->zoom_model->get_api_settings();
        $apiKey=$settings[0]['api_key'];
        $apiSecret=$settings[0]['api_secret'];
        $email=$settings[0]['zoom_email'];

        $params = array('apiKey' => $apiKey, 'apiSecret' => $apiSecret);
        $zoom = new ZoomAPIWrapper($params);
        $pathParams=array('userId'=>$email);
        $response = $zoom->doRequest(GET, '/users/{userId}/meetings','' ,$pathParams , '');
        $data['data']=$response['meetings'];
        $data['title']= _l('zoom_list');
  
        $response_meetings = $response['meetings']; 
		
		
        foreach($response_meetings as $meet){
			
                    $id=$meet['id'];
             		$exist=$this->zoom_model->check_meeting_exist($id);
                    if(empty($exist)){
                            
							$data2 = array(
								'subject'=>$meet['topic'],
								'start_time'=>$meet['start_time'],
								'duration'=>$meet['duration'],
								'timezone'=>$meet['timezone'],
								'agenda'=>$meet['agenda'],
								'join_url'=>$meet['join_url'],
								'meeting_id'=>$meet['id']	
							);
						$this->zoom_model->insert_meeting($data2);		
                         
                    }     					
			
        }
		if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('zoom_meetings', 'zoom_list'));
            }
        $this->load->view('zoom', $data);	 
       
    }

    
	public function create_meeting()
    {
		
        $data = [
			'staff_members' => $this->staff_model->get('', ['active' => 1]),
			'rel_type' => 'lead',
			'rel_contact_type' => 'contact',
			'rel_contact_id' => '',
			'rel_id' => '',
			
		];  
        $data['title']= _l('zoom_create_meeting');
        $this->load->view('zoom_create_meeting', $data);	 

    }
    
	function delete_meeting(){


        $settings=$this->zoom_model->get_api_settings();
        $apiKey=$settings[0]['api_key'];
        $apiSecret=$settings[0]['api_secret'];
        $email=$settings[0]['zoom_email'];
		
		$meeting_id=$this->uri->segment(4);

        $params = array('apiKey' => $apiKey, 'apiSecret' => $apiSecret); 
        $zoom = new ZoomAPIWrapper($params);
        $pathParams=array('meetingId'=>$meeting_id);
		
			
		 
        $response = $zoom->doRequest(DELETE, '/meetings/{meetingId}','' ,$pathParams); 	
		
		
			$this->zoom_model->delete_meeting($meeting_id);			
            set_alert('success', _l('zoom_meeting_deleted', _l('zoom')));
            redirect(admin_url('zoom_meetings'));
        
		
	}		
	
    function submit_meeting(){

        $settings=$this->zoom_model->get_api_settings();
        $apiKey=$settings[0]['api_key'];
        $apiSecret=$settings[0]['api_secret'];
        $email=$settings[0]['zoom_email'];

        $params = array('apiKey' => $apiKey, 'apiSecret' => $apiSecret);
        $zoom = new ZoomAPIWrapper($params);
        $pathParams=array('userId'=>$email);
        
        $subject   = $this->input->post('subject', false);
        $start_time   = $this->input->post('start_time', false);
        $timezone   = $this->input->post('timezone', false);
        $agenda   = $this->input->post('agenda', false);
        $duration   = $this->input->post('duration', false);
		$join_before_host   = $this->input->post('join_before_host', false);
		$host_video   = $this->input->post('host_video', false);
		$participant_video   = $this->input->post('participant_video', false);
		$mute_upon_entry= $this->input->post('mute_upon_entry', false);
		$waiting_room= $this->input->post('waiting_room', false);
        $clientid= $this->input->post('clientid', false);
        $staff = $this->input->post('staff');
        
      

        



        $participants=array();
		
		
		   if (isset($clientid)) {
              
                    $customers['contacts'][] = zoom_get_user_limited_details($clientid, 'contacts');
              
           }
		   if (isset($staff)) {
               foreach ($staff as $st) {
                    $participants['contacts'][] = zoom_get_staff_limited_details($st, 'staff');
               }
          }
		  
		  
		$meeting_data = array(
            "topic"  => $subject,
            "start_time"   => gmdate( "Y-m-d\TH:i:s", strtotime($start_time)),
            "timezone"=> $timezone,
            "duration"=>$duration,
            "agenda"=> $agenda ,
			"settings" => [
                    'join_before_host' => isset($join_before_host) ? true : false,
                    'host_video' => isset($host_video) ? true : false,
                    'participant_video' => isset($participant_video) ? true : false,
                    'mute_upon_entry' => isset($mute_upon_entry) ? true : false,
                    'waiting_room' => isset($waiting_room) ? true : false,
               ]
          );
		  if (isset($participants) && !empty($participants)) {
               $meeting_data['settings'][] = [
                    'approval_type' => 0,
                    'registration_type' => 1,
                    'registrants_email_notification' => true
               ];
          }
        $response = $zoom->doRequest(POST, '/users/{userId}/meetings','' ,$pathParams , $meeting_data); 
		
		
		
        if ($response) {
			
			   
				$meeting_id=$response['id'];
				$pathmeetParams=array('meetingId'=>$meeting_id);
				 
				$i=0;
				foreach($participants as $p){
					    
						if(isset($p[$i]->staffid)){
				    	$to_user_id  = $p[$i]->staffid;
						}elseif(isset($p[$i]->userid)){
						$to_user_id  = $p[$i]->userid;
						}	
						
							$email_staff       = $p[$i]->email;
							$firstname   = $p[$i]->firstname;
							$lastname    = $p[$i]->lastname;
						
					
					$registrant_data = array(
						"email"  => $email_staff,
						"first_name"   => $registrant_fname,
						"last_name"=> $registrant_lname,
					  );
					$rp = $zoom->doRequest(POST, '/meetings/{meetingId}/registrants','' ,$pathmeetParams , $registrant_data);
					
					
					$description='Join the meeting: <a target="_blank" href="'.$response['join_url'].'"> Link</a>';
					
					$data_notifications = array(
				
						'date'=>date('Y-m-d'),
						'description'=>$description,
						'touserid'=>$to_user_id
					    
				    );
						$res_data= $zoom->doRequest(GET, '/meetings/{meetingId}/invitation','',$pathmeetParams); 
						$email_body = $res_data['invitation'];
						$msg=htmlentities ($email_body);
						
						
						$this->load->library('email');
						$this->email->from($email, 'Meeting');
						$this->email->to($email_staff);
						$this->email->subject('Zoom Meeting Invitation');
						$this->email->message($msg);
						$this->email->set_mailtype("html");
						$this->email->send();
					$i++;			
				}
				if(!empty($customers)){
					
					$cust_email=$customers['contacts'][0]->email;
					$res_data= $zoom->doRequest(GET, '/meetings/{meetingId}/invitation','',$pathmeetParams); 
					$email_body = $res_data['invitation'];
					$msg=htmlentities ($email_body);
						
					$this->load->library('email');
						$this->email->from($email, 'Meeting');
						$this->email->to($cust_email);
						$this->email->subject('Zoom Meeting Invitation');
						$this->email->message($msg);
						$this->email->set_mailtype("html");
						$this->email->send();
				
				}
				$data = array(
					'subject'=>$response['topic'],
					'start_time'=>$response['start_time'],
					'duration'=>$response['duration'],
					'timezone'=>$response['timezone'],
					'agenda'=>$response['agenda'],
					'join_url'=>$response['join_url'],
					'meeting_id'=>$response['id']	
				);
			
			$this->zoom_model->insert_notificatons($data_notifications);	

			$this->zoom_model->insert_meeting($data);	
			
			
					
            set_alert('success', _l('zoom_meeting_created', _l('zoom')));
            redirect(admin_url('zoom_meetings/zoom_meetings'));
        }

    }


    public function api_meeting()
    {

        $data['settings']=$this->zoom_model->get_api_settings();
        $data['title']= _l('zoom_api_settings');
        $this->load->view('zoom_api_settings', $data);	 

    }
	
    
	 
    public function api_meeting_submit()
    {

        $data['zoom_email']   = $this->input->post('zoom_email', false);
        $data['api_key']      = $this->input->post('api_key', false);
        $data['api_secret']   = $this->input->post('api_secret', false);

        $id=$this->zoom_model->update_meeting_settings($data);
        $data['title']= _l('zoom_api_settings');
        

        if ($id) {
					
            set_alert('success', _l('zoom_api_updated', _l('zoom')));
            redirect(admin_url('zoom_meetings/api_meeting'));
        }

        
    }

    public function add_registrant(){

        $data['title']= _l('zoom_add_registrant');
        $this->load->view('zoom_add_registrant', $data);	
          
    }


    public function submit_registrant(){

        $settings=$this->zoom_model->get_api_settings();
        $apiKey=$settings[0]['api_key'];
        $apiSecret=$settings[0]['api_secret'];
        $email=$settings[0]['zoom_email'];
        $registrant_email   = html_purify($this->input->post('zoom_registrant_email', false));
        $registrant_fname   = html_purify($this->input->post('zoom_registrant_fname', false));
        $registrant_lname = html_purify($this->input->post('zoom_registrant_lname', false));
        $zoom_registrant_meetid = html_purify($this->input->post('zoom_registrant_meetid', false));

        $params = array('apiKey' => $apiKey, 'apiSecret' => $apiSecret);
        $zoom = new ZoomAPIWrapper($params);
        $pathParams=array('meetingId'=>$zoom_registrant_meetid);
        
        

        $registrant_data = array(
            "email"  => $registrant_email,
            "first_name"   => $registrant_fname,
            "last_name"=> $registrant_lname,
          );
        $response = $zoom->doRequest(POST, '/meetings/{meetingId}/registrants','' ,$pathParams , $registrant_data); 	
        
        if($response['code']==3001){
            set_alert('warning', _l('zoom_meeting_not_exists'));	
            redirect(admin_url('zoom_meetings/add_registrant'));
        }
        else{
			
			
			$response_data= $zoom->doRequest(GET, '/meetings/{meetingId}/invitation','',$pathParams); 
			$email_body = $response_data['invitation'];
			$msg=htmlentities ($email_body);
			
			
			$this->load->library('email');
			$this->email->from($email, 'Meeting');
			$this->email->to($registrant_email);
			$this->email->subject('Zoom Meeting Invitation');
			$this->email->message($msg);
			$this->email->set_mailtype("html");
			$this->email->send();
			
            set_alert('success', _l('zoom_registrant_added', _l('zoom')));
            redirect(admin_url('zoom_meetings/add_registrant'));
        }
    }

	
}
