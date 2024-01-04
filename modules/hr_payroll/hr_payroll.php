<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: HR Payroll
Description: This module encompasses everything that goes into onboarding and paying your employees.
Version: 1.0.4
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('HR_PAYROLL_MODULE_NAME', 'hr_payroll');
define('HR_PAYROLL_MODULE_UPLOAD_FOLDER', module_dir_path(HR_PAYROLL_MODULE_NAME, 'uploads'));
define('HR_PAYROLL_ATTENDANCE_SAMPLE_UPLOAD_FOLDER', module_dir_path(HR_PAYROLL_MODULE_NAME, 'uploads/attendance_sample_file/'));
define('HR_PAYROLL_PAYSLIP_FOLDER', module_dir_path(HR_PAYROLL_MODULE_NAME, 'uploads/payslip/'));

define('HR_PAYROLL_CREATE_PAYSLIP_EXCEL', 'modules/hr_payroll/uploads/payslip_excel_file/');
define('HR_PAYROLL_CREATE_ATTENDANCE_SAMPLE', 'modules/hr_payroll/uploads/attendance_sample_file/');
define('HR_PAYROLL_CREATE_EMPLOYEES_SAMPLE', 'modules/hr_payroll/uploads/employees_sample_file/');
define('HR_PAYROLL_CREATE_COMMISSIONS_SAMPLE', 'modules/hr_payroll/uploads/commissions_sample_file/');
define('HR_PAYROLL_ERROR', 'modules/hr_payroll/uploads/file_error_response/');
define('HR_PAYROLL_PAYSLIP_FILE', 'modules/hr_payroll/uploads/payslip/');
define('HR_PAYROLL_EXPORT_EMPLOYEE_PAYSLIP', module_dir_path(HR_PAYROLL_MODULE_NAME, 'uploads/export_employee_payslip/'));

define('HR_PAYROLL_REVISION', 104);

//prefix for contract
define('HR_PAYROLL_PREFIX_PROBATIONARY', ' (CT1)');
define('HR_PAYROLL_PREFIX_FORMAL', ' (CT2)');


hooks()->add_action('admin_init', 'hr_payroll_permissions');
hooks()->add_action('app_admin_head', 'hr_payroll_add_head_components');
hooks()->add_action('app_admin_footer', 'hr_payroll_load_js');
hooks()->add_action('admin_init', 'hr_payroll_module_init_menu_items');

//hr profile hook
hooks()->add_filter('hr_profile_tab_name', 'hr_payroll_add_tab_name', 10);
hooks()->add_filter('hr_profile_tab_content', 'hr_payroll_add_tab_content', 10);
hooks()->add_action('hr_profile_load_js_file', 'hr_payroll_load_js_file');

/**
* Register activation module hook
*/
register_activation_hook(HR_PAYROLL_MODULE_NAME, 'hr_payroll_module_activation_hook');


/**
 * hr payroll module activation hook
 * @return [type] 
 */
function hr_payroll_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(HR_PAYROLL_MODULE_NAME, [HR_PAYROLL_MODULE_NAME]);


$CI = & get_instance();
$CI->load->helper(HR_PAYROLL_MODULE_NAME . '/hr_payroll');

/**
 * Init hr payroll module menu items in setup in admin_init hook
 * @return null
 */
function hr_payroll_module_init_menu_items()
{
    $CI = &get_instance();

    if(has_permission('hrp_employee','','view') || has_permission('hrp_attendance','','view') || has_permission('hrp_commission','','view') || has_permission('hrp_deduction','','view') || has_permission('hrp_bonus_kpi','','view') || has_permission('hrp_insurrance','','view') || has_permission('hrp_payslip','','view') || has_permission('hrp_payslip_template','','view') || has_permission('hrp_income_tax','','view') || has_permission('hrp_report','','view') || has_permission('hrp_setting','','view') || has_permission('hrp_employee','','view_own') || has_permission('hrp_attendance','','view_own') || has_permission('hrp_commission','','view_own') || has_permission('hrp_deduction','','view_own') || has_permission('hrp_bonus_kpi','','view_own') || has_permission('hrp_insurrance','','view_own') || has_permission('hrp_payslip','','view_own') || has_permission('hrp_payslip_template','','view_own') || has_permission('hrp_income_tax','','view_own')){
       $CI->app_menu->add_sidebar_menu_item('hr_payroll', [
        'name'     => _l('hr_payroll'),
        'icon'     => 'fa fa-users',
        'position' => 30,
    ]);
   }

    if(has_permission('hrp_employee','','view') || has_permission('hrp_employee','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hr_manage_employees',
            'name'     => _l('hr_manage_employees'),
            'icon'     => 'fa fa-vcard',
            'href'     => admin_url('hr_payroll/manage_employees'),
            'position' => 1,
        ]);
    }

    if(has_permission('hrp_attendance','','view') || has_permission('hrp_attendance','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hr_manage_attendance',
            'name'     => _l('hr_manage_attendance'),
            'icon'     => 'fa fa-pencil-square menu-icon',
            'href'     => admin_url('hr_payroll/manage_attendance'),
            'position' => 2,
        ]);
    }

    if(has_permission('hrp_commission','','view') || has_permission('hrp_commission','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hr_manage_commissions',
            'name'     => _l('hrp_commission_manage'),
            'icon'     => 'fa fa-american-sign-language-interpreting',
            'href'     => admin_url('hr_payroll/manage_commissions'),
            'position' => 3,
        ]);
    }


    if(has_permission('hrp_deduction','','view') || has_permission('hrp_deduction','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hr_manage_deductions',
            'name'     => _l('hrp_deduction_manage'),
            'icon'     => 'fa fa-cut',
            'href'     => admin_url('hr_payroll/manage_deductions'),
            'position' => 4,
        ]);
    }


    if(has_permission('hrp_bonus_kpi','','view') || has_permission('hrp_bonus_kpi','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hr_bonus_kpi',
            'name'     => _l('hr_bonus_kpi'),
            'icon'     => 'fa fa-gift',
            'href'     => admin_url('hr_payroll/manage_bonus'),
            'position' => 5,
        ]);
    }

    if(has_permission('hrp_insurrance','','view') || has_permission('hrp_insurrance','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hrp_insurrance',
            'name'     => _l('hrp_insurrance'),
            'icon'     => 'fa fa-medkit',
            'href'     => admin_url('hr_payroll/manage_insurances'),
            'position' => 6,
        ]);
    }

    if(has_permission('hrp_payslip','','view') || has_permission('hrp_payslip','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hr_pay_slips',
            'name'     => _l('hr_pay_slips'),
            'icon'     => 'fa-solid fa-money-check',
            'href'     => admin_url('hr_payroll/payslip_manage'),
            'position' => 7,
        ]);
    }

    if(has_permission('hrp_payslip_template','','view') || has_permission('hrp_payslip_template','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hrp_payslip_template',
            'name'     => _l('hr_pay_slip_templates'),
            'icon'     => 'fa fa-outdent',
            'href'     => admin_url('hr_payroll/payslip_templates_manage'),
            'position' => 8,
        ]);
    }

    if(has_permission('hrp_income_tax','','view') || has_permission('hrp_income_tax','','view_own')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hrp_income_tax',
            'name'     => _l('hrp_income_tax'),
            'icon'     => 'fa fa-calendar-minus',
            'href'     => admin_url('hr_payroll/income_taxs_manage'),
            'position' => 9,
        ]);
    }

    if(has_permission('hrp_report','','view')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hr_payroll_reports',
            'name'     => _l('hrp_reports'),
            'icon'     => 'fa fa-list-alt',
            'href'     => admin_url('hr_payroll/reports'),
            'position' => 10,
        ]);
    }

    if(has_permission('hrp_setting','','view')){
        $CI->app_menu->add_sidebar_children_item('hr_payroll', [
            'slug'     => 'hrp_settings',
            'name'     => _l('settings'),
            'icon'     => 'fa fa-cog menu-icon',
            'href'     => admin_url('hr_payroll/setting?group=income_tax_rates'),
            'position' => 11,
        ]);

    }
}


/**
 * hr payroll load js
 * @return library 
 */
function hr_payroll_load_js(){
    $CI = &get_instance();

    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri,'admin/hr_payroll') === false)){
        echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/js/deactivate_hotkey.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    }

    if (!(strpos($viewuri, '/admin/hr_payroll/setting') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_employees') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_attendance') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_deductions') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_commissions') === false) || !(strpos($viewuri, '/admin/hr_payroll/income_taxs_manage') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_insurances') === false) ) {   
     echo '<script src="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
     echo '<script src="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
 }

 if (!(strpos($viewuri,'admin/hr_payroll/view_payslip_templates_detail') === false)){
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/luckysheet.umd_payslip.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';

}

if (!(strpos($viewuri,'admin/hr_payroll/view_payslip_detail') === false)){
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/luckysheet.umd_payslip.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
}



if (!(strpos($viewuri,'admin/hr_payroll/view_payslip_templates_detail') === false) || !(strpos($viewuri,'admin/hr_payroll/view_payslip') === false) ) {

    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/spectrum.min.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/plugin.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/js/manage.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/vue.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/vuex.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/vuexx.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/index.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/echarts.min.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/chartmix.umd.min.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/FileSaver.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script  src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/excel.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script  src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/js/exports.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/js/upload_file.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/luckyexcel.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/js/store.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
}

if(!(strpos($viewuri,'admin/hr_payroll/reports') === false)){

    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/highcharts/exporting.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
    echo '<script src="'.module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/highcharts/series-label.js').'?v=' . HR_PAYROLL_REVISION.'"></script>';
}

}


/**
 * hr payroll add head components
 * @return library 
 */
function hr_payroll_add_head_components(){
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri, '/admin/hr_payroll') === false)) { 
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/css/styles.css') . '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri, '/admin/hr_payroll/setting') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_employees') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_attendance') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_deductions') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_commissions') === false) || !(strpos($viewuri, '/admin/hr_payroll/income_taxs_manage') === false) || !(strpos($viewuri, '/admin/hr_payroll/manage_insurances') === false) ) { 

        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<script src="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
    }

    if (!(strpos($viewuri,'admin/hr_payroll/view_payslip_templates_detail') === false) || !(strpos($viewuri,'admin/hr_payroll/view_payslip') === false)  ) {

        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/css/manage.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/iconfont.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/plugins.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/pluginsCss.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';

        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/iconCustom.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-cellFormat.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        //not scroll
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-core.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-print.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-protection.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-zoom.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/chartmix.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/spectrum.min.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/plugins/luckysheet/css/chartmix.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri,'admin/hr_payroll/manage_bonus') === false) ) {

    }

    if (!(strpos($viewuri,'admin/hr_payroll/payslip_manage') === false) || !(strpos($viewuri,'admin/hr_payroll/payslip_templates_manage') === false) ) {
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/css/modal_dialog.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri, '/admin/hr_payroll/import_xlsx_attendance') === false) || !(strpos($viewuri, '/admin/hr_payroll/import_xlsx_employees') === false) || !(strpos($viewuri,'admin/hr_payroll/import_xlsx_commissions') === false) || !(strpos($viewuri,'admin/hr_payroll/view_payslip_detail') === false) || !(strpos($viewuri,'admin/hr_payroll/payslip_manage') === false) ) {
       echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/css/box_loading/box_loading.css')  .'?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />'; 
   }

   if (!(strpos($viewuri,'admin/hr_payroll/view_payslip_detail') === false) || !(strpos($viewuri,'admin/hr_payroll/view_payslip_templates_detail') === false) ) {
        echo '<link href="' . module_dir_url(HR_PAYROLL_MODULE_NAME, 'assets/css/luckysheet.css') . '?v=' . HR_PAYROLL_REVISION. '"  rel="stylesheet" type="text/css" />';
    }

}



/**
 * hr payroll permissions
 * @return capabilities 
 */
function hr_payroll_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    $dashboard['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        
    ];

    $capabilities_3['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    $capabilities_4['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
    ];

    register_staff_capabilities('hrp_employee', $capabilities_3, _l('hr_payroll_employee'));
    register_staff_capabilities('hrp_attendance', $capabilities_3, _l('hr_payroll_attendance'));
    register_staff_capabilities('hrp_commission', $capabilities_3, _l('hr_payroll_commission'));
    register_staff_capabilities('hrp_deduction', $capabilities_3, _l('hr_payroll_deduction'));
    register_staff_capabilities('hrp_bonus_kpi', $capabilities_3, _l('hr_payroll_bonus_kpi'));
    register_staff_capabilities('hrp_insurrance', $capabilities_3, _l('hr_payroll_insurrance'));
    register_staff_capabilities('hrp_payslip', $capabilities_3, _l('hr_payroll_payslip'));
    register_staff_capabilities('hrp_payslip_template', $capabilities_3, _l('hr_payroll_payslip_template'));
    register_staff_capabilities('hrp_income_tax', $capabilities_4, _l('hr_payroll_income_tax'));
    register_staff_capabilities('hrp_report', $dashboard, _l('hr_payroll_report'));
    register_staff_capabilities('hrp_setting', $capabilities, _l('hr_payroll_setting'));

}


/**
 * hr payroll add tab name
 * @param  [type] $row  
 * @param  [type] $aRow 
 * @return [type]       
 */
function hr_payroll_add_tab_name($tab_names)
{
    $tab_names[] = 'hrp_payslip';
    return $tab_names;
}


/**
 * hr payroll add tab content
 * @param  [type] $tab_content_link 
 * @return [type]                   
 */
function hr_payroll_add_tab_content($tab_content_link)
{
    if(!(strpos($tab_content_link, 'hr_record/includes/hrp_payslip') === false)){
        $tab_content_link = 'hr_payroll/employee_payslip/staff_payslip_tab_content';
    }

    return $tab_content_link;
}


/**
 * hr payroll load js file
 * @param  [type] $group_name 
 * @return [type]             
 */
function hr_payroll_load_js_file($group_name)
{
    echo  require 'modules/hr_payroll/assets/js/employee_payslip/payslip_js.php';

}


eval(base64_decode("aG9va3MoKS0+YWRkX2FjdGlvbignaHJfcGF5cm9sbF9pbml0JyxIUl9QQVlST0xMX01PRFVMRV9OQU1FLidfYXBwaW50Jyk7Cmhvb2tzKCktPmFkZF9hY3Rpb24oJ3ByZV9hY3RpdmF0ZV9tb2R1bGUnLCBIUl9QQVlST0xMX01PRFVMRV9OQU1FLidfcHJlYWN0aXZhdGUnKTsKaG9va3MoKS0+YWRkX2FjdGlvbigncHJlX2RlYWN0aXZhdGVfbW9kdWxlJywgSFJfUEFZUk9MTF9NT0RVTEVfTkFNRS4nX3ByZWRlYWN0aXZhdGUnKTs="));

eval(base64_decode("ZnVuY3Rpb24gaHJfcGF5cm9sbF9hcHBpbnQoKXsKICAgICRDSSA9ICYgZ2V0X2luc3RhbmNlKCk7ICAgIAogICAgcmVxdWlyZV9vbmNlICdsaWJyYXJpZXMvZ3Rzc2xpYi5waHAnOwogICAgJGhycGF5cm9sbF9hcGkgPSBuZXcgSFJQYXlyb2xsTGljKCk7CiAgICAkaHJwYXlyb2xsX2d0c3NyZXMgPSAkaHJwYXlyb2xsX2FwaS0+dmVyaWZ5X2xpY2Vuc2UodHJ1ZSk7ICAgIAogICAgaWYoISRocnBheXJvbGxfZ3Rzc3JlcyB8fCAoJGhycGF5cm9sbF9ndHNzcmVzICYmIGlzc2V0KCRocnBheXJvbGxfZ3Rzc3Jlc1snc3RhdHVzJ10pICYmICEkaHJwYXlyb2xsX2d0c3NyZXNbJ3N0YXR1cyddKSl7CiAgICAgICAgICRDSS0+YXBwX21vZHVsZXMtPmRlYWN0aXZhdGUoSFJfUEFZUk9MTF9NT0RVTEVfTkFNRSk7CiAgICAgICAgc2V0X2FsZXJ0KCdkYW5nZXInLCAiT25lIG9mIHlvdXIgbW9kdWxlcyBmYWlsZWQgaXRzIHZlcmlmaWNhdGlvbiBhbmQgZ290IGRlYWN0aXZhdGVkLiBQbGVhc2UgcmVhY3RpdmF0ZSBvciBjb250YWN0IHN1cHBvcnQuIik7CiAgICAgICAgcmVkaXJlY3QoYWRtaW5fdXJsKCdtb2R1bGVzJykpOwogICAgfSAgICAKfQ=="));

eval(base64_decode("ZnVuY3Rpb24gaHJfcGF5cm9sbF9wcmVhY3RpdmF0ZSgkbW9kdWxlX25hbWUpewogICAgaWYgKCRtb2R1bGVfbmFtZVsnc3lzdGVtX25hbWUnXSA9PSBIUl9QQVlST0xMX01PRFVMRV9OQU1FKSB7ICAgICAgICAgICAgIAogICAgICAgIHJlcXVpcmVfb25jZSAnbGlicmFyaWVzL2d0c3NsaWIucGhwJzsKICAgICAgICAkaHJwYXlyb2xsX2FwaSA9IG5ldyBIUlBheXJvbGxMaWMoKTsKICAgICAgICAkaHJwYXlyb2xsX2d0c3NyZXMgPSAkaHJwYXlyb2xsX2FwaS0+dmVyaWZ5X2xpY2Vuc2UoKTsgICAgICAgICAgCiAgICAgICAgaWYoISRocnBheXJvbGxfZ3Rzc3JlcyB8fCAoJGhycGF5cm9sbF9ndHNzcmVzICYmIGlzc2V0KCRocnBheXJvbGxfZ3Rzc3Jlc1snc3RhdHVzJ10pICYmICEkaHJwYXlyb2xsX2d0c3NyZXNbJ3N0YXR1cyddKSl7CiAgICAgICAgICAgICAkQ0kgPSAmIGdldF9pbnN0YW5jZSgpOwogICAgICAgICAgICAkZGF0YVsnc3VibWl0X3VybCddID0gJG1vZHVsZV9uYW1lWydzeXN0ZW1fbmFtZSddLicvZ3RzdmVyaWZ5L2FjdGl2YXRlJzsgCiAgICAgICAgICAgICRkYXRhWydvcmlnaW5hbF91cmwnXSA9IGFkbWluX3VybCgnbW9kdWxlcy9hY3RpdmF0ZS8nLkhSX1BBWVJPTExfTU9EVUxFX05BTUUpOyAKICAgICAgICAgICAgJGRhdGFbJ21vZHVsZV9uYW1lJ10gPSBIUl9QQVlST0xMX01PRFVMRV9OQU1FOyAKICAgICAgICAgICAgJGRhdGFbJ3RpdGxlJ10gPSAiTW9kdWxlIExpY2Vuc2UgQWN0aXZhdGlvbiI7IAogICAgICAgICAgICBlY2hvICRDSS0+bG9hZC0+dmlldygkbW9kdWxlX25hbWVbJ3N5c3RlbV9uYW1lJ10uJy9hY3RpdmF0ZScsICRkYXRhLCB0cnVlKTsKICAgICAgICAgICAgZXhpdCgpOwogICAgICAgIH0gICAgICAgIAogICAgfQp9"));

eval(base64_decode("ZnVuY3Rpb24gaHJfcGF5cm9sbF9wcmVkZWFjdGl2YXRlKCRtb2R1bGVfbmFtZSl7CiAgICBpZiAoJG1vZHVsZV9uYW1lWydzeXN0ZW1fbmFtZSddID09IEhSX1BBWVJPTExfTU9EVUxFX05BTUUpIHsKICAgICAgICByZXF1aXJlX29uY2UgJ2xpYnJhcmllcy9ndHNzbGliLnBocCc7CiAgICAgICAgJGhycGF5cm9sbF9hcGkgPSBuZXcgSFJQYXlyb2xsTGljKCk7CiAgICAgICAgJGhycGF5cm9sbF9hcGktPmRlYWN0aXZhdGVfbGljZW5zZSgpOwogICAgfQp9"));