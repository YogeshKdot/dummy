<?php defined('BASEPATH') or exit('No direct script access allowed');
class Code_envato extends AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('codeEnv');
	}

	public function module_activation(){
		//LYMODFIX
		update_option($module_name.'_is_verified', true);
		$res['default_url']= $this->input->post('default_url');
		echo json_encode($res);
		return;
		//
		
		if($this->input->is_ajax_request()){
			$module_name = $this->input->post('module_name');
			$res = $this->codeenv->verifyPurchase(null, $this->input->post('purchase_key'));
			if ($res['status']) {
				update_option($module_name.'_is_verified', true);
				$res['default_url']= $this->input->post('default_url');
			}
			echo json_encode($res);
		}
	}
	public function index()
    {
        access_denied();
    }
}