<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('after_email_templates', 'add_teampassword_email_templates');
/**
 * AES_256 Encrypt
 * @param string $str
 * @return string
 */
function AES_256_Encrypt($str) {
	$key = get_option('team_password_security');
	if ($key == '' || $key == null) {
		$key = 'g8934fuw9843hwe8rf9*5bhv';
	}
	$method = 'aes-256-cbc';
	$key = substr(hash('sha256', $key, true), 0, 32);
	$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
	return base64_encode(openssl_encrypt($str, $method, $key, OPENSSL_RAW_DATA, $iv));
}
/**
 * AES_256 Decrypt
 * @param string $str
 * @return string
 */
function AES_256_Decrypt($str) {
	$key = get_option('team_password_security');
	if ($key == '' || $key == null) {
		$key = 'g8934fuw9843hwe8rf9*5bhv';
	}
	$method = 'aes-256-cbc';
	$key = substr(hash('sha256', $key, true), 0, 32);
	$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
	return openssl_decrypt(base64_decode($str), $method, $key, OPENSSL_RAW_DATA, $iv);
}
/**
 * get permission
 * @param  $type
 * @param  $obj_id
 * @param  string $permission
 * @return   bool
 */
function get_permission($type, $obj_id, $permission = '') {
	$id_staff = get_staff_user_id();
	$CI = &get_instance();
	$CI->load->model('team_password/team_password_model');
	$CI->db->where('staff', $id_staff);
	$CI->db->where('type', $type);
	$CI->db->where('obj_id', $obj_id);
	$data_permission = $CI->db->get(db_prefix() . 'permission')->row();
	if ($data_permission) {
		$flag = 0;
		$length = strlen($permission);
		for ($i = 0; $i < $length; $i++) {
			if ($permission[$i] == 'r') {
				if ($data_permission->r == 'on') {
					$flag++;
				}
			}
			if ($permission[$i] == 'w') {
				if ($data_permission->w == 'on') {
					$flag++;
				}
			}
		}

		if ($flag > 0) {
			if ($flag == $length) {
				return 1;
			}
		}
		return 0;
	}else{

		$CI->db->where('id',$obj_id);
		$item = $CI->db->get(db_prefix().'tp_'.$type)->row();
		if($item){
			$CI->db->where('staff',$id_staff);
			$CI->db->where('type','category');
			$list_per = $CI->db->get(db_prefix().'permission')->result_array();
			
			foreach($list_per as $per){
				$ids = $CI->team_password_model->get_tree_cate_ids($per['obj_id']);
				foreach($ids as $id){
					$CI->db->where('staff', $id_staff);
					$CI->db->where('type', 'category');
					$CI->db->where('obj_id', $id);
					$cate_permission = $CI->db->get(db_prefix() . 'permission')->row();
					if ($cate_permission) {
						$flag1 = 0;
						$length1 = strlen($permission);
						for ($i = 0; $i < $length1; $i++) {
							if ($permission[$i] == 'r') {
								if ($cate_permission->r == 'on') {
									$flag1++;
								}
							}
							if ($permission[$i] == 'w') {
								if ($cate_permission->w == 'on') {
									$flag1++;
								}
							}
						}

						if ($flag1 > 0) {
							if ($flag1 == $length1) {
								return 1;
							}
						}
						return 0;
					}
				}
			}
		}

	}
	return 2;
}
/**
 * load error page
 * @param  string $title
 * @param  string $content
 * @return view
 */
function error_page($title = '', $content = '') {
	$data['title'] = $title;
	$data['content'] = $content;
	$CI = &get_instance();
	$CI->data($data);
	$CI->view('team_password_mgt/error_page');
	$CI->layout();
}

/**
 * item name by objid
 * @param  int $id
 * @param  int $type
 * @return string
 */
function item_name_by_objid($id, $type) {
	$CI = &get_instance();
	$name = '';
	if ($type == 'normal') {
		$CI->db->where('id', $id);
		$normal = $CI->db->get(db_prefix() . 'tp_normal')->row();
		if ($normal) {
			$name = $normal->name;
		}
	} elseif ($type == 'bank_account') {
		$CI->db->where('id', $id);
		$bank_account = $CI->db->get(db_prefix() . 'tp_bank_account')->row();
		if ($bank_account) {
			$name = $bank_account->name;
		}
	} elseif ($type == 'email') {
		$CI->db->where('id', $id);
		$email = $CI->db->get(db_prefix() . 'tp_email')->row();
		if ($email) {
			$name = $email->name;
		}
	} elseif ($type == 'credit_card') {
		$CI->db->where('id', $id);
		$credit_card = $CI->db->get(db_prefix() . 'tp_credit_card')->row();
		if ($credit_card) {
			$name = $credit_card->name;
		}
	} elseif ($type == 'software_license') {
		$CI->db->where('id', $id);
		$software_license = $CI->db->get(db_prefix() . 'tp_software_license')->row();
		if ($software_license) {
			$name = $software_license->name;
		}
	} elseif ($type == 'server') {
		$CI->db->where('id', $id);
		$server = $CI->db->get(db_prefix() . 'tp_server')->row();
		if ($server) {
			$name = $server->name;
		}
	}

	return $name;
}

if (!function_exists('add_teampassword_email_templates')) {
	/**
	 * Init appointly email templates and assign languages
	 * @return void
	 */
	function add_teampassword_email_templates() {
		$CI = &get_instance();

		$data['teampassword_templates'] = $CI->emails_model->get(['type' => 'teampassword', 'language' => 'english']);

		$CI->load->view('team_password/email_templates', $data);
	}
}

/**
 * Gets the category name tp.
 *
 * @param      $id     The identifier
 *
 * @return     string  The category name tp.
 */
function get_category_name_tp($id) {
	$CI = &get_instance();
	$CI->db->where('id', $id);
	$category = $CI->db->get(db_prefix() . 'team_password_category')->row();
	if ($category) {
		return $category->category_name;
	} else {
		return '';
	}

}

/**
 * Gets the contract relate.
 *
 * @param       $type    The type
 * @param       $obj_id  The object identifier
 *
 * @return       The contract relate.
 */
function get_contract_relate($type, $obj_id) {
	$CI = &get_instance();
	$CI->load->model('team_password/team_password_model');
	$CI->load->model('contracts_model');

	switch ($type) {
	case 'normal':
		$data_obj = $CI->team_password_model->get_normal($obj_id);
		if ($data_obj) {
			if ($data_obj->relate_to == 'contract') {
				return $contract = explode(',', $data_obj->relate_id);
			} elseif ($data_obj->relate_to == 'project') {
				return $project = explode(',', $data_obj->relate_id);
			} else {
				return '';
			}
		} else {
			return '';
		}
		break;
	case 'bank_account':
		$data_obj = $CI->team_password_model->get_bank_account($obj_id);
		if ($data_obj) {
			if ($data_obj->relate_to == 'contract') {
				return $contract = explode(',', $data_obj->relate_id);
			} elseif ($data_obj->relate_to == 'project') {
				return $project = explode(',', $data_obj->relate_id);
			} else {
				return '';
			}
		} else {
			return '';
		}
		break;
	case 'credit_card':
		$data_obj = $CI->team_password_model->get_credit_card($obj_id);
		if ($data_obj) {
			if ($data_obj->relate_to == 'contract') {
				return $contract = explode(',', $data_obj->relate_id);
			} elseif ($data_obj->relate_to == 'project') {
				return $project = explode(',', $data_obj->relate_id);
			} else {
				return '';
			}
		} else {
			return '';
		}
		break;
	case 'email':
		$data_obj = $CI->team_password_model->get_email($obj_id);
		if ($data_obj) {
			if ($data_obj->relate_to == 'contract') {
				return $contract = explode(',', $data_obj->relate_id);
			} elseif ($data_obj->relate_to == 'project') {
				return $project = explode(',', $data_obj->relate_id);
			} else {
				return '';
			}
		} else {
			return '';
		}
		break;
	case 'server':
		$data_obj = $CI->team_password_model->get_server($obj_id);
		if ($data_obj) {
			if ($data_obj->relate_to == 'contract') {
				return $contract = explode(',', $data_obj->relate_id);
			} elseif ($data_obj->relate_to == 'project') {
				return $project = explode(',', $data_obj->relate_id);
			} else {
				return '';
			}
		} else {
			return '';
		}
		break;
	case 'software_license':
		$data_obj = $CI->team_password_model->get_software_license($obj_id);
		if ($data_obj) {
			if ($data_obj->relate_to == 'contract') {
				return $contract = explode(',', $data_obj->relate_id);
			} elseif ($data_obj->relate_to == 'project') {
				return $project = explode(',', $data_obj->relate_id);
			} else {
				return '';
			}
		} else {
			return '';
		}
		break;
	}
}

function get_shared_item_by_client($client) {
	$CI = &get_instance();
	return $CI->db->query('select s.id as id, c.id as contact_id, s.email as email, c.email as contact_email, s.type as type, s.share_id, s.effective_time, s.hash from ' . db_prefix() . 'tp_share s left join ' . db_prefix() . 'contacts c on c.email = s.client where c.userid = ' . $client)->result_array();
}

/**
 * { row team password options exist }
 *
 * @param      <type>   $name   The name
 *
 * @return     integer  ( 1 or 0 )
 */
function row_tp_options_exist($name) {
	$CI = &get_instance();
	$i = count($CI->db->query('Select * from ' . db_prefix() . 'options where name = ' . $name)->result_array());
	if ($i == 0) {
		return 0;
	}
	if ($i > 0) {
		return 1;
	}
}

/**
 * { handle item password file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean
 */
function handle_item_password_file($id, $type) {

	$path = TEAM_PASSWORD_MODULE_UPLOAD_FOLDER . '/' . $type . '/' . $id . '/';
	$CI = &get_instance();
	$totalUploaded = 0;

	if (isset($_FILES['attachments']['name'])
		&& ($_FILES['attachments']['name'] != '' || is_array($_FILES['attachments']['name']) && count($_FILES['attachments']['name']) > 0)) {
		if (!is_array($_FILES['attachments']['name'])) {
			$_FILES['attachments']['name'] = [$_FILES['attachments']['name']];
			$_FILES['attachments']['type'] = [$_FILES['attachments']['type']];
			$_FILES['attachments']['tmp_name'] = [$_FILES['attachments']['tmp_name']];
			$_FILES['attachments']['error'] = [$_FILES['attachments']['error']];
			$_FILES['attachments']['size'] = [$_FILES['attachments']['size']];
		}

		_file_attachments_index_fix('attachments');
		for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {

			// Get the temp file path
			$tmpFilePath = $_FILES['attachments']['tmp_name'][$i];
			// Make sure we have a filepath
			if (!empty($tmpFilePath) && $tmpFilePath != '') {
				if (_perfex_upload_error($_FILES['attachments']['error'][$i])
					|| !_upload_extension_allowed($_FILES['attachments']['name'][$i])) {
					continue;
				}

				_maybe_create_upload_path($path);
				$filename = unique_filename($path, $_FILES['attachments']['name'][$i]);
				$newFilePath = $path . $filename;
				// Upload the file into the temp dir
				if (move_uploaded_file($tmpFilePath, $newFilePath)) {
					$attachment = [];
					$attachment[] = [
						'file_name' => $filename,
						'filetype' => $_FILES['attachments']['type'][$i],
					];

					$CI->misc_model->add_attachment_to_database($id, $type, $attachment);
					$totalUploaded++;
				}
			}
		}
	}

	return (bool) $totalUploaded;
}

/**
 * Gets the item tp attachment.
 *
 * @param       $id     The identifier
 * @param        $type   The type
 *
 * @return       The item tp attachment.
 */
function get_item_tp_attachment($id, $type) {
	$CI = &get_instance();

	$CI->db->where('rel_id', $id);
	$CI->db->where('rel_type', $type);
	$attachments = $CI->db->get(db_prefix() . 'files')->result_array();

	return $attachments;
}

/**
 * get hash by item client
 * @param  int $share_id
 * @param  int $type
 * @param  int $contact
 * @return string
 */
function get_hash_by_item_client($share_id, $type, $contact) {
	$CI = &get_instance();
	$CI->db->where('id', $contact);
	$contact_email = $CI->db->get(db_prefix() . 'contacts')->row();
	$email = '';
	if ($contact_email) {
		$email = $contact_email->email;
	}

	if ($email != '') {
		$CI->db->where('type', $type);
		$CI->db->where('client', $email);
		$CI->db->where('share_id', $share_id);
		$CI->db->order_by('effective_time', 'DESC');
		$share = $CI->db->get(db_prefix() . 'tp_share')->row();

		if ($share) {
			return $share->hash;
		} else {
			return '';
		}

	} else {
		return '';
	}
}

/**
 * get contract id
 * @param  int $ctr
 * @return object
 */
function get_contract_id($ctr) {
	$CI = &get_instance();
	$CI->db->where('id', $ctr);
	$contract = $CI->db->get(db_prefix() . 'contracts')->row();
	return $contract;
}

/**
 * get project id
 * @param  int $ctr
 * @return object
 */
function get_project_id($ctr) {
	$CI = &get_instance();
	$CI->db->where('id', $ctr);
	$project = $CI->db->get(db_prefix() . 'projects')->row();
	return $project;
}

/**
 * AES_256 Encrypt with key
 * @param string $str
 * @return string
 */
function AES_256_Encrypt_with_key($str,$key) {
	
	$method = 'aes-256-cbc';
	$key = substr(hash('sha256', $key, true), 0, 32);
	$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
	return base64_encode(openssl_encrypt($str, $method, $key, OPENSSL_RAW_DATA, $iv));
}
/**
 * AES_256 Decrypt with key
 * @param string $str
 * @return string
 */
function AES_256_Decrypt_with_key($str,$key) {
	
	$method = 'aes-256-cbc';
	$key = substr(hash('sha256', $key, true), 0, 32);
	$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
	return openssl_decrypt(base64_decode($str), $method, $key, OPENSSL_RAW_DATA, $iv);
}

/**
 * Gets the recursive cate.
 *
 * @param      integer|string  $cate  The cate identifier
 *
 * @return              The recursive cate.
 */
function get_recursive_cate($cate){
    if(!isset($cate)){
        $cate = 0;
    }
    $CI = & get_instance();
    $arr_cate = $CI->db->query('select      s1.id as id,
                                s1.parent as parent
                    from        '.db_prefix().'team_password_category s1
                    left join   '.db_prefix().'team_password_category s2 on s2.id = s1.parent 
                    left join   '.db_prefix().'team_password_category s3 on s3.id = s2.parent 
                    left join   '.db_prefix().'team_password_category s4 on s4.id = s3.parent  
                    left join   '.db_prefix().'team_password_category s5 on s5.id = s4.parent  
                    left join   '.db_prefix().'team_password_category s6 on s6.id = s5.parent
                    where       '.$cate.' in (s1.parent, 
                                       s2.parent, 
                                       s3.parent, 
                                       s4.parent, 
                                       s5.parent, 
                                       s6.parent) 
                    order       by 2,1;')->result_array();    
    return $arr_cate;
}
