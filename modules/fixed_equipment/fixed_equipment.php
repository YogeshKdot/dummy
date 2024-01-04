<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Fixed Equipment Management
Description: This module provides important details such as check-in and checks outs, a location of the assets, depreciation, audit, maintenance schedule, date of return of the assets among other details.
Version: 1.0.2
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('FIXED_EQUIPMENT_MODULE_NAME', 'fixed_equipment');
define('FIXED_EQUIPMENT_MODULE_UPLOAD_FOLDER', module_dir_path(FIXED_EQUIPMENT_MODULE_NAME, 'uploads'));
define('FIXED_EQUIPMENT_PATH', 'modules/fixed_equipment/uploads/');
define('FIXED_EQUIPMENT_IMAGE_UPLOADED_PATH', 'modules/fixed_equipment/uploads/');
define('FIXED_EQUIPMENT_REVISION', 1021);
define('FIXED_EQUIPMENT_PATH_PLUGIN', 'modules/fixed_equipment/assets/plugins');
define('FIXED_EQUIPMENT_LIBRARIES', 'modules/fixed_equipment/libraries');
define('FIXED_EQUIPMENT_IMPORT_ITEM_ERROR', 'modules/fixed_equipment/uploads/import_item_error/');


hooks()->add_action('admin_init', 'fixed_equipment_permissions');
hooks()->add_action('admin_init', 'fixed_equipment_module_init_menu_items');
hooks()->add_action('app_admin_head', 'fixed_equipment_add_head_components');
hooks()->add_action('app_admin_footer', 'fixed_equipment_load_js');
hooks()->add_action('hr_profile_tab_name', 'fixed_equipment_add_tab_name');
hooks()->add_action('hr_profile_tab_content', 'fixed_equipment_add_tab_content');
hooks()->add_action('hr_profile_load_js_file', 'fixed_equipment_hr_profile_load_js_file');
hooks()->add_filter('hr_profile_load_icon', 'fixed_equipment_load_icon', 10, 2);
/*Attendance export excel path*/
define('FIXED_EQUIPMENT_PATH_EXPORT_FILE', 'modules/fixed_equipment/uploads/attendance/');

hooks()->add_action('after_custom_fields_select_options','init_fixed_equipment_customfield');

/**
* Register activation module hook
*/
register_activation_hook(FIXED_EQUIPMENT_MODULE_NAME, 'fixed_equipment_module_activation_hook');
/**
 * activation hook
 */
function fixed_equipment_module_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__ . '/install.php');
}

register_language_files(FIXED_EQUIPMENT_MODULE_NAME, [FIXED_EQUIPMENT_MODULE_NAME]);

$CI = & get_instance();
$CI->load->helper(FIXED_EQUIPMENT_MODULE_NAME . '/fixed_equipment');

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function fixed_equipment_module_init_menu_items()
{  
	$CI = &get_instance();
	if (has_permission('fixed_equipment_dashboard', '', 'view_own') ||
		has_permission('fixed_equipment_dashboard', '', 'view') ||
		has_permission('fixed_equipment_assets', '', 'view_own') ||
		has_permission('fixed_equipment_assets', '', 'view') ||
		has_permission('fixed_equipment_licenses', '', 'view_own') ||
		has_permission('fixed_equipment_licenses', '', 'view') ||
		has_permission('fixed_equipment_accessories', '', 'view_own') ||
		has_permission('fixed_equipment_accessories', '', 'view') ||
		has_permission('fixed_equipment_consumables', '', 'view_own') ||
		has_permission('fixed_equipment_consumables', '', 'view') ||
		has_permission('fixed_equipment_components', '', 'view_own') ||
		has_permission('fixed_equipment_components', '', 'view') ||
		has_permission('fixed_equipment_predefined_kits', '', 'view_own') ||
		has_permission('fixed_equipment_predefined_kits', '', 'view') ||
		has_permission('fixed_equipment_requested', '', 'view_own') ||
		has_permission('fixed_equipment_requested', '', 'view') ||
		has_permission('fixed_equipment_maintenances', '', 'view_own') ||
		has_permission('fixed_equipment_maintenances', '', 'view') ||
		has_permission('fixed_equipment_audit', '', 'view_own') ||
		has_permission('fixed_equipment_audit', '', 'view') ||
		has_permission('fixed_equipment_report', '', 'view_own') ||
		has_permission('fixed_equipment_report', '', 'view') ||
		has_permission('fixed_equipment_depreciations', '', 'view_own') ||
		has_permission('fixed_equipment_depreciations', '', 'view') ||
		has_permission('fixed_equipment_sign_manager', '', 'view_own') ||
		has_permission('fixed_equipment_sign_manager', '', 'view') ||
		is_admin()) {

		$CI->app_menu->add_sidebar_menu_item('fixed_equipment', [
			'name'     => _l('fe_fixed_equipment'),
			'icon'     => 'fa fa-bullseye',
			'position' => 30,
		]);

		if (has_permission('fixed_equipment_dashboard', '', 'view_own') || has_permission('fixed_equipment_dashboard', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_dashboard',
				'name'     => _l('fe_dashboard'),
				'href'     => admin_url('fixed_equipment/dashboard'),
				'icon'     => 'fa fa-dashboard menu-icon',
				'position' =>0,
			]);
		}  

		if (has_permission('fixed_equipment_assets', '', 'view_own') || has_permission('fixed_equipment_assets', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_assets',
				'name'     => _l('fe_assets'),
				'href'     => admin_url('fixed_equipment/assets'),
				'icon'     => 'fa fa-bath',
				'position' =>1,
			]);
		} 

		if (has_permission('fixed_equipment_licenses', '', 'view_own') || has_permission('fixed_equipment_licenses', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_licenses',
				'name'     => _l('fe_licenses'),
				'href'     => admin_url('fixed_equipment/licenses'),
				'icon'     => 'fa fa-certificate',
				'position' =>2,
			]);
		} 


		if (has_permission('fixed_equipment_accessories', '', 'view_own') || has_permission('fixed_equipment_accessories', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_accessories',
				'name'     => _l('fe_accessories'),
				'href'     => admin_url('fixed_equipment/accessories'),
				'icon'     => 'fa fa-check',
				'position' =>3,
			]);
		} 


		if (has_permission('fixed_equipment_consumables', '', 'view_own') || has_permission('fixed_equipment_consumables', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_consumables',
				'name'     => _l('fe_consumables'),
				'href'     => admin_url('fixed_equipment/consumables'),
				'icon'     => 'fa fa-life-ring',
				'position' =>4,
			]);
		} 


		if (has_permission('fixed_equipment_components', '', 'view_own') || has_permission('fixed_equipment_components', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_components',
				'name'     => _l('fe_components'),
				'href'     => admin_url('fixed_equipment/components'),
				'icon'     => 'fa fa-recycle',
				'position' =>5,
			]);
		} 

		if (has_permission('fixed_equipment_predefined_kits', '', 'view_own') || has_permission('fixed_equipment_predefined_kits', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_predefined_kits',
				'name'     => _l('fe_predefined_kits'),
				'href'     => admin_url('fixed_equipment/predefined_kits'),
				'icon'     => 'fa fa-object-group',
				'position' =>6,
			]);
		} 
		if (has_permission('fixed_equipment_sign_manager', '', 'view_own') || has_permission('fixed_equipment_sign_manager', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_checkout_mgt',
				'name'     => _l('fe_sign_manager'),
				'href'     => admin_url('fixed_equipment/checkout_managements'),
				'icon'     => 'fa fa-arrow-right menu-icon',
				'position' =>6,
			]);
		} 
		if (has_permission('fixed_equipment_requested', '', 'view_own') || has_permission('fixed_equipment_requested', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_requested',
				'name'     => _l('fe_requested'),
				'href'     => admin_url('fixed_equipment/requested'),
				'icon'     => 'fa fa-undo',
				'position' =>7,
			]);
		}

		if (has_permission('fixed_equipment_maintenances', '', 'view_own') || has_permission('fixed_equipment_maintenances', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_assets_maintenances',
				'name'     => _l('fe_maintenances'),
				'href'     => admin_url('fixed_equipment/assets_maintenances'),
				'icon'     => 'fa fa-wrench',
				'position' =>8,
			]);
		} 


		if (has_permission('fixed_equipment_audit', '', 'view_own') || has_permission('fixed_equipment_audit', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_bulk_audit',
				'name'     => _l('fe_audit'),
				'href'     => admin_url('fixed_equipment/audit_managements'),
				'icon'     => 'fa fa-file',
				'position' =>9,
			]);
		}  

		if (has_permission('fixed_equipment_depreciations', '', 'view_own') || has_permission('fixed_equipment_depreciations', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_depreciations',
				'name'     => _l('fe_depreciations'),
				'href'     => admin_url('fixed_equipment/depreciations'),
				'icon'     => 'fa fa-circle',
				'position' =>10,
			]);
		} 


		if (has_permission('fixed_equipment_locations', '', 'view_own') || has_permission('fixed_equipment_locations', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_locations',
				'name'     => _l('fe_locations'),
				'href'     => admin_url('fixed_equipment/locations'),
				'icon'     => 'fa fa-map menu-icon',
				'position' =>11,
			]);
		} 

		if (has_permission('fixed_equipment_report', '', 'view_own') || has_permission('fixed_equipment_report', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_report',
				'name'     => _l('fe_report'),
				'href'     => admin_url('fixed_equipment/report'),
				'icon'     => 'fa fa-pie-chart menu-icon',
				'position' =>12,
			]);
		} 


		if (is_admin()) {
			$CI->app_menu->add_sidebar_children_item('fixed_equipment', [
				'slug'     => 'fixed_equipment_settings',
				'name'     => _l('fe_settings'),
				'href'     => admin_url('fixed_equipment/settings?tab=depreciations'),
				'icon'     => 'fa fa-cogs',
				'position' =>13,
			]);
		}  



	}
}
/**
 * load js
 */
function fixed_equipment_load_js(){
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=depreciations') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/depreciations.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=locations') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/locations.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=suppliers') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/suppliers.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=asset_manufacturers') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/asset_manufacturers.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=categories') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/categories.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=approval_settings') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/approval_settings.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=models') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/models.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=status_labels') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/status_labels.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=permission') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/permission.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/view_model') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/view_model.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_asset') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_asset.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/licenses') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/licenses.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_licenses') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_licenses.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/accessories') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/accessories.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/consumables') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/consumables.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/components') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/components.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/predefined_kits') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/predefined_kits.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_predefined_kits') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_predefined_kits.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_accessories') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_accessories.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_consumables') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_consumables.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_components') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_components.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/assets_maintenances') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/assets_maintenances.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/requested') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/requested.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_request') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_request.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/settings?tab=custom_field') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/custom_field.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_customfield') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/settings/detail_customfield.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/audit_request') === false) || !(strpos($viewuri, '/admin/fixed_equipment/view_audit_request') === false) || !(strpos($viewuri, '/admin/fixed_equipment/audit') === false)) {
		echo '<link rel="stylesheet prefetch" href="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.css').'">';
		echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.jquery.js').'"></script>';
		echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/handsontable-chosen-editor.js').'"></script>' ;
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'third_party/scan_qrcodes/html5-qrcode.min.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/audit_managements') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/audit_managements.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/view_audit_request') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/view_audit_request.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/depreciations') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/depreciations.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/locations') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/locations.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/detail_locations') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/detail_locations.js') . '"></script>';
		$googlemap_api_key = '';
		$api_key = get_option('fe_googlemap_api_key');
		if($api_key){
			$googlemap_api_key = $api_key;
		}	
		echo '<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>';
		echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$googlemap_api_key.'&callback=initMap&libraries=&v=weekly" defer></script>';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/checkout_managements') === false)) {
		echo '<script src="' . site_url('assets/plugins/signature-pad/signature_pad.min.js') . '"></script>';
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/js/checkout_managements.js') . '"></script>';
	}
}
/**
 * fixed equipment add head components
 */
function fixed_equipment_add_head_components(){
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	echo '<link href="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/css/style.css') . '"  rel="stylesheet" type="text/css" />';
	if (!(strpos($viewuri, '/admin/fixed_equipment/assets') === false)) {
		echo '<link href="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/css/asset_management.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/audit_request') === false) || !(strpos($viewuri, '/admin/fixed_equipment/view_audit_request') === false) || !(strpos($viewuri, '/admin/fixed_equipment/audit') === false)) {
		echo '<script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>';
		echo '<link href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" rel="stylesheet">';
	}

	if (!(strpos($viewuri, '/admin/fixed_equipment/report') === false) || !(strpos($viewuri, '/admin/fixed_equipment/dashboard') === false)) {
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>'; 
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/predefined_kits') === false)) {
		echo '<link href="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/css/predefined_kits.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/checkout_managements') === false)) {
		echo '<link href="' . site_url('assets/themes/perfex/css/style.min.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/fixed_equipment/audit') === false)) {
		echo '<link href="' . module_dir_url(FIXED_EQUIPMENT_MODULE_NAME, 'assets/css/audit.css') . '"  rel="stylesheet" type="text/css" />';
	}
}
/**
 * fixed equipment permissions
 */
function fixed_equipment_permissions()
{
	$capabilities = [];

	// dashboard
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
	];
	register_staff_capabilities('fixed_equipment_dashboard', $capabilities, _l('fe_fixed_equipment_dashboard'));

		// asset
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_assets', $capabilities, _l('fe_fixed_equipment_assets'));

		// licenses
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_licenses', $capabilities, _l('fe_fixed_equipment_licenses'));

		// accessories
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_accessories', $capabilities, _l('fe_fixed_equipment_accessories'));

		// consumables
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_consumables', $capabilities, _l('fe_fixed_equipment_consumables'));

		// components
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_components', $capabilities, _l('fe_fixed_equipment_components'));

		// predefined_kits
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_predefined_kits', $capabilities, _l('fe_fixed_equipment_predefined_kits'));

		// requested
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_requested', $capabilities, _l('fe_fixed_equipment_requested'));

		// maintenances
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_maintenances', $capabilities, _l('fe_fixed_equipment_maintenances'));

		// audit
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_audit', $capabilities, _l('fe_fixed_equipment_audit'));

	// locations
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('edit'),
		'delete' => _l('delete')
	];
	register_staff_capabilities('fixed_equipment_locations', $capabilities, _l('fe_fixed_equipment_locations'));

		// report
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
	];
	register_staff_capabilities('fixed_equipment_report', $capabilities, _l('fe_fixed_equipment_report'));

	// sign manager
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create')
	];
	register_staff_capabilities('fixed_equipment_sign_manager', $capabilities, _l('fe_fixed_equipment_sign_manager'));

	// depreciations
	$capabilities['capabilities'] = [
		'view_own' => _l('permission_view'),
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
	];
	register_staff_capabilities('fixed_equipment_depreciations', $capabilities, _l('fe_fixed_equipment_depreciations'));

}
/**
 * Initializes the fixed_equipment customfield.
 * @param string  $custom_field  The custom field
 */
function init_fixed_equipment_customfield($custom_field = ''){
	$select = '';
	if($custom_field != ''){
		if($custom_field->fieldto == 'fixed_equipment_name'){
			$select = 'selected';
		}
	}
	$html = '<option value="fixed_equipment_name" '.$select.' >'. _l('fe_fixed_equipment').'</option>';
	echo html_entity_decode($html);
}

/**
 * fixed equipment add tab name
 * @param  string $tab_names 
 * @return string            
 */
function fixed_equipment_add_tab_name($tab_names)
{
	$tab_names[] = 'fe_asset';
	return $tab_names;
}

/**
 * fixed equipment add tab content
 * @param  string $tab_content_link 
 * @return  string                  
 */
function fixed_equipment_add_tab_content($tab_content_link)
{
	if(!(strpos($tab_content_link, 'hr_record/includes/fe_asset') === false)){
		$tab_content_link = FIXED_EQUIPMENT_MODULE_NAME.'/employee_asset/asset_list_content';
	}
	return $tab_content_link;
}

/**
 * fixed equipment hr profile load js file
 * @param  string $group_name 
 */
function fixed_equipment_hr_profile_load_js_file($group_name)
{
	if($group_name == 'fe_asset'){
		echo require('modules/fixed_equipment/assets/js/staff_asset_js.php');		
	}
}
/**
 * fixed equipment load icon
 * @param  string $icon  
 * @param  string $group 
 * @return string        
 */
function fixed_equipment_load_icon($icon, $group)
{
    if($group == 'fe_asset'){
        $icon = '<span class="fa fa-bath fa-fw fa-lg"></span>';
    }
    return $icon;
}
