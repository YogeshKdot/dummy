<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Zillapage
Description: Default module for create Landingpages and get leads
Version: 1.0.3
Requires at least: 2.3.*
*/
require(__DIR__ . '/vendor/autoload.php');

define('ZILLAPAGE_MODULE_NAME', 'zillapage');

define('ZILLAPAGE_IMAGE_PATH', 'modules/zillapage/assets/images');
define('ZILLAPAGE_ASSETS_PATH', 'modules/zillapage/assets');

hooks()->add_action('app_admin_head', 'zillapage_add_head_component');
hooks()->add_action('admin_init', 'zillapage_module_init_menu_items');
hooks()->add_action('admin_init', 'zillapage_permissions');

define('VERSION_ZILLAPAGE', 103);



function zillapage_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('landingpages', $capabilities, _l('landingpages'));


    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('landingpages-leads', $capabilities, _l('landingpages-leads'));

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('landingpages-blocks', $capabilities, _l('landingpages-blocks'));

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('landingpages-templates', $capabilities, _l('landingpages-templates'));

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('landingpages-settings', $capabilities, _l('landingpages-settings'));

    
}


/**
* Register activation module hook
*/
register_activation_hook(ZILLAPAGE_MODULE_NAME, 'zillapage_module_activation_hook');

function zillapage_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(ZILLAPAGE_MODULE_NAME, [ZILLAPAGE_MODULE_NAME]);

/**
 * Init zillapage module menu items in setup in admin_init hook
 * @return null
 */
function zillapage_module_init_menu_items()
{ 
    $CI = &get_instance();

    if (has_permission('landingpages', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('landingpages-menu', [
                'slug'     => 'landingpages-menu',
                'name'     => _l('landing_pages'),
                'href'     => '#',
                'icon'     => 'fa fa-paper-plane', 
                'position' => 40,
        ]);

        $CI->app_menu->add_sidebar_children_item('landingpages-menu', [
                'slug'     => 'landingpages',
                'name'     => _l('landing_pages'),
                'href'     => admin_url('zillapage/landingpages/index'),
                'icon'     => 'fa fa-paper-plane', 
                'position' => 40,
        ]);

        $CI->app_menu->add_sidebar_children_item('landingpages-menu', [
                'slug'     => 'landingpages-templates',
                'name'     => _l('templates'),
                'icon'     => 'fa fa-list-alt',
                'href'     => admin_url('zillapage/landingpages/templates'),
                'position' => 45,
        ]);
    }

    if (has_permission('landingpages-leads', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('landingpages-menu', [
                'slug'     => 'landingpages-leads',
                'name'     => _l('form_leads'),
                'icon'     => 'fa fa-users',
                'href'     => admin_url('zillapage/leads'),
                'position' => 45,
        ]);
    }

    if (has_permission('landingpages-blocks', '', 'view')) {

        $CI->app_menu->add_sidebar_children_item('landingpages-menu', [
                'slug'     => 'landingpages-blocks',
                'name'     => _l('admin_blocks'),
                'icon'     => 'fa fa-bars',
                'href'     => admin_url('zillapage/blocks/index'),
                'position' => 45,
        ]);
    }

    if (has_permission('landingpages-templates', '', 'view')) {
        
        $CI->app_menu->add_sidebar_children_item('landingpages-menu', [
                'slug'     => 'landingpages-templates',
                'name'     => _l('admin_templates'),
                'icon'     => 'fa fa-file-text-o',
                'href'     => admin_url('zillapage/templates/index'),
                'position' => 45,
        ]);
    }

    if (has_permission('landingpages-settings', '', 'view')) {
        
        $CI->app_menu->add_sidebar_children_item('landingpages-menu', [
                'slug'     => 'landingpages-setting',
                'name'     => _l('setting'),
                'icon'     => 'fa fa-cog',
                'href'     => admin_url('zillapage/settings'),
                'position' => 45,
        ]);
    }


}

/**
* init add head component
*/
function zillapage_add_head_component(){

    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    
    if(!(strpos($viewuri,'zillapage/landingpages') === false)){
        echo '<link href="' . module_dir_url(ZILLAPAGE_MODULE_NAME, 'assets/templates/css/template.css') . '?v=' . VERSION_ZILLAPAGE. '"  rel="stylesheet" type="text/css" />';
    }

}

/**
 * Replace media URL
 *
 * @param  int $template
 *
 * @return array
 */

if ( ! function_exists('replaceVarContentStyle'))
{
    function replaceVarContentStyle($item=""){

        $results = array();
        $image_url = base_url(ZILLAPAGE_IMAGE_PATH.'/content_media')."/";
        $temp = $item;

        if (is_object($item)) {
            if (isset($item->content)) {
                $temp->content = str_replace('##image_url##', $image_url, $item->content);
            }
            if (isset($item->style)) {
                $temp->style = str_replace('##image_url##', $image_url, $item->style);
            }
            if (isset($item->thank_you_page)) {
                $temp->thank_you_page = str_replace('##image_url##', $image_url, $item->thank_you_page);
            }
        }
        else{
            if (isset($item)) {
                $temp = str_replace('##image_url##', $image_url, $item);
            }
        }
        return $temp;
    }
}


if ( ! function_exists('guidV4'))
{
    /**
     * guidV4 ()
     * -------------------------------------------------------------------
     *
     * @return string
     */
    function guidV4()
    {
        // Microsoft guid {xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx}
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        $data = openssl_random_pseudo_bytes(16);

        // set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

        // set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
} 



if (!function_exists('getAllImagesContentMedia')) {

    function getAllImagesContentMedia(){
        
        $dir = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/content_media/";
    
        $url_content_media = base_url(ZILLAPAGE_IMAGE_PATH.'/content_media')."/";

        $accept = array('jpg', 'svg', 'jpeg', 'png','gif');

        $files = array();    
        foreach (scandir($dir) as $file) {
            $ext = pathinfo($dir . '/' . $file, PATHINFO_EXTENSION);
            if(in_array($ext, $accept)){
                $files[$url_content_media.'/'.$file] = filemtime($dir . '/' . $file);
            }
            
        }

        arsort($files);

        $files = array_keys($files);
        

        return ($files) ? $files : false;

    }
}


if (!function_exists('handle_delete_file_zillapage')) {

    function handle_delete_file_zillapage($file_path = '')
    {
        // delete file 
        if ($file_path) {
            if (file_exists($file_path)) {
                unlink($file_path);
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('handle_thumb_template_upload')) {

    function handle_thumb_template_upload($file_old = '')
    {
        
        if (isset($_FILES['thumb']['name']) && $_FILES['thumb']['name'] != '') {
            // Get the temp file path
            $tmpFilePath = $_FILES['thumb']['tmp_name'];
            // Make sure we have a filepath
            $path  = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/thumb_templates/";

            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $path_parts = pathinfo($_FILES['thumb']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                // Setup our new file path
                
                $filename    = time(). '.' . $extension;
                $newFilePath = $path . $filename;

                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                    // delete file old
                    if ($file_old) {
                        $file_path = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/thumb_templates";
                        $path_old = $path.$file_old;
                        if (file_exists($path_old)) {
                            unlink($path_old);
                        }
                    }

                    return $filename;
                }
            }
        }

        return false;
    }
}

if (!function_exists('handle_thumb_block_upload')) {

    function handle_thumb_block_upload($file_old = '')
    {
        
        if (isset($_FILES['thumb']['name']) && $_FILES['thumb']['name'] != '') {
            // Get the temp file path
            $tmpFilePath = $_FILES['thumb']['tmp_name'];
            // Make sure we have a filepath
            $path  = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/thumb_blocks/";

            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $path_parts = pathinfo($_FILES['thumb']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                // Setup our new file path
                
                $filename    = time(). '.' . $extension;
                $newFilePath = $path . $filename;

                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                    // delete file old
                    if ($file_old) {
                        $file_path = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/thumb_blocks";
                        $path_old = $path.$file_old;
                        if (file_exists($path_old)) {
                            unlink($path_old);
                        }
                    }

                    return $filename;
                }
            }
        }

        return false;
    }
}

if (!function_exists('handle_favicon_landingpage_upload')) {

    function handle_favicon_landingpage_upload($file_old = '')
    {
        
        if (isset($_FILES['favicon']['name']) && $_FILES['favicon']['name'] != '') {
            // Get the temp file path
            $tmpFilePath = $_FILES['favicon']['tmp_name'];
            // Make sure we have a filepath
            $path  = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/uploads/";

            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $path_parts = pathinfo($_FILES['favicon']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                // Setup our new file path
                
                $filename    = 'favicon-' .time(). '.' . $extension;
                $newFilePath = $path . $filename;

                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                    // delete file old
                    if ($file_old) {
                        $file_path = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/content_media";
                        $path_old = $path.$file_old;
                        if (file_exists($path_old)) {
                            unlink($path_old);
                        }
                    }

                    return $filename;
                }
            }
        }

        return false;
    }
}

if (!function_exists('handle_social_image_landingpage_upload')) {

    function handle_social_image_landingpage_upload($file_old = '')
    {
        
        if (isset($_FILES['social_image']['name']) && $_FILES['social_image']['name'] != '') {
            // Get the temp file path
            $tmpFilePath = $_FILES['social_image']['tmp_name'];
            // Make sure we have a filepath
            $path  = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/uploads/";

            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $path_parts = pathinfo($_FILES['social_image']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                // Setup our new file path
                
                $filename    = 'socialimage-' .time(). '.' . $extension;
                $newFilePath = $path . $filename;

                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                    // delete file old
                    if ($file_old) {
                        $file_path = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/content_media";
                        $path_old = $path.$file_old;
                        if (file_exists($path_old)) {
                            unlink($path_old);
                        }
                    }
                    
                    return $filename;
                }
            }
        }

        return false;
    }
}

if(!function_exists("getDeviceTracking")){

    function getDeviceTracking($tracking){
        
        if($tracking->isMobile()){

            return "Mobile";
        }
        elseif($tracking->isTablet()){

            return "Tablet";
        }
        elseif($tracking->isDesktop()){
            
            return "Desktop";
        }
        else{
            return "Unknown";
        }
    }
}

if(!function_exists("getFieldFormData")){

    function getFieldFormData($stdClass, $field){
        if(isset($stdClass->$field)){
            return $stdClass->$field;
        }
        return "";
    }
}
