<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Perfex Shop
Description: Ecommerce module for Perfex CRM (Sell Products & Services)
Version: 1.0
Requires at least: 2.3.*
Author: ESTACK TECHNOLOGIES
Author URI: https://estacktechnologies.com/
*/

//Module name
define('PRODUCTS_MODULE_NAME', 'products');

// Define upload folder location
define('PRODUCT_MODULE_UPLOAD_FOLDER', module_dir_path(PRODUCTS_MODULE_NAME, 'uploads/'));

// Get codeigniter instance
$CI = &get_instance();

// Register activation module hook
register_activation_hook(PRODUCTS_MODULE_NAME, 'products_module_activation_hook');
function products_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__.'/install.php';
}

// Register language files, must be registered if the module is using languages
register_language_files(PRODUCTS_MODULE_NAME, [PRODUCTS_MODULE_NAME]);

// Load module helper file
$CI->load->helper(PRODUCTS_MODULE_NAME.'/products');

// Load module Library file
 $CI->load->library(PRODUCTS_MODULE_NAME.'/'.'products_lib');

// Inject css file for products module
hooks()->add_action('app_admin_head', 'products_add_head_components');
function products_add_head_components()
{
    // Check module is enable or not (refer install.php)
    if ('1' == get_option('products_enabled')) {
        $CI = &get_instance();
        echo '<link href="'.module_dir_url('products', 'assets/css/products.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
        echo '<script src="'.module_dir_url('products', 'assets/js/products.js').'?v='.$CI->app_scripts->core_version().'"></script>';
    }
}

// Inject Javascript file for products module
hooks()->add_action('app_admin_footer', 'products_load_js');
function products_load_js()
{
    if ('1' == get_option('products_enabled')) {
        $CI = &get_instance();

        echo '<script src="'.module_dir_url('products', 'assets/highcharts/highcharts.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('products', 'assets/highcharts/variable-pie.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('products', 'assets/highcharts/export-data.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('products', 'assets/highcharts/accessibility.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('products', 'assets/highcharts/exporting.js').'?v='.$CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('products', 'assets/highcharts/highcharts-3d.js').'?v='.$CI->app_scripts->core_version().'"></script>';
    }
}

// Inject Style file for products frontendview
hooks()->add_action('app_customers_footer', 'customers_load_css');
function customers_load_css()
{
    if ('1' == get_option('products_enabled')) {
        $CI      = &get_instance();
        $viewuri = $_SERVER['REQUEST_URI'];
        if (false !== strpos($viewuri, '/products/client')) {
            echo '<link href="'.module_dir_url('products', 'assets/css/products_frontend.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
        }
    }
}

//inject permissions Feature and Capabilities for products module
hooks()->add_filter('staff_permissions', 'products_module_permissions_for_staff');
function products_module_permissions_for_staff($permissions)
{
    $viewGlobalName      = _l('permission_view').'('._l('permission_global').')';
    $allPermissionsArray = [
        'view'     => $viewGlobalName,
        'create'   => _l('permission_create'),
    ];
    $permissions['products'] = [
                'name'         => _l('products'),
                'capabilities' => $allPermissionsArray,
            ];

    return $permissions;
}

// Inject sidebar menu and links for products module
hooks()->add_action('admin_init', 'products_module_init_menu_items');
function products_module_init_menu_items()
{
    $CI = &get_instance();
    if (has_permission('products', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('products', [
            'slug'     => 'products',
            'name'     => _l('products'),
            'icon'     => 'fa fa-cart-plus',
            'href'     => '#',
            'position' => 30,
        ]);
    }

    if (has_permission('products', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('products', [
            'slug'     => 'products',
            'name'     => _l('products'),
            'href'     => admin_url('products'),
            'position' => 1,
        ]);
    }

    if (has_permission('products', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('products', [
            'slug'     => 'products_categories',
            'name'     => _l('products_categories'),
            'href'     => admin_url('products/products_categories'),
            'position' => 5,
        ]);
    }

    if (has_permission('products', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('products', [
            'slug'     => 'order_history',
            'name'     => _l('order_history'),
            'href'     => admin_url('products/order_history'),
            'position' => 5,
        ]);
    }

    if (has_permission('products', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('products', [
            'slug'     => 'add_new_order',
            'name'     => _l('add_new_order'),
            'href'     => admin_url('products/staff_order'),
            'position' => 8,
        ]);
    }

    if (has_permission('products', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'order_report',
            'name'     => _l('order_report'),
            'href'     => admin_url('products/order_report'),
            'position' => 7,
        ]);
    }

    if (has_permission('products', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'quantities_report',
            'name'     => _l('quantities_report'),
            'href'     => admin_url('products/quantities_report'),
            'position' => 8,
        ]);
    }
}

// Inject email template for products module
hooks()->add_action('after_email_templates', 'add_email_template_products');
function add_email_template_products()
{
    $CI                        = &get_instance();
    $data['hasPermissionEdit'] = has_permission('email_templates', '', 'edit');
    $data['orders']            = $CI->emails_model->get([
        'type'     => 'order',
        'language' => 'english',
    ]);
    $CI->load->view('products/mail_lists/email_templates_list', $data, false);
}

// Inject merge fields that will be used email templates for products module
register_merge_fields('products/order_merge_fields');

hooks()->add_filter('available_merge_fields', 'products_fields_merge');
function products_fields_merge($fields)
{
    foreach ($fields as $key => $value) {
        if (isset($value['other'])) {
            foreach ($value['other'] as $s_key => $s_value) {
                if (!empty($value['other'][$s_key]['available'])) {
                    array_push($value['other'][$s_key]['available'], 'order');
                }
            }
        }
        if (isset($value['client'])) {
            foreach ($value['client'] as $s_key => $s_value) {
                if (!empty($value['client'][$s_key]['available'])) {
                    array_push($value['client'][$s_key]['available'], 'order');
                }
            }
        }
        if (isset($value['invoice'])) {
            foreach ($value['invoice'] as $s_key => $s_value) {
                if (!empty($value['invoice'][$s_key]['available'])) {
                    array_push($value['invoice'][$s_key]['available'], 'order');
                }
            }
        }
        $final_fields[$key] = $value;
    }

    return $final_fields;
}

// Add Menu In Customer Side
hooks()->add_action('customers_navigation_start', 'add_product_menu');
function add_product_menu()
{
    if (0 == get_option('product_menu_disabled')) {
        echo '<li class="customers-nav-item-contracts">
            <a href="'.site_url('products/client').'">'._l('products').'</a>
        </li>';
    }
}

// Add settings menu(tab menu) In Admin Side
$CI->app_tabs->add_settings_tab('products', [
    'name'     => 'Products',
    'view'     => 'products/settings',
    'position' => 60,
]);

// Inject upload folder location for products module
hooks()->add_filter('get_upload_path_by_type', 'product_upload_folder', 10, 2);
function product_upload_folder($path, $type)
{
    if ('products' == $type) {
        return PRODUCT_MODULE_UPLOAD_FOLDER;
    }

    return $path;
}

// Change Order Status On change Invoice status

hooks()->add_action('invoice_status_changed', 'change_order_status');
function change_order_status($data)
{
    if (!class_exists('Invoices_model', false)) {
        get_instance()->load->model('invoices_model');
    }
    $CI = &get_instance();
    $CI->load->model('products/order_model');
    if (Invoices_model::STATUS_PAID == $data['status']) {
        $CI->order_model->update_quantity_on_invoice($data['invoice_id']);
    }
    $CI->order_model->update_status($data['invoice_id'], $data['status']);
}

hooks()->add_action('invoice_marked_as_cancelled', 'change_cancel_order');

function change_cancel_order($invoice_id)
{
    if (!class_exists('Invoices_model', false)) {
        get_instance()->load->model('invoices_model');
    }

    $CI = &get_instance();
    $CI->load->model('products/order_model');
    $CI->order_model->update_status($invoice_id, Invoices_model::STATUS_CANCELLED);
}
