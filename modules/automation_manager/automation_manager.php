<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Automation Manager
Description: Module for automating tasks
Version: 1.1.1
Author: Image Design
Author URI: https://image-design.pl/
Requires at least: 2.3.*
*/

define('AUTOMATION_MANAGER_MODULE_NAME', 'automation_manager');

/**
 * Register activation module hook
 */
register_activation_hook(AUTOMATION_MANAGER_MODULE_NAME, 'automation_manager_module_activation_hook');


$CI = &get_instance();
$CI->load->helper(AUTOMATION_MANAGER_MODULE_NAME . '/automation');

hooks()->add_action('admin_init', 'automation_manager_module_init_menu_items');
hooks()->add_action('before_update_task', 'apply_actions_for_custom_field_trigger', 10,  2);
hooks()->add_action('before_update_task', 'apply_actions_for_dates_changed_trigger', 10, 2);
hooks()->add_action('after_add_task', 'apply_actions_for_task_created_trigger');

hooks()->add_filter('task_status_changed', 'apply_actions_for_status_trigger', 10, 1);
hooks()->add_filter('before_update_task', 'apply_actions_for_priority_trigger', 10, 2);

register_cron_task('apply_actions_for_all_dates_trigger');
register_cron_task('apply_actions_for_inactive_trigger');

/**
 * Register language files, translations in language folder
 */
register_language_files(AUTOMATION_MANAGER_MODULE_NAME, [AUTOMATION_MANAGER_MODULE_NAME]);


/**
 * Create database schema.
 */
function automation_manager_module_activation_hook()
{
    $CI = &get_instance();

    require_once(__DIR__ . '/install.php');
}

/**
 * Init automations module menu items in setup in admin_init hook
 * @return null
 */
function automation_manager_module_init_menu_items()
{
    $CI = &get_instance();

    if (is_admin()) {
        $CI->app_menu->add_sidebar_children_item('utilities', [
            'slug'     => 'automation_manager',
            'name'     => _l('automation'),
            'href'     => admin_url('automation_manager'),
            'position' => 26,
        ]);
    }
}


/**
 * Called from cron, check start_date, due_date, finish_date triggers
 * Check if hour is 0, to optimize aplication (if trigger condition is met action needs to be called only once)
 */
function apply_actions_for_all_dates_trigger()
{
    if (date('G') != 0) {
        return;
    }

    apply_actions_for_dates_trigger('start_date', 'startdate');
    apply_actions_for_dates_trigger('due_date', 'duedate');
    apply_actions_for_dates_trigger('finish_date', 'datefinished');
}

/**
 * Apply actions after task is created
 */
function apply_actions_for_task_created_trigger($taskID)
{
    $triggers = get_triggers('task_created');
    process_triggers($triggers, $taskID);
}

/**
 * Called from cron, check start_date, due_date, finish_date triggers
 * For each trigger get tasks where days from last comment and timer are equal to trigger value 
 */
function apply_actions_for_inactive_trigger()
{
    if (date('G') != 0) {
        return;
    }

    $CI = &get_instance();

    $triggers = get_triggers('inactive', null, True);

    foreach ($triggers as $trigger) {
        $tasks = [];
        $mathSign = get_math_sign($trigger['value']);
        $tasksComments = $CI->db->select(['taskid', "DATEDIFF(NOW(),dateadded) as inactive"])->having('inactive ' .  $mathSign[0], $mathSign[1])->get(db_prefix() . 'task_comments')->result_array();
        $tasksTimers = $CI->db->select(['task_id', "DATEDIFF(NOW(),FROM_UNIXTIME(end_time)) as inactive"])->having('inactive ' .  $mathSign[0], $mathSign[1])->get(db_prefix() . 'taskstimers')->result_array();

        foreach ($tasksComments as $task) {
            $tasks[$task['taskid']] = $task['inactive'];
        }

        foreach ($tasksTimers as $task) {
            if (!isset($tasks[$task['taskid']]) || $tasks[$task['taskid']] > $task['inactive']) {
                $tasks[$task['taskid']] = $task['inactive'];
            }
        }

        foreach (array_keys($tasks) as $taskId) {
            process_triggers([$trigger], $taskId);
        }
    }
}

function get_math_sign($value)
{
    if (substr($value, 0, 1) == '>') {
        return ['>', trim(substr($value, 1))];
    } elseif (substr($value, 0, 2) == '>=') {
        return ['>=',  trim(substr($value, 2))];
    } elseif (substr($value, 0, 1) == '<') {
        return ['< ', trim(substr($value, 1))];
    } elseif (substr($value, 0, 2) == '<=') {
        return ['< ', trim(substr($value, 2))];
    } elseif (substr($value, 0, 1) == '=') {
        return ['=', trim(substr($value, 1))];
    }
    return ['=', $value];
}


/**
 * If priority key is set in data array apply actions
 */
function apply_actions_for_priority_trigger($data, $taskId)
{
    if (!isset($data['priority'])) {
        return $data;
    }

    $triggers = get_triggers('priority', $data['priority']);

    process_triggers($triggers, $taskId);

    return $data;
}

/**
 * Get triggers for this status and apply actions
 */
function apply_actions_for_status_trigger($data)
{
    $triggers = get_triggers('status', $data['status']);
    process_triggers($triggers, $data['task_id']);
}


/**
 * While saving task, check if custom field match any trigger
 */
function apply_actions_for_custom_field_trigger($data, $taskID)
{

    $CI = &get_instance();
    $CI->load->model('Tasks_model');


    $customFields = $CI->input->post('custom_fields', false);

    foreach ($customFields['tasks'] ?? [] as $customFieldId => $customFIeldValue) {
        // If custom field was not changed don`t apply actions
        $previousCustomField = $CI->db->where('fieldto', 'tasks')->where('fieldid', $customFieldId)->where('relid', $taskID)->get(db_prefix() . 'customfieldsvalues')->row();


        if ($previousCustomField->value == $customFIeldValue) {
            continue;
        }

        $CI->db->select([db_prefix() . 'automations.id', db_prefix() . 'automation_triggers.id as trigger_id', 'automation_id', db_prefix() . 'automation_triggers.type', 'value', 'additional_argument', 'join']);
        $CI->db->where(db_prefix() . 'automation_triggers.type', 'custom_field');
        $CI->db->where(db_prefix() . 'automation_triggers.value', $customFieldId);
        $CI->db->where(db_prefix() . 'automations.active', true);

        // $CI->db->where(db_prefix() . 'automation_triggers.additional_argument like', $customFIeldValue);

        $CI->db->join(db_prefix() . 'automations', db_prefix() . 'automations.id = ' . db_prefix() . 'automation_triggers.automation_id');
        $triggers = $CI->db->get(db_prefix() . 'automation_triggers')->result_array();

        foreach ($triggers as $triggerKey => &$trigger) {

            if (str_contains($trigger['additional_argument'], '{total_logged_time}')) {
                $trigger['additional_argument'] = str_replace('{total_logged_time}', $CI->task_model->calc_task_total_time() / (60 * 60), $trigger['additional_argument']);
            }

            $operator = trim(substr($trigger['additional_argument'], 0, 2));

            if (in_array($operator, ['>', '<', '>=', '<=', '='])) {
                $trigger['additional_argument'] = trim(substr($trigger['additional_argument'], 2));
            }

            if (
                ($operator == '>' && !($customFIeldValue > $trigger['additional_argument'])) ||
                ($operator == '<' && !($customFIeldValue < $trigger['additional_argument'])) ||
                ($operator == '>=' && !($customFIeldValue >= $trigger['additional_argument'])) ||
                ($operator == '<=' && !($customFIeldValue <= $trigger['additional_argument'])) ||
                ($operator == '=' && !($customFIeldValue = $trigger['additional_argument'])) ||
                (!in_array($operator, ['>', '<', '>=', '<=', '=']) && !like_match($trigger['additional_argument'], $customFIeldValue))

            ) {
                unset($triggers[$triggerKey]);
            }
        }

        process_triggers($triggers, $taskID);
    }
    return $data;
}


function apply_actions_for_dates_changed_trigger($data, $taskID)
{
    $CI = &get_instance();

    if (!isset($data['startdate']) && !isset($data['duedate'])) {
        return $data;
    }

    $CI->load->model('Tasks_model');
    $task = $CI->tasks_model->get($taskID);


    if (isset($data['duedate'])) {
        if ($task->duedate != $data['duedate']) {
            $triggers = get_triggers('due_date_changed');
            process_triggers($triggers,  $taskID);
        }
    }

    if (isset($data['startdate'])) {
        if ($task->duedate != $data['startdate']) {
            $triggers = get_triggers('start_date_changed');
            process_triggers($triggers, $taskID);
        }
    }

    return $data;
}
