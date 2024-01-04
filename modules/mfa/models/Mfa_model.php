<?php
defined('BASEPATH') or exit('No direct script access allowed');
require (FCPATH.'modules/mfa/helpers/ClickatellException.php');

use Twilio\Rest\Client;
use Clickatell\ClickatellException;
use modules\mfa\helpers\Rest;
/**
 * MFA model
 */
class Mfa_model extends App_Model {

	/**
	 * Constructs a new instance.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * { mfa setting by admin }
	 *
	 * @param         $data   The data
	 *
	 * @return     boolean  
	 */
	public function mfa_setting($data, $group){
		
		$affected_rows = 0;
		if(isset($data['enable_mfa'])){
			$data['enable_mfa'] = 1;
		}else{
			$data['enable_mfa'] = 0;
			$this->db->update(db_prefix().'staff',['two_factor_auth_enabled' => 0]);
		}

		if($group == 'mfa_google_authenticator'){
			if(isset($data['enable_google_authenticator'])){
				$data['enable_google_authenticator'] = 1;
			}else{
				$data['enable_google_authenticator'] = 0;
				$this->db->update(db_prefix().'staff',['mfa_google_ath_enable' => 0]);
			}

			$this->load->model('roles_model');
			$roles = $this->roles_model->get();
			foreach($roles as $role){
				if(isset($data['enable_gg_auth_'.$role['roleid']])){
					$this->db->where('roleid', $role['roleid']);
					$this->db->update(db_prefix().'roles', ['enable_gg_auth' => 1]);
					if ($this->db->affected_rows() > 0) {
			            $affected_rows++;
			        }
				}else{
					$this->db->where('roleid', $role['roleid']);
					$this->db->update(db_prefix().'roles', ['enable_gg_auth' => 0]);
					if ($this->db->affected_rows() > 0) {
						$this->db->where('role', $role['roleid']);
						$this->db->update(db_prefix().'staff',['mfa_google_ath_enable' => 0]);

			            $affected_rows++;
			        }
				}
			}

			if(isset($data['enable_gg_auth_0'])){
				$this->db->where('option_name', 'enable_gg_auth_for_users_have_not_role');
				$this->db->update(db_prefix().'mfa_options', ['option_val' => '1']);
				if ($this->db->affected_rows() > 0) {
		            $affected_rows++;
		        }
			}else{
				$this->db->where('option_name', 'enable_gg_auth_for_users_have_not_role');
				$this->db->update(db_prefix().'mfa_options', ['option_val' => '0']);
				if ($this->db->affected_rows() > 0) {
		            $affected_rows++;
		        }
			}
		}

		if($group == 'mfa_whatsapp'){
			if(isset($data['enable_whatsapp'])){
				$data['enable_whatsapp'] = 1;
			}else{
				$data['enable_whatsapp'] = 0;
				$this->db->update(db_prefix().'staff',['mfa_whatsapp_enable' => 0]);
			}
		}

		if($group == 'mfa_sms'){
			if(isset($data['enable_sms'])){
				$data['enable_sms'] = 1;
			}else{
				$data['enable_sms'] = 0;
				$this->db->update(db_prefix().'staff',['mfa_sms_enable' => 0]);
			}
		}

		$setting_dt = []; 
		if(isset($data['settings'])){
			$setting_dt['settings'] = $data['settings'];
			unset($data['settings']);
		}

		if($group == 'mfa_general'){
			unset($data['enable_google_authenticator']);
			unset($data['enable_whatsapp']);
			unset($data['enable_sms']);
		}else if($group == 'mfa_google_authenticator'){
			unset($data['enable_mfa']);
			unset($data['enable_whatsapp']);
			unset($data['enable_sms']);
		}else if($group == 'mfa_whatsapp'){
			unset($data['enable_mfa']);
			unset($data['enable_google_authenticator']);
			unset($data['enable_sms']);
		}else if($group == 'mfa_sms'){
			unset($data['enable_mfa']);
			unset($data['enable_google_authenticator']);
			unset($data['mfa_whatsapp']);
		}

		$data_key = ['enable_mfa', 'enable_google_authenticator', 'enable_whatsapp', 'enable_sms', 'twilio_account_sid', 'twilio_auth_token', 'twilio_phone_number', 'delete_history_after_months', 'whatsapp_message_template'];

		foreach($data_key as $key){
			if(isset($data[$key])){
				$this->db->where('option_name', $key);
				$this->db->update(db_prefix().'mfa_options', ['option_val' => $data[$key]]);
				if ($this->db->affected_rows() > 0) {
		            $affected_rows++;
		        }
		    }
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
	 * { mfa staff info }
	 *
	 * @param         $staff  The staff
	 * @param         $data   The data
	 *
	 * @return     boolean  
	 */
	public function mfa_staff_info($staff, $data){

		if(isset($data['enable_email_authenticator'])){
			$data['two_factor_auth_enabled'] = 1;
			unset($data['enable_email_authenticator']);
		}else{
			$data['two_factor_auth_enabled'] = 0;
		}

		if(isset($data['mfa_google_ath_enable'])){
			$data['mfa_google_ath_enable'] = 1;
		}else{
			$data['mfa_google_ath_enable'] = 0;
		}

		if(isset($data['mfa_whatsapp_enable'])){
			$data['mfa_whatsapp_enable'] = 1;
		}else{
			$data['mfa_whatsapp_enable'] = 0;
		}

		if(isset($data['mfa_sms_enable'])){
			$data['mfa_sms_enable'] = 1;
		}else{
			$data['mfa_sms_enable'] = 0;
		}

		$this->db->where('staffid', $staff);
		$this->db->update(db_prefix().'staff', $data);
		if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;

	}

	/**
	 * Sends a security code.
	 *
	 * @param        $staff  The staff
	 * @param        $type   The type
	 */
	public function send_security_code($staff, $type){
		$account_sid   = get_mfa_option('twilio_account_sid');
        $auth_token   = get_mfa_option('twilio_auth_token');
        $twilio_number   = get_mfa_option('twilio_phone_number');

        $result = 0;

        if($staff->phonenumber == '' || is_null($staff->phonenumber)){
        	return false;
        }

        $security_code = get_security_code($staff->staffid, $type);

        if($security_code == ''){
        	return false;
        }

        if($type == 'sms' && get_mfa_option('enable_sms') == 1 && $staff->mfa_sms_enable == 1){

        	$mess = 'Your security code is '. $security_code;
			$message = $this->sendSMS($mess, $staff->phonenumber);

			$data['staff'] = $staff->staffid;
            $data['type'] = 'sms';

            if($message == true){
            	$data['status'] = 'success';
            	$data['mess'] = 'Send security code to '.$staff->phonenumber.' via SMS successfully';
            	$this->send_code_log($data);
            	$result++;
            }else{
            	$data['status'] = 'fail';
            	$data['mess'] = 'Send security code to '.$staff->phonenumber.' fail';
            	$this->send_code_log($data);
            }
        }
        
        if($type == 'whatsapp' && get_mfa_option('enable_whatsapp') == 1 && $staff->mfa_whatsapp_enable == 1){
        	$client = new Client($account_sid, $auth_token);
        	$mess_template = get_mfa_option('whatsapp_message_template');
        	$body = str_replace('{{1}}', admin_url(), $mess_template);
        	$body_rs = str_replace('{{2}}', $security_code, $body);

        	$message = $client->messages->create(
               'whatsapp:'.$staff->whatsapp_number,
                array(
                        "body" => $body_rs,
                        "from" => 'whatsapp:'.$twilio_number
                    )
            );

        	$data['staff'] = $staff->staffid;
            $data['type'] = 'whatsapp';
            if($message->sid){
            	$data['status'] = 'success';
            	$data['mess'] = 'Send security code to '.$staff->phonenumber.' via Whatsapp successfully';
            	$this->send_code_log($data);
            	$result++;
            }else{
            	$data['status'] = 'success';
            	$data['mess'] = 'Send security code to '.$staff->phonenumber.' via Whatsapp successfully';
            	$this->send_code_log($data);
            }
        }

        if($result > 0){
        	return true;
        }
        return false;

	}

	//send sms with setting
	/**
	 * sendSMS
	 * @param  [type] $request 
	 * @return [type]          
	 */
	public function sendSMS($request, $to) {


        if (get_option('sms_twilio_active') == 1) {
            return $this->twilioSms($request,$to);
        }
        else if (get_option('sms_clickatell_active') == 1) {

            return $this->clickatellSms($request,$to);
            
        }
        else if (get_option('sms_msg91_active') == 1) {
            return $this->msg91Sms($request,$to);
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
	 * { delete old security code }
	 *
	 * @param      $staff  The staff
	 *
	 * @return     boolean  
	 */
	public function delete_old_security_code($staff){
		$this->db->where('staff', $staff);
		$this->db->delete(db_prefix().'mfa_security_code');
		if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
	}

	/**
	 * { function_description }
	 *
	 * @param        $staff   The staff
	 * @param        $type    The type
	 * @param        $status  The status
	 */
	public function mfa_history_login($staff, $type, $status){
		$this->db->insert(db_prefix().'mfa_history_login', [
			'staff' => $staff,
			'type' => $type,
			'status' => $status,
			'mess' => '',
			'time' => date('Y-m-d H:i:s')
		]);
		if($this->db->insert_id()){
			return true;
		}
		return false;
	}

	/**
	 * Sends code history.
	 */
	public function send_code_log($data){
		$this->db->insert(db_prefix().'mfa_send_code_logs', [
			'staff' => $data['staff'],
			'type' => $data['type'],
			'status' => $data['status'],
			'mess' => $data['mess'],
			'time' => date('Y-m-d H:i:s')
		]);
		if($this->db->insert_id()){
			return true;
		}
		return false;
	}

	/**
	 * { delete history }
	 *
	 * @return     boolean  
	 */
	public function delete_history(){
		$affected_rows = 0;
		$this->db->where('1 = 1');
		$this->db->delete(db_prefix().'mfa_history_login');
		if ($this->db->affected_rows() > 0) {
            $affected_rows++;
        }
        
        $this->db->where('1 = 1');
		$this->db->delete(db_prefix().'mfa_send_code_logs');
		if ($this->db->affected_rows() > 0) {
            $affected_rows++;
        }

        if($affected_rows > 0){
        	return true;
        }
        return false;
	}

	/**
	 * { Mfa chart rp }
	 *
	 * @param      string  $year          The year
	 * @param      string  $login status  The load type rp
	 *
	 * @return     array    
	 */
	public function login_per_month_rp($year = '', $login_status = '', $staffs = []){
		if($year == ''){
			$year = date('Y');
		}

		$where = '';
		if($login_status == 'all'){
			$where = '';
		}else if($login_status == 'success'){
			$where = ' AND status = "success"';
		}else if($login_status == 'fail'){
			$where = ' AND status = "fail"';
		}

		if(!is_admin()){
			$where .= ' AND mhl.staff = '.get_staff_user_id();
		}else{

			if(is_array($staffs)){
				$_staffs = [];
	            foreach ($staffs as $staff) {
	                if ($staff != '') {
	                    array_push($_staffs, $staff);
	                }
	            }
		        if (count($_staffs) > 0) {
		            $where .= ' AND mhl.staff IN (' . implode(', ', $_staffs) . ')';
		        }
		    }
		}

		$data_rs = [];


		$data_type = ['google_authenticator', 'whatsapp', 'sms'];

		foreach($data_type as $dttype){

			$query = $this->db->query('SELECT DATE_FORMAT(mhl.time, "%m") AS month, Count(*) as count FROM '.db_prefix().'mfa_history_login mhl Where type = "'.$dttype.'" AND DATE_FORMAT(mhl.time, "%Y") = "'.$year.'" '.$where.' group by month')->result_array();
			

			$result = [];
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;
			$result[] = 0;

			foreach ($query as $value) {
				if($value['count'] > 0){
					$result[$value['month'] - 1] =  (int)$value['count'];
				}
			}

			$data_rs[] = ['name' => _l('mfa_'.$dttype), 'data' => $result];
		}

		return $data_rs;
	}

	/**
	 * Sends a test message.
	 *
	 * @param        $data   The data
	 */
	public function send_test_message($data){
		$account_sid = $data['account_sid'];
		$auth_token = $data['auth_token'];
		$twilio_number = $data['twilio_phone_number'];
		$to = $data['phone_number'];
		$mess_template = $data['mess_template'];

		if($account_sid == '' || $auth_token == '' || $twilio_number == '' || $to == '' || $mess_template == ''){
			return false;
		}

		$client = new Client($account_sid, $auth_token);
    	$body = str_replace('{{1}}', admin_url(), $mess_template);
    	$body_rs = str_replace('{{2}}', '123456', $body);

    	$message = $client->messages->create(
           'whatsapp:'.$to,
            array(
                    "body" => $body_rs,
                    "from" => 'whatsapp:'.$twilio_number
                )
        );

        if($message->sid){
        	return true;
        }
        return false;
	}
}