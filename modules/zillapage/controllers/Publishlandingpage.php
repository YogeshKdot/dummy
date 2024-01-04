<?php

defined('BASEPATH') or exit('No direct script access allowed');

use hisorange\BrowserDetect\Parser as Browser;

class Publishlandingpage extends ClientsController
{
	public function __construct()
    {
        parent::__construct();
        $this->load->model('landingpage_model');
    }

    public function index($code)
    {
    	$page = $this->landingpage_model->get_landing_page_code($code);
                
        if ($page && $page->is_publish) {
        	$data['page']                 =  $page;
        	$data['title']                 = _l('landing_pages');
        	$this->load->view('publish/index', $data);
        }
        else echo "<h3>Not found page or you need publish page</h3>";
    }

    public function getpagejson(){

    	if ($this->input->post()) {

    		$code = $this->input->post('_code');
	    	$page = $this->landingpage_model->get_landing_page_code($code);

	    	if ($page && $page->is_publish) {
	    		
	    		$blocks_css = $this->landingpage_model->get_landing_page_setting('blockscss');
        		$blockscss = replaceVarContentStyle($blocks_css->value);

        		header('Content-Type: application/json');
            	echo json_encode([
            		'blockscss'=>$blockscss, 
		            'css' => $page->css,
		            'html'=>html_entity_decode($page->html), 
		            'custom_header' => $page->custom_header,
		            'custom_footer' => $page->custom_footer,
		            'thank_you_page_css' => $page->thank_you_page_css,
		            'thank_you_page_html' => html_entity_decode($page->thank_you_page_html),
		            'main_page_script' => $page->main_page_script,
	        	]); die;
	    	}
    	}
    	echo "<h3>Not found page or you need publish page</h3>";
    	
    }
    
    public function formsubmission(){

    	if ($this->input->post()) {

    		$code = $this->input->post('_code');
	    	$page = $this->landingpage_model->get_landing_page_code($code);

	        if (!$page) {
	        	header('Content-Type: application/json');
	            echo json_encode(['error'=> 'Not found page or you need publish page']); die;
	        }
	        $new_browser = new Browser();

	        $tracking = $new_browser->detect();

	        $fields_expect = ['_code','csrf_token_name'];

	        $all_fields_request = array_keys($this->input->post());

	        $fields = array_diff($all_fields_request, $fields_expect);

	        $fields = array_unique($fields);

	        $field_values = array();
	        
	        if(count($fields) > 0){

	            foreach ($fields as $key) {
	                $field_values[$key] = $this->input->post($key);
	            }

	            if(!array_filter($field_values)) {

	            	header('Content-Type: application/json');
	            	echo json_encode(['error'=> 'Not found any fields submit. Please enter some fields']); die;
	            }
	            $data = [
                    'landing_page_id' => $page->id,
	                'field_values' => json_encode($field_values),
	                'browser' => $tracking->browserFamily(),
	                'os' => $tracking->platformFamily(),
	                'device' => getDeviceTracking($tracking),
                ];

                $this->db->insert(db_prefix() . 'landing_page_form_data', $data);
                
                $insert_id = $this->db->insert_id();

				
                if ($insert_id) {
                    
                    log_activity('New Form Submit for Landing Page: '.$page->name.': [Data ID:' . $insert_id . ']');
                    
                    // notification
                    if ($page->notify_lead_imported != 0) {
                        if ($page->notify_type == 'assigned') {
                            $to_responsible = true;
                        } else {
                            $ids            = @unserialize($page->notify_ids);
                            $to_responsible = false;
                            if ($page->notify_type == 'specific_staff') {
                                $field = 'staffid';
                            } elseif ($page->notify_type == 'roles') {
                                $field = 'role';
                            }
                        }

                        if ($to_responsible == false && is_array($ids) && count($ids) > 0) {
                            $this->db->where('active', 1);
                            $this->db->where_in($field, $ids);
                            $staff = $this->db->get(db_prefix() . 'staff')->result_array();
                        } else {
                            $staff = [
                                [
                                    'staffid' => $page->responsible,
                                ],
                            ];
                        }
                        $notifiedUsers = [];
                        foreach ($staff as $member) {
                            if ($member['staffid'] != 0) {
                                $notified = add_notification([
                                    'description'     => 'not_lead_imported_from_landing_page',
                                    'touserid'        => $member['staffid'],
                                    'fromcompany'     => 1,
                                    'fromuserid'      => 0,
                                    'additional_data' => serialize([
                                        $page->name,
                                    ]),
                                    'link' => 'zillapage/leads',
                                ]);
                                if ($notified) {
                                    array_push($notifiedUsers, $member['staffid']);
                                }
                            }
                        }
                        pusher_trigger_notification($notifiedUsers);
                    }
                    header('Content-Type: application/json');
		            echo json_encode([
		            	'type_form_submit' => $page->type_form_submit,
		                'redirect_url' => $page->redirect_url
		            ]); die;
                }
                else{
                	header('Content-Type: application/json');
	            	echo json_encode(['error'=> 'Insert Form data failed']); die;
                }

	            
	            
	        }
	        else{
	        	header('Content-Type: application/json');
	            echo json_encode(['error'=> 'Not found any fields submit. Please enter some fields']); die;
	        }
	    }

    }
    


    public function thankyou($code){
    	$page = $this->landingpage_model->get_landing_page_code($code);
                
        if ($page && $page->is_publish) {
        	$data['page']                 =  $page;
        	$data['title']                 = _l('landing_pages');
        	$this->load->view('publish/thank_page', $data);
        }
        else echo "<h3>Not found page or you need publish page</h3>";
    }

    public function getblockscss()
    {
        $this->load->model('landingpage_model');
        $blocks_css = $this->landingpage_model->get_landing_page_setting('blockscss');
        $blockscss = replaceVarContentStyle($blocks_css->value);
        echo $blockscss; die;
    }
}
