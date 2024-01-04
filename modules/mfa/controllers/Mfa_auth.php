<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * MFA Controller
 */
class mfa_auth extends App_Controller {

	/**
	 * Constructs a new instance.
	 */
	public function __construct() {
		parent::__construct();
		if ($this->app->is_db_upgrade_required()) {
            redirect(admin_url());
        }

        load_admin_language();
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', _l('form_validation_required'));
        $this->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->form_validation->set_message('matches', _l('form_validation_matches'));

        hooks()->do_action('admin_auth_init');

		$this->load->model('mfa_model');
	}

	/**
	 * { multi factor authentication }
	 * @param        $staffid  The staffid
	 * @return view
	 */
	public function multi_factor_authentication($staffid){
		$this->load->model('staff_model');
		$staff = $this->staff_model->get($staffid);
		$data['title'] = _l('multi_factor_authentication');
		$data['staff'] = $staff;
		$data['qr_url'] = '';
		if(!$this->input->post()){
			$this->session->unset_userdata('staff_user_id');
	        $this->session->unset_userdata('staff_logged_in');
	    }
		
		$this->load->view('authentication', $data);
	}

	/**
	 * { mfa check auth }
	 *
	 * @param        $staffid  The staffid
	 * @return 		 redirect
	 */
	public function mfa_check_auth($staffid){
		$this->load->model('staff_model');
		$staff = $this->staff_model->get($staffid);

		if($this->input->post()){
			$code = $this->input->post();
			$rs_gg_ath = 0;
			$rs_whatsapp = 0;
			$rs_sms = 0;

			// Check staff & admin setting
			if($staff->mfa_google_ath_enable == 1 && get_mfa_option('enable_google_authenticator') == 1 && enable_gg_auth_with_role($staff->role) == 1){ 
				if(!class_exists('PHPGangsta_GoogleAuthenticator')){
					require_once MFA_PATH.'assets/plugins/PHPGangsta/GoogleAuthenticator.php';
				}

				$auth = new PHPGangsta_GoogleAuthenticator();
				$secret_key = $staff->gg_auth_secret_key;
				$tolerance = 1;
				$check_result = $auth->verifyCode($secret_key, $code['code'], $tolerance);
				if($check_result){
					$rs_gg_ath++;
					$this->mfa_model->mfa_history_login($staff->staffid, 'google_authenticator', 'success');
				}
			}

			if($staff->mfa_whatsapp_enable == 1 && get_mfa_option('enable_whatsapp') == 1){
				$check = check_security_code($staff->staffid, $code['code'], 'whatsapp');
				if($check == true){
					$rs_whatsapp++;
					$this->mfa_model->mfa_history_login($staff->staffid, 'whatsapp', 'success');
				}
			}

			if($staff->mfa_sms_enable == 1 && get_mfa_option('enable_sms') == 1){
				$check = check_security_code($staff->staffid, $code['code'], 'sms');
				if($check == true){
					$rs_sms++;
					$this->mfa_model->mfa_history_login($staff->staffid, 'sms', 'success');
				}
			}

			if($rs_gg_ath > 0 || $rs_whatsapp > 0 || $rs_sms > 0){
				$this->mfa_model->delete_old_security_code($staff->staffid);
				$user_data = [
                        'staff_user_id'   => $staff->staffid,
                        'staff_logged_in' => true,
                    ];
                $this->session->set_userdata($user_data);
				redirect(admin_url());
			}else{
				if($staff->mfa_google_ath_enable == 1 && get_mfa_option('enable_google_authenticator') == 1){
					$this->mfa_model->mfa_history_login($staff->staffid, 'google_authenticator', 'fail');
				}

				if($staff->mfa_whatsapp_enable == 1 && get_mfa_option('enable_whatsapp') == 1){
					$this->mfa_model->mfa_history_login($staff->staffid, 'whatsapp', 'fail');
				}

				if($staff->mfa_sms_enable == 1 && get_mfa_option('enable_sms') == 1){
					$this->mfa_model->mfa_history_login($staff->staffid, 'sms', 'fail');
				}

				redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/'.$staff->staffid));
			}		
		}
	}
}