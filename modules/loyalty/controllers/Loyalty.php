<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Loyalty Controller
 */
class loyalty extends AdminController {

	/**
	 * Constructs a new instance.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('loyalty_model');
	}

	/**
     * { configruration }
	  * @return view
     */
	public function configruration(){
		if (!has_permission('loyalty', '', 'edit') && !is_admin()) {
			access_denied('loyalty');
		}
		$data['group'] = $this->input->get('group');

		$data['title']                 = _l('configruration');
		
		$data['tab'][] = 'loyalty_setting';
		$data['tab'][] = 'card_management';

		if($data['group'] == ''){
			$data['group'] = 'loyalty_setting';
		}

		$data['tabs']['view'] = 'includes/'.$data['group'];


		$data['cards'] = $this->loyalty_model->get_list_card();

		$this->load->view('manage_configruration', $data);
	}

    /**
     * { loyalty setting }
     * @return redirect
     */
    public function loyalty_setting(){
    	if(!has_permission('loyalty','','edit') && !is_admin()){
    		access_denied('loyalty');
    	}

    	if($this->input->post()){
    		$data['loyalty_setting'] = 1;
    	}else{
    		$data['loyalty_setting'] = 0;	
    	}

    	$update = $this->loyalty_model->loyalty_setting($data);

    	if($update == true){
    		set_alert('success', _l('updated_successfully'));
    	}else{
    		set_alert('warning', _l('updated_fail'));
    	}

    	redirect(admin_url('loyalty/configruration?group=loyalty_setting'));

    }

    /**
     * Creates a card.
     * @return view
     */
    public function create_card($id = ''){
    	if($id == ''){

    		$data['title'] = _l('create_card');
    	}else{
    		$data['card'] = $this->loyalty_model->get_card($id);
    		$data['title'] = _l('edit_card');
    	}

    	$this->load->view('includes/create_card',$data);
    }

    /**
	 * add update candidate
	 * @param int $id
	 */
    public function card_config() {

    	$data = $this->input->post();
    	if ($data) {
    		if ($data['card_id'] == '') {
    			unset($data['card_id']);
    			$ids = $this->loyalty_model->add_card($data);
    			if ($ids) {
    				handle_card_picture($ids);
    				$success = true;
    				$message = _l('added_successfully', _l('card'));
    				set_alert('success', $message);
    			}
    			redirect(admin_url('loyalty/configruration?group=card_management'));
    		} else {
    			$id = $data['card_id'];
    			unset($data['card_id']);
    			$success = $this->loyalty_model->update_card($data, $id);
    			if ($success == true) {

    				handle_card_picture($id);
    				$message = _l('updated_successfully', _l('card'));
    				set_alert('success', $message);
    			}
    			redirect(admin_url('loyalty/configruration?group=card_management'));
    		}
    	}
    }

	/**
	 * { delete card }
	 *
	 * @param  $id     The identifier
	 */
	public function delete_card($id){
		if (!$id) {
			redirect(admin_url('loyalty/configruration?group=card_management'));
		}
		$response = $this->loyalty_model->delete_card($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('card')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('card')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('card')));
		}
		redirect(admin_url('loyalty/configruration?group=card_management'));
	}

	/**
	 * { loyalty rule }
	 * @return  view
	 */
	public function loyalty_rule(){
		$data['title'] = _l('loyalty_programs');
		$this->load->view('loyalty_rule/manage', $data);
	}

	/**
	 * Creates a loyalty rule.
	 *
	 * @param      string  $id     The identifier
	 */
	public function create_loyalty_rule($id = ''){
		$this->load->model('currencies_model');
		$this->load->model('invoice_items_model');
		$this->load->model('client_groups_model');
		$this->load->model('clients_model');

		if($id == ''){

			$data['title'] = _l('create_loyalty_rule');
		}else{
			$data['loyalty_rule'] = $this->loyalty_model->get_loyalty_rule($id);
			$data['title'] = _l('edit_loyalty_rule');
		}

		$data['client_groups'] = $this->client_groups_model->get_groups();
		$data['clients'] = $this->clients_model->get();
		$data['items'] = $this->invoice_items_model->get();
		$data['item_groups'] = $this->invoice_items_model->get_groups();
		$data['base_currency'] = $this->currencies_model->get_base_currency();

		$this->load->view('loyalty_rule/loyalty_rule',$data);
	}

	/**
	 * { loyalty rule form }
	 * @return redirect
	 */
	public function loyalty_rule_form(){
		
		if ($this->input->post()) {
			$data = $this->input->post();
			if ($data['id'] == '') {
				unset($data['id']);
				$ids = $this->loyalty_model->add_loyalty_rule($data);
				if ($ids) {
					$success = true;
					$message = _l('added_successfully', _l('loyalty_rule'));
					set_alert('success', $message);
				}
				redirect(admin_url('loyalty/loyalty_rule'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->loyalty_model->update_loyalty_rule($data, $id);
				if ($success == true) {

					
					$message = _l('updated_successfully', _l('loyalty_rule'));
					set_alert('success', $message);
				}
				redirect(admin_url('loyalty/loyalty_rule'));
			}
		}
	}

	/**
	 * { table loyalty rule }
	 */
	public function table_loyalty_rule(){
		$this->app->get_table_data(module_views_path('loyalty', 'loyalty_rule/table_loyalty_rule'));
	}

	/**
	 * { delete loyalty rule }
	 *
	 * @param  $id     The identifier
	 */
	public function delete_loyalty_rule($id){
		if (!$id) {
			redirect(admin_url('loyalty/loyalty_rule'));
		}
		$response = $this->loyalty_model->delete_loyalty_rule($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('loyalty_rule')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('loyalty_rule')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('loyalty_rule')));
		}
		redirect(admin_url('loyalty/loyalty_rule'));
	}

	/**
	 * { membership rule }
	 * @return view
	 */
	public function membership_rule(){
		$this->load->model('client_groups_model');
		$this->load->model('clients_model');
		$data['title'] = _l('membership_rule');

		$data['client_groups'] = $this->client_groups_model->get_groups();
		$data['clients'] = $this->clients_model->get();
		$data['cards'] = $this->loyalty_model->get_list_card();
		$this->load->view('membership_rule/manage', $data);
	}

	/**
	 * { table loyalty rule }
	 */
	public function table_membership_rule(){
		$this->app->get_table_data(module_views_path('loyalty', 'membership_rule/table_membership_rule'));
	}

	/**
	 * { client group change }
	 *
	 * @param      string  $client_group  The client group
	 * @return json
	 */
	public function client_group_change($client_group = ''){
		$this->load->model('clients_model');
		$html = '';
		if($client_group != ''){
			$list_clients = $this->loyalty_model->list_clients_by_group($client_group);
			
		}else{
			$list_clients = $this->clients_model->get();
		}



		if($list_clients > 0){
			foreach($list_clients as $cli){
				$html .= '<option value="'.$cli['userid'].'">'.$cli['company'].'</option>';
			}
		}
		echo json_encode([
			'html' => $html,
		]);
	}

	/**
	 * { membership rule form }
	 * @return redirect
	 */
	public function membership_rule_form(){
		$data = $this->input->post();
		if ($data) {
			if ($data['id'] == '') {
				unset($data['id']);
				$ids = $this->loyalty_model->add_membership_rule($data);
				if ($ids) {
					$success = true;
					$message = _l('added_successfully', _l('membership_rule'));
					set_alert('success', $message);
				}
				redirect(admin_url('loyalty/membership?group=membership_rule'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->loyalty_model->update_membership_rule($data, $id);
				if ($success == true) {

					
					$message = _l('updated_successfully', _l('membership_rule'));
					set_alert('success', $message);
				}
				redirect(admin_url('loyalty/membership?group=membership_rule'));
			}
		}
	}

	/**
	 * { delete membership rule }
	 *
	 * @param  $id     The identifier
	 */
	public function delete_membership_rule($id){
		if (!$id) {
			redirect(admin_url('loyalty/membership?group=membership_rule'));
		}
		$response = $this->loyalty_model->delete_membership_rule($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('membership_rule')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('membership_rule')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('membership_rule')));
		}
		redirect(admin_url('loyalty/membership?group=membership_rule'));
	}

	/**
	 * { delete membership program }
	 *
	 * @param  $id     The identifier
	 */
	public function delete_membership_program($id){
		if (!$id) {
			redirect(admin_url('loyalty/membership?group=membership_program'));
		}
		$response = $this->loyalty_model->delete_membership_program($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('membership_program')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('membership_program')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('membership_program')));
		}
		redirect(admin_url('loyalty/membership?group=membership_program'));
	}

	/**
	 * { membership program detail }
	 * @return view
	 */
	public function membership_program($id){
		$this->load->model('clients_model');
		$data['title'] = _l('membership_program');
		$data['mbs_program'] = $this->loyalty_model->get_membership_program($id);
		$data['clients'] = $this->clients_model->get();
		$this->load->view('membership_program/program_detail', $data);
	}

	/**
	 * { manage membership program }
	 *
	 * @param      string  $id     The identifier
	 */
	public function mbs_program($id = ''){
		if($id == ''){
			$data['title'] = _l('add_membership_program');
		}else{
			$data['title'] = _l('edit_membership_program');
			$data['mbs_program'] = $this->loyalty_model->get_membership_program($id);
		}

		$this->load->model('currencies_model');
		$this->load->model('client_groups_model');
		$this->load->model('clients_model');
		$this->load->model('invoice_items_model');

		$data['base_currency'] = $this->currencies_model->get_base_currency();
		$data['items'] = $this->invoice_items_model->get();
		$data['item_groups'] = $this->invoice_items_model->get_groups();
		$data['client_groups'] = $this->client_groups_model->get_groups();
		$data['clients'] = $this->clients_model->get();
		$data['memberships'] = $this->loyalty_model->get_membership_rule();
		$this->load->view('membership_program/membership_program', $data);

	}

	/**
	 * { table membership program }
	 */
	public function table_membership_program(){
		$this->app->get_table_data(module_views_path('loyalty', 'membership_program/table_membership_program'));
	}

	/**
	 * { membership program form }
	 * @return redirect
	 */
	public function membership_program_form(){
		$data = $this->input->post();
		if ($data) {
			if ($data['id'] == '') {
				unset($data['id']);
				$ids = $this->loyalty_model->add_membership_program($data);
				if ($ids) {
					$success = true;
					$message = _l('added_successfully', _l('membership_program'));
					set_alert('success', $message);
				}
				redirect(admin_url('loyalty/membership?group=membership_program'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->loyalty_model->update_membership_program($data, $id);
				if ($success == true) {

					
					$message = _l('updated_successfully', _l('membership_program'));
					set_alert('success', $message);
				}
				redirect(admin_url('loyalty/membership?group=membership_program'));
			}
		}
	}

	/**
	 * Voucher code exists
	 * @return boolean
	 */
	public function voucher_code_exists() {
		if ($this->input->is_ajax_request()) {
			if ($this->input->post()) {
				// First we need to check if the email is the same
				$id = $this->input->post('id');
				if ($id != '') {
					$this->db->where('id', $id);
					$campaign = $this->db->get(db_prefix().'loy_mbs_program')->row();
					if ($campaign->voucher_code == $this->input->post('voucher_code')) {
						echo json_encode(true);
						die();
					}
				}
				$this->db->where('voucher_code', $this->input->post('voucher_code'));
				$total_rows = $this->db->count_all_results(db_prefix().'loy_mbs_program');
				if ($total_rows > 0) {
					echo json_encode(false);
				} else {
					echo json_encode(true);
				}
				die();
			}
		}
	}

	/**
	 * { transation }
	 * @return view
	 */
	public function transation(){
		$this->load->model('clients_model');
		$data['cus'] = $this->input->get('cus');
		$data['clients'] = $this->clients_model->get();
		$data['title'] = _l('transation');
		$this->load->view('transation/manage', $data);
	}

	/**
	 * { table transation }
	 * 
	 */
	public function table_transation(){
		$this->app->get_table_data(module_views_path('loyalty', 'transation/table_transation'));
	}

	/**
	 * { transation form }
	 * @return redirect
	 */
	public function transation_form(){
		if($this->input->post()){
			$data = $this->input->post();
			$id = $this->loyalty_model->add_transation_manual($data);
			if ($id) {
				$success = true;
				$message = _l('added_successfully', _l('transation'));
				set_alert('success', $message);
			}
			redirect(admin_url('loyalty/transation'));
		}
	}

	/**
	 * { delete transation }
	 * @param $id transation
	 * @return redirect
	 */
	public function delete_transation($id){
		if (!$id) {
			redirect(admin_url('loyalty/transation'));
		}
		$response = $this->loyalty_model->delete_transation($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('transation')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('transation')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('transation')));
		}
		redirect(admin_url('loyalty/transation'));
	}


	/**
	 * { manage user }
	 * @return view
	 */
	public function user(){
		$this->load->model('clients_model');

		$data['client_groups'] = $this->client_groups_model->get_groups();
		$data['clients'] = $this->clients_model->get();
		$data['title'] = _l('user');
		$this->load->view('user/manage', $data);
	}

	/**
	 * { table user }
	 */
	public function table_user(){
		$this->app->get_table_data(module_views_path('loyalty', 'user/table_user'));
	}

	/**
	 * { table redeem log }
	 * 
	 */
	public function table_rd_log(){
		$this->app->get_table_data(module_views_path('loyalty', 'transation/table_redeem_log'));
	}

	/**
	 * { membership }
	 * @return view
	 */
	public function membership(){
		$this->load->model('client_groups_model');
		$this->load->model('clients_model');

		$data['group'] = $this->input->get('group');
		if($data['group'] == ''){
			$data['group'] = 'membership_rule';
		}

		$data['title'] = _l('membership');

		$data['client_groups'] = $this->client_groups_model->get_groups();
		$data['clients'] = $this->clients_model->get();
		$data['memberships'] = $this->loyalty_model->get_membership_rule();

		$data['cards'] = $this->loyalty_model->get_list_card();

		$this->load->view('membership', $data);
	}

	/**
	 * Gets the client information loy.
	 * @return json
	 */
	public function get_client_info_loy($client){
		$point = client_loyalty_point($client);
		$redemp_rule = get_redemp_rule_client($client, 'pos');

		$disabled = '';
		$val = '';
		$val_to = '';
		$weight = 0;
		$type = '';
		$hide = '';
		$min = 0;
		$max_received = 0;
		if($redemp_rule != ''){
			$loy_rule = get_rule_by_id($redemp_rule->loy_rule);
			if($loy_rule){
				if($loy_rule->redeemp_type == 'full'){
					$val =  client_loyalty_point($client);
					$val_to =  app_format_money(client_loyalty_point($client)*$redemp_rule->point_weight,'');
					$disabled = 'readonly';
					$min = $val;
				}elseif($loy_rule->redeemp_type == 'partial'){
					$disabled = '';
					$val = '';
					$val_to = '';
					$min = 0;
				}
				$type = $loy_rule->redeemp_type;
				$max_received = $loy_rule->max_amount_received;
			}
			$weight = $redemp_rule->point_weight;
		}else{
			$hide = 'hide';
		}

		echo json_encode([
			'point' => $point,
			'val' => $val,
			'val_to' => $val_to,
			'disabled' => $disabled,
			'weight' => $weight,
			'type' => $type,
			'hide' => $hide,
			'min' => $min,
			'max_received' => $max_received,
		]);
	}

	/**
	 * Gets the client information loy.
	 * @return json
	 */
	public function get_client_info_loy_inv($client){
		$point = client_loyalty_point($client);
		$redemp_rule = get_redemp_rule_client_inv($client);

		$disabled = '';
		$val = '';
		$val_to = 0;
		$weight = 0;
		$type = '';
		$hide = '';
		$min = 0;
		$max_received = 0;
		if($redemp_rule != ''){
			$loy_rule = get_rule_by_id($redemp_rule->loy_rule);
			if($loy_rule){
				if($loy_rule->redeemp_type == 'full'){
					$val =  client_loyalty_point($client);
					$val_to =  client_loyalty_point($client)*$redemp_rule->point_weight;
					$disabled = 'readonly';
					$min = $val;
				}elseif($loy_rule->redeemp_type == 'partial'){
					$disabled = '';
					$val = '';
					$val_to = 0;
					$min = 0;
				}
				$type = $loy_rule->redeemp_type;
				$max_received = $loy_rule->max_amount_received;
			}
			$weight = $redemp_rule->point_weight;
		}else{
			$hide = 'hide';
		}

		echo json_encode([
			'point' => $point,
			'val' => $val,
			'val_to' => $val_to,
			'disabled' => $disabled,
			'weight' => $weight,
			'type' => $type,
			'hide' => $hide,
			'min' => $min,
			'max_received' => $max_received,
		]);
	}

	/**
	 * { loyalty rule detail }
	 *
	 * @param      <type>  $id     The identifier
	 * @return view
	 */
	public function loyalty_program_detail($id){
		$this->load->model('clients_model');
		$this->load->model('currencies_model');

		$data['title'] = _l('loyalty_program');
		$data['loyalty_program'] = $this->loyalty_model->get_loyalty_rule($id);
		$data['base_currency'] = $this->currencies_model->get_base_currency();

		$data['clients'] = $this->clients_model->get();

		$this->load->view('loyalty_rule/program_detail', $data);
	}

	/**
       * voucher apply 
       * @return  json
       */
    public function get_mbs_discount(){
        $data = $this->input->post();           
        $return = $this->loyalty_model->apply_mbs_program_discount($data['clientid']);

        echo json_encode([$return]);
    }

    /**
     * Gets the list product identifier.
     */
    public function get_list_product_id(){
    	if($this->input->post()){
    		$data = $this->input->post();
    		$ids = [];
    		$list_qty = [];
    		$list_rate = [];
    		if(count($data['description']) > 0){
    			foreach($data['description'] as $key => $des){
    				$ids[] = get_item_id_by_item_name($des, $data['long_description'][$key]);
    				$list_qty[] = $data['qty'][$key];
    				$list_rate[] = $data['rate'][$key];
    			}
    		}

    		echo json_encode([ 
    			'product_ids' => implode(',', $ids),
    			'list_qty' => implode(',', $list_qty),
    			'list_rate' => implode(',', $list_rate)
    		]);
    	}
    }

    /**
       * voucher apply 
       * @return  json
       */
    public function voucher_apply(){
        $data = $this->input->post();           
        $return = $this->loyalty_model->apply_voucher_to_portal($data['clientid'],$data['voucher']);

        if(count($return) > 0){
            echo json_encode(['rs' => $return]);
        }else{ 
            echo json_encode(['rs' => '']);
        }
    }

}

