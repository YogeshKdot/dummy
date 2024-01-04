<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Team Password
Description: Team Password is a self-hosted team password manager for companies to share passwords with there teams
Version: 1.0.8
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
 */

define('TEAM_PASSWORD_MODULE_NAME', 'team_password');
define('TEAM_PASSWORD_MODULE_UPLOAD_FOLDER', module_dir_path(TEAM_PASSWORD_MODULE_NAME, 'uploads'));
define('TEAM_PASSWORD_REVISION', 108);
hooks()->add_action('app_admin_head', 'int_add_head_component');
hooks()->add_action('app_admin_footer', 'team_password_load_js');
hooks()->add_action('admin_init', 'team_password_permissions');
define('TEAM_PASSWORD_PATH', 'modules/team_password/uploads/');
define('TEAM_PASSWORD_APP_PATH', 'modules/team_password/');
hooks()->add_action('admin_init', 'team_password_module_init_menu_items');
hooks()->add_action('customers_navigation_end', 'team_password_module_init_client_menu_items');
register_merge_fields('team_password/merge_fields/teampassword_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'teampassword_register_other_merge_fields');
hooks()->add_action('after_contract_view_as_client_link', 'init_item_relate_contract');
hooks()->add_action('after_customer_admins_tab', 'init_tab_item_shared');
hooks()->add_action('after_custom_profile_tab_content', 'init_content_item_shared');
hooks()->add_action('after_li_contract_view', 'init_tab_contracthtml');
hooks()->add_action('after_tab_contract_content', 'init_tab_contracthtml_content');
hooks()->add_action('after_project_member_list', 'init_project_item_relate');
hooks()->add_filter('before_client_updated', 'teampassword_unset_data_update_client',10,2);
/**
 * key: g8934fuw9843hwe8rf9*5bhv You can change this key the first time you active module but when you have used the module you cannot change it
 * because it will affect the decryption process.
 */

/**
 * Register activation module hook
 */
register_activation_hook(TEAM_PASSWORD_MODULE_NAME, 'team_password_module_activation_hook');
$CI = &get_instance();

$CI->load->helper(TEAM_PASSWORD_MODULE_NAME . '/team_password');
/**
 * team password module activation hook
 */
function team_password_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(TEAM_PASSWORD_MODULE_NAME, [TEAM_PASSWORD_MODULE_NAME]);

/**
 * init add head component
 */
function int_add_head_component() {
	$CI = &get_instance();

	$viewuri = $_SERVER['REQUEST_URI'];

	if (!(strpos($viewuri, '/admin/team_password/add_normal') === false) || !(strpos($viewuri, '/admin/team_password/add_bank_acount') === false) || !(strpos($viewuri, '/admin/team_password/add_credit_card') === false) || !(strpos($viewuri, '/admin/team_password/add_server') === false) || !(strpos($viewuri, '/admin/team_password/add_email') === false) || !(strpos($viewuri, '/admin/team_password/add_software_license') === false)) {
		echo '<link href="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/css/custom.css') . '?v=' . TEAM_PASSWORD_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, '/admin/team_password/dashboard') === false)) {
		echo '<link href="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/css/dashboard.css') . '?v=' . TEAM_PASSWORD_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, '/admin/team_password/view_normal') === false) || !(strpos($viewuri, '/admin/team_password/view_bank_account') === false) || !(strpos($viewuri, '/admin/team_password/view_credit_card') === false) || !(strpos($viewuri, '/admin/team_password/view_server') === false) || !(strpos($viewuri, '/admin/team_password/view_email') === false) || !(strpos($viewuri, '/admin/team_password/view_software_license') === false)) {

		echo '<link href="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/css/view_password.css') . '?v=' . TEAM_PASSWORD_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
}

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function team_password_module_init_menu_items() {
	$CI = &get_instance();

	if (has_permission('team_password', '', 'view_own') || has_permission('team_password', '', 'view') || is_admin()) {

		$CI->app_menu->add_sidebar_menu_item('team_password', [
			'name' => _l('team_password'),
			'icon' => 'fa fa-key',
			'position' => 30,
		]);

		if (has_permission('team_password', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('team_password', [
				'slug' => 'team_password-dashboard',
				'name' => _l('tp_dashboard'),
				'icon' => 'fa fa-home menu-icon',
				'href' => admin_url('team_password/dashboard'),
				'position' => 1,
			]);
		}

		$CI->app_menu->add_sidebar_children_item('team_password', [
			'slug' => 'team_password_management',
			'name' => _l('team_password'),
			'icon' => 'fa fa-key',
			'href' => admin_url('team_password/team_password_mgt?cate=all&type=normal'),
			'position' => 2,
		]);
		$CI->app_menu->add_sidebar_children_item('team_password', [
			'slug' => 'team_password_category_management',
			'name' => _l('category_management'),
			'icon' => 'fa fa-list-ul',
			'href' => admin_url('team_password/category_management'),
			'position' => 3,
		]);

		$CI->app_menu->add_sidebar_children_item('team_password', [
			'slug' => 'team_password-report',
			'name' => _l('statistical'),
			'icon' => 'fa fa-area-chart',
			'href' => admin_url('team_password/report'),
			'position' => 4,
		]);
	}

	if (is_admin()) {
		$CI->app_menu->add_sidebar_children_item('team_password', [
			'slug' => 'tp-setting',
			'name' => _l('setting_tp'),
			'icon' => 'fa fa-gear',
			'href' => admin_url('team_password/setting'),
			'position' => 5,
		]);
	}
}

/**
 * team password permissions
 */
function team_password_permissions() {
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];

	register_staff_capabilities('team_password', $capabilities, _l('team_password'));
}

/**
 * init add footer component
 */
function team_password_load_js() {
	$CI = &get_instance();

	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/team_password/category_management') === false)) {
		echo '<script src="' . module_dir_url('menu_setup', 'assets/jquery-nestable/jquery.nestable.js') . '"></script>';
		echo '<link href="' . module_dir_url('menu_setup', 'assets/font-awesome-icon-picker/css/fontawesome-iconpicker.min.css') . '" rel="stylesheet">';
		echo '<script src="' . module_dir_url('menu_setup', 'assets/font-awesome-icon-picker/js/fontawesome-iconpicker.js') . '"></script>';
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/category.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/team_password/view_normal') === false) || !(strpos($viewuri, '/admin/team_password/view_bank_account') === false) || !(strpos($viewuri, '/admin/team_password/view_credit_card') === false) || !(strpos($viewuri, '/admin/team_password/view_email') === false) || !(strpos($viewuri, '/admin/team_password/view_email') === false) || !(strpos($viewuri, '/admin/team_password/view_server') === false) || !(strpos($viewuri, '/admin/team_password/view_software_license') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/normal/permission.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/normal/share.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';

		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/file/file_action.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/team_password/team_password_mgt') === false)) {

		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/plugins/treeview/src/js/bootstrap-treeview.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/team_password/add_normal') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/normal/add_normal.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/team_password/add_bank_account') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/bank_account/add_bank_account.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/team_password/add_credit_card') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/credit_card/add_credit_card.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/team_password/add_email') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/email/add_email.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/team_password/add_server') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/server/add_server.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/team_password/add_software_license') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/software_license/add_software_license.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/team_password/report') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/report/report.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/team_password/dashboard') === false)) {
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}
}
/**
 *  add menu item and js file to client
 */
function team_password_module_init_client_menu_items() {
	$CI = &get_instance();
	if (is_client_logged_in() || is_staff_logged_in()) {
		$count = 0;
		$contact_id = get_contact_user_id();
		$CI->db->where('id', $contact_id);
		$data_contact = $CI->db->get(db_prefix() . 'contacts')->row();
		$data_share = [];
		if ($data_contact && isset($data_contact->email)) {
			$email = $data_contact->email;
			if ($email) {
				$data_share = $CI->db->query('select * from ' . db_prefix() . 'tp_share where client = \'' . $email . '\' or email = \'' . $email . '\'')->result_array();
				$count = count($data_share);
			}
		}

		$menu = '';

		$menu .= '<li class="customers-nav-item-Insurances-plan">
                          <a href="' . site_url('team_password/team_password_client/team_password_mgt') . '">
                            <i class=""></i> '
		. _l('team_password') . '
                          </a>
                         </li>';

        if(is_client_logged_in() && get_option('hide_password_from_client_area') == 0 ){
			echo html_entity_decode($menu);
		}

		echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/client/main.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
		echo '<link href="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/css/client/custom.css') . '?v=' . TEAM_PASSWORD_REVISION . '"  rel="stylesheet" type="text/css" />';
		$viewuri = $_SERVER['REQUEST_URI'];
		if (!(strpos($viewuri, '/team_password/team_password_client/view_share_client') === false)) {
			$arr = explode('/', $viewuri);
			if (isset($arr[5])) {
				switch ($arr[5]) {
				case 'normal':
					echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/normal/add_normal.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
					break;
				case 'bank_account':
					echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/bank_account/add_bank_account.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
					break;
				case 'credit_card':
					echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/credit_card/add_credit_card.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
					break;
				case 'email':
					echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/email/add_email.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
					break;
				case 'email':
					echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/email/add_email.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
					break;
				case 'server':
					echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/server/add_server.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
					break;
				case 'software_license':
					echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/software_license/add_software_license.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';
					break;
				}
			}

			echo '<script src="' . module_dir_url(TEAM_PASSWORD_MODULE_NAME, 'assets/js/file/file_action_client.js') . '?v=' . TEAM_PASSWORD_REVISION . '"></script>';

		}

	}
}

/**
 * Register other merge fields for teampassword
 *
 * @param [array] $for
 * @return void
 */
function teampassword_register_other_merge_fields($for) {
	$for[] = 'teampassword';

	return $for;
}

/**
 * Initializes the item relate contract.
 *
 * @param $contract  The contract
 */
function init_item_relate_contract($contract) {
	if ($contract) {
		echo '<li>
               <a href="' . admin_url('team_password/items_relate/' . $contract->id) . '" target="_blank">
               ' . _l('view_items_relate') . '
               </a>
        </li>';
	} else {
		echo '';
	}
}

/**
 * Initializes the tab item shared.
 *
 *
 */
function init_tab_item_shared() {
	echo '<li role="presentation">
                  <a href="#item_shared" aria-controls="item_shared" role="tab" data-toggle="tab">
                  ' . _l('item_shared') . '
                  </a>
               </li>';
}

/**
 * Initializes the tab item shared.
 *
 *
 */
function init_content_item_shared($client) {
	$CI = &get_instance();
	$CI->load->model('team_password/team_password_model');
	if ($client) {
		echo '<div role="tabpanel" class="tab-pane" id="item_shared">';
		require "modules/team_password/views/item_shared.php";
		echo '</div>';
	}
}

/**
 * Initializes the tab contracthtml.
 */
function init_tab_contracthtml() {
	if(has_permission('teampassword','','view') || is_admin() || is_client_logged_in()){
		echo '<li role="presentation" class="">
	               <a href="#items_relate" aria-controls="items_relate" role="tab" data-toggle="tab">
	               <i class="fa fa-key" aria-hidden="true"></i> ' . _l('items_relate') . '
	               </a>
	            </li>';
    }
}

/**
 * Initializes the tab contracthtml.
 */
function init_tab_contracthtml_content($contract) {
	$CI = &get_instance();
	if(has_permission('teampassword','','view') || is_admin() || is_client_logged_in()){

		if(is_client_logged_in()){
			if(get_option('hide_password_from_client_area') == 0){
				echo '<div role="tabpanel" class="tab-pane" id="items_relate">';
				
				 require "modules/team_password/views/items_relate.php";
				
				echo '</div>';
			}
		}else{
			echo '<div role="tabpanel" class="tab-pane" id="items_relate">';
			
			 require "modules/team_password/views/items_relate.php";
			
			echo '</div>';
		}
	}
}

/**
 * Initializes the project item relate.
 *
 * @param  $project  The project
 */
function init_project_item_relate($project) {
	$CI = &get_instance();
	if(has_permission('teampassword','','view') || is_admin() || is_client_logged_in()){
		if(is_client_logged_in()){
			if(get_option('hide_password_from_client_area') == 0){
				require "modules/team_password/views/project_items_relate.php";
			}
		}else{
			require "modules/team_password/views/project_items_relate.php";
		}
	}

}

/**
 * { teampassword unset data update client }
 *
 * @param        $data   The data
 * @param        $id     The identifier
 *
 * @return     $data  
 */
function teampassword_unset_data_update_client($data,$id){
	if(isset($data['DataTables_Table_0_length'])){
		unset($data['DataTables_Table_0_length']);
	}

	if(isset($data['DataTables_Table_1_length'])){
		unset($data['DataTables_Table_1_length']);
	}

	if(isset($data['DataTables_Table_2_length'])){
		unset($data['DataTables_Table_2_length']);
	}

	if(isset($data['DataTables_Table_3_length'])){
		unset($data['DataTables_Table_3_length']);
	}

	return  $data;
}