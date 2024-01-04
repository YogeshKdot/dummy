<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
  Module Name: Status Manager
  Description: This custom module is developed to manage task & project statuses
  Version: 1.1.0
  Requires at least: 3.0.*
*/

define('STATUS_MANAGER', 'status_manager');

/**
* Load the module helper
*/
$CI->load->helper(STATUS_MANAGER . '/status_manager');

/**
 * Register activation module hook
 */
register_activation_hook(STATUS_MANAGER, 'status_manager_module_activation_hook');
register_deactivation_hook(STATUS_MANAGER, 'status_manager_module_uninstall_hook');
register_uninstall_hook(STATUS_MANAGER, 'status_manager_module_uninstall_hook');


hooks()->add_action('before_task_description_section', 'save_status_of_current_task');
hooks()->add_filter('project_get', 'save_status_of_current_project');
hooks()->add_action('admin_init', 'status_manager_module_init_menu_items');


hooks()->add_filter('task_single_mark_as_statuses', 'filter_statuses_avalible_for_change_in_task_view');
hooks()->add_filter('tasks_table_row_data', 'filter_statuses_avalible_for_change_in_table', 10, 2);
hooks()->add_filter('tasks_related_table_row_data', 'filter_statuses_avalible_for_change_in_table', 10, 2);
hooks()->add_filter('global_search_result_query', 'filter_global_search_result_for_staff');
hooks()->add_filter('tasks_table_sql_where', 'filter_tasks_for_staff');
hooks()->add_filter('before_get_task_statuses', 'load_statuses_from_db');
hooks()->add_filter('before_get_project_statuses', 'load_project_statuses_from_db');
hooks()->add_filter('admin_area_auto_loaded_vars', 'filter_statuses_for_staff_in_global_vars');



/**
 * Create database schema.
 * Create default statuses in database if not exists
 */
function status_manager_module_activation_hook()
{
    $CI = &get_instance();
    $CI->is_task_status_manager_loading = true;

    require_once(__DIR__ . '/install.php');

    $CI->is_task_status_manager_loading = false;
}


/**
 * Drop tables
 */
function status_manager_module_uninstall_hook()
{
    $CI = &get_instance();
    $CI->db->query('SET foreign_key_checks = 0;');
    $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'task_status_dont_have_staff`');
    $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'task_status_can_change`');
    $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'task_statuses`');
    $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'project_status_can_change`');
    $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'project_statuses`');
    $CI->db->query('SET foreign_key_checks = 1;');
}

/**
 * Init status manager module menu items in setup in admin_init hook
 * @return null
 */
function status_manager_module_init_menu_items()
{
    if (is_admin()) {

        $CI = &get_instance();

        $CI->app_menu->add_setup_menu_item('status_manager', [
            'name'     => _l("status_manager"),
            'collapse' => true,
            'position' => 8,
        ]);

        $CI->app_menu->add_setup_children_item('status_manager', [
            'name'     => _l("task_statuses"),
            'slug'     => 'task_status_manager',
            'href'     => admin_url('status_manager'),
            'position' => 8,
            'badge'    => [],
        ]);

        $CI->app_menu->add_setup_children_item('status_manager', [
            'name'     => _l("project_statuses"),
            'slug'     => 'project_status_manager',
            'href'     => admin_url('status_manager/project'),
            'position' => 9,
            'badge'    => [],
        ]);
    }
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(STATUS_MANAGER, [STATUS_MANAGER]);


/**
 * Using before_get_task_statuses hook override default statuses 
 * When on tasks or project tasks view return filtered statuses to hide those statuses from summary.
 * Get statuses from task_statuses table.
 */
function load_statuses_from_db($statuses)
{
    $CI = &get_instance();
    if ($CI->is_task_status_manager_loading ?? false) {
        return $statuses;
    }

    if ($CI->uri->uri_string == 'admin/tasks') {
        $statusesUserCantSee = $CI->db->where('staff_id', get_staff_user_id())->get(db_prefix() . 'task_status_dont_have_staff')->result_array();
        $statusesIdsUserCantSee = array_map(fn ($x) => $x['task_status_id'], $statusesUserCantSee);

        if (!empty($statusesIdsUserCantSee)) {
            return $CI->db->where_not_in('id', $statusesIdsUserCantSee)->get(db_prefix() . 'task_statuses')->result_array();
        }
    }

    return  $CI->db->get(db_prefix() . 'task_statuses')->result_array();
}

/**
 * Using before_get_project_statuses hook override default statuses 
 * When on tasks or project tasks view return filtered statuses to hide those statuses from summary.
 * Get statuses from task_statuses table.
 */
function load_project_statuses_from_db($statuses)
{
    $CI = &get_instance();
    if ($CI->is_task_status_manager_loading ?? false) {
        return $statuses;
    }

    if (isset($CI->uri->segments[3], $CI->uri->segments[4]) && ($CI->uri->segments[3] == 'project' || $CI->uri->segments[3] == 'view') && is_numeric($CI->uri->segments[4])) {
        $CI->project_status_once = true;
        $statusesAvalibleToChange = $CI->db->where('project_status_id', $CI->current_project_status)->get(db_prefix() . 'project_status_can_change')->result_array();
        $statusesIdsAvalibleToChange = array_map(fn ($x) => $x['project_status_id_can_change_to'], $statusesAvalibleToChange);
        if (!empty($statusesIdsAvalibleToChange)) { // && !is_admin()
            $CI->db->where_in('id', $statusesIdsAvalibleToChange);
        }
    }

    return array_map(function ($x) {
        $x['id'] = intval($x['id']);
        return $x;
    }, $CI->db->get(db_prefix() . 'project_statuses')->result_array());
}


/**
 * Filter tasks by task_status_dont_have_staff table
 */
function filter_tasks_for_staff($where)
{
    $CI = &get_instance();
    $statusesUserCantSee = $CI->db->where('staff_id', get_staff_user_id())->get(db_prefix() . 'task_status_dont_have_staff')->result_array();
    $statusesIdsUserCantSee = array_map(fn ($x) => $x['task_status_id'], $statusesUserCantSee);
    if (!empty($statusesIdsUserCantSee)) {
        $where[] = 'AND (status NOT IN (' . implode(',', $statusesIdsUserCantSee) . '))';
    }
    return $where;
}

/**
 * Hide tasks in search results
 * Task status need to have user in task_status_dont_have_staff in order to be hidden
 */
function filter_global_search_result_for_staff($results)
{

    $CI = &get_instance();
    $statusesUserCantSee = $CI->db->where('staff_id', get_staff_user_id())->get(db_prefix() . 'task_status_dont_have_staff')->result_array();
    $statusesIdsUserCantSee = array_map(fn ($x) => $x['task_status_id'], $statusesUserCantSee);

    foreach ($results as $key => $result) {
        if ($result['type'] == 'tasks') {
            foreach ($result['result'] as $key2 => $task)
                if (in_array($task['status'], $statusesIdsUserCantSee)) {
                    unset($results[$key]['result'][$key2]);
                }
        }
    }
    return $results;
}

/**
 * Filter global task_statuses variable
 * Do not include statuses that individual users cannot access
 */
function filter_statuses_for_staff_in_global_vars($vars)
{
    $CI = &get_instance();
    $statusesUserCantSee = $CI->db->where('staff_id', get_staff_user_id())->get(db_prefix() . 'task_status_dont_have_staff')->result_array();
    $statusesIdsUserCantSee = array_map(fn ($x) => $x['task_status_id'], $statusesUserCantSee);

    if (!empty($statusesIdsUserCantSee)) {
        $vars['task_statuses'] =  $CI->db->where_not_in('id', $statusesIdsUserCantSee)->get(db_prefix() . 'task_statuses')->result_array();
    }

    return $vars;
}

/**
 * Filter status dropdown in tasks view
 * Only include statuses that can be changed from the current task status
 */
function filter_statuses_avalible_for_change_in_task_view()
{

    $CI = &get_instance();
    $statusesAvalibleToChange = $CI->db->where('task_status_id', $CI->current_task_status)->get(db_prefix() . 'task_status_can_change')->result_array();
    $statusesIdsAvalibleToChange = array_map(fn ($x) => $x['task_status_id_can_change_to'], $statusesAvalibleToChange);
    if (!empty($statusesIdsAvalibleToChange) && !is_admin()) {
        $CI->db->where_in('id', $statusesIdsAvalibleToChange);
    }
    return $CI->db->get(db_prefix() . 'task_statuses')->result_array();
}

/**
 * Replace status field in tasks table
 * Only include statuses that can be changed from the current task status
 */
function filter_statuses_avalible_for_change_in_table($taskRow, $task)
{

    $CI = &get_instance();
    $statusesAvalibleToChange = $CI->db->where('task_status_id', $task['status'])->get(db_prefix() . 'task_status_can_change')->result_array();
    $canChangeStatus = ($task['current_user_is_creator'] != '0' || $task['current_user_is_assigned'] || has_permission('tasks', '', 'edit'));
    $status          = get_task_status_by_id($task['status']);

    $statusesIdsAvalibleToChange = array_map(fn ($x) => $x['task_status_id_can_change_to'], $statusesAvalibleToChange);
    if (empty($statusesIdsAvalibleToChange) || is_admin()) {
        return $taskRow;
    }
    $taskStatuses = $CI->db->where_in('id', $statusesIdsAvalibleToChange)->get(db_prefix() . 'task_statuses')->result_array();


    // Copied from application/views/admin/tables/tasks.php -> lines 133-160
    $outputStatus    = '';

    $outputStatus .= '<span class="inline-block label" style="color:' . $status['color'] . ';border:1px solid ' . $status['color'] . '" task-status-table="' . $task['status'] . '">';

    $outputStatus .= $status['name'];

    if ($canChangeStatus) {
        $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
        $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $task['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
        $outputStatus .= '</a>';

        $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $task['id'] . '">';
        foreach ($taskStatuses as $taskChangeStatus) {
            if ($task['status'] != $taskChangeStatus['id']) {
                $outputStatus .= '<li>
                  <a href="#" onclick="task_mark_as(' . $taskChangeStatus['id'] . ',' . $task['id'] . '); return false; " style="color:' . $taskChangeStatus['color'] . ';">
                     ' . _l('task_mark_as', $taskChangeStatus['name']) . '
                  </a>
               </li>';
            }
        }
        $outputStatus .= '</ul>';
        $outputStatus .= '</div>';
    }

    $outputStatus .= '</span>';

    // Replace entire column html
    $taskRow[3] = $outputStatus;
    return $taskRow;
}

/**
 * We need to save the status of an open task
 * in order to filter the statuses that can be changed later.
 */
function save_status_of_current_task($task)
{
    $CI = &get_instance();
    $CI->current_task_status = $task->status;
}
/**
 * We need to save the status of an open project
 * in order to filter the statuses that can be changed later.
 */
function save_status_of_current_project($project)
{
    $CI = &get_instance();
    $CI->current_project_status = $project->status;
    return $project;
}
