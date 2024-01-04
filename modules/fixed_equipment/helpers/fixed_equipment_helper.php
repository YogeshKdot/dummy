<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Gets the item file attachment.
 *
 * @param        $id     The identifier
 *
 * @return       The item tp attachment.
 */
function fe_get_item_file_attachment($id, $type = 'locations') {
	$CI = &get_instance();
	$CI->db->where('rel_id', $id);
	$CI->db->where('rel_type', $type);
	$attachments = $CI->db->get(db_prefix() . 'files')->result_array();
	return $attachments;
}
/**
 * { handle item file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean
 */
function fe_handle_item_file($id, $type, $head_file_name = '') {
	$path = FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER . '/'.$type.'/' . $id . '/';
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
			$filename = $head_file_name.unique_filename($path, $_FILES['attachments']['name'][$i]);
			$newFilePath = $path.$filename;
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
 * reformat currency asset
 * @param  string $str 
 * @return string        
 */
function fe_reformat_currency_asset($str)
{
	return str_replace(',','', $str);
}

  /**
     * check format date ymd
     * @param  date $date 
     * @return boolean       
     */
  function fe_check_format_date_ymd($date) {
  	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
  		return true;
  	} else {
  		return false;
  	}
  }
    /**
     * check format date
     * @param  date $date 
     * @return boolean 
     */
    function fe_check_format_date($date){
    	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4]):?((0|[0-5][0-9]):?(0|[0-5][0-9])|6000|60:00)$/",$date)) {
    		return true;
    	} else {
    		return false;
    	}
    }
/**
* format date
* @param  date $date     
* @return date           
*/
function fe_format_date($date){
	if(!fe_check_format_date_ymd($date)){
		$date = to_sql_date($date);
	}
	return $date;
}            

/**
 * format date time
 * @param  date $date     
 * @return date           
 */
function fe_format_date_time($date){
	if(!fe_check_format_date($date)){
		$date = to_sql_date($date, true);
	}
	return $date;
}

/**
 * get image qrcode
 * @param  integer $asset_id 
 * @return string $url            
 */
function fe_get_image_qrcode($asset_id){
	$url =  site_url('modules/fixed_equipment/assets/images/no_image.jpg');
	$CI = &get_instance();
	$CI->db->where('id', $asset_id);
	$data_assets = $CI->db->get(db_prefix() . 'fe_assets')->row();
	if($data_assets){
		if($data_assets->qr_code != ''){
			$url = base_url(FIXED_EQUIPMENT_PATH.'qrcodes/'.$data_assets->qr_code.'.png');	
		}
	}
	return $url;
}

/**
 * get expired date
 * @param  date $start_date   
 * @param  integer $number_month 
 * @return date               
 */
function get_expired_date($start_date, $number_month){
	$time_st = strtotime($start_date . " +".$number_month." month");
	return date('Y-m-d', $time_st);
}

/**
 * crawl get
 * @param  string &$curl  
 * @param  string $link   
 * @param  string $header 
 * @return string         
 */
function fe_crawl_get(&$curl, $link, $header = null) {
	$cookie_file = dirname(__FILE__) . '/' . 'cookie.txt';      
	curl_setopt($curl, CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($curl, CURLOPT_COOKIESESSION, true);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
	if (isset($header)) {
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	}
	return curl_exec($curl);
}
/**
 * address2geo
 * @param  string 
 * @return json
 */
function fe_address2geo($address){
	$googlemap_api_key = '';
	$api_key = get_option('fe_googlemap_api_key');
	if($api_key){
		$googlemap_api_key = $api_key;
	}
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".rawurlencode($address)."&key=".$googlemap_api_key;
	$curl = curl_init();
	$curlData = fe_crawl_get($curl,$url);  
	$geo = json_decode($curlData);   
	if(isset($geo) && isset($geo->results[0])){
		return json_encode($geo->results[0]->geometry->location);
	}
	return '';
}

/**
	 * get list month
	 * @param   $from_date 
	 * @param   $to_date             
	 */
function fe_get_list_month($from_date, $to_date){
	$start    = new DateTime($from_date);
	$start->modify('first day of this month');
	$end      = new DateTime($to_date);
	$end->modify('first day of next month');
	$interval = DateInterval::createFromDateString('1 month');
	$period   = new DatePeriod($start, $interval, $end);
	$result = [];
	foreach ($period as $dt) {
		$result[] = $dt->format("Y-m-01");
	}
	return $result;
}

/**
 * permisstion list
 * @return array
 */
function fe_list_permisstion() {
	$permission = [];
	$permission[] = 'fixed_equipment_dashboard';
	$permission[] = 'fixed_equipment_assets';
	$permission[] = 'fixed_equipment_licenses';
	$permission[] = 'fixed_equipment_accessories';
	$permission[] = 'fixed_equipment_consumables';
	$permission[] = 'fixed_equipment_components';
	$permission[] = 'fixed_equipment_predefined_kits';
	$permission[] = 'fixed_equipment_requested';
	$permission[] = 'fixed_equipment_maintenances';
	$permission[] = 'fixed_equipment_audit';
	$permission[] = 'fixed_equipment_locations';
	$permission[] = 'fixed_equipment_report';
	$permission[] = 'fixed_equipment_depreciations';
	$permission[] = 'fixed_equipment_sign_manager';
	return $permission;
}

/**
 * get staff id permissions
 * @return array
 */
function fe_get_staff_id_permissions() {
	$CI = &get_instance();
	$array_staff_id = [];
	$index = 0;

	$str_permissions = '';
	foreach (fe_list_permisstion() as $per_key => $per_value) {
		if (strlen($str_permissions) > 0) {
			$str_permissions .= ",'" . $per_value . "'";
		} else {
			$str_permissions .= "'" . $per_value . "'";
		}
	}

	$sql_where = "SELECT distinct staff_id FROM " . db_prefix() . "staff_permissions
        where feature IN (" . $str_permissions . ")
        ";
	$staffs = $CI->db->query($sql_where)->result_array();

	if (count($staffs) > 0) {
		foreach ($staffs as $key => $value) {
			$array_staff_id[$index] = $value['staff_id'];
			$index++;
		}
	}
	return $array_staff_id;
}

/**
 * get staff id not permissions
 * @return array
 */
function fe_get_staff_id_not_permissions() {
	$CI = &get_instance();
	$CI->db->where('admin != ', 1);
	if (count(fe_get_staff_id_permissions()) > 0) {
		$CI->db->where_not_in('staffid', fe_get_staff_id_permissions());
	}
	return $CI->db->get(db_prefix() . 'staff')->result_array();

}
/**
 * get client IP
 * @return string
 */
function fe_get_client_ip() {
	//whether ip is from the share internet
	$ip = '';
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

/**
 * get staff email
 * @param  integer $staffid
 * @return string $email
 */
function fe_get_staff_email($staffid) {
	$CI = &get_instance();
	$CI->db->where('staffid', $staffid);
	$CI->db->select('email');
	$email = '';
	$data = $CI->db->get(db_prefix() . 'staff')->row();
	if ($data) {
		$email = $data->email;
	}
	return $email;
}

/**
 * get sign image
 * @param  integer $id     
 * @param  string $folder 
 * @return string         
 */
function fe_get_sign_image($id, $folder){
		$file_path  = FCPATH. 'modules/fixed_equipment/uploads/sign_document/'.$id.'/signature.png';
		return $file_path;
}
/**
 * get image qrcode
 * @param  integer $asset_id 
 * @return string $url            
 */
function fe_get_image_qrcode_pdf($asset_id){
	$url =  site_url('modules/fixed_equipment/assets/images/no_image.jpg');
	$CI = &get_instance();
	$CI->db->where('id', $asset_id);
	$data_assets = $CI->db->get(db_prefix() . 'fe_assets')->row();
	if($data_assets){
		if($data_assets->qr_code != ''){
			$url  = FCPATH. 'modules/fixed_equipment/uploads/qrcodes/'.$data_assets->qr_code.'.png';
		}
	}
	return $url;
}

/**
 * get status modules wh
 * @param  string $module_name 
 * @return boolean             
 */
function fe_get_status_modules($module_name){
	$CI             = &get_instance();

	$sql = 'select * from '.db_prefix().'modules where module_name = "'.$module_name.'" AND active =1 ';
	$module = $CI->db->query($sql)->row();
	if($module){
		return true;
	}else{
		return false;
	}
}