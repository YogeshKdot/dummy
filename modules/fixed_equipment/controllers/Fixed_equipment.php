<?php 
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * fixed_equipment
 */
class fixed_equipment extends AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('fixed_equipment_model');
		$this->load->model('departments_model');
		hooks()->do_action('fixed_equipment_init');
	}

	/* index */
	public function index()
	{
		if (!has_permission('fixed_equipment_dashboard', '', 'view')) {
			access_denied('fixed_equipment');
		}
		$data['title']                 = _l('fe_fixed_equipment');
		$this->load->view('dashboard', $data);
	}
	
	/**
	 * settings
	 * @return view 
	 */
	public function settings(){
		if (!is_admin()) {          
			access_denied('fe_fixed_equipment');
		}
		$data['title']                 = _l('fe_fixed_equipment');
		$data['tab'] = $this->input->get('tab');
		if($data['tab'] == 'suppliers'){
			$this->load->model('staff_model');
			$data['locations'] = $this->fixed_equipment_model->get_locations();
			$data['staffs'] = $this->staff_model->get();
			$this->load->model('currencies_model');
			$data['currencies'] = $this->currencies_model->get();
			$data['base_currency'] = $this->currencies_model->get_base_currency();
		}
		if($data['tab'] == 'models'){
			$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();
			$data['categories'] = $this->fixed_equipment_model->get_categories('','asset');
			$data['depreciations'] = $this->fixed_equipment_model->get_depreciations();
			$data['custom_field_lists'] = get_custom_fields('fixed_equipment');
			$data['field_sets'] = $this->fixed_equipment_model->get_field_set();
		}
		if($data['tab'] == 'approval_settings'){
			$this->load->model('staff_model');
			$data['staffs'] = $this->staff_model->get();
		}
		$this->load->view('manage_setting', $data);
	}

	/**
	 * depreciations table
	 * @return json 
	 */
	public function depreciations_table(){
		if ($this->input->is_ajax_request()) {
			if($this->input->post()){
				$select = [
					'id',
					'name',         
					'term'
				];
				$where        = [];
				$aColumns     = $select;
				$sIndexColumn = 'id';
				$sTable       = db_prefix() . 'fe_depreciations';
				$join         = [];

				$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
					'id',
					'name',
					'term'
				]);


				$output  = $result['output'];
				$rResult = $result['rResult'];
				foreach ($rResult as $aRow) {
					$row = [];
					$row[] = $aRow['id'];  
					$_data = '';
					$_data .= '<div class="row-options">';
					$_data .= '<a href="javascript:void(0)" data-id="' . $aRow['id'] . '" data-name="' . $aRow['name'] . '" data-term="' . $aRow['term'] . '" onclick="edit(this); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_depreciations/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
					$_data .= '</div>'; 

					$row[] = $aRow['name'].$_data;   
					$row[] = $aRow['term'].' '._l('fe_months');

					$output['aaData'][] = $row;                                      
				}

				echo json_encode($output);
				die();
			}
		}
	}
	/**
	 * add depreciations
	 */
	public function add_depreciations(){
		if($this->input->post()){
			$data = $this->input->post();
			if($data['id'] == ''){
				unset($data['id']);
				$result =  $this->fixed_equipment_model->add_depreciations($data);
				if(is_numeric($result)){
					set_alert('success', _l('fe_added_successfully', _l('fe_depreciations')));
				}
				else{
					set_alert('danger', _l('fe_added_fail', _l('fe_depreciations')));					
				}
			}
			else{
				$result =  $this->fixed_equipment_model->update_depreciations($data);
				if($result){
					set_alert('success', _l('fe_updated_successfully', _l('fe_depreciations')));
				}
				else{
					set_alert('danger', _l('fe_no_data_changes', _l('fe_depreciations')));					
				}
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=depreciations'));
	}
	/**
	 * delete depreciations
	 * @param  integer $id 
	 */
	public function delete_depreciations($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_depreciations($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_depreciations')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_depreciations')));					
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=depreciations'));
	}

	/**
	 * locations table
	 * @return json 
	 */
	public function locations_table(){
		if ($this->input->is_ajax_request()) {
			if($this->input->post()){
				$this->load->model('currencies_model');
				$select = [
					'id',
					'location_name',         
					'id',
					'parent',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id'
				];
				$where        = [];
				$aColumns     = $select;
				$sIndexColumn = 'id';
				$sTable       = db_prefix() . 'fe_locations';
				$join         = [];

				$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
					'id',
					'location_name',
					'parent',
					'manager',
					'location_currency',
					'address',
					'city',
					'state',
					'zip',
					'country',
					'date_creator'
				]);


				$output  = $result['output'];
				$rResult = $result['rResult'];
				foreach ($rResult as $aRow) {
					$row = [];
					$row[] = $aRow['id'];  
					$_data = '';
					$_data .= '<div class="row-options">';
					$_data .= '<a href="'.admin_url('fixed_equipment/detail_locations/'.$aRow['id']).'" >' . _l('fe_view') . '</a>';
					if(is_admin() || has_permission('fixed_equipment_locations', '', 'edit')){
						$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					}
					if(is_admin() || has_permission('fixed_equipment_locations', '', 'delete')){
						$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_locations/'.$aRow['id']).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
					}
					$_data .= '</div>'; 

					$row[] = $aRow['location_name'].$_data;  

					$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'locations').'">';  

					$parent_name = '';
					if(is_numeric($aRow['parent'])){
						$data_location = $this->fixed_equipment_model->get_locations($aRow['parent']);
						if($data_location){
							$parent_name =  $data_location->location_name;
						}
					}
					$row[] = $parent_name;  

					$row[] = $this->fixed_equipment_model->count_asset_by_location($aRow['id']);  

					$row[] = $this->fixed_equipment_model->count_asset_assign_by_location($aRow['id']); 

					$currentcy_name = '';
					if(is_numeric($aRow['location_currency'])){
						$data_currencies = $this->currencies_model->get($aRow['location_currency']);
						if($data_currencies){
							$currentcy_name = $data_currencies->name;
						}
					}

					$row[] = $currentcy_name;  
					$row[] = $aRow['address'];  
					$row[] = $aRow['city'];  
					$row[] = $aRow['state'];  
					$row[] = $aRow['zip'];  

					$country_name = '';
					if(is_numeric($aRow['country'])){
						$data_country = get_country($aRow['country']);
						if($data_country){
							$country_name = $data_country->short_name;
						}
					}
					$row[] = $country_name;  


					$manager_name = '';
					if(is_numeric($aRow['manager'])){
						$manager_name =  get_staff_full_name($aRow['manager']);
					}
					$row[] = $manager_name; 

					$row[] = _dt($aRow['date_creator']);  


					$output['aaData'][] = $row;                                      
				}

				echo json_encode($output);
				die();
			}
		}
	}
	/**
	 * add locations
	 */
	public function add_locations(){
		if($this->input->post()){
			$data = $this->input->post();
			if($data['id'] == ''){
				unset($data['id']);
				$insert_id =  $this->fixed_equipment_model->add_locations($data);
				if(is_numeric($insert_id)){
					fe_handle_item_file($insert_id, 'locations');
					set_alert('success', _l('fe_added_successfully', _l('fe_locations')));
				}
				else{
					set_alert('danger', _l('fe_added_fail', _l('fe_locations')));					
				}
			}
			else{
				$result =  $this->fixed_equipment_model->update_locations($data);
				if($result){
					set_alert('success', _l('fe_updated_successfully', _l('fe_locations')));
				}
				else{
					set_alert('danger', _l('fe_no_data_changes', _l('fe_locations')));					
				}
				fe_handle_item_file($data['id'], 'locations');
			}
		}
		redirect(admin_url('fixed_equipment/locations'));
	}
	/**
	 * delete locations
	 * @param  integer $id 
	 */
	public function delete_locations($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_locations($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_locations')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_locations')));					
			}
		}
		redirect(admin_url('fixed_equipment/locations'));
	}
/**
 * get modal content locations
 * @param  integer $id
 * @return integer     
 */
public function get_modal_content_locations($id){
	$this->load->model('staff_model');
	$this->load->model('currencies_model');
	$data['location'] = $this->fixed_equipment_model->get_locations($id);
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$data['staffs'] = $this->staff_model->get();
	$data['currencies'] = $this->currencies_model->get();
	$data['base_currency'] = $this->currencies_model->get_base_currency();
	echo json_encode([
		'data' =>  $this->load->view('settings/includes/locations_modal_content', $data, true),
		'success' => true
	]);
}
/**
	 * { file item }
	 *
	 * @param        $id      The identifier
	 * @param        $rel_id  The relative identifier
	 */
public function file_item($id, $rel_id, $type)
{
	$data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
	$data['current_user_is_admin']             = is_admin();
	$data['file'] = $this->fixed_equipment_model->get_file($id, $rel_id);
	$data['types'] = $type;
	if (!$data['file']) {
		header('HTTP/1.0 404 Not Found');
		die;
	}
	$this->load->view('settings/includes/_file', $data);
}
   /**
	 * { delete file attachment }
	 *
	 * @param  $id     The identifier
	 */
   public function delete_file_item($id,$type)
   {
   	$this->load->model('misc_model');
   	$file = $this->misc_model->get_file($id);
   	if ($file->staffid == get_staff_user_id() || is_admin()) {
   		echo html_entity_decode($this->fixed_equipment_model->delete_file_item($id,$type));
   	} else {
   		header('HTTP/1.0 400 Bad error');
   		echo _l('access_denied');
   		die;
   	}
   }

/**
	 * suppliers table
	 * @return json 
	 */
public function suppliers_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_suppliers';
			$join         = [];

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'supplier_name',
				'address',
				'city',
				'state',
				'country',
				'zip',
				'contact_name',
				'phone',
				'fax',
				'email',
				'url',
				'note',
				'date_creator'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];

				$row[] = $aRow['id'];  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_suppliers/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>'; 

				$row[] = '<span class="text-nowrap">'.$aRow['supplier_name'].'</span>'.$_data;  
				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'suppliers').'">';  
				$row[] = '<span class="text-nowrap">'.$aRow['address'].'</span>';   
				$row[] = '<span class="text-nowrap">'.$aRow['contact_name'].'</span>';  
				$row[] = $aRow['email'];  
				$row[] = $aRow['phone'];  
				$row[] = $aRow['fax'];  
				$row[] = '<span class="text-nowrap">'.$aRow['url'].'</span>';  
				$row[] = $this->fixed_equipment_model->count_total_asset_supplier($aRow['id'], 'asset');
				$row[] = $this->fixed_equipment_model->count_total_asset_supplier($aRow['id'], 'accessory');
				$row[] = $this->fixed_equipment_model->count_total_asset_supplier($aRow['id'], 'license');

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
	/**
	 * add suppliers
	 */
	public function add_suppliers(){
		if($this->input->post()){
			$data = $this->input->post();
			if($data['id'] == ''){
				unset($data['id']);
				$insert_id =  $this->fixed_equipment_model->add_suppliers($data);
				if(is_numeric($insert_id)){
					fe_handle_item_file($insert_id, 'suppliers');
					set_alert('success', _l('fe_added_successfully', _l('fe_suppliers')));
				}
				else{
					set_alert('danger', _l('fe_added_fail', _l('fe_suppliers')));					
				}
			}
			else{
				$result =  $this->fixed_equipment_model->update_suppliers($data);
				if($result){
					set_alert('success', _l('fe_updated_successfully', _l('fe_suppliers')));
				}
				else{
					set_alert('danger', _l('fe_no_data_changes', _l('fe_suppliers')));					
				}
				fe_handle_item_file($data['id'], 'suppliers');
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=suppliers'));
	}
	/**
	 * delete suppliers
	 * @param  integer $id 
	 */
	public function delete_suppliers($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_suppliers($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_suppliers')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_suppliers')));					
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=suppliers'));
	}

	/**
 * get modal content suppliers
 * @param  integer $id
 * @return integer     
 */
	public function get_modal_content_suppliers($id){
		$this->load->model('staff_model');
		$this->load->model('currencies_model');
		$data['supplier'] = $this->fixed_equipment_model->get_suppliers($id);
		$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
		$data['staffs'] = $this->staff_model->get();
		$data['currencies'] = $this->currencies_model->get();
		$data['base_currency'] = $this->currencies_model->get_base_currency();
		echo json_encode([
			'data' =>  $this->load->view('settings/includes/suppliers_modal_content', $data, true),
			'success' => true
		]);
	}
/**
	 * asset_manufacturers table
	 * @return json 
	 */
public function asset_manufacturers_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_asset_manufacturers';
			$join         = [];

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'name',
				'url',
				'support_url',
				'support_phone',
				'support_email',
				'date_creator'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];

				$row[] = $aRow['id'];  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_asset_manufacturers/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>'; 

				$row[] = '<span class="text-nowrap">'.$aRow['name'].'</span>'.$_data;  
				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'asset_manufacturers').'">';  
				$row[] = '<span class="text-nowrap">'.$aRow['url'].'</span>';   
				$row[] = '<span class="text-nowrap">'.$aRow['support_url'].'</span>';  
				$row[] = '<span class="text-nowrap">'.$aRow['support_phone'].'</span>';  
				$row[] = '<span class="text-nowrap">'.$aRow['support_email'].'</span>';  
				$total1 = 0;
				$row[] = $this->fixed_equipment_model->count_asset_by_manufacturer_only_asset_type($aRow['id']);  
				$row[] = $this->fixed_equipment_model->count_total_asset_manufacturer($aRow['id'], 'license');
				$row[] = $this->fixed_equipment_model->count_total_asset_manufacturer($aRow['id'], 'consumable');
				$row[] = $this->fixed_equipment_model->count_total_asset_manufacturer($aRow['id'], 'accessory');
				$row[] = _dt($aRow['date_creator']);  
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
	/**
	 * add asset_manufacturers
	 */
	public function add_asset_manufacturers(){
		if($this->input->post()){
			$data = $this->input->post();
			if($data['id'] == ''){
				unset($data['id']);
				$insert_id =  $this->fixed_equipment_model->add_asset_manufacturers($data);
				if(is_numeric($insert_id)){
					fe_handle_item_file($insert_id, 'asset_manufacturers');
					set_alert('success', _l('fe_added_successfully', _l('fe_asset_manufacturers')));
				}
				else{
					set_alert('danger', _l('fe_added_fail', _l('fe_asset_manufacturers')));					
				}
			}
			else{
				$result =  $this->fixed_equipment_model->update_asset_manufacturers($data);
				if($result){
					set_alert('success', _l('fe_updated_successfully', _l('fe_asset_manufacturers')));
				}
				else{
					set_alert('danger', _l('fe_no_data_changes', _l('fe_asset_manufacturers')));					
				}
				fe_handle_item_file($data['id'], 'asset_manufacturers');
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=asset_manufacturers'));
	}
	/**
	 * delete asset_manufacturers
	 * @param  integer $id 
	 */
	public function delete_asset_manufacturers($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_asset_manufacturers($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_asset_manufacturers')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_asset_manufacturers')));					
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=asset_manufacturers'));
	}

	/**
 * get modal content asset_manufacturers
 * @param  integer $id
 * @return integer     
 */
	public function get_modal_content_asset_manufacturers($id){
		$this->load->model('staff_model');
		$this->load->model('currencies_model');
		$data['asset_manufacturer'] = $this->fixed_equipment_model->get_asset_manufacturers($id);
		echo json_encode([
			'data' =>  $this->load->view('settings/includes/asset_manufacturers_modal_content', $data, true),
			'success' => true
		]);
	}

	/**
	 * categories table
	 * @return json 
	 */
	public function categories_table(){
		if ($this->input->is_ajax_request()) {
			if($this->input->post()){
				$select = [
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id'
				];
				$where        = [];
				$aColumns     = $select;
				$sIndexColumn = 'id';
				$sTable       = db_prefix() . 'fe_categories';
				$join         = [];

				$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
					'category_name',
					'type',
					'primary_default_eula',
					'confirm_acceptance',
					'send_mail_to_user',
				]);


				$output  = $result['output'];
				$rResult = $result['rResult'];
				foreach ($rResult as $aRow) {
					$row = [];

					$row[] = $aRow['id'];  
					$_data = '';
					$_data .= '<div class="row-options">';
					$_data .= '<a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_categories/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
					$_data .= '</div>'; 

					$row[] = $aRow['category_name'].$_data;  
					$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'categories').'">';  
					$row[] = _l('fe_'.$aRow['type']);   
					$qty = 0;
					$row[] = $this->fixed_equipment_model->count_asset_by_category($aRow['id'], $aRow['type']);  

					$eula = '<i class="fa fa-times text-danger"></i>';
					if($aRow['primary_default_eula'] == 1){
						$eula = '<i class="fa fa-check text-success"></i>';						
					}
					$row[] = $eula; 

					$mail = '<i class="fa fa-times text-danger"></i>';
					if($aRow['send_mail_to_user'] == 1){
						$mail = '<i class="fa fa-check text-success"></i>';
					}
					$row[] = $mail; 

					$acceptance = '<i class="fa fa-times text-danger"></i>';
					if($aRow['confirm_acceptance'] == 1){
						$acceptance = '<i class="fa fa-check text-success"></i>';
					}
					$row[] = $acceptance;  




					$output['aaData'][] = $row;                                      
				}

				echo json_encode($output);
				die();
			}
		}
	}
	/**
	 * add categories
	 */
	public function add_categories(){
		if($this->input->post()){
			$data = $this->input->post();
			if($data['id'] == ''){
				unset($data['id']);
				$insert_id =  $this->fixed_equipment_model->add_categories($data);
				if(is_numeric($insert_id)){
					fe_handle_item_file($insert_id, 'categories');
					set_alert('success', _l('fe_added_successfully', _l('fe_categories')));
				}
				else{
					set_alert('danger', _l('fe_added_fail', _l('fe_categories')));					
				}
			}
			else{
				$result =  $this->fixed_equipment_model->update_categories($data);
				if($result){
					set_alert('success', _l('fe_updated_successfully', _l('fe_categories')));
				}
				else{
					set_alert('danger', _l('fe_no_data_changes', _l('fe_categories')));					
				}
				fe_handle_item_file($data['id'], 'categories');
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=categories'));
	}
	/**
	 * delete categories
	 * @param  integer $id 
	 */
	public function delete_categories($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_categories($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_categories')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_categories')));					
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=categories'));
	}

 /**
 * get modal content categories
 * @param  integer $id
 * @return integer     
 */
 public function get_modal_content_categories($id){
 	$this->load->model('staff_model');
 	$this->load->model('currencies_model');
 	$data['category'] = $this->fixed_equipment_model->get_categories($id);
 	echo json_encode([
 		'data' =>  $this->load->view('settings/includes/categories_modal_content', $data, true),
 		'success' => true
 	]);
 }

	/**
	 * models table
	 * @return json 
	 */
	public function models_table(){
		if ($this->input->is_ajax_request()) {
			if($this->input->post()){
				$select = [
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id',
					'id'
				];
				$where        = [];
				$aColumns     = $select;
				$sIndexColumn = 'id';
				$sTable       = db_prefix() . 'fe_models';
				$join         = [];

				$manufacturer = $this->input->post("manufacturer");
				$category = $this->input->post("category");
				$depreciation = $this->input->post("depreciation");
				if($manufacturer != ''){
					$list = implode(',', $manufacturer);
					array_push($where, 'AND manufacturer in ('.$list.')');
				}

				if($category != ''){
					$list = implode(',', $category);
					array_push($where, 'AND category in ('.$list.')');
				}

				if($depreciation != ''){
					$list = implode(',', $depreciation);
					array_push($where, 'AND depreciation in ('.$list.')');
				}


				$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
					'model_name',
					'manufacturer',
					'category',
					'model_no',
					'depreciation',
					'eol',
					'note',
					'custom_field',
					'may_request',
					'date_creator'
				]);


				$output  = $result['output'];
				$rResult = $result['rResult'];
				foreach ($rResult as $aRow) {
					$row = [];

					$row[] = $aRow['id'];  
					$_data = '';
					$_data .= '<div class="row-options">';
					$_data .= '<a href="'.admin_url('fixed_equipment/view_model/'.$aRow['id'].'').'">' . _l('fe_view') . '</a>';
					$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_models/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
					$_data .= '</div>'; 

					$row[] = '<a href="'.admin_url('fixed_equipment/view_model/'.$aRow['id'].'').'"><span class="text-nowrap">'.$aRow['model_name'] .'</span></a>'.$_data;
					$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'models').'">';  

					$manufacturer_name = '';
					if(is_numeric($aRow['manufacturer'])){
						$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($aRow['manufacturer']);
						if($data_manufacturer){
							$manufacturer_name = $data_manufacturer->name;								
						}
					}
					$row[] = '<span class="text-nowrap">'.$manufacturer_name.'</span>';  
					$row[] = $aRow['model_no']; 

					$row[] = $this->fixed_equipment_model->count_asset_by_model($aRow['id']);  

					$depreciation_name = '';
					if(is_numeric($aRow['depreciation'])){
						$data_depreciation = $this->fixed_equipment_model->get_depreciations($aRow['depreciation']);
						if($data_depreciation){
							$depreciation_name = $data_depreciation->name;								
						}
					}
					$row[] = '<span class="text-nowrap">'.$depreciation_name.'</span>';  

					$category_name = '';
					if(is_numeric($aRow['category'])){
						$data_category = $this->fixed_equipment_model->get_categories($aRow['category']);
						if($data_category){
							$category_name = $data_category->category_name;								
						}
					}
					$row[] = '<span class="text-nowrap">'.$category_name.'</span>';  
					$row[] = (is_numeric($aRow['eol']) ? '<span class="text-nowrap">'.$aRow['eol'].' '._l('months').'</span>' : '');  

					$row[] = '<span class="text-nowrap">'.$aRow['note'].'</span>';  
					$output['aaData'][] = $row;                                      
				}

				echo json_encode($output);
				die();
			}
		}
	}
	/**
	 * add models
	 */
	public function add_models(){
		if($this->input->post()){
			$data = $this->input->post();
			if($data['id'] == ''){
				unset($data['id']);
				$insert_id =  $this->fixed_equipment_model->add_models($data);
				if(is_numeric($insert_id)){
					fe_handle_item_file($insert_id, 'models');
					set_alert('success', _l('fe_added_successfully', _l('fe_models')));
				}
				else{
					set_alert('danger', _l('fe_added_fail', _l('fe_models')));					
				}
			}
			else{
				$result =  $this->fixed_equipment_model->update_models($data);
				if($result){
					set_alert('success', _l('fe_updated_successfully', _l('fe_models')));
				}
				else{
					set_alert('danger', _l('fe_no_data_changes', _l('fe_models')));					
				}
				fe_handle_item_file($data['id'], 'models');
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=models'));
	}
	/**
	 * delete models
	 * @param  integer $id 
	 */
	public function delete_models($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_models($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_models')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_models')));					
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=models'));
	}

	/**
 * get modal content models
 * @param  integer $id
 * @return integer     
 */
	public function get_modal_content_models($id){
		$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();
		$data['categories'] = $this->fixed_equipment_model->get_categories('','asset');
		$data['depreciations'] = $this->fixed_equipment_model->get_depreciations();
		$data['custom_field_lists'] = get_custom_fields('fixed_equipment');
		$data['model'] = $this->fixed_equipment_model->get_models($id);
		$data['field_sets'] = $this->fixed_equipment_model->get_field_set();

		$custom_field_id_list = [];
		$data_custom = $this->fixed_equipment_model->get_custom_field_models($id);
		if($data_custom){
			foreach ($data_custom as $fields) {
				$custom_field_id_list[] = $fields['fieldid'];
			}
		}
		$data['model']->custom_field = $custom_field_id_list;
		echo json_encode([
			'data' =>  $this->load->view('settings/includes/models_modal_content', $data, true),
			'success' => true
		]);
	}
/**
	 * status_labels table
	 * @return json 
	 */
public function status_labels_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_status_labels';
			$join         = [];

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'name',
				'status_type',
				'chart_color',
				'note',
				'show_in_side_nav',
				'default_label'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="javascript:void(0)" data-id="' . $aRow['id'] . '" data-name="' . $aRow['name'] . '" data-status_type="' . $aRow['status_type'] . '" data-chart_color="' . $aRow['chart_color'] . '" data-note="' . $aRow['note'] . '" data-default_label="' . $aRow['default_label'] . '" onclick="edit(this); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_status_labels/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>'; 

				$row[] = $aRow['name'].$_data;   
				$row[] = _l('fe_'.$aRow['status_type']);
				$row[] = $this->fixed_equipment_model->count_asset_by_status($aRow['id']);
				$row[] = '<div><button class="btn" style="background-color: '.$aRow['chart_color'].'"></button> <strong><small>'.$aRow['chart_color'].'</small></strong></div>';
				$default_label = '<i class="fa fa-times"></i>';
				if($aRow['default_label'] == 1){
					$default_label = '<i class="fa fa-check"></i>';
				}
				$row[] = $default_label;

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
	/**
	 * add status_labels
	 */
	public function add_status_labels(){
		if($this->input->post()){
			$data = $this->input->post();
			if($data['id'] == ''){
				unset($data['id']);
				$result =  $this->fixed_equipment_model->add_status_labels($data);
				if(is_numeric($result)){
					set_alert('success', _l('fe_added_successfully', _l('fe_status_labels')));
				}
				else{
					set_alert('danger', _l('fe_added_fail', _l('fe_status_labels')));					
				}
			}
			else{
				$result =  $this->fixed_equipment_model->update_status_labels($data);
				if($result){
					set_alert('success', _l('fe_updated_successfully', _l('fe_status_labels')));
				}
				else{
					set_alert('danger', _l('fe_no_data_changes', _l('fe_status_labels')));					
				}
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=status_labels'));
	}

	/**
	 * delete status_labels
	 * @param  integer $id 
	 */
	public function delete_status_labels($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_status_labels($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_status_labels')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_status_labels')));					
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=status_labels'));
	}

/**
 * view model
 * @param  integer $id 
 */
public function view_model($id){
	$data['model'] = $this->fixed_equipment_model->get_models($id);
	$title = '';
	if($data['model']){
		$title = $data['model']->model_name;
	}
	$data['title']                 = $title;
	$this->load->view('settings/view_model', $data);
}

/**
 * view model table
 */
public function view_model_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$model_id = $this->input->post('model_id');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];

			$custom_fields = get_custom_fields('fixed_equipment');
			foreach($custom_fields as $field){
				array_push($select,'id');
			}

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];
			array_push($where, 'AND type = "asset" AND active = 1 AND model_id = '.$model_id);

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				'depreciation',
				'supplier_id',
				'order_number',
				'description',
				'requestable',
				'qr_code',
				'date_creator',
				'updated_at',
				'checkin_out',
				'checkin_out_id',
				'status'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  

				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_asset/'.$aRow['id'].'?tab=details').'">' . _l('fe_view') . '</a>';
				$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_assets/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>'; 

				$row[] = $aRow['assets_name'].$_data;   

				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 

				$row[] = $aRow['series'];  

				$model_name = '';
				$model_no = '';
				$category_id = 0;
				$manufacturer_id = 0;
				if(is_numeric($aRow['model_id']) > 0){
					$data_model = $this->fixed_equipment_model->get_models($aRow['model_id']);
					if($data_model){
						$model_name = $data_model->model_name;
						$model_no = $data_model->model_no;
						$category_id = $data_model->category;
						$manufacturer_id = $data_model->manufacturer;
					}
				}
				$row[] = '<span class="text-nowrap">'.$model_name.'</span>';  
				$row[] = '<span class="text-nowrap">'.$model_no.'</span>';  

				$category_name = '';
				if(is_numeric($category_id) && $category_id > 0){
					$data_cat = $this->fixed_equipment_model->get_categories($category_id);
					if($data_cat){
						$category_name = $data_cat->category_name;
					}
				}
				$row[] = $category_name;  

				$status_name = '';
				if(is_numeric($aRow['status']) && $aRow['status'] > 0){
					$data_status = $this->fixed_equipment_model->get_status_labels($aRow['status']);
					if($data_status){
						$status_name = $data_status->name;
					}
				}
				$row[] = $status_name;  

				$data_location_info = $this->fixed_equipment_model->get_asset_location_info($aRow['id']);
				$checkout_to = '';
				$current_location = '';

				if($data_location_info->checkout_to != ''){		
					$icon_checkout_to = '';
					if($data_location_info->checkout_type == 'location'){
						$icon_checkout_to = '<i class="fa fa-map-marker"></i>';
						$checkout_to = '<a href="'.admin_url('fixed_equipment/detail_locations/'.$data_location_info->to_id).'?re=assets" class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</a>';  
						$current_location = '';
					}
					elseif($data_location_info->checkout_type == 'user'){
						$icon_checkout_to = '<i class="fa fa-user"></i>';
						$checkout_to = '<span class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</span>';  
						$current_location = '';
					}
					elseif($data_location_info->checkout_type == 'asset'){
						$icon_checkout_to = '<i class="fa fa-barcode"></i>';
						$checkout_to = '<a href="'.admin_url('fixed_equipment/detail_asset/'.$data_location_info->to_id.'?tab=details').'" class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</a>';  
						$current_location = '';
					}
				}
				$row[] = $checkout_to;  
				$row[] = '<span class="text-nowrap">'.$data_location_info->curent_location.'</span>';  
				$row[] = '<span class="text-nowrap">'.$data_location_info->default_location.'</span>'; 

				$manufacturer_name = '';
				if(is_numeric($manufacturer_id) && $manufacturer_id > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($manufacturer_id);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = $manufacturer_name;  

				$supplier_name = '';
				if(is_numeric($aRow['supplier_id'])){
					$data_supplier = $this->fixed_equipment_model->get_suppliers($aRow['supplier_id']);
					if($data_supplier){
						$supplier_name = $data_supplier->supplier_name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$supplier_name.'</span>';  

				$row[] = $aRow['date_buy'] != '' ? _d($aRow['date_buy']) : '';  
				$row[] = $aRow['unit_price'] != '' ? app_format_money($aRow['unit_price'], $currency_name) : '';  
				$row[] = $aRow['order_number'];  
				$row[] = $aRow['warranty_period'] != '' ? $aRow['warranty_period'].' '._l('months') : '';  
				$row[] = '';  
				$row[] = $aRow['description'];  
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow['id'], 'checkout');  
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow['id'], 'checkin'); 
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow['id'], 'checkout', 1);  
				$row[] = '<span class="text-nowrap">'._dt($aRow['date_creator']).'</span>';
				$row[] = '<span class="text-nowrap">'._dt($aRow['updated_at']).'</span>'; 

				$checkout_date = ''; 
				$expected_checkin_date = ''; 
				if($aRow['checkin_out'] == 2){
					if(is_numeric($aRow['checkin_out_id']) && $aRow['checkin_out_id'] > 0){
						$data_checkout = $this->fixed_equipment_model->get_checkin_out_data($aRow['checkin_out_id']);
						if($data_checkout){
							$expected_checkin_date =(($data_checkout->expected_checkin_date != '' || $data_checkout->expected_checkin_date != null) ? _d($data_checkout->expected_checkin_date) : '');
							$checkout_date = (($data_checkout->checkin_date != '' || $data_checkout->checkin_date != null) ? _d($data_checkout->checkin_date) : _d(date('Y-m-d'), $data_checkout->date_creator));
						}
					}
				}

				$row[] = $checkout_date;  
				$row[] = $expected_checkin_date; 

				$last_audit = '';
				$next_audit = '';
				$data_audit = $this->fixed_equipment_model->get_2_audit_info_asset($aRow['id']);
				if($data_audit){
					if(isset($data_audit[0]) && isset($data_audit[1])){
						$next_audit = _d(date('Y-m-d',strtotime($data_audit[0]['audit_date'])));
						$last_audit = _d(date('Y-m-d',strtotime($data_audit[1]['audit_date'])));
					}
					if(isset($data_audit[0]) && !isset($data_audit[1])){
						$next_audit = _d(date('Y-m-d',strtotime($data_audit[0]['audit_date'])));
					}
				}
				$row[] = '<span class="text-nowrap">'.$last_audit.'</span>';  
				$row[] = '<span class="text-nowrap">'.$next_audit.'</span>';  

				foreach ($custom_fields as $field) {
					$value = get_custom_field_value($aRow['id'], $field['id'], 'fixed_equipment'); 
					$row[] = $value;  
				} 

				

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
 * assets
 */
public function assets(){
	if (!(has_permission('fixed_equipment_assets', '', 'view_own') || has_permission('fixed_equipment_assets', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$data['title']    = _l('fe_asset_management');
	$this->load->model('currencies_model');
	$this->load->model('staff_model');
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}

	$data['models'] = $this->fixed_equipment_model->get_models();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['status_labels'] = $this->fixed_equipment_model->get_status_labels();
	$data['status_label_checkout'] = $this->fixed_equipment_model->get_status_labels('','deployable');
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
	$data['staffs'] = $this->staff_model->get();
	$this->load->view('asset_management', $data);
}
/**
 * add assets 
 */
public function add_assets(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		if (!$this->input->post('id')) {
			$res = $this->fixed_equipment_model->add_asset($data);
			if (count($res) > 0) {
				$message = _l('fe_added_successfully', _l('fe_asset'));
				set_alert('success', $message);
			}
			else{
				$message = _l('fe_added_fail', _l('fe_asset'));
				set_alert('danger', $message);
			}
			redirect(admin_url('fixed_equipment/assets'));
		} else {
			$id = $data['id'];
			unset($data['id']);
			$success = $this->fixed_equipment_model->update_asset($data, $id);
			if ($success) {
				$message = _l('fe_updated_successfully', _l('fe_asset'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_updated_fail', _l('fe_asset'));
				set_alert('danger',$message);
			}
			redirect(admin_url('fixed_equipment/assets'));
		}
		die;
	}
	$data['title']    = _l('fe_add_asets');
	$this->load->view('add_assets', $data);
}
/**
	 * status_labels table
	 * @return json 
	 */
public function all_asset_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){

			$model = $this->input->post("model");
			$status = $this->input->post("status");
			$supplier = $this->input->post("supplier");
			$location = $this->input->post("location");

			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				db_prefix().'fe_assets.id',

				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description',
				'requestable',
				'qr_code',
				db_prefix().'fe_assets.date_creator',
				'updated_at',
				'checkin_out',
				'status',

				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',

			];



			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = ['LEFT JOIN '.db_prefix().'fe_models ON '.db_prefix().'fe_models.id = '.db_prefix().'fe_assets.model_id'];
			array_push($where, 'AND type = "asset"');
			array_push($where, 'AND active = 1');
			
			if($model != ''){
				array_push($where, 'AND '.db_prefix().'fe_assets.model_id = '.$model);
			}	
			if($status != ''){
				array_push($where, 'AND status = '.$status);
			}
			if($supplier != ''){
				array_push($where, 'AND supplier_id = '.$supplier);
			}
			if($location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}

			if(!is_admin() || !has_permission('fixed_equipment_assets', '', 'view')){
				array_push($where, 'AND requestable = 1');
			}
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_assets.id',
				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description',
				'requestable',
				'qr_code',
				db_prefix().'fe_assets.date_creator',
				'updated_at',
				'checkin_out',
				'checkin_out_id',
				db_prefix().'fe_models.model_name',
				db_prefix().'fe_models.model_no',
				'status'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = '<input type="checkbox" class="individual" data-id="'.$aRow['id'].'" onchange="checked_add(this); return false;"/>';     
				$row[] = $aRow['id'];  

				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_asset/'.$aRow[db_prefix().'fe_assets.id'].'?tab=details').'">' . _l('fe_view') . '</a>';
				$_data .= ' | <a target="_blank" href="'.admin_url('fixed_equipment/print_qrcode_pdf/'.$aRow[db_prefix().'fe_assets.id'].'?output_type=I').'" class="text-warning">' . _l('fe_print_qrcode') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_assets', '', 'edit')){
					$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow[db_prefix().'fe_assets.id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || has_permission('fixed_equipment_assets', '', 'delete')){
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_assets/'.$aRow[db_prefix().'fe_assets.id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$row[] = '<span class="text-nowrap">'.$aRow['assets_name'].'</span>'.$_data;   

				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 

				$row[] = $aRow['series'];  

				$category_id = 0;
				$manufacturer_id = 0;
				if(is_numeric($aRow['model_id']) > 0){
					$data_model = $this->fixed_equipment_model->get_models($aRow['model_id']);
					if($data_model){
						$category_id = $data_model->category;
						$manufacturer_id = $data_model->manufacturer;
					}
				}
				$row[] = '<span class="text-nowrap">'.$aRow['model_name'].'</span>';  
				$row[] = $aRow['model_no'];  

				$category_name = '';
				if(is_numeric($category_id) && $category_id > 0){
					$data_cat = $this->fixed_equipment_model->get_categories($category_id);
					if($data_cat){
						$category_name = '<span class="text-nowrap">'.$data_cat->category_name.'</span>';  
					}
				}
				$row[] = $category_name;  

				$status = '';
				$status_name = '';
				if(is_numeric($aRow['status']) && $aRow['status'] > 0){
					$data_status = $this->fixed_equipment_model->get_status_labels($aRow['status']);
					if($data_status){
						$status = $data_status->status_type;
						if($aRow['checkin_out'] == 2 && $status == 'deployable'){
							$status = 'deployed';
						}
						$status_name = '<div class="row text-nowrap mleft5 mright5"><span style="color:'.$data_status->chart_color.'">'.$data_status->name.'</span><span class="mleft10 label label-primary">'._l('fe_'.$status).'</span></div>';
					}
				}
				$row[] = $status_name;  



				$data_location_info = $this->fixed_equipment_model->get_asset_location_info($aRow[db_prefix().'fe_assets.id']);
				$checkout_to = '';
				$current_location = '';

				if($data_location_info->checkout_to != ''){		
					$icon_checkout_to = '';
					if($data_location_info->checkout_type == 'location'){
						$icon_checkout_to = '<i class="fa fa-map-marker"></i>';
						$checkout_to = '<a href="'.admin_url('fixed_equipment/detail_locations/'.$data_location_info->to_id).'?re=assets" class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</a>';  
						$current_location = '';
					}
					elseif($data_location_info->checkout_type == 'user'){
						$head = '';
						$tail = '';
			         if(fe_get_status_modules('hr_profile')){
			         	$head = '<a href="'.admin_url('hr_profile/member/'.$data_location_info->to_id.'/profile').'" target="_blank">';
							$tail = '</a>';
			         }
						$icon_checkout_to = '<i class="fa fa-user"></i>';
						$checkout_to = $head.'<span class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</span>'.$tail;  
						$current_location = '';
					}
					elseif($data_location_info->checkout_type == 'asset'){
						$icon_checkout_to = '<i class="fa fa-barcode"></i>';
						$checkout_to = '<a href="'.admin_url('fixed_equipment/detail_asset/'.$data_location_info->to_id.'?tab=details').'" class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</a>';  
						$current_location = '';
					}
				}
				$row[] = $checkout_to;  
				$row[] = '<span class="text-nowrap">'.$data_location_info->curent_location.'</span>';  
				$row[] = '<span class="text-nowrap">'.$data_location_info->default_location.'</span>';  
				$manufacturer_name = '';
				if(is_numeric($manufacturer_id) && $manufacturer_id > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($manufacturer_id);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = $manufacturer_name;  

				$supplier_name = '';
				if(is_numeric($aRow['supplier_id'])){
					$data_supplier = $this->fixed_equipment_model->get_suppliers($aRow['supplier_id']);
					if($data_supplier){
						$supplier_name = '<span class="text-nowrap">'.$data_supplier->supplier_name.'</span>';  
					}
				}
				$row[] = $supplier_name;  

				$row[] = $aRow['date_buy'] != '' ? _d($aRow['date_buy']) : '';  
				$row[] = $aRow['unit_price'] != '' ? app_format_money($aRow['unit_price'], $currency_name) : '';  
				$row[] = $aRow['order_number'];  
				$row[] = (($aRow['warranty_period'] != '' && $aRow['warranty_period'] != 0) ? $aRow['warranty_period'].' '._l('months') : '');  
				$row[] = (($aRow['warranty_period'] != '' && $aRow['warranty_period'] != 0) ? _d(get_expired_date($aRow['date_buy'], $aRow['warranty_period'])) : '');    
				$row[] = '<span class="text-nowrap">'.$aRow['description'].'</span>';  
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkout',0);  
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkin'); 
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkout', 1, 1);  
				$row[] = '<span class="text-nowrap">'._dt($aRow['date_creator']).'</span>';
				$row[] = '<span class="text-nowrap">'._dt($aRow['updated_at']).'</span>'; 
				$checkout_date = ''; 
				$expected_checkin_date = ''; 
				if($aRow['checkin_out'] == 2){
					if(is_numeric($aRow['checkin_out_id']) && $aRow['checkin_out_id'] > 0){
						$data_checkout = $this->fixed_equipment_model->get_checkin_out_data($aRow['checkin_out_id']);
						if($data_checkout){
							$expected_checkin_date =(($data_checkout->expected_checkin_date != '' || $data_checkout->expected_checkin_date != null) ? _d($data_checkout->expected_checkin_date) : '');
							$checkout_date = (($data_checkout->checkin_date != '' || $data_checkout->checkin_date != null) ? _d($data_checkout->checkin_date) : _d(date('Y-m-d'), $data_checkout->date_creator));
						}
					}
				}

				$row[] = $checkout_date;  
				$row[] = $expected_checkin_date;  
				$last_audit = '';
				$next_audit = '';
				$data_audit = $this->fixed_equipment_model->get_2_audit_info_asset($aRow['id']);
				if($data_audit){
					if(isset($data_audit[0]) && isset($data_audit[1])){
						$next_audit = _d(date('Y-m-d',strtotime($data_audit[0]['audit_date'])));
						$last_audit = _d(date('Y-m-d',strtotime($data_audit[1]['audit_date'])));
					}
					if(isset($data_audit[0]) && !isset($data_audit[1])){
						$next_audit = _d(date('Y-m-d',strtotime($data_audit[0]['audit_date'])));
					}
				}
				$row[] = '<span class="text-nowrap">'.$last_audit.'</span>';  
				$row[] = '<span class="text-nowrap">'.$next_audit.'</span>';  

				$button = '';
				if(is_admin() || has_permission('fixed_equipment_assets', '', 'create')){
					if($aRow['checkin_out'] == 2){
						$button = '<a class="btn btn-primary" data-asset_name="'.$aRow['assets_name'].'" data-serial="'.$aRow['series'].'" data-model="'.$aRow['model_name'].'" onclick="check_in(this, '.$aRow[db_prefix().'fe_assets.id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					else{
						if($status == 'deployable'){
							$button = '<a class="btn btn-danger" data-asset_name="'.$aRow['assets_name'].'" data-serial="'.$aRow['series'].'" data-model="'.$aRow['model_name'].'" onclick="check_out(this, '.$aRow[db_prefix().'fe_assets.id'].')" >' . _l('fe_checkout') . '</a>';  					
						}
					}
				}
				$row[] = $button;
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}


/**
 * delete assets
 */

public function delete_assets($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_assets($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_assets')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_assets')));					
		}
	}
	redirect(admin_url('fixed_equipment/assets'));
}
/**
* get modal content assets
* @param  integer $id 
* @return json     
*/
public function get_modal_content_assets($id){
	$this->load->model('currencies_model');
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$data['models'] = $this->fixed_equipment_model->get_models();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['status_labels'] = $this->fixed_equipment_model->get_status_labels();

	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$data['asset'] = $this->fixed_equipment_model->get_assets($id);
	echo json_encode([
		'data' =>  $this->load->view('includes/new_asset_modal', $data, true),
		'success' => true
	]);
}
/**
* check exist serial
* @param  string $serial   
* @param  integer $asset_id 
* @return string           
*/
public function check_exist_serial($serial, $asset_id){
	$message = '';
	if($asset_id == 0){
		$asset_id = '';
	}
	$data = $this->fixed_equipment_model->check_exist_serial($serial, $asset_id);
	if($data){
		$message = _l('fe_this_serial_number_exists_in_the_system');
	}
	echo json_encode($message);
}
/**
 * check in assets
 * @return  
 */
public  function check_in_assets(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$result = $this->fixed_equipment_model->check_in_assets($data);
		if($result > 0){
			if($data['type'] == 'checkout'){
				set_alert('success', _l('fe_checkout_successfully', _l('fe_assets')));				
			}
			else{
				set_alert('success', _l('fe_checkin_successfully', _l('fe_assets')));				
			}
		}
		else{
			if($data['type'] == 'checkout'){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_assets')));					
			}else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_assets')));				
			}
		}
		redirect(admin_url('fixed_equipment/assets'));
	}
}


/**
 * check out assets
 * @return  
 */
public  function check_out_assets(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$result = $this->fixed_equipment_model->check_out_assets($data);
		if($result > 0){
			set_alert('success', _l('fe_checkout_successfully', _l('fe_assets')));
		}
		else{
			set_alert('danger', _l('fe_checkout_fail', _l('fe_assets')));					
		}
		redirect(admin_url('fixed_equipment/assets'));
	}
}

/**
 * detail asset
 * @param  integer $id 
 */
public function detail_asset($id){
	$data['redirect'] = $this->input->get('re');
	$data['asset'] = $this->fixed_equipment_model->get_assets($id);
	if($data['asset']){
		if($data['asset']->active == 0){
			set_alert('danger', _l('fe_this_asset_not_exist'));
			redirect(admin_url('fixed_equipment/assets'));
		}
	}
	else{
		set_alert('danger', _l('fe_this_asset_not_exist'));
		redirect(admin_url('fixed_equipment/assets'));
	}
	$title = '';
	if($data['asset']){
		$data['model'] = $this->fixed_equipment_model->get_models($data['asset']->model_id);
		$title = $data['model']->model_name;
	}
	$data['title'] = $title;
	$data['id'] = $id;
	$data['tab'] = $this->input->get('tab');
	if($data['tab'] == 'maintenances'){
		$this->load->model('currencies_model');
		$this->load->model('staff_model');
		$base_currency = $this->currencies_model->get_base_currency();
		$data['currency_name'] = '';
		if(isset($base_currency)){
			$data['currency_name'] = $base_currency->name;
		}
		$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
		$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
	}

	if($data['tab'] == 'assets'){
		$data['models'] = $this->fixed_equipment_model->get_models();
		$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
		$data['status_labels'] = $this->fixed_equipment_model->get_status_labels();
		$data['status_label_checkout'] = $this->fixed_equipment_model->get_status_labels('','deployable');
		$data['locations'] = $this->fixed_equipment_model->get_locations();
		$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
		$data['staffs'] = $this->staff_model->get();
	}
	$this->load->view('view_detail_assets', $data);
}

	 /**
	 * models table
	 * @return json 
	 */
	 public function asets_history_table(){
	 	if ($this->input->is_ajax_request()) {
	 		if($this->input->post()){
	 			$this->load->model('staff_model');
	 			$id = $this->input->post('id');
	 			$select = [
	 				'id',
	 				'id',
	 				'id',
	 				'id',
	 				'id'			
	 			];
	 			$where        = [];

	 			array_push($where, 'where item_id = '.$id);

	 			$aColumns     = $select;
	 			$sIndexColumn = 'id';
	 			$sTable       = db_prefix() . 'fe_log_assets';
	 			$join         = [];

	 			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
	 				'id',
	 				'admin_id',
	 				'action',
	 				'target',
	 				'changed',
	 				db_prefix() . 'fe_log_assets.to',
	 				'to_id',
	 				'notes',
	 				'date_creator'
	 			]);


	 			$output  = $result['output'];
	 			$rResult = $result['rResult'];
	 			foreach ($rResult as $aRow) {
	 				$row = [];
	 				$row[] = _dt($aRow['date_creator']);  
	 				$row[] = get_staff_full_name($aRow['admin_id']);  
	 				$row[] = _l('fe_'.$aRow['action']);  

	 				$target = '';
	 				switch ($aRow['to']) {
	 					case 'user':
	 					$department_name = '';
	 					$data_staff_department = $this->departments_model->get_staff_departments($aRow['to_id']);
	 					if($data_staff_department){
	 						foreach ($data_staff_department as $key => $staff_department) {
	 							$department_name .= $staff_department['name'].', ';
	 						}
	 						if($department_name != ''){
	 							$department_name = '('.rtrim($department_name,', ').') ';
	 						}
	 					}
	 					$target = '<i class="fa fa-user"></i> '.$department_name.''.get_staff_full_name($aRow['to_id']);
	 					break;
	 					case 'asset':
	 					$data_assets = $this->fixed_equipment_model->get_assets($aRow['to_id']);
	 					if($data_assets){
	 						$target = '<i class="fa fa-barcode"></i> ('.$data_assets->qr_code.') '.$data_assets->assets_name;
	 					}
	 					break;
	 					case 'location':
	 					$data_locations = $this->fixed_equipment_model->get_locations($aRow['to_id']);
	 					if($data_locations){
	 						$target = '<i class="fa fa-map-marker"></i> '.$data_locations->location_name;
	 					}
	 					break;
	 				}

	 				$row[] = $target;  
	 				$row[] = $aRow['notes'];  
	 				$output['aaData'][] = $row;                                      
	 			}

	 			echo json_encode($output);
	 			die();
	 		}
	 	}
	 }
/**
 * licenses
 */
public function licenses(){
	if (!(has_permission('fixed_equipment_licenses', '', 'view_own') || has_permission('fixed_equipment_licenses', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$this->load->model('currencies_model');
	if ($this->input->post()) {
		$data             = $this->input->post();
		if ($data['id'] == '') {
			unset($data['id']);
			$res = $this->fixed_equipment_model->add_licenses($data);
			if (is_numeric($res)) {
				$message = _l('fe_added_successfully', _l('fe_licenses'));
				set_alert('success', $message);
			}
			else{
				$message = _l('fe_added_fail', _l('fe_licenses'));
				set_alert('danger', $message);
			}
		} else {
			$success = $this->fixed_equipment_model->update_licenses($data);
			if ($success == 1) {
				$message = _l('fe_updated_successfully', _l('fe_licenses'));
				set_alert('success',$message);
			}
			elseif($success == 2){
				$message = _l('fe_updated_fail', _l('fe_licenses'));
				set_alert('danger',$message);
			}
			else{
				$message = _l('this_seat_is_currently_checked_out_to_a_user', _l('fe_licenses'));
				set_alert('danger',$message);
			}
		}
		redirect(admin_url('fixed_equipment/licenses'));
		die;
	}
	$this->load->model('staff_model');
	$data['title']  = _l('fe_licenses_management');
	$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
	$data['staffs'] = $this->staff_model->get();			
	$data['categories'] = $this->fixed_equipment_model->get_categories('','license');
	$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['depreciations'] = $this->fixed_equipment_model->get_depreciations();
	$base_currency = $this->currencies_model->get_base_currency();

	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$this->load->view('licenses_management', $data);
}

/**
 * licenses table
 * @return json 
 */
public function licenses_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];
			$manufacturer = $this->input->post('manufacturer');

			if(isset($manufacturer) && $manufacturer != ''){
				array_push($where, 'AND manufacturer_id = '.$manufacturer);
			}
			array_push($where, 'AND type = "license"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'date_buy',
				'depreciation',
				'supplier_id',
				'order_number',
				'description',
				'category_id',
				'product_key',
				'seats',
				'model_no',
				'location_id',
				'manufacturer_id',
				'licensed_to_name',
				'licensed_to_email',
				'reassignable',
				'termination_date',
				'expiration_date',
				'purchase_order_number',
				'maintained',      
				'manufacturer_id',  
				'checkin_out',     
				'status'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			array_push($where, 'AND type = "license"');
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  

				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_licenses/'.$aRow['id'].'?tab=details').'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_licenses', '', 'edit')){				
					$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || has_permission('fixed_equipment_licenses', '', 'delete')){				
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_license/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$row[] = $aRow['assets_name'].$_data;   

				$row[] = $aRow['product_key'];  

				$row[] = _d($aRow['expiration_date']);  

				$row[] = $aRow['licensed_to_email'];  

				$row[] = $aRow['licensed_to_name'];  

				$manufacturer_name = '';
				if(is_numeric($aRow['manufacturer_id']) && $aRow['manufacturer_id'] > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($aRow['manufacturer_id']);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = $manufacturer_name;  
				$total = 0;
				$avail = 0;
				$data_total = $this->fixed_equipment_model->count_total_avail_seat($aRow['id']);
				if($data_total){
					$total = $data_total->total;
					$avail = $data_total->avail;
				}

				$row[] = $total;  

				$row[] = $avail;  
				if(is_admin() || has_permission('fixed_equipment_licenses', '', 'create')){
					if($aRow['checkin_out'] == 2){
						$row[] = '<a class="btn btn-primary" data-asset_name="'.$aRow['assets_name'].'" onclick="check_in(this, '.$aRow['id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					else{
						$row[] = '<a class="btn btn-danger" data-asset_name="'.$aRow['assets_name'].'" onclick="check_out(this, '.$aRow['id'].')" >' . _l('fe_checkout') . '</a>';  			
					}
				}

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}


/**
 * delete license
 */
public function delete_license($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_licenses($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_licenses')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_licenses')));					
		}
	}
	redirect(admin_url('fixed_equipment/licenses'));
}

/**
 * get data licenses
 * @param  integer $id 
 * @return integer     
 */
public function get_data_licenses($id){
	$data =  $this->fixed_equipment_model->get_assets($id);
	echo json_encode($data);
}

/**
 * check in assets
 * @return  
 */
public  function check_in_license(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		if($data['item_id'] != ''){
			$id = $data['id'];
			$data['asset_id'] = $data['id'];
			unset($data['id']);
			$result = $this->fixed_equipment_model->check_in_licenses($data);
			if($result > 0){
				if($data['type'] == 'checkout'){
					set_alert('success', _l('fe_checkout_successfully', _l('fe_licenses')));
				}
				else{
					set_alert('success', _l('fe_checkin_successfully', _l('fe_licenses')));
				}
			}
			else{
				if($data['type'] == 'checkout'){
					set_alert('danger', _l('fe_checkout_fail', _l('fe_licenses')));				
				}
				else{
					set_alert('danger', _l('fe_checkin_fail', _l('fe_licenses')));						
				}
			}
			redirect(admin_url('fixed_equipment/detail_licenses/'.$id.'?tab=seat'));
		}
		else{
			$result = $this->fixed_equipment_model->check_in_license_auto($data);
			if($result > 0){
				if($data['type'] == 'checkout'){
					set_alert('success', _l('fe_checkout_successfully', _l('fe_licenses')));
				}
				else{
					set_alert('success', _l('fe_checkin_successfully', _l('fe_licenses')));					
				}
			}
			else{
				if($data['type'] == 'checkout'){
					set_alert('danger', _l('fe_checkout_fail', _l('fe_licenses')));					
				}
				else{
					set_alert('danger', _l('fe_checkin_fail', _l('fe_licenses')));				
				}
			}
			redirect(admin_url('fixed_equipment/licenses'));			
		}
	}
}
/**
 * detail licenses
 * @param  inter $id 
 */
public function detail_licenses($id){
	$data['asset'] = $this->fixed_equipment_model->get_assets($id);
	$title = '';
	if($data['asset']){
		$title = $data['asset']->assets_name;
	}
	$data['title'] = $title;
	$data['id'] = $id;
	$data['tab'] = $this->input->get('tab');
	if($data['tab'] == 'seat'){
		$this->load->model('staff_model');
		$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
		$data['staffs'] = $this->staff_model->get();
	}
	$this->load->view('view_detail_licenses', $data);
}

/**
 * status_labels table
 * @return json 
 */
public function license_seat_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$id = $this->input->post('id');
			$select = [
				'id',
				'id',
				'id',
				'id'
			];
			if(is_admin() || has_permission('fixed_equipment_licenses', '', 'view')){		
				array_push($select, 'id');		
			}


			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_seats';
			$join         = [];
			array_push($where, 'AND license_id = '.$id);
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'seat_name',
				db_prefix() . 'fe_seats.to',
				'to_id',
				'license_id',
				'status',
				'date_creator'
			]);

			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $key => $aRow) {
				$row = [];
				$_data = '';

				$row[] = $aRow['seat_name'];   

				$to_user = "";
				$to_asset = "";
				$location = "";

				if($aRow['to']!=""){
					switch ($aRow['to']) {
						case 'user':
						$to_user = get_staff_full_name($aRow['to_id']);
						$department_name = '';
						$data_staff_department = $this->departments_model->get_staff_departments($aRow['to_id']);
						if($data_staff_department){
							foreach ($data_staff_department as $key => $staff_department) {
								$department_name .= $staff_department['name'].', ';
							}
							if($department_name != ''){
								$location = rtrim($department_name,', ');
							}
						}
						break;
						case 'asset':
						$asset_data = $this->fixed_equipment_model->get_assets($aRow['to_id']);
						if($asset_data){
							$to_asset = '';
							if($asset_data->series != '' && $asset_data->assets_name != ''){
								$to_asset = '('.$asset_data->series.') '.$asset_data->assets_name;
							}
							if($asset_data->series == '' && $asset_data->assets_name != ''){
								$to_asset = $asset_data->assets_name;
							}
							if($asset_data->series != '' && $asset_data->assets_name == ''){
								$to_asset = $asset_data->series;
							}
							$location = $this->fixed_equipment_model->get_asset_location_info($aRow['to_id'])->curent_location;
						}
						break;
					}
				}
				$license_name = "";
				$data_asset = $this->fixed_equipment_model->get_assets($aRow['license_id']);
				if($data_asset){
					$license_name = $data_asset->assets_name;
				}

				$row[] = $to_user;   
				$row[] = '<a href="'.admin_url('fixed_equipment/detail_asset/'.$aRow['to_id'].'?tab=details').'">' . $to_asset. '</a>';
				$row[] = $location;   
				if(is_admin() || has_permission('fixed_equipment_licenses', '', 'create')){				
					if($aRow['status'] == 2){
						$row[] = '<a class="btn btn-primary" data-license_name="'.$license_name.'" onclick="check_in(this, '.$aRow['id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					else{
						$row[] = '<a class="btn btn-danger" data-license_name="'.$license_name.'" onclick="check_out(this, '.$aRow['id'].')" >' . _l('fe_checkout') . '</a>';  					
					}
				}
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
 * get location name
 * @param  integer $asset_id 
 * @return integer           
 */
public function get_location_name($asset_id){
	$obj = new stdClass();
	$obj->current_location = "";
	$obj->default_location = "";

	$data_asset = $this->fixed_equipment_model->get_assets($asset_id);
	if($data_asset){
		$location_name = '';
		if(is_numeric($data_asset->asset_location) && $data_asset->asset_location > 0){
			$data_location = $this->fixed_equipment_model->get_locations($data_asset->asset_location);
			if($data_location){
				$location_name = $data_location->location_name;
			}
		}
		$curent_location = '';
		if($data_asset->checkin_out == 2){
			$curent_location = $this->fixed_equipment_model->get_current_asset_location($data_asset->id);
		}
		else{
			$data_checkin_out = $this->fixed_equipment_model->get_last_checkin_out_assets($data_asset->id,'checkin');
			if($data_checkin_out){
				$location_id = $data_checkin_out->location_id;
				if(!is_numeric($location_id) || $location_id == 0){
					$location_id = $data_asset->asset_location;
				}
				$data_location = $this->fixed_equipment_model->get_locations($location_id);
				if($data_location){
					$curent_location = $data_location->location_name;
				}
			}
			else{
				$curent_location = $location_name;
			}

		}
		$obj->current_location = $curent_location;
		$obj->default_location = $location_name;
	}
	return $obj;
}
	/**
	 * license history table
	 * @return json 
	 */
	public function license_history_table(){
		if ($this->input->is_ajax_request()) {
			if($this->input->post()){
				$this->load->model('staff_model');
				$id = $this->input->post('id');
				$select = [
					'id',
					'id',
					'id',
					'id',
					'id'			
				];
				$where        = [];

				array_push($where, 'where item_id = '.$id);

				$aColumns     = $select;
				$sIndexColumn = 'id';
				$sTable       = db_prefix() . 'fe_log_assets';
				$join         = [];

				$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
					'id',
					'admin_id',
					'action',
					'target',
					'changed',
					db_prefix() . 'fe_log_assets.to',
					'to_id',
					'notes',
					'date_creator'
				]);


				$output  = $result['output'];
				$rResult = $result['rResult'];
				foreach ($rResult as $aRow) {
					$row = [];
					$row[] = _dt($aRow['date_creator']);  
					$row[] = get_staff_full_name($aRow['admin_id']);  
					$row[] = _l('fe_'.$aRow['action']);  

					$target = '';
					switch ($aRow['to']) {
						case 'user':
						$department_name = '';
						$data_staff_department = $this->departments_model->get_staff_departments($aRow['to_id']);
						if($data_staff_department){
							foreach ($data_staff_department as $key => $staff_department) {
								$department_name .= $staff_department['name'].', ';
							}
							if($department_name != ''){
								$department_name = '('.rtrim($department_name,', ').') ';
							}
						}
						$target = '<i class="fa fa-user"></i> '.$department_name.''.get_staff_full_name($aRow['to_id']);
						break;
						case 'asset':
						$data_assets = $this->fixed_equipment_model->get_assets($aRow['to_id']);
						if($data_assets){
							$target = '<i class="fa fa-barcode"></i> ('.$data_assets->qr_code.') '.$data_assets->assets_name;
						}
						break;
						case 'location':
						$data_locations = $this->fixed_equipment_model->get_locations($aRow['to_id']);
						if($data_locations){
							$target = '<i class="fa fa-map-marker"></i> '.$data_locations->location_name;
						}
						break;
					}

					$row[] = $target;  
					$row[] = $aRow['notes'];  
					$output['aaData'][] = $row;                                      
				}

				echo json_encode($output);
				die();
			}
		}
	}
/**
 * accessories
 */
public function accessories(){
	if (!(has_permission('fixed_equipment_accessories', '', 'view_own') || has_permission('fixed_equipment_accessories', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$this->load->model('currencies_model');
	if ($this->input->post()) {
		$data             = $this->input->post();
		if ($data['id'] == '') {
			unset($data['id']);
			$insert_id = $this->fixed_equipment_model->add_accessories($data);
			if (is_numeric($insert_id)) {
				fe_handle_item_file($insert_id, 'accessory');
				$message = _l('fe_added_successfully', _l('fe_accessories'));
				set_alert('success', $message);
			}
			else{
				$message = _l('fe_added_fail', _l('fe_accessories'));
				set_alert('danger', $message);
			}
		} else {
			$success = $this->fixed_equipment_model->update_accessories($data);
			if($success == 1){
				$message = _l('fe_quantity_not_valid', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 2){
				$message = _l('fe_this_accessory_not_exist', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 3){
				$message = _l('fe_quantity_is_unknown', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 4) {
				$message = _l('fe_updated_successfully', _l('fe_accessories'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_no_data_changes', _l('fe_accessories'));
				set_alert('warning',$message);
			}
			fe_handle_item_file($data['id'], 'accessory');
		}
		redirect(admin_url('fixed_equipment/accessories'));
		die;
	}
	$this->load->model('staff_model');
	$data['title']  = _l('fe_accessories_management');
	$data['assets'] = $this->fixed_equipment_model->get_assets();
	$data['staffs'] = $this->staff_model->get();			
	$data['categories'] = $this->fixed_equipment_model->get_categories('','accessory');
	$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();

	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$this->load->view('accessories_management', $data);
}

/**
	 * accessories table
	 * @return json 
	 */
public function accessories_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}

			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];

			if(is_admin() || has_permission('fixed_equipment_accessories', '', 'view')){
				array_push($select, 'id');
			}

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];
			$manufacturer = $this->input->post('manufacturer');
			$category = $this->input->post('category');
			$location = $this->input->post('location');

			if(isset($manufacturer) && $manufacturer != ''){
				array_push($where, 'AND manufacturer_id = '.$manufacturer);
			}
			if(isset($category) && $category != ''){
				array_push($where, 'AND category_id = '.$category);
			}
			if(isset($location) && $location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}
			array_push($where, 'AND type = "accessory"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'category_id',
				'model_no',
				'manufacturer_id',  
				'asset_location',
				'quantity',
				'min_quantity',
				'unit_price',
				'checkin_out'     
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  



				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'accessory').'">';  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_accessories/'.$aRow['id']).'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_accessories', '', 'edit')){		
					$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || has_permission('fixed_equipment_accessories', '', 'delete')){		
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_accessories/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$min_quantity = $aRow['min_quantity'];  
				$avail = $aRow['quantity'] - $this->fixed_equipment_model->count_checkin_asset_by_parents($aRow['id']);
				$warning_class = '';
				$warning_attribute = '';
				if($avail < $min_quantity){
					$warning_class = 'text-danger bold';
					$warning_attribute = 'data-toggle="tooltip" data-placement="top" data-original-title="'._l('fe_the_quantity_has_reached_the_warning_level').'"';
				}
				$row[] = '<span class="text-nowrap '.$warning_class.'" '.$warning_attribute.'>'.$aRow['assets_name'].'</span>'.$_data;   

				$category_name = '';
				if(is_numeric($aRow['category_id']) && $aRow['category_id'] > 0){
					$data_category = $this->fixed_equipment_model->get_categories($aRow['category_id']);
					if($data_category){
						$category_name = $data_category->category_name;
					}
				}
				$row[] = $category_name;  

				$row[] = $aRow['model_no'];  

				$manufacturer_name = '';
				if(is_numeric($aRow['manufacturer_id']) && $aRow['manufacturer_id'] > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($aRow['manufacturer_id']);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = $manufacturer_name;  

				$location_name = '';
				if(is_numeric($aRow['asset_location']) && $aRow['asset_location'] > 0){
					$data_location = $this->fixed_equipment_model->get_locations($aRow['asset_location']);
					if($data_location){
						$location_name = $data_location->location_name;
					}
				}
				$row[] = $location_name;  
				$row[] = $aRow['quantity'];  
				$row[] = $min_quantity;  
				$row[] = '<span class="'.$warning_class.'" '.$warning_attribute.'>'.$avail.'</span>';  
				$row[] = app_format_money($aRow['unit_price'], $currency_name);  

				if(is_admin() || has_permission('fixed_equipment_accessories', '', 'create')){
					if($aRow['checkin_out'] == 1){
						$event_add = ' disabled';
						if($avail > 0){
							$event_add = ' data-asset_name="'.$aRow['assets_name'].'" onclick="check_out(this, '.$aRow['id'].')"';
						}
						$row[] = '<a class="btn btn-danger"'.$event_add.'>' . _l('fe_checkout') . '</a>';  			
					}
				}

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* get modal content accessories
* @param  integer $id
* @return integer     
*/
public function get_data_accessories_modal($id){
	$this->load->model('staff_model');
	$this->load->model('currencies_model');
	$this->load->model('staff_model');
	$data['accessory'] = $this->fixed_equipment_model->get_assets($id);
	$data['title']  = _l('fe_accessories_management');
	$data['assets'] = $this->fixed_equipment_model->get_assets();
	$data['staffs'] = $this->staff_model->get();			
	$data['categories'] = $this->fixed_equipment_model->get_categories('','accessory');
	$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();

	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	echo json_encode($this->load->view('includes/new_accessories_modal', $data, true));
}

/**
* delete accessories
* @param  integer $id 
*/
public function delete_accessories($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_assets($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_accessories')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_accessories')));					
		}
	}
	redirect(admin_url('fixed_equipment/accessories'));
}

/**
* consumables
*/
public function consumables(){
	if (!(has_permission('fixed_equipment_consumables', '', 'view_own') || has_permission('fixed_equipment_consumables', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$this->load->model('currencies_model');
	if ($this->input->post()) {
		$data             = $this->input->post();
		if ($data['id'] == '') {
			unset($data['id']);
			$insert_id = $this->fixed_equipment_model->add_consumables($data);
			if (is_numeric($insert_id)) {
				fe_handle_item_file($insert_id, 'consumable');
				$message = _l('fe_added_successfully', _l('fe_consumables'));
				set_alert('success', $message);
			}
			else{
				$message = _l('fe_added_fail', _l('fe_consumables'));
				set_alert('danger', $message);
			}
		} else {
			$success = $this->fixed_equipment_model->update_consumables($data);
			if($success == 1){
				$message = _l('fe_quantity_not_valid', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 2){
				$message = _l('fe_this_consumables_not_exist', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 3){
				$message = _l('fe_quantity_is_unknown', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 4) {
				$message = _l('fe_updated_successfully', _l('fe_accessories'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_no_data_changes', _l('fe_accessories'));
				set_alert('warning',$message);
			}
			fe_handle_item_file($data['id'], 'consumable');
		}
		redirect(admin_url('fixed_equipment/consumables'));
		die;
	}
	$this->load->model('staff_model');
	$data['title']  = _l('fe_consumables_management');
	$data['assets'] = $this->fixed_equipment_model->get_assets();
	$data['staffs'] = $this->staff_model->get();			
	$data['categories'] = $this->fixed_equipment_model->get_categories('','consumable');
	$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();

	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$this->load->view('consumables_management', $data);
}

/**
* consumables table
* @return json 
*/
public function consumables_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}

			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];

			if(is_admin() || has_permission('fixed_equipment_consumables', '', 'view')){
				array_push($select, 'id');
			}

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];

			$manufacturer = $this->input->post('manufacturer');
			$category = $this->input->post('category');
			$location = $this->input->post('location');

			if(isset($manufacturer) && $manufacturer != ''){
				array_push($where, 'AND manufacturer_id = '.$manufacturer);
			}
			if(isset($category) && $category != ''){
				array_push($where, 'AND category_id = '.$category);
			}
			if(isset($location) && $location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}
			array_push($where, 'AND type = "consumable"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'category_id',
				'model_no',
				'manufacturer_id',  
				'asset_location',
				'quantity',
				'min_quantity',
				'unit_price',
				'checkin_out'     
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  


				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'consumable').'">';  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_consumables/'.$aRow['id']).'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_consumables', '', 'edit')){
					$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || has_permission('fixed_equipment_consumables', '', 'delete')){
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_consumables/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$min_quantity = $aRow['min_quantity'];  
				$avail = $aRow['quantity'] - $this->fixed_equipment_model->count_checkin_asset_by_parents($aRow['id']);
				$warning_class = '';
				$warning_attribute = '';
				if($avail < $min_quantity){
					$warning_class = 'text-danger bold';
					$warning_attribute = 'data-toggle="tooltip" data-placement="top" data-original-title="'._l('fe_the_quantity_has_reached_the_warning_level').'"';
				}
				$row[] = '<span class="text-nowrap '.$warning_class.'" '.$warning_attribute.'>'.$aRow['assets_name'].'</span>'.$_data;   

				$category_name = '';
				if(is_numeric($aRow['category_id']) && $aRow['category_id'] > 0){
					$data_category = $this->fixed_equipment_model->get_categories($aRow['category_id']);
					if($data_category){
						$category_name = $data_category->category_name;
					}
				}
				$row[] = $category_name;  

				$row[] = $aRow['model_no'];  

				$manufacturer_name = '';
				if(is_numeric($aRow['manufacturer_id']) && $aRow['manufacturer_id'] > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($aRow['manufacturer_id']);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = $manufacturer_name;  

				$location_name = '';
				if(is_numeric($aRow['asset_location']) && $aRow['asset_location'] > 0){
					$data_location = $this->fixed_equipment_model->get_locations($aRow['asset_location']);
					if($data_location){
						$location_name = $data_location->location_name;
					}
				}
				$row[] = $location_name;  
				$row[] = $aRow['quantity'];  
				$row[] = $min_quantity;  
				$row[] = '<span class="'.$warning_class.'" '.$warning_attribute.'>'.$avail.'</span>';  
				$row[] = app_format_money($aRow['unit_price'], $currency_name);  

				if(is_admin() || has_permission('fixed_equipment_consumables', '', 'create')){
					if($aRow['checkin_out'] == 1){
						$event_add = ' disabled';
						if($avail > 0){
							$event_add = ' data-asset_name="'.$aRow['assets_name'].'" onclick="check_out(this, '.$aRow['id'].')"';
						}
						$row[] = '<a class="btn btn-danger"'.$event_add.'>' . _l('fe_checkout') . '</a>';  			
					}
				}

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* get modal content consumables
* @param  integer $id
* @return integer     
*/
public function get_data_consumables_modal($id){
	$this->load->model('staff_model');
	$this->load->model('currencies_model');
	$this->load->model('staff_model');
	$data['consumable'] = $this->fixed_equipment_model->get_assets($id);
	$data['assets'] = $this->fixed_equipment_model->get_assets();
	$data['staffs'] = $this->staff_model->get();			
	$data['categories'] = $this->fixed_equipment_model->get_categories('','consumable');
	$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	echo json_encode($this->load->view('includes/new_consumables_modal', $data, true));
}
/**
* delete consumables
* @param  integer $id 
*/
public function delete_consumables($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_assets($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_consumables')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_consumables')));					
		}
	}
	redirect(admin_url('fixed_equipment/consumables'));
}
/**
* components
*/
public function components(){
	if (!(has_permission('fixed_equipment_components', '', 'view_own') || has_permission('fixed_equipment_components', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$this->load->model('currencies_model');
	if ($this->input->post()) {
		$data             = $this->input->post();
		if ($data['id'] == '') {
			unset($data['id']);
			$insert_id = $this->fixed_equipment_model->add_components($data);
			if (is_numeric($insert_id)) {
				fe_handle_item_file($insert_id, 'component');
				$message = _l('fe_added_successfully', _l('fe_components'));
				set_alert('success', $message);
			}
			else{
				$message = _l('fe_added_fail', _l('fe_components'));
				set_alert('danger', $message);
			}
		} else {
			$success = $this->fixed_equipment_model->update_components($data);
			if ($success == 1) {
				$message = _l('fe_updated_successfully', _l('fe_components'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_no_data_changes', _l('fe_components'));
				set_alert('warning',$message);
			}
			fe_handle_item_file($data['id'], 'component');
		}
		redirect(admin_url('fixed_equipment/components'));
		die;
	}
	$this->load->model('staff_model');
	$data['title']  = _l('fe_components_management');
	$data['assets'] = $this->fixed_equipment_model->get_assets('', 'asset');
	$data['categories'] = $this->fixed_equipment_model->get_categories('','component');
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();

	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$this->load->view('components_management', $data);
}

/**
* components table
* @return json 
*/
public function components_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];
			if(is_admin() || has_permission('fixed_equipment_components', '', 'view')){
				array_push($select, 'id');
			}
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];

			$category = $this->input->post('category');
			$location = $this->input->post('location');
			if(isset($category) && $category != ''){
				array_push($where, 'AND category_id = '.$category);
			}
			if(isset($location) && $location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}

			array_push($where, 'AND type = "component"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'category_id',
				'series',
				'manufacturer_id',  
				'asset_location',
				'quantity',
				'min_quantity',
				'unit_price',
				'order_number',
				'date_buy',
				'checkin_out'     
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  				
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_components/'.$aRow['id']).'">' . _l('fe_view') . '</a>';

				if(is_admin() || has_permission('fixed_equipment_components', '', 'edit')){
					$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || has_permission('fixed_equipment_components', '', 'delete')){
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_components/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}

				$_data .= '</div>'; 

				$avail = $aRow['quantity'] - $this->fixed_equipment_model->count_checkin_component_by_parents($aRow['id']);
				$min_quantity = $aRow['min_quantity'];  

				$warning_class = '';
				$warning_attribute = '';
				if($avail < $min_quantity){
					$warning_class = 'text-danger bold';
					$warning_attribute = 'data-toggle="tooltip" data-placement="top" data-original-title="'._l('fe_the_quantity_has_reached_the_warning_level').'"';
				}
				$row[] = '<span class="text-nowrap '.$warning_class.'" '.$warning_attribute.'>'.$aRow['assets_name'].'</span>'.$_data;  

				$row[] = $aRow['series'];  

				$category_name = '';
				if(is_numeric($aRow['category_id']) && $aRow['category_id'] > 0){
					$data_category = $this->fixed_equipment_model->get_categories($aRow['category_id']);
					if($data_category){
						$category_name = $data_category->category_name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$category_name.'</span>';  
				$remain = 0;
				$row[] = $aRow['quantity'];  
				$row[] = '<span class="'.$warning_class.'" '.$warning_attribute.'>'.$avail.'</span>';  
				$row[] = $min_quantity;  



				$location_name = '';
				if(is_numeric($aRow['asset_location']) && $aRow['asset_location'] > 0){
					$data_location = $this->fixed_equipment_model->get_locations($aRow['asset_location']);
					if($data_location){
						$location_name = $data_location->location_name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$location_name.'</span>';  
				$row[] = $aRow['order_number'];  
				$row[] = _d($aRow['date_buy']);  
				$row[] = app_format_money($aRow['unit_price'], $currency_name);  
				if(is_admin() || has_permission('fixed_equipment_components', '', 'create')){
					if($aRow['checkin_out'] == 1){
						$event_add = ' disabled';
						if($avail > 0){
							$event_add = ' data-asset_name="'.$aRow['assets_name'].'" onclick="check_out(this, '.$aRow['id'].')"';
						}
						$row[] = '<a class="btn btn-danger"'.$event_add.'>' . _l('fe_checkout') . '</a>';  			
					}
				}
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* get modal content components
* @param  integer $id
* @return integer     
*/
public function get_data_components_modal($id){
	$this->load->model('staff_model');
	$this->load->model('currencies_model');
	$this->load->model('staff_model');
	$data['component'] = $this->fixed_equipment_model->get_assets($id);
	$data['assets'] = $this->fixed_equipment_model->get_assets();
	$data['categories'] = $this->fixed_equipment_model->get_categories('','component');
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	echo json_encode($this->load->view('includes/new_components_modal', $data, true));
}
/**
* delete components
* @param  integer $id 
*/
public function delete_components($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_assets($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_components')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_components')));					
		}
	}
	redirect(admin_url('fixed_equipment/components'));
}

/**
* predefined_kits
*/
public function predefined_kits(){
	if (!(has_permission('fixed_equipment_predefined_kits', '', 'view_own') || has_permission('fixed_equipment_predefined_kits', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$this->load->model('currencies_model');
	if ($this->input->post()) {
		$data             = $this->input->post();
		if ($data['id'] == '') {
			unset($data['id']);
			$insert_id = $this->fixed_equipment_model->add_predefined_kits($data);
			if($insert_id == 0){
				$message = _l('fe_added_fail', _l('fe_predefined_kits'));
				set_alert('danger', $message);
			}
			elseif($insert_id == -1){
				$message = _l('fe_the_name_has_already_been_taken', _l('fe_predefined_kits'));
				set_alert('danger', $message);
			}
			else {
				$message = _l('fe_added_successfully', _l('fe_predefined_kits'));
				set_alert('success', $message);
			}
		} else {
			$success = $this->fixed_equipment_model->update_predefined_kits($data);
			if($success == 0){
				$message = _l('fe_no_data_changes', _l('fe_predefined_kits'));
				set_alert('warning',$message);
			}
			elseif($success == -1){
				$message = _l('fe_the_name_has_already_been_taken', _l('fe_predefined_kits'));
				set_alert('danger', $message);
			}
			else {
				$message = _l('fe_updated_successfully', _l('fe_predefined_kits'));
				set_alert('success',$message);
			}
		}
		redirect(admin_url('fixed_equipment/predefined_kits'));
		die;
	}
	$this->load->model('staff_model');
	$data['title']  = _l('fe_predefined_kits_management');
	$data['staffs'] = $this->staff_model->get();			
	$data['assets'] = $this->fixed_equipment_model->get_assets();
	$data['categories'] = $this->fixed_equipment_model->get_categories('','component');
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();

	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$this->load->view('predefined_kits_management', $data);
}

/**
* predefined_kits table
* @return json 
*/
public function predefined_kits_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}

			$select = [
				'id',
				'id'
			];
			if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'view')){
				array_push($select, 'id');
			}
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];
			array_push($where, 'AND type = "predefined_kit"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'checkin_out'     
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_predefined_kits/'.$aRow['id']).'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'edit')){
					$_data .= ' | <a href="javascript:void(0)" data-assets_name="'.$aRow['assets_name'].'" onclick="edit(this,'.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'delete')){
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_predefined_kits/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$row[] = $aRow['assets_name'].$_data;   
				if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'create')){
					if($aRow['checkin_out'] == 2){
						$row[] = '<a class="btn btn-primary" data-asset_name="'.$aRow['assets_name'].'" onclick="check_in(this, '.$aRow['id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					else{
						$row[] = '<a class="btn btn-danger" data-asset_name="'.$aRow['assets_name'].'" onclick="check_out(this, '.$aRow['id'].')" >' . _l('fe_checkout') . '</a>';  			
					}
				}

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* get modal content predefined_kits
* @param  integer $id
* @return integer     
*/
public function get_data_predefined_kits_modal($id){
	$this->load->model('staff_model');
	$this->load->model('currencies_model');
	$this->load->model('staff_model');
	$data['component'] = $this->fixed_equipment_model->get_assets($id);
	$data['assets'] = $this->fixed_equipment_model->get_assets();
	$data['categories'] = $this->fixed_equipment_model->get_categories('','component');
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	echo json_encode($this->load->view('includes/new_predefined_kits_modal', $data, true));
}
/**
* delete predefined_kits
* @param  integer $id 
*/
public function delete_predefined_kits($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_assets($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_predefined_kits')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_predefined_kits')));					
		}
	}
	redirect(admin_url('fixed_equipment/predefined_kits'));
}

/**
* detail predefined_kits
*/
public function detail_predefined_kits($id){
	$this->load->model('currencies_model');
	$data['id'] = $id;
	$data['models'] = $this->fixed_equipment_model->get_models();
	$data['assets'] = $this->fixed_equipment_model->get_assets($id);
	if($data['assets']){
		$data['title']  = $data['assets']->assets_name;
	}
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$this->load->view('detail_predefined_kits', $data);
}
/**
* detail predefined_kits
*/
public function add_model_predefined_kits(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$id = $data['parent_id'];
		if ($data['id'] == '') {
			unset($data['id']);
			$insert_id = $this->fixed_equipment_model->add_model_predefined_kits($data);
			if(is_numeric($insert_id) && $insert_id > 0){
				$message = _l('fe_added_successfully', _l('fe_models'));
				set_alert('success', $message);
			}
			elseif(is_numeric($insert_id) && $insert_id == 0) {
				$message = _l('fe_added_fail', _l('fe_models'));
				set_alert('danger', $message);
			}
			else{
				$message = _l('fe_the_model_has_already_been_taken', _l('fe_models'));
				set_alert('danger', $message);
			}
		} else {
			$success = $this->fixed_equipment_model->update_model_predefined_kits($data);
			if($success == true){
				$message = _l('fe_updated_successfully', _l('fe_predefined_kits'));
				set_alert('success',$message);
			}
			else {
				$message = _l('fe_no_data_changes', _l('fe_predefined_kits'));
				set_alert('warning',$message);
			}
		}
		redirect(admin_url('fixed_equipment/detail_predefined_kits/'.$id));
		die;
	}
}
/**
* model predefined kits table
*/
public function model_predefined_kits_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$parent_id = $this->input->post('id');
			$select = [
				'id',
				'id',
				'id'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_model_predefined_kits';
			$join         = [];
			array_push($where, 'AND parent_id = '.$parent_id);

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'model_id',
				'quantity'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$_data = '';
				$_data .= '<div class="row-options">';
				if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'edit')){
					$_data .= '<a href="javascript:void(0)" data-id="' . $aRow['id'] . '" data-model_id="' . $aRow['model_id'] . '" data-quantity="' . $aRow['quantity'] . '" onclick="edit(this); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || (has_permission('fixed_equipment_predefined_kits', '', 'edit') && has_permission('fixed_equipment_predefined_kits', '', 'delete'))){
					$_data .= ' | ';
				}
				if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'delete')){
					$_data .= '<a href="'.admin_url('fixed_equipment/delete_model_predefined_kits/'.$parent_id.'/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 
				$model_name = '';
				if(is_numeric($aRow['model_id']) && $aRow['model_id'] > 0){
					$data_model = $this->fixed_equipment_model->get_models($aRow['model_id']);
					if($data_model){
						$model_name = $data_model->model_name;
					}
				}
				$row[] = $model_name.$_data;   
				$row[] = $aRow['quantity'];

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* delete model predefined_kits
* @param  integer $id 
*/
public function delete_model_predefined_kits($parent_id, $id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_model_predefined_kits($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully'));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail'));					
		}
	}
	redirect(admin_url('fixed_equipment/detail_predefined_kits/'.$parent_id));
}

/**
* check in accessories
* @return  
*/
public  function check_in_accessories(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$id = $data['id'];
		$redirect_detailt_page = 0;
		if(isset($data['detailt_page'])){
			$redirect_detailt_page = $data['detailt_page'];
			unset($data['detailt_page']);
		}
		$result = $this->fixed_equipment_model->check_in_accessories($data);
		if(is_numeric($result)){
			if($result == -1){
				set_alert('danger', _l('fe_this_accessory_has_been_checkout_for_this_user', _l('fe_accessories')));					
			}
			elseif($result == 0){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_accessories')));					
			}
			else{
				set_alert('success', _l('fe_checkout_successfully', _l('fe_accessories')));
			}
			if($redirect_detailt_page == 0){
				redirect(admin_url('fixed_equipment/accessories'));
			}
			else{
				redirect(admin_url('fixed_equipment/detail_accessories/'.$data['item_id']));
			}
		}
		else{
			if($result == true){
				set_alert('success', _l('fe_checkin_successfully', _l('fe_accessories')));
			}
			else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_accessories')));					
			}
			redirect(admin_url('fixed_equipment/detail_accessories/'.$data['item_id']));
		}
	}
}
/**
* detail accessories
* @param  integer $id 
* @return integer     
*/
public function detail_accessories($id){
	$data['redirect'] = $this->input->get('re');
	$data['title']  = '';
	$data_asset = $this->fixed_equipment_model->get_assets($id);
	if($data_asset){
		$data['title'] = $data_asset->assets_name.''.($data_asset->model_no != '' ? ' ('.$data_asset->model_no.')' : '');
		$data['asset_name'] = $data_asset->assets_name;
		$quantity = $data_asset->quantity;
		$total_checkout = $this->fixed_equipment_model->count_checkin_asset_by_parents($id);
		$data['allow_checkout'] = (($quantity - $total_checkout) > 0);
		$data['id'] = $id;
		$data['staffs'] = $this->staff_model->get();			
	}
	else{
		redirect(admin_url('fixed_equipment/accessories'));
	}
	$this->load->view('detail_accessories', $data);
}
/**
* detail accessories table
*/
public function detail_accessories_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$item_id =  $this->input->post('parent_id');
			$select = [
				'id',
				'id',
				'id',
				'id'
			];
			if(is_admin() || has_permission('fixed_equipment_accessories', '', 'view')){
				array_push($select, 'id');
			}
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_checkin_assets';
			$join         = [];
			array_push($where, 'AND item_id = '.$item_id.' AND status = 2');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'staff_id',
				'date_creator',
				'status',
				'notes'
			]);

			$assets_name = '';
			$data_assets = $this->fixed_equipment_model->get_assets($item_id);
			if($data_assets){
				$assets_name = $data_assets->assets_name;
			}

			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$row[] = get_staff_full_name($aRow['staff_id']);   
				$row[] = $aRow['notes'];
				$row[] = _d($aRow['date_creator']);
				if(is_admin() || has_permission('fixed_equipment_accessories', '', 'create')){
					$button = '';
					if($aRow['status'] == 2){
						$button = '<a class="btn btn-primary" data-asset_name="'.$assets_name.'" onclick="check_in(this, '.$aRow['id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					$row[] = $button;
				}
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}


/**
* check in consumables
* @return  
*/
public  function check_in_consumables(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$id = $data['id'];
		$redirect_detailt_page = 0;
		if(isset($data['detailt_page'])){
			$redirect_detailt_page = $data['detailt_page'];
			unset($data['detailt_page']);
		}
		$result = $this->fixed_equipment_model->check_in_consumables($data);
		if(is_numeric($result)){
			if($result == -1){
				set_alert('danger', _l('fe_this_consumables_has_been_checkout_for_this_user', _l('fe_consumables')));					
			}
			elseif($result == 0){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_consumables')));					
			}
			else{
				set_alert('success', _l('fe_checkout_successfully', _l('fe_consumables')));
			}
			if($redirect_detailt_page == 0){
				redirect(admin_url('fixed_equipment/consumables'));
			}
			else{
				redirect(admin_url('fixed_equipment/detail_consumables/'.$data['item_id']));
			}
		}
		else{
			if($result == true){
				set_alert('success', _l('fe_checkin_successfully', _l('fe_consumables')));
			}
			else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_consumables')));					
			}
			redirect(admin_url('fixed_equipment/detail_consumables/'.$data['item_id']));
		}
	}
}
/**
* detail consumables
* @param  integer $id 
* @return integer     
*/
public function detail_consumables($id){
	$data['redirect'] = $this->input->get('re');
	$data['title']  = '';
	$data_asset = $this->fixed_equipment_model->get_assets($id);
	if($data_asset){
		$data['title'] = $data_asset->assets_name.''.($data_asset->model_no != '' ? ' ('.$data_asset->model_no.')' : '');
		$data['asset_name'] = $data_asset->assets_name;
		$quantity = $data_asset->quantity;
		$total_checkout = $this->fixed_equipment_model->count_checkin_asset_by_parents($id);
		$data['allow_checkout'] = (($quantity - $total_checkout) > 0);
		$data['id'] = $id;
		$data['staffs'] = $this->staff_model->get();			
	}
	else{
		redirect(admin_url('fixed_equipment/consumables'));
	}
	$this->load->view('detail_consumables', $data);
}
/**
* detail consumables table
*/
public function detail_consumables_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$item_id =  $this->input->post('parent_id');
			$select = [
				'id',
				'id',
				'id',
				'id'
			];

			if(is_admin() || has_permission('fixed_equipment_consumables', '', 'view')){
				array_push($select, 'id');
			}

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_checkin_assets';
			$join         = ['LEFT JOIN '.db_prefix() . 'staff ON '.db_prefix() . 'staff.staffid = '.db_prefix() . 'fe_checkin_assets.staff_id'];
			array_push($where, 'AND item_id = '.$item_id.' AND status = 2');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix() . 'fe_checkin_assets.id',
				db_prefix() . 'fe_checkin_assets.staff_id',
				db_prefix() . 'fe_checkin_assets.date_creator',
				db_prefix() . 'fe_checkin_assets.status',
				db_prefix() . 'staff.lastname',
				db_prefix() . 'staff.firstname',
				'notes'
			]);

			$assets_name = '';
			$data_assets = $this->fixed_equipment_model->get_assets($item_id);
			if($data_assets){
				$assets_name = $data_assets->assets_name;
			}

			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  

				$row[] = $aRow['firstname'].' '.$aRow['lastname'];   


				$row[] = $aRow['notes'];
				$row[] = _d($aRow['date_creator']);
				if(is_admin() || has_permission('fixed_equipment_consumables', '', 'create')){
					$button = '';
					if($aRow['status'] == 2){
						$button = '<a class="btn btn-primary" data-asset_name="'.$assets_name.'" onclick="check_in(this, '.$aRow['id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					$row[] = $button;
				}
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* check in components
* @return  
*/
public  function check_in_components(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$id = $data['id'];
		$redirect_detailt_page = 0;
		if(isset($data['detailt_page'])){
			$redirect_detailt_page = $data['detailt_page'];
			unset($data['detailt_page']);
		}
		$result = $this->fixed_equipment_model->check_in_components($data);
		if(is_numeric($result)){
			if($result == -1){
				set_alert('danger', _l('fe_the_current_quantity_is_not_enough_for_checkout', _l('fe_components')));					
			}
			elseif($result == 0){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_components')));					
			}
			else{
				set_alert('success', _l('fe_checkout_successfully', _l('fe_components')));
			}
			if($redirect_detailt_page == 0){
				redirect(admin_url('fixed_equipment/components'));
			}
			else{
				redirect(admin_url('fixed_equipment/detail_components/'.$data['item_id']));
			}
		}
		else{
			if($result == true){
				set_alert('success', _l('fe_checkin_successfully', _l('fe_components')));
			}
			elseif($result == false){
				set_alert('danger', _l('fe_quantity_not_valid', _l('fe_components')));					
			}
			else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_components')));					
			}
			redirect(admin_url('fixed_equipment/detail_components/'.$data['item_id']));
		}
	}
}
/**
* detail components
* @param  integer $id 
* @return integer     
*/
public function detail_components($id){
	$data['redirect'] = $this->input->get('re');
	$data['title']  = '';
	$data_asset = $this->fixed_equipment_model->get_assets($id);
	if($data_asset){
		$data['title'] = $data_asset->assets_name.''.($data_asset->model_no != '' ? ' ('.$data_asset->model_no.')' : '');
		$data['asset_name'] = $data_asset->assets_name;
		$quantity = $data_asset->quantity;
		$total_checkout = $this->fixed_equipment_model->count_checkin_component_by_parents($id);
		$data['allow_checkout'] = (($quantity - $total_checkout) > 0);
		$data['id'] = $id;
		$data['staffs'] = $this->staff_model->get();	
		$data['assets'] = $this->fixed_equipment_model->get_assets('', 'asset');		
	}
	else{
		redirect(admin_url('fixed_equipment/components'));
	}
	$this->load->view('detail_components', $data);
}
/**
* detail components table
*/
public function detail_components_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$item_id =  $this->input->post('parent_id');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id'
			];
			if(is_admin() || has_permission('fixed_equipment_components', '', 'view')){
				array_push($select, 'id');
			}
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_checkin_assets';
			$join         = [];
			array_push($where, 'AND item_id = '.$item_id.' AND status = 2');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'asset_id',
				'quantity',
				'date_creator',
				'status',
				'notes'
			]);

			$assets_name = '';
			$data_assets = $this->fixed_equipment_model->get_assets($item_id);
			if($data_assets){
				$assets_name = $data_assets->assets_name;
			}

			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id']; 
				$assets_name = '';
				$data_asset = $this->fixed_equipment_model->get_assets($aRow['asset_id']); 
				if($data_asset){
					if($data_asset->assets_name != '' && $data_asset->series != ''){
						$assets_name = $data_asset->assets_name.' ('.$data_asset->series.')';
					}
					if($data_asset->assets_name == '' && $data_asset->series != ''){
						$assets_name = $data_asset->series;
					}
				}
				$row[] = $assets_name; 
				$row[] = $aRow['quantity'];
				$row[] = $aRow['notes'];
				$row[] = _d($aRow['date_creator']);
				if(is_admin() || has_permission('fixed_equipment_components', '', 'create')){
					$button = '';
					if($aRow['status'] == 2){
						$button = '<a class="btn btn-primary" data-asset_name="'.$assets_name.'" onclick="check_in(this, '.$aRow['id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					$row[] = $button;
				}
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* check in predefined_kits
* @return  
*/
public  function check_in_predefined_kits(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$id = $data['id'];
		$redirect_detailt_page = 0;
		if(isset($data['detailt_page'])){
			$redirect_detailt_page = $data['detailt_page'];
			unset($data['detailt_page']);
		}
		$result = $this->fixed_equipment_model->check_in_predefined_kits($data);
		if(is_object($result)){
			$status = 'success';
			if($result->status == 0 || $result->status == 1 || $result->status == 3){
				$status = 'danger';
			}
			set_alert($status, $result->msg);
			if($redirect_detailt_page == 0){
				redirect(admin_url('fixed_equipment/predefined_kits'));
			}
			else{
				redirect(admin_url('fixed_equipment/detail_predefined_kits/'.$data['item_id']));
			}
		}
		else{
			if($result == true){
				set_alert('success', _l('fe_checkin_successfully', _l('fe_predefined_kits')));
			}
			else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_predefined_kits')));					
			}
			redirect(admin_url('fixed_equipment/detail_predefined_kits/'.$data['item_id']));
		}
	}
}
/**
* table asset licenses table
*/
public function table_asset_licenses_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$id = $this->input->post('id');
			$select = [
				'license_id',
				'id',         
				'id'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_seats';
			$join         = [];
			array_push($where, 'AND status = 2 AND '.db_prefix().'fe_seats.to = "asset" AND to_id = '.$id);

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'license_id',
				'status', 
				'id'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$license_name = '';
				$product_key = '';
				$data_licenses = $this->fixed_equipment_model->get_assets($aRow['license_id']);
				if($data_licenses){
					$license_name = $data_licenses->assets_name;
					$product_key = $data_licenses->product_key;
				}
				$row[] = $license_name;  
				$row[] = $product_key;  
				if(is_admin() || has_permission('fixed_equipment_licenses', '', 'create')){
					$row[] = '<a class="btn btn-primary" data-license_name="'.$license_name.'" onclick="check_in(this, '.$aRow['id'].')">'._l('fe_checkin').'</a>';  
				}
				else{
					$row[] = '';
				}
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* check in assets
* @return  
*/
public  function check_in_license_detail_asset(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$id = $data['id'];
		unset($data['id']);
		$result = $this->fixed_equipment_model->check_in_licenses($data);
		if($result > 0){
			set_alert('success', _l('fe_checkin_successfully', _l('fe_licenses')));
		}
		else{
			set_alert('danger', _l('fe_checkin_fail', _l('fe_licenses')));					
		}
		redirect(admin_url('fixed_equipment/detail_asset/'.$id.'?tab=licenses'));
	}
}

/**
* table asset component table
* @return json 
*/
public function table_asset_component_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$id = $this->input->post('id');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				'id',
				'id',
				'id'
			];


			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_checkin_assets';
			$join         = [];
			array_push($where, 'AND asset_id = '.$id.' AND status = 2');

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'item_id',
				'quantity'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];  
			foreach ($rResult as $aRow) {
				$row = [];
				$assets_name = '';
				$purchase_cost = 0;
				$data_assets = $this->fixed_equipment_model->get_assets($aRow['item_id']);
				if($data_assets){
					$assets_name = $data_assets->assets_name;
					$purchase_cost = $data_assets->unit_price;
				}
				$row[] = $assets_name;  
				$row[] = $aRow['quantity'];  
				$row[] = app_format_money($purchase_cost,$currency_name);  


				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* upload asset file
*/
public function upload_asset_file(){
	if ($this->input->post()) {
		$id = $this->input->post('id');
		$result = fe_handle_item_file($id, 'asset_files', strtotime(date('y-m-d')).'-');
		if($result > 0){
			set_alert('success', _l('fe_uploaded_successfully', _l('fe_assets')));
		}
		else{
			set_alert('danger', _l('fe_upload_fail', _l('fe_assets')));					
		}
		redirect(admin_url('fixed_equipment/detail_asset/'.$id.'?tab=files'));
	}
}

/**
* asset file table
* @return json 
*/
public function asset_files_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$id = $this->input->post('id');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id'
			];


			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'files';
			$join         = [];
			array_push($where, 'AND rel_id = '.$id.' AND rel_type = "asset_files"');

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'file_name',
				'dateadded',
				'filetype'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];  
			foreach ($rResult as $aRow) {
				$row = [];
				$image = '';
				if($aRow['filetype'] != ''){
					$type_split = explode('/', $aRow['filetype']);
					if(isset($type_split[0])){
						if($type_split[0] == 'image'){
							$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.site_url(FIXED_EQUIPMENT_IMAGE_UPLOADED_PATH.'asset_files/'.$id.'/'.$aRow['file_name']).'">';  
						}
					}
				}
				$row[] = $image;  

				$file_name = '<a href="'.site_url(FIXED_EQUIPMENT_PATH.'asset_files/'.$id.'/'.$aRow['file_name']).'" download>'.$aRow['file_name'].'</a>';
				$row[] = $file_name;  
				$row[] = $aRow['filetype'];  
				$row[] = _dt($aRow['dateadded']);  

				$action = '';
				$action .= '<a data-placement="top" data-toggle="tooltip" data-title="'._l('fe_delete').'" href="'.admin_url('fixed_equipment/delete_asset_file_item/'.$id.'/'.$aRow['id'].'/asset_files').'" class="btn btn-danger btn-icon _delete" data-original-title="" title="'._l('fe_delete').'"><i class="fa fa-remove"></i></a>';

				$action .= '<a data-placement="top" data-toggle="tooltip" data-title="'._l('fe_download').'" href="'.site_url(FIXED_EQUIPMENT_PATH.'asset_files/'.$id.'/'.$aRow['file_name']).'" class="btn btn-default btn-icon mleft10" data-original-title="" title="'._l('fe_download').'" download>
				<i class="fa fa-download"></i>
				</a>';

				$row[] = $action;  
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}


/**
* { delete file attachment }
*
* @param  $id     The identifier
*/
public function delete_asset_file_item($id, $file_id,$type)
{
	$this->load->model('misc_model');
	$file = $this->misc_model->get_file($file_id);
	$result = false;
	if ($file->staffid == get_staff_user_id() || is_admin()) {
		$result = html_entity_decode($this->fixed_equipment_model->delete_file_item($file_id,$type));
	} 
	if($result == true){
		set_alert('success', _l('fe_deleted_successfully'));
	}
	else{
		set_alert('danger', _l('fe_deleted_fail'));					
	}
	redirect(admin_url('fixed_equipment/detail_asset/'.$id.'?tab=files'));
}

/**
* upload license file
*/
public function upload_license_file(){
	if ($this->input->post()) {
		$id = $this->input->post('id');
		$result = fe_handle_item_file($id, 'license_files', strtotime(date('y-m-d')).'-');
		if($result > 0){
			set_alert('success', _l('fe_uploaded_successfully', _l('fe_licenses')));
		}
		else{
			set_alert('danger', _l('fe_upload_fail', _l('fe_licenses')));					
		}
		redirect(admin_url('fixed_equipment/detail_licenses/'.$id.'?tab=files'));
	}
}
/**
* license file table
* @return json 
*/
public function license_files_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$id = $this->input->post('id');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id'
			];


			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'files';
			$join         = [];
			array_push($where, 'AND rel_id = '.$id.' AND rel_type = "license_files"');

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'file_name',
				'dateadded',
				'filetype'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];  
			foreach ($rResult as $aRow) {
				$row = [];
				$image = '';
				if($aRow['filetype'] != ''){
					$type_split = explode('/', $aRow['filetype']);
					if(isset($type_split[0])){
						if($type_split[0] == 'image'){
							$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.site_url(FIXED_EQUIPMENT_IMAGE_UPLOADED_PATH.'license_files/'.$id.'/'.$aRow['file_name']).'">';  
						}
					}
				}
				$row[] = $image;  

				$file_name = '<a href="'.site_url(FIXED_EQUIPMENT_PATH.'license_files/'.$id.'/'.$aRow['file_name']).'" download>'.$aRow['file_name'].'</a>';
				$row[] = $file_name;  
				$row[] = $aRow['filetype'];  
				$row[] = _dt($aRow['dateadded']);  

				$action = '';
				$action .= '<a data-placement="top" data-toggle="tooltip" data-title="'._l('fe_delete').'" href="'.admin_url('fixed_equipment/delete_license_file_item/'.$id.'/'.$aRow['id'].'/license_files').'" class="btn btn-danger btn-icon _delete" data-original-title="" title="'._l('fe_delete').'"><i class="fa fa-remove"></i></a>';

				$action .= '<a data-placement="top" data-toggle="tooltip" data-title="'._l('fe_download').'" href="'.site_url(FIXED_EQUIPMENT_PATH.'license_files/'.$id.'/'.$aRow['file_name']).'" class="btn btn-default btn-icon mleft10" data-original-title="" title="'._l('fe_download').'" download>
				<i class="fa fa-download"></i>
				</a>';

				$row[] = $action;  
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}


/**
* { delete file attachment }
*
* @param  $id     The identifier
*/
public function delete_license_file_item($id, $file_id,$type)
{
	$this->load->model('misc_model');
	$file = $this->misc_model->get_file($file_id);
	$result = false;
	if ($file->staffid == get_staff_user_id() || is_admin()) {
		$result = html_entity_decode($this->fixed_equipment_model->delete_file_item($file_id,$type));
	} 
	if($result == true){
		set_alert('success', _l('fe_deleted_successfully'));
	}
	else{
		set_alert('danger', _l('fe_deleted_fail'));					
	}
	redirect(admin_url('fixed_equipment/detail_licenses/'.$id.'?tab=files'));
}

/**
* assets mantanances
*/
public function assets_maintenances(){
	if (!(has_permission('fixed_equipment_maintenances', '', 'view_own') || has_permission('fixed_equipment_maintenances', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	if ($this->input->post()) {
		$data  = $this->input->post();
		$insert_id = 0;
		if($data['id'] == ''){
			unset($data['id']);
			$insert_id = $this->fixed_equipment_model->add_assets_maintenances($data);
			if($insert_id > 0){
				set_alert('success', _l('fe_added_successfully', _l('fe_assets_maintenances')));
			}
			else{
				set_alert('danger', _l('fe_added_fail', _l('fe_assets_maintenances')));					
			}
		}
		else
		{
			$result = $this->fixed_equipment_model->update_assets_maintenances($data);
			if($result == true){
				set_alert('success', _l('fe_updated_successfully', _l('fe_assets_maintenances')));
			}
			else{
				set_alert('danger', _l('fe_no_data_changes', _l('fe_assets_maintenances')));					
			}
		}
		$redirect = $this->input->get('redirect');
		if($redirect != ''){
			$rel_type = $this->input->get('rel_type');
			$rel_id = $this->input->get('rel_id');
			if($rel_type != '' && is_numeric($rel_id)){
				if($rel_type == 'audit'){
					$this->fixed_equipment_model->update_audit_detail_item($data['asset_id'], $rel_id, ['maintenance_id' => $insert_id]);
				}
			}
			redirect(admin_url($redirect));
		}
		else{
			redirect(admin_url('fixed_equipment/assets_maintenances'));			
		}
	}

	$data['title']    = _l('fe_assets_maintenances');
	$this->load->model('currencies_model');
	$this->load->model('staff_model');
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
	$this->load->view('assets_maintenance_management', $data);
}


/**
* assets maintenances table
* @return json 
*/
public function assets_maintenances_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){

			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}

			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];


			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_asset_maintenances';
			$join         = [];

			$maintenance_type = $this->input->post("maintenance_type");
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");

			if($maintenance_type != ''){
				array_push($where, ' AND maintenance_type = "'.$maintenance_type.'"');
			}
			if($from_date != '' && $to_date == ''){
				$from_date = fe_format_date($from_date);
				array_push($where, ' AND date(start_date)="'.$from_date.'"');
			}
			if($from_date != '' && $to_date != ''){
				$from_date = fe_format_date($from_date);
				$to_date = fe_format_date($to_date);
				array_push($where, ' AND date(start_date) between "'.$from_date.'" AND "'.$to_date.'"');
			}

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'asset_id',
				'supplier_id',
				'maintenance_type',
				'title',
				'start_date',
				'completion_date',
				'cost',
				'notes',
				'date_creator',
				'warranty_improvement'
			]);

			$output  = $result['output'];
			$rResult = $result['rResult'];  
			foreach ($rResult as $aRow) {
				$row = [];


				$serial = '';
				$data_asset = $this->fixed_equipment_model->get_assets($aRow['asset_id']);
				if($data_asset){
					$serial = $data_asset->series;
				}

				$row[] = $aRow['id'];

				$_data = '';
					$_data .= '<div class="row-options">';
				if(is_admin() || has_permission('fixed_equipment_maintenances', '', 'edit')){
					$_data .= ' <a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(is_admin() || (has_permission('fixed_equipment_maintenances', '', 'edit') && has_permission('fixed_equipment_maintenances', '', 'delete'))){
					$_data .= ' | ';
				}
				if(is_admin() || has_permission('fixed_equipment_maintenances', '', 'delete')){
					$_data .= ' <a href="'.admin_url('fixed_equipment/delete_asset_maintenances/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
					$_data .= '</div>'; 
				$row[] = '<span class="text-nowrap">'.$this->fixed_equipment_model->get_asset_name($aRow['asset_id']).'</span>'.$_data;  
				$row[] = '<span class="text-nowrap">'.$serial.'</span>';  
				$data_location_asset = $this->fixed_equipment_model->get_asset_location_info($aRow['asset_id']);
				$row[] = '<span class="text-nowrap">'.$data_location_asset->curent_location.'</span>';  
				$row[] = _l('fe_'.$aRow['maintenance_type']);  
				$row[] = '<span class="text-nowrap">'.$aRow['title'].'</span>';  
				$row[] = '<span class="text-nowrap">'._d($aRow['start_date']).'</span>';  
				$row[] = '<span class="text-nowrap">'._d($aRow['completion_date']).'</span>';   
				$row[] = $aRow['notes']; 
				$warranty = ''; 
				$row[] = $warranty;  
				$row[] = app_format_money($aRow['cost'], $currency_name);  

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* delete asset maintenances
* @param  integer $id 
*/
public function delete_asset_maintenances($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_asset_maintenances($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_depreciations')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_depreciations')));					
		}
	}
	redirect(admin_url('fixed_equipment/assets_maintenances'));
}
/**
* get data assets maintenances
* @param  integer $id 
*/
public function get_data_assets_maintenances($id){
	$data_assets = $this->fixed_equipment_model->get_asset_maintenances($id);
	if($data_assets){
		$data_assets->completion_date = _d($data_assets->completion_date);
		$data_assets->start_date = _d($data_assets->start_date);
		$data_assets->cost = app_format_money($data_assets->cost,'');
	}
	echo json_encode($data_assets);
}

/**
* detail assets table
* @return json 
*/
public function detail_assets_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){

			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$id = $this->input->post('id');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];


			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_asset_maintenances';
			$join         = [];

			array_push($where, ' AND asset_id='.$id);

			$maintenance_type = $this->input->post("maintenance_type");
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");

			if($maintenance_type != ''){
				array_push($where, ' AND maintenance_type = "'.$maintenance_type.'"');
			}
			if($from_date != '' && $to_date == ''){
				$from_date = fe_format_date($from_date);
				array_push($where, ' AND date(start_date)="'.$from_date.'"');
			}
			if($from_date != '' && $to_date != ''){
				$from_date = fe_format_date($from_date);
				$to_date = fe_format_date($to_date);
				array_push($where, ' AND date(start_date) between "'.$from_date.'" AND "'.$to_date.'"');
			}

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'asset_id',
				'supplier_id',
				'maintenance_type',
				'title',
				'start_date',
				'completion_date',
				'cost',
				'notes',
				'date_creator',
				'warranty_improvement'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];  
			foreach ($rResult as $aRow) {
				$row = [];


				$serial = '';
				$data_asset = $this->fixed_equipment_model->get_assets($aRow['asset_id']);
				if($data_asset){
					$serial = $data_asset->series;
				}

				$row[] = $aRow['id'];

				$_data = '';
				$_data .= '<div class="row-options">';
				if(has_permission('fixed_equipment_maintenances', '', 'edit') || is_admin()){
					$_data .= '<a href="javascript:void(0)" onclick="edit_maintenance('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if((has_permission('fixed_equipment_maintenances', '', 'edit') && has_permission('fixed_equipment_maintenances', '', 'delete')) || is_admin()){
					$_data .= ' | ';
				}
				if(has_permission('fixed_equipment_maintenances', '', 'delete') || is_admin()){
					$_data .= '<a href="'.admin_url('fixed_equipment/delete_asset_maintenance_detail/'.$id.'/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$row[] = $this->fixed_equipment_model->get_asset_name($aRow['asset_id']).$_data; 
				$row[] = $serial;  
				$data_location_asset = $this->fixed_equipment_model->get_asset_location_info($aRow['asset_id']);
				$row[] = $data_location_asset->curent_location;  
				$row[] = _l('fe_'.$aRow['maintenance_type']);  
				$row[] = $aRow['title'];  
				$row[] = _d($aRow['start_date']);  
				$row[] = _d($aRow['completion_date']);  
				$row[] = $aRow['notes']; 
				$warranty = ''; 
				$row[] = $warranty;  
				$row[] = app_format_money($aRow['cost'], $currency_name);  

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* approval setting
* @param  string $id 
* @return redirect
*/
public function approver_setting($id = ''){
	if ($this->input->post()) {
		$data                = $this->input->post();
		$id = $data['approval_setting_id'];
		unset($data['approval_setting_id']);
		if ($id == '') {
			$id = $this->fixed_equipment_model->add_approval_process($data);
			if ($id > 0) {
				set_alert('success', _l('fe_added_successfully', _l('fe_approval_process')));
			}
			else{
				set_alert('danger', _l('fe_added_fail', _l('fe_approval_process')));
			}
		} else {
			$success = $this->fixed_equipment_model->update_approval_process($id, $data);
			if ($success) {
				set_alert('success', _l('fe_updated_successfully', _l('fe_approval_process')));
			}
			else{
				set_alert('danger', _l('fe_updated_fail', _l('fe_approval_process')));				
			}
		}
		redirect(admin_url('fixed_equipment/settings?tab=approval_settings'));
	}
}
/**
 * approve setting table
 */
public function approve_setting_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$select = [
				'name',         
				'related'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_approval_setting';
			$join         = [];

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',         
				'name',         
				'related'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_approve_setting/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>'; 

				$row[] = $aRow['name'].$_data;   
				$row[] = _l('fe_'.$aRow['related']);

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
 * delete approve setting
 * @param  integer $id 
 * @return integer     
 */
public function delete_approve_setting($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_approve_setting($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_approval_process')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_approval_process')));					
		}
	}
	redirect(admin_url('fixed_equipment/settings?tab=approval_settings'));
}
/**
 * get approve setting
 * @param  integer $id 
 * @return json     
 */
public function get_approve_setting($id){
	$data_setting = $this->fixed_equipment_model->get_approval_setting($id);
	$data_setting->notification_recipient = array_map('intval', explode(',', $data_setting->notification_recipient));
	echo json_encode([
		'success' => true,
		'data_setting' => $data_setting
	]);
	die();  
}
/**
* requested 
*/
public function requested(){
	if (!(has_permission('fixed_equipment_requested', '', 'view_own') || has_permission('fixed_equipment_requested', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$data['title']    = _l('fe_request_management');
	$this->load->model('staff_model');
	$data['staffs'] = $this->staff_model->get();
	$data['assets'] = $this->fixed_equipment_model->get_assets('','asset', true, true,'deployable');
	$this->load->view('requested_management', $data);
}

/**
* request table
* @return json 
*/
public function request_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id'
			];

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_checkin_assets';
			$join         = ['LEFT JOIN '.db_prefix().'fe_assets ON '.db_prefix().'fe_assets.id = '.db_prefix().'fe_checkin_assets.item_id'];

			$checkout_for = $this->input->post("checkout_for");
			$status = $this->input->post("status");
			$create_from_date = $this->input->post("create_from_date");
			$create_to_date = $this->input->post("create_to_date");
			if(has_permission('fixed_equipment_requested', '', 'view') || is_admin()){
				if(isset($checkout_for) && $checkout_for != ''){
					$list_checkout_for = (is_array($checkout_for) ? implode(',', $checkout_for) : '');
					if($list_checkout_for != ''){
						array_push($where, 'AND staff_id IN ('.$list_checkout_for.')');
					}
				}
			}
			else{
				array_push($where, 'AND staff_id = '.get_staff_user_id().'');
			}

			if($status != ''){
				if($status == 3){
					$status = 0;
				}
				array_push($where, 'AND '.db_prefix().'fe_checkin_assets.request_status = '.$status);
			}

			if($create_from_date != '' && $create_to_date != ''){
				$from_date = fe_format_date($create_from_date);
				$to_date = fe_format_date($create_to_date);
				array_push($where, 'AND (date('.db_prefix().'fe_checkin_assets.date_creator) between "'.$from_date.'" AND "'.$to_date.'")');
			}

			if($create_from_date == '' && $create_to_date != ''){
				$to_date = fe_format_date($create_to_date);
				array_push($where, 'AND date('.db_prefix().'fe_checkin_assets.date_creator) = "'.$to_date.'"');
			}

			if($create_from_date != '' && $create_to_date == ''){
				$from_date = fe_format_date($create_from_date);
				array_push($where, 'AND date('.db_prefix().'fe_checkin_assets.date_creator) = "'.$from_date.'"');
			}
			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.type = "checkout"');
			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.requestable = 1');

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_assets.assets_name',
				db_prefix().'fe_assets.series',
				db_prefix().'fe_assets.model_id',
				'request_title',
				'request_status',
				'staff_id',
				'checkout_to',
				'notes',
				db_prefix().'fe_checkin_assets.date_creator'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$_data = '';


				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_request/'.$aRow['id']).'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_requested', '', 'delete')){
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_request/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>';  

				$row[] = '<span class="text-nowrap">'.$aRow['request_title'].'</span>'.$_data; 
				$row[] = '<span class="text-nowrap">'.$aRow['assets_name'].'</span>'; 
				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 
				$row[] = $aRow['series'];  
				$row[] = '<span class="text-nowrap">'.get_staff_full_name($aRow['staff_id']).'</span>';  
				$row[] = $aRow['notes']; 
				$row[] = '<span class="text-nowrap">'._dt($aRow['date_creator']).'</span>'; 
				$status = '';
				if($aRow['request_status'] == 0){
					$status = '<span class="label label-primary">'._l('fe_new').'</span>';
				} 
				elseif($aRow['request_status'] == 1){
					$status = '<span class="label label-success">'._l('fe_approved').'</span>';
				}
				else{
					$status = '<span class="label label-danger">'._l('fe_rejected').'</span>';
				}
				$row[] = $status;  


				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* add new request 
*/
public function add_new_request(){
	if($this->input->post()){
		$data = $this->input->post();
		$insert_id = $this->fixed_equipment_model->add_new_request($data);
		if($insert_id){
			$staff_id = get_staff_user_id();
			$rel_type = 'checkout';
			$check_proccess = $this->fixed_equipment_model->get_approve_setting($rel_type, false);
			$process = '';
			if($check_proccess){
				if($check_proccess->choose_when_approving == 0){
					$this->fixed_equipment_model->send_request_approve($insert_id, $rel_type, $staff_id);
					$process = 'not_choose';
					set_alert('success', _l('fe_successful_submission_of_approval_request'));
				}else{
					$process = 'choose';
					set_alert('success', _l('fe_created_successfully'));
				}
			}else{
				// Auto checkout if not approve process
				$this->fixed_equipment_model->change_request_status($insert_id, 1);
				$data_checkout_log = $this->fixed_equipment_model->get_checkin_out_data($insert_id);
				if($data_checkout_log){
					// Change status to checkout and save request id
					$this->db->where('id', $data_checkout_log->item_id);
					$this->db->update(db_prefix().'fe_assets', ['checkin_out' => 2, 'checkin_out_id' => $insert_id]);
					$this->fixed_equipment_model->add_log($staff_id, $rel_type, $data_checkout_log->item_id, '', '', 'user', $data_checkout_log->staff_id, $data_checkout_log->notes);
				}
				$process = 'no_proccess';
				set_alert('success', _l('fe_checkout_successfully'));
			}
			redirect(admin_url('fixed_equipment/detail_request/'.$insert_id.'?process='.$process));
		}
		else{
			set_alert('danger', _l('fe_request_failed'));					
		}
	}
	redirect(admin_url('fixed_equipment/requested'));
}
/**
* detail request
* @param  integer $id 
*/
public function detail_request($id){
	$this->load->model('staff_model');
	$data_checkout_log = $this->fixed_equipment_model->get_checkin_out_data($id);
	if($data_checkout_log){
		$item_id = $data_checkout_log->item_id;
		$data['asset'] = $this->fixed_equipment_model->get_assets($item_id);
		$title = '';
		if($data['asset']){
			$data['model'] = $this->fixed_equipment_model->get_models($data['asset']->model_id);
			$title = $data['model']->model_name;
		}
		$data['staffs'] = $this->staff_model->get();
		$data['data_approve'] = $this->fixed_equipment_model->get_approval_details($id, 'checkout');
		$data['title'] = $title;
		$data['id'] = $id;
		$data['tab'] = $this->input->get('tab');


		$rel_type = 'checkout';
		$process = '';
		$check_proccess = $this->fixed_equipment_model->get_approve_setting($rel_type, false);
		if($check_proccess){
			if($check_proccess->choose_when_approving == 0){
				$process = 'not_choose';
			}else{
				$process = 'choose';
			}
		}else{
			$process = 'no_proccess';
		}
		$data['process'] = $process;
		$this->load->view('detail_request', $data);
	}
	else{
		redirect(admin_url('fixed_equipment/add_new_request'));
	}
}
	/**
	 * delete request
	 * @param  integer $id 
	 */
	public function delete_request($id){
		if($id != ''){
			$result =  $this->fixed_equipment_model->delete_request($id);
			if($result){
				set_alert('success', _l('fe_deleted_successfully', _l('fe_request')));
			}
			else{
				set_alert('danger', _l('fe_deleted_fail', _l('fe_request')));					
			}
		}
		redirect(admin_url('fixed_equipment/requested'));
	}

/**
* approve request form
* @return json 
*/
public function approve_request_form(){
	$data = $this->input->post();
	$data['date'] = date('Y-m-d');
	$data['staffid'] = get_staff_user_id();
	$success = $this->fixed_equipment_model->change_approve($data);
	$message = '';
	if($success == true){
		if($data['approve'] == 1){
			$message = _l('fe_approved');
		}
		else{
			$message = _l('fe_rejected');			
		}
	}
	else{
		$message = _l('fe_approve_fail');
	}
	echo json_encode([
		'success' => $success,
		'message' => $message,
	]);
	die();      
}

/**
* choose approver request
* @return json 
*/
public function choose_approver_request(){
	$data = $this->input->post();
	$success = false;
	$message = '';
	if($data['id']){
		$insert_id = $this->fixed_equipment_model->add_approver_choosee_when_approve($data['id'], 'checkout', $data['approver']);
		if(is_numeric($insert_id) && $insert_id > 0){
			$success = true;
			$message = _l('fe_successful_submission_of_approval_request');
		}
		else{
			$success = false;
			$message = _l('fe_submit_approval_request_failed');
		}
	}
	echo json_encode([
		'success' => $success,
		'message' => $message
	]);
}

/**
* assets detail mantanances
*/
public function assets_detail_maintenances(){
	if ($this->input->post()) {
		$data  = $this->input->post();
		$id = $data['id'];
		if($data['maintenance_id'] == ''){
			unset($data['maintenance_id']);
			unset($data['id']);
			$result = $this->fixed_equipment_model->add_assets_maintenances($data);
			if($result > 0){
				set_alert('success', _l('fe_added_successfully', _l('fe_assets_maintenances')));
			}
			else{
				set_alert('danger', _l('fe_added_fail', _l('fe_assets_maintenances')));					
			}
		}
		else
		{
			$data['id'] = $data['maintenance_id'];
			unset($data['maintenance_id']);
			$result = $this->fixed_equipment_model->update_assets_maintenances($data);
			if($result == true){
				set_alert('success', _l('fe_updated_successfully', _l('fe_assets_maintenances')));
			}
			else{
				set_alert('danger', _l('fe_no_data_changes', _l('fe_assets_maintenances')));					
			}
		}
		redirect(admin_url('fixed_equipment/detail_asset/'.$id.'?tab=maintenances'));
	}
}


/**
* asset checkout table
* @return json 
*/
public function asset_checkout_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){

			$id = $this->input->post("id");
			$model = $this->input->post("model");
			$status = $this->input->post("status");
			$supplier = $this->input->post("supplier");
			$location = $this->input->post("location");



			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				db_prefix().'fe_assets.id',
				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description',
				'requestable',
				'qr_code',
				db_prefix().'fe_assets.date_creator',
				'updated_at',
				'checkin_out',
				'status',

				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator'

			];


			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = ['LEFT JOIN '.db_prefix().'fe_models ON '.db_prefix().'fe_models.id = '.db_prefix().'fe_assets.model_id'];

			$list_id = '';
			$list_checkout_assets = $this->fixed_equipment_model->get_list_checkout_assets($id);
			foreach ($list_checkout_assets as $value) {
				$list_id .= $value['item_id'].',';
			}
			if($list_id != ''){
				$list_id = rtrim($list_id,',');
				array_push($where, 'AND '.db_prefix().'fe_assets.id in ('.$list_id.')');
			}
			else{
				array_push($where, 'AND '.db_prefix().'fe_assets.id = 0');
			}


			array_push($where, 'AND type = "asset"');

			if($model != ''){
				array_push($where, 'AND '.db_prefix().'fe_assets.model_id = '.$model);
			}	
			if($status != ''){
				array_push($where, 'AND status = '.$status);
			}
			if($supplier != ''){
				array_push($where, 'AND supplier_id = '.$supplier);
			}
			if($location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_assets.id',
				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description',
				'requestable',
				'qr_code',
				db_prefix().'fe_assets.date_creator',
				'updated_at',
				'checkin_out',
				db_prefix().'fe_models.model_name',
				db_prefix().'fe_models.model_no',
				'status'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  

				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_asset/'.$aRow[db_prefix().'fe_assets.id'].'?tab=details').'">' . _l('fe_view') . '</a>';
				if(has_permission('fixed_equipment_assets', '', 'edit') || is_admin()){
					$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow[db_prefix().'fe_assets.id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				}
				if(has_permission('fixed_equipment_assets', '', 'delete') || is_admin()){
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_assets/'.$aRow[db_prefix().'fe_assets.id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$row[] = '<span class="text-nowrap">'.$aRow['assets_name'].'</span>'.$_data;   

				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 

				$row[] = $aRow['series'];  

				$category_id = 0;
				$manufacturer_id = 0;
				if(is_numeric($aRow['model_id']) > 0){
					$data_model = $this->fixed_equipment_model->get_models($aRow['model_id']);
					if($data_model){
						$category_id = $data_model->category;
						$manufacturer_id = $data_model->manufacturer;
					}
				}
				$row[] = '<span class="text-nowrap">'.$aRow['model_name'].'</span>';  
				$row[] = $aRow['model_no'];  

				$category_name = '';
				if(is_numeric($category_id) && $category_id > 0){
					$data_cat = $this->fixed_equipment_model->get_categories($category_id);
					if($data_cat){
						$category_name = $data_cat->category_name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$category_name.'</span>';  

				$status_name = '';
				if(is_numeric($aRow['status']) && $aRow['status'] > 0){
					$data_status = $this->fixed_equipment_model->get_status_labels($aRow['status']);
					if($data_status){
						$status = $data_status->status_type;
						if($aRow['checkin_out'] == 2 && $status == 'deployable'){
							$status = 'deployed';
						}
						$status_name = '<div class="row text-nowrap mleft5 mright5"><span style="color:'.$data_status->chart_color.'">'.$data_status->name.'</span><span class="mleft10 label label-primary">'._l('fe_'.$status).'</span></div>';
					}
				}
				$row[] = $status_name; 


				$data_location_info = $this->fixed_equipment_model->get_asset_location_info($aRow[db_prefix().'fe_assets.id']);
				$row[] = ($data_location_info->checkout_to != '' ? _l('fe_'.$data_location_info->checkout_to) : '');  
				$row[] = '<span class="text-nowrap">'.$data_location_info->curent_location.'</span>';  
				$row[] = '<span class="text-nowrap">'.$data_location_info->default_location.'</span>';  


				$manufacturer_name = '';
				if(is_numeric($manufacturer_id) && $manufacturer_id > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($manufacturer_id);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$manufacturer_name.'</span>';  

				$supplier_name = '';
				if(is_numeric($aRow['supplier_id'])){
					$data_supplier = $this->fixed_equipment_model->get_suppliers($aRow['supplier_id']);
					if($data_supplier){
						$supplier_name = $data_supplier->supplier_name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$supplier_name.'</span>';  

				$row[] = $aRow['date_buy'] != '' ? _d($aRow['date_buy']) : '';  
				$row[] = $aRow['unit_price'] != '' ? app_format_money($aRow['unit_price'], $currency_name) : '';  
				$row[] = $aRow['order_number'];  
				$row[] = $aRow['warranty_period'] != '' ? $aRow['warranty_period'].' '._l('months') : '';  
				$row[] = '';  
				$row[] = '<span class="text-nowrap">'.$aRow['description'].'</span>';  

				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkout');  
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkin'); 
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkout',1);  

				$row[] = '<span class="text-nowrap">'._dt($aRow['date_creator']).'</span>';
				$row[] = '<span class="text-nowrap">'._dt($aRow['updated_at']).'</span>'; 
				$checkout_date = ''; 
				$row[] = '<span class="text-nowrap">'.$checkout_date.'</span>';  
				$expected_checkin_date = ''; 
				$row[] = '<span class="text-nowrap">'.$expected_checkin_date.'</span>';  
				$last_audit = '';
				$row[] = '<span class="text-nowrap">'.$last_audit.'</span>';  
				$next_audit = '';
				$row[] = '<span class="text-nowrap">'.$next_audit.'</span>';  

				$button = '';

				if($aRow['checkin_out'] == 2 && (has_permission('fixed_equipment_assets', '', 'create') || is_admin())){
					$button = '<a class="btn btn-primary" data-asset_name="'.$aRow['assets_name'].'" data-serial="'.$aRow['series'].'" data-model="'.$aRow['model_name'].'" onclick="detal_asset_check_in(this, '.$aRow[db_prefix().'fe_assets.id'].')" >' . _l('fe_checkin') . '</a>';  
				}				
				$row[] = $button;
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* check in detail assets
* @return  
*/
public  function check_in_detail_assets(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$id = $data['parent_id'];
		unset($data['parent_id']);
		$result = $this->fixed_equipment_model->check_in_assets($data);
		if($result > 0){
			set_alert('success', _l('fe_checkin_successfully', _l('fe_assets')));
		}
		else{
			set_alert('danger', _l('fe_checkin_fail', _l('fe_assets')));					
		}
		redirect(admin_url('fixed_equipment/detail_asset/'.$id.'?tab=assets'));
	}
}

/**
* add fieldset
*/
public function add_fieldset(){
	if($this->input->post()){
		$data = $this->input->post();
		if($data['id'] == ''){
			unset($data['id']);
			$result =  $this->fixed_equipment_model->add_fieldset($data);
			if(is_numeric($result)){
				set_alert('success', _l('fe_added_successfully', _l('fe_fieldset')));
			}
			else{
				set_alert('danger', _l('fe_added_fail', _l('fe_fieldset')));					
			}
		}
		else{
			$result =  $this->fixed_equipment_model->update_fieldset($data);
			if($result){
				set_alert('success', _l('fe_updated_successfully', _l('fe_fieldset')));
			}
			else{
				set_alert('danger', _l('fe_no_data_changes', _l('fe_fieldset')));					
			}
		}
	}
	redirect(admin_url('fixed_equipment/settings?tab=custom_field'));
}

/**
* customfield table
* @return json 
*/
public function customfield_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				'id',
				'id',
				'id',
				'id'
			];

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_fieldsets';
			$join         = [];
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'name',
				'notes'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_customfield/'.$aRow['id']).'">' . _l('fe_view') . '</a>';
				$_data .= ' | <a href="javascript:void(0)" onclick="edit('.$aRow['id'].', this); return false;" data-name="'.$aRow['name'].'" data-notes="'.$aRow['notes'].'" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_fieldset/'.$aRow['id'].'').'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>';  

				$row[] = $aRow['name'].$_data;
				$row[] = $this->fixed_equipment_model->count_custom_field_by_field_set($aRow['id']);
				$used = '';

				$list_model = $this->fixed_equipment_model->get_list_model_by_fieldset($aRow['id']);
				if($list_model){
					foreach ($list_model as $model){
						$used .= '<span class="label label-success mright5">'.$model['model_name'].'</span>';
					}
				}
				$row[] = $used;
				$row[] = $aRow['notes'];
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* detail customfield
* @return  
*/
public function detail_customfield($id){
	$data['title'] = _l('fe_detail_fieldset');
	$data['id'] = $id;
	$this->load->view('settings/detail_customfield',$data);
}
/**
* add custom field 
*/
public function add_custom_field(){
	if($this->input->post()){
		$data = $this->input->post();
		if($data['id'] == ''){
			unset($data['id']);
			$result =  $this->fixed_equipment_model->add_custom_field($data);
			if(is_numeric($result)){
				set_alert('success', _l('fe_added_successfully', _l('fe_custom_field')));
			}
			else{
				set_alert('danger', _l('fe_added_fail', _l('fe_custom_field')));					
			}
		}
		else{
			$result =  $this->fixed_equipment_model->update_custom_field($data);
			if($result){
				set_alert('success', _l('fe_updated_successfully', _l('fe_custom_field')));
			}
			else{
				set_alert('danger', _l('fe_no_data_changes', _l('fe_custom_field')));					
			}
		}
	}
	redirect(admin_url('fixed_equipment/detail_customfield/'.$data['fieldset_id']));
}
/**
 * custom_field_table
 * @return json 
 */
public function custom_field_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$id = $this->input->post('id');
			$select = [
				'id',
				'id',
				'id',
				'id'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_custom_fields';
			$join         = [];
			array_push($where, ' AND fieldset_id = '.$id);
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'title',
				'type',
				db_prefix() . 'fe_custom_fields.option',
				'required',
				'default_value',
				'fieldset_id'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$_data = '';
				$name_s = '<a href="javascript:void(0)" onclick="edit('.$aRow['id'].');">'.$aRow['title'].'</a>';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="javascript:void(0)" onclick="edit('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_custom_field/'.$id.'/'.$aRow['id']).'" data-id="' . $aRow['id'] . '" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>'; 

				$row[] = $name_s.$_data;   
				$row[] = _l('fe_'.$aRow['type']);  
				$required = '';
				if($aRow['required'] == 1){
					$required = '<i class="fa fa-check"></i>';
				}
				else{
					$required = '<i class="fa fa-times"></i>';
				}
				$row[] = $required;  
				$option_list = '';
				if($aRow['option'] != '' && $aRow['option'] != null){
					$decode_option = json_decode($aRow['option']);
					if(is_array($decode_option)){
						foreach ($decode_option as $option) {
							$option_list .= '<span class="label label-success mright5">'.$option.'</span>';
						}
					}
				}
				$row[] = $option_list;  

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* delete custom field
* @param  integer $fieldset_id 
* @param  integer $id          
*/
public function delete_custom_field($fieldset_id, $id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_custom_field($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_custom_field')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_custom_field')));					
		}
	}
	redirect(admin_url('fixed_equipment/detail_customfield/'.$fieldset_id));
}
/**
* get custom field data
* @param  integer $id 
* @return integer     
*/
public function get_custom_field_data($id){
	$data = $this->fixed_equipment_model->get_custom_fields($id);
	echo json_encode($data);
	die;
}
/**
* delete fieldset
* @param  integer $fieldset_id 
* @param  integer $id          
*/
public function delete_fieldset($fieldset_id){
	if($fieldset_id != ''){
		$result =  $this->fixed_equipment_model->delete_fieldset($fieldset_id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_fieldset')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_fieldset')));					
		}
	}
	redirect(admin_url('fixed_equipment/settings?tab=custom_field'));
}


/**
* get_custom_field_model
* @param  integer $id 
* @return integer     
*/
public function get_custom_field_model($id = ''){
	if($id == ''){
		echo json_encode('');
		die;
	}
	$data_models = $this->fixed_equipment_model->get_models($id);
	$html = '';
	if($data_models){
		$fieldset_id = $data_models->fieldset_id;
		if($fieldset_id && $fieldset_id != '' && $fieldset_id != null){

			$data_list_custom_field = $this->fixed_equipment_model->get_custom_field_by_fieldset($fieldset_id);
			if($data_list_custom_field){
				foreach ($data_list_custom_field as $key => $customfield) {

					switch ($customfield['type']) {
						case 'select':
						$data['option'] = $customfield['option'];
						$data['title'] = $customfield['title'];
						$data['id'] = $customfield['id'];
						$data['required'] = $customfield['required'];
						$data['select'] = '';
						$html .= $this->load->view('includes/controls/select', $data, true);
						break;
						case 'multi_select':
						$data['option'] = $customfield['option'];
						$data['title'] = $customfield['title'];
						$data['id'] = $customfield['id'];
						$data['required'] = $customfield['required'];
						$data['select'] = '';
						$html .= $this->load->view('includes/controls/multi_select', $data, true);
						break;
						case 'checkbox':
						$data['option'] = $customfield['option'];
						$data['title'] = $customfield['title'];
						$data['id'] = $customfield['id'];
						$data['required'] = $customfield['required'];
						$data['select'] = '';
						$html .= $this->load->view('includes/controls/checkbox', $data, true);
						break;
						case 'radio_button':
						$data['option'] = $customfield['option'];
						$data['title'] = $customfield['title'];
						$data['id'] = $customfield['id'];
						$data['required'] = $customfield['required'];
						$data['select'] = '';
						$html .= $this->load->view('includes/controls/radio_button', $data, true);
						break;
						case 'textarea':
						$data['id'] = $customfield['id'];
						$data['title'] = $customfield['title'];
						$data['required'] = $customfield['required'];
						$data['value'] = '';
						$html .= $this->load->view('includes/controls/textarea', $data, true);
						break;
						case 'numberfield':
						$data['id'] = $customfield['id'];
						$data['title'] = $customfield['title'];
						$data['required'] = $customfield['required'];
						$data['value'] = '';
						$html .= $this->load->view('includes/controls/numberfield', $data, true);
						break;
						case 'textfield':
						$data['id'] = $customfield['id'];
						$data['title'] = $customfield['title'];
						$data['required'] = $customfield['required'];
						$data['value'] = '';
						$html .= $this->load->view('includes/controls/textfield', $data, true);
						break;
					}
				}
			}
		}
	}
	echo json_encode($html);
	die;
}

/**
* audit
* @return  
*/

public function audit_managements(){
	if (!(has_permission('fixed_equipment_audit', '', 'view_own') || has_permission('fixed_equipment_audit', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$data['title'] = _l('fe_audit_management');
	$this->load->model('staff_model');
	$data['staffs'] = $this->staff_model->get();
	$this->load->view('audit_management',$data);
}

/**
* audit request
* @return  
*/
public function audit_request(){
	$data['title'] = _l('fe_audit');
	$data['models'] = $this->fixed_equipment_model->get_models();
	$query = 'select id, assets_name, series from '.db_prefix().'fe_assets where type != "predefined_kit" and active = 1';
	$data['assets'] = $this->fixed_equipment_model->data_query($query, true);
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$this->load->model('staff_model');
	$data['staffs'] = $this->staff_model->get();
	$this->load->view('audits',$data);
}
/**
* get data hanson audit
* @return json 
*/
public function get_data_hanson_audit(){
	$data_hanson = json_encode(['id' => '', 'assets' => '', 'quantity' => '']);
	$data = $this->input->post();
	if($data['asset_location'] != '' || $data['model_id'] != '' || (isset($data['asset_id']) && $data['asset_id'] != '') || $data['checkin_checkout_status'] != ''){
		$query = '';
		$query .= 'select id, assets_name, series, type from '.db_prefix().'fe_assets';

		$list_query = [];
		if($data['asset_location'] != ''){
			$list_query[] = 'asset_location = '.$data['asset_location'];
		}

		if($data['model_id'] != ''){
			$list_query[] = 'model_id = '.$data['model_id'];
		}

		if(isset($data['asset_id']) && $data['asset_id'] != ''){
			$list_id_asset = (is_array($data['asset_id']) ? implode(',', $data['asset_id']) : $data['asset_id']);
			$list_query[] = 'id in ('.$list_id_asset.')';
		}

		if($data['checkin_checkout_status'] != ''){
			$list_query[] = 'checkin_out = '.$data['checkin_checkout_status'];
		}
		$list_query[] = 'type != "predefined_kit" and active = 1';
		$count = count($list_query);
		if($count > 0){
			$query .= ' where';
			foreach ($list_query as $key => $q) {
				$query .= ' '.$q;
				if(($key + 1) < $count){
					$query .= ' AND';
				}
			}
		}

		$data_asset = $this->fixed_equipment_model->data_query($query, true);
		$new_detailt = [];
		foreach ($data_asset as $key => $item) {
			$quantity = $this->get_quantity_asset_by_type($item['id'], $item['type']);
			$assets_name = '';
			if($item['series'] != '' && $item['assets_name'] != ''){
				$assets_name = $item['series'].' - '.$item['assets_name'];
			}
			elseif($item['series'] == '' && $item['assets_name'] != ''){
				$assets_name = $item['assets_name'];
			}
			elseif($item['series'] != '' && $item['assets_name'] == ''){
				$assets_name = $item['series'];
			}
			array_push($new_detailt, array(
				'id' => $item['id'],
				'item' => $assets_name,
				'type' => ucfirst($item['type']),
				'quantity' => $quantity
			));
		}
		$data_hanson = json_encode($new_detailt);
	}
	echo json_encode([
		'data_hanson' => $data_hanson,
		'success' => true
	]); 
}

/**
* get quantity asset by type
* @return integer $quantity 
*/
public function get_quantity_asset_by_type($asset_id, $type){
	$quantity = 0;
	switch ($type) {
		case 'accessory':
		$query = 'select quantity from '.db_prefix().'fe_assets where id = '.$asset_id;
		$data_asset = $this->fixed_equipment_model->data_query($query);
		if($data_asset){
			$quantity = $data_asset->quantity;
		}
		break;
		case 'consumable':
		$query = 'select quantity from '.db_prefix().'fe_assets where id = '.$asset_id;
		$data_asset = $this->fixed_equipment_model->data_query($query);
		if($data_asset){
			$quantity = $data_asset->quantity;
		}
		break;
		case 'component':
		$query = 'select quantity from '.db_prefix().'fe_assets where id = '.$asset_id;
		$data_asset = $this->fixed_equipment_model->data_query($query);
		if($data_asset){
			$quantity = $data_asset->quantity;
		}
		break;
		case 'license':
		$query = 'select seats from '.db_prefix().'fe_assets where id = '.$asset_id;
		$data_asset = $this->fixed_equipment_model->data_query($query);
		if($data_asset){
			$quantity = $data_asset->seats;
		}
		break;
		default:
		$quantity = 1;
		break;
	}
	return $quantity;
}

/**
* create audit request
*/
public function create_audit_request(){
	if($this->input->post()){
		$data =  $this->input->post();
		$insert_id = $this->fixed_equipment_model->create_audit_request($data);
		if(is_numeric($insert_id)){
// Approve
			$staff_id = get_staff_user_id();
			$rel_type = 'audit';
			$check_proccess = $this->fixed_equipment_model->get_approve_setting($rel_type, false);
			$process = '';
			if($check_proccess){
				if($check_proccess->choose_when_approving == 0){
					$this->fixed_equipment_model->send_request_approve($insert_id, $rel_type, $staff_id);
					$process = 'not_choose';
					set_alert('success', _l('fe_successful_submission_of_approval_request'));
				}else{
					$process = 'choose';
					set_alert('success', _l('fe_created_successfully'));
				}
			}else{
// Auto checkout if not approve process
// Change status
				$this->db->where('id', $insert_id);
				$this->db->update(db_prefix().'fe_audit_requests', ['status' => 1]);
				$process = 'no_proccess';
				set_alert('success', _l('fe_approved'));
			}
// End Approve
			redirect(admin_url('fixed_equipment/view_audit_request/'.$insert_id.'?process='.$process));
		}
		else{
			set_alert('danger', _l('fe_request_failed'));			
		}
	}
	redirect(admin_url('fixed_equipment/audit_managements'));
}

/**
* create audit request
*/
public function view_audit_request($id){
	$this->load->model('staff_model');
	$title = '';
	$data['audit'] = $this->fixed_equipment_model->get_audits($id);
	if($data['audit']){
		$title = $data['audit']->title;
	}
	$data['title'] = $title;

	$audit_detail = $this->fixed_equipment_model->get_audit_detail_by_master($id);

	$new_detailt = [];
	foreach ($audit_detail as $key => $item) {
		array_push($new_detailt, array(
			'id' => $item['asset_id'],
			'item' => $item['asset_name'],
			'type' => $item['type'],
			'quantity' => $item['quantity'],
			'maintenance' => $item['maintenance']
		));
	}
	$data['data_hanson'] = $new_detailt;
	$data['staffs'] = $this->staff_model->get();
	$data['data_approve'] = $this->fixed_equipment_model->get_approval_details($id, 'audit');

	$data['id'] = $id;

	$rel_type = 'audit';
	$process = '';
	$check_proccess = $this->fixed_equipment_model->get_approve_setting($rel_type, false);
	if($check_proccess){
		if($check_proccess->choose_when_approving == 0){
			$process = 'not_choose';
		}else{
			$process = 'choose';
		}
	}else{
		$process = 'no_proccess';
	}
	$data['process'] = $process;
	$this->load->model('currencies_model');
	$this->load->model('staff_model');
	$base_currency = $this->currencies_model->get_base_currency();
	$data['currency_name'] = '';
	if(isset($base_currency)){
		$data['currency_name'] = $base_currency->name;
	}
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
	$this->load->view('view_audit_managements', $data);
}
/**
* audit managements table
*/
public function audit_managements_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$current_user = get_staff_user_id();
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id'
			];
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_audit_requests';
			$join         = [];

			$auditor = $this->input->post("auditor");
			$status = $this->input->post("status");
			$audit_from_date = $this->input->post("audit_from_date");
			$audit_to_date = $this->input->post("audit_to_date");

			if(has_permission('fixed_equipment_audit', '', 'view') || is_admin()){ 
				if(isset($auditor) && $auditor != ''){
					$list_auditor = (is_array($auditor) ? implode(',', $auditor) : '');
					if($list_auditor != ''){
						array_push($where, 'AND auditor IN ('.$list_auditor.')');
					}
				}
			} 
			else{
				array_push($where, 'AND auditor = '.$current_user);
			}

			if($status != ''){
				if($status == 3){
					$status = 0;
				}
				array_push($where, 'AND status = '.$status);
			}

			if($audit_from_date != '' && $audit_to_date != ''){
				$from_date = fe_format_date($audit_from_date);
				$to_date = fe_format_date($audit_to_date);
				array_push($where, 'AND (date(audit_date) between "'.$from_date.'" AND "'.$to_date.'")');
			}

			if($audit_from_date == '' && $audit_to_date != ''){
				$to_date = fe_format_date($audit_to_date);
				array_push($where, 'AND date(audit_date) = "'.$to_date.'"');
			}

			if($audit_from_date != '' && $audit_to_date == ''){
				$from_date = fe_format_date($audit_from_date);
				array_push($where, 'AND date(audit_date) = "'.$from_date.'"');
			}

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'title',
				'audit_date',
				'auditor',
				'asset_location',
				'model_id',
				'asset_id',
				'checkin_checkout_status',
				'status',
				'date_creator'
			]);

			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$_data = '';
				$name_s = '<a href="'.admin_url('fixed_equipment/view_audit_request/'.$aRow['id']).'" >'.$aRow['title'].'</a>';

				$status = '';
				switch ($aRow['status']) {
					case 0:
					$status = '<span class="label label-primary">'._l('fe_new').'</span>';
					$_data .= '<div class="row-options">';
					$_data .= '<a href="'.admin_url('fixed_equipment/view_audit_request/'.$aRow['id']).'" class="text-primary">' . _l('fe_view') . '</a>';

					if(is_admin() || has_permission('fixed_equipment_audit', '', 'delete')){
						$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_audit_request/'.$aRow['id']).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
					}


					$_data .= '</div>'; 
					break;
					case 1:
					$status = '<span class="label label-success">'._l('fe_approved').'</span>';
					$_data .= '<div class="row-options">';
					$_data .= '<a href="'.admin_url('fixed_equipment/view_audit_request/'.$aRow['id']).'" class="text-primary">' . _l('fe_view') . '</a>';
					if(is_admin() || has_permission('fixed_equipment_audit', '', 'delete')){
						$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_audit_request/'.$aRow['id']).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
					}
					if($aRow['auditor'] == $current_user){
						$_data .= ' | <a href="'.admin_url('fixed_equipment/audit/'.$aRow['id']).'" class="text-success">' . _l('fe_audit') . '</a>';
					}
					$_data .= '</div>'; 
					break;
					case 2:
					$status = '<span class="label label-danger">'._l('fe_rejected').'</span>';
					$_data .= '<div class="row-options">';
					$_data .= '<a href="'.admin_url('fixed_equipment/view_audit_request/'.$aRow['id']).'" class="text-primary">' . _l('fe_view') . '</a>';
					if(is_admin() || has_permission('fixed_equipment_audit', '', 'delete')){
						$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_audit_request/'.$aRow['id']).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
					}
					$_data .= '</div>'; 
					break;
				}



				$row[] = $name_s.$_data;   
				$row[] = get_staff_full_name($aRow['auditor']);  
				$row[] = _d($aRow['audit_date']);



				$row[] = $status;

				$row[] = _d($aRow['date_creator']);

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* approve request form audit
* @return json 
*/
public function approve_request_form_audit(){
	$data = $this->input->post();
	$data['date'] = date('Y-m-d');
	$data['staffid'] = get_staff_user_id();
	$success = $this->fixed_equipment_model->change_approve_audit($data);
	$message = '';
	if($success == true){
		if($data['approve'] == 1){
			$message = _l('fe_approved');
		}
		else{
			$message = _l('fe_rejected');			
		}
	}
	else{
		$message = _l('fe_approve_fail');
	}
	echo json_encode([
		'success' => $success,
		'message' => $message,
	]);
	die();      
}

/**
* choose approver request audit
* @return json 
*/
public function choose_approver_request_audit(){
	$data = $this->input->post();
	$success = false;
	$message = '';
	if($data['id']){
		$insert_id = $this->fixed_equipment_model->add_approver_choosee_when_approve_audit($data['id'], 'audit', $data['approver']);
		if(is_numeric($insert_id) && $insert_id > 0){
			$success = true;
			$message = _l('fe_successful_submission_of_approval_request');
		}
		else{
			$success = false;
			$message = _l('fe_submit_approval_request_failed');
		}
	}
	echo json_encode([
		'success' => $success,
		'message' => $message
	]);
}


/**
* delete audit request
* @param  integer $id 
*/
public function delete_audit_request($id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_audit_request($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_audit_request')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_audit_request')));					
		}
	}
	redirect(admin_url('fixed_equipment/audit_managements'));
}

/**
* audit
*/
public function audit($id){
	$this->load->model('staff_model');
	$title = '';
	$data['audit'] = $this->fixed_equipment_model->get_audits($id);
	if($data['audit']){
		$title = $data['audit']->title;
	}
	$data['title'] = $title;

	$audit_detail = $this->fixed_equipment_model->get_audit_detail_by_master($id);

	$new_detailt = [];
	foreach ($audit_detail as $key => $item) {
		array_push($new_detailt, array(
			'id' => $item['asset_id'],
			'item' => $item['asset_name'],
			'type' => $item['type'],
			'quantity' => $item['quantity'],
			'adjust' => $item['adjusted'],
			'maintenance' => $item['maintenance'],
			'accept' => (int)$item['accept']
		));
	}
	$data['data_hanson'] = $new_detailt;
	$data['staffs'] = $this->staff_model->get();

	$approve_audit = $this->fixed_equipment_model->get_approval_details($id, 'audit');

	$data['data_approve'] = $this->fixed_equipment_model->get_approval_details($id, 'close_audit');
	$current_user_id = get_staff_user_id();

	$data['is_approver'] = false;
	foreach ($data['data_approve'] as $key => $staff) {
		if($current_user_id == $staff['staffid']){
			$data['is_approver'] = true;
			break;
		}
	}
	$rel_type = 'audit';
	$process = '';
	$check_proccess = $this->fixed_equipment_model->get_approve_setting($rel_type, false);
	if($check_proccess){
		if($check_proccess->choose_when_approving == 0){
			$process = 'not_choose';
		}else{
			$process = 'choose';
		}
	}else{
		$data['is_approver'] = true;
		$process = 'no_proccess';
	}
	$data['process'] = $process;
	$data['id'] = $id;
	$this->load->view('audit', $data);
}

/**
* create audit request
*/
public function close_audit_request(){
	if($this->input->post()){
		$data =  $this->input->post();
		$rel_id = $data['id'];

		$res = $this->fixed_equipment_model->update_audit_request($data);
		if($res){			
			// Approve
			$staff_id = get_staff_user_id();
			$rel_type = 'audit';
			$check_proccess = $this->fixed_equipment_model->get_approve_setting($rel_type, false);
			$process = '';
			if($check_proccess){
				if($check_proccess->choose_when_approving == 0){
					$this->fixed_equipment_model->send_request_approve_close_audit($rel_id, 'close_audit', $staff_id);
					// Update status to waiting approve
					$this->db->where('id', $rel_id);
					$this->db->update(db_prefix().'fe_audit_requests', ['closed' => 4]);
					// End update status to waiting approve
					$process = 'not_choose';
					set_alert('success', _l('fe_successful_submission_of_approval_request'));
				}else{
					$process = 'choose';
					set_alert('success', _l('fe_created_successfully'));
				}
			}else{
				// Auto checkout if not approve process
				// Change status
				$this->db->where('id', $rel_id);
				$this->db->update(db_prefix().'fe_audit_requests', ['closed' => 1]);
				$data_hanson = (isset($data['assets_detailt']) ? json_decode($data['assets_detailt']) : []);
				// Change asset quantity after close audit 
				$this->fixed_equipment_model->update_asset_quantity_close_audit($data_hanson, $rel_id);
				$process = 'no_proccess';
				set_alert('success', _l('fe_approved'));
			}
			// End Approve
			redirect(admin_url('fixed_equipment/audit/'.$rel_id.'?process='.$process));
		}
		else{
			set_alert('danger', _l('fe_request_failed'));			
		}
	}
	redirect(admin_url('fixed_equipment/audit_managements'));
}

/**
* approve request close audit
* @return json 
*/
public function approve_request_close_audit(){
	$data = $this->input->post();
	$data['date'] = date('Y-m-d');
	$data['staffid'] = get_staff_user_id();
	$success = $this->fixed_equipment_model->change_approve_close_audit($data);
	$message = '';
	if($success == true){
		if($data['approve'] == 1){
			$message = _l('fe_approved');
		}
		else{
			$message = _l('fe_rejected');			
		}
	}
	else{
		$message = _l('fe_approve_fail');
	}
	echo json_encode([
		'success' => $success,
		'message' => $message,
	]);
	die();      
}

/**
* choose approver request close audit
* @return json 
*/
public function choose_approver_request_close_audit(){
	$data = $this->input->post();
	$success = false;
	$message = '';
	if($data['id']){
		$insert_id = $this->fixed_equipment_model->add_approver_choosee_when_close_audit($data['id'], 'close_audit', $data['approver']);
		if(is_numeric($insert_id) && $insert_id > 0){
			// Update status to waiting approve
			$this->db->where('id', $data['id']);
			$this->db->update(db_prefix().'fe_audit_requests', ['closed' => 4]);
			// End update status to waiting approve
			$success = true;
			$message = _l('fe_successful_submission_of_approval_request');
		}
		else{
			$success = false;
			$message = _l('fe_submit_approval_request_failed');
		}
	}
	echo json_encode([
		'success' => $success,
		'message' => $message
	]);
}

/**
* report
* @return  
*/
public function report(){
	if (!(has_permission('fixed_equipment_report', '', 'view_own') || has_permission('fixed_equipment_report', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$this->load->model('staff_model');
	$data['title'] = _l('fe_report');
	$data['staffs'] = $this->staff_model->get();
	$this->load->view('report',$data);
}
/**
* table activity dashboard
*/
public function table_activity_dashboard(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('staff_model');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id'			
			];
			$where        = [];

			if(!(has_permission('fixed_equipment_dashboard', '', 'view') || is_admin())){
				array_push($where, 'AND admin_id = '.get_staff_user_id());				
			}


			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_log_assets';
			$join         = [];


			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'admin_id',
				'action',
				'target',
				'changed',
				db_prefix() . 'fe_log_assets.to',
				'to_id',
				'notes',
				'date_creator'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = _dt($aRow['date_creator']);  
				$row[] = get_staff_full_name($aRow['admin_id']);  
				$row[] = _l('fe_'.$aRow['action']);  

				$target = '';
				switch ($aRow['to']) {
					case 'user':
					$department_name = '';
					$data_staff_department = $this->departments_model->get_staff_departments($aRow['to_id']);
					if($data_staff_department){
						foreach ($data_staff_department as $key => $staff_department) {
							$department_name .= $staff_department['name'].', ';
						}
						if($department_name != ''){
							$department_name = '('.rtrim($department_name,', ').') ';
						}
					}
					$head = '';
					$tail = '';
		         if(fe_get_status_modules('hr_profile')){
		         	$head = '<a href="'.admin_url('hr_profile/member/'.$aRow['to_id'].'/profile').'" target="_blank">';
						$tail = '</a>';
		         }
					$target = $head.'<i class="fa fa-user"></i> '.$department_name.''.get_staff_full_name($aRow['to_id']).$tail;
					break;
					case 'asset':
					$data_assets = $this->fixed_equipment_model->get_assets($aRow['to_id']);
					if($data_assets){
						$target = '<a href="'.admin_url('fixed_equipment/detail_asset/'.$aRow['to_id'].'?tab=details').'" target="_blank"><i class="fa fa-barcode"></i> '.$data_assets->series.' '.$data_assets->assets_name.'</a>';
					}
					break;
					case 'location':
					$data_locations = $this->fixed_equipment_model->get_locations($aRow['to_id']);
					if($data_locations){
						$target = '<a href="'.admin_url('fixed_equipment/detail_locations/'.$aRow['to_id']).'" target="_blank"><i class="fa fa-map-marker"></i> '.$data_locations->location_name.'</a>';
					}
					break;
				}

				$row[] = $target;  
				$row[] = $aRow['notes'];  
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
* table activity report
*/
public function table_activity_report(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('staff_model');
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id'			
			];
			$where        = [];


			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_log_assets';
			$join         = [];

			$filter_date = $this->fixed_equipment_model->from_to_date_report();
			if($filter_date->from_date != '' && $filter_date->to_date != ''){
				array_push($where, 'AND (date(date_creator) between "'.$filter_date->from_date.'" AND "'.$filter_date->to_date.'")');
			}

			if($filter_date->from_date == '' && $filter_date->to_date != ''){
				array_push($where, 'AND date(date_creator) = "'.$filter_date->to_date.'"');
			}

			if($filter_date->from_date != '' && $filter_date->to_date == ''){
				array_push($where, 'AND date(date_creator) = "'.$filter_date->from_date.'"');
			}

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'admin_id',
				'action',
				'target',
				'changed',
				db_prefix() . 'fe_log_assets.to',
				'to_id',
				'notes',
				'date_creator'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = _dt($aRow['date_creator']);  
				$row[] = get_staff_full_name($aRow['admin_id']);  
				$row[] = _l('fe_'.$aRow['action']);  

				$target = '';
				switch ($aRow['to']) {
					case 'user':
					$department_name = '';
					$data_staff_department = $this->departments_model->get_staff_departments($aRow['to_id']);
					if($data_staff_department){
						foreach ($data_staff_department as $key => $staff_department) {
							$department_name .= $staff_department['name'].', ';
						}
						if($department_name != ''){
							$department_name = '('.rtrim($department_name,', ').') ';
						}
					}
					$target = '<i class="fa fa-user"></i> '.$department_name.''.get_staff_full_name($aRow['to_id']);
					break;
					case 'asset':
					$data_assets = $this->fixed_equipment_model->get_assets($aRow['to_id']);
					if($data_assets){
						$target = '<i class="fa fa-barcode"></i> ('.$data_assets->qr_code.') '.$data_assets->assets_name;
					}
					break;
					case 'location':
					$data_locations = $this->fixed_equipment_model->get_locations($aRow['to_id']);
					if($data_locations){
						$target = '<i class="fa fa-map-marker"></i> '.$data_locations->location_name;
					}
					break;
				}

				$row[] = $target;  
				$row[] = $aRow['notes'];  
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* table unaccepted assets report
* @return json 
*/
public function table_unaccepted_assets_report(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id'
			];

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_checkin_assets';
			$join         = ['LEFT JOIN '.db_prefix().'fe_assets ON '.db_prefix().'fe_assets.id = '.db_prefix().'fe_checkin_assets.item_id'];

			$checkout_for = $this->input->post("checkout_for");

			if(has_permission('fixed_equipment_report', '', 'view') || is_admin()){
				if(isset($checkout_for) && $checkout_for != ''){
					$list_checkout_for = (is_array($checkout_for) ? implode(',', $checkout_for) : '');
					if($list_checkout_for != ''){
						array_push($where, 'AND staff_id IN ('.$list_checkout_for.')');
					}
				}
			}else{
				array_push($where, 'AND staff_id = '.get_staff_user_id().'');
			}


			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.request_status = 2');

			$filter_date = $this->fixed_equipment_model->from_to_date_report();
			if($filter_date->from_date != '' && $filter_date->to_date != ''){
				array_push($where, 'AND (date('.db_prefix().'fe_checkin_assets.date_creator) between "'.$filter_date->from_date.'" AND "'.$filter_date->to_date.'")');
			}

			if($filter_date->from_date == '' && $filter_date->to_date != ''){
				array_push($where, 'AND date('.db_prefix().'fe_checkin_assets.date_creator) = "'.$filter_date->to_date.'"');
			}

			if($filter_date->from_date != '' && $filter_date->to_date == ''){
				array_push($where, 'AND date('.db_prefix().'fe_checkin_assets.date_creator) = "'.$filter_date->from_date.'"');
			}

			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.type = "checkout"');
			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.requestable = 1');

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_assets.assets_name',
				db_prefix().'fe_assets.series',
				db_prefix().'fe_assets.model_id',
				'request_title',
				'request_status',
				'staff_id',
				'checkout_to',
				'notes',
				db_prefix().'fe_checkin_assets.date_creator'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$_data = '';


				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_request/'.$aRow['id']).'">' . _l('fe_view') . '</a>';
				$_data .= '</div>';  

				$row[] = $aRow['request_title'].$_data; 
				$row[] = $aRow['assets_name'];  
				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 
				$row[] = $aRow['series'];  
				$row[] = get_staff_full_name($aRow['staff_id']);  
				$row[] = $aRow['notes']; 
				$row[] = _dt($aRow['date_creator']);  
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
 * table inventory report report
 * @return json 
 */
public function table_inventory_report_report(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_checkin_assets.id'
			];

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_checkin_assets';
			$join         = ['LEFT JOIN '.db_prefix().'fe_assets ON '.db_prefix().'fe_assets.id = '.db_prefix().'fe_checkin_assets.item_id'];

			$checkout_for = $this->input->post("checkout_for");



			if(isset($checkout_for) && $checkout_for != ''){
				$list_checkout_for = (is_array($checkout_for) ? implode(',', $checkout_for) : '');
				if($list_checkout_for != ''){
					array_push($where, 'AND staff_id IN ('.$list_checkout_for.')');
				}
			}
			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.request_status = 2');

			$filter_date = $this->fixed_equipment_model->from_to_date_report();
			if($filter_date->from_date != '' && $filter_date->to_date != ''){
				array_push($where, 'AND (date('.db_prefix().'fe_checkin_assets.date_creator) between "'.$filter_date->from_date.'" AND "'.$filter_date->to_date.'")');
			}

			if($filter_date->from_date == '' && $filter_date->to_date != ''){
				array_push($where, 'AND date('.db_prefix().'fe_checkin_assets.date_creator) = "'.$filter_date->to_date.'"');
			}

			if($filter_date->from_date != '' && $filter_date->to_date == ''){
				array_push($where, 'AND date('.db_prefix().'fe_checkin_assets.date_creator) = "'.$filter_date->from_date.'"');
			}
			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.type = "checkout"');
			array_push($where, 'AND '.db_prefix().'fe_checkin_assets.requestable = 1');

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_checkin_assets.id',
				db_prefix().'fe_assets.assets_name',
				db_prefix().'fe_assets.series',
				db_prefix().'fe_assets.model_id',
				'request_title',
				'request_status',
				'staff_id',
				'checkout_to',
				'notes',
				db_prefix().'fe_checkin_assets.date_creator'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$_data = '';


				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_request/'.$aRow['id']).'">' . _l('fe_view') . '</a>';
				$_data .= '</div>';  

				$row[] = $aRow['request_title'].$_data; 
				$row[] = $aRow['assets_name'];  
				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 
				$row[] = $aRow['series'];  
				$row[] = get_staff_full_name($aRow['staff_id']);  
				$row[] = $aRow['notes']; 
				$row[] = _dt($aRow['date_creator']);  
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* dashboard
*/
public function dashboard()
{
	if (!(has_permission('fixed_equipment_dashboard', '', 'view_own') || has_permission('fixed_equipment_dashboard', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$data_asset_by_status = [];
	$data_status = $this->fixed_equipment_model->get_status_labels();
	$count_total_asset = $this->fixed_equipment_model->count_total_assets('asset');
	if($count_total_asset > 0){
		foreach ($data_status as $status) {
			$count_result = 0;
			$query = 'select count(1) as count from '.db_prefix().'fe_assets where status = '.$status['id'].' and type = "asset" and active = 1';
			$data_query = $this->fixed_equipment_model->data_query($query);
			if($data_query){
				$count_result = $data_query->count;
			}
			$ratio = ($count_result * 100) / $count_total_asset;
			$data_asset_by_status[] = ['name' => $status['name'], 'y' => round($ratio,2), 'drilldown' => $status['name'], 'color' => $status['chart_color']];
		}
	}
	$data['asset_by_status'] = json_encode($data_asset_by_status);
	$data['title'] = _l('fe_fixed_equipment');
	$this->load->view('dashboard', $data);
}

/**
* table asset categories dashboard
*/
public function table_asset_categories_dashboard(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'			
			];
			$where        = [];


			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_categories';
			$join         = [];



			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'id',
				'category_name',
				'type'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];

				$row[] = $aRow['category_name'];  
				$row[] = _l('fe_'.$aRow['type']);  

				$assets = 0;
				$license = 0;
				$accessory = 0;
				$consumable = 0;
				$component = 0;
				switch ($aRow['type']) {
					case 'asset':
					$query = 'select count(1) as count from '.db_prefix().'fe_assets left join '.db_prefix().'fe_models on '.db_prefix().'fe_models.id = '.db_prefix().'fe_assets.model_id where '.db_prefix().'fe_models.category = '.$aRow['id'].' and active = 1';
					$result_query = $this->fixed_equipment_model->data_query($query);
					if($result_query){
						$assets = $result_query->count;
					}
					break;
					case 'license':
					$query = 'select count(1) as count from '.db_prefix().'fe_assets where category_id = '.$aRow['id'].' and active = 1';
					$result_query = $this->fixed_equipment_model->data_query($query);
					if($result_query){
						$license = $result_query->count;
					}
					break;
					case 'accessory':
					$query = 'select count(1) as count from '.db_prefix().'fe_assets where category_id = '.$aRow['id'].' and active = 1';
					$result_query = $this->fixed_equipment_model->data_query($query);
					if($result_query){
						$accessory = $result_query->count;
					}
					break;
					case 'consumable':
					$query = 'select count(1) as count from '.db_prefix().'fe_assets where category_id = '.$aRow['id'].' and active = 1';
					$result_query = $this->fixed_equipment_model->data_query($query);
					if($result_query){
						$consumable = $result_query->count;
					}
					break;
					case 'component':
					$query = 'select count(1) as count from '.db_prefix().'fe_assets where category_id = '.$aRow['id'].' and active = 1';
					$result_query = $this->fixed_equipment_model->data_query($query);
					if($result_query){
						$component = $result_query->count;
					}
					break;
				}


				$row[] = $assets;
				$row[] = $license;
				$row[] = $accessory;
				$row[] = $consumable;
				$row[] = $component;

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* depreciations
* @return  
*/
public function depreciations(){
	if (!(has_permission('fixed_equipment_depreciations', '', 'view_own') || has_permission('fixed_equipment_depreciations', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$this->load->model('staff_model');
	$data['title'] = _l('fe_depreciations');
	$data['staffs'] = $this->staff_model->get();
	$data['status_labels'] = $this->fixed_equipment_model->get_status_labels();
	$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
	$this->load->view('depreciations_management',$data);
}

/**
* depreciation table
* @return json 
*/
public function depreciation_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){

			$asset = $this->input->post("asset");
			$status = $this->input->post("status");

			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name_s = '';
			if(isset($base_currency)){
				$currency_name_s = $base_currency->name;
			}

			$select = [
				db_prefix().'fe_assets.id',

				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description'
			];



			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = ['LEFT JOIN '.db_prefix().'fe_models ON '.db_prefix().'fe_models.id = '.db_prefix().'fe_assets.model_id'];

			$list_asset_id = $this->fixed_equipment_model->get_list_asset_id_has_depreciations();
			if(count($list_asset_id) > 0){
				array_push($where, 'AND '.db_prefix() . 'fe_assets.id in ('.implode(',', $list_asset_id).')');
			}
			else{
				array_push($where, 'AND '.db_prefix() . 'fe_assets.id = 0');
			}
			
			if($asset != ''){
				array_push($where, 'AND '.db_prefix() . 'fe_assets.id in ('.implode(',', $asset).')');
			}	
			if($status != ''){
				array_push($where, 'AND status = '.$status);
			}

			if(!is_admin()){
				array_push($where, 'AND requestable = 1');
			}

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_assets.id',
				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description',
				'requestable',
				'qr_code',
				db_prefix().'fe_assets.date_creator',
				'updated_at',
				'checkin_out',
				'status',
				db_prefix().'fe_models.model_name',
				db_prefix().'fe_models.model_no',
				'type'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$allow_add = false;

				$eol = 0;
				$depreciation_name = '';
				$depreciation_value = '';
				if($aRow['type'] == 'asset'){
					$data_model = $this->fixed_equipment_model->get_models($aRow['model_id']);
					if($data_model){
						$eol = _d(get_expired_date($aRow['date_buy'], $data_model->eol));
						if(is_numeric($data_model->depreciation) && $data_model->depreciation > 0){
							$data_depreciation = $this->fixed_equipment_model->get_depreciations($data_model->depreciation);
							if($data_depreciation && $aRow['unit_price'] != '' && $aRow['unit_price'] != 0 && $aRow['unit_price'] != null){
								$allow_add = true;
								$depreciation_name = $data_depreciation->name;	
								$depreciation_value = $data_depreciation->term;	
							}
						}
					}
				}

				if($aRow['type'] == 'license'){
					if(is_numeric($aRow['depreciation']) && $aRow['depreciation'] > 0){
						$data_depreciation = $this->fixed_equipment_model->get_depreciations($aRow['depreciation']);
						if($data_depreciation && $aRow['unit_price'] != '' && $aRow['unit_price'] != 0 && $aRow['unit_price'] != null){
							$allow_add = true;
							$depreciation_name = $data_depreciation->name;	
							$depreciation_value = $data_depreciation->term;	
						}
					}
				}

				if($allow_add){

					$row[] = $aRow['id'];  

					$_data = '';

					$_data .= '<div class="row-options">';

					$_data .= '<a href="'.admin_url('fixed_equipment/detail_asset/'.$aRow[db_prefix().'fe_assets.id'].'?tab=details&re=depreciations').'">' . _l('fe_view') . '</a>';

					$_data .= '</div>'; 

					$row[] = '<span class="text-nowrap">'.$aRow['assets_name'].'</span>'.$_data;   

					$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 

					$row[] = $aRow['series'];  

					$row[] = '<span class="text-nowrap">'.$depreciation_name.'</span>';  

					$row[] = $depreciation_value;  

					$status = '';

					$status_name = '';

					if(is_numeric($aRow['status']) && $aRow['status'] > 0){
						$data_status = $this->fixed_equipment_model->get_status_labels($aRow['status']);
						if($data_status){
							$status = $data_status->status_type;
							if($aRow['checkin_out'] == 2 && $status == 'deployable'){
								$status = 'deployed';
							}
							$status_name = '<div class="row text-nowrap pleft15 pright15"><span style="color:'.$data_status->chart_color.'">'.$data_status->name.'</span><span class="mleft10 label label-primary">'._l('fe_'.$status).'</span></div>';
						}
					}

					$row[] = $status_name;  

					$data_location_info = $this->fixed_equipment_model->get_asset_location_info($aRow[db_prefix().'fe_assets.id']);
					$row[] = '<span class="text-nowrap">'.$data_location_info->curent_location.'</span>';  
					$row[] = $aRow['date_buy'] != '' ? '<span class="text-nowrap">'._d($aRow['date_buy']).'</span>' : '';  

					$row[] = '<span class="text-nowrap">'.$eol.'</span>'; 

					$cost = ($aRow['unit_price'] != '' && $aRow['unit_price'] != null) ? $aRow['unit_price'] : 0;

					$row[] = $aRow['unit_price'] != '' ? '<span class="text-nowrap">'.app_format_money($cost, $currency_name_s).'</span>' : '';  

					$monthly_depreciation = 0;

					$diff = 0;

					if($aRow['date_buy'] != ''){
						$depreciation_result = $this->fixed_equipment_model->straight_line_depreciation_method($cost, $depreciation_value, 0, $aRow['date_buy']);
						if($depreciation_result){
							$monthly_depreciation = $depreciation_result->current_depreciation;
							$diff = $depreciation_result->diff;
						}
					}

					$currency_val = $cost - $diff;


					$include_maintenance = $this->fixed_equipment_model->total_maintenance_asset_cost($aRow[db_prefix().'fe_assets.id']);
					$row[] = app_format_money(round($currency_val + $include_maintenance, 2), $currency_name_s);
					
					$row[] = app_format_money(round($currency_val, 2), $currency_name_s);

					$row[] = app_format_money(round($monthly_depreciation, 2), $currency_name_s);

					$row[] = app_format_money(round($diff, 2), $currency_name_s);

					$output['aaData'][] = $row;                                     
				}
			}
			echo json_encode($output);
			die();
		}
	}
}
/**
 * location
*/
public function locations(){
	if (!(has_permission('fixed_equipment_locations', '', 'view_own') || has_permission('fixed_equipment_locations', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	$data['title']    = _l('fe_location_management');
	$this->load->model('staff_model');
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$data['staffs'] = $this->staff_model->get();
	$this->load->model('currencies_model');
	$data['currencies'] = $this->currencies_model->get();
	$data['base_currency'] = $this->currencies_model->get_base_currency();
	$this->load->view('location_management', $data);
}
/**
 * detail locations
 * @param  integer $id
 */
public function detail_locations($id){
	if (!(has_permission('fixed_equipment_locations', '', 'view_own') || has_permission('fixed_equipment_locations', '', 'view') || is_admin())) {          
		access_denied('fe_fixed_equipment');
	}
	if(!isset($id) || $id == ''){
		redirect(admin_url('fixed_equipment/dashboard'));
	}
	$data['redirect'] = $this->input->get('re');
	$data['title']    = '';
	$this->load->model('staff_model');
	$data['location'] = $this->fixed_equipment_model->get_locations($id);
	if($data['location']){
		$data['title'] = $data['location']->location_name;
	}
	$data['id'] = $id;
	$this->load->model('currencies_model');
	$data['currencies'] = $this->currencies_model->get();
	$data['base_currency'] = $this->currencies_model->get_base_currency();


	$data['models'] = $this->fixed_equipment_model->get_models();
	$data['suppliers'] = $this->fixed_equipment_model->get_suppliers();
	$data['status_labels'] = $this->fixed_equipment_model->get_status_labels();
	$data['status_label_checkout'] = $this->fixed_equipment_model->get_status_labels('','deployable');
	$data['locations'] = $this->fixed_equipment_model->get_locations();
	$data['assets'] = $this->fixed_equipment_model->get_assets('','asset');
	$data['staffs'] = $this->staff_model->get();

	$data['accessories_categories'] = $this->fixed_equipment_model->get_categories('','accessory');
	$data['consumable_categories'] = $this->fixed_equipment_model->get_categories('','consumable');
	$data['component_categories'] = $this->fixed_equipment_model->get_categories('','component');

	$data['manufacturers'] = $this->fixed_equipment_model->get_asset_manufacturers();

	$this->load->view('detail_locations', $data);
}

/**
	 * asset location table
	 * @return json 
	 */
public function asset_location_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){

			$id = $this->input->post("id");
			$model = $this->input->post("model");
			$status = $this->input->post("status");
			$supplier = $this->input->post("supplier");
			$location = $this->input->post("location");

			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				db_prefix().'fe_assets.id',

				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description',
				db_prefix().'fe_assets.requestable',
				'qr_code',
				db_prefix().'fe_assets.date_creator',
				'updated_at',
				'checkin_out',
				db_prefix().'fe_assets.status',

				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
				db_prefix().'fe_assets.date_creator',
			];



			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [
				'LEFT JOIN '.db_prefix().'fe_models ON '.db_prefix().'fe_models.id = '.db_prefix().'fe_assets.model_id',
				'LEFT JOIN '.db_prefix().'fe_checkin_assets ON '.db_prefix().'fe_checkin_assets.id = '.db_prefix().'fe_assets.checkin_out_id',
			];
			array_push($where, 'AND '.db_prefix().'fe_assets.type = "asset"');
			array_push($where, 'AND active = 1');
			
			if($model != ''){
				array_push($where, 'AND '.db_prefix().'fe_assets.model_id = '.$model);
			}	
			if($status != ''){
				array_push($where, 'AND '.db_prefix().'fe_assets.status = '.$status);
			}
			if($supplier != ''){
				array_push($where, 'AND supplier_id = '.$supplier);
			}
			if($id != ''){
				array_push($where, 'AND '.db_prefix().'fe_assets.asset_location = '.$id);
			}

			if(!is_admin() || !has_permission('fixed_equipment_assets', '', 'view')){
				array_push($where, 'AND '.db_prefix().'fe_assets.requestable = 1');
			}
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_assets.id',
				'assets_code',
				'assets_name',
				'series',
				'asset_group',
				'asset_location',
				'model_id',
				'date_buy',
				'warranty_period',
				'unit_price',
				db_prefix().'fe_assets.depreciation',
				'supplier_id',
				'order_number',
				'description',
				db_prefix().'fe_assets.requestable',
				'qr_code',
				db_prefix().'fe_assets.date_creator',
				'updated_at',
				'checkin_out',
				'checkin_out_id',
				db_prefix().'fe_models.model_name',
				db_prefix().'fe_models.model_no',
				db_prefix().'fe_assets.status'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  

				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_asset/'.$aRow[db_prefix().'fe_assets.id'].'?tab=details&re=detail_locations/'.$id).'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_assets', '', 'view')){
					$_data .= ' | <a href="javascript:void(0)" onclick="edit_assets_location('.$aRow[db_prefix().'fe_assets.id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_assets_location/'.$aRow[db_prefix().'fe_assets.id'].'/'.$id).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$row[] = '<span class="text-nowrap">'.$aRow['assets_name'].'</span>'.$_data;   

				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 

				$row[] = '<span class="text-nowrap">'.$aRow['series'].'</span>';  

				$category_id = 0;
				$manufacturer_id = 0;
				if(is_numeric($aRow['model_id']) > 0){
					$data_model = $this->fixed_equipment_model->get_models($aRow['model_id']);
					if($data_model){
						$category_id = $data_model->category;
						$manufacturer_id = $data_model->manufacturer;
					}
				}
				$row[] = '<span class="text-nowrap">'.$aRow['model_name'].'</span>';  
				$row[] = $aRow['model_no'];  

				$category_name = '';
				if(is_numeric($category_id) && $category_id > 0){
					$data_cat = $this->fixed_equipment_model->get_categories($category_id);
					if($data_cat){
						$category_name = $data_cat->category_name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$category_name.'</span>';  

				$status = '';
				$status_name = '';
				if(is_numeric($aRow['status']) && $aRow['status'] > 0){
					$data_status = $this->fixed_equipment_model->get_status_labels($aRow['status']);
					if($data_status){
						$status = $data_status->status_type;
						if($aRow['checkin_out'] == 2 && $status == 'deployable'){
							$status = 'deployed';
						}
						$status_name = '<div class="row text-nowrap mleft5 mright5"><span style="color:'.$data_status->chart_color.'">'.$data_status->name.'</span><span class="mleft10 label label-primary">'._l('fe_'.$status).'</span></div>';
					}
				}
				$row[] = $status_name;  





				$data_location_info = $this->fixed_equipment_model->get_asset_location_info($aRow[db_prefix().'fe_assets.id']);
				$checkout_to = '';
				$current_location = '';

				if($data_location_info->checkout_to != ''){		
					$icon_checkout_to = '';
					if($data_location_info->checkout_type == 'location'){
						$icon_checkout_to = '<i class="fa fa-map-marker"></i>';
						$checkout_to = '<a href="'.admin_url('fixed_equipment/detail_locations/'.$data_location_info->to_id).'?re=assets" class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</a>';  
						$current_location = '';
					}
					elseif($data_location_info->checkout_type == 'user'){
						$icon_checkout_to = '<i class="fa fa-user"></i>';
						$checkout_to = '<span class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</span>';  
						$current_location = '';
					}
					elseif($data_location_info->checkout_type == 'asset'){
						$icon_checkout_to = '<i class="fa fa-barcode"></i>';
						$checkout_to = '<a href="'.admin_url('fixed_equipment/detail_asset/'.$data_location_info->to_id.'?tab=details').'" class="text-nowrap">'.$icon_checkout_to.' '.$data_location_info->checkout_to.'</a>';  
						$current_location = '';
					}
				}
				$row[] = $checkout_to;  
				$row[] = '<span class="text-nowrap">'.$data_location_info->curent_location.'</span>';  
				$row[] = '<span class="text-nowrap">'.$data_location_info->default_location.'</span>';  


				$manufacturer_name = '';
				if(is_numeric($manufacturer_id) && $manufacturer_id > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($manufacturer_id);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$manufacturer_name.'</span>';  

				$supplier_name = '';
				if(is_numeric($aRow['supplier_id'])){
					$data_supplier = $this->fixed_equipment_model->get_suppliers($aRow['supplier_id']);
					if($data_supplier){
						$supplier_name = $data_supplier->supplier_name;
					}
				}
				$row[] = '<span class="text-nowrap">'.$supplier_name.'</span>';  

				$row[] = $aRow['date_buy'] != '' ? _d($aRow['date_buy']) : '';  
				$row[] = $aRow['unit_price'] != '' ? app_format_money($aRow['unit_price'], $currency_name) : '';  
				$row[] = $aRow['order_number'];  
				$row[] = (($aRow['warranty_period'] != '' && $aRow['warranty_period'] != 0) ? '<span class="text-nowrap">'.$aRow['warranty_period'].' '._l('months').'</span>' : '');  
				$row[] = (($aRow['warranty_period'] != '' && $aRow['warranty_period'] != 0) ? _d(get_expired_date($aRow['date_buy'], $aRow['warranty_period'])) : '');    
				$row[] = '<span class="text-nowrap">'.$aRow['description'].'</span>';  
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkout',0);  
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkin'); 
				$row[] = $this->fixed_equipment_model->count_log_detail($aRow[db_prefix().'fe_assets.id'], 'checkout', 1, 1);  
				$row[] = '<span class="text-nowrap">'._dt($aRow['date_creator']).'</span>';
				$row[] = '<span class="text-nowrap">'._dt($aRow['updated_at']).'</span>'; 
				$checkout_date = ''; 
				$expected_checkin_date = ''; 
				if($aRow['checkin_out'] == 2){
					if(is_numeric($aRow['checkin_out_id']) && $aRow['checkin_out_id'] > 0){
						$data_checkout = $this->fixed_equipment_model->get_checkin_out_data($aRow['checkin_out_id']);
						if($data_checkout){
							$expected_checkin_date =(($data_checkout->expected_checkin_date != '' || $data_checkout->expected_checkin_date != null) ? _d($data_checkout->expected_checkin_date) : '');
							$checkout_date = (($data_checkout->checkin_date != '' || $data_checkout->checkin_date != null) ? _d($data_checkout->checkin_date) : _d(date('Y-m-d'), $data_checkout->date_creator));
						}
					}
				}

				$row[] = '<span class="text-nowrap">'.$checkout_date.'</span>';  
				$row[] = '<span class="text-nowrap">'.$expected_checkin_date.'</span>';  
				$last_audit = '';
				$next_audit = '';
				$data_audit = $this->fixed_equipment_model->get_2_audit_info_asset($aRow['id']);
				if($data_audit){
					if(isset($data_audit[0]) && isset($data_audit[1])){
						$next_audit = _d(date('Y-m-d',strtotime($data_audit[0]['audit_date'])));
						$last_audit = _d(date('Y-m-d',strtotime($data_audit[1]['audit_date'])));
					}
					if(isset($data_audit[0]) && !isset($data_audit[1])){
						$next_audit = _d(date('Y-m-d',strtotime($data_audit[0]['audit_date'])));
					}
				}
				$row[] = '<span class="text-nowrap">'.$last_audit.'</span>';  
				$row[] = '<span class="text-nowrap">'.$next_audit.'</span>';  

				$button = '';
				if(is_admin() || has_permission('fixed_equipment_assets', '', 'view')){
					if($aRow['checkin_out'] == 2){
						$button = '<a class="btn btn-primary" data-asset_name="'.$aRow['assets_name'].'" data-serial="'.$aRow['series'].'" data-model="'.$aRow['model_name'].'" onclick="check_in_asset(this, '.$aRow[db_prefix().'fe_assets.id'].')" >' . _l('fe_checkin') . '</a>';  
					}
					else{
						if($status == 'deployable'){
							$button = '<a class="btn btn-danger" data-asset_name="'.$aRow['assets_name'].'" data-serial="'.$aRow['series'].'" data-model="'.$aRow['model_name'].'" onclick="check_out_asset(this, '.$aRow[db_prefix().'fe_assets.id'].')" >' . _l('fe_checkout') . '</a>';  					
						}
					}
				}
				
				$row[] = $button;
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
 * accessories location table
 * @return json 
 */
public function accessories_location_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}

			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];

			if(is_admin() || has_permission('fixed_equipment_accessories', '', 'view')){
				array_push($select, 'id');
			}

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];
			$manufacturer = $this->input->post('manufacturer');
			$category = $this->input->post('category');
			$location = $this->input->post('location');

			if(isset($manufacturer) && $manufacturer != ''){
				array_push($where, 'AND manufacturer_id = '.$manufacturer);
			}
			if(isset($category) && $category != ''){
				array_push($where, 'AND category_id = '.$category);
			}
			if(isset($location) && $location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}
			array_push($where, 'AND type = "accessory"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'category_id',
				'model_no',
				'manufacturer_id',  
				'asset_location',
				'quantity',
				'min_quantity',
				'unit_price',
				'checkin_out'     
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'accessory').'">';  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_accessories/'.$aRow['id'].'?re=detail_locations/'.$location).'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_accessories', '', 'view')){		
					$_data .= ' | <a href="javascript:void(0)" onclick="edit_accessories_location('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_assets_location/'.$aRow['id'].'/'.$location).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$min_quantity = $aRow['min_quantity'];  
				$avail = $aRow['quantity'] - $this->fixed_equipment_model->count_checkin_asset_by_parents($aRow['id']);
				$warning_class = '';
				$warning_attribute = '';
				if($avail < $min_quantity){
					$warning_class = 'text-danger bold';
					$warning_attribute = 'data-toggle="tooltip" data-placement="top" data-original-title="'._l('fe_the_quantity_has_reached_the_warning_level').'"';
				}
				$row[] = '<span class="text-nowrap '.$warning_class.'" '.$warning_attribute.'>'.$aRow['assets_name'].'</span>'.$_data;   

				$category_name = '';
				if(is_numeric($aRow['category_id']) && $aRow['category_id'] > 0){
					$data_category = $this->fixed_equipment_model->get_categories($aRow['category_id']);
					if($data_category){
						$category_name =  '<span class="text-nowrap">'.$data_category->category_name.'</span>';
					}
				}
				$row[] = $category_name;  

				$row[] = $aRow['model_no'];  

				$manufacturer_name = '';
				if(is_numeric($aRow['manufacturer_id']) && $aRow['manufacturer_id'] > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($aRow['manufacturer_id']);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = $manufacturer_name;  
				$row[] = $aRow['quantity'];  
				$row[] = $min_quantity;  
				$row[] = '<span class="'.$warning_class.'" '.$warning_attribute.'>'.$avail.'</span>';  
				$row[] = app_format_money($aRow['unit_price'], $currency_name);  

				if(is_admin() || has_permission('fixed_equipment_accessories', '', 'view')){
					if($aRow['checkin_out'] == 1){
						$event_add = ' disabled';
						if($avail > 0){
							$event_add = ' data-asset_name="'.$aRow['assets_name'].'" onclick="check_out_accessory(this, '.$aRow['id'].')"';
						}
						$row[] = '<a class="btn btn-danger"'.$event_add.'>' . _l('fe_checkout') . '</a>';  			
					}
				}

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* consumables location table
* @return json 
*/
public function consumables_location_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}

			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];

			if(is_admin() || has_permission('fixed_equipment_consumables', '', 'view')){
				array_push($select, 'id');
			}

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];

			$manufacturer = $this->input->post('manufacturer');
			$category = $this->input->post('category');
			$location = $this->input->post('location');

			if(isset($manufacturer) && $manufacturer != ''){
				array_push($where, 'AND manufacturer_id = '.$manufacturer);
			}
			if(isset($category) && $category != ''){
				array_push($where, 'AND category_id = '.$category);
			}
			if(isset($location) && $location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}
			array_push($where, 'AND type = "consumable"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'category_id',
				'model_no',
				'manufacturer_id',  
				'asset_location',
				'quantity',
				'min_quantity',
				'unit_price',
				'checkin_out'     
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$row[] = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'consumable').'">';  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_consumables/'.$aRow['id'].'?re=detail_locations/'.$location).'">' . _l('fe_view') . '</a>';
				if(is_admin() || has_permission('fixed_equipment_consumables', '', 'view')){
					$_data .= ' | <a href="javascript:void(0)" onclick="edit_consumables_location('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_assets_location/'.$aRow['id'].'/'.$location).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}
				$_data .= '</div>'; 

				$min_quantity = $aRow['min_quantity'];  
				$avail = $aRow['quantity'] - $this->fixed_equipment_model->count_checkin_asset_by_parents($aRow['id']);
				$warning_class = '';
				$warning_attribute = '';
				if($avail < $min_quantity){
					$warning_class = 'text-danger bold';
					$warning_attribute = 'data-toggle="tooltip" data-placement="top" data-original-title="'._l('fe_the_quantity_has_reached_the_warning_level').'"';
				}
				$row[] = '<span class="text-nowrap '.$warning_class.'" '.$warning_attribute.'>'.$aRow['assets_name'].'</span>'.$_data;

				$category_name = '';
				if(is_numeric($aRow['category_id']) && $aRow['category_id'] > 0){
					$data_category = $this->fixed_equipment_model->get_categories($aRow['category_id']);
					if($data_category){
						$category_name = '<span class="text-nowrap">'.$data_category->category_name.'</span>';
					}
				}
				$row[] = $category_name;  

				$row[] = $aRow['model_no'];  

				$manufacturer_name = '';
				if(is_numeric($aRow['manufacturer_id']) && $aRow['manufacturer_id'] > 0){
					$data_manufacturer = $this->fixed_equipment_model->get_asset_manufacturers($aRow['manufacturer_id']);
					if($data_manufacturer){
						$manufacturer_name = $data_manufacturer->name;
					}
				}
				$row[] = $manufacturer_name;  

				$row[] = $aRow['quantity'];  
				$row[] = $min_quantity;  
				$row[] = '<span class="'.$warning_class.'" '.$warning_attribute.'>'.$avail.'</span>';  
				$row[] = app_format_money($aRow['unit_price'], $currency_name);  

				if(is_admin() || has_permission('fixed_equipment_consumables', '', 'view')){
					if($aRow['checkin_out'] == 1){
						$event_add = ' disabled';
						if($avail > 0){
							$event_add = ' data-asset_name="'.$aRow['assets_name'].'" onclick="check_out_consumable(this, '.$aRow['id'].')"';
						}
						$row[] = '<a class="btn btn-danger"'.$event_add.'>' . _l('fe_checkout') . '</a>';  			
					}
				}

				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}

/**
* components location table
* @return json 
*/
public function components_location_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id',
				'id'
			];
			if(is_admin() || has_permission('fixed_equipment_components', '', 'view')){
				array_push($select, 'id');
			}
			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = [];

			$category = $this->input->post('category');
			$location = $this->input->post('location');
			if(isset($category) && $category != ''){
				array_push($where, 'AND category_id = '.$category);
			}
			if(isset($location) && $location != ''){
				array_push($where, 'AND asset_location = '.$location);
			}

			array_push($where, 'AND type = "component"');
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				'assets_name',
				'category_id',
				'series',
				'manufacturer_id',  
				'asset_location',
				'quantity',
				'min_quantity',
				'unit_price',
				'order_number',
				'date_buy',
				'checkin_out'     
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="'.admin_url('fixed_equipment/detail_components/'.$aRow['id'].'?re=detail_locations/'.$location).'">' . _l('fe_view') . '</a>';

				if(is_admin() || has_permission('fixed_equipment_components', '', 'view')){
					$_data .= ' | <a href="javascript:void(0)" onclick="edit_component_location('.$aRow['id'].'); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
					$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_assets_location/'.$aRow['id'].'/'.$location).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				}

				$_data .= '</div>'; 
				$avail = $aRow['quantity'] - $this->fixed_equipment_model->count_checkin_component_by_parents($aRow['id']);
				$min_quantity = $aRow['min_quantity'];  

				$warning_class = '';
				$warning_attribute = '';
				if($avail < $min_quantity){
					$warning_class = 'text-danger bold';
					$warning_attribute = 'data-toggle="tooltip" data-placement="top" data-original-title="'._l('fe_the_quantity_has_reached_the_warning_level').'"';
				}
				$row[] = '<span class="text-nowrap '.$warning_class.'" '.$warning_attribute.'>'.$aRow['assets_name'].'</span>'.$_data;  
				$row[] = $aRow['series'];  

				$category_name = '';
				if(is_numeric($aRow['category_id']) && $aRow['category_id'] > 0){
					$data_category = $this->fixed_equipment_model->get_categories($aRow['category_id']);
					if($data_category){
						$category_name = '<span class="text-nowrap">'.$data_category->category_name.'</span>';  
					}
				}
				$row[] = $category_name;  
				$remain = 0;
				$row[] = $aRow['quantity'];  
				$row[] = '<span class="'.$warning_class.'" '.$warning_attribute.'>'.$avail.'</span>';  
				$row[] = $min_quantity;  
				$row[] = $aRow['order_number'];  
				$row[] = _d($aRow['date_buy']);  
				$row[] = app_format_money($aRow['unit_price'], $currency_name);  
				if(is_admin() || has_permission('fixed_equipment_components', '', 'view')){
					if($aRow['checkin_out'] == 1){
						$event_add = ' disabled';
						if($avail > 0){
							$event_add = ' data-asset_name="'.$aRow['assets_name'].'" onclick="check_out_component(this, '.$aRow['id'].')"';
						}
						$row[] = '<a class="btn btn-danger"'.$event_add.'>' . _l('fe_checkout') . '</a>';  			
					}
				}
				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}


/**
 * check in assets
 * @return  
 */
public  function check_in_assets_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		$result = $this->fixed_equipment_model->check_in_assets($data);
		if($result > 0){
			if($data['type'] == 'checkout'){
				set_alert('success', _l('fe_checkout_successfully', _l('fe_assets')));
			}
			else{
				set_alert('success', _l('fe_checkin_successfully', _l('fe_assets')));
			}
		}
		else{
			if($data['type'] == 'checkout'){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_assets')));					
			}
			else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_assets')));					
			}
		}
		redirect(admin_url('fixed_equipment/detail_locations/'.$location));
	}
}


/**
* check in accessories
* @return  
*/
public  function check_in_accessories_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		$result = $this->fixed_equipment_model->check_in_accessories($data);
		if(is_numeric($result)){
			if($result == -1){
				set_alert('danger', _l('fe_this_accessory_has_been_checkout_for_this_user', _l('fe_accessories')));					
			}
			elseif($result == 0){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_accessories')));					
			}
			else{
				set_alert('success', _l('fe_checkout_successfully', _l('fe_accessories')));
			}
			redirect(admin_url('fixed_equipment/detail_locations/'.$location));
		}
		else{				
			redirect(admin_url('fixed_equipment/detail_locations/'.$location));
		}
	}
}

/**
* check in consumables
* @return  
*/
public  function check_in_consumables_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		$result = $this->fixed_equipment_model->check_in_consumables($data);
		if(is_numeric($result)){
			if($result == -1){
				set_alert('danger', _l('fe_this_consumables_has_been_checkout_for_this_user', _l('fe_consumables')));					
			}
			elseif($result == 0){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_consumables')));					
			}
			else{
				set_alert('success', _l('fe_checkout_successfully', _l('fe_consumables')));
			}
			redirect(admin_url('fixed_equipment/detail_locations/'.$location));
		}
		else{
			if($result == true){
				set_alert('success', _l('fe_checkin_successfully', _l('fe_consumables')));
			}
			else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_consumables')));					
			}
			redirect(admin_url('fixed_equipment/detail_locations/'.$location));
		}
	}
}

/**
* check in components
* @return  
*/
public  function check_in_components_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		$result = $this->fixed_equipment_model->check_in_components($data);
		if(is_numeric($result)){
			if($result == -1){
				set_alert('danger', _l('fe_this_component_has_been_checkout_for_this_asset', _l('fe_components')));					
			}
			elseif($result == 0){
				set_alert('danger', _l('fe_checkout_fail', _l('fe_components')));					
			}
			else{
				set_alert('success', _l('fe_checkout_successfully', _l('fe_components')));
			}
			redirect(admin_url('fixed_equipment/detail_locations/'.$location));
		}
		else{
			if($result == true){
				set_alert('success', _l('fe_checkin_successfully', _l('fe_components')));
			}
			else{
				set_alert('danger', _l('fe_checkin_fail', _l('fe_components')));					
			}
			redirect(admin_url('fixed_equipment/detail_locations/'.$location));
		}
	}
}

/**
 * other setting
 */
public function other_setting(){
	if($this->input->post()){
		$data = $this->input->post();
		$affected_row = 0;
		if(isset($data['fe_googlemap_api_key'])){
			$res = update_option('fe_googlemap_api_key', $data['fe_googlemap_api_key']);
			if($res){
				$affected_row++;
			}
		}

		if($affected_row > 0){
			set_alert('success', _l('fe_saved_successfully', _l('fe_settings')));
		}
		else{
			set_alert('danger', _l('fe_save_fail', _l('fe_settings')));					
		}
		
	}
	redirect(admin_url('fixed_equipment/settings?tab=other_setting'));
}

/**
 * delete assets location
 */
public function delete_assets_location($id, $location_id){
	if($id != ''){
		$result =  $this->fixed_equipment_model->delete_assets($id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_assets')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_assets')));					
		}
	}
	redirect(admin_url('fixed_equipment/detail_locations/'.$location_id));
}

/**
 * update asset location
 */
public function update_asset_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		if ($this->input->post('id')) {
			$id = $data['id'];
			unset($data['id']);
			$success = $this->fixed_equipment_model->update_asset($data, $id);
			if ($success) {
				$message = _l('fe_updated_successfully', _l('fe_asset'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_updated_fail', _l('fe_asset'));
				set_alert('danger',$message);
			}
		}
		redirect(admin_url('fixed_equipment/detail_locations/'.$location));
	}
}

/**
 * update accessories location
 */
public function update_accessories_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		if ($this->input->post('id')) {
			$success = $this->fixed_equipment_model->update_accessories($data);
			if($success == 1){
				$message = _l('fe_quantity_not_valid', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 2){
				$message = _l('fe_this_accessory_not_exist', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 3){
				$message = _l('fe_quantity_is_unknown', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 4) {
				$message = _l('fe_updated_successfully', _l('fe_accessories'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_no_data_changes', _l('fe_accessories'));
				set_alert('warning',$message);
			}
			fe_handle_item_file($data['id'], 'accessory');
		}
		redirect(admin_url('fixed_equipment/detail_locations/'.$location));
	}
}

/**
* update consumables location
*/
public function update_consumables_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		if ($this->input->post('id')) {
			$success = $this->fixed_equipment_model->update_consumables($data);
			if($success == 1){
				$message = _l('fe_quantity_not_valid', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 2){
				$message = _l('fe_this_consumables_not_exist', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 3){
				$message = _l('fe_quantity_is_unknown', _l('fe_accessories'));
				set_alert('danger',$message);
			}
			elseif($success == 4) {
				$message = _l('fe_updated_successfully', _l('fe_accessories'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_no_data_changes', _l('fe_accessories'));
				set_alert('warning',$message);
			}
			fe_handle_item_file($data['id'], 'consumable');
		}
		redirect(admin_url('fixed_equipment/detail_locations/'.$location));
	}
}

/**
* update components location
*/
public function update_components_location(){
	if ($this->input->post()) {
		$data             = $this->input->post();
		$location = '';
		if(isset($data['location'])){
			$location = $data['location'];
			unset($data['location']);
		}
		else{
			redirect(admin_url('fixed_equipment/dashboard'));
		}
		if ($this->input->post('id')) {
			$success = $this->fixed_equipment_model->update_components($data);
			if ($success == 1) {
				$message = _l('fe_updated_successfully', _l('fe_components'));
				set_alert('success',$message);
			}
			else{
				$message = _l('fe_no_data_changes', _l('fe_components'));
				set_alert('warning',$message);
			}
			fe_handle_item_file($data['id'], 'component');
		}
		redirect(admin_url('fixed_equipment/detail_locations/'.$location));
	}
}

/**
 * staff asset table
 * @return json 
 */
public function staff_asset_table(){
	if ($this->input->is_ajax_request()) {
		if($this->input->post()){
			$this->load->model('currencies_model');
			$base_currency = $this->currencies_model->get_base_currency();
			$currency_name = '';
			if(isset($base_currency)){
				$currency_name = $base_currency->name;
			}
			$select = [
				db_prefix().'fe_assets.id',
				db_prefix().'fe_assets.id',
				db_prefix().'fe_assets.id',
				db_prefix().'fe_assets.id',
				db_prefix().'fe_assets.id',
				db_prefix().'fe_assets.id'
			];

			$where        = [];
			$aColumns     = $select;
			$sIndexColumn = 'id';
			$sTable       = db_prefix() . 'fe_assets';
			$join         = ['LEFT JOIN '.db_prefix().'fe_checkin_assets ON '.db_prefix().'fe_assets.id = '.db_prefix().'fe_checkin_assets.item_id', 
				'LEFT JOIN '.db_prefix().'fe_seats ON '.db_prefix().'fe_assets.id = '.db_prefix().'fe_seats.license_id'
			];
			array_push($where, 'AND '.db_prefix().'fe_assets.active=1');
			$staffid = $this->input->post('staffid');
			if(isset($staffid) && $staffid != ''){
				$query = 'AND (('.db_prefix().'fe_assets.type="asset" and '.db_prefix().'fe_assets.checkin_out = 2 and '.db_prefix().'fe_assets.checkin_out_id = '.db_prefix().'fe_checkin_assets.id and '.db_prefix().'fe_checkin_assets.type="checkout" and '.db_prefix().'fe_checkin_assets.checkout_to="user" and (('.db_prefix().'fe_checkin_assets.requestable = 0 and '.db_prefix().'fe_checkin_assets.request_status = 0) or ('.db_prefix().'fe_checkin_assets.requestable = 1 and '.db_prefix().'fe_checkin_assets.request_status = 1)) and '.db_prefix().'fe_checkin_assets.staff_id='.$staffid.') OR
					('.db_prefix().'fe_assets.type="license" and '.db_prefix().'fe_seats.to = "user" and '.db_prefix().'fe_seats.to_id='.$staffid.') OR
					('.db_prefix().'fe_assets.type="accessory" and '.db_prefix().'fe_checkin_assets.type="checkout" and '.db_prefix().'fe_checkin_assets.status=2 and '.db_prefix().'fe_checkin_assets.checkout_to="user" and (('.db_prefix().'fe_checkin_assets.requestable = 0 and '.db_prefix().'fe_checkin_assets.request_status = 0) or ('.db_prefix().'fe_checkin_assets.requestable = 1 and '.db_prefix().'fe_checkin_assets.request_status = 1)) and '.db_prefix().'fe_checkin_assets.staff_id='.$staffid.') OR
					('.db_prefix().'fe_assets.type="consumable" and '.db_prefix().'fe_checkin_assets.type="checkout" and '.db_prefix().'fe_checkin_assets.status=2 and '.db_prefix().'fe_checkin_assets.checkout_to="user" and (('.db_prefix().'fe_checkin_assets.requestable = 0 and '.db_prefix().'fe_checkin_assets.request_status = 0) or ('.db_prefix().'fe_checkin_assets.requestable = 1 and '.db_prefix().'fe_checkin_assets.request_status = 1)) and '.db_prefix().'fe_checkin_assets.staff_id='.$staffid.'))';
				array_push($where, $query);
			}
			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
				db_prefix().'fe_assets.id',
				db_prefix().'fe_assets.assets_name',
				db_prefix().'fe_assets.model_id',
				db_prefix().'fe_assets.series',
				db_prefix().'fe_assets.type',
				db_prefix().'fe_checkin_assets.date_creator as checkout_date1',
				db_prefix().'fe_assets.checkin_out_id as checkin_out_id',
				db_prefix().'fe_seats.date_creator as checkout_date2'
			]);


			$output  = $result['output'];
			$rResult = $result['rResult'];
			foreach ($rResult as $aRow) {
				$row = [];
				$row[] = $aRow['id'];  
				$image = '';
				$checkout_date = _dt($aRow['checkout_date1']);					
				if($aRow['type'] == 'asset'){
					$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">'; 					
				}
				if($aRow['type'] == 'consumable'){
					$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'consumable').'">';  
				}
				if($aRow['type'] == 'accessory'){
					$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'accessory').'">';  
				}
				if($aRow['type'] == 'license'){
					$checkout_date = _dt($aRow['checkout_date2']);					
					$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['id'], 'license').'">';  
				}
				$row[] = $aRow['assets_name'];  
				$row[] = $image;  
				$row[] = $aRow['series'];  
				$row[] = _l('fe_'.$aRow['type']);  

				$row[] = $checkout_date;  
				$sign_document = '';
				$sign_document_status = '';
				if(is_numeric($aRow['checkin_out_id'])){
					$data_document = $this->fixed_equipment_model->get_sign_document_check_in_out($aRow['checkin_out_id']);
					if($data_document){
						$sign_document = '<a href="'.admin_url('fixed_equipment/checkout_managements#'.$data_document->id).'" >#'.$data_document->reference.'</a>';
						$status = $data_document->status;
						if($status == 1){
							$sign_document_status = '<span class="label label-danger">'._l('fe_not_yet_sign').'</span>';
						}
						if($status == 2){
							$sign_document_status = '<span class="label label-warning">'._l('fe_signing').'</span>';
						}
						if($status == 3){
							$sign_document_status = '<span class="label label-success">'._l('fe_signed').'</span>';
						}
					}
				}
				$row[] = $sign_document_status;  
				$row[] = $sign_document;  


				$output['aaData'][] = $row;                                      
			}

			echo json_encode($output);
			die();
		}
	}
}
/**
 * get asset staff predefined kit
 * @return  
 */
public function get_asset_staff_predefined_kit($id, $staffid){

	$html = '';
	$data = $this->fixed_equipment_model->get_list_checked_out_predefined_kit_staff($staffid, $id);
	if($data){
		foreach ($data as $row) {
			$model_id = '';
			$serial = '';
			$data_asset = $this->fixed_equipment_model->get_assets($row['item_id']);
			if($data_asset){
				$model_id = $data_asset->model_id;
				$serial = $data_asset->series;
			}
			$asset_name = '';
			if($row['asset_name'] != '' && $serial != ''){
				$asset_name = $row['asset_name'].' - '.$serial;
			}
			if($row['asset_name'] != '' && $serial == ''){
				$asset_name = $row['asset_name'];
			}
			if($row['asset_name'] == '' && $serial != ''){
				$asset_name = $serial;
			}
			$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($model_id, 'models').'">'; 
			$html .= '<div class="alert alert-info mbot0">'.$image.' '.$asset_name.'</div>';
		}
	}
	if($html != ''){
		$html = '<div class="row"><div class="col-md-12 text-left mtop15 mbot5"><strong>'._l('fe_assets_currently_checked_out_to_this_user').'</strong><hr></div></div>'.$html;
	}
	echo json_encode($html);
}

 /**
 * asset staff history table
 * @return json 
 */
 public function asset_staff_history_table(){
 	if ($this->input->is_ajax_request()) {
 		if($this->input->post()){
 			$this->load->model('staff_model');
 			$staffid = $this->input->post('staffid');
 			$select = [
 				'id',
 				'id',
 				'id',
 				'id',
 				'id'			
 			];
 			$where        = [];

 			array_push($where, 'AND '.db_prefix() . 'fe_log_assets.to = "user" AND (action = "checkout" OR action = "checkin") AND to_id = '.$staffid);

 			$aColumns     = $select;
 			$sIndexColumn = 'id';
 			$sTable       = db_prefix() . 'fe_log_assets';
 			$join         = [];

 			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
 				'id',
 				'admin_id',
 				'action',
 				'target',
 				'item_id',
 				'changed',
 				db_prefix() . 'fe_log_assets.to',
 				'to_id',
 				'notes',
 				'date_creator'
 			]);


 			$output  = $result['output'];
 			$rResult = $result['rResult'];
 			foreach ($rResult as $aRow) {
 				$row = [];
 				$row[] = _dt($aRow['date_creator']);  
 				$row[] = get_staff_full_name($aRow['admin_id']);  
 				$row[] = _l('fe_'.$aRow['action']);  

 				$asset = '';
 				$data_asset = $this->fixed_equipment_model->get_assets($aRow['item_id']);
 				if($data_asset){	 					
 					if($data_asset->assets_name != '' && $data_asset->series != ''){
 						$asset = $data_asset->assets_name.' '.$data_asset->series; 
 					}
 					if($data_asset->assets_name != '' && $data_asset->series == ''){
 						$asset = $data_asset->assets_name; 
 					}
 					if($data_asset->assets_name == '' && $data_asset->series != ''){
 						$asset = $data_asset->series; 
 					}
 				}

 				$row[] = $asset;  
 				$row[] = $aRow['notes'];  
 				$output['aaData'][] = $row;                                      
 			}

 			echo json_encode($output);
 			die();
 		}
 	}
 }

/**
* delete asset maintenances detail 
* @param  integer $id 
*/
public function delete_asset_maintenance_detail($id, $maintenance_id){
	if($maintenance_id != ''){
		$result =  $this->fixed_equipment_model->delete_asset_maintenances($maintenance_id);
		if($result){
			set_alert('success', _l('fe_deleted_successfully', _l('fe_depreciations')));
		}
		else{
			set_alert('danger', _l('fe_deleted_fail', _l('fe_depreciations')));					
		}
	}
	redirect(admin_url('fixed_equipment/detail_asset/'.$id.'?tab=maintenances'));
}


	/**
	 * permission table
	 */
	public function permission_table() {
		if ($this->input->is_ajax_request()) {

			$select = [
				'staffid',
				'CONCAT(firstname," ",lastname) as full_name',
				'firstname', //for role name
				'email',
				'phonenumber',
			];
			$where = [];
			$where[] = 'AND ' . db_prefix() . 'staff.admin != 1';

			$arr_staff_id = fe_get_staff_id_permissions();

			if (count($arr_staff_id) > 0) {
				$where[] = 'AND ' . db_prefix() . 'staff.staffid IN (' . implode(', ', $arr_staff_id) . ')';
			} else {
				$where[] = 'AND ' . db_prefix() . 'staff.staffid IN ("")';
			}

			$aColumns = $select;
			$sIndexColumn = 'staffid';
			$sTable = db_prefix() . 'staff';
			$join = ['LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = ' . db_prefix() . 'staff.role'];

			$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'roles.name as role_name', db_prefix() . 'staff.role']);

			$output = $result['output'];
			$rResult = $result['rResult'];

			$not_hide = '';

			foreach ($rResult as $aRow) {
				$row = [];

				$_data = '';
				$_data .= '<div class="row-options">';
				$_data .= '<a href="javascript:void(0)" onclick="permissions_update(' . $aRow['staffid'] . ', ' . $aRow['role'] . ', ' . $not_hide . '); return false;" class="text-danger">' . _l('fe_edit') . '</a>';
				$_data .= ' | <a href="'.admin_url('fixed_equipment/delete_permission/'.$aRow['staffid']).'" class="text-danger _delete">' . _l('fe_delete') . '</a>';
				$_data .= '</div>'; 

				$row[] = '<a href="' . admin_url('staff/member/' . $aRow['staffid']) . '">' . $aRow['full_name'] . '</a>'.$_data;

				$row[] = $aRow['role_name'];
				$row[] = $aRow['email'];
				$row[] = $aRow['phonenumber'];

				$options = '';

			

				$row[] = $options;

				$output['aaData'][] = $row;
			}

			echo json_encode($output);
			die();
		}
	}

/**
 * permission modal
 */
	public function permission_modal() {
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		$this->load->model('staff_model');

		if ($this->input->post('slug') === 'update') {
			$staff_id = $this->input->post('staff_id');
			$role_id = $this->input->post('role_id');

			$data = ['funcData' => ['staff_id' => isset($staff_id) ? $staff_id : null]];

			if (isset($staff_id)) {
				$data['member'] = $this->staff_model->get($staff_id);
			}

			$data['roles_value'] = $this->roles_model->get();
			$data['staffs'] = fe_get_staff_id_not_permissions();
			$add_new = $this->input->post('add_new');

			if ($add_new == ' hide') {
				$data['add_new'] = ' hide';
				$data['display_staff'] = '';
			} else {
				$data['add_new'] = '';
				$data['display_staff'] = ' hide';
			}

			$this->load->view('settings/includes/permission_modal', $data);
		}
	}

	/**
 * staff id changed
 * @param  integer $staff_id
 * @return json
 */
	public function staff_id_changed($staff_id) {
		$role_id = '';
		$status = 'false';

		$staff = $this->staff_model->get($staff_id);
		if ($staff) {
			$role_id = $staff->role;
			$status = 'true';

		}

		echo json_encode([
			'role_id' => $role_id,
			'status' => $status,
		]);
		die;
	}

	/**
 * hr profile update permissions
 * @param  string $id
 */
	public function update_permissions($id = '') {
		if (!is_admin()) {
			access_denied('fixed_equipment');
		}
		$data = $this->input->post();

		if (!isset($id) || $id == '') {
			$id = $data['staff_id'];
		}

		if (isset($id) && $id != '') {
			if (is_admin()) {
				if (isset($data['administrator'])) {
					$data['admin'] = 1;
					unset($data['administrator']);
				} else {
					if ($id != get_staff_user_id()) {
						if ($id == 1) {
							return [
								'cant_remove_main_admin' => true,
							];
						}
					} else {
						return [
							'cant_remove_yourself_from_admin' => true,
						];
					}
					$data['admin'] = 0;
				}
			}

			$this->db->where('staffid', $id);
			$this->db->update(db_prefix() . 'staff', [
				'role' => $data['role'],
			]);

			$response = $this->staff_model->update_permissions((isset($data['admin']) && $data['admin'] == 1 ? [] : $data['permissions']), $id);
		} else {
			$this->load->model('roles_model');
			$role_id = $data['role'];
			unset($data['role']);
			unset($data['staff_id']);
			$data['update_staff_permissions'] = true;
			$response = $this->roles_model->update($data, $role_id);
		}

		if (is_array($response)) {
			if (isset($response['cant_remove_main_admin'])) {
				set_alert('warning', _l('staff_cant_remove_main_admin'));
			} elseif (isset($response['cant_remove_yourself_from_admin'])) {
				set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
			}
		} elseif ($response == true) {
			set_alert('success', _l('ts_updated_successfully', _l('staff_member')));
		}
		redirect(admin_url('fixed_equipment/settings?tab=permission'));
	}

	/**
	 * delete permission
	 * @param  integer $id
	 */
	public function delete_permission($id) {
		if (!is_admin()) {
			access_denied('fixed_equipment');
		}
		$response = $this->fixed_equipment_model->delete_permission($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('hr_is_referenced', _l('department_lowercase')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('fixed_equipment/settings?tab=permission'));
	}


	/**
	 * checkout management table
	 * @return json 
	 */
	public function checkout_management_table(){
		if ($this->input->is_ajax_request()) {
			if($this->input->post()){
				$hide_first_column = ' hide';
				if(is_admin() || has_permission('fixed_equipment_sign_manager', '', 'create')){
					$hide_first_column = '';
				}
				$this->load->model('currencies_model');
				$base_currency = $this->currencies_model->get_base_currency();
				$currency_name = '';
				if(isset($base_currency)){
					$currency_name = $base_currency->name;
				}
				$select = [
					db_prefix().'fe_checkin_assets.id',
					db_prefix().'fe_checkin_assets.id',
					db_prefix().'fe_checkin_assets.id',
					db_prefix().'fe_checkin_assets.id',
					db_prefix().'fe_checkin_assets.id',
					db_prefix().'fe_checkin_assets.id'
				];

				$where        = [];
				$aColumns     = $select;
				$sIndexColumn = 'id';
				$sTable       = db_prefix() . 'fe_checkin_assets';
				$join         = ['LEFT JOIN '.db_prefix().'fe_assets ON '.db_prefix().'fe_assets.id = '.db_prefix().'fe_checkin_assets.item_id', 'left join '.db_prefix().'staff on '.db_prefix().'staff.staffid = '.db_prefix().'fe_checkin_assets.staff_id', 'left join '.db_prefix().'fe_locations on '.db_prefix().'fe_locations.id = '.db_prefix().'fe_checkin_assets.location_id', 'LEFT JOIN '.db_prefix().'fe_sign_documents ON FIND_IN_SET('.db_prefix().'fe_checkin_assets.id, '.db_prefix().'fe_sign_documents.checkin_out_id)'];

				$location_id = $this->input->post('location_id');
				if($location_id != ''){
					array_push($where, ' AND '.db_prefix().'fe_checkin_assets.location_id = '.$location_id.'');
				}

				$asset_id = $this->input->post('asset_id');
				if($asset_id != ''){
					array_push($where, ' AND '.db_prefix().'fe_checkin_assets.asset_id = '.$asset_id.'');
				}

				$staff_id = $this->input->post('staff_id');
				if($staff_id != ''){
					array_push($where, ' AND '.db_prefix().'fe_checkin_assets.staff_id = '.$staff_id.'');
				}

				$date_creator = $this->input->post('date');
				if($date_creator != ''){
					array_push($where, ' AND date('.db_prefix().'fe_checkin_assets.date_creator) = "'.$date_creator.'"');
				}

				$check_type = $this->input->post('check_type');
				if($check_type != ''){
					array_push($where, ' AND '.db_prefix().'fe_checkin_assets.type = "'.$check_type.'"');
				}

				$from_date = $this->input->post('from_date');
				$to_date = $this->input->post('to_date');
				if($from_date != '' && $to_date != ''){
					array_push($where, ' AND date('.db_prefix().'fe_checkin_assets.date_creator) between \''.fe_format_date($from_date).'\' and \''.fe_format_date($to_date).'\'');
				}

				$sign_document = $this->input->post('sign_document');
				if($sign_document != ''){
					array_push($where, ' AND '.db_prefix().'fe_sign_documents.id = "'.$sign_document.'"');
				}

				$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
					db_prefix().'fe_checkin_assets.id',
					'assets_name',
					'item_id',
					db_prefix().'fe_assets.model_id',
					db_prefix().'fe_assets.series',
					db_prefix().'fe_checkin_assets.type as check_type',
					db_prefix().'fe_checkin_assets.item_type',
					db_prefix().'fe_sign_documents.id as sign_document_id',
					db_prefix().'fe_sign_documents.reference as sign_document_reference',					
					db_prefix().'fe_checkin_assets.date_creator as checkout_date'
				]);
				$output  = $result['output'];
				$rResult = $result['rResult'];
				foreach ($rResult as $aRow) {
					$row = [];

					$row[] = '<input type="checkbox" class="individual'.$hide_first_column.'" data-id="'.$aRow['id'].'" onchange="checked_add(this); return false;"/>';     
					$row[] = $aRow['id'];  

					$image = '';
					$url = '';
					$item_id = $aRow['item_id'];
					$assets_name = $aRow['assets_name'];
					$assets_tag = $aRow['series'];
					$checkout_date = _dt($aRow['checkout_date']);					
					if($aRow['item_type'] == 'asset'){
						$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($aRow['model_id'], 'models').'">';
						$url = admin_url('fixed_equipment/detail_asset/'.$item_id.'?tab=details');
					}
					if($aRow['item_type'] == 'consumable'){
						$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($item_id, 'consumable').'">'; 
						$url = admin_url('fixed_equipment/detail_consumables/'.$item_id);
					}
					if($aRow['item_type'] == 'component'){
						$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($item_id, 'component').'">';  
						$url = admin_url('fixed_equipment/detail_components/'.$item_id);
					}
					if($aRow['item_type'] == 'accessory'){
						$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($item_id, 'accessory').'">';  
						$url = admin_url('fixed_equipment/detail_accessories/'.$item_id);
					}
					if($aRow['item_type'] == 'license'){
						$license_id = '';		
						$data_seats = $this->fixed_equipment_model->get_seats($aRow['item_id']);
						if($data_seats){
							$license_id = $data_seats->license_id;		
							$data_licenses = $this->fixed_equipment_model->get_assets($license_id);
							if($data_licenses){
								$assets_name = $data_licenses->assets_name;
								$assets_tag = $data_licenses->series;
							}
						}
						$item_id = $license_id;
						$image = '<img class="img img-responsive staff-profile-image-small pull-left" src="'.$this->fixed_equipment_model->get_image_items($license_id, 'license').'">'; 
						$url = admin_url('fixed_equipment/detail_licenses/'.$item_id.'?tab=details');
					}

					$row[] = '<a href="'.$url.'">' . $assets_name . '</a>';  
					$row[] = $image;  
					$row[] = $assets_tag;  
					$row[] = ($aRow['item_type'] != null ? _l('fe_'.$aRow['item_type']) : '');  
					$check_type = '';
					if($aRow['check_type'] == 'checkout'){
						$check_type = '<span class="label label-warning">'._l('fe_'.$aRow['check_type']).'</span>';
					}
					else{
						$check_type = '<span class="label label-success">'._l('fe_'.$aRow['check_type']).'</span>';
					}
					$row[] = $check_type;  

					$row[] = $checkout_date;  

					$sign_doc = '';
					if($aRow['sign_document_id'] != '' && $aRow['sign_document_reference'] != ''){
						$sign_doc = '<a href="javascript:void(0)" onclick="detail_sign_document('.$aRow['sign_document_id'].')">#' . $aRow['sign_document_reference'] . '</a>';
					}
					$row[] = $sign_doc;  
					$output['aaData'][] = $row;                                      
				}
				echo json_encode($output);
				die();
			}
		}
	}
   
   /**
   * checkout managements
   */
	public function checkout_managements(){
		if (!(has_permission('fixed_equipment_sign_manager', '', 'view_own') || has_permission('fixed_equipment_sign_manager', '', 'view') || is_admin())) {          
			access_denied('fixed_equipment');
		}
		$data['title']    = _l('fe_sign_manager');	
		$data['locations'] = $this->fixed_equipment_model->get_locations();
		$data['assets'] = $this->fixed_equipment_model->get_assets();
		$data['staffs'] = $this->staff_model->get();
		$data['sign_documents'] = $this->fixed_equipment_model->get_sign_document();
		$data['check_in_out_not_sign'] = $this->fixed_equipment_model->get_check_in_out_not_yet_sign();
		$this->load->view('checkout_management', $data);
	}

	/**
   * detail checkout
   */
	public function detail_checkout(){
		if (!(has_permission('fixed_equipment_sign_manager', '', 'view_own') || has_permission('fixed_equipment_sign_manager', '', 'view') || is_admin())) {          
			access_denied('fixed_equipment');
		}
		$data['title']    = _l('fe_detail_checkout');	

		$this->load->view('detail_checkout', $data);
	}

	/**
	 * get sign modal	
	 * @return string
	 */
	public function get_sign_modal(){
		$html = '';
		$list = $this->input->post('id_list');
		$data['list_id'] = $list;
		$checkout_to_staff = 0;
		$data['check_in_out'] = $this->fixed_equipment_model->get_check_in_out_list($list);
		if(count($data['check_in_out']) > 0 && isset($data['check_in_out'][0])){
			$checkout_to_staff = $this->fixed_equipment_model->get_staff_check_in_out($data['check_in_out'][0]['id']);
		}
		$html = $this->load->view('includes/sign_modal', $data, true);
		echo $html;
		die;
	}

	/**
	 * 	
	 * @param  integer $staffid 
	 * @return integer          
	 */
	public function get_check_in_out_staff_option($staffid){
		$check_in_out_not_sign = $this->fixed_equipment_model->get_check_in_out_not_yet_sign($staffid);
		$html = '';
		foreach ($check_in_out_not_sign as $key => $value) {
			$html .= '<option value="'.$value['id'].'">#'.$value['id'].' '.$value['asset_name'].'</option>';
		}
		echo $html;
		die;
	}

	public function add_sign_document(){
		if($this->input->post()){
			$data = $this->input->post();
			$res = $this->fixed_equipment_model->add_sign_document($data);
			if($res){
				set_alert('success', _l('fe_created_successfully'));
				redirect(admin_url('fixed_equipment/checkout_managements#'.$res));
			}
			else{
				set_alert('danger', _l('fe_create_failed'));				
			}
		}
		redirect(admin_url('fixed_equipment/checkout_managements'));
	}

	/**
	 * get sign document detail
	 * @param  integer $id 
	 * @return string     
	 */
	public function get_sign_document_detail($id){
		$html = '';
		$checkout_to_staff = 0;
		$data['id'] = $id;
		$data['sign_documents'] = $this->fixed_equipment_model->get_sign_document($id);
		$data['signers'] = $this->fixed_equipment_model->get_signer_by_master($id);
		$html = $this->load->view('includes/sign_detail', $data, true);
		echo $html;
		die;
	}

	/**
	 * change sign document status
	 * @param  integer $id     
	 * @param  integer $status 
	 * @return json         
	 */
	public function change_sign_document_status($id, $status){
		$message = '';
		$success = $this->fixed_equipment_model->change_sign_document_status($id, $status);
		if($success){
			$message = _l('fe_changed_status_successfully');
		}
		else{
			$message = _l('fe_change_status_failed');
		}
		echo json_encode([
			'success' => $success,
			'message' => $message
		]);
	}

	/**
	 * staff sign document
	 */
	public function staff_sign_document(){
		if($this->input->post()){
			$id = $this->input->post('id');
			$document_id = $this->input->post('document_id');
			process_digital_signature_image($this->input->post('signature', false), FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER .'/sign_document/'. $id);
			$data_update['firstname'] = $this->input->post('firstname');
			$data_update['lastname'] = $this->input->post('lastname');
			$data_update['email'] = $this->input->post('email');
			$data_update['ip_address'] = fe_get_client_ip();
			$data_update['date_of_signing'] = date('Y-m-d H:i:s');

			$result = $this->fixed_equipment_model->update_signer_info($id, $data_update);
			if($result){
				set_alert('success', _l('fe_signed_successfully'));
			}
			else{
				set_alert('danger', _l('fe_sign_failed'));
			}
		}
		if(is_numeric($document_id)){
			redirect(admin_url('fixed_equipment/checkout_managements#'.$document_id));
		}
		else{
			redirect(admin_url('fixed_equipment/checkout_managements'));			
		}
	}
	/**
	 * [sign_detail_pdf
	 * @param  integer $id 
	 */
	public function sign_detail_pdf($id){
		if (!$id) {
			redirect(admin_url('fixed_equipment/checkout_managements'));
		}
		$type = 'D';
		if ($this->input->get('output_type')) {
			$type = $this->input->get('output_type');
		}

		if ($this->input->get('print')) {
			$type = 'I';
		}
		$data['title'] = _l('fe_sign_documents');
		$data['type'] = $type;
		$data['id'] = $id;
		$data['sign_documents'] = $this->fixed_equipment_model->get_sign_document($id);
		$data['signers'] = $this->fixed_equipment_model->get_signer_by_master($id);

		$html = $this->load->view('sign_document/sign_document_html_view', $data, true);
		$html .= '<link href="' . FCPATH.'modules/fixed_equipment/assets/css/sign_document_pdf.css"  rel="stylesheet" type="text/css" />';
		$data['html'] = $html;
		$this->load->view('sign_document/preview_pdf', $data);
	}

	/**
	 * get asset info from qr code
	 * @return json 
	 */
	public  function get_asset_info_from_qr_code(){
		$data = $this->input->post();
		$success = false;
		$id = '';
		$html = '';
		$asset_data = $this->fixed_equipment_model->get_asset_by_qr_code($data['qrcode']);
		if($asset_data){
			$success = true;
			$model_name_s = '';
			$data_model = $this->fixed_equipment_model->get_models($asset_data->model_id);
			if($data_model){
				$model_name_s = $data_model->model_name;
			}

			$location_name = '';
			if(is_numeric($asset_data->location_id)){
				$data_alocation = $this->fixed_equipment_model->get_locations($asset_data->location_id);
				if($data_alocation){
					$location_name = $data_alocation->location_name;
				}
			}
			$supplier_name_s = '';
			if(is_numeric($asset_data->supplier_id)){
				$data_supplier = $this->fixed_equipment_model->get_suppliers($asset_data->supplier_id);
				if($data_supplier){
					$supplier_name_s = $data_supplier->supplier_name;
				}
			}
			$id = $asset_data->id;
			$html .= '<div class="row"><div class="col-md-3"><img class="img img-responsive pull-left mtop10 mright10 mbot10" src="'.$this->fixed_equipment_model->get_image_items($asset_data->model_id, 'models').'"/></div>';
			$html .= '<div class="col-md-9"><a target="_blank" href="'.admin_url('fixed_equipment/detail_asset/'.$id.'?tab=details').'"><h4 class="bold">'.$asset_data->assets_name.'</h4></a>';
			$html .= (($asset_data->series != null && $asset_data->series != '') ? _l('fe_asset_tag').': '.$asset_data->series.'<br>' : '');
			$html .= (($model_name_s != '') ? _l('fe_models').': '.$model_name_s.'<br>' : '');
			$html .= (($location_name != '') ? _l('fe_locations').': '.$location_name.'<br>' : '');
			$html .= (($asset_data->date_buy != null && $asset_data->date_buy != '') ? _l('fe_purchase_date').': '.$asset_data->date_buy.'<br>' : '');
			$html .= (($asset_data->unit_price != null && $asset_data->unit_price != '') ? _l('fe_purchase_cost').': '.app_format_money($asset_data->unit_price, '').'<br>' : '');
			$html .= (($asset_data->warranty_period != null && $asset_data->warranty_period != '' && $asset_data->warranty_period != 0) ? _l('fe_warranty').': '.$asset_data->warranty_period.'<br>' : '');
			$html .= (($supplier_name_s != '') ? _l('fe_supplier').': '.$supplier_name_s.'<br>' : '');
			$html .= '</div>';
			$html .= '</div>';
		}
		echo json_encode([
			'id' => $id,
			'success' => $success,
			'html' => $html
		]);
	}



	/**
	 * print qr PDF
	 * @param  integer $id 
	 */
	public function print_qrcode_pdf($id_s){
		$type = 'D';
		if ($this->input->get('output_type')) {
			$type = $this->input->get('output_type');
		}

		if ($this->input->get('print')) {
			$type = 'I';
		}

		$data['title'] = _l('fe_print_qrcode');
		$data['type'] = $type;
		$data['list_id'] = explode(',', urldecode($id_s));
		$html = $this->load->view('asset_managerments/print_qrcode_html_view', $data, true);
		$html .= '<link href="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/css/sign_document_pdf.css') . '"  rel="stylesheet" type="text/css" />';
		$data['html'] = $html;
		$this->load->view('asset_managerments/preview_pdf', $data);
	}
	/**
	 * bulk upload
	 * @param  string $type 
	 */
	public function bulk_upload($type){
		$data['title'] = _l('fe_bulk_upload');
		$data['type'] = $type;
			$this->load->model('staff_model');
		$data_staff = $this->staff_model->get(get_staff_user_id());

		/*get language active*/
		if ($data_staff) {
			if ($data_staff->default_language != '') {
				$data['active_language'] = $data_staff->default_language;
			} else {
				$data['active_language'] = get_option('active_language');
			}
		} else {
			$data['active_language'] = get_option('active_language');
		}
		$this->load->view('asset_managerments/bulk_upload', $data);
	}
	/**
	 * import xlsx item
	 * @param  string $type 
	 */
	public function import_xlsx_item($type){
		if (!class_exists('XLSXReader_fin')) {
			require_once module_dir_path(FIXED_EQUIPMENT_MODULE_NAME) . 'assets/plugins/XLSXReader/XLSXReader.php';
		}
		require_once module_dir_path(FIXED_EQUIPMENT_MODULE_NAME) . 'assets/plugins/XLSXWriter/xlsxwriter.class.php';

		$total_row_success = 0;
		$total_row_false = 0;
		$total_rows = 0;
		$string_error = '';
		$error_filename = '';
		$file_type = '';
		$result = new stdClass();
		if ($this->input->post()) {
			$data = $this->input->post();
			if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
				$file_type = substr($_FILES["file_csv"]["name"],strrpos($_FILES["file_csv"]["name"],"."),(strlen($_FILES["file_csv"]["name"]) - strrpos($_FILES["file_csv"]["name"],".")));
				$this->delete_error_file_day_before(1, FIXED_EQUIPMENT_IMPORT_ITEM_ERROR);
				$result = $this->fixed_equipment_model->data_import_xlsx_item($_FILES['file_csv']['tmp_name'], $_FILES['file_csv']['name'], $type);
				$error_filename = $result->error_filename;
			}
		}
		$data = [
				'total_row_success' => $result->total_row_success,
				'total_row_error' => $result->total_row_error,
				'total_rows' => $result->total_rows,
				'arr_insert' => json_encode($result->arr_insert),
				'file_type' => $file_type,
				'site_url' => site_url(),
				'staff_id' => get_staff_user_id(),
				'error_filename' => FIXED_EQUIPMENT_IMPORT_ITEM_ERROR . $error_filename,
		];
		echo json_encode($data);
		die;
	}

	/**
	 * delete error file day before
	 * @param  string $before_day
	 * @param  string $folder_name
	 * @return boolean
	 */
	public function delete_error_file_day_before($before_day = '', $folder_name = '') {
		if ($before_day != '') {
			$day = $before_day;
		} else {
			$day = '7';
		}

		if ($folder_name != '') {
			$folder = $folder_name;
		} else {
			$folder = FIXED_EQUIPMENT_IMPORT_ITEM_ERROR;
		}

		//Delete old file before 7 day
		$date = date_create(date('Y-m-d H:i:s'));
		date_sub($date, date_interval_create_from_date_string($day . " days"));
		$before_7_day = strtotime(date_format($date, "Y-m-d H:i:s"));

		foreach (glob($folder . '*') as $file) {

			$file_arr = explode("/", $file);
			$filename = array_pop($file_arr);

			if (file_exists($file)) {
				//don't delete index.html file
				if ($filename != 'index.html') {
					$file_name_arr = explode("_", $filename);
					$date_create_file = array_pop($file_name_arr);
					$date_create_file = str_replace('.xlsx', '', $date_create_file);

					if ((float) $date_create_file <= (float) $before_7_day) {
						unlink($folder . $filename);
					}
				}
			}
		}
		return true;
	}

}