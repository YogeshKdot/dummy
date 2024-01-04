<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Customer Loyalty & Membership
Description: A loyalty program is a rewards program typically offered to customers, and even staff, who frequently make purchases.
Version: 1.0.2
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('LOYALTY_MODULE_NAME', 'loyalty');
define('LOYALTY_MODULE_UPLOAD_FOLDER', module_dir_path(LOYALTY_MODULE_NAME, 'uploads'));
define('LOYALTY_REVISION', 102);
hooks()->add_action('admin_init', 'loyalty_permissions');
hooks()->add_action('app_admin_footer', 'loyalty_head_components');
hooks()->add_action('app_admin_footer', 'loyalty_add_footer_components');
hooks()->add_action('admin_init', 'loyalty_module_init_menu_items');
hooks()->add_filter('after_payment_added', 'add_transation');
hooks()->add_action('customers_navigation_end', 'init_loyalty_portal_menu');

// redeem feature hook
hooks()->add_action('omni_sale_discount', 'init_redemp_omni_sale');
hooks()->add_action('client_pt_footer_js','init_loyalty_omni_sale_js');
hooks()->add_action('after_cart_added', 'apply_redeem_log_program',10,2);  
hooks()->add_action('omni_sale_pos_redeem', 'init_redemp_pos');
hooks()->add_action('sale_invoice_redeem', 'init_redemp_invoice');
hooks()->add_action('head_element_client', 'init_head_element');
hooks()->add_action('sale_invoice_client_redeem', 'init_redeem_client_inv');

hooks()->add_action('loy_after_invoice_added', 'apply_redeem_log_inv',10,2);  

// Merge field mailtemplate
hooks()->add_filter('other_merge_fields_available_for', 'loyalty_register_other_merge_fields');
register_merge_fields('loyalty/merge_fields/birthday_bonus_point_merge_fields');
register_merge_fields('loyalty/merge_fields/new_account_bonus_points_merge_fields');

// Unset redeem data before add invoice
hooks()->add_filter('before_invoice_added', 'unset_invoice_redeem_data');

// apply voucher hook
hooks()->add_filter('apply_other_voucher', 'apply_voucher_to_portal',10,3);

// apply membership program discount
hooks()->add_filter('apply_mbs_program_discount', 'apply_mbs_program_discount',10,2);

// get point when create account
hooks()->add_action('after_client_added', 'get_point_when_create_account');

// Cron auto add point on client's birthday
hooks()->add_action('before_cron_run', 'add_point_on_birthday'); 

define('LOYALTY_PATH', 'modules/loyalty/uploads/');
/**
 * Register activation module hook
 */
register_activation_hook(LOYALTY_MODULE_NAME, 'loyalty_module_activation_hook');
/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(LOYALTY_MODULE_NAME . '/loyalty');

function loyalty_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(LOYALTY_MODULE_NAME, [LOYALTY_MODULE_NAME]);

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function loyalty_module_init_menu_items() {

	$CI = &get_instance();
	if (has_permission('loyalty', '', 'view')) {

		$CI->app_menu->add_sidebar_menu_item('loyalty', [
			'name' => _l('loyalty'),
			'icon' => 'fa fa-handshake-o',
			'position' => 20,
		]);

		$CI->app_menu->add_sidebar_children_item('loyalty', [
            'slug'     => 'loyalty-user',
            'name'     => _l('user'),
            'icon'     => 'fa fa-user-circle menu-icon',
            'href'     => admin_url('loyalty/user'),
            'position' => 1,
            ]);
      
        
        $CI->app_menu->add_sidebar_children_item('loyalty', [
            'slug'     => 'loyalty-transation',
            'name'     => _l('transation'),
            'icon'     => 'fa fa-backward',
            'href'     => admin_url('loyalty/transation'),
            'position' => 2,
        ]);

        $CI->app_menu->add_sidebar_children_item('loyalty', [
            'slug'     => 'loyalty-mbs',
            'name'     => _l('membership'),
            'icon'     => 'fa fa-address-book',
            'href'     => admin_url('loyalty/membership?group=membership_rule'),
            'position' => 3,
        ]);

        $CI->app_menu->add_sidebar_children_item('loyalty', [
            'slug'     => 'loyalty-rule',
            'name'     => _l('loyalty_programs'),
            'icon'     => 'fa fa-address-book-o',
            'href'     => admin_url('loyalty/loyalty_rule'),
            'position' => 4,
        ]);

         $CI->app_menu->add_sidebar_children_item('loyalty', [
            'slug'     => 'loyalty-config',
            'name'     => _l('configuration'),
            'icon'     => 'fa fa-gears',
            'href'     => admin_url('loyalty/configruration'),
            'position' => 5,
        ]);
	}

}

/**
 * error log permissions
 * @return
 */
function loyalty_permissions() {
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
	];

	register_staff_capabilities('loyalty', $capabilities, _l('loyalty'));

}

/**
 * add head components
 */
function loyalty_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if(!(strpos($viewuri, '/admin/loyalty/configruration') === false)){
        echo '<link href="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/css/configuration.css') . '?v=' . LOYALTY_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/admin/loyalty/create_card') === false)){
        echo '<link href="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/css/create_card.css') . '?v=' . LOYALTY_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/admin/loyalty/create_loyalty_rule') === false)){
        echo '<link href="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/css/loyalty_rule.css') . '?v=' . LOYALTY_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/loyalty/loyalty_portal') === false)){
        echo '<link href="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/css/home_portal.css') . '?v=' . LOYALTY_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/loyalty/mbs_program') === false)){
        echo '<link href="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/css/mbs_program.css') . '?v=' . LOYALTY_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/loyalty/membership_program') === false)){
        echo '<link href="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/css/mbs_program_detail.css') . '?v=' . LOYALTY_REVISION . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/loyalty/loyalty_program_detail') === false)){
        echo '<link href="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/css/mbs_program_detail.css') . '?v=' . LOYALTY_REVISION . '"  rel="stylesheet" type="text/css" />';
    }
}

/**
 * add footer components
 * @return
 */
function loyalty_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];	

	if(!(strpos($viewuri, '/admin/loyalty/create_card') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/create_card.js') . '?v=' . LOYALTY_REVISION . '" ></script>';
    }

    if(!(strpos($viewuri, '/admin/loyalty/create_loyalty_rule') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/loyalty_rule.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }

    if(!(strpos($viewuri, '/admin/loyalty/loyalty_rule') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/manage_loyalty_rule.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }

    if(!(strpos($viewuri, '/admin/loyalty/membership?group=membership_rule') === false) || !(strpos($viewuri, '/admin/loyalty/membership') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/manage_membership_rule.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }

    if(!(strpos($viewuri, '/admin/loyalty/membership?group=membership_program') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/manage_membership_program.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }

    if(!(strpos($viewuri, '/admin/loyalty/transation') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/mange_transation.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }
    
    if(!(strpos($viewuri, '/admin/loyalty/user') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/manage_user.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }

    if(!(strpos($viewuri, '/admin/loyalty/mbs_program') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/membership_program.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }

    if(!(strpos($viewuri, '/admin/invoices/invoice') === false) && (strpos($viewuri, '/admin/invoices/invoice/') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/invoice_redeem.js') . '?v=' . LOYALTY_REVISION . '"></script>';
    }
}

/**
 * add transation
 * @param integer $payment_id
 */
function add_transation($payment_id) {
    if(get_option('loyalty_setting') == 1 || get_option('loyalty_setting') == '1'){
        add_transation_loy($payment_id);
    }
    return $payment_id;
}

/**
 * init loyalty portal menu
 * 
 *       
 */
function init_loyalty_portal_menu()
{
    $item ='';
    if(is_client_logged_in()){
        $item .= '<li class="customers-nav-item">';
                      $item .= '<a href="'.site_url('loyalty/loyalty_portal').'">'._l("membership").'';        
                      $item .= '</a>';
                   $item .= '</li>';
    }
    echo html_entity_decode($item);

}

/**
 * Initializes the redemp omni sale.
 *
 * @param      <type>  $client  The client
 */
function init_redemp_omni_sale($client){
    $CI = &get_instance();
    if($client != ''){
        require "modules/loyalty/views/redeem.php"; 
    }
}

/**
 * Initializes the loyalty omni sale js.
 */
function init_loyalty_omni_sale_js(){
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if(!(strpos($viewuri, '/omni_sales/omni_sales_client/view_overview') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/omni_redeem.js') . '"></script>';
    }

    if(!(strpos($viewuri, '/admin/omni_sales/pos') === false)){
        render_admin_js_variables();
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/omni_redeem.js') . '"></script>';
    }

    if(!(strpos($viewuri, '/invoice/') === false)){
        echo '<script src="' . module_dir_url(LOYALTY_MODULE_NAME, 'assets/js/inv_client_redeem.js') . '"></script>';
    }
}

/**
 * { apply credit mbs program }
 *
 * @param    $invoice_id  The invoice identifier
 * 
 
 */
function apply_redeem_log_program($iv_number,$data){
    $CI = &get_instance();
    $CI->load->model('loyalty/loyalty_model');
    if($iv_number){
        $CI->loyalty_model->add_redeem_log_program($iv_number,$data);
    }
}

/**
 * Initializes the redemp to POS (Omni Sales).
 */
function init_redemp_pos(){
    require "modules/loyalty/views/pos_redeem.php"; 
}


/**
 * Apply voucher to portal (Omni Sales)
 * @param integer $data, $client, $voucher
 */
function apply_voucher_to_portal($data, $client, $voucher) {
    $CI = &get_instance();
    if(isset($data)){
        return $data;
    }else{
        $CI->load->model('loyalty/loyalty_model');
        $voucher = $CI->loyalty_model->apply_voucher_to_portal($client, $voucher);
        return $voucher;
    }   
}

/**
 * Initializes the head element.
 */
function init_head_element(){
    $viewuri = $_SERVER['REQUEST_URI'];
    if(!(strpos($viewuri, '/admin/omni_sales/pos') === false)){
        echo '<script src="'.site_url().'assets/plugins/jquery/jquery.min.js"></script>';
    }
}

/**
 * Apply membership program discount
 * @param $data, $client
 */
function apply_mbs_program_discount($data, $client) {
    $CI = &get_instance();
    if(isset($data) && count($data) > 0){
        return $data;
    }else{
        $CI->load->model('loyalty/loyalty_model');
        $program = $CI->loyalty_model->apply_mbs_program_discount($client);
        return $program;
    } 
}

if (defined('APP_CSRF_PROTECTION') && APP_CSRF_PROTECTION) {
    hooks()->add_action('post_redeem_head', 'csrf_jquery_token');
}

/**
 * Initializes the redemp sale invoice.
 *
 * 
 */
function init_redemp_invoice(){
    $CI = &get_instance();
    require "modules/loyalty/views/invoice_redeem.php";
}

/**
 * { unset invoice redeem data }
 *
 * @param        $data   The data
 *
 * @return     $data 
 */
function unset_invoice_redeem_data($data){
    $CI = &get_instance();

    if(isset($data['data']['weight'])){
        $data['redeem']['weight'] = $data['data']['weight'];
        unset($data['data']['weight']);
    }

    if(isset($data['data']['rate_percent'])){
        $data['redeem']['rate_percent'] = $data['data']['rate_percent'];
        unset($data['data']['rate_percent']);
    }

    if(isset($data['data']['data_max'])){
        $data['redeem']['data_max'] = $data['data']['data_max'];
        unset($data['data']['data_max']);
    }

    if(isset($data['data']['redeem_to'])){
        $data['redeem']['redeem_to'] = $data['data']['redeem_to'];
        unset($data['data']['redeem_to']);
    }

    if(isset($data['data']['redeem_from'])){
        $data['redeem']['redeem_from'] = $data['data']['redeem_from'];
        unset($data['data']['redeem_from']);
    }

    if(isset($data['data']['voucher'])){
        $data['redeem']['voucher_code'] = $data['data']['voucher'];
        unset($data['data']['voucher']);
    }

    if(isset($data['data']['program_discount'])){
        $data['redeem']['program_discount'] = $data['data']['program_discount'];
        unset($data['data']['program_discount']);
    }

    if(isset($data['data']['redeem_discount'])){
        $data['redeem']['redeem_discount'] = $data['data']['redeem_discount'];
        unset($data['data']['redeem_discount']);
    }

    if(isset($data['data']['voucher_value'])){
        $data['redeem']['voucher_value'] = $data['data']['voucher_value'];
        unset($data['data']['voucher_value']);
    }

    if(isset($data['data']['list_id_product'])){
        $data['redeem']['list_id_product'] = $data['data']['list_id_product'];
        unset($data['data']['list_id_product']);
    }

    if(isset($data['data']['list_group_product'])){
        $data['redeem']['list_group_product'] = $data['data']['list_group_product'];
        unset($data['data']['list_group_product']);
    }

    if(isset($data['data']['list_qty_product'])){
        $data['redeem']['list_qty_product'] = $data['data']['list_qty_product'];
        unset($data['data']['list_qty_product']);
    }

    if(isset($data['data']['list_prices_product'])){
        $data['redeem']['list_prices_product'] = $data['data']['list_prices_product'];
        unset($data['data']['list_prices_product']);
    }

    if(isset($data['data']['symbol'])){
        unset($data['data']['symbol']);
    }

    $list_programs = $CI->db->get(db_prefix().'loy_mbs_program')->result_array();
    if( count($list_programs) > 0){
        foreach($list_programs as $program){
            if(isset($data['data']['program_'.$program['id']])){
                $data['redeem']['program_'.$program['id']] = $data['data']['program_'.$program['id']];
                unset($data['data']['program_'.$program['id']]);
            }
        }
    }

    return $data;
}

/**
 * { apply credit mbs program }
 *
 * @param    $invoice_id  The invoice identifier
 * 
 
 */
function apply_redeem_log_inv($iv_id,$data){
    $CI = &get_instance();
    $CI->load->model('loyalty/loyalty_model');
    if($iv_id > 0){
        $CI->loyalty_model->apply_redeem_log_inv($iv_id,$data);
    }
}

/**
 * Initializes the redeem client inv.
 */
function init_redeem_client_inv($invoice){
    $redeem_log = total_rows(db_prefix().'loy_redeem_log', ['invoice' => $invoice->id]);
    $voucher_log = total_rows(db_prefix().'loy_voucher_inv_log', ['invoice' => $invoice->id]);
    $program_log = total_rows(db_prefix().'loy_program_discount_log', ['invoice' => $invoice->id]);

    if(isset($invoice) && is_client_logged_in() && $redeem_log == 0 && $voucher_log == 0 && $program_log == 0){
        if($invoice->status != 2 && $invoice->status != 5){
            require "modules/loyalty/views/inv_client_redeem.php";
        }
    }
}

/**
 * Gets the point when create account.
 *
 * @param        $client_id  The client identifier
 */
function get_point_when_create_account($client_id){
    $CI = &get_instance();
    $cur_date = date('Y-m-d');
    $loy_rule = $CI->db->query('SELECT MAX(create_account_point) as create_point FROM '.db_prefix().'loy_rule WHERE create_account_point IS NOT NULL and enable = 1 and start_date <= "'.$cur_date.'" and end_date >= "'.$cur_date.'"')->row();
    if($loy_rule){
        if($loy_rule->create_point > 0){

            $CI->db->insert(db_prefix().'loy_transation',[
                'reference' => 'manual_credit',
                'client' => $client_id,
                'invoice' => 0,
                'date_create' => date('Y-m-d H:i:s'),
                'loyalty_point' => $loy_rule->create_point,
                'type' => 'credit',
                'note' => 'bonus_points_for_creating_new_accounts'
            ]);
            $insert_transation = $CI->db->insert_id();
            if($insert_transation){
                $CI->db->where('userid', $client_id);
                $CI->db->update(db_prefix().'clients', ['loy_point' => $loy_rule->create_point]);
                if ($CI->db->affected_rows() > 0) {
                    $primary_contact = get_primary_contact_user_id_loy($client_id);

                    $data = [];
                    $data['mail_to'] = '';
                    $data['contact_name'] = '';
                    if($primary_contact){
                        $data['mail_to'] = $primary_contact->email;
                        $data['contact_name'] = $primary_contact->firstname;
                    }

                    $data['points_received'] = $loy_rule->create_point;

                    if($data['mail_to'] != ''){
                        $template = mail_template('new_account_bonus_points', 'loyalty', array_to_object($data));
                        $template->send();
                    }
                }
            }
        }
    }
}

/**
 * Adds a point on birthday.
 */
function add_point_on_birthday(){
    $CI = &get_instance();
    $list_clients = $CI->db->get(db_prefix().'clients')->result_array();

    $cur_date = date('m-d');
    $cur_date_query = date('Y-m-d');
    $CI->load->model('client_groups_model');
    $CI->db->where('fieldto', 'customers');
    $CI->db->where('name', 'Birthday');
    $CI->db->where('slug', 'customers_birthday');
    $birthday_cf = $CI->db->get(db_prefix().'customfields')->row();

    if(count($list_clients) > 0){
        foreach($list_clients as $client){
            $total_transaction = total_rows(db_prefix().'loy_transation', ['client' => $client['userid'], 'note' => 'bonus_points_for_customers_birthday']);
            if($total_transaction == 0){
                $old_point = client_loyalty_point($client['userid']);
                $groups = $CI->client_groups_model->get_customer_groups($client['userid']);
                if(count($groups) > 0){
                    $groups_lst = array();
                    foreach($groups as $gr){
                        $groups_lst[] = $gr['groupid'];
                    }
                    $loy_rule = $CI->db->query('SELECT MAX(birthday_point) as birthday_point_max FROM '.db_prefix().'loy_rule WHERE (find_in_set('.$client['userid'].', client) or client_group IN ('.implode(',', $groups_lst).') ) and birthday_point IS NOT NULL and enable = 1 and start_date <= "'.$cur_date_query.'" and end_date >= "'.$cur_date_query.'"')->row();
                }else{
                    $loy_rule = $CI->db->query('SELECT MAX(birthday_point) as birthday_point_max FROM '.db_prefix().'loy_rule WHERE find_in_set('.$client['userid'].', client) and birthday_point IS NOT NULL and enable = 1 and start_date <= "'.$cur_date_query.'" and end_date >= "'.$cur_date_query.'"')->row();
                }

                if(isset($birthday_cf) && isset($loy_rule)){
                    $CI->db->where('relid', $client['userid']);
                    $CI->db->where('fieldid', $birthday_cf->id);
                    $CI->db->where('fieldto', 'customers');
                    $birthday = $CI->db->get(db_prefix().'customfieldsvalues')->row();

                    $client_birthday = '';
                    if($birthday){
                        $client_birthday = date('m-d', strtotime($birthday->value));
                    }

                    if($client_birthday != '' && $client_birthday == $cur_date && $loy_rule->birthday_point_max > 0){
                        $CI->db->insert(db_prefix().'loy_transation',[
                            'reference' => 'manual_credit',
                            'client' => $client['userid'],
                            'invoice' => 0,
                            'date_create' => date('Y-m-d H:i:s'),
                            'loyalty_point' => $loy_rule->birthday_point_max,
                            'type' => 'credit',
                            'note' => 'bonus_points_for_customers_birthday'
                        ]);
                        $insert_transation = $CI->db->insert_id();
                        if($insert_transation){
                            $CI->db->where('userid', $client['userid']);
                            $CI->db->update(db_prefix().'clients', ['loy_point' => ($old_point+$loy_rule->birthday_point_max)]);
                            if ($CI->db->affected_rows() > 0) { 
                                $primary_contact = get_primary_contact_user_id_loy($client['userid']);

                                $data = [];

                                $data['mail_to'] = '';
                                $data['contact_name'] = '';
                                if($primary_contact){
                                    $data['mail_to'] = $primary_contact->email;
                                    $data['contact_name'] = $primary_contact->firstname;
                                }

                                $data['points_received'] = $loy_rule->birthday_point_max;

                                if($data['mail_to'] != ''){
                                    $template = mail_template('birthday_bonus_points', 'loyalty', array_to_object($data));
                                    $template->send();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

/**
 * Register other merge fields for loyalty
 *
 * @param [array] $for
 * @return void
 */
function loyalty_register_other_merge_fields($for) {
    $for[] = 'loyalty';

    return $for;
}