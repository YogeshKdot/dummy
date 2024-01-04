<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Team password model
 */
class Team_password_model extends App_Model {
	/**
	 * add category management
	 * @param object $data
	 * @return int $insert_id
	 */

	public function add_category_management($data) {
		$this->db->insert(db_prefix() . 'team_password_category', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	/**
	 * update category management
	 * @param object $data
	 * @return bool
	 */
	public function update_category_management($data) {
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'team_password_category', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete category management
	 * @param int $id
	 * @return bool
	 */
	public function delete_category_management($id) {
		$ids_rs = $this->get_tree_cate_ids($id);
		$result = 0;
		foreach($ids_rs as $rs){
			$this->db->where('id', $rs);
			$this->db->delete(db_prefix() . 'team_password_category');
			if ($this->db->affected_rows() > 0) {
				$result++;
			}

			$this->db->where('mgt_id', $rs);
			$normal = $this->db->get(db_prefix().'tp_normal')->result_array();
			foreach($normal as $nor){
				$rs_nor = $this->delete_normal($nor['id']);
				if($rs_nor == true){
					$result++;
				}
			}

			$this->db->where('mgt_id', $rs);
			$bank_account = $this->db->get(db_prefix().'tp_bank_account')->result_array();
			foreach($bank_account as $bnk){
				$rs_bnk = $this->delete_bank_account($bnk['id']);
				if($rs_bnk == true){
					$result++;
				}
			}

			$this->db->where('mgt_id', $rs);
			$credit_card = $this->db->get(db_prefix().'tp_credit_card')->result_array();
			foreach($credit_card as $cre){
				$rs_cre = $this->delete_credit_card($cre['id']);
				if($rs_cre == true){
					$result++;
				}
			}

			$this->db->where('mgt_id', $rs);
			$email = $this->db->get(db_prefix().'tp_email')->result_array();
			foreach($email as $em){
				$rs_em = $this->delete_email($em['id']);
				if($rs_em == true){
					$result++;
				}
			}

			$this->db->where('mgt_id', $rs);
			$server = $this->db->get(db_prefix().'tp_server')->result_array();
			foreach($server as $sv){
				$rs_sv = $this->delete_server($sv['id']);
				if($rs_sv == true){
					$result++;
				}
			}

			$this->db->where('mgt_id', $rs);
			$software_license = $this->db->get(db_prefix().'tp_software_license')->result_array();
			foreach($software_license as $sw){
				$rs_sw = $this->delete_server($sw['id']);
				if($rs_sw == true){
					$result++;
				}
			}
		}

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'team_password_category');
		if ($this->db->affected_rows() > 0) {
			$result++;
		}

		if($result > 0){
			return true;
		}
		
		return false;
	}
	/**
	 * get category management
	 * @param int $id
	 * @return object
	 */
	public function get_category_management($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'team_password_category')->row();
		} else {
			return $this->db->get(db_prefix() . 'team_password_category')->result_array();
		}
	}

	/**
	 * add normal
	 * @param object $data
	 * @return int $insert_id
	 */
	public function add_normal($data) {
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['add_from'] = get_staff_user_id();
		$data['password'] = AES_256_Encrypt($data['password']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->insert(db_prefix() . 'tp_normal', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			if($data['relate_to'] == 'contract'){
				if($data['relate_id'] != ''){
					$this->load->model('contracts_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$contract = $this->contracts_model->get($rlid);
						if($contract){
							$contact_id = get_primary_contact_user_id($contract->client);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'normal';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}elseif($data['relate_to'] == 'project'){
				if($data['relate_id'] != ''){
					$this->load->model('projects_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$project = $this->projects_model->get($rlid);
						if($project){
							$contact_id = get_primary_contact_user_id($project->clientid);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'normal';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}

			$this->db->where('type','category');
			$this->db->where('share_id', $data['mgt_id']);
			$cate_shared = $this->db->get(db_prefix().'tp_share')->result_array();
			if(count($cate_shared) > 0){
				foreach($cate_shared as $cs){
					$shared_data['share_id'] = $insert_id;
					$shared_data['type'] = 'normal';
					$shared_data['client'] = $cs['client'];
					$shared_data['email'] = $cs['email'];
					$shared_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
					$shared_data['read'] = 'on';
					$shared_data['write'] = 'on';
					$shared_data['creator'] = get_staff_user_id();
					$shared_data['datecreator'] = date('Y-m-d H:i:s');
					$this->add_share($shared_data);
				}
			}

			$this->notification_to_staff($insert_id,'normal','create');

			return $insert_id;
		}
		return false;
	}
	/**
	 * delete normal
	 * @param int $id
	 * @return bool
	 */
	public function delete_normal($id) {
		$this->db->where('type','normal');
		$this->db->where('obj_id',$id);
		$this->db->delete(db_prefix().'permission');

		$this->db->where('type','normal');
		$this->db->where('share_id',$id);
		$this->db->delete(db_prefix().'tp_share');

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'tp_normal');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * update normal
	 * @param object $data
	 * @return bool
	 */
	public function update_normal($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = '';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['password'] = AES_256_Encrypt($data['password']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_normal', $data);
		if ($this->db->affected_rows() > 0) {

			$this->notification_to_staff($data['id'],'normal','changing');

			$this->log_password_action($data['id'],'normal','change');

			return true;
		}
		return false;
	}
	/**
	 * get normal
	 * @param int $id
	 * @return object
	 */
	public function get_normal($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'tp_normal')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_normal')->result_array();
		}
	}
	/**
	 * add permission
	 * @param object $data
	 * @return int
	 */
	public function add_permission($data) {
		unset($data['id']);
		if (!isset($data['read'])) {
			$data['read'] = 'off';
		}
		if (!isset($data['write'])) {
			$data['write'] = 'off';
		}
		$inserted = 0;
		foreach ($data['staff'] as $item) {
			$data_add['staff'] = $item;
			$data_add['r'] = $data['read'];
			$data_add['w'] = $data['write'];
			$data_add['obj_id'] = $data['obj_id'];
			$data_add['type'] = $data['type'];

			$this->db->where('staff', $item);
			$this->db->where('type', $data['type']);
			$this->db->where('obj_id', $data['obj_id']);
			$data_permission = $this->db->get(db_prefix() . 'permission')->row();
			if ($data_permission) {
				$this->db->where('staff', $item);
				$this->db->where('type', $data['type']);
				$this->db->where('obj_id', $data['obj_id']);
				$this->db->update(db_prefix() . 'permission', $data_add);
				if ($this->db->affected_rows() > 0) {
					$inserted++;
				}
			} else {
				$this->db->insert(db_prefix() . 'permission', $data_add);
				$insert_id = $this->db->insert_id();
				if ($insert_id) {
					$inserted++;
				}
			}
		}
		if ($inserted != 0) {
			return 1;
		}
		return 0;
	}
	/**
	 * delete permission
	 * @param object $data
	 * @return int
	 */
	public function delete_permision($id) {
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'permission');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * get contact
	 * @param int $userid
	 * @return object
	 */
	public function get_contact($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'contacts')->row();
		} else {
			return $this->db->get(db_prefix() . 'contacts')->result_array();
		}
	}
	/**
	 * add share
	 * @param object $data
	 * @return int $insert_id
	 */
	public function add_share($data) {
		unset($data['id']);
		if (!isset($data['read'])) {
			$data['r'] = 'off';
		} else {
			$data['r'] = $data['read'];
		}
		if (!isset($data['write'])) {
			$data['w'] = 'off';
		} else {
			$data['w'] = $data['write'];
		}
		if (!isset($data['not_in_the_system'])) {
			$data['not_in_the_system'] = 'off';
		}
		$data['effective_time'] = to_sql_date($data['effective_time'], true);
		$data['hash'] = app_generate_hash();
		unset($data['read']);
		unset($data['write']);
		$this->db->insert(db_prefix() . 'tp_share', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			$email = '';
			if ($data['not_in_the_system'] == 'off') {
				$email = $data['client'];
			} else {
				$email = $data['email'];
			}

			$this->db->where('id', $insert_id);
			$share = $this->db->get(db_prefix() . 'tp_share')->row();

			$template = mail_template('teampassword_share_item_to_contact', 'team_password', array_to_object($share));

			$template->send();

		}
		return $insert_id;
	}

	/**
	 * add share cate
	 * @param object $data
	 * @return int $insert_id
	 */
	public function add_share_cate($data) {
		unset($data['id']);
		if (!isset($data['read'])) {
			$data['r'] = 'off';
		} else {
			$data['r'] = $data['read'];
		}
		if (!isset($data['write'])) {
			$data['w'] = 'off';
		} else {
			$data['w'] = $data['write'];
		}
		if (!isset($data['not_in_the_system'])) {
			$data['not_in_the_system'] = 'off';
		}
		$data['effective_time'] = to_sql_date($data['effective_time'], true);
		$data['hash'] = app_generate_hash();
		unset($data['read']);
		unset($data['write']);
		$this->db->insert(db_prefix() . 'tp_share', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			return $insert_id;
		}
		return false;
		
	}
	/**
	 * update share
	 * @param object $data
	 * @return bool
	 */
	public function update_share($data) {
		if (!isset($data['read'])) {
			$data['r'] = 'off';
		} else {
			$data['r'] = $data['read'];
		}
		if (!isset($data['write'])) {
			$data['w'] = 'off';
		} else {
			$data['w'] = $data['write'];
		}
		if (!isset($data['not_in_the_system'])) {
			$data['not_in_the_system'] = 'off';
		}
		$data['effective_time'] = to_sql_date($data['effective_time'], true);

		unset($data['read']);
		unset($data['write']);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_share', $data);
		if ($this->db->affected_rows() > 0) {
			$email = '';
			if ($data['not_in_the_system'] == 'off') {
				$email = $data['client'];
			} else {
				$email = $data['email'];
			}

			$this->db->where('id', $data['id']);
			$share = $this->db->get(db_prefix() . 'tp_share')->row();

			$template = mail_template('teampassword_share_item_to_contact', 'team_password', array_to_object($share));

			$template->send();

			return true;
		}
		return false;
	}

	/**
	 * update share
	 * @param object $data
	 * @return bool
	 */
	public function update_share_cate($data) {
		if (!isset($data['read'])) {
			$data['r'] = 'off';
		} else {
			$data['r'] = $data['read'];
		}
		if (!isset($data['write'])) {
			$data['w'] = 'off';
		} else {
			$data['w'] = $data['write'];
		}
		if (!isset($data['not_in_the_system'])) {
			$data['not_in_the_system'] = 'off';
		}
		$data['effective_time'] = to_sql_date($data['effective_time'], true);

		unset($data['read']);
		unset($data['write']);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_share', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * delete share
	 * @param int $id
	 * @return bool
	 */
	public function delete_share($id) {
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'tp_share');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * get name object
	 * @param  string $type
	 * @param  int $obj_id
	 * @return string objectname
	 */
	public function get_name_obj($type, $obj_id) {
		$name_obj = '';
		switch ($type) {
		case 'normal':
			$data_obj = $this->get_normal($obj_id);
			if ($data_obj) {
				$name_obj = $data_obj->name;
			}
			break;
		case 'bank_account':
			$data_obj = $this->get_bank_account($obj_id);
			if ($data_obj) {
				$name_obj = $data_obj->name;
			}
			break;
		case 'credit_card':
			$data_obj = $this->get_credit_card($obj_id);
			if ($data_obj) {
				$name_obj = $data_obj->name;
			}
			break;
		case 'email':
			$data_obj = $this->get_email($obj_id);
			if ($data_obj) {
				$name_obj = $data_obj->name;
			}
			break;
		case 'server':
			$data_obj = $this->get_server($obj_id);
			if ($data_obj) {
				$name_obj = $data_obj->name;
			}
			break;
		case 'software_license':
			$data_obj = $this->get_software_license($obj_id);
			if ($data_obj) {
				$name_obj = $data_obj->name;
			}
			break;
		}
		return $name_obj;
	}

	/**
	 * send mail
	 * @param object $data
	 * @return bool
	 */
	public function send_mail($data) {
		$staff_id = get_staff_user_id();
		$inbox = array();
		$inbox['to'] = implode(',', $data['email']);
		$inbox['sender_name'] = get_staff_full_name($staff_id);
		$inbox['subject'] = _strip_tags($data['subject']);
		$inbox['body'] = _strip_tags($data['content']);
		$inbox['body'] = nl2br_save_html($inbox['body']);
		$inbox['date_received'] = date('Y-m-d H:i:s');
		$inbox['from_email'] = get_option('smtp_email');
		if (strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0) {

			$ci = &get_instance();
			$ci->email->initialize();
			$ci->load->library('email');
			$ci->email->clear(true);
			$ci->email->from($inbox['from_email'], $inbox['sender_name']);
			$ci->email->to($inbox['to']);

			$ci->email->subject($inbox['subject']);
			$ci->email->message($inbox['body']);

			$ci->email->send(true);
		}
		return true;
	}
	/**
	 * get data tp_normal
	 * @param int $id
	 * @return object
	 */
	public function get_tp_normal($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'tp_normal')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_normal')->result_array();
		}
	}
	/**
	 * get data tp_normal by mgt_id
	 * @param int $mgt_id
	 * @return object
	 */
	public function get_tp_normal_mgt_id($mgt_id = '') {
		if ($mgt_id != '') {
			$this->db->where('mgt_id', $mgt_id);
			return $this->db->get(db_prefix() . 'tp_normal')->result_array();
		} else {
			return $this->db->get(db_prefix() . 'tp_normal')->result_array();
		}
	}
	/**
	 * get tp_share by hash
	 * @param string $hash
	 * @return object
	 */
	public function get_tp_share_hash($hash = '') {
		if ($hash != '') {
			$this->db->where('hash', $hash);
			return $this->db->get(db_prefix() . 'tp_share')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_share')->result_array();
		}
	}
	/**
	 * get contact by email
	 * @param string $email
	 * @return object
	 */
	public function get_contact_by_email($email = '') {
		if ($email != '') {
			$this->db->where('email', $email);
			return $this->db->get(db_prefix() . 'contacts')->row();
		} else {
			return $this->db->get(db_prefix() . 'contacts')->result_array();
		}
	}

	/**
	 * add bank account
	 * @param object $data
	 * @return int $insert_id
	 */
	public function add_bank_account($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['add_from'] = get_staff_user_id();
		$data['pin'] = AES_256_Encrypt($data['pin']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->insert(db_prefix() . 'tp_bank_account', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			if($data['relate_to'] == 'contract'){
				if($data['relate_id'] != ''){
					$this->load->model('contracts_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$contract = $this->contracts_model->get($rlid);
						if($contract){
							$contact_id = get_primary_contact_user_id($contract->client);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'bank_account';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}elseif($data['relate_to'] == 'project'){
				if($data['relate_id'] != ''){
					$this->load->model('projects_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$project = $this->projects_model->get($rlid);
						if($project){
							$contact_id = get_primary_contact_user_id($project->clientid);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'bank_account';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}

			$this->db->where('type','category');
			$this->db->where('share_id', $data['mgt_id']);
			$cate_shared = $this->db->get(db_prefix().'tp_share')->result_array();
			if(count($cate_shared) > 0){
				foreach($cate_shared as $cs){
					$shared_data['share_id'] = $insert_id;
					$shared_data['type'] = 'bank_account';
					$shared_data['client'] = $cs['client'];
					$shared_data['email'] = $cs['email'];
					$shared_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
					$shared_data['read'] = 'on';
					$shared_data['write'] = 'on';
					$shared_data['creator'] = get_staff_user_id();
					$shared_data['datecreator'] = date('Y-m-d H:i:s');
					$this->add_share($shared_data);
				}
			}

			$this->notification_to_staff($insert_id,'bank_account','create');

			return $insert_id;
		}
		return false;
	}

	/**
	 * delete bank account
	 * @param int $id
	 * @return bool
	 */
	public function delete_bank_account($id) {
		$this->db->where('type','bank_account');
		$this->db->where('obj_id',$id);
		$this->db->delete(db_prefix().'permission');

		$this->db->where('type','bank_account');
		$this->db->where('share_id',$id);
		$this->db->delete(db_prefix().'tp_share');

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'tp_bank_account');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * update bank account
	 * @param object $data
	 * @return bool
	 */
	public function update_bank_account($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['pin'] = AES_256_Encrypt($data['pin']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_bank_account', $data);
		if ($this->db->affected_rows() > 0) {

			$this->notification_to_staff($data['id'],'bank_account','changing');

			$this->log_password_action($data['id'],'bank_account','change');

			return true;
		}
		return false;
	}

	/**
	 * get bank account
	 * @param int $id
	 * @return object
	 */
	public function get_bank_account($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'tp_bank_account')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_bank_account')->result_array();
		}
	}
	/**
	 * get data from tp_bank_account table by mgt_id
	 * @param int $mgt_id
	 * @return object
	 */

	public function get_tp_bank_account_mgt_id($mgt_id = '') {
		if ($mgt_id != '') {
			$this->db->where('mgt_id', $mgt_id);
			return $this->db->get(db_prefix() . 'tp_bank_account')->result_array();
		} else {
			return $this->db->get(db_prefix() . 'tp_bank_account')->result_array();
		}
	}

	/**
	 * add credit card
	 * @param object $data
	 * @return int $insert_id
	 */
	public function add_credit_card($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['add_from'] = get_staff_user_id();
		$data['valid_from'] = to_sql_date($data['valid_from']);
		$data['valid_to'] = to_sql_date($data['valid_to']);
		$data['pin'] = AES_256_Encrypt($data['pin']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->insert(db_prefix() . 'tp_credit_card', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			if($data['relate_to'] == 'contract'){
				if($data['relate_id'] != ''){
					$this->load->model('contracts_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$contract = $this->contracts_model->get($rlid);
						if($contract){
							$contact_id = get_primary_contact_user_id($contract->client);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'credit_card';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}elseif($data['relate_to'] == 'project'){
				if($data['relate_id'] != ''){
					$this->load->model('projects_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$project = $this->projects_model->get($rlid);
						if($project){
							$contact_id = get_primary_contact_user_id($project->clientid);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'credit_card';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}

			$this->db->where('type','category');
			$this->db->where('share_id', $data['mgt_id']);
			$cate_shared = $this->db->get(db_prefix().'tp_share')->result_array();
			if(count($cate_shared) > 0){
				foreach($cate_shared as $cs){
					$shared_data['share_id'] = $insert_id;
					$shared_data['type'] = 'credit_card';
					$shared_data['client'] = $cs['client'];
					$shared_data['email'] = $cs['email'];
					$shared_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
					$shared_data['read'] = 'on';
					$shared_data['write'] = 'on';
					$shared_data['creator'] = get_staff_user_id();
					$shared_data['datecreator'] = date('Y-m-d H:i:s');
					$this->add_share($shared_data);
				}
			}

			$this->notification_to_staff($insert_id,'credit_card','create');

			return $insert_id;
		}
		return false;
	}

	/**
	 * delete data from 'tp_credit_card' table
	 * @param int $id
	 * @return bool
	 */
	public function delete_credit_card($id) {
		$this->db->where('type','credit_card');
		$this->db->where('obj_id',$id);
		$this->db->delete(db_prefix().'permission');

		$this->db->where('type','credit_card');
		$this->db->where('share_id',$id);
		$this->db->delete(db_prefix().'tp_share');

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'tp_credit_card');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * update data to 'tp_credit_card' table
	 * @param object $data
	 * @return bool
	 */
	public function update_credit_card($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['valid_from'] = to_sql_date($data['valid_from']);
		$data['valid_to'] = to_sql_date($data['valid_to']);
		$data['pin'] = AES_256_Encrypt($data['pin']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_credit_card', $data);
		if ($this->db->affected_rows() > 0) {

			$this->notification_to_staff($data['id'],'credit_card','changing');

			$this->log_password_action($data['id'],'credit_card','change');

			return true;
		}
		return false;
	}
	/**
	 * get  data from 'tp_credit_card' table
	 * @param int $id
	 * @return object
	 */
	public function get_credit_card($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'tp_credit_card')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_credit_card')->result_array();
		}
	}
	/**
	 * get data from tp_credit_card table by mgt_id
	 * @param int $id
	 * @return object
	 */
	public function get_tp_credit_card_mgt_id($mgt_id = '') {
		if ($mgt_id != '') {
			$this->db->where('mgt_id', $mgt_id);
			return $this->db->get(db_prefix() . 'tp_credit_card')->result_array();
		} else {
			return $this->db->get(db_prefix() . 'tp_credit_card')->result_array();
		}
	}
	public function get_data_share($email = '') {
		return $this->db->query('select * from ' . db_prefix() . 'tp_share where client = \'' . $email . '\' or email = \'' . $email . '\'')->result_array();
	}

	/**
	 * add data to 'tp_email' table
	 * @param object $data
	 * @return int $insert_id
	 */
	public function add_email($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['add_from'] = get_staff_user_id();
		$data['password'] = AES_256_Encrypt($data['password']);
		$data['smtp_password'] = AES_256_Encrypt($data['smtp_password']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->insert(db_prefix() . 'tp_email', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			if($data['relate_to'] == 'contract'){
				if($data['relate_id'] != ''){
					$this->load->model('contracts_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$contract = $this->contracts_model->get($rlid);
						if($contract){
							$contact_id = get_primary_contact_user_id($contract->client);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'email';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}elseif($data['relate_to'] == 'project'){
				if($data['relate_id'] != ''){
					$this->load->model('projects_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$project = $this->projects_model->get($rlid);
						if($project){
							$contact_id = get_primary_contact_user_id($project->clientid);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'email';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}

			$this->db->where('type','category');
			$this->db->where('share_id', $data['mgt_id']);
			$cate_shared = $this->db->get(db_prefix().'tp_share')->result_array();
			if(count($cate_shared) > 0){
				foreach($cate_shared as $cs){
					$shared_data['share_id'] = $insert_id;
					$shared_data['type'] = 'email';
					$shared_data['client'] = $cs['client'];
					$shared_data['email'] = $cs['email'];
					$shared_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
					$shared_data['read'] = 'on';
					$shared_data['write'] = 'on';
					$shared_data['creator'] = get_staff_user_id();
					$shared_data['datecreator'] = date('Y-m-d H:i:s');
					$this->add_share($shared_data);
				}
			}

			$this->notification_to_staff($insert_id,'email','create');

			return $insert_id;
		}
		return false;
	}

	/**
	 * delete data from 'tp_email' table
	 * @param int $id
	 * @return bool
	 */
	public function delete_email($id) {
		$this->db->where('type','email');
		$this->db->where('obj_id',$id);
		$this->db->delete(db_prefix().'permission');

		$this->db->where('type','email');
		$this->db->where('share_id',$id);
		$this->db->delete(db_prefix().'tp_share');

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'tp_email');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * update data to 'tp_email' table
	 * @param object $data
	 * @return bool
	 */
	public function update_email($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['password'] = AES_256_Encrypt($data['password']);
		$data['smtp_password'] = AES_256_Encrypt($data['smtp_password']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_email', $data);
		if ($this->db->affected_rows() > 0) {

			$this->notification_to_staff($data['id'],'email','changing');

			$this->log_password_action($data['id'],'email','change');

			return true;
		}
		return false;
	}
	/**
	 * get data from 'tp_email' table
	 * @param int $id
	 * @return object
	 */
	public function get_email($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'tp_email')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_email')->result_array();
		}
	}

	/**
	 * add data to "tp_server" table
	 * @param object $data
	 * @return int $insert_id
	 */
	public function add_server($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['add_from'] = get_staff_user_id();
		$data['password'] = AES_256_Encrypt($data['password']);
		$data['custom_field'] = json_encode($array_custom);
		$this->db->insert(db_prefix() . 'tp_server', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			if($data['relate_to'] == 'contract'){
				if($data['relate_id'] != ''){
					$this->load->model('contracts_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$contract = $this->contracts_model->get($rlid);
						if($contract){
							$contact_id = get_primary_contact_user_id($contract->client);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'server';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}elseif($data['relate_to'] == 'project'){
				if($data['relate_id'] != ''){
					$this->load->model('projects_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$project = $this->projects_model->get($rlid);
						if($project){
							$contact_id = get_primary_contact_user_id($project->clientid);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'server';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}

			$this->db->where('type','category');
			$this->db->where('share_id', $data['mgt_id']);
			$cate_shared = $this->db->get(db_prefix().'tp_share')->result_array();
			if(count($cate_shared) > 0){
				foreach($cate_shared as $cs){
					$shared_data['share_id'] = $insert_id;
					$shared_data['type'] = 'server';
					$shared_data['client'] = $cs['client'];
					$shared_data['email'] = $cs['email'];
					$shared_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
					$shared_data['read'] = 'on';
					$shared_data['write'] = 'on';
					$shared_data['creator'] = get_staff_user_id();
					$shared_data['datecreator'] = date('Y-m-d H:i:s');
					$this->add_share($shared_data);
				}
			}

			$this->notification_to_staff($insert_id,'server','create');

			return $insert_id;
		}
		return false;
	}

	/**
	 * delete data to "tp_server" table
	 * @param int $id
	 * @return bool
	 */
	public function delete_server($id) {
		$this->db->where('type','server');
		$this->db->where('obj_id',$id);
		$this->db->delete(db_prefix().'permission');

		$this->db->where('type','server');
		$this->db->where('share_id',$id);
		$this->db->delete(db_prefix().'tp_share');

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'tp_server');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * update data "tp_server" table
	 * @param object $data
	 * @return bool
	 */
	public function update_server($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['password'] = AES_256_Encrypt($data['password'], 'abcderfsdfsghytg');
		$data['custom_field'] = json_encode($array_custom);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_server', $data);
		if ($this->db->affected_rows() > 0) {

			$this->notification_to_staff($data['id'],'server','changing');

			$this->log_password_action($data['id'],'server','change');

			return true;
		}
		return false;
	}

	/**
	 * get data "tp_server" table
	 * @param int $id
	 * @return object
	 */
	public function get_server($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'tp_server')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_server')->result_array();
		}
	}

	/**
	 * add data "tp_software_license" table
	 * @param object $data
	 * @return bool
	 */
	public function add_software_license($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['add_from'] = get_staff_user_id();
		$data['custom_field'] = json_encode($array_custom);
		$this->db->insert(db_prefix() . 'tp_software_license', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			if($data['relate_to'] == 'contract'){
				if($data['relate_id'] != ''){
					$this->load->model('contracts_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$contract = $this->contracts_model->get($rlid);
						if($contract){
							$contact_id = get_primary_contact_user_id($contract->client);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'software_license';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}elseif($data['relate_to'] == 'project'){
				if($data['relate_id'] != ''){
					$this->load->model('projects_model');
					$relate_id = explode(',', $data['relate_id']);
					foreach($relate_id as $rlid){
						$project = $this->projects_model->get($rlid);
						if($project){
							$contact_id = get_primary_contact_user_id($project->clientid);
							$this->db->where('id',$contact_id);
							$ct = $this->db->get(db_prefix().'contacts')->row();
							if($ct){
								$share_data = [];
								$share_data['id'] = ''; 
								$share_data['share_id'] = $insert_id;
								$share_data['type'] = 'software_license';
								$share_data['client'] = $ct->email;
								$share_data['email'] = '';
								$share_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
								$share_data['read'] = 'on';
								$share_data['write'] = 'on';
								$share_data['creator'] = get_staff_user_id();
								$share_data['datecreator'] = date('Y-m-d H:i:s');
								$this->add_share($share_data);
							}
						}
					}
				}
			}

			$this->db->where('type','category');
			$this->db->where('share_id', $data['mgt_id']);
			$cate_shared = $this->db->get(db_prefix().'tp_share')->result_array();
			if(count($cate_shared) > 0){
				foreach($cate_shared as $cs){
					$shared_data['share_id'] = $insert_id;
					$shared_data['type'] = 'software_license';
					$shared_data['client'] = $cs['client'];
					$shared_data['email'] = $cs['email'];
					$shared_data['effective_time'] = date('Y-m-d H:i:s',strtotime("+1 week"));
					$shared_data['read'] = 'on';
					$shared_data['write'] = 'on';
					$shared_data['creator'] = get_staff_user_id();
					$shared_data['datecreator'] = date('Y-m-d H:i:s');
					$this->add_share($shared_data);
				}
			}

			$this->notification_to_staff($insert_id,'software_license','create');

			return $insert_id;
		}
		return false;
	}

	/**
	 * delete data "tp_software_license" table
	 * @param int $id
	 * @return bool
	 */
	public function delete_software_license($id) {
		$this->db->where('type','software_license');
		$this->db->where('obj_id',$id);
		$this->db->delete(db_prefix().'permission');

		$this->db->where('type','software_license');
		$this->db->where('share_id',$id);
		$this->db->delete(db_prefix().'tp_share');
		
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'tp_software_license');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * update data "tp_software_license" table
	 * @param object $data
	 * @return bool
	 */
	public function update_software_license($data) {
		if (!isset($data['enable_log'])) {
			$data['enable_log'] = 'off';
		}
		$array_custom = [];
		if (isset($data['field_name'])) {
			if (count($data['field_name']) > 0) {
				foreach ($data['field_name'] as $key => $value) {
					array_push($array_custom, array('name' => $value, 'value' => $data['field_value'][$key]));
				}
				unset($data['field_name']);
				unset($data['field_value']);
			}
		}

		if(isset($data['relate_id']) && count($data['relate_id']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id']);
		}

		if(isset($data['relate_id_project']) && count($data['relate_id_project']) > 0){
			$data['relate_id'] = implode(',', $data['relate_id_project']);
			unset($data['relate_id_project']);
		}

		$data['custom_field'] = json_encode($array_custom);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'tp_software_license', $data);
		if ($this->db->affected_rows() > 0) {

			$this->notification_to_staff($data['id'],'software_license','changing');

			$this->log_password_action($data['id'],'software_license','change');

			return true;
		}
		return false;
	}
	/**
	 * update data "tp_software_license" table
	 * @param int $id
	 * @return object
	 */
	public function get_software_license($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'tp_software_license')->row();
		} else {
			return $this->db->get(db_prefix() . 'tp_software_license')->result_array();
		}
	}
	/**
	 * check_format_date
	 * @param  datetime $date
	 * @return bool
	 */
	public function check_format_date($date) {
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4]):?((0|[0-5][0-9]):?(0|[0-5][0-9])|6000|60:00)$/", $date)) {
			return true;
		} else {
			return false;
		}
	}
	public function check_format_date_ymd($date) {
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Gets the item relate contract.
	 *
	 * @param      $contract  The contract
	 * @param      string  $type      The type
	 *
	 * @return      The item relate contract.
	 */
	public function get_item_relate_contract($contract, $type) {
		if ($type == 'bank_account') {
			$items = $this->db->query('select * from ' . db_prefix() . 'tp_bank_account where relate_to = "contract" and find_in_set('.$contract.', relate_id) ')->result_array();
		} elseif ($type == 'credit_card') {
			$items = $this->db->query('select * from ' . db_prefix() . 'tp_credit_card where relate_to = "contract" and find_in_set('.$contract.', relate_id) ')->result_array();
		} elseif ($type == 'email') {
			$items = $this->db->query('select * from ' . db_prefix() . 'tp_email where relate_to = "contract" and find_in_set('.$contract.', relate_id) ')->result_array();
		} elseif ($type == 'normal') {
			$items = $this->db->query('select * from ' . db_prefix() . 'tp_normal where relate_to = "contract" and find_in_set('.$contract.', relate_id) ')->result_array();
		} elseif ($type == 'server') {
			$items = $this->db->query('select * from ' . db_prefix() . 'tp_server where relate_to = "contract" and find_in_set('.$contract.', relate_id) ')->result_array();
		} elseif ($type == 'software_license') {
			$items = $this->db->query('select * from ' . db_prefix() . 'tp_software_license where relate_to = "contract" and find_in_set('.$contract.', relate_id) ')->result_array();
		}

		return $items;
	}

	/**
	 * Gets the item relate project.
	 *
	 * @param      $project  The project
	 * @param      string  $type      The type
	 *
	 * @return      The item relate project.
	 */
	public function get_item_relate_project($project, $type) {
		if ($type == 'bank_account') {
			$items = $this->db->query('select id, name, mgt_id, datecreator, pin from ' . db_prefix() . 'tp_bank_account where relate_to = "project" and find_in_set('.$project.', relate_id) ')->result_array();
		} elseif ($type == 'credit_card') {
			$items = $this->db->query('select id, name, mgt_id, datecreator, pin from ' . db_prefix() . 'tp_credit_card where relate_to = "project" and find_in_set('.$project.', relate_id) ')->result_array();
		} elseif ($type == 'email') {
			$items = $this->db->query('select id, name, mgt_id, datecreator, password from ' . db_prefix() . 'tp_email where relate_to = "project" and find_in_set('.$project.', relate_id) ')->result_array();
		} elseif ($type == 'normal') {
			$items = $this->db->query('select id, name, mgt_id, password from ' . db_prefix() . 'tp_normal where relate_to = "project" and find_in_set('.$project.', relate_id) ')->result_array();
		} elseif ($type == 'server') {
			$items = $this->db->query('select id, name, mgt_id, datecreator, password from ' . db_prefix() . 'tp_server where relate_to = "project" and find_in_set('.$project.', relate_id) ')->result_array();
		} elseif ($type == 'software_license') {
			$items = $this->db->query('select id, name, mgt_id, datecreator, license_key from ' . db_prefix() . 'tp_software_license where relate_to = "project" and find_in_set('.$project.', relate_id) ')->result_array();
		}

		return $items;
	}

	/**
	 * Gets the item by cate.
	 *
	 * @param        $cate   The cate
	 * @param      string  $type   The type
	 *
	 * @return      The item by cate.
	 */
	public function get_item_by_cate($cate, $type) {

		if ($type == 'bank_account') {
			$items = $this->db->query('select id, name, mgt_id, datecreator from ' . db_prefix() . 'tp_bank_account where mgt_id = ' . $cate)->result_array();
		} elseif ($type == 'credit_card') {
			$items = $this->db->query('select id, name, mgt_id, datecreator from ' . db_prefix() . 'tp_credit_card where mgt_id = ' . $cate)->result_array();
		} elseif ($type == 'email') {
			$items = $this->db->query('select id, name, mgt_id, datecreator from ' . db_prefix() . 'tp_email where mgt_id = ' . $cate)->result_array();
		} elseif ($type == 'normal') {
			$items = $this->db->query('select id, name, mgt_id from ' . db_prefix() . 'tp_normal where mgt_id = ' . $cate)->result_array();
		} elseif ($type == 'server') {
			$items = $this->db->query('select id, name, mgt_id, datecreator from ' . db_prefix() . 'tp_server where mgt_id = ' . $cate)->result_array();
		} elseif ($type == 'software_license') {
			$items = $this->db->query('select id, name, mgt_id, datecreator from ' . db_prefix() . 'tp_software_license where mgt_id = ' . $cate)->result_array();
		}
		return $items;
	}
	public function notifications($id_staff, $link, $description) {
		$notifiedUsers = [];
		$id_userlogin = get_staff_user_id();

		if ($id_staff == $id_userlogin) {
			$notified = add_notification([
				'fromuserid' => $id_userlogin,
				'description' => $description,
				'link' => $link,
				'touserid' => $id_staff,
				'additional_data' => serialize([
					$description,
				]),
			]);
			if ($notified) {
				array_push($notifiedUsers, $id_staff);
			}
		}
		pusher_trigger_notification($notifiedUsers);
	}

	/**
	 * { team password setting }
	 *
	 * @param  $data   The data
	 */
	public function team_password_setting($data) {
		$rs = 0;

		if(isset($data['hide_password_from_client_area'])){
			$data['hide_password_from_client_area'] = '1';
		}else{
			$data['hide_password_from_client_area'] = '0';
		}

		$this->db->where('name', 'hide_password_from_client_area');
		$this->db->update(db_prefix() . 'options', ['value' => $data['hide_password_from_client_area']]);
		if ($this->db->affected_rows() > 0) {
			$rs ++;
		}

		if($data['security_key'] != get_option('team_password_security')){
			$old_key = get_option('team_password_security');
			$new_key = $data['security_key'];
			$password['normal'] = $this->get_normal();
			$password['bank_account'] = $this->get_bank_account();
			$password['credit_card'] = $this->get_credit_card();
			$password['email'] = $this->get_email();
			$password['server'] = $this->get_server();


			foreach ($password as $type => $pass) {
				foreach($pass as $p){
					if($type == 'normal'){
						$nm_decrypt = AES_256_Decrypt_with_key($p['password'], $old_key);
						$nm_encrypt = AES_256_Encrypt_with_key($nm_decrypt, $new_key);

						$this->db->where('id',$p['id']);
						$this->db->update(db_prefix().'tp_normal',[
							'password' => $nm_encrypt,
						]);

					}elseif($type == 'bank_account'){
						$ba_decrypt = AES_256_Decrypt_with_key($p['pin'], $old_key);
						$ba_encrypt = AES_256_Encrypt_with_key($ba_decrypt, $new_key);
						$this->db->where('id',$p['id']);
						$this->db->update(db_prefix().'tp_bank_account',[
							'pin' => $ba_encrypt,
						]);
					}elseif($type == 'credit_card'){
						$cc_decrypt = AES_256_Decrypt_with_key($p['pin'], $old_key);
						$cc_encrypt = AES_256_Encrypt_with_key($cc_decrypt, $new_key);
						$this->db->where('id',$p['id']);
						$this->db->update(db_prefix().'tp_credit_card',[
							'pin' => $cc_encrypt,
						]);
					}elseif($type == 'email'){
						$ep_decrypt = AES_256_Decrypt_with_key($p['password'], $old_key);
						$ep_encrypt = AES_256_Encrypt_with_key($ep_decrypt, $new_key);

						$es_decrypt = AES_256_Decrypt_with_key($p['smtp_password'], $old_key);
						$es_encrypt = AES_256_Encrypt_with_key($es_decrypt, $new_key);

						$this->db->where('id',$p['id']);
						$this->db->update(db_prefix().'tp_email',[
							'password' => $ep_encrypt,
							'smtp_password' => $es_encrypt,
						]);
					}elseif($type == 'server'){
						$sv_decrypt = AES_256_Decrypt_with_key($p['password'], $old_key);
						$sv_encrypt = AES_256_Encrypt_with_key($sv_decrypt, $new_key);
						$this->db->where('id',$p['id']);
						$this->db->update(db_prefix().'tp_server',[
							'password' => $sv_encrypt,
						]);
					}
				}
			}
		}

		$this->db->where('name', 'team_password_security');
		$this->db->update(db_prefix() . 'options', ['value' => $data['security_key']]);
		if ($this->db->affected_rows() > 0) {
			$rs ++;
		}

		if($rs > 0){
			return true;
		}
		return false;
	}

	/**
     * Gets the file.
     *
     * @param         $id      The file id
     * @param      boolean  $rel_id  The relative identifier
     *
     * @return     boolean  The file.
     */
    public function get_file($id, $rel_id = false)
    {
        $this->db->where('id', $id);
        $file = $this->db->get(db_prefix().'files')->row();

        if ($file && $rel_id) {
            if ($file->rel_id != $rel_id) {
                return false;
            }
        }
        return $file;
    }

    /**
     * { delete team password item attachment }
     *
     * @param        $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_file_item($id,$type)
    {
        $attachment = $this->get_item_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(TEAM_PASSWORD_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(TEAM_PASSWORD_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(TEAM_PASSWORD_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(TEAM_PASSWORD_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Gets the item attachments.
     *
     * @param        $assets  The assets
     * @param      string  $id      The identifier
     *
     * @return      The item attachments.
     */
    public function get_item_attachments($assets, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        }
        $result = $this->db->get('tblfiles');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * Gets the count password dashboard.
     */
    public function get_count_password_dashboard(){
    	$rs = [];

		
		$rs['normal'] = total_rows(db_prefix().'tp_normal');
		$rs['bank_account'] = total_rows(db_prefix().'tp_bank_account');
		$rs['credit_card'] = total_rows(db_prefix().'tp_credit_card');
		$rs['email'] = total_rows(db_prefix().'tp_email');
		$rs['server'] = total_rows(db_prefix().'tp_server');
		$rs['software_license'] = total_rows(db_prefix().'tp_software_license');
		$rs['total'] = $rs['normal'] + $rs['bank_account'] + $rs['credit_card'] + $rs['email'] + $rs['server'] + $rs['software_license'];

		return $rs;
    }

    /**
     * { count password by category }
     *
     * @return     array  ( render chart )
     */
    public function count_password_by_category() {
		$plans = $this->get_category_management();

		$chart = [];

		foreach ($plans as $pl) {
			$normal = total_rows(db_prefix().'tp_normal',['mgt_id' => $pl['id']]);
			$bank_account = total_rows(db_prefix().'tp_bank_account',['mgt_id' => $pl['id']]);
			$credit_card = total_rows(db_prefix().'tp_credit_card',['mgt_id' => $pl['id']]);
			$server = total_rows(db_prefix().'tp_server',['mgt_id' => $pl['id']]);
			$software_license = total_rows(db_prefix().'tp_software_license',['mgt_id' => $pl['id']]);
			$email = total_rows(db_prefix().'tp_email',['mgt_id' => $pl['id']]);
			$total = $normal + $bank_account + $credit_card + $server + $software_license + $email;

			$chart[] = ['name' => $pl['category_name'], 'y' => $total, 'z'=>100]; 
		}

	
		return $chart;
	}

	/**
     * { count password by category }
     *
     * @return     array  ( render chart )
     */
    public function count_share_by_type() {

		$chart = [];	

		$normal = total_rows(db_prefix().'tp_share',['type' => 'normal']);
		$bank_account = total_rows(db_prefix().'tp_share',['type' => 'bank_account']);
		$credit_card = total_rows(db_prefix().'tp_share',['type' => 'credit_card']);
		$email = total_rows(db_prefix().'tp_share',['type' => 'email']);
		$server = total_rows(db_prefix().'tp_share',['type' => 'server']);
		$software_license = total_rows(db_prefix().'tp_share',['type' => 'software_license']);

		$chart[] = ['name' => _l('normal'), 'y' => $normal, 'z' => 100];
		$chart[] = ['name' => _l('bank_account'), 'y' => $bank_account, 'z' => 100];
		$chart[] = ['name' => _l('credit_card'), 'y' => $credit_card, 'z' => 100];
		$chart[] = ['name' => _l('email'), 'y' => $email, 'z' => 100];
		$chart[] = ['name' => _l('server'), 'y' => $server, 'z' => 100];
		$chart[] = ['name' => _l('software_license'), 'y' => $software_license, 'z' => 100];

		return $chart;
	}

	/**
	 * Gets your password shared.
	 */
	public function get_your_password_shared(){
		return $this->db->query('select * from '.db_prefix().'permission where staff = '.get_staff_user_id().' limit 5')->result_array();
	}

	/**
	 * { notification to staff }
	 *
	 * @param      <type>  $password  The password
	 * @param      <type>  $type      The type
	 */
	public function notification_to_staff($password, $pw_type ,$type){

		$this->load->model('staff_model');
		$staffs = $this->staff_model->get();

        $additional_data = item_name_by_objid($password,$pw_type);
        $mes_create = 'notify_send_create_password';
        $mes_changing = 'notify_send_changing_password';
        $link = 'team_password/view_'.$pw_type.'/' . $password;

        $mes = '';

        if($type == 'create'){
        	$mes = $mes_create;
        }else{
        	$mes = $mes_changing; 
        }

        foreach($staffs as $s){

        	if($s['admin'] == 1 || has_permission('team_password',$s['staffid'],'view') ){
				$notified = add_notification([
			        'description'     => $mes,
			        'touserid'        => $s['staffid'],
			        'link'            => $link,
			        'additional_data' => serialize([
			            $additional_data,
			        ]),
			    ]);
			    if ($notified) {
			        pusher_trigger_notification([$s['staffid']]);
			    }
			}
		}
	}

	/**
	 * Logs a password action.
	 *
	 * @param         $pass     The pass
	 * @param         $pw_type  The password type
	 * @param         $type     The type
	 *
	 * @return     boolean  
	 */
	public function log_password_action($pass, $pw_type, $type){ 
		$this->db->insert(db_prefix().'tp_logs',[
			'staff' => get_staff_user_id(),
			'type' => $type,
			'time' => date('Y-m-d H:i:s'),
			'rel_id' => $pass,
			'rel_type' => $pw_type,
		]);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}

		return false;
	}

	/**
	 * Gets the logs password.
	 *
	 * @param      $rel_id    The relative identifier
	 * @param      $rel_type  The relative type
	 */
	public function get_logs_password($rel_id, $rel_type){
		$this->db->where('rel_id', $rel_id);
		$this->db->where('rel_type', $rel_type);
		return $this->db->get(db_prefix().'tp_logs')->result_array();
	}

	/**
	 * { clear logs }
	 *
	 * @param      $rel_id    The relative identifier
	 * @param      $rel_type  The relative type
	 */
	public function clear_logs($rel_id, $rel_type){
		$this->db->where('rel_id',$rel_id);
		$this->db->where('rel_type',$rel_type);
		$this->db->delete(db_prefix().'tp_logs');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Gets the password expire.
	 *
	 * @return     <array>  List password expire.
	 */
	public function get_password_expire(){
		return $this->db->query('select * from '.db_prefix().'tp_share where effective_time > "'.date('Y-m-d H:i:s').'" order by effective_time asc limit 5')->result_array();
	}

	    /**
     * Gets the tree data cate.
     *
     * @param       $type   The type
     */
    public function get_tree_data_cate($type,$cgr){
      $category = $this->db->query('select * from '.db_prefix().'team_password_category order by parent, id')->result_array();
      $cate_tree = [];

      $node_all = [];
      $node_all['text'] = '<i class="fa fa-object-group"></i> '._l('all_category');
      $node_all['href'] = admin_url('team_password/team_password_mgt?cate=all&type='.$type);
      $node_all['state'] = ['selected' => false];
      if($cgr == 'all'){
      	$node_all['state'] = ['selected' => true];
      }
      $cate_tree[] = $node_all;

      foreach($category as $cate){
        if($cate['parent'] == 0){
          $node = array();
          $node['text'] = '<i class="fa '.$cate['icon'].'"></i>'.' '.$cate['category_name'];
          $node['href'] = admin_url('team_password/team_password_mgt?cate='.$cate['id'].'&type='.$type);
          $node['state'] = false;
          if($cgr == $cate['id']){
          	$node['state'] = ['selected' => true];
          }

          $node['nodes'] = $this->get_child_node_cate($cate['id'], $category, $type,$cgr);
          if(count($node['nodes']) == 0){
            unset($node['nodes']);
          }

          $cate_tree[] = $node;
        }else{
          break;
        }
      }

      return $cate_tree;

    }

     /**
     * Gets the child node cate.
     *
     * @param        $id        The identifier
     * @param        $arr_cate  The arr cate
     * @param        $type      The type
     * @param        $cgr       The cgr
     *
     * @return     array   The child node cate.
     */
    public function get_child_node_cate($id, $arr_cate, $type,$cgr){
      $dep_tree = array();
      foreach($arr_cate as $cate){
        if($cate['parent'] == $id){
          $node = array();
          $node['text'] = '<i class="fa '.$cate['icon'].'"></i>'.' '.$cate['category_name'];
          $node['href'] = admin_url('team_password/team_password_mgt?cate='.$cate['id'].'&type='.$type);

          $node['state'] =  ['selected' => false];
          if($cgr == $cate['id']){
          	$node['state'] = ['selected' => true];
          }

          $node['nodes'] = $this->get_child_node_cate($cate['id'],$arr_cate,$type,$cgr);
          if(count($node['nodes']) == 0){
            unset($node['nodes']);
          }
          $dep_tree[] = $node; 
        }
      }
      return $dep_tree;
    }


    /**
     * Gets the tree data cate by staff
     *
     * @param       $type   The type
     */
    public function get_tree_data_cate_staff($type,$cgr,$staff){

      $this->db->where('staff',$staff);
	  $this->db->where('type','category');
	  $list_per = $this->db->get(db_prefix().'permission')->result_array();	
	  $ids_cate_check = [];
	  $ids_cate = [];
	  foreach($list_per as $per){
	  	$ids_cate_check[] = $per['obj_id'];
	  }

	  foreach($list_per as $per2){
	  	$cat = $this->get_category_management($per2['obj_id']);
	  	if($cat){
	  		if(!in_array($cat->parent,$ids_cate_check) ){
	  			$ids_cate[] = $per2['obj_id'];
	  		}
	  	}
	  }

      $category = $this->db->query('select * from '.db_prefix().'team_password_category order by parent, id')->result_array();
      $cate_tree = [];

      $node_all = [];
      $node_all['text'] = '<i class="fa fa-object-group"></i> '._l('all_category');
      $node_all['href'] = admin_url('team_password/team_password_mgt?cate=all&type='.$type);
      $node_all['state'] = ['selected' => false];
      if($cgr == 'all'){
      	$node_all['state'] = ['selected' => true];
      }
      $cate_tree[] = $node_all;
      foreach($category as $cate){
        if(in_array($cate['id'], $ids_cate)){
          $node = array();
          $node['text'] = '<i class="fa '.$cate['icon'].'"></i>'.' '.$cate['category_name'];
          $node['href'] = admin_url('team_password/team_password_mgt?cate='.$cate['id'].'&type='.$type);
          $node['state'] = false;
          if($cgr == $cate['id']){
          	$node['state'] = ['selected' => true];
          }

          $node['nodes'] = $this->get_child_node_cate($cate['id'], $category, $type,$cgr);
          if(count($node['nodes']) == 0){
            unset($node['nodes']);
          }

          $cate_tree[] = $node;
        }

      }

      return $cate_tree;

    }
    /**
     * get tree cate ids.
     *
     * @param        $id        The identifier
     * @param        $arr_cate  The arr cate
     * @param        $type      The type
     * @param        $cgr       The cgr
     *
     * @return     array   The child node cate.
     */
    public function get_tree_cate_ids($cate_id){
	    $dep_tree = $this->db->query('select 
	        id 
	        from    (select * from '.db_prefix().'team_password_category
	         order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
	        (select @pv := '.$cate_id.') initialisation
	       where   find_in_set(parent, @pv)
	         and     length(@pv := concat(@pv, ",", id)) OR id = '.$cate_id)->result_array();

	    $rs = [];

	    if(count($dep_tree) > 0){
	    	foreach($dep_tree as $id){
	    		$rs[] = $id['id'];
	    	}
	    }else{
	    	$rs[] = $cate_id;
	    }

	    return $rs;
    }

    /**
     * { update permission }
     *
     * @param         $id     The identifier
     * @param         $data   The data
     *
     * @return     boolean  
     */
    public function update_permission($id,$data){
    	if (!isset($data['read'])) {
			$data['r'] = 'off';
		}else{
			$data['r'] = 'on';
			unset($data['read']);
		}

		if (!isset($data['write'])) {
			$data['w'] = 'off';
		}else{
			$data['w'] = 'on';
			unset($data['write']);
		}


		if(count($data['staff']) > 0){
			$data['staff'] = $data['staff'][0];
		}

    	$this->db->where('id',$id);
    	$this->db->update(db_prefix().'permission',$data);
    	if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
    }

    public function list_cate_permission($staff){
    	$this->db->where('staff',$staff);
        $this->db->where('type','category');
        $list_per = $this->db->get(db_prefix().'permission')->result_array();

        $rs = [];
        
        foreach($list_per as $per){
        	$ids_rs = $this->get_tree_cate_ids($per['obj_id']);
        	foreach($ids_rs as $id){
        		if(!(in_array($id, $rs))){
        			$rs[] = $id;
        		}
        	}
        }

        return $rs;
    }

}
