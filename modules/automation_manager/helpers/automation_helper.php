<?php


/**
 * Check if due_date, finish_date or start_date trigger activated.
 * If activated apply actions.
 */
function apply_actions_for_dates_trigger($type, $field)
{

    $CI = &get_instance();

    $triggers = get_triggers($type, null, True);

    $tasks = $CI->db->where($field, date('Y-m-d'))->get(db_prefix() . 'tasks')->result_array();

    foreach ($tasks as $task) {
        process_triggers($triggers, $task['id']);
    }
}

/**
 * Get triggers for specified type and value.
 * @param string $type one of: status, start_date, finish_date, due_date, priority, custom_field, inactive
 * @param mixed $value additional value
 * @param bool $lastTriggeredYesterday if you want triggers that weren`t triggered today
 */
function get_triggers($type, $value = null, $lastTriggeredYesterday = false)
{
    $CI = &get_instance();

    $CI->db->select([
        db_prefix() . 'automations.id',
        db_prefix() . 'automation_triggers.id as trigger_id',
        db_prefix() . 'automation_triggers.type',
        'additional_argument',
        'automation_id',
        'value',
        'join'
    ]);

    $CI->db->where(db_prefix() . 'automations.active', true);
    $CI->db->where(db_prefix() . 'automation_triggers.type', $type);

    if ($value) {
        $CI->db->where(db_prefix() . 'automation_triggers.value',  $value);
    }

    if ($lastTriggeredYesterday) {
        $CI->db->where(db_prefix() . 'automation_triggers.last_triggered !=', date('Y-m-d'));
        $CI->db->or_where(db_prefix() . 'automation_triggers.last_triggered ', NULL);
    }

    $CI->db->join(db_prefix() . 'automations', db_prefix() . 'automations.id = ' . db_prefix() . 'automation_triggers.automation_id');

    return $CI->db->get(db_prefix() . 'automation_triggers')->result_array();
}


/**
 * Check if condition for specified trigger and specified task is met
 */
function check_trigger($type, $value, $taskID, $additional_argument = '')
{
    $CI = &get_instance();
    $CI->load->model('Tasks_model');
    $task = $CI->Tasks_model->get($taskID);

    if ($type == 'status') {
        return $task->status == $value;
    }

    if ($type == 'priority') {
        return $task->priority == $value;
    }

    if ($type == 'task_created') {
        return false;
    }

    if ($type == 'due_date_changed') {
        return false;
    }

    if ($type == 'start_date_changed') {
        return false;
    }

    if ($type == 'start_date') {
        return date('d-M-Y', strtotime($task->startdate)) == date('d-M-Y');
    }

    if ($type == 'due_date') {
        return date('d-M-Y', strtotime($task->duedate)) == date('d-M-Y');
    }

    if ($type == 'inactive') {
        $lastComment = $CI->db->where('taskid', $taskID)->order_by('dateadded', 'desc')->limit(1)->get(db_prefix() . 'task_comments')->row_array();
        $lastTimer = $CI->db->where('task_id', $taskID)->order_by('end_time', 'desc')->limit(1)->get(db_prefix() . 'taskstimers')->row_array();
        $inactiveDays = round((time() - max(strtotime($lastComment['dateadded'] ?? 0), $lastTimer['end_time'] ?? 0)) / (60 * 60 * 24));

        if (substr($value, 0, 1) == '>') {
            return $inactiveDays > trim(substr($value, 1));
        } elseif (substr($value, 0, 2) == '>=') {
            return $inactiveDays >=  trim(substr($value, 2));
        } elseif (substr($value, 0, 1) == '<') {
            return $inactiveDays < trim(substr($value, 1));
        } elseif (substr($value, 0, 2) == '<=') {
            return $inactiveDays < trim(substr($value, 2));
        } elseif (substr($value, 0, 1) == '=') {
            return $inactiveDays == trim(substr($value, 1));
        }

        return $inactiveDays == $value;
    }

    if ($type == 'custom_field') {
        $CI
            ->db
            ->where('relid', $taskID)
            ->where('fieldid', $value);

        $operator = trim(substr($additional_argument, 0, 2));

        if (str_contains($additional_argument, '{total_logged_time}')) {
            $additional_argument = str_replace('{total_logged_time}', $CI->task_model->calc_task_total_time() / (60 * 60), $additional_argument);
        }

        if (in_array($operator, ['>', '<', '>=', '<=', '='])) {
            $additional_argument  = trim(substr($additional_argument, 2));
            $CI->db->where('value ' . $operator, $additional_argument);
        } else {
            $CI->db->where('value like', $additional_argument);
        }

        return $CI->db->get(db_prefix() . 'customfieldsvalues')->num_rows() > 0;
    }
}

/**
 * SQL Like operator in PHP.
 * Returns TRUE if match else FALSE.
 * @param string $pattern
 * @param string $subject
 * @return bool
 */
function like_match($pattern, $subject)
{
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool) preg_match("/^{$pattern}$/i", $subject);
}


/**
 * Apply specified action to task
 * 
 * @param string $type One of: 
 * - change_status - requires $value with status id
 * - add_comment - requires $value with comment text
 * - change_priority - requires $value with priority id
 * - set_follower - requires $value with staff id and additional_argument with set type (add or replace all)
 * - set_assignee - requires $value with staff id and additional_argument with set type (add or replace all)
 * - add_timer - requires $value with timer value in HH:MM, $additional_argument with staff id and $additional_argument2 with timer comment
 * - add_reminder - requires $value with days (days into the future from day of event), $additional_argument with reminder text and $additional_argument_2 with reminder hour
 * - set_custom_field - required $value with custom fields id and $additional_argument with custom field content
 * - add_tag - requires $value with tag text and $additional_argument with operation type: add, remove_all_and_add, remove
 * - change_due_date - required $value with numbers of days duedate has to be changed
 */
function apply_action($type, $value, $taskID, $additional_argument = '', $additional_argument_2 = '')
{
    $CI = &get_instance();
    $CI->load->model('Tasks_model');

    if ($type == 'change_status') {
        $CI->Tasks_model->mark_as($value, $taskID);
    }

    if ($type == 'add_comment') {
        $CI->db->insert(db_prefix() . 'task_comments', [
            'taskid'     => $taskID,
            'content'    => $value,
            'staffid'    => 0,
            'contact_id' => 0,
            'dateadded'  => date('Y-m-d H:i:s'),
        ]);
    }

    if ($type == 'add_timer') {
        $CI->Tasks_model->timesheet([
            'timesheet_duration' => $value,
            'timesheet_staff_id' => $additional_argument,
            'timesheet_task_id' => $taskID,
            'note' => $additional_argument_2
        ]);
    }

    if ($type == 'change_priority') {
        $CI->db->where('id', $taskID);
        $CI->db->update(db_prefix() . 'tasks', [
            'priority' => $value
        ]);
    }

    if ($type == 'set_assignee') {

        if ($additional_argument == 'replaceAll') {
            foreach ($CI->Tasks_model->get_task_assignees($taskID) as $taskAssignee) {
                $CI->Tasks_model->remove_assignee($taskAssignee['id'], $taskID);
            }
        }

        foreach (explode(',', $value) as $staff) {
            if (!$CI->Tasks_model->is_task_assignee($staff, $taskID)) {
                $CI->Tasks_model->add_task_assignees([
                    'taskid' => $taskID,
                    'assignee' => $staff
                ], true);
            }
        }
    }

    if ($type == 'set_follower') {

        if ($additional_argument == 'replaceAll') {
            foreach ($CI->Tasks_model->get_task_followers($taskID) as $taskAssignee) {
                $CI->Tasks_model->remove_follower($taskAssignee['id'], $taskID);
            }
        }

        foreach (explode(',', $value) as $staff) {
            if (!$CI->Tasks_model->is_task_follower($staff, $taskID)) {
                $CI->Tasks_model->add_task_followers([
                    'taskid' => $taskID,
                    'follower' => $staff
                ], true);
            }
        }
    }

    if ($type == 'add_reminder') {

        $CI->load->model('Misc_model');

        $CI->Misc_model->add_reminder([
            'date' => date('d-m-Y h:i:s', strtotime(date('d-m-Y ') . $additional_argument_2 . " + $value days")),
            'description' => $additional_argument,
            'rel_type' => 'task',
            'rel_id' => $taskID,
            'staff' => $CI->db->where('id', $taskID)->select(['id', 'addedfrom'])->get(db_prefix() . 'tasks')->row()->addedfrom
        ], $taskID);
    }

    if ($type == 'set_custom_field') {
        handle_custom_fields_post($taskID, [
            [$value => $additional_argument]
        ]);
    }

    if ($type == 'add_tag') {
        $tags = get_tags_in($taskID, 'task');

        if ($additional_argument == 'add') {
            $tags += array_merge($tags, explode(',', $value));
        }

        if ($additional_argument == 'remove_all_and_add') {
            $tags = explode(',', $value);
        }

        if ($additional_argument == 'remove') {
            $tags = array_filter($tags, fn ($tag) => $tag != $value);
        }

        handle_tags_save($tags, $taskID, 'task');
    }

    if ($type == 'change_due_date') {

        $CI->db->where('id', $taskID);
        $CI->db->set('duedate', "DATE_ADD(`duedate`, INTERVAL {$value} DAY)", FALSE);
        $CI->db->update(db_prefix() . 'tasks');
    }
}



/**
 * Process array of triggers.
 * If thigger has and join, get all associated triggers and check if conditions are met
 */
function process_triggers($triggers, $taskId)
{
    $CI = &get_instance();

    foreach ($triggers as $trigger) {

        if ($trigger['join'] == 'and') {
            //Process `and` join
            $CI->db->where('automation_id', $trigger['automation_id']);
            $CI->db->where('id !=', $trigger['trigger_id']);
            $additionalTriggers = $CI->db->get(db_prefix() . 'automation_triggers')->result_array();
            $andCondition = true;

            foreach ($additionalTriggers as $additionalTrigger) {
                if (!check_trigger($additionalTrigger['type'], $additionalTrigger['value'], $taskId, $additionalTrigger['additional_argument'])) {
                    $andCondition = false;
                    break;
                }
            }

            if (!$andCondition) {
                continue;
            }
        }

        //Make action
        $CI->db->where('automation_id', $trigger['automation_id']);
        $actions = $CI->db->get(db_prefix() . 'automation_actions')->result_array();
        foreach ($actions as $action) {
            apply_action($action['type'], $action['value'], $taskId, $action['additional_argument'], $action['additional_argument_2']);
        }

        // Update last_triggered filed of this trigger 
        $CI->db->where('id', $trigger['trigger_id'])->update(db_prefix() . 'automation_triggers', ['last_triggered' => date('Y-m-d'), 'last_triggered_by' => $taskId]);
    }
}
