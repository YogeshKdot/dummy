<?php

defined('BASEPATH') or exit('No direct script access allowed');
set_time_limit(0);

Class Client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('zoom_model');
        $this->load->library('ZoomAPIWrapper');
    }

	public function meeting_list()
    {
		$client_id=$_SESSION['contact_user_id']; 
        $settings=$this->zoom_model->get_client_api_settings($client_id);
        $apiKey=$settings[0]['api_key'];
        $apiSecret=$settings[0]['api_secret'];
        $email=$settings[0]['zoom_email'];

        $params = array('apiKey' => $apiKey, 'apiSecret' => $apiSecret);
        $zoom = new ZoomAPIWrapper($params);
        $pathParams=array('userId'=>$email);
        $response = $zoom->doRequest(GET, '/users/{userId}/meetings','' ,$pathParams , '');
        $data['data']=$response['meetings'];
       
  
        $response_meetings = $response['meetings']; 
		
		
        foreach($response_meetings as $meet){
			
                    $id=$meet['id'];
             		$exist=$this->zoom_model->check_client_meeting_exist($id);
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
						$this->zoom_model->insert_client_meeting($data2);		
                         
                    }     					
			
        }
		
		$contact_id=$_SESSION['contact_user_id'];
		
		$data['client_meetings'] = $this->zoom_model->get_client_meetings($contact_id);
	
         $data['title']='Zoom Meetings';
         $this->data($data);
		 $this->view('client_meeting_list', $data);
		 $this->layout();
    }
	
	function delete_meeting(){

        $client_id=$_SESSION['contact_user_id']; 
        $settings=$this->zoom_model->get_client_api_settings($client_id);
        $apiKey=$settings[0]['api_key'];
        $apiSecret=$settings[0]['api_secret'];
        $email=$settings[0]['zoom_email'];
		
		$meeting_id=$this->uri->segment(4);

        $params = array('apiKey' => $apiKey, 'apiSecret' => $apiSecret); 
        $zoom = new ZoomAPIWrapper($params);
        $pathParams=array('meetingId'=>$meeting_id);
		
			
		 
        $response = $zoom->doRequest(DELETE, '/meetings/{meetingId}','' ,$pathParams); 	
		
		
			$this->zoom_model->delete_client_meeting($meeting_id);			
            set_alert('success', _l('zoom_meeting_deleted', _l('zoom')));
			$contact_id=$_SESSION['contact_user_id'];
		
		    $data['client_meetings'] = $this->zoom_model->get_client_meetings($contact_id);
            redirect(site_url('zoom/client/meeting_list',$data));
        
		
	}
	
	 function api_client_meeting()
    {
        $client_id=$_SESSION['contact_user_id']; 
        $data['settings']=$this->zoom_model->get_client_api_settings($client_id);
		

        $data['title']= _l('zoom_api_settings');
		$this->data($data);
        $this->view('zoom_client_api_settings', $data);	 
        $this->layout();
    }
	   public function api_meeting_submit()
    {
        $data['client_id']   = html_purify($this->input->post('client_id', false));
        $data['zoom_email']   = html_purify($this->input->post('zoom_email', false));
        $data['api_key']      = html_purify($this->input->post('api_key', false));
        $data['api_secret']   = html_purify($this->input->post('api_secret', false));

        $id=$this->zoom_model->update_client_meeting_settings($data);
        $data['title']= _l('zoom_api_settings');
        

        if ($id) {
					
            set_alert('success', _l('zoom_api_updated', _l('zoom')));
            redirect(site_url('zoom/client/api_client_meeting'));
        }

        
    }
	
}
