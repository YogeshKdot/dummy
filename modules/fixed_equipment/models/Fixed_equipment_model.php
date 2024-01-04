<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Fixed equipment model
 */
class fixed_equipment_model extends app_model
{
	public function __construct()
	{
		parent::__construct();
		if(!class_exists('qrstr')){
			include_once(FIXED_EQUIPMENT_PATH_PLUGIN.'/phpqrcode/qrlib.php');		
		}
	}
	/**
	 * add depreciations
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_depreciations($data){
		$this->db->insert(db_prefix().'fe_depreciations', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update depreciations
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_depreciations($data){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_depreciations', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * delete depreciations
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_depreciations($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_depreciations');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

		/**
	 * get depreciations
	 * @param  integer $id 
	 * @return array or object    
	 */
		public function get_depreciations($id = ''){
			if($id != ''){
				$this->db->where('id', $id);
				return $this->db->get(db_prefix().'fe_depreciations')->row();
			}
			else{
				return $this->db->get(db_prefix().'fe_depreciations')->result_array();
			}
		}

	/**
	 * add locations
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_locations($data){
		$this->db->insert(db_prefix().'fe_locations', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update locations
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_locations($data){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_locations', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * delete locations
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_locations($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_locations');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * get locations
	 * @param  integer $id 
	 * @return array or object    
	 */
	public function get_locations($id = ''){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_locations')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_locations')->result_array();
		}
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
	 * { delete filed item }
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
 			unlink(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id . '/' . $attachment->file_name);
 		}
 		$this->db->where('id', $attachment->id);
 		$this->db->delete('tblfiles');
 		if ($this->db->affected_rows() > 0) {
 			$deleted = true;
 		}

 		if (is_dir(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id)) {
				// Check if no attachments left, so we can delete the folder also
 			$other_attachments = list_files(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id);
 			if (count($other_attachments) == 0) {
					// okey only index.html so we can delete the folder also
 				delete_dir(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'.$type.'/'. $attachment->rel_id);
 			}
 		}
 	}
 	return $deleted;
 }

	/**
	 * Gets the item attachments.
	 *
	 * @param  $assets  The assets
	 * @param  string  $id The identifier
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
	 * get image items
	 * @param  integer $item_id 
	 * @return integer          
	 */
	public function get_image_items($item_id, $type){
		$file_path  = site_url('modules/fixed_equipment/assets/images/no_image.jpg');
		$data_file = $this->get_image_file_name($item_id, $type);
		if($data_file){
			if($data_file->file_name!=''){
				$file_path  = site_url(FIXED_EQUIPMENT_IMAGE_UPLOADED_PATH.$type.'/'.$item_id.'/'.$data_file->file_name);
			}
		}
		return $file_path;
	}

	 /**
	 * get image file name
	 * @param   int $id 
	 * @param   string $type 
	 * @return  object   
	 */
	 public function get_image_file_name($id, $type){
	 	$this->db->where('rel_id',$id);
	 	$this->db->where('rel_type', $type);
	 	$this->db->select('file_name');
	 	return $this->db->get(db_prefix().'files')->row();
	 }

	/**
	 * add suppliers
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_suppliers($data){
		$this->db->insert(db_prefix().'fe_suppliers', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update suppliers
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_suppliers($data){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_suppliers', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * delete suppliers
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_suppliers($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_suppliers');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * get suppliers
	 * @param  integer $id 
	 * @return array or object    
	 */
	public function get_suppliers($id = ''){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_suppliers')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_suppliers')->result_array();
		}
	}
/**
	 * add asset_manufacturers
	 * @param array $data 
	 * @return integer $insert id 
	 */
public function add_asset_manufacturers($data){
	$this->db->insert(db_prefix().'fe_asset_manufacturers', $data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		return $insert_id;
	}
	return 0;
}
	/**
	 * update asset_manufacturers
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_asset_manufacturers($data){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_asset_manufacturers', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * delete asset_manufacturers
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_asset_manufacturers($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_asset_manufacturers');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * get asset_manufacturers
	 * @param  integer $id 
	 * @return array or object    
	 */
	public function get_asset_manufacturers($id = ''){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_asset_manufacturers')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_asset_manufacturers')->result_array();
		}
	}

	/**
	 * add categories
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_categories($data){
		$this->db->insert(db_prefix().'fe_categories', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update categories
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_categories($data){
		if(!isset($data['primary_default_eula'])){
			$data['primary_default_eula'] = 0;
		}
		if(!isset($data['confirm_acceptance'])){
			$data['confirm_acceptance'] = 0;
		}
		if(!isset($data['send_mail_to_user'])){
			$data['send_mail_to_user'] = 0;
		}
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_categories', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete categories
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_categories($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_categories');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get categories
	 * @param  integer $id 
	 * @return array or object    
	 */
	public function get_categories($id = '', $type = ''){
		if($type != ''){
			$this->db->where('type', $type);
		}
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_categories')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_categories')->result_array();
		}
	}

	/**
	 * add models
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_models($data){
		if(isset($data['model_name'])){
			$data_add['model_name'] = $data['model_name'];
		}
		if(isset($data['manufacturer'])){
			$data_add['manufacturer'] = $data['manufacturer'];
		}
		if(isset($data['category'])){
			$data_add['category'] = $data['category'];
		}
		if(isset($data['model_no'])){
			$data_add['model_no'] = $data['model_no'];
		}
		if(isset($data['depreciation'])){
			$data_add['depreciation'] = $data['depreciation'];
		}
		if(isset($data['eol'])){
			$data_add['eol'] = $data['eol'];
		}
		if(isset($data['note'])){
			$data_add['note'] = $data['note'];
		}
		if(isset($data['may_request'])){
			$data_add['may_request'] = $data['may_request'];
		}
		if(isset($data['fieldset_id'])){
			$data_add['fieldset_id'] = $data['fieldset_id'];
		}
		$this->db->insert(db_prefix().'fe_models', $data_add);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			if (isset($data['custom_fields'])) {
				$custom_fields = $data['custom_fields'];
				handle_custom_fields_post($insert_id, $custom_fields);
			}				
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update models
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_models($data){
		if(isset($data['model_name'])){
			$data_update['model_name'] = $data['model_name'];
		}
		if(isset($data['manufacturer'])){
			$data_update['manufacturer'] = $data['manufacturer'];
		}
		if(isset($data['category'])){
			$data_update['category'] = $data['category'];
		}
		if(isset($data['model_no'])){
			$data_update['model_no'] = $data['model_no'];
		}
		if(isset($data['depreciation'])){
			$data_update['depreciation'] = $data['depreciation'];
		}
		if(isset($data['eol'])){
			$data_update['eol'] = $data['eol'];
		}
		if(isset($data['note'])){
			$data_update['note'] = $data['note'];
		}
		if(isset($data['may_request'])){
			$data_update['may_request'] = $data['may_request'];
		}
		else{
			$data_update['may_request'] = 0;
		}
		if(isset($data['fieldset_id'])){
			$data_update['fieldset_id'] = $data['fieldset_id'];
		}
		$affectedRows = 0;
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_models', $data_update);
		if($this->db->affected_rows() > 0) {
			$affectedRows++;
		}
		if (isset($data['custom_fields'])) {
			$custom_fields = $data['custom_fields'];
			if (handle_custom_fields_post($data['id'], $custom_fields)) {
				$affectedRows++;
			}
		}
		if($affectedRows != 0){
			return true;
		}
		return false;
	}

	/**
	 * delete models
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_models($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_models');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get models
	 * @param  integer $id 
	 * @return array or object    
	 */
	public function get_models($id = ''){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_models')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_models')->result_array();
		}
	}

/**
 * get custom field models
 * @param  integer $model_id 
 * @return array           
 */
public function get_custom_field_models($model_id){
	return $this->db->query('select a.id, fieldid, b.name, b.slug, a.value from '.db_prefix().'customfieldsvalues a left join '.db_prefix().'customfields b on a.fieldid = b.id where relid = '.$model_id.' and a.fieldto = "fixed_equipment" and active = 1')->result_array();
}

	/**
	 * add status_labels
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_status_labels($data){
		$this->db->insert(db_prefix().'fe_status_labels', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}

	/**
	 * update status_labels
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_status_labels($data){
		if(!isset($data['default_label'])){
			$data['default_label'] = 0;
		}
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_status_labels', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete status_labels
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_status_labels($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_status_labels');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get status_labels
	 * @param  integer $id 
	 * @return array or object    
	 */
	public function get_status_labels($id = '', $status_type = ''){
		if($status_type != ''){
			$this->db->where('status_type', $status_type);
		}
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_status_labels')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_status_labels')->result_array();
		}
	}
/**
 * add asset
 * @param array $data 
 */
public function add_asset($data){
	$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
	$data['date_buy'] = fe_format_date($data['date_buy']);
	$list_serial = [];
	$list_insert_id = [];
	if(isset($data['serial'])){
		$list_serial = $data['serial'];
		$alocation_name = '--';
		$supplier_name_s = '--';
		$model_name_s = '--';
		$model_no_s = '--';
		if(isset($data['asset_location']) && $data['asset_location'] != ''){
			$data_alocation = $this->get_locations($data['asset_location']);
			if($data_alocation){
				$alocation_name = $data_alocation->location_name;
			}
		}
		if(isset($data['supplier_id']) && $data['supplier_id'] != ''){
			$data_supplier = $this->get_suppliers($data['supplier_id']);
			if($data_supplier){
				$supplier_name_s = $data_supplier->supplier_name;
			}
		}
		if(isset($data['model_id']) && $data['model_id'] != ''){
			$data_model = $this->get_models($data['model_id']);
			if($data_model){
				$model_name_s = $data_model->model_name;
				$model_no_s = $data_model->model_no;
			}
		}
		$asset_name_s = (($data['assets_name'] == '') ? $model_name_s : $data['assets_name']);
		$tempDir = FIXED_EQUIPMENT_PATH.'qrcodes/';
		foreach ($list_serial as $key => $serial) {
			if($serial != ''){
				$qr_code = md5($serial);              
				$html = '';
				$html .= "\n"._l('fe_asset_name').': '.$asset_name_s."\n";
				$html .= _l('fe_asset_tag').': '.$serial."\n";
				$html .= _l('fe_models').': '.$model_name_s."\n";
				$html .= _l('fe_model_no').': '.$model_no_s."\n";
				$html .= 'QR code : '.$qr_code."\n";
				$html .= _l('fe_locations').': '.$alocation_name."\n";
				$html .= _l('fe_purchase_date').': '.$data['date_buy']."\n";
				$html .= _l('fe_purchase_cost').': '.$data['unit_price']."\n";
				$html .= _l('fe_warranty').': '.$data['warranty_period']."\n";
				$html .= _l('fe_supplier').': '.$supplier_name_s."\n";
				$codeContents = $html;
				$fileName = $qr_code;
				$pngAbsoluteFilePath = $tempDir.$fileName;
				$urlRelativeFilePath = $tempDir.$fileName;
				if (!file_exists($pngAbsoluteFilePath)) {
					QRcode::png($codeContents, $pngAbsoluteFilePath.'.png', "L", 4, 4);
				} 

				$data_add['assets_name'] = $asset_name_s;
				$data_add['model_id'] = $data['model_id'];
				$data_add['status'] = $data['status'];
				$data_add['supplier_id'] = $data['supplier_id'];
				$data_add['date_buy'] = $data['date_buy'];
				$data_add['order_number'] = $data['order_number'];
				$data_add['unit_price'] = $data['unit_price'];
				$data_add['asset_location'] = $data['asset_location'];
				$data_add['location_id'] = $data['asset_location'];
				$data_add['warranty_period'] = $data['warranty_period'];
				$data_add['description'] = $data['description'];
				$data_add['qr_code'] = $qr_code;
				$data_add['series'] = $serial;

				$data_add['requestable'] = isset($data['requestable']) ? $data['requestable'] : 0;

				$this->db->insert(db_prefix() . 'fe_assets',$data_add);
				$insert_id = $this->db->insert_id();
				if($insert_id){
					$this->add_log(get_staff_user_id(), 'create_new', $insert_id, '', '', '', null, '');
					if(isset($data['customfield'])){
						foreach ($data['customfield'] as $customfield_id => $field_value) {
							$value = (is_array($field_value) ? json_encode($field_value) : $field_value);
							$data_customfield = $this->get_custom_fields($customfield_id);
							if($data_customfield){
								$data_insert['title'] = $data_customfield->title;
								$data_insert['type'] = $data_customfield->type;
								$data_insert['option'] = $data_customfield->option;
								$data_insert['required'] = $data_customfield->required;
								$data_insert['value'] = $value;
								$data_insert['fieldset_id'] = $data_customfield->fieldset_id;
								$data_insert['custom_field_id'] = $data_customfield->id;
								$data_insert['asset_id'] = $insert_id;
								$this->db->insert(db_prefix() . 'fe_custom_field_values',$data_insert);
							}
						}
					}
					$list_insert_id[] = $insert_id;
				}
			}
		}
	}
	return $list_insert_id;
}
/**
 * update asset
 * @param  array $data 
 * @param  integer $id   
 * @return boolean       
 */
public function update_asset($data, $id){
	$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
	$data['date_buy'] = fe_format_date($data['date_buy']);

	$data_asset = $this->get_assets($id);
	if($data_asset){
		if(is_numeric($data_asset->location_id) && (int)$data_asset->location_id == 0){
			$data_add['location_id'] = $data['asset_location'];
		}
	}

	$list_serial = [];
	$list_insert_id = [];
	$affectedRows = 0;
	if(isset($data['serial'])){
		$list_serial = $data['serial'];
		$alocation_name = '--';
		$supplier_name_s = '--';
		$model_name_s = '--';
		$model_no_s = '--';
		if(isset($data['asset_location']) && $data['asset_location'] != ''){
			$data_alocation = $this->get_locations($data['asset_location']);
			if($data_alocation){
				$alocation_name = $data_alocation->location_name;
			}
		}
		if(isset($data['supplier_id']) && $data['supplier_id'] != ''){
			$data_supplier = $this->get_suppliers($data['supplier_id']);
			if($data_supplier){
				$supplier_name_s = $data_supplier->supplier_name;
			}
		}
		if(isset($data['model_id']) && $data['model_id'] != ''){
			$data_model = $this->get_models($data['model_id']);
			if($data_model){
				$model_name_s = $data_model->model_name;
				$model_no_s = $data_model->model_no;
			}
		}
		$asset_name_s = (($data['assets_name'] == '') ? $model_name_s : $data['assets_name']);
		$tempDir = FIXED_EQUIPMENT_PATH.'qrcodes/';
		foreach ($list_serial as $key => $serial) {
			if($serial != ''){
				$qr_code = md5($serial);              
				$html = '';
				$html .= "\n"._l('fe_asset_name').': '.$asset_name_s."\n";
				$html .= _l('fe_asset_tag').': '.$serial."\n";
				$html .= _l('fe_models').': '.$model_name_s."\n";
				$html .= _l('fe_model_no').': '.$model_no_s."\n";
				$html .= 'QR code : '.$qr_code."\n";
				$html .= _l('fe_locations').': '.$alocation_name."\n";
				$html .= _l('fe_purchase_date').': '.$data['date_buy']."\n";
				$html .= _l('fe_purchase_cost').': '.$data['unit_price']."\n";
				$html .= _l('fe_warranty').': '.$data['warranty_period']."\n";
				$html .= _l('fe_supplier').': '.$supplier_name_s."\n";
				$codeContents = $html;
				$fileName = $qr_code;
				$pngAbsoluteFilePath = $tempDir.$fileName;
				$urlRelativeFilePath = $tempDir.$fileName;
				if (!file_exists($pngAbsoluteFilePath)) {
					QRcode::png($codeContents, $pngAbsoluteFilePath.'.png', "L", 4, 4);
				} 
				$data_add['assets_name'] = $asset_name_s;
				$data_add['model_id'] = $data['model_id'];
				$data_add['status'] = $data['status'];
				$data_add['supplier_id'] = $data['supplier_id'];
				$data_add['date_buy'] = $data['date_buy'];
				$data_add['order_number'] = $data['order_number'];
				$data_add['unit_price'] = $data['unit_price'];
				$data_add['asset_location'] = $data['asset_location'];
				$data_add['warranty_period'] = $data['warranty_period'];
				$data_add['description'] = $data['description'];
				$data_add['qr_code'] = $qr_code;
				$data_add['series'] = $serial;
				$data_add['requestable'] = isset($data['requestable']) ? $data['requestable'] : 0;

				$old_model_id = '';
				$this->db->where('id', $id);
				$data_saved_assets = $this->db->get(db_prefix() . 'fe_assets')->row();
				if($data_saved_assets && $key == 0){
					$old_model_id = $data_saved_assets->model_id;
					$this->db->where('id', $data_saved_assets->id);
					$this->db->update(db_prefix() . 'fe_assets',$data_add);
					if($this->db->affected_rows() > 0) {
						$change = '';
						if($data_add['status'] != $data_saved_assets->status){
							$status_name1 = '';
							$data_status1 = $this->fixed_equipment_model->get_status_labels($data_saved_assets->status);
							if($data_status1){
								$status_name1 = $data_status1->name;
							}
							$status_name2 = '';
							$data_status2 = $this->fixed_equipment_model->get_status_labels($data_add['status']);
							if($data_status2){
								$status_name2 = $data_status2->name;
							}
							if($status_name1 != '' && $status_name2 != ''){
								$change = _l('fe_status').': '.$status_name1.' &#10145; '.$status_name2;
							}
						}
						$this->add_log(get_staff_user_id(), 'update', $data_saved_assets->id, '', $change, '', null, '');
						$affectedRows++;
					}
					// Custom field
					if($old_model_id != '' && ($data['model_id'] == $old_model_id)){
						foreach ($data['customfield'] as $customfield_id => $field_value) {
							$value = (is_array($field_value) ? json_encode($field_value) : $field_value);
							$this->db->where('asset_id', $id);
							$this->db->where('custom_field_id', $customfield_id);
							$data_customfield = $this->db->get(db_prefix().'fe_custom_field_values')->row();
							if($data_customfield){
								$this->db->where('id', $data_customfield->id);
								$this->db->update(db_prefix() . 'fe_custom_field_values', ['value' => $value]);
								if($this->db->affected_rows() > 0) {
									$affectedRows++;
								}
							}
						}
					}
					else{
						// If change model -> delete old custom field and add new custom field
						$this->db->where('asset_id', $id);
						$this->db->delete(db_prefix().'fe_custom_field_values');
						if($this->db->affected_rows() > 0) {
							if(isset($data['customfield'])){
								foreach ($data['customfield'] as $customfield_id => $field_value) {
									$value = (is_array($field_value) ? json_encode($field_value) : $field_value);
									$data_customfield = $this->get_custom_fields($customfield_id);
									if($data_customfield){
										$data_insert['title'] = $data_customfield->title;
										$data_insert['type'] = $data_customfield->type;
										$data_insert['option'] = $data_customfield->option;
										$data_insert['required'] = $data_customfield->required;
										$data_insert['value'] = $value;
										$data_insert['fieldset_id'] = $data_customfield->fieldset_id;
										$data_insert['custom_field_id'] = $data_customfield->id;
										$data_insert['asset_id'] = $id;
										$this->db->insert(db_prefix() . 'fe_custom_field_values',$data_insert);
										if($this->db->insert_id() > 0) {
											$affectedRows++;
										}
									}
								}
							}
						}
					}
						// Custom field
				}
				else{
					$this->db->insert(db_prefix() . 'fe_assets',$data_add);
					$insert_id = $this->db->insert_id();
					if($insert_id){
						$this->add_log(get_staff_user_id(), 'create_new', $insert_id, '', '', '', null, '');
						if(isset($data['customfield'])){
							foreach ($data['customfield'] as $customfield_id => $field_value) {
								$value = (is_array($field_value) ? json_encode($field_value) : $field_value);
								$data_customfield = $this->get_custom_fields($customfield_id);
								if($data_customfield){
									$data_insert['title'] = $data_customfield->title;
									$data_insert['type'] = $data_customfield->type;
									$data_insert['option'] = $data_customfield->option;
									$data_insert['required'] = $data_customfield->required;
									$data_insert['value'] = $value;
									$data_insert['fieldset_id'] = $data_customfield->fieldset_id;
									$data_insert['custom_field_id'] = $data_customfield->id;
									$data_insert['asset_id'] = $insert_id;
									$this->db->insert(db_prefix() . 'fe_custom_field_values',$data_insert);
									if($this->db->insert_id() > 0) {
										$affectedRows++;
									}
								}
							}
						}
						$affectedRows++;
					}
				}
			}
		}
	}

	if($affectedRows != 0){
		return true;				
	}
	else{
		return false;
	}
}
/**
 * delete assets
 * @param  integer $id 
 * @return integer     
 */
public function delete_assets($id){
	$this->db->where('rel_id', $id);
	$this->db->where('rel_type', 'assets');
	$attachments = $this->db->get(db_prefix().'files')->result_array();
	foreach ($attachments as $attachment) {
		$this->delete_assets_attachment($attachment['id']);
	}
	$this->db->where('id', $id);
	$this->db->delete(db_prefix() . 'fe_assets');
	if ($this->db->affected_rows() > 0) {
		$this->delete_history_assets($id);
		$this->delete_checkin_out_assets($id);
		return true;
	}

	return false;
}
/**
 * delete assets attachment
 * @param  integer $id 
 * @return integer     
 */
public function delete_assets_attachment($id)
{
	$attachment = $this->get_assets_attachments('assets', $id);
	$deleted    = false;
	if ($attachment) {
		if (empty($attachment->external)) {
			unlink(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'. $attachment->rel_id . '/' . $attachment->file_name);
		}
		$this->db->where('id', $attachment->id);
		$this->db->delete(''.db_prefix().'files');
		if ($this->db->affected_rows() > 0) {
			$deleted = true;
		}

		if (is_dir(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'. $attachment->rel_id)) {
			$other_attachments = list_files(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'. $attachment->rel_id);
			if (count($other_attachments) == 0) {
				delete_dir(FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/'. $attachment->rel_id);
			}
		}
	}
	return $deleted;
}
/**
 * get assets attachments
 * @param  string $type   
 * @param  integer $assets 
 * @param  integer $id     
 */
public function get_assets_attachments($type, $assets, $id = '')
{
	// If is passed id get return only 1 attachment
	if (is_numeric($id)) {
		$this->db->where('id', $id);
	} else {
		$this->db->where('rel_id', $assets);
	}
	$this->db->where('rel_type', $type);
	$result = $this->db->get(''.db_prefix().'files');
	if (is_numeric($id)) {
		return $result->row();
	}
	return $result->result_array();
}
	/**
	 * get assets
	 * @param  integer $id 
	 * @return array or object    
	 */
	public function get_assets($id = '', $type = '', $checkin = false, $requestable = false, $status = ''){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_assets')->row();
		}
		else{
			$this->db->select('*, '.db_prefix().'fe_assets.id as id');
			if($status != ''){
				$this->db->join(db_prefix().'fe_status_labels', db_prefix().'fe_status_labels.id = '.db_prefix().'fe_assets.status', 'left');
				$this->db->where(db_prefix().'fe_status_labels.status_type', $status);
			}
			if($type != ''){
				$this->db->where('type', $type);
			}
			if($checkin == true){
				$this->db->where('checkin_out', 1);
			}
			if($requestable == true){
				$this->db->where('requestable', 1);
			}
			$this->db->where('active', 1);
			return $this->db->get(db_prefix().'fe_assets')->result_array();
		}
	}
/**
 * check exist serial
 * @param  string $serial   
 * @param  string $asset_id 
 * @return object           
 */

public function check_exist_serial($serial, $asset_id = '')
{
	$query = '';
	if($asset_id != ''){
		$query = ' and id != '.$asset_id.'';
	}
	return $this->db->query('select * from '.db_prefix().'fe_assets where active = 1 and series = \''.$serial.'\''.$query)->row();
}

/**
 * check in assets
 * @param  array $data 
 * @return array       
 */
public function check_in_assets($data){
	if(isset($data['checkin_date'])){
		$data['checkin_date'] = fe_format_date($data['checkin_date']);
	}

	if(isset($data['expected_checkin_date'])){
		$data['expected_checkin_date'] = fe_format_date($data['expected_checkin_date']);
	}

	if($data['type'] == 'checkin'){
		$data['check_status'] = 1;
	}
	$data['item_type'] = 'asset';
	$this->db->insert(db_prefix().'fe_checkin_assets', $data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		//Get checkout id to update status after checkin
		$checkin_out_id = '';
		$data_assets = $this->get_assets($data['item_id']);
		if($data_assets){
			$checkin_out_id = $data_assets->checkin_out_id;	
			// If has select location
			if(isset($data['location_id']) && $data['location_id'] != ''){
				$data_asset['location_id'] = $data['location_id'];
			}
			else{
				// Not select location then using default location
				$data_asset['location_id'] = $data_assets->asset_location;	
				// If is check out and check out to asset then get location of this asset	
				if((isset($data['type']) && $data['type'] == 'checkout')){

					if((isset($data['checkout_to']) && $data['checkout_to'] == 'asset') && (isset($data['asset_id']) && is_numeric($data['asset_id']))) {
						$data_asset_checkout = $this->get_assets($data['asset_id']);
						if(is_numeric($data_asset_checkout->location_id) && $data_asset_checkout->location_id > 0){
							$data_asset['location_id'] = $data_asset_checkout->location_id;
							if(!is_numeric($data_asset['location_id']) && ($data_asset['location_id'] == 0 || $data_asset['location_id'] == null || $data_asset['location_id'] == '')){
								if(is_numeric($data_asset_checkout->asset_location) && $data_asset_checkout->asset_location > 0){
									$data_asset['location_id'] = $data_asset_checkout->asset_location;
								}
							}
						}
					}
				}	
			}	
		}

		$asset_name = '';
		if(isset($data['asset_name']) && $data['asset_name'] != ''){
			$asset_name = $data['asset_name'];
		}
		$checkin_out = 1;
		if($data['type'] == 'checkout'){
			$checkin_out = 2;
		}
		$data_asset['assets_name'] = $asset_name;
		$data_asset['checkin_out'] = $checkin_out;
		$data_asset['checkin_out_id'] = $insert_id;
		$data_asset['status'] = $data['status'];
		$this->db->where('id', $data['item_id']);
		$this->db->update(db_prefix().'fe_assets', $data_asset);
		if($data['type'] == 'checkout'){
			$to_id = '';
			switch ($data['checkout_to']) {
				case 'user':
				$to_id = $data['staff_id'];
				break;
				case 'asset':
				$to_id = $data['asset_id'];
				$this->update_location_for_checkout_to_asset($data['item_id'], $data_asset['location_id']);
				break;
				case 'location':
				$to_id = $data['location_id'];
				$this->update_location_for_checkout_to_asset($data['item_id'], $data_asset['location_id']);
				break;
			}
			// Add log checkout
			$this->add_log(get_staff_user_id(), $data['type'], $data['item_id'], '', '', $data['checkout_to'], $to_id, $data['notes']);
		}
		elseif($data['type'] == 'checkin'){
			$to_id = '';
			$to = '';

			$data_checkout = '';
			if($checkin_out_id == '' || $checkin_out_id == null){
				$data_checkout = $this->db->query('select * from '.db_prefix().'fe_checkin_assets where item_id = '.$data['item_id'].' and (type="checkout" OR type="request") order by date_creator desc limit 0,1')->row();
			}
			else{
				$data_checkout = $this->get_checkin_out_data($checkin_out_id);
			}

			if($data_checkout != ''){
				// Update status of checkout when checkin
				$this->db->where('id', $data_checkout->id);
				$this->db->update(db_prefix().'fe_checkin_assets', ['check_status' => 1]);
				//
				$to_id = '';
				$to = $data_checkout->checkout_to;
				switch ($to) {
					case 'user':
					$to_id = $data_checkout->staff_id;
					break;
					case 'asset':
					$to_id = $data_checkout->asset_id;
					$this->update_location_for_checkout_to_asset($data_checkout->item_id, $data_asset['location_id']);
					break;
					case 'location':
					$to_id = $data_checkout->location_id;
					$this->update_location_for_checkout_to_asset($data_checkout->item_id, $data_asset['location_id']);
					break;
				}
			}
			// Add log checkin
			$this->add_log(get_staff_user_id(), $data['type'], $data['item_id'], '', '', $to, $to_id, $data['notes']);
		}
		return $insert_id;
	}
	return 0;
}
/**
 * add log
 * @param string $admin_id 
 * @param string $action   
 * @param string $item_id  
 * @param string $target   
 * @param string $changed  
 * @param string $to       
 * @param string $to_id    
 * @param string $notes    
 */
public function add_log($admin_id = '', $action = '', $item_id = '', $target = '', $changed = '', $to = '',$to_id = '',$notes = ''){
	$data['admin_id'] = $admin_id;
	$data['action'] = $action;
	$data['item_id'] = $item_id;
	$data['target'] = $target;
	$data['changed'] = $changed;
	$data['to'] = $to;
	$data['to_id'] = $to_id;
	$data['notes'] = $notes;
	$this->db->insert(db_prefix().'fe_log_assets', $data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		return $insert_id;
	}
	return 0;
}

/**
 * count log detail
 * @param  integer $item_id 
 * @param  string $action  
 * @param  integer $requestable  
 * @return integer          
 */
public function count_log_detail($item_id = '', $type = '', $requestable = '', $request_status = ''){
	if($item_id != ''){
		$this->db->where('item_id', $item_id);
	}
	if($type != ''){
		$this->db->where('type', $type);
	}
	if(is_numeric($requestable)){
		$this->db->where('requestable', $requestable);
	}
	if(is_numeric($request_status)){
		$this->db->where('request_status', $request_status);
	}
	return $this->db->get(db_prefix().'fe_checkin_assets')->num_rows();
}

/**
 * get last checkin out assets
 * @param  integer $asset_id 
 * @param  string $type     
 * @return object           
 */
public function get_last_checkin_out_assets($asset_id, $type = 'checkin'){
	return $this->db->query('select * from '.db_prefix().'fe_checkin_assets where item_id = '.$asset_id.' and type = "'.$type.'" order by date_creator desc limit 0,1')->row();
}
/**
 * add licenses
 * @param array $data 
 */
public function add_licenses($data){
	$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
	$data['date_buy'] = (isset($data['date_buy']) || $data['date_buy'] != '') ? fe_format_date($data['date_buy']) : null;
	$data['expiration_date'] = (isset($data['expiration_date']) || $data['expiration_date'] != '') ? fe_format_date($data['expiration_date']) : null;
	$data['termination_date'] = (isset($data['termination_date']) || $data['termination_date'] != '') ? fe_format_date($data['termination_date']) : null;
	$this->db->insert(db_prefix() . 'fe_assets',$data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		for($i = 1; $i <= $data['seats']; $i++){
			$data_seats['seat_name'] = 'Seat '.$i;
			$data_seats['to'] = '';
			$data_seats['to_id'] = '';
			$data_seats['license_id'] = $insert_id;
			$this->db->insert(db_prefix() . 'fe_seats',$data_seats);
		}
	}
	return $insert_id;
}
/**
 * update licenses
 * @param  array $data 
 */
public function update_licenses($data){
	$data_all_seat = $this->get_seat_by_parent($data['id']);
	$data_avail_seat = $this->get_seat_by_parent($data['id'], 1);
	$total_all = count($data_all_seat);
	$total_avail = count($data_avail_seat);
	if($data['seats'] > $total_all){
		// Aditional seat
		$identity = $total_all + 1;
		$remain = $data['seats'] - $total_all;
		for($i = 1; $i <= $remain; $i++){
			$data_seats['seat_name'] = 'Seat '.$identity;
			$data_seats['to'] = '';
			$data_seats['to_id'] = '';
			$data_seats['license_id'] = $data['id'];
			$this->db->insert(db_prefix() . 'fe_seats',$data_seats);
			$identity++;
		}
	}
	if($data['seats'] < $total_all){
		// Remove seat
		$remain = $total_all - $data['seats'];
		if($remain > $total_avail){
			return 3;
		}
		else{
			foreach ($data_avail_seat as $key => $value) {
				$this->db->where('id', $value['id']);
				$this->db->delete(db_prefix() . 'fe_seats');
				if ($this->db->affected_rows() > 0) {
					$this->db->where('item_id', $value['id']);
					$this->db->delete(db_prefix().'fe_checkin_assets');
				}
				if(($key+1) == $remain){
					break;
				}
			}
		}
	}
	$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
	$data['date_buy'] = (isset($data['date_buy']) || $data['date_buy'] != '') ? fe_format_date($data['date_buy']) : null;
	$data['expiration_date'] = (isset($data['expiration_date']) || $data['expiration_date'] != '') ? fe_format_date($data['expiration_date']) : null;
	$data['termination_date'] = (isset($data['termination_date']) || $data['termination_date'] != '') ? fe_format_date($data['termination_date']) : null;
	if(isset($data['reassignable'])){
		$data['reassignable'] = $data['reassignable'];
	}
	else{
		$data['reassignable'] = 0;
	}
	if(isset($data['maintained'])){
		$data['maintained'] = $data['maintained'];
	}
	else{
		$data['maintained'] = 0;
	}
	$this->db->where('id', $data['id']);
	$this->db->update(db_prefix() . 'fe_assets',$data);
	if($this->db->affected_rows() > 0) {
		return 1;
	}
	return 2;
}
/**
 * delete licenses
 * @param  integer $id 
 * @return boolean     
 */
public function delete_licenses($id){
	$this->db->where('id', $id);
	$this->db->delete(db_prefix() . 'fe_assets');
	if ($this->db->affected_rows() > 0) {
		$this->delete_history_assets($id);
		$this->delete_checkin_out_assets($id);
		$this->delete_seats($id);
		return true;
	}
	return false;
}


/**
 * delete checkin out assets
 * @param  integer $item_id 
 * @return integer          
 */
public function delete_history_assets($item_id){
	$this->db->where('item_id', $item_id);
	$this->db->delete(db_prefix() . 'fe_log_assets');
	if ($this->db->affected_rows() > 0) {
		return true;
	}
	return false;
}

/**
 * delete checkin out assets
 * @param  integer $item_id 
 * @return integer          
 */
public function delete_checkin_out_assets($item_id){
	$this->db->where('item_id', $item_id);
	$this->db->delete(db_prefix() . 'fe_checkin_assets');
	if ($this->db->affected_rows() > 0) {
		return true;
	}
	return false;
}
/**
 * delete seats
 * @param  integer $license_id 
 * @return boolean             
 */
public function delete_seats($license_id){
	$this->db->where('license_id', $license_id);
	$this->db->delete(db_prefix() . 'fe_seats');
	if ($this->db->affected_rows() > 0) {
		return true;
	}
	return false;
}
/**
 * check in licenses
 * @param  array $data 
 * @return array       
 */
public function check_in_licenses($data){
	if(isset($data['checkin_date'])){
		$data['checkin_date'] = fe_format_date($data['checkin_date']);
	}
	$data['item_type'] = 'license';
	$this->db->insert(db_prefix().'fe_checkin_assets', $data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		$to = '';
		$to_id = '';
		if(isset($data['checkout_to'])){
			$to = $data['checkout_to'];
			switch ($to) {
				case 'user':
				$to_id = $data['staff_id'];
				break;
				case 'asset':
				$to_id = $data['asset_id'];
				break;
			}
		}

		$check_status = 1;
		if($data['type'] == 'checkout'){
			$check_status = 2;
		}
		// --- Upadate status Seat table
		$this->db->where('id', $data['item_id']);
		$this->db->update(db_prefix().'fe_seats', [
			'status' => $check_status,
			'to' => $to,
			'to_id' => $to_id
		]);
		// --- End upadate status Seat table

		// --- Upadate status Assets table if all item in Seat table same status
		$asset_id = '';
		$data_seat = $this->get_seats($data['item_id']);
		if($data_seat){
			$asset_id = $data_seat->license_id;
			$full_status = $this->check_full_checkin_out($asset_id, $check_status);
			if($full_status){
				$this->db->where('id', $asset_id);
				$this->db->update(db_prefix().'fe_assets', [
					'checkin_out' => $check_status
				]);
			}
		}
		// --- End upadate status Assets table

		// ---  Add log
		if($asset_id != ''){
			if($data['type'] == 'checkout'){
				$this->add_log(get_staff_user_id(), $data['type'], $asset_id, '', '', $to, $to_id, $data['notes']);
			}
			elseif($data['type'] == 'checkin'){
				$data_checkout = $this->db->query('select * from '.db_prefix().'fe_log_assets where item_id = '.$asset_id.' and action="checkout" order by date_creator desc limit 0,1')->row();
				if($data_checkout){
					$to_id = $data_checkout->to_id;
					$to = $data_checkout->to;
				}
				$this->add_log(get_staff_user_id(), $data['type'], $asset_id, '', '', $to, $to_id, $data['notes']);
			}
		}
		// ---  End add log
		return $insert_id;
	}
	return 0;
}
/**
 * get seats
 * @param  integer $id 
 * @return integer     
 */
public function get_seats($id){
	if($id != ''){
		$this->db->where('id', $id);
		return $this->db->get(db_prefix().'fe_seats')->row();
	}
	else{
		return $this->db->get(db_prefix().'fe_seats')->result_array();
	}
}
/**
 * check full checkin out
 * @param  integer  $license_id 
 * @param  integer $status     
 * @return boolean              
 */
public function check_full_checkin_out($license_id, $status = 1){
	$this->db->where('license_id', $license_id);
	$data = $this->db->get(db_prefix().'fe_seats')->result_array();
	if($data && is_array($data)){
		$count_total = count($data);
		$count_effect = 0;
		foreach ($data as $key => $value) {
			if($value['status'] == $status){
				$count_effect++;
			}
		}
		return ($count_total == $count_effect);
	}
	return false;
}
/**
 * count total avail seat
 * @param  integer $license_id 
 * @return object             
 */
public function count_total_avail_seat($license_id){
	$obj = new stdClass();
	$obj->total = 0;
	$obj->avail = 0;
	$this->db->where('license_id', $license_id);
	$data = $this->db->get(db_prefix().'fe_seats')->result_array();
	if($data && is_array($data)){
		$count_total = count($data);
		$count_effect = 0;
		foreach ($data as $key => $value) {
			if($value['status'] == 1){
				$count_effect++;
			}
		}
		$obj->total = $count_total;
		$obj->avail = $count_effect;
	}
	return $obj;
}
/**
 * check in license auto
 * @param  array $data 
 * @return integter       
 */
public function check_in_license_auto($data){
	$result = 0;
	if(isset($data['id']) && $data['id'] != ''){
		$id = $data['id'];
		unset($data['id']);
		if($data['type'] == 'checkin'){
			$this->db->where('status', 2);
			$this->db->order_by('id', 'desc');
			$this->db->where('license_id', $id);
		}
		else{
			$this->db->where('status', 1);
			$this->db->order_by('id', 'desc');
			$this->db->where('license_id', $id);
		}
		$data_seat = $this->db->get(db_prefix().'fe_seats')->row();
		if($data_seat){
			$data['item_id'] = $data_seat->id;
		}
		if(isset($data['item_id']) && $data['item_id'] != ''){
			$result = $this->check_in_licenses($data);
		}
	}
	return $result;
}
/**
 * get seat by parent
 * @param  integer $license_id 
 * @return array object             
 */
public function get_seat_by_parent($license_id, $status = ''){
	$this->db->where('license_id', $license_id);
	if($status != ''){
		$this->db->where('status', $status);		
	}
	$this->db->order_by('id', 'desc');		
	return $this->db->get(db_prefix().'fe_seats')->result_array();
}

	/**
	 * add accessories
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_accessories($data){
		$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
		$data['date_buy'] = fe_format_date($data['date_buy']);
		$this->db->insert(db_prefix().'fe_assets', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update accessories
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_accessories($data){
		if(isset($data['quantity'])){
			$total_checkout = $this->count_checkin_asset_by_parents($data['id']);
			$data_assets = $this->get_assets($data['id']);
			if($data_assets){
				if($data['quantity'] == 0){
					// Quantity not valid
					return 1;
				}
				if($data['quantity'] < $data_assets->quantity){
					$delete = $data_assets->quantity - $data['quantity'];
					$remain = $data_assets->quantity - $total_checkout;
					if($delete > $remain){
					// Quantity not valid (Not smaller than valid quantity)
						return 1;
					}
				}
			}
			else{
				// This accessory not exist
				return 2;
			}
		}
		else{
			// Quantity is unknown
			return 3;
		}
		$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
		$data['date_buy'] = fe_format_date($data['date_buy']);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_assets', $data);
		if($this->db->affected_rows() > 0) {
			// Updated successfull
			return 4;
		}
		// Update fail
		return 5;
	}

		/**
	 * add consumables
	 * @param array $data 
	 * @return integer $insert id 
	 */
		public function add_consumables($data){
			$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
			$data['date_buy'] = fe_format_date($data['date_buy']);
			$this->db->insert(db_prefix().'fe_assets', $data);
			$insert_id = $this->db->insert_id();
			if($insert_id){
				return $insert_id;
			}
			return 0;
		}
	/**
	 * update consumables
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_consumables($data){
		if(isset($data['quantity'])){
			$total_checkout = $this->count_checkin_asset_by_parents($data['id']);
			$data_assets = $this->get_assets($data['id']);
			if($data_assets){
				if($data['quantity'] == 0){
					// Quantity not valid
					return 1;
				}
				if($data['quantity'] < $data_assets->quantity){
					$delete = $data_assets->quantity - $data['quantity'];
					$remain = $data_assets->quantity - $total_checkout;
					if($delete > $remain){
					// Quantity not valid (Not smaller than valid quantity)
						return 1;
					}
				}
			}
			else{
				// This accessory not exist
				return 2;
			}
		}
		else{
			// Quantity is unknown
			return 3;
		}
		$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
		$data['date_buy'] = fe_format_date($data['date_buy']);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_assets', $data);
		if($this->db->affected_rows() > 0) {
			// Updated successfull
			return 4;
		}
		// Update fail
		return 5;
	}
	/**
	 * add components
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_components($data){
		$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
		$data['date_buy'] = fe_format_date($data['date_buy']);
		$this->db->insert(db_prefix().'fe_assets', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update components
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_components($data){
		$data['unit_price'] = fe_reformat_currency_asset($data['unit_price']);
		$data['date_buy'] = fe_format_date($data['date_buy']);
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_assets', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * add predefined_kits
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_predefined_kits($data){
		$this->db->where('assets_name', $data['assets_name']);
		$this->db->where('type', 'predefined_kit');
		$data_exist = $this->db->get(db_prefix().'fe_assets')->row();
		if(!$data_exist){
			$this->db->insert(db_prefix().'fe_assets', $data);
			$insert_id = $this->db->insert_id();
			if($insert_id){
				return $insert_id;
			}
			return 0;
		}
		else{
			return -1;
		}
	}
	/**
	 * update predefined_kits
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_predefined_kits($data){
		$this->db->where('assets_name', $data['assets_name']);
		$this->db->where('type', 'predefined_kit');
		$data_exist = $this->db->get(db_prefix().'fe_assets')->row();
		if(!$data_exist){
			$this->db->where('id', $data['id']);
			$this->db->update(db_prefix().'fe_assets', $data);
			if($this->db->affected_rows() > 0) {
				return 1;
			}
			return 0;
		}
		return -1;
	}

	/**
	 * add model predefined kits
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_model_predefined_kits($data){
		$this->db->where('model_id',$data['model_id']);
		$this->db->where('parent_id',$data['parent_id']);
		$data_model_p = $this->db->get(db_prefix().'fe_model_predefined_kits')->row();
		if(!$data_model_p){
			$this->db->insert(db_prefix().'fe_model_predefined_kits', $data);
			$insert_id = $this->db->insert_id();
			if($insert_id){
				return $insert_id;
			}
			return 0;
		}
		return '';
	}

	/**
	 * update model predefined kits
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_model_predefined_kits($data){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_model_predefined_kits', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * delete model predefined kits
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_model_predefined_kits($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_model_predefined_kits');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
 * check in accessories
 * @param  array $data 
 * @return array       
 */
	public function check_in_accessories($data){
		$asset_id = (isset($data['item_id']) ? $data['item_id'] : '');
		$to = (isset($data['checkout_to']) ? $data['checkout_to'] : '');
		$to_id = (isset($data['staff_id']) ? $data['staff_id'] : '');
		if($data['type'] == 'checkin'){
			$this->db->where('id', $data['id']);
			$this->db->update(db_prefix().'fe_checkin_assets',[
				'status' => 1,
				'checkin_date' => fe_format_date($data['checkin_date']),
				'notes' => $data['notes']
			]);

			if($this->db->affected_rows() > 0) {
				//Check in
				$this->db->where('id', $data['id']);
				$data_checkout = $this->db->get(db_prefix().'fe_checkin_assets')->row();
				if($data_checkout){
					unset($data_checkout->id);
					$data_checkout->type = 'checkin';
					$data_checkout->item_type = 'accessory';
					$this->db->insert(db_prefix().'fe_checkin_assets', (array)$data_checkout);
				}
				// ---  Add log
				if($asset_id != ''){
					$data_checkout = $this->db->query('select * from '.db_prefix().'fe_log_assets where item_id = '.$asset_id.' and action="checkout" order by date_creator desc limit 0,1')->row();
					if($data_checkout){
						$to_id = $data_checkout->to_id;
						$to = $data_checkout->to;
					}
					$this->add_log(get_staff_user_id(), $data['type'], $asset_id, '', '', $to, $to_id, $data['notes']);
				}
				// ---  End add log
				return true;
			}
			return false;
		}
		else{
			unset($data['id']);
			$data['item_type'] = 'accessory';
			$this->db->insert(db_prefix().'fe_checkin_assets', $data);
			$insert_id = $this->db->insert_id();
			if($insert_id){
				// ---  Add log
				if($asset_id != ''){
					$this->add_log(get_staff_user_id(), $data['type'], $asset_id, '', '', $to, $to_id, $data['notes']);
				}
				// ---  End add log
				return $insert_id;
			}
			return 0;
		}
	}
/**
 * count checkin asset by parents
 * @param  integer $parent_id 
 * @return integer            
*/
public function count_checkin_asset_by_parents($parent_id){
	$this->db->where('item_id', $parent_id);
	$this->db->where('status', 2);
	return $this->db->get(db_prefix().'fe_checkin_assets')->num_rows();
}

/**
 * check in consumables
 * @param  array $data 
 * @return array       
 */
public function check_in_consumables($data){
	$asset_id = $data['item_id'];
	$to = $data['checkout_to'];
	$to_id = $data['staff_id'];

	if($data['type'] == 'checkin'){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_checkin_assets',[
			'status' => 1,
			'checkin_date' => fe_format_date($data['checkin_date']),
			'notes' => $data['notes']
		]);
		if($this->db->affected_rows() > 0) {
			// --- Check in
			$this->db->where('id', $data['id']);
			$data_checkout = $this->db->get(db_prefix().'fe_checkin_assets')->row();
			if($data_checkout){
				unset($data_checkout->id);
				$data_checkout->type = 'checkin';
				$data_checkout->item_type = 'consumable';
				$this->db->insert(db_prefix().'fe_checkin_assets', (array)$data_checkout);
			}
			// ---  Add log
			if($asset_id != ''){
				$data_checkout = $this->db->query('select * from '.db_prefix().'fe_log_assets where item_id = '.$asset_id.' and action="checkout" order by date_creator desc limit 0,1')->row();
				if($data_checkout){
					$to_id = $data_checkout->to_id;
					$to = $data_checkout->to;
				}
				$this->add_log(get_staff_user_id(), $data['type'], $asset_id, '', '', $to, $to_id, $data['notes']);
			}
				// ---  End add log
			return true;
		}
		return false;
	}
	else{
		unset($data['id']);
		$data['item_type'] = 'consumable';
		$this->db->insert(db_prefix().'fe_checkin_assets', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			// ---  Add log
			if($asset_id != ''){
				$this->add_log(get_staff_user_id(), $data['type'], $asset_id, '', '', $to, $to_id, $data['notes']);
			}
			// ---  End add log
			return $insert_id;
		}
		return 0;
	}
}


/**
 * check in components
 * @param  array $data 
 * @return array       
 */
public function check_in_components($data){
	if($data['type'] == 'checkin'){
		$data_checked_out = $this->get_checkin_out_data($data['id']);
		if($data_checked_out){
			$old_qty = $data_checked_out->quantity;

			// Get old quantity checked out
			// If adjust quantity is greater than the old quantity return error is -1
			// Else if adjust quantity is equal old quantity then change status to 1 (checked in) and update quantity, note
			// Else if adjust quantity is smaller than old quantity then only change quantity of check out

			if($data['quantity'] > $old_qty){
				return false;
			}
			if($data['quantity'] == $old_qty){
				$this->db->where('id', $data['id']);
				$this->db->update(db_prefix().'fe_checkin_assets',[
					'status' => 1,
					'quantity' => 0,
					'notes' => $data['notes']
				]);
				if($this->db->affected_rows() > 0) {
					return true;
				}
			}
			if($data['quantity'] < $old_qty){
				$this->db->where('id', $data['id']);
				$this->db->update(db_prefix().'fe_checkin_assets',[
					'quantity' => $old_qty - $data['quantity'],
					'notes' => $data['notes']
				]);
				if($this->db->affected_rows() > 0) {
					return true;
				}
			}
			// --- Check in
			$this->db->where('id', $data['id']);
			$data_checkout = $this->db->get(db_prefix().'fe_checkin_assets')->row();
			if($data_checkout){
				unset($data_checkout->id);
				$data_checkout->type = 'checkin';
				$data_checkout->item_type = 'component';
				$data_checkout->quantity = $data['quantity'];
				$this->db->insert(db_prefix().'fe_checkin_assets', (array)$data_checkout);
			}
		}
		return '';
	}
	else{
		$data['item_type'] = 'component';
		$data_assets = $this->get_assets($data['item_id']);
		$amount_checked_out = $this->count_checkin_component_by_parents($data['item_id']);
		if($data_assets){
			$total_amount = $data_assets->quantity;
			if(($amount_checked_out + $data['quantity']) <= $total_amount){
				unset($data['id']);
				$this->db->insert(db_prefix().'fe_checkin_assets', $data);
				$insert_id = $this->db->insert_id();
				if($insert_id){
					return $insert_id;
				}
			}
			else{
				return -1;
			}
		}
		return 0;
	}
}

/**
 * check in predefined_kits
 * @param  array $data 
 * @return array       
 */
public function check_in_predefined_kits($data){
	if($data['type'] == 'checkin'){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_checkin_assets',[
			'status' => 1,
			'quantity' => $data['quantity'],
			'notes' => $data['notes']
		]);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	else{
		$robj = new stdClass();
		unset($data['id']);
		if(isset($data['checkin_date'])){
			$data['checkin_date'] = fe_format_date($data['checkin_date']);
		}
		if(isset($data['expected_checkin_date'])){
			$data['expected_checkin_date'] = fe_format_date($data['expected_checkin_date']);
		}
		$result = $this->list_asset_checkout_predefined_kits($data['item_id']);
		if($result->status == 2){
			$affectedRows = 0;
			$list_asset = $result->list_asset;
			foreach ($list_asset as $asset) {
				$data_checkout['item_id'] = $asset['id'];
				$data_checkout['type'] = 'checkout';
				$data_checkout['model'] = $asset['model'];
				$data_checkout['asset_name'] = $asset['assets_name'];
				$data_checkout['status'] = $asset['status'];
				$data_checkout['checkout_to'] = 'user';
				$data_checkout['location_id'] = '';
				$data_checkout['asset_id'] = $asset['id'];
				$data_checkout['staff_id'] = $data['staff_id'];
				$data_checkout['checkin_date'] = $data['checkin_date'];
				$data_checkout['expected_checkin_date'] = $data['expected_checkin_date'];
				$data_checkout['predefined_kit_id'] = $data['item_id'];
				$data_checkout['notes'] = $data['notes'];
				$res = $this->check_in_assets($data_checkout);
				if($res != 0){
					$affectedRows++;
				}
			}
			if($affectedRows != 0){
				$robj->status = 2;
				$robj->msg = _l('fe_checkout_successfully');
				return $robj;
			}
			else{
				$robj->status = 3;
				$robj->msg = _l('fe_checkout_fail');
				return $robj;
			}
		}
		else{
			return $result;
		}

	}
}
/**
 * list asset checkout predefined kits
 * @param  integer $kit_id 
 * @return object         
 */
public function list_asset_checkout_predefined_kits($kit_id){
	$robj = new stdClass();
	$robj->status = 2;
	$robj->msg = '';
	$list_asset = [];
	$this->db->where('parent_id', $kit_id);
	$list_model = $this->db->get(db_prefix().'fe_model_predefined_kits')->result_array();
	if($list_model){
		$count_affected_model = 1;
		$count_model = 1;
		foreach ($list_model as $model_append) {
			$count_model++;
			$model_id = $model_append['model_id'];
			$model_name = '';
			$models = $this->get_models($model_id);
			if($models){
				$model_name = $models->model_name;
			}
			$this->db->where('model_id', $model_id);
			$this->db->where('active', 1);
			$this->db->where('type', 'asset');
			$this->db->where('checkin_out', 1);
			$this->db->order_by('id', 'desc');

			$this->db->select(db_prefix().'fe_assets.id, assets_name, status');

			$this->db->join(db_prefix().'fe_status_labels', db_prefix().'fe_status_labels.id = '.db_prefix().'fe_assets.status', 'left');
			$this->db->where(db_prefix().'fe_status_labels.status_type', 'deployable');
			$list_asset_model = $this->db->get(db_prefix().'fe_assets')->result_array();
			if($list_asset_model){
				// If enough quantity or more -> get id of asset add to array
				$quantity = $model_append['quantity'];
				if(count($list_asset_model) >= $quantity){
					$count_affected_model++;
					foreach ($list_asset_model as $i => $asset) {
						$list_asset[] = array('id' => $asset['id'], 'assets_name' => $asset['assets_name'], 'status' => $asset['status'], 'model' => $model_name);
						if($i == ($quantity - 1)){
							break;
						}
					}
				}
				else{
					// Not enought quantity -> retrun error
					$robj->status = 1;
					$robj->msg = $model_name.' '._l('fe_not_enough_amount_of_asset_to_checkout');
					return $robj;
				}
			}
		}

		if($count_affected_model != $count_model){
			$robj->status = 1;
			$robj->msg = $model_name.' '._l('fe_not_enough_amount_of_asset_to_checkout');
			return $robj;
		}
	}
	else{
		// No model append
		$robj->status = 0;
		$robj->msg = _l('fe_no_model_available');
		return $robj;
	}
	$robj->list_asset = $list_asset;
	return $robj;
}

/**
 * add assets maintenances
 * @param array $data 
 */
public function add_assets_maintenances($data){
	if(isset($data['start_date'])){
		$data['start_date'] = fe_format_date($data['start_date']);
	}
	if(isset($data['completion_date'])){
		$data['completion_date'] = fe_format_date($data['completion_date']);
	}
	$data['cost'] = fe_reformat_currency_asset($data['cost']);
	$this->db->insert(db_prefix().'fe_asset_maintenances', $data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		return $insert_id;
	}
	return 0;
}
/**
 * update assets maintenances
 * @param array $data 
 */
public function update_assets_maintenances($data){
	if(isset($data['start_date'])){
		$data['start_date'] = fe_format_date($data['start_date']);
	}
	if(isset($data['completion_date'])){
		$data['completion_date'] = fe_format_date($data['completion_date']);
	}
	if(!isset($data['warranty_improvement'])){
		$data['warranty_improvement'] = $data['warranty_improvement'];
	}
	$data['cost'] = fe_reformat_currency_asset($data['cost']);
	$this->db->where('id', $data['id']);
	$this->db->update(db_prefix().'fe_asset_maintenances', $data);
	if($this->db->affected_rows() > 0) {
		return true;
	}
	return false;
}

public function get_asset_name($asset_id){
	$asset_name = '';
	if($asset_id != ''){
		$data_asset = $this->get_assets($asset_id);
		if($data_asset){
			$asset_name = $data_asset->assets_name;
			if($asset_name == ''){
				$data_model = $this->get_models($data_asset->model_id);
				if($data_model){
					$asset_name = $data_model->model_name;
				}
			}
		}
	}
	return $asset_name;
}


/**
 * get asset location checkout
 * @param   $asset_id 
 */
public function get_asset_location_checkout($checkin_out_id, $location_id){
	$current_location = '';
	$checkout_to = '';
	$checkout_type = '';
	$to_id = '';
	$this->db->where('id', $checkin_out_id);
	$this->db->where('type', 'checkout');
	$data_checkout = $this->db->get(db_prefix().'fe_checkin_assets')->row();
	if($data_checkout){
		$to = $data_checkout->checkout_to;
		$checkout_to = '';
		$checkout_type = $to;
		if($to != '' && $to != null){
			switch ($to) {
				case 'user':
				$department_name = '';
				if(is_numeric($data_checkout->staff_id) && $data_checkout->staff_id > 0){
					$data_staff_department = $this->departments_model->get_staff_departments($data_checkout->staff_id);
					if($data_staff_department){
						foreach ($data_staff_department as $key => $staff_department) {
							$department_name .= $staff_department['name'].', ';
						}
						if($department_name != ''){
							$department_name = rtrim($department_name,', ');
						}
					}
				}
				$checkout_to = get_staff_full_name($data_checkout->staff_id);
				$current_location = $department_name;
				$to_id = $data_checkout->staff_id;				
				break;
				case 'asset':
				if(is_numeric($location_id) && $location_id > 0){
					$data_assets = $this->get_assets($data_checkout->asset_id);
					if($data_assets){
						$checkout_to = (($data_assets->series != '') ? '('.$data_assets->series.') - ' : '').''.$data_assets->assets_name;

						if(is_numeric($location_id) && $location_id > 0){
							$data_locations = $this->fixed_equipment_model->get_locations($location_id);
							if($data_locations){
								$current_location = $data_locations->location_name;
							}							
						}
						
						$to_id = $data_checkout->asset_id;
					}
				}
				break;
				case 'location':
				if(is_numeric($location_id) && $location_id > 0){
					$data_locations = $this->get_locations($data_checkout->location_id);
					if($data_locations){
						$checkout_to = $data_locations->location_name;						
						$current_location = $data_locations->location_name;
						$to_id = $data_checkout->location_id;
					}
				}
				break;
			}
		}
	}
	$obj = new stdClass();
	$obj->current_location = $current_location;
	$obj->checkout_to = $checkout_to;
	$obj->checkout_type = $checkout_type;
	$obj->to_id = $to_id;
	return $obj;
}
/**
 * get asset location info
 * @param  integer $asset_id 
 * @return integer           
 */
public function get_asset_location_info($asset_id){
	$obj = new stdClass();
	$obj->default_location = '';
	$obj->curent_location = '';
	$obj->checkout_to = '';
	$obj->checkout_type = '';
	$obj->to_id = '';
	$data_assets = $this->get_assets($asset_id);
	if($data_assets){
		$default_location = '';
		$curent_location = '';
		$checkout_to = '';
		$to_id = '';
		$checkout_type = '';

		if(is_numeric($data_assets->asset_location) && $data_assets->asset_location > 0){
			$data_location = $this->get_locations($data_assets->asset_location);
			if($data_location){
				$default_location = $data_location->location_name;
			}
		}

		if($data_assets->checkin_out == 2){
			$checkout_info = $this->get_asset_location_checkout($data_assets->checkin_out_id, $data_assets->location_id);
			$curent_location = $checkout_info->current_location;
			$checkout_to = $checkout_info->checkout_to;
			$to_id = $checkout_info->to_id;
			$checkout_type = $checkout_info->checkout_type;
		}
		else{
			$location_id = $data_assets->location_id;
			if(is_numeric($location_id) && $location_id > 0){
				$data_location = $this->get_locations($location_id);
				if($data_location){
					$curent_location = $data_location->location_name;
				}
				else{
					$curent_location = $default_location;
				}
			}
			else{
				$curent_location = $default_location;
			}
		}
		$obj->checkout_to = $checkout_to;
		$obj->checkout_type = $checkout_type;
		$obj->default_location = $default_location; 
		$obj->curent_location = $curent_location;  
		$obj->to_id = $to_id;
	}
	return $obj;
}

/**
 * delete asset maintenances
 * @param  integer $id 
 * @return boolean     
 */
public function delete_asset_maintenances($id){
	$this->db->where('id', $id);
	$this->db->delete(db_prefix().'fe_asset_maintenances');
	if($this->db->affected_rows() > 0) {
		return true;
	}
	return false;
}

/**
 * get asset maintenances
 * @param  integer $id 
 * @return integer     
 */
public function get_asset_maintenances($id){
	if($id != ''){
		$this->db->where('id', $id);
		return $this->db->get(db_prefix().'fe_asset_maintenances')->row();
	}
	else{
		return $this->db->get(db_prefix().'fe_asset_maintenances')->result_array();
	}
}
/**
	 * add approval process
	 * @param array $data 
	 * @return boolean 
	 */
public function add_approval_process($data)
{
	unset($data['approval_setting_id']);


	if(isset($data['staff'])){
		$setting = [];
		foreach ($data['staff'] as $key => $value) {
			$node = [];
			$node['approver'] = 'specific_personnel';
			$node['staff'] = $data['staff'][$key];

			$setting[] = $node;
		}
		unset($data['approver']);
		unset($data['staff']);
	}



	if(!isset($data['choose_when_approving'])){
		$data['choose_when_approving'] = 0;
	}

	if(isset($data['departments'])){
		$data['departments'] = implode(',', $data['departments']);
	}

	if(isset($data['job_positions'])){
		$data['job_positions'] = implode(',', $data['job_positions']);
	}

	$data['setting'] = json_encode($setting);

	if(isset($data['notification_recipient'])){
		$data['notification_recipient'] = implode(",", $data['notification_recipient']);
	}

	$this->db->insert(db_prefix() .'fe_approval_setting', $data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		return true;
	}
	return false;
}
	/**
	 * update approval process
	 * @param  integer $id   
	 * @param  array $data 
	 * @return boolean       
	 */
	public function update_approval_process($id, $data)
	{
		if(isset($data['staff'])){
			$setting = [];
			foreach ($data['staff'] as $key => $value) {
				$node = [];
				$node['approver'] = 'specific_personnel';
				$node['staff'] = $data['staff'][$key];

				$setting[] = $node;
			}
			unset($data['approver']);
			unset($data['staff']);
		}

		if(!isset($data['choose_when_approving'])){
			$data['choose_when_approving'] = 0;
		}

		$data['setting'] = json_encode($setting);

		if(isset($data['departments'])){
			$data['departments'] = implode(',', $data['departments']);
		}else{
			$data['departments'] = '';
		}

		if(isset($data['job_positions'])){
			$data['job_positions'] = implode(',', $data['job_positions']);
		}else{
			$data['job_positions'] = '';
		}

		if(isset($data['notification_recipient'])){
			$data['notification_recipient'] = implode(",", $data['notification_recipient']);
		}

		$this->db->where('id', $id);
		$this->db->update(db_prefix() .'fe_approval_setting', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete approval setting
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_approve_setting($id)
	{
		if(is_numeric($id)){
			$this->db->where('id', $id);
			$this->db->delete(db_prefix() .'fe_approval_setting');
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		}
		return false;
	}

/**
 * get approval setting
 * @param  integer $id 
 * @return integer     
 */
public function get_approval_setting($id){
	if($id != ''){
		$this->db->where('id',$id);
		return $this->db->get(db_prefix().'fe_approval_setting')->row();
	}else {
		return $this->db->get(db_prefix().'fe_approval_setting')->result_array();
	}
}
/**
 * add new request
 * @param $data 
 */
public function add_new_request($data){
	$this->db->insert(db_prefix().'fe_checkin_assets', $data);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		return $insert_id;
	}
	return 0;
}
/**
 * change request status
 * @param  integer $id     
 * @param  integer $status 
 * @return integer         
 */
public function change_request_status($id, $status){
	if(is_numeric($id)){
		$this->db->where('id', $id);
		$this->db->update(db_prefix().'fe_checkin_assets', ['request_status' => $status]);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
	}
	return false;
}

/**
 * get approve setting
 * @param  string  $type         
 * @param  boolean $only_setting 
 * @return boolean                
 */
public function get_approve_setting($type, $only_setting = true){
	$this->db->select('*');
	$this->db->where('related', $type);
	$approval_setting = $this->db->get(db_prefix().'fe_approval_setting')->row();
	if($approval_setting){
		if($only_setting == false){
			return $approval_setting;
		}else{
			return json_decode($approval_setting->setting);
		}
	}else{
		return false;
	}
}


	/**
	 * send request approve
	 * @param  array $data     
	 * @param  integer $staff_id 
	 * @return bool           
	 */
	public function send_request_approve($rel_id, $rel_type, $staff_id = ''){
		$data_new = $this->get_approve_setting($rel_type, true);
		$data_setting = $this->get_approve_setting($rel_type, false);
		$this->delete_approval_details($rel_id, $rel_type);
		$date_send = date('Y-m-d H:i:s');
		foreach ($data_new as $value) {
			$row = [];
			$row['notification_recipient'] = $data_setting->notification_recipient;
			$row['approval_deadline'] = date('Y-m-d', strtotime(date('Y-m-d').' +'.$data_setting->number_day_approval.' day'));
			$row['staffid'] = $value->staff;
			$row['date_send'] = $date_send;
			$row['rel_id'] = $rel_id;
			$row['rel_type'] = $rel_type;
			$row['sender'] = $staff_id;
			$this->db->insert(db_prefix().'fe_approval_details', $row);
		}
		$this->send_notify_approve($rel_id, $rel_type);
		return true;
	}
	/**
	 * delete approval details
	 * @param  string $rel_id   
	 * @param  string $rel_type 
	 * @return boolean           
	*/
	public function delete_approval_details($rel_id, $rel_type)
	{
		$this->db->where('rel_id', $rel_id);
		$this->db->where('rel_type', $rel_type);
		$this->db->delete(db_prefix().'fe_approval_details');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/**
	 * get checkin out data
	 * @param  integer $id 
	 * @return integer     
	 */
	public function get_checkin_out_data($id){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_checkin_assets')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_checkin_assets')->result_array();
		}
	}
	/**
	 * get approval details
	 * @param  integer $rel_id   
	 * @param  string $rel_type 
	 * @return integer           
	 */
	public function get_approval_details($rel_id,$rel_type){
		if($rel_id != ''){
			$this->db->where('rel_id',$rel_id);
			$this->db->where('rel_type',$rel_type);
			$this->db->order_by('id');
			return $this->db->get(db_prefix().'fe_approval_details')->result_array();
		}else {
			return $this->db->get(db_prefix().'fe_approval_details')->result_array();
		}
	}
/**
 * change approve
 * @param  [type] $data 
 * @return [type]       
 */
public function change_approve($data){
	$this->db->where('rel_id', $data['rel_id']);
	$this->db->where('rel_type', $data['rel_type']);
	$this->db->where('staffid', $data['staffid']);
	$this->db->update(db_prefix() . 'fe_approval_details', $data);
	if ($this->db->affected_rows() > 0) {
		$this->send_notify_approve($data['rel_id'], $data['rel_type']);
		// If has rejected then change status to finish approve
		if($data['approve'] == 2)
		{
			$this->change_request_status($data['rel_id'], 2);
			return true;
		}
		$count_approve_total = $this->count_approve($data['rel_id'],$data['rel_type'])->count;
		$count_approve = $this->count_approve($data['rel_id'],$data['rel_type'],1)->count;
		$count_rejected = $this->count_approve($data['rel_id'],$data['rel_type'],2)->count;
		if(($count_approve + $count_rejected) == $count_approve_total){
			if($count_approve_total == $count_approve){
				$this->change_request_status($data['rel_id'], 1);
				$data_checkout_log = $this->fixed_equipment_model->get_checkin_out_data($data['rel_id']);
				if($data_checkout_log){
					$this->db->where('id', $data_checkout_log->item_id);
					$this->db->update(db_prefix().'fe_assets', ['checkin_out' => 2, 'checkin_out_id' => $data['rel_id']]);
					$this->add_log(get_staff_user_id(), $data['rel_type'], $data_checkout_log->item_id, '', '', 'user', $data_checkout_log->staff_id, $data_checkout_log->notes);
				}
			}
			else{
				$this->change_request_status($data['rel_id'], 2);
			}
		}
		return true;               
	}
	return false;
}
/**
 * send notify approve
 * @param  integer $id           
 * @param  string $request_type 
 * @return boolean               
 */
public function send_notify_approve($id,$request_type){
	$link = '';
	if($request_type == 'checkout'){
		$link = 'fixed_equipment/detail_request/'.$id;
	}
	elseif($request_type == 'audit'){
		$link = 'fixed_equipment/view_audit_request/'.$id;		
	}
	elseif($request_type == 'close_audit'){
		$link = 'fixed_equipment/audit/'.$id;		
	}
	$data_approve = $this->get_approval_details($id,$request_type);
	if($data_approve){
		foreach ($data_approve as $key => $approver) {
			if($approver['approve'] == '' || $approver['approve'] == null)
			{
				$string_sub = _l('fe_sent_you_an_approval_request').' '._l('fe_'.$request_type);
				$this->notifications($approver['staffid'], $link, strtolower($string_sub));
				return true;
			}
			elseif($approver['approve'] == 2){
				return true;
			}
		}
	}
	return false;
}
/**
 * notifications
 * @param  integer $id_staff    
 * @param  integer $link        
 * @param  integer 
 * @return integer              
 */
public function notifications($id_staff, $link, $description){

	$notifiedUsers = [];
	$id_userlogin = get_staff_user_id();
	$notified = add_notification([
		'fromuserid'      => $id_userlogin,
		'description'     => $description,
		'link'            => $link,
		'touserid'        => $id_staff,
		'additional_data' => serialize([
			$description,
		]),
	]);
	if ($notified) {
		array_push($notifiedUsers, $id_staff);
	}
	pusher_trigger_notification($notifiedUsers);
}
/**
 * count approve
 * @param integer $rel_id   
 * @param integer $rel_type 
 * @param  string $approve  
 * @return object        
 */
public function count_approve($rel_id, $rel_type, $approve = ''){
	if($approve == ''){
		return $this->db->query('SELECT count(distinct(staffid)) as count FROM '.db_prefix().'fe_approval_details where rel_id = '.$rel_id.' and rel_type = \''.$rel_type.'\'')->row();
	}
	else{
		return $this->db->query('SELECT count(distinct(staffid)) as count FROM '.db_prefix().'fe_approval_details where rel_id = '.$rel_id.' and rel_type = \''.$rel_type.'\' and approve = '.$approve.'')->row();
	}
}
/**
 * get_last_checkin_out_asset
 * @param  integer $item_id 
 * @return object          
 */
public function get_last_checkin_out_asset($item_id){
	return $this->db->query('select * from '.db_prefix().'fe_checkin_assets where item_id = '.$item_id.' and type="checkout" order by date_creator desc limit 0,1')->row();
}

	/**
	 * add approver choosee when approve
	 * @param  array $data     
	 * @param  integer $staff_id 
	 * @return bool           
	 */
	public function add_approver_choosee_when_approve($rel_id, $rel_type, $staff_id = ''){
		$data_new = $this->get_approve_setting($rel_type, true);
		$data_setting = $this->get_approve_setting($rel_type, false);
		$this->delete_approval_details($rel_id, $rel_type);
		$date_send = date('Y-m-d H:i:s');
		$row = [];
		$row['notification_recipient'] = $data_setting->notification_recipient;
		$row['approval_deadline'] = date('Y-m-d', strtotime(date('Y-m-d').' +'.$data_setting->number_day_approval.' day'));
		$row['staffid'] = $staff_id;
		$row['date_send'] = $date_send;
		$row['rel_id'] = $rel_id;
		$row['rel_type'] = $rel_type;
		$row['sender'] = $staff_id;
		$this->db->insert(db_prefix().'fe_approval_details', $row);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			$link = 'fixed_equipment/detail_request/'.$rel_id;
			$string_sub = _l('fe_sent_you_an_approval_request').' '._l($rel_type);
			$this->notifications($staff_id, $link, strtolower($string_sub));
			return $insert_id;
		}
		return 0;
	}
/**
 * get list checkout assets
 * @param  integer $asset id 
 * @return array object           
 */
public function get_list_checkout_assets($asset_id){
	return $this->db->query('select * from '.db_prefix().'fe_checkin_assets where checkout_to = "asset" and asset_id = '.$asset_id.' and (type="checkout" OR type="request") and check_status = 2')->result_array();
}

	/**
	 * add fieldset
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_fieldset($data){
		$this->db->insert(db_prefix().'fe_fieldsets', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update fieldset
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_fieldset($data){
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_fieldsets', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * add custom_field
	 * @param array $data 
	 * @return integer $insert id 
	 */
	public function add_custom_field($data){
		$data['option'] = is_array($data['option']) ? json_encode($data['option']) : null;
		if(!isset($data['required'])){
			$data['required'] = 0;
		}
		$this->db->insert(db_prefix().'fe_custom_fields', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return 0;
	}
	/**
	 * update custom_field
	 * @param  array $data 
	 * @return boolean     
	 */
	public function update_custom_field($data){
		$data['option'] = is_array($data['option']) ? json_encode($data['option']) : null;
		if(!isset($data['required'])){
			$data['required'] = 0;
		}
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix().'fe_custom_fields', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete custom_field
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_custom_field($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_custom_fields');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

		/**
	 * get custom_fields
	 * @param  integer $id 
	 * @return array or object    
	 */
		public function get_custom_fields($id = ''){
			if($id != ''){
				$this->db->where('id', $id);
				return $this->db->get(db_prefix().'fe_custom_fields')->row();
			}
			else{
				return $this->db->get(db_prefix().'fe_custom_fields')->result_array();
			}
		}
		/**
		 * get field set
		 * @param  integer $id 
		 * @return array or object    
		 */
		public function get_field_set($id = ''){
			if($id != ''){
				$this->db->where('id', $id);
				return $this->db->get(db_prefix().'fe_fieldsets')->row();
			}
			else{
				return $this->db->get(db_prefix().'fe_fieldsets')->result_array();
			}
		}

		/**
	 * get custom fields by field set
	 * @param  integer $id 
	 * @return array object    
	 */
		public function get_custom_field_by_fieldset($id = ''){
			$this->db->where('fieldset_id', $id);
			return $this->db->get(db_prefix().'fe_custom_fields')->result_array();
		}

	/**
	 * get custom fields value assets
	 * @param  integer $id 
	 * @return array object    
	 */
	public function get_custom_field_value_assets($asset_id = ''){
		$this->db->where('asset_id', $asset_id);
		return $this->db->get(db_prefix().'fe_custom_field_values')->result_array();
	}
/**
 * data query
 * @param  string  $query    
 * @param  boolean $multiple 
 * @return array object or array            
 */
public function data_query($query, $multiple = false){
	if($multiple){
		return $this->db->query($query)->result_array();
	}
	else{
		return $this->db->query($query)->row();			
	}
}
	/**
	 * create audit request
	 * @param  array $data 
	 * @return array       
	 */
	public function create_audit_request($data){
		$data_detail = [];
		if(isset($data['assets_detailt'])){
			$data_detail = json_decode($data['assets_detailt']);
			unset($data['assets_detailt']);
		}
		if(isset($data['asset_id'])){
			$data['asset_id'] = json_encode($data['asset_id']);
		}
		$data['audit_date'] = fe_format_date($data['audit_date']);
		$this->db->insert(db_prefix().'fe_audit_requests', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){

			foreach ($data_detail as $k => $row) {
				if($row[0] != null){
					$data_add['asset_id'] = $row[0];
					$data_add['asset_name'] = $row[1];
					$data_add['type'] = $row[2];
					$data_add['quantity'] = $row[3];
					$data_add['audit_id'] = $insert_id;
					$this->db->insert(db_prefix().'fe_audit_detail_requests', $data_add);
				}
			}
			return $insert_id;
		}
		return 0;
	}

/**
 * get audits
 * @param  integer $id 
 * @return array or object     
 */
public function get_audits($id){
	if($id != ''){
		$this->db->where('id', $id);
		return $this->db->get(db_prefix().'fe_audit_requests')->row();
	}
	else{
		return $this->db->get(db_prefix().'fe_audit_requests')->result_array();
	}
}

	/**
	 * get audit detail by master
	 * @param  integer $id 
	 * @return array     
	*/
	public function get_audit_detail_by_master($id){
		$this->db->where('audit_id', $id);
		return $this->db->get(db_prefix().'fe_audit_detail_requests')->result_array();
	}

/**
 * change approve audit
 * @param  array $data 
 * @return boolean       
 */
public function change_approve_audit($data){
	$this->db->where('rel_id', $data['rel_id']);
	$this->db->where('rel_type', $data['rel_type']);
	$this->db->where('staffid', $data['staffid']);
	$this->db->update(db_prefix() . 'fe_approval_details', $data);
	if ($this->db->affected_rows() > 0) {
		$this->send_notify_approve($data['rel_id'], $data['rel_type']);
		// If has rejected then change status to finish approve
		if($data['approve'] == 2)
		{
			$this->db->where('id', $data['rel_id']);
			$this->db->update(db_prefix().'fe_audit_requests', ['status' => 2]);
			return true;
		}
		$count_approve_total = $this->count_approve($data['rel_id'],$data['rel_type'])->count;
		$count_approve = $this->count_approve($data['rel_id'],$data['rel_type'],1)->count;
		$count_rejected = $this->count_approve($data['rel_id'],$data['rel_type'],2)->count;
		if(($count_approve + $count_rejected) == $count_approve_total){
			if($count_approve_total == $count_approve){
				$this->db->where('id', $data['rel_id']);
				$this->db->update(db_prefix().'fe_audit_requests', ['status' => 1]);
			}
			else{
				$this->db->where('id', $data['rel_id']);
				$this->db->update(db_prefix().'fe_audit_requests', ['status' => 2]);
			}
		}
		return true;               
	}
	return false;
}

/**
	 * add approver choosee when approve audit
	 * @param  array $data     
	 * @param  integer $staff_id 
	 * @return bool           
	 */
public function add_approver_choosee_when_approve_audit($rel_id, $rel_type, $staff_id = ''){
	$data_new = $this->get_approve_setting($rel_type, true);
	$data_setting = $this->get_approve_setting($rel_type, false);
	$this->delete_approval_details($rel_id, $rel_type);
	$date_send = date('Y-m-d H:i:s');
	$row = [];
	$row['notification_recipient'] = $data_setting->notification_recipient;
	$row['approval_deadline'] = date('Y-m-d', strtotime(date('Y-m-d').' +'.$data_setting->number_day_approval.' day'));
	$row['staffid'] = $staff_id;
	$row['date_send'] = $date_send;
	$row['rel_id'] = $rel_id;
	$row['rel_type'] = $rel_type;
	$row['sender'] = $staff_id;
	$this->db->insert(db_prefix().'fe_approval_details', $row);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		$link = 'fixed_equipment/view_audit_request/'.$rel_id;
		$string_sub = _l('fe_sent_you_an_approval_request').' '._l($rel_type);
		$this->notifications($staff_id, $link, strtolower($string_sub));
		return $insert_id;
	}
	return 0;
}

/**
	 * delete audit request
	 * @param  integer $id 
	 * @return boolean     
	 */
public function delete_audit_request($id){
	$this->db->where('id', $id);
	$this->db->delete(db_prefix().'fe_audit_requests');
	if($this->db->affected_rows() > 0) {
		$this->db->where('audit_id', $id);
		$this->db->delete(db_prefix().'fe_audit_detail_requests');
		return true;
	}
	return false;
}

	/**
	 * send request approve close audit
	 * @param  array $data     
	 * @param  integer $staff_id 
	 * @return bool           
	 */
	public function send_request_approve_close_audit($rel_id, $rel_type, $staff_id = ''){
		$request_type = 'audit';
		$data_new = $this->get_approve_setting($request_type, true);
		$data_setting = $this->get_approve_setting($request_type, false);
		$this->delete_approval_details($rel_id, $rel_type);
		$date_send = date('Y-m-d H:i:s');
		foreach ($data_new as $value) {
			$row = [];
			$row['notification_recipient'] = $data_setting->notification_recipient;
			$row['approval_deadline'] = date('Y-m-d', strtotime(date('Y-m-d').' +'.$data_setting->number_day_approval.' day'));
			$row['staffid'] = $value->staff;
			$row['date_send'] = $date_send;
			$row['rel_id'] = $rel_id;
			$row['rel_type'] = $rel_type;
			$row['sender'] = $staff_id;
			$this->db->insert(db_prefix().'fe_approval_details', $row);
		}
		
		// Send notify
		$this->send_notify_approve($rel_id, $rel_type);
		// End Send notify
		return true;
	}
	/**
	 * update audit request
	 * @param  array $data 
	 * @return boolean       
	 */
	public function update_audit_request($data){
		$id = $data['id'];
		$affectedRows = 0;
		$list_detail = json_decode($data['assets_detailt']);
		foreach ($list_detail as $key => $row) {
			if($row[0] != null){
				$adjusted = (isset($row[4]) && is_numeric($row[4])) ? $row[4] : null;
				$maintenance = (isset($row[5]) && is_numeric($row[5])) ? $row[5] : 0;
				$accept = (isset($row[6]) && is_numeric($row[6])) ? $row[6] : 0;
				$data_update['adjusted'] = $adjusted;
				$data_update['accept'] = $accept;
				$data_update['maintenance'] = $maintenance;
				$this->db->where('asset_id', $row[0]);
				$this->db->where('audit_id', $id);
				$this->db->update(db_prefix().'fe_audit_detail_requests', $data_update);
				$affectedRows++;
			}
		}
		if($affectedRows != 0){
			return true;
		}
		return false;
	}

/**
 * change approve close audit
 * @param  array $data 
 * @return boolean       
 */
public function change_approve_close_audit($data){
	$data_hanson = (isset($data['data_hanson']) ? json_decode($data['data_hanson']) : []);
	unset($data['data_hanson']);
	$this->db->where('rel_id', $data['rel_id']);
	$this->db->where('rel_type', $data['rel_type']);
	$this->db->where('staffid', $data['staffid']);
	$this->db->update(db_prefix() . 'fe_approval_details', $data);
	if ($this->db->affected_rows() > 0) {
		$this->update_asset_quantity_close_audit($data_hanson, $data['rel_id']);
		$this->send_notify_approve($data['rel_id'], $data['rel_type']);
		// If has rejected then change status to finish approve
		if($data['approve'] == 2)
		{
			$this->db->where('id', $data['rel_id']);
			$this->db->update(db_prefix().'fe_audit_requests', ['closed' => 2]);
			return true;
		}
		$count_approve_total = $this->count_approve($data['rel_id'],$data['rel_type'])->count;
		$count_approve = $this->count_approve($data['rel_id'],$data['rel_type'],1)->count;
		$count_rejected = $this->count_approve($data['rel_id'],$data['rel_type'],2)->count;
		if(($count_approve + $count_rejected) == $count_approve_total){
			if($count_approve_total == $count_approve){
				$this->db->where('id', $data['rel_id']);
				$this->db->update(db_prefix().'fe_audit_requests', ['closed' => 1]);
			}
			else{
				$this->db->where('id', $data['rel_id']);
				$this->db->update(db_prefix().'fe_audit_requests', ['closed' => 2]);
			}
		}
		return true;               
	}
	return false;
}
/**
 * update asset quantity close audit
 * @param  array $data_hanson 
 * @param  integer $rel_id      
 * @return boolean              
 */
public function update_asset_quantity_close_audit($data_hanson, $rel_id){
	foreach ($data_hanson as $row) {
		if(($row[6] != null && $row[6] != '') && ($row[4] != null && $row[4] != '')){
			switch (strtolower($row[2])) {
				case 'asset':
				if($row[6] == 1 && $row[4] == 0){
					// Deactive 
					$this->db->where('id', $row[0]);
					$this->db->update(db_prefix().'fe_assets', ['active' => 0]);
				}
				elseif($row[6] == 1 && $row[4] != 0){
					// Active
					$this->db->where('id', $row[0]);
					$this->db->update(db_prefix().'fe_assets', ['active' => 1]);	
				}
				break;
				case 'license':
				if(is_numeric($row[4])){
					if(($row[3] > $row[4]) && $row[6] == 1){
						$query = 'select count(1) as count from '.db_prefix().'fe_seats where license_id = '.$row[0];
						$count_avail = $this->data_query($query)->count;
						if($row[4] < $count_avail){
							$balance = $count_avail - $row[4];
							$this->db->query('delete from '.db_prefix().'fe_seats where license_id='.$row[0].' order by status asc limit '.$balance);
						}
						$this->db->where('id', $row[0]);
						$this->db->update(db_prefix().'fe_assets', ['seats' => $row[4]]);
					}					
				}					
				break;
				case 'accessory':
				if(is_numeric($row[4])){
					if(($row[3] > $row[4]) && $row[6] == 1){
						$query = 'select count(1) as count from '.db_prefix().'fe_checkin_assets where item_id = '.$row[0].' and status = 2';
						$count_avail = $this->data_query($query)->count;

						if($row[4] < $count_avail){
							$balance = $count_avail - $row[4];
							$this->db->query('delete from '.db_prefix().'fe_checkin_assets where item_id = '.$row[0].' and status = 2 order by id asc limit '.$balance);
						}
						$this->db->where('id', $row[0]);
						$this->db->update(db_prefix().'fe_assets', ['quantity' => $row[4]]);
					}
				}
				break;
				case 'consumable':
				if(is_numeric($row[4])){
					if(($row[3] > $row[4]) && $row[6] == 1){
						$query = 'select count(1) as count from '.db_prefix().'fe_checkin_assets where item_id = '.$row[0].' and status = 2';
						$count_avail = $this->data_query($query)->count;
						if($row[4] < $count_avail){
							$balance = $count_avail - $row[4];
							$this->db->query('delete from '.db_prefix().'fe_checkin_assets where item_id = '.$row[0].' and status = 2 order by id asc limit '.$balance);
						}
						$this->db->where('id', $row[0]);
						$this->db->update(db_prefix().'fe_assets', ['quantity' => $row[4]]);
					}
				}
				break;
				case 'component':
				if(is_numeric($row[4])){
					if(($row[3] > $row[4]) && $row[6] == 1){
						$query = 'select count(1) as count from '.db_prefix().'fe_checkin_assets where item_id = '.$row[0].' and status = 2';
						$count_avail = $this->data_query($query)->count;
						if($row[4] < $count_avail){
							$balance = $count_avail - $row[4];
							$this->db->query('delete from '.db_prefix().'fe_checkin_assets where item_id = '.$row[0].' and status = 2 order by id asc limit '.$balance);
						}
						$this->db->where('id', $row[0]);
						$this->db->update(db_prefix().'fe_assets', ['quantity' => $row[4]]);
					}
				}
				break;
			}
			// Update change to audit detail
			$this->db->where('asset_id', $row[0]);
			$this->db->where('audit_id', $rel_id);
			$this->db->update(db_prefix().'fe_audit_detail_requests', ['adjusted' => $row[4], 'accept' => $row[6]]);
		}
	}
	return true;
}

/**
	 * add approver choosee when close audit
	 * @param  array $data     
	 * @param  integer $staff_id 
	 * @return bool           
	 */
public function add_approver_choosee_when_close_audit($rel_id, $rel_type, $staff_id = ''){
	$request_type = 'audit';
	$data_new = $this->get_approve_setting($request_type, true);
	$data_setting = $this->get_approve_setting($request_type, false);
	$this->delete_approval_details($rel_id, $rel_type);
	$date_send = date('Y-m-d H:i:s');
	$row = [];
	$row['notification_recipient'] = $data_setting->notification_recipient;
	$row['approval_deadline'] = date('Y-m-d', strtotime(date('Y-m-d').' +'.$data_setting->number_day_approval.' day'));
	$row['staffid'] = $staff_id;
	$row['date_send'] = $date_send;
	$row['rel_id'] = $rel_id;
	$row['rel_type'] = $rel_type;
	$row['sender'] = $staff_id;
	$this->db->insert(db_prefix().'fe_approval_details', $row);
	$insert_id = $this->db->insert_id();
	if($insert_id){
		// Send notify
		$this->send_notify_approve($rel_id, $rel_type);
		// End Send notify
		return $insert_id;
	}
	return 0;
}

/**
 * count asset by_model
 * @param  integer $model_id 
 * @return integer           
 */
public function count_asset_by_model($model_id){
	$count = 0;
	$this->db->where('model_id', $model_id);
	$this->db->where('active', 1);
	$count_row = $this->db->get(db_prefix().'fe_assets')->num_rows();
	if(is_numeric($count_row)){
		$count = $count_row;
	}
	return $count;
}

/**
 * count custom field by field set
 * @param  integer $fieldset_id 
 * @return integer              
 */
public function count_custom_field_by_field_set($fieldset_id){
	$count = 0;
	$this->db->where('fieldset_id', $fieldset_id);
	$count_row = $this->db->get(db_prefix().'fe_custom_fields')->num_rows();
	if(is_numeric($count_row)){
		$count = $count_row;
	}
	return $count;
}

/**
 * get list model by fieldset
 * @param  integer $fieldset_id 
 * @return array              
 */
public function get_list_model_by_fieldset($fieldset_id){
	$this->db->where('fieldset_id', $fieldset_id);
	return $this->db->get(db_prefix().'fe_models')->result_array();
}

/**
 * from to date report
 * @return object 
 */
public function from_to_date_report(){
	$from_date = '';
	$to_date = '';
	$months_report = $this->input->post('months_report');
	if($months_report == 'this_month'){
		$from_date = date('Y-m-01');
		$to_date   = date('Y-m-t');
	}

	if($months_report == '1'){ 
		$from_date = date('Y-m-01', strtotime('first day of last month'));
		$to_date   = date('Y-m-t', strtotime('last day of last month'));              
	}


	if($months_report == 'this_year'){
		$from_date = date('Y-m-d', strtotime(date('Y-01-01')));
		$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
	}

	if($months_report == 'last_year'){
		$from_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
		$to_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));               
	}

	if($months_report == '3'){
		$months_report--;
		$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
		$to_date   = date('Y-m-t');
	}

	if($months_report == '6'){
		$months_report--;
		$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
		$to_date   = date('Y-m-t');
	}

	if($months_report == '12'){
		$months_report--;
		$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
		$to_date   = date('Y-m-t');

	}

	if($months_report == 'custom'){
		$from_date = fe_format_date($this->input->post('report_from'));
		$to_date   = fe_format_date($this->input->post('report_to'));                                      
	}

	$obj = new stdClass();
	$obj->from_date = $from_date;
	$obj->to_date = $to_date;
	return $obj;
}

/**
 * count total assets
 * @param  string $type 
 * @return integer       
 */
public function count_total_assets($type){
	$count = 0;
	$this->db->where('type', $type);
	$this->db->where('active', 1);
	$count_row = $this->db->get(db_prefix().'fe_assets')->num_rows();
	if(is_numeric($count_row)){
		$count = $count_row;
	}
	return $count;
}
/**
 * calculate depreciation
 * @param  string $cost               
 * @param  integer $month_depreciation 
 * @param  date $start_using_date   
 * @param  string $currency_name      
 * @return object                     
 */
public function calculate_depreciation($cost, $month_depreciation, $start_using_date){
	$obj = new stdClass();
	$cost_s = 0;
	$year_depreciation_s = 0;
	$monthly_depreciation_s = 0;

	// depreciation by year
	$year_depreciation_s = $cost / ($month_depreciation / 12);

	// depreciation by month
	$monthly_depreciation_s = $year_depreciation_s / 12;

	// Number day using by month
	$number_day_in_month = date('t', strtotime($start_using_date));
	$number_day_using_by_month = ($number_day_in_month - date('d', strtotime($start_using_date)))+1;

	// Depreciation of month
	$monthly_depreciation_s = ($monthly_depreciation_s / $number_day_in_month) * $number_day_using_by_month;

	$obj->cost = $cost_s;
	$obj->year_depreciation = $year_depreciation_s;
	$obj->monthly_depreciation = $monthly_depreciation_s;
	return $obj;
}

	/**
	 * get list month
	 * @param   $from_date 
	 * @param   $to_date             
	 */
	public function get_list_month($from_date, $to_date){
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
 * count asset by location
 * @param  integer $asset_location 
 * @return integer                 
 */
public function count_asset_by_location($location_id){
	$count = 0;
	$data = $this->db->query('select count(1) as count from '.db_prefix().'fe_assets where active=1 and type="asset" and asset_location = '.$location_id)->row();
	if($data){
		$count = $data->count;
	}
	return $count;
}
/**
 * count asset assign by location
 * @param  integer $location_id 
 * @return integer              
 */
public function count_asset_assign_by_location($location_id){
	$count = 0;
	$data = $this->db->query('SELECT count(1) as count FROM '.db_prefix().'fe_assets LEFT JOIN '.db_prefix().'fe_checkin_assets ON '.db_prefix().'fe_checkin_assets.id = '.db_prefix().'fe_assets.checkin_out_id where ('.db_prefix().'fe_checkin_assets.checkout_to = "location" OR '.db_prefix().'fe_checkin_assets.checkout_to = "asset") AND '.db_prefix().'fe_checkin_assets.location_id = '.$location_id.' and '.db_prefix().'fe_assets.checkin_out = 2 AND '.db_prefix().'fe_assets.location_id = '.$location_id.' AND  '.db_prefix().'fe_assets.active=1')->row();
	if($data){
		$count = $data->count;
	}
	return $count;
}

/**
 * count asset by manufacturer
 * @param  integer $location_id 
 * @return integer              
 */
public function count_asset_by_manufacturer($location_id){
	$count = 0;
	$data = $this->db->query('select count(1) as count from '.db_prefix().'fe_checkin_assets where type = "checkout" and location_id = '.$location_id.' and ((requestable = 0 and request_status = 0) OR (requestable = 1 and request_status = 1))')->row();
	if($data){
		$count = $data->count;
	}
	return $count;
}

/**
 * count total assets supplier
 * @param  string $type 
 * @return integer       
 */
public function count_total_asset_supplier($supplier_id, $type = ''){
	$count = 0;
	$this->db->where('supplier_id', $supplier_id);
	if($type != ''){
		$this->db->where('type', $type);
	}
	$this->db->where('active', 1);
	$count_row = $this->db->get(db_prefix().'fe_assets')->num_rows();
	if(is_numeric($count_row)){
		$count = $count_row;
	}
	return $count;
}

/**
 * count total assets manufacturer
 * @param  string $type 
 * @return integer       
 */
public function count_total_asset_manufacturer($manufacturer_id, $type = ''){
	$count = 0;
	$this->db->where('manufacturer_id', $manufacturer_id);
	if($type != ''){
		$this->db->where('type', $type);
	}
	$this->db->where('active', 1);
	$count_row = $this->db->get(db_prefix().'fe_assets')->num_rows();
	if(is_numeric($count_row)){
		$count = $count_row;
	}
	return $count;
}

/**
 * count asset by manufacturer
 * @param  integer $location_id 
 * @return integer              
 */
public function count_asset_by_manufacturer_only_asset_type($manufacturer_id){
	$count = 0;
	$data = $this->db->query('select count(1) as count from '.db_prefix().'fe_assets a LEFT JOIN '.db_prefix().'fe_models b ON b.id = a.model_id  where b.manufacturer = '.$manufacturer_id.' and a.type = "asset" and a.active = 1')->row();
	if($data){
		$count = $data->count;
	}
	return $count;
}

/**
 * count asset by category
 * @param  integer $cat_id 
 * @param  string $type   
 * @return integer
 */
public function count_asset_by_category($cat_id, $type){
	$count = 0;
	if($type == 'asset'){
		$data = $this->db->query('select count(1) as count from '.db_prefix().'fe_assets a LEFT JOIN '.db_prefix().'fe_models b ON b.id = a.model_id  where b.category = '.$cat_id.' and a.type = "asset" and a.active = 1')->row();
		if($data){
			$count = $data->count;
		}
	}
	else{
		$data = $this->db->query('select count(1) as count from '.db_prefix().'fe_assets  where category_id = '.$cat_id.' and type = "'.$type.'" and active = 1')->row();
		if($data){
			$count = $data->count;
		}
	}
	return $count;
}

/**
 * count asset by status
 * @param  integer $status id 
 * @return integer           
 */
public function count_asset_by_status($status_id){
	$count = 0;
	$this->db->where('status', $status_id);
	$this->db->where('active', 1);
	$count_row = $this->db->get(db_prefix().'fe_assets')->num_rows();
	if(is_numeric($count_row)){
		$count = $count_row;
	}
	return $count;
}

/**
 * get 2 audit info asset
 * @param  integer $asset_id 
 * @return object           
 */
public function get_2_audit_info_asset($asset_id){
	return $this->db->query('select * from '.db_prefix().'fe_audit_detail_requests a LEFT JOIN '.db_prefix().'fe_audit_requests b ON a.audit_id = b.id where a.asset_id = '.$asset_id.' and b.closed = 1 order by a.date_creator desc limit 2')->result_array();
}

/**
 * get cordinate
 * @return json 
 */
public function get_coordinate($address){
	$coordinate = fe_address2geo($address);
	return $coordinate;
}

	/**
	 * delete fieldset
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_fieldset($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix().'fe_fieldsets');
		if($this->db->affected_rows() > 0) {
			$this->db->where('fieldset_id', $id);
			$this->db->delete(db_prefix().'fe_custom_fields');
			return true;
		}
		return false;
	}

/**
	 * delete request
	 * @param  integer $id 
	 * @return boolean     
	 */
public function delete_request($id){
	$this->db->where('id', $id);
	$this->db->delete(db_prefix().'fe_checkin_assets');
	if($this->db->affected_rows() > 0) {
		return true;
	}
	return false;
}

/**
 * get staff assets
 * @param  integer $staffid 
 * @return array object          
 */
public function get_staff_assets($staffid){
	$staff_query = '';
	if($staffid != ''){
		$staff_query = ' and b.staff_id='.$staffid;
	}
	$query = 'select * from '.db_prefix().'fe_assets a LEFT JOIN '.db_prefix().'fe_checkin_assets b ON a.checkin_out_id = b.id where a.type="asset" and a.checkin_out=2 and a.active=1 and b.type="checkout" and b.checkout_to="user" and ((b.requestable = 0 and b.request_status = 0) or (b.requestable = 1 and b.request_status = 1))'.$staff_query;
	return $this->db->query($query)->result_array();
}

/**
 * get list asset id has depreciations
 * @return array 
 */
public function get_list_asset_id_has_depreciations(){
	$list_id = [];
	$data_asset = $this->db->query('select id, type, model_id, date_buy, unit_price, depreciation from '.db_prefix().'fe_assets where active=1 and (type="asset" OR type="license")')->result_array();
	foreach ($data_asset as $key => $row) {
		if($row['type'] == 'asset'){
			$data_model = $this->fixed_equipment_model->get_models($row['model_id']);
			if($data_model){
				$eol = _d(get_expired_date($row['date_buy'], $data_model->eol));
				if(is_numeric($data_model->depreciation) && $data_model->depreciation > 0){
					$data_depreciation = $this->fixed_equipment_model->get_depreciations($data_model->depreciation);
					if($data_depreciation && $row['unit_price'] != '' && $row['unit_price'] != 0 && $row['unit_price'] != null){
						$list_id[] = $row['id'];
					}
				}
			}
		}

		if($row['type'] == 'license'){
			if(is_numeric($row['depreciation']) && $row['depreciation'] > 0){
				$data_depreciation = $this->fixed_equipment_model->get_depreciations($row['depreciation']);
				if($data_depreciation && $row['unit_price'] != '' && $row['unit_price'] != 0 && $row['unit_price'] != null){
					$list_id[] = $row['id'];
				}
			}
		}
	}
	return $list_id;
}
/**
 * get list checked out predefined kit staff
 * @param  integer $staffid           
 * @param  integer $predefined_kit_id 
 * @return array                    
 */
public function get_list_checked_out_predefined_kit_staff($staffid, $predefined_kit_id){
	$this->db->where('type', 'checkout');
	$this->db->where('checkout_to', 'user');
	$this->db->where('check_status', 2);
	$this->db->where('staff_id', $staffid);
	$this->db->where('predefined_kit_id', $predefined_kit_id);
	return $this->db->get(db_prefix().'fe_checkin_assets')->result_array();
}

/**
 * count checkin asset by parents
 * @param  integer $parent_id 
 * @return integer            
*/
public function count_checkin_component_by_parents($parent_id){
	$sum = 0;
	$this->db->where('item_id', $parent_id);
	$this->db->where('status', 2);
	$data = $this->db->get(db_prefix().'fe_checkin_assets')->result_array();
	foreach ($data as $row) {
		$sum += $row['quantity'];
	}
	return $sum;
}
/**
 * update location for checkout to asset
 * @param  integer $asset_id    
 * @param  integer $location_id 
 * @return boolean              
 */
public function update_location_for_checkout_to_asset($asset_id, $location_id){
	$list_id_assigned = $this->db->query('select '.db_prefix().'fe_assets.id from '.db_prefix().'fe_assets 
		left join '.db_prefix().'fe_checkin_assets 
		on '.db_prefix().'fe_checkin_assets.id = '.db_prefix().'fe_assets.checkin_out_id 
		where '.db_prefix().'fe_assets.active = 1 
		and '.db_prefix().'fe_assets.type = "asset" 
		and '.db_prefix().'fe_checkin_assets.checkout_to = "asset" 
		and '.db_prefix().'fe_checkin_assets.asset_id = '.$asset_id)->result_array();
	$affectedRows = 0;
	foreach ($list_id_assigned as $row) {
		$this->db->where('id', $row['id']);
		$this->db->update(db_prefix().'fe_assets', ['location_id' => $location_id]);
		if($this->db->affected_rows() > 0) {
			$affectedRows++;
		}
	}
	if($affectedRows > 0) {
		return true;
	}
	return false;
}

/**
 * get current asset location
 * @param   $asset_id 
 */
public function get_current_asset_location($asset_id){
	$current_location = '';
	$checkout_to = '';
	$query = 'select * from '.db_prefix().'fe_log_assets where item_id = '.$asset_id.' and action="checkout" order by date_creator desc limit 0,1';
	$data_checkout = $this->db->query($query)->row();
	if($data_checkout){
		$to_id = $data_checkout->to_id;
		$to = $data_checkout->to;
		$checkout_to = $to;
		if($to_id != '' && $to != ''){
			switch ($to) {
				case 'user':
				$department_name = '';
				$data_staff_department = $this->departments_model->get_staff_departments($to_id);
				if($data_staff_department){
					foreach ($data_staff_department as $key => $staff_department) {
						$department_name .= $staff_department['name'].', ';
					}
					if($department_name != ''){
						$department_name = '('.rtrim($department_name,', ').') ';
					}
				}
				$current_location = $department_name.''.get_staff_full_name($to_id);
				break;
				case 'asset':
				$data_assets = $this->get_assets($to_id);
				if($data_assets){
					$current_location = '('.$data_assets->qr_code.') '.$data_assets->assets_name;
				}
				break;
				case 'location':
				$data_locations = $this->get_locations($to_id);
				if($data_locations){
					$current_location = $data_locations->location_name;
				}
				break;
			}
		}
	}
	$obj = new stdClass();
	$obj->current_location = $current_location;
	$obj->checkout_to = $checkout_to;
	return $obj;
}

/**
 * straight line depreciation method
 * @param  double $cost
 * @param  double $depreciation_value
 * @param  double $salvage_value
 * @param  date $purchase_date
 * @return object
 */
public function straight_line_depreciation_method($cost, $depreciation_value, $salvage_value, $purchase_date) {
	$obj = new stdClass();
	// Monthly Depreciation Value = (Cost – Salvage value) / Number of months
	$diff = 0;
	$monthly_depreciation = ($cost - $salvage_value) / $depreciation_value;
	$list_date = fe_get_list_month($purchase_date, date('Y-m-d'));
	if (is_array($list_date) && count($list_date) > 0) {
		foreach ($list_date as $date) {
			$diff += $monthly_depreciation;
		}
	}
	$obj->diff = $diff;
	$obj->current_depreciation = $monthly_depreciation;
	return $obj;
}

/**
 * total maintenance asset cost
 * @param  integer $asset_id 
 * @return decimal           
 */
public function total_maintenance_asset_cost($asset_id){
	$total = 0;
	$data = $this->db->query('select sum(cost) as cost from '.db_prefix().'fe_asset_maintenances where asset_id='.$asset_id)->row();
	if($data){
		$total = $data->cost;
	}
	return $total;
}

	/**
	 * delete permission
	 * @param  integer $id
	 * @return boolean
	 */
	public function delete_permission($id) {
		$str_permissions = '';
		foreach (fe_list_permisstion() as $per_key => $per_value) {
			if (strlen($str_permissions) > 0) {
				$str_permissions .= ",'" . $per_value . "'";
			} else {
				$str_permissions .= "'" . $per_value . "'";
			}
		}
		$sql_where = " feature IN (" . $str_permissions . ") ";
		$this->db->where('staff_id', $id);
		$this->db->where($sql_where);
		$this->db->delete(db_prefix() . 'staff_permissions');

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get check in out list
	 * @param string $list 
	 * @return array       
	 */
	public function get_check_in_out_list($list){
		$this->db->where('id IN ('.$list.')');
		return $this->db->get(db_prefix().'fe_checkin_assets')->result_array();
	}

	/**
	 * get staff check in out
	 * @param  integer $checkin_out_id 
	 * @return integer                 
	*/
	public function get_staff_check_in_out($checkin_out_id, $i = 0){
		$result = '';
		$data_checkin_out = $this->get_checkin_out_data($checkin_out_id);
		if($data_checkin_out && is_numeric($data_checkin_out->staff_id)){
			if($data_checkin_out->checkout_to == 'user'){
				$result = $data_checkin_out->staff_id;				
			}
			else if($data_checkin_out->checkout_to == 'asset'){
				$this->db->where('id', $data_checkin_out->asset_id);
				$this->db->where('checkin_out', 2);
				$data_asset = $this->db->get(db_prefix().'fe_assets')->row();
				if($data_asset){
					$result = $this->get_staff_check_in_out($data_asset->checkin_out_id, $i++);					
				}
			}
			else if($data_checkin_out->checkout_to == 'location'){
				$this->db->where('id', $data_checkin_out->location_id);
				$data_location = $this->db->get(db_prefix().'fe_locations')->row();
				if($data_location){
					$result = $data_location->manager;					
				}
			}
		}
		if(is_numeric($result)){
			return $result;
		}
		if($i == 100){
			return 0;
		}
	}

	/**
	 * get_sign_documents
	 * @return array or object 
	 */
	public function get_sign_document($id = '', $where = ''){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_sign_documents')->row();
		}
		else{
			if($where != ''){
				$this->db->where($where);
			}
			return $this->db->get(db_prefix().'fe_sign_documents')->result_array();
		}
	}

	/**
	 * get check in out not yet sign
	 * @return array 
	 */
	public function get_check_in_out_not_yet_sign($staff_id = ''){
		$query = 'select * from '.db_prefix() . 'fe_checkin_assets';
		$id_used = $this->db->query('select GROUP_CONCAT(checkin_out_id SEPARATOR \',\') as id FROM '.db_prefix() . 'fe_sign_documents')->row();
		if(isset($id_used->id) && $id_used->id != ''){
			$query = $query.' where NOT find_in_set(id, "'.$id_used->id.'")';			
			if(is_numeric($staff_id)){
				$query = $query.' and staff_id = '.$staff_id;
			}
		}
		else{
			if(is_numeric($staff_id)){
				$query = $query.' where staff_id = '.$staff_id;
			}
		}
		return $this->db->query($query)->result_array();
	}

	public function add_sign_document($data){
		$checkin_out_id = '';
		if(isset($data['check_in_out_id']) && $data['check_in_out_id'] != '' && isset($data['check_in_out_id'][0])){
			$checkin_out_id = implode(',', $data['check_in_out_id']);
			$data_insert['checkin_out_id'] = $checkin_out_id;
			$data_insert['check_to_staff'] = $this->get_staff_check_in_out($data['check_in_out_id'][0]);
			$this->db->insert(db_prefix().'fe_sign_documents', $data_insert);
			$insert_id = $this->db->insert_id();
			if($insert_id){
				$reference = str_pad($insert_id, 5, '0', STR_PAD_LEFT);
				$this->db->where('id', $insert_id);
				$this->db->update(db_prefix().'fe_sign_documents', ['reference' => $reference]);
				$staff_sign_id = [];
				$staff_sign_id[] = get_staff_user_id();
				$staff_sign_id[] = $data_insert['check_to_staff'];
				foreach ($staff_sign_id	 as $key => $value) {
					$data_signer['sign_document_id'] = $insert_id;
					$data_signer['staff_id'] = $value;
					$this->db->insert(db_prefix().'fe_signers', $data_signer);
				}
				return $insert_id;
			}
			return 0;
		}
	}

	/**
	 * get signer
	 * @return array or object 
	 */
	public function get_signer($id = ''){
		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix().'fe_signers')->row();
		}
		else{
			return $this->db->get(db_prefix().'fe_signers')->result_array();
		}
	}

	/**
	 * get signer by master
	 * @return array or object 
	 */
	public function get_signer_by_master($id = ''){
		$this->db->where('sign_document_id', $id);
		return $this->db->get(db_prefix().'fe_signers')->result_array();
	}

	/**
	 * get sign document check in out
	 * @return array 
	 */
	public function get_sign_document_check_in_out($check_in_out){
		return $this->db->query('SELECT * FROM '.db_prefix().'fe_sign_documents where find_in_set('.$check_in_out.', checkin_out_id)')->row();
	}

	/**
	 * change sign document status
	 * @param  integer $id     
	 * @param  integer $status 
	 * @return boolean         
	 */
	public function change_sign_document_status($id, $status){
		$this->db->where('id', $id);
		$this->db->update(db_prefix().'fe_sign_documents', ['status' => $status]);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * update signer info
	 * @param  integer $id   
	 * @param  array $data 
	 * @return boolean       
	 */
	public function update_signer_info($id, $data){
		$this->db->where('id', $id);
		$this->db->update(db_prefix().'fe_signers', $data);
		if($this->db->affected_rows() > 0) {
			$this->db->where('id', $id);
			$signer_data = $this->db->get(db_prefix().'fe_signers')->row();
			if($signer_data){
				$document_id = $signer_data->sign_document_id;
				$list_signer_data = $this->get_signer_by_master($document_id);
				$check = 0;
				foreach ($list_signer_data as $key => $value) {
					if($value['date_of_signing'] != null){
						$check++;
					}
				}
				if($check == 1){
					$this->change_sign_document_status($document_id, 2);
				}
				if($check == 2){
					$this->change_sign_document_status($document_id, 3);
				}
			}
			$data_signer = $this->get_signer_by_master();
			return true;
		}
		return false;
	}

	/**
	 * get assets by qrcode
	 * @param  string $qrcode 
	 * @return object    
	 */
	public function get_asset_by_qr_code($qr_code = ''){
		return $this->db->query('select * from '.db_prefix().'fe_assets where qr_code="'.$qr_code.'"')->row();
	}

	/**
    * data xlsx
    * @param  string $tmpFilePath 
    * @param  string $newFilePath 
    * @return string           
    */
    public function data_import_xlsx_item($tmpFilePath, $newFilePath, $type){
        $arr_insert = [];
        $error_filename = '';
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            $rows = [];
            $arr_insert = [];

            $tmpDir = TEMP_FOLDER . '/' . time() . uniqid() . '/';

            if (!file_exists(TEMP_FOLDER)) {
                mkdir(TEMP_FOLDER, 0755);
            }

            if (!file_exists($tmpDir)) {
                mkdir($tmpDir, 0755);
            }

            // Setup our new file path
            $newFilePath = $tmpDir . $newFilePath;

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                //Reader file
                $xlsx = new XLSXReader_fin($newFilePath);
                $sheetNames = $xlsx->getSheetNames();
                $data = $xlsx->getSheetData($sheetNames[1]);

                //Writer file
                $width = [];
            	$writer_header = [];
                foreach ($data[0] as $key => $value) {
                	$writer_header[$value] = 'string';
                	$width[] = 50;
                }
                $width[] = 100;
                $writer_header[''] = 'string';
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $writer_header, $col_options = ['widths' => $width]);

                $total_row_success = 0;
                $total_row_error = 0;
                $total_rows = 0;

                for ($row = 1; $row < count($data); $row++) {
                    $total_rows++;
                    $check_result = $this->check_xlsx_row_item($data[$row], $type);
                    if($check_result->not_error == true){
                    	array_push($arr_insert, $check_result->data);
                    	$res = $this->add_item_to_db($check_result->data, $type);
                    	if($res){
                    		$total_row_success++;
                    	}
                    }
                    else{
                    	 // write error file
                    	 $arr_error = [];
                    	 foreach ($check_result->data as $key => $value) {
                    	 	if($key != 'type'){
                    	 		if($key == 'serial'){
                    	 			$arr_error[] = ((isset($value[0])) ? $value[0] : '');                    	 	                   	 		
                    	 		}
                    	 		else{
                    	 			$arr_error[] = $value;                    	 	                    	 		
                    	 		}
                    	 	}
                    	 }
                    	 $arr_error[] = $check_result->string_error;                    	 	
                         $writer->writeSheetRow('Sheet1', $arr_error);
                    	 $total_row_error++;
                    }
                }
                if ($total_row_error != 0) {
                    $error_filename = 'import_item_error_' . get_staff_user_id() . '_' . strtotime(date('Y-m-d H:i:s')) . '.xlsx';
                    $writer->writeToFile(str_replace($error_filename, FIXED_EQUIPMENT_IMPORT_ITEM_ERROR . $error_filename, $error_filename));
                }
            }
            if (file_exists($newFilePath)) {
                @unlink($newFilePath);
            }
        }

        $out_data = new stdClass();
        $out_data->total_row_success = $total_row_success;
        $out_data->total_row_error = $total_row_error;
        $out_data->total_rows = $total_rows;
        $out_data->arr_insert = $arr_insert;
        $out_data->error_filename = $error_filename;
        return $out_data;
    }

    /**
     * check xlsx row item
     * @param   $data 
     * @param   $type 
     * @return        
     */
    public function check_xlsx_row_item($data, $type){
    	switch ($type) {
    		case 'asset':
    		return $this->check_xlsx_asset($data);
    		break;
    		case 'license':
    		return $this->check_xlsx_license($data);
    		break;
    		case 'accessory':
    		return $this->check_xlsx_accessory($data);
    		break;
    		case 'consumable':
    		return $this->check_xlsx_consumable($data);
    		break;
    		case 'component':
    		return $this->check_xlsx_component($data);
    		break;
    	}
    	return false;
    }
    /**
     * check xlsx asset
     * @param  array $data 
     * @return object       
     */
    public function check_xlsx_asset($data){
    	$obj = new stdClass();
    	$not_error = true;
    	$string_error = '';

    	$data_insert["assets_name"] = ((isset($data[0]) && $data[0] != null) ? $data[0] : '');
    	$serial = (isset($data[1]) ? $data[1] : '');
    	$data_insert["serial"] = [];
    	$data_insert["model_id"] = (isset($data[2]) ? $data[2] : '');
    	$data_insert["status"] = (isset($data[3]) ? $data[3] : '');
    	$data_insert["supplier_id"] = (isset($data[4]) ? $data[4] : '');
    	$data_insert["asset_location"] = (isset($data[5]) ? $data[5] : '');
    	$data_insert["date_buy"] = (isset($data[6]) ? $data[6] : '');
    	$data_insert["unit_price"] = (isset($data[7]) ? $data[7] : '');
    	$data_insert["order_number"] = (isset($data[8]) ? $data[8] : '');
    	$data_insert["warranty_period"] = (isset($data[9]) ? $data[9] : '');
    	$data_insert['requestable'] = (isset($data[10]) ? $data[10] : '');
    	$data_insert["description"] = (isset($data[11]) ? $data[11] : '');

         //Required:
         //Asset tag
         //Model
         //Status
    	if($serial == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_asset_tag') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		$data_insert["serial"][] = $serial;    		
    	}


    	if($data_insert["model_id"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_model_id') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(is_numeric($data_insert["model_id"])){
    			$check_model = $this->get_models($data_insert["model_id"]);
    			if(!$check_model){
    				$not_error = false;
    				$string_error .= ', '._l('fe_model_id') .' '. _l('fe_invalid');
    			}
    			else{
    				if($data_insert["assets_name"] == ''){
    					$data_insert["assets_name"] = $check_model->model_name;
    				}
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_model_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["status"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_status_id') .' '. _l('fe_not_yet_entered');
    	}
        else{
    		if(is_numeric($data_insert["status"])){
    			$check = $this->get_status_labels($data_insert["status"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_status_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_status_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	// Check supplier ID, Location ID, Warranty, Purchase cost
    	if($data_insert["supplier_id"] != ''){
    		if(is_numeric($data_insert["supplier_id"])){
    			$check = $this->get_suppliers($data_insert["supplier_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_supplier_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_supplier_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["asset_location"] != ''){
    		if(is_numeric($data_insert["asset_location"])){
    			$check = $this->get_locations($data_insert["asset_location"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_location_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_location_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["warranty_period"] != ''){
    		if(!is_numeric($data_insert["warranty_period"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_warranty') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["requestable"] != ''){
    		if(!is_numeric($data_insert["requestable"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_requestable') .' '. _l('fe_must_be_number');
    		}
    		else{
    			if(!in_array($data_insert["requestable"], [0,1])){
    				$not_error = false;
    				$string_error .= ', '._l('fe_requestable').' '. strtolower(_l('fe_value')) .' '. _l('fe_invalid');
    			}
    		}
    	}

    	if($data_insert["unit_price"] != ''){
    		if(!is_numeric($data_insert["unit_price"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_purchase_cost') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["date_buy"] != ''){
    		$reg_day = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/'; /*mm/dd/YYYY*/
    		if (preg_match($reg_day, $data_insert["date_buy"], $match) != 1) {
    			$string_error .= ', '._l('fe_purchase_date') .' '. _l('invalid');
    			$not_error = false;
    		}
    		else{
    			$data_insert["date_buy"] = date('Y-m-d', strtotime($data_insert["date_buy"]));
    		}
    	}


        $obj->data = $data_insert;
        $obj->not_error = $not_error;
        $obj->string_error = ltrim($string_error, ', ');
    	return $obj;
    }
    /**
     * check xlsx license
     * @param  array $data 
     * @return object       
     */
    public function check_xlsx_license($data){
    	$obj = new stdClass();
    	$not_error = true;
    	$string_error = '';


    	$data_insert["type"] = 'license'; 
    	$data_insert["assets_name"] = ((isset($data[0]) && $data[0] != null) ? $data[0] : ''); 
    	$data_insert["product_key"] = ((isset($data[1]) && $data[1] != null) ? $data[1] : ''); 
    	$data_insert["seats"] = ((isset($data[2]) && $data[2] != null) ? $data[2] : ''); 
    	$data_insert["licensed_to_name"] = ((isset($data[3]) && $data[3] != null) ? $data[3] : ''); 
    	$data_insert["licensed_to_email"] = ((isset($data[4]) && $data[4] != null) ? $data[4] : ''); 
    	$data_insert["reassignable"] = ((isset($data[5]) && $data[5] != null) ? $data[5] : ''); 
    	$data_insert["maintained"] = ((isset($data[6]) && $data[6] != null) ? $data[6] : ''); 
    	$data_insert["order_number"] = ((isset($data[7]) && $data[7] != null) ? $data[7] : ''); 
    	$data_insert["purchase_order_number"] = ((isset($data[8]) && $data[8] != null) ? $data[8] : ''); 
    	$data_insert["unit_price"] = ((isset($data[9]) && $data[9] != null) ? $data[9] : ''); 
    	$data_insert["date_buy"] = ((isset($data[10]) && $data[10] != null) ? $data[10] : ''); 
    	$data_insert["expiration_date"] = ((isset($data[11]) && $data[11] != null) ? $data[11] : ''); 
    	$data_insert["termination_date"] = ((isset($data[12]) && $data[12] != null) ? $data[12] : ''); 
    	$data_insert["category_id"] = ((isset($data[13]) && $data[13] != null) ? $data[13] : ''); 
    	$data_insert["manufacturer_id"] = ((isset($data[14]) && $data[14] != null) ? $data[14] : ''); 
    	$data_insert["supplier_id"] = ((isset($data[15]) && $data[15] != null) ? $data[15] : ''); 
    	$data_insert["depreciation"] = ((isset($data[16]) && $data[16] != null) ? $data[16] : ''); 
    	$data_insert["description"] = ((isset($data[17]) && $data[17] != null) ? $data[17] : ''); 

         //Required:
         //Software name
         //Category ID
         //Seats
         //Manufacturer
    	if($data_insert["assets_name"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_software_name') .' '. _l('fe_not_yet_entered');
    	}

    	if($data_insert["seats"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_seats') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(!is_numeric($data_insert["seats"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_seats') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["reassignable"] != ''){
    		if(!is_numeric($data_insert["reassignable"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_reassignable') .' '. _l('fe_must_be_number');
    		}
    		else{
    			if(!in_array($data_insert["reassignable"], [0,1])){
    				$not_error = false;
    				$string_error .= ', '._l('fe_reassignable').' '. strtolower(_l('fe_value')) .' '. _l('fe_invalid');
    			}
    		}
    	}

    	if($data_insert["maintained"] != ''){
    		if(!is_numeric($data_insert["maintained"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_reassignable') .' '. _l('fe_must_be_number');
    		}
    		else{
    			if(!in_array($data_insert["maintained"], [0,1])){
    				$not_error = false;
    				$string_error .= ', '._l('fe_reassignable').' '. strtolower(_l('fe_value')) .' '. _l('fe_invalid');
    			}
    		}
    	}

    	if($data_insert["unit_price"] != ''){
    		if(!is_numeric($data_insert["unit_price"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_purchase_cost') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["date_buy"] != ''){
    		$reg_day = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/'; /*mm/dd/YYYY*/
    		if (preg_match($reg_day, $data_insert["date_buy"], $match) != 1) {
    			$string_error .= ', '._l('fe_purchase_date') .' '. _l('invalid');
    			$not_error = false;
    		}
    		else{
    			$data_insert["date_buy"] = date('Y-m-d', strtotime($data_insert["date_buy"]));
    		}
    	}

    	if($data_insert["expiration_date"] != ''){
    		$reg_day = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/'; /*mm/dd/YYYY*/
    		if (preg_match($reg_day, $data_insert["expiration_date"], $match) != 1) {
    			$string_error .= ', '._l('fe_expiration_date') .' '. _l('invalid');
    			$not_error = false;
    		}
    		else{
    			$data_insert["expiration_date"] = date('Y-m-d', strtotime($data_insert["expiration_date"]));
    		}
    	}
    	if($data_insert["termination_date"] != ''){
    		$reg_day = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/'; /*mm/dd/YYYY*/
    		if (preg_match($reg_day, $data_insert["termination_date"], $match) != 1) {
    			$string_error .= ', '._l('fe_termination_date') .' '. _l('invalid');
    			$not_error = false;
    		}
    		else{
    			$data_insert["termination_date"] = date('Y-m-d', strtotime($data_insert["termination_date"]));
    		}
    	}

    	if($data_insert["category_id"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_category_id') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(is_numeric($data_insert["category_id"])){
    			$check = $this->get_categories($data_insert["category_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_category_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_category_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["manufacturer_id"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(is_numeric($data_insert["manufacturer_id"])){
    			$check = $this->get_asset_manufacturers($data_insert["manufacturer_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["supplier_id"] != ''){
    		if(!is_numeric($data_insert["supplier_id"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_supplier_id') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["depreciation"] != ''){
    		if(!is_numeric($data_insert["depreciation"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_depreciation_id') .' '. _l('fe_must_be_number');
    		}
    	}

        $obj->data = $data_insert;
        $obj->not_error = $not_error;
        $obj->string_error = ltrim($string_error, ', ');
    	return $obj;
    }
    /**
     * add item to database
     * @param array $data 
     * @param string $type 
     */
    public function add_item_to_db($data, $type){
    	switch ($type) {
    		case 'asset':
    		$res = $this->add_asset($data);
    		if(count($res) > 0){
    			return true;
    		}
    		break;
    		case 'license':
    		$res = $this->add_licenses($data);
    		if (is_numeric($res) && $res > 0) {
    			return true;
    		}
    		break;
    		case 'accessory':
    		$res = $this->add_accessories($data);
    		if (is_numeric($res) && $res > 0) {
    			return true;
    		}
    		break;
    		case 'consumable':
    		$res = $this->add_consumables($data);
    		if (is_numeric($res) && $res > 0) {
    			return true;
    		}    		break;
    		case 'component':
    		$res = $this->add_components($data);
    		if (is_numeric($res) && $res > 0) {
    			return true;
    		}    		break;
    	}
    	return false;
    }
    /**
     * check xlsx accessory
     * @param  array $data 
     * @return object       
     */
    public function check_xlsx_accessory($data){
    	$obj = new stdClass();
    	$not_error = true;
    	$string_error = '';
    	$data_insert["type"] = 'accessory';
    	$data_insert["assets_name"] = ((isset($data[0]) && $data[0] != null) ? $data[0] : ''); 
    	$data_insert["model_no"] = ((isset($data[1]) && $data[1] != null) ? $data[1] : ''); 
    	$data_insert["order_number"] = ((isset($data[2]) && $data[2] != null) ? $data[2] : ''); 
    	$data_insert["unit_price"] = ((isset($data[3]) && $data[3] != null) ? $data[3] : ''); 
    	$data_insert["date_buy"] = ((isset($data[4]) && $data[4] != null) ? $data[4] : ''); 
    	$data_insert["quantity"] = ((isset($data[5]) && $data[5] != null) ? $data[5] : ''); 
    	$data_insert["min_quantity"] = ((isset($data[6]) && $data[6] != null) ? $data[6] : ''); 
    	$data_insert["category_id"] = ((isset($data[7]) && $data[7] != null) ? $data[7] : ''); 
    	$data_insert["supplier_id"] = ((isset($data[8]) && $data[8] != null) ? $data[8] : ''); 
    	$data_insert["manufacturer_id"] = ((isset($data[9]) && $data[9] != null) ? $data[9] : ''); 
    	$data_insert["asset_location"] = ((isset($data[10]) && $data[10] != null) ? $data[10] : ''); 

		//Required:
		//Accessory name
		//Category ID
		//Quantity
		if($data_insert["assets_name"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_accessory_name') .' '. _l('fe_not_yet_entered');
    	}
    	if($data_insert["category_id"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_category_id') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(is_numeric($data_insert["category_id"])){
    			$check = $this->get_categories($data_insert["category_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_category_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_category_id') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["quantity"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_quantity') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(!is_numeric($data_insert["quantity"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_quantity') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["unit_price"] != ''){
    		if(!is_numeric($data_insert["unit_price"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_purchase_cost') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["date_buy"] != ''){
    		$reg_day = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/'; /*mm/dd/YYYY*/
    		if (preg_match($reg_day, $data_insert["date_buy"], $match) != 1) {
    			$string_error .= ', '._l('fe_purchase_date') .' '. _l('invalid');
    			$not_error = false;
    		}
    		else{
    			$data_insert["date_buy"] = date('Y-m-d', strtotime($data_insert["date_buy"]));
    		}
    	}
    	if($data_insert["min_quantity"] != ''){
    		if(!is_numeric($data_insert["min_quantity"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_min_quantity') .' '. _l('fe_must_be_number');
    		}
    	}
		// Check supplier ID, Location ID, Warranty, Purchase cost
    	if($data_insert["supplier_id"] != ''){
    		if(is_numeric($data_insert["supplier_id"])){
    			$check = $this->get_suppliers($data_insert["supplier_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_supplier_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_supplier_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["asset_location"] != ''){
    		if(is_numeric($data_insert["asset_location"])){
    			$check = $this->get_locations($data_insert["asset_location"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_location_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_location_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["manufacturer_id"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(is_numeric($data_insert["manufacturer_id"])){
    			$check = $this->get_asset_manufacturers($data_insert["manufacturer_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_must_be_number');
    		}
    	}

    	$obj->data = $data_insert;
    	$obj->not_error = $not_error;
    	$obj->string_error = ltrim($string_error, ', ');
    	return $obj;
    }
    /**
     * check xlsx consumable
     * @param  array $data 
     * @return object       
     */
    public function check_xlsx_consumable($data){
    	$obj = new stdClass();
    	$not_error = true;
    	$string_error = '';
    	$data_insert["type"] = 'consumable';
    	$data_insert["assets_name"] = ((isset($data[0]) && $data[0] != null) ? $data[0] : ''); 
    	$data_insert["model_no"] = ((isset($data[1]) && $data[1] != null) ? $data[1] : ''); 
    	$data_insert["item_no"] = ((isset($data[2]) && $data[2] != null) ? $data[2] : ''); 
    	$data_insert["order_number"] = ((isset($data[3]) && $data[3] != null) ? $data[3] : ''); 
    	$data_insert["unit_price"] = ((isset($data[4]) && $data[4] != null) ? $data[4] : ''); 
    	$data_insert["date_buy"] = ((isset($data[5]) && $data[5] != null) ? $data[5] : ''); 
    	$data_insert["quantity"] = ((isset($data[6]) && $data[6] != null) ? $data[6] : ''); 
    	$data_insert["min_quantity"] = ((isset($data[7]) && $data[7] != null) ? $data[7] : ''); 
    	$data_insert["category_id"] = ((isset($data[8]) && $data[8] != null) ? $data[8] : ''); 
    	$data_insert["manufacturer_id"] = ((isset($data[9]) && $data[9] != null) ? $data[9] : ''); 
    	$data_insert["asset_location"] = ((isset($data[10]) && $data[10] != null) ? $data[10] : ''); 

		//Required:
		//Consumables name
		//Category ID
		//Quantity
		
		if($data_insert["assets_name"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_accessory_name') .' '. _l('fe_not_yet_entered');
    	}
    	if($data_insert["category_id"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_category_id') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(is_numeric($data_insert["category_id"])){
    			$check = $this->get_categories($data_insert["category_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_category_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_category_id') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["quantity"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_quantity') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(!is_numeric($data_insert["quantity"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_quantity') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["manufacturer_id"] != ''){
    		if(is_numeric($data_insert["manufacturer_id"])){
    			$check = $this->get_asset_manufacturers($data_insert["manufacturer_id"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_manufacturer_id') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["asset_location"] != ''){
    		if(is_numeric($data_insert["asset_location"])){
    			$check = $this->get_locations($data_insert["asset_location"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_location_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_location_id') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["unit_price"] != ''){
    		if(!is_numeric($data_insert["unit_price"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_purchase_cost') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["date_buy"] != ''){
    		$reg_day = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/'; /*mm/dd/YYYY*/
    		if (preg_match($reg_day, $data_insert["date_buy"], $match) != 1) {
    			$string_error .= ', '._l('fe_purchase_date') .' '. _l('invalid');
    			$not_error = false;
    		}
    		else{
    			$data_insert["date_buy"] = date('Y-m-d', strtotime($data_insert["date_buy"]));
    		}
    	}

    	if($data_insert["min_quantity"] != ''){
    		if(!is_numeric($data_insert["min_quantity"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_min_quantity') .' '. _l('fe_must_be_number');
    		}
    	}

    	$obj->data = $data_insert;
    	$obj->not_error = $not_error;
    	$obj->string_error = ltrim($string_error, ', ');
    	return $obj;
    }
     /**
     * check xlsx component
     * @param  array $data 
     * @return object       
     */
    public function check_xlsx_component($data){
    	$obj = new stdClass();
    	$not_error = true;
    	$string_error = '';
    	$data_insert["type"] = 'component';
    	$data_insert["assets_name"] = ((isset($data[0]) && $data[0] != null) ? $data[0] : ''); 
    	$data_insert["quantity"] = ((isset($data[1]) && $data[1] != null) ? $data[1] : ''); 
    	$data_insert["min_quantity"] = ((isset($data[2]) && $data[2] != null) ? $data[2] : ''); 
    	$data_insert["series"] = ((isset($data[3]) && $data[3] != null) ? $data[3] : ''); 
    	$data_insert["order_number"] = ((isset($data[4]) && $data[4] != null) ? $data[4] : ''); 
    	$data_insert["unit_price"] = ((isset($data[5]) && $data[5] != null) ? $data[5] : ''); 
    	$data_insert["date_buy"] = ((isset($data[6]) && $data[6] != null) ? $data[6] : ''); 
    	$data_insert["category_id"] = ((isset($data[7]) && $data[7] != null) ? $data[7] : ''); 
    	$data_insert["asset_location"] = ((isset($data[8]) && $data[8] != null) ? $data[8] : ''); 
		//Required:
		//Component name
		//Quantity
		if($data_insert["assets_name"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_component_name') .' '. _l('fe_not_yet_entered');
    	}
    	if($data_insert["quantity"] == ''){
    		$not_error = false;
    		$string_error .= ', '._l('fe_quantity') .' '. _l('fe_not_yet_entered');
    	}
    	else{
    		if(!is_numeric($data_insert["quantity"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_quantity') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["min_quantity"] != ''){
    		if(!is_numeric($data_insert["min_quantity"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_min_quantity') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["asset_location"] != ''){
    		if(is_numeric($data_insert["asset_location"])){
    			$check = $this->get_locations($data_insert["asset_location"]);
    			if(!$check){
    				$not_error = false;
    				$string_error .= ', '._l('fe_location_id') .' '. _l('fe_invalid');
    			}
    		}
    		else{
    			$not_error = false;
    			$string_error .= ', '._l('fe_location_id') .' '. _l('fe_must_be_number');
    		}
    	}
    	if($data_insert["unit_price"] != ''){
    		if(!is_numeric($data_insert["unit_price"])){
    			$not_error = false;
    			$string_error .= ', '._l('fe_purchase_cost') .' '. _l('fe_must_be_number');
    		}
    	}

    	if($data_insert["date_buy"] != ''){
    		$reg_day = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/'; /*mm/dd/YYYY*/
    		if (preg_match($reg_day, $data_insert["date_buy"], $match) != 1) {
    			$string_error .= ', '._l('fe_purchase_date') .' '. _l('invalid');
    			$not_error = false;
    		}
    		else{
    			$data_insert["date_buy"] = date('Y-m-d', strtotime($data_insert["date_buy"]));
    		}
    	}
    	$obj->data = $data_insert;
    	$obj->not_error = $not_error;
    	$obj->string_error = ltrim($string_error, ', ');
    	return $obj;
    }

    /**
    * update audit detail item
    * @param  integer $asset_id 
    * @param  integer $audit_id 
    * @param  array $data     
    */
    public function update_audit_detail_item($asset_id, $audit_id, $data){
		$this->db->where('asset_id', $asset_id);
		$this->db->where('audit_id', $audit_id);
		$this->db->update(db_prefix().'fe_audit_detail_requests', $data);
    }

}
