<?php

class Migration_Version_110 extends App_module_migration
{
    public function up()
    {

        $CI = &get_instance();

        $CI->db->query(
            'CREATE TABLE `' . db_prefix() . 'project_statuses` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `order` INT(11) NOT NULL,
                `name` TEXT NOT NULL,
                `color` TEXT NOT NULL,
                `filter_default` BOOLEAN,
                PRIMARY KEY (`id`) )
        DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
        );


        $CI->db->query(
            'CREATE TABLE `' . db_prefix() . 'project_status_can_change` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `project_status_id` INT(11) NOT NULL,
                `project_status_id_can_change_to` INT(11) NOT NULL,
                PRIMARY KEY (`id`))
        DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
        );

        $CI->db->query(
            'ALTER TABLE `' . db_prefix() . 'project_status_can_change`
        ADD CONSTRAINT `project_status_can_change_project_status_id` FOREIGN KEY (`project_status_id`) 
        REFERENCES `' . db_prefix() . 'project_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
        );

        $CI->db->query(
            'ALTER TABLE `' . db_prefix() . 'project_status_can_change`
        ADD CONSTRAINT `project_status_can_change_project_status_id_2` FOREIGN KEY (`project_status_id_can_change_to`) 
        REFERENCES `' . db_prefix() . 'project_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      '
        );


        $CI->load->model('Projects_model');
        $CI->is_task_status_manager_loading = true;
        $statuses = $CI->Projects_model->get_project_statuses();

        // Create default statuses
        foreach ($statuses as $status) {
            $CI->db->query("INSERT INTO " . db_prefix() . "project_statuses (`id`, `name`, `color` ,`order`, `filter_default`) VALUES ({$status['id']},'{$status['name']}','{$status['color']}',{$status['order']}," . intval($status['filter_default']) . ") ON DUPLICATE KEY UPDATE id={$status['id']}");
        }
        $CI->is_task_status_manager_loading = false;
    }
}
