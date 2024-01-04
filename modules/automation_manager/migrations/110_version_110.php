<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Version_110 extends App_module_migration
{

    /**
     * Add triggers, actions and last_triggered_by, active fields
     */
    public function up()
    {
        $CI = &get_instance();

        $CI->db->query("
        ALTER TABLE 
        `" . db_prefix() . "automation_triggers` CHANGE `type` `type` 
        ENUM(
            'status',
            'start_date',
            'finish_date',
            'due_date',
            'priority',
            'custom_field',
            'inactive',
            'task_created',
            'due_date_changed',
            'start_date_changed'
            ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL");

        $CI->db->query("
        ALTER TABLE 
        `" . db_prefix() . "automation_actions` CHANGE `type` `type` 
        ENUM(
             'change_status',
             'add_comment',
             'add_timer',
             'change_priority',
             'set_follower',
             'set_assignee',
             'add_reminder',
             'set_custom_field',
             'add_tag',
             'change_due_date'
            )
        CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL");

        $CI->db->query("ALTER TABLE `" . db_prefix() . "automation_triggers` ADD `last_triggered_by` INT NULL AFTER `last_triggered`");
        $CI->db->query("ALTER TABLE `" . db_prefix() . "automations` ADD `active`  BOOLEAN NOT NULL DEFAULT TRUE AFTER `join`");
    }
}
