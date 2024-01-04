<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Task bookmarks
Description: Group and bookmark tasks
Version: 1.0.0
Requires at least: 2.3.*
Author: ESTACK TECHNOLOGIES
Author URI: https://estacktechnologies.com/
*/

define('TASK_BOOKMARKS_MODULE_NAME', 'task_bookmarks');

hooks()->add_action('admin_init', 'task_bookmarks_module_init_menu_items');
hooks()->add_action('app_admin_footer', 'task_bookmarks_load_js');
hooks()->add_action('app_admin_head', 'task_bookmarks_add_head_components');
hooks()->add_action('admin_init', 'task_bookmarks_permissions');

hooks()->add_filter('tasks_table_columns', 'task_bookmarks_add_table_column', 10, 2);
hooks()->add_filter('tasks_table_row_data', 'task_bookmarks_add_table_row', 10, 3);

hooks()->add_filter('list_task_bookmarks', 'list_task_bookmarks', 10, 2);
hooks()->add_filter('get_dashboard_widgets', 'task_bookmarks_add_dashboard_widget');


/**
* Register activation module hook
*/
register_activation_hook(TASK_BOOKMARKS_MODULE_NAME, 'task_bookmarks_module_activation_hook');



function task_bookmarks_add_dashboard_widget($widgets)
{
    $widgets[] = [
            'path'      => 'task_bookmarks/task_bookmarks_widget',
            'container' => 'top-12',
        ];

    return $widgets;
}
/**
 * Injects JavaScript
 * @return null
 */
function task_bookmarks_load_js(){
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('task_bookmarks', 'assets/js/task_bookmarks.js').'?v=' . $CI->app_scripts->core_version().'"></script>';

		$loaddepdendencies = $_SERVER['REQUEST_URI'];
		
		if ( strpos($loaddepdendencies,'task_bookmarks') !== false && strpos($loaddepdendencies,'view_task_') == false ) {
		?>
		<script src="<?php echo module_dir_url('menu_setup','assets/jquery-nestable/jquery.nestable.js'); ?>"></script>
		<link href="<?php echo module_dir_url('menu_setup', 'assets/font-awesome-icon-picker/css/fontawesome-iconpicker.min.css'); ?>" rel="stylesheet">
		<script src="<?php echo module_dir_url('menu_setup', 'assets/font-awesome-icon-picker/js/fontawesome-iconpicker.js'); ?>"></script>
		<?php
		}
		
		
}
/**
 * Functions
 */
function task_bookmarks_add_head_components(){
    if (get_option('task_bookmarks_enabled') == '1'){
        $CI = &get_instance();
        echo '<link href="' . module_dir_url('task_bookmarks', 'assets/css/task_bookmarks.css') .'?v=' . $CI->app_scripts->core_version(). '"  rel="stylesheet" type="text/css" />';
    }
}

function list_task_bookmarks($list_task_bookmarks_html, $task_id)
{
    $CI = &get_instance();
    $list_task_bookmarks_html .= '<div class="buttonHolder">';
    $task_bookmarks_id = [];
    $CI->db->where('task_id',$task_id);
    $task_bookmarks = $CI->db->get('tbltask_bookmarks_detail')->result_array();

    $CI->db->where('creator',get_staff_user_id());
    $list_task_bookmarks = $CI->db->get('tbltask_bookmarks')->result_array();
    if($task_bookmarks != false){
        foreach ($task_bookmarks as $value) {
            $task_bookmarks_id[] = $value['task_bookmarks_id'];   
        }
    }
  
    foreach($list_task_bookmarks as $t_bookmarks){ 

        if(in_array($t_bookmarks['id'], $task_bookmarks_id)){ 
            $list_task_bookmarks_html .= '<div class="hi-icon-wrap hi-icon-effect-8 hi-icon-effect-1a tasksinline"><a href="Javascript:void(0);" class="hi-icon fa ' . htmlspecialchars($t_bookmarks['icon']) . '" style="color: '.htmlspecialchars($t_bookmarks['color']).';" onclick="remove_task_bookmarks('.htmlspecialchars($t_bookmarks['id']).','.htmlspecialchars($task_id).')" data-toggle ="tooltip", title="Remove: '.htmlspecialchars($t_bookmarks['name']).'"><i class="fa ' . htmlspecialchars($t_bookmarks['icon']) . '"></i></a></div>';
        }else{
            $list_task_bookmarks_html .= '<div class="hi-icon-wrap hi-icon-effect-8 hi-icon-effect-1a tasksinline"><a href="Javascript:void(0);" class="hi-icon fa ' . htmlspecialchars($t_bookmarks['icon']) . '" onclick="add_task_bookmarks('.htmlspecialchars($t_bookmarks['id']).','.htmlspecialchars($task_id).')" data-toggle ="tooltip", title="Add: '.htmlspecialchars($t_bookmarks['name']).'"><i class="fa ' . htmlspecialchars($t_bookmarks['icon']) . '"></i></a></div>';
        }
    }
    $list_task_bookmarks_html .= '</div>';

    return $list_task_bookmarks_html;
}

function task_bookmarks_module_activation_hook() {
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

function task_bookmarks_add_table_column($table_data) {
    array_push($table_data, _l('task_bookmarks'));
    return $table_data;
}

function task_bookmarks_add_table_row($row ,$aRow) {
    $CI = &get_instance();
    $icon = '';
    $task_bookmarks_id = [];
    $CI->db->where('task_id',$aRow['id']);
    $task_bookmarks = $CI->db->get('tbltask_bookmarks_detail')->result_array();
    if(count($task_bookmarks) > 0){
        foreach ($task_bookmarks as $value) {
            $task_bookmarks_id[] = $value['task_bookmarks_id'];   
        }
    }
    $CI->db->where('creator',get_staff_user_id());
    $list_task_bookmarks = $CI->db->get('tbltask_bookmarks')->result_array();
    foreach($list_task_bookmarks as $t_bookmarks){ 

        if(in_array($t_bookmarks['id'], $task_bookmarks_id)){ 
            $icon .= '<div class="hi-icon-wrap hi-icon-effect-8 hi-icon-effect-1a" style="display: inline-block; margin-top: 10px;"><a href="Javascript:void(0);" class="hi-icon fa ' . htmlspecialchars($t_bookmarks['icon']) . '" style="color: '.htmlspecialchars($t_bookmarks['color']).';" onclick="remove_task_bookmarks('.htmlspecialchars($t_bookmarks['id']).','.htmlspecialchars($aRow['id']).', false)" data-toggle ="tooltip", title="Remove: '.htmlspecialchars($t_bookmarks['name']).'"><i class="fa ' . htmlspecialchars($t_bookmarks['icon']) . '"></i></a></div>';
        }else{
            $icon .= '<div class="hi-icon-wrap hi-icon-effect-8 hi-icon-effect-1a" style="display: inline-block; margin-top: 10px;"><a href="Javascript:void(0);" class="hi-icon fa ' . htmlspecialchars($t_bookmarks['icon']) . '" onclick="add_task_bookmarks('.htmlspecialchars($t_bookmarks['id']).','.htmlspecialchars($aRow['id']).', false)" data-toggle ="tooltip", title="Add: '.htmlspecialchars($t_bookmarks['name']).'"><i class="fa ' . htmlspecialchars($t_bookmarks['icon']) . '"></i></a></div>';
        }
    }
    $row[] = $icon;
    return $row;
}

function task_bookmarks_module_init_menu_items()
{
    if (has_permission('task_bookmarks', '', 'view')) {
        $CI = &get_instance();

        $CI->app_menu->add_sidebar_menu_item('task_bookmarks', [
                'name'     => _l('task_bookmarks'),
                'href'     => admin_url('task_bookmarks'),
                'position' => 31,
                'icon'     => 'fa fa-bookmark menu-icon',
        ]);
    }
}

function task_bookmarks_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view_own'),
    ];

    register_staff_capabilities('task_bookmarks', $capabilities, _l('task_bookmarks'));
	
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(TASK_BOOKMARKS_MODULE_NAME, [TASK_BOOKMARKS_MODULE_NAME]);