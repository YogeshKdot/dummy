<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Task_status_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get taks statuses all or by id
     * @param  mixed $id task status id
     * @param  boolean $withNotAssignedStaff  return with ids of not assigned staff
     * @param  boolean $withAvalibleStatusesForChange  return with ids of statuses avalible to change
     * @return object|array Tasks status/es
     */
    public function get($id = '', $withNotAssignedStaff = true, $withAvalibleStatusesForChange = true)
    {
        if ($id) {
            $this->db->where('id', $id);
        }

        $statuses = $this->db->get(db_prefix() . 'task_statuses')->result_array();

        if (!$statuses) {
            return false;
        }


        if ($withNotAssignedStaff || $withAvalibleStatusesForChange) {
            foreach ($statuses as &$status) {
                if ($withNotAssignedStaff) {
                    $status['notAssignedStaff'] = $this->getNotAssignedStaff($status['id']);
                }
                if ($withAvalibleStatusesForChange) {
                    $status['avalibleStatusesForChange'] = $this->getAvalibleStatusesForChange($status['id']);
                }
            }
        }

        if ($id) {
            $statuses = $statuses[0];
        }

        return $statuses;
    }

    /**
     * Get ids of not assigned staff
     * @param  mixed $id task status id
     * @return array Staff who can`t see this status. Array of staff ids 
     */
    public function getNotAssignedStaff($statusId)
    {
        $this->db->where('task_status_id', $statusId);
        $notAssignetStaff = $this->db->get(db_prefix() . 'task_status_dont_have_staff')->result_array();
        return array_map(fn ($staff) => $staff['staff_id'], $notAssignetStaff);
    }

    /**
     * Get ids of statuses avalible to change
     * @param  mixed $id task status id
     * @return array Statuses which can be changed to when task has this status. Array of task statuses ids 
     */
    public function getAvalibleStatusesForChange($statusId)
    {
        $this->db->where('task_status_id', $statusId);
        $statusesToChange = $this->db->get(db_prefix() . 'task_status_can_change')->result_array();
        return array_map(fn ($staff) => $staff['task_status_id_can_change_to'], $statusesToChange);
    }

    /**
     * Update task status
     * @param  array $data     task status $_POST data
     * @param  mixed $statusId task status id
     * @return boolean
     */
    public function update($data, $statusId)
    {

        $this->db->where('id', $statusId);
        $this->db->update(db_prefix() . 'task_statuses', [
            'name'         => $data['name'],
            'order'         => $data['order'],
            'color'         => $data['color'],
            'filter_default'         =>  isset($data['filter_default']),
        ]);

        $this->db->where('task_status_id', $statusId);
        $this->db->delete(db_prefix() . 'task_status_dont_have_staff');
        if (isset($data['notAssignedStaffIds'])) {
            $this->storeNotAssignedStaffIds($statusId, $data['notAssignedStaffIds']);
        }

        $this->db->where('task_status_id', $statusId);
        $this->db->delete(db_prefix() . 'task_status_can_change');
        if (isset($data['avalibleStatusesForChange'])) {
            $this->storeAvalibleStatusesForChange($statusId, $data['avalibleStatusesForChange']);
        }

        log_activity('Task Status Updated [ID: ' . $statusId . ', Name: ' . $data['name'] . ']');

        return true;
    }

    /**
     * Add new task status
     * @param array $data task status $_POST data
     * @return mixed
     */
    public function store($data)
    {
        $this->db->insert(db_prefix() . 'task_statuses', [
            'name'         => $data['name'],
            'order'         => $data['order'],
            'color'         => $data['color'],
            'filter_default'         =>  isset($data['filter_default']),
        ]);
        $statusId = $this->db->insert_id();

        if (!$statusId) {
            return false;
        }

        if (isset($data['notAssignedStaffIds'])) {
            $this->storeNotAssignedStaffIds($statusId, $data['notAssignedStaffIds']);
        }

        if (isset($data['avalibleStatusesForChange'])) {
            $this->storeAvalibleStatusesForChange($statusId, $data['avalibleStatusesForChange']);
        }


        log_activity('New Task status added [ID: ' . $statusId . ', Name: ' . $data['name'] . ']');

        return $statusId;
    }


    /**
     * Add not assigned staff to task status
     * @param  int $statusId task status id
     * @param  array $staffIds array of staff ids
     * @return boolean
     */
    public function storeNotAssignedStaffIds($statusId, $staffIds)
    {
        return $this->db->insert_batch(db_prefix() . 'task_status_dont_have_staff', array_map(fn ($staffId) => [
            'task_status_id' => $statusId,
            'staff_id' => $staffId
        ], $staffIds));
    }

    /**
     * Add avalible statuses for change to task status
     * @param  int $statusId task status id
     * @param  array $statusesIds array of task statuses ids
     * @return boolean
     */
    public function storeAvalibleStatusesForChange($statusId, $statusesIds)
    {
        return $this->db->insert_batch(db_prefix() . 'task_status_can_change', array_map(fn ($statusId2) => [
            'task_status_id' => $statusId,
            'task_status_id_can_change_to' => $statusId2
        ], $statusesIds));
    }

    /**
     * Delete statuses and all connections
     * @param  mixed $statusId task status id
     * @return boolean
     */
    public function delete($statusId)
    {
        $this->db->where('id', $statusId);
        $this->db->delete(db_prefix() . 'task_statuses');


        $this->db->where('task_status_id', $statusId);
        $this->db->delete(db_prefix() . 'task_status_dont_have_staff');

        $this->db->where('task_status_id', $statusId);
        $this->db->delete(db_prefix() . 'task_status_can_change');

        $this->db->where('task_status_id_can_change_to', $statusId);
        $this->db->delete(db_prefix() . 'task_status_can_change');


        return true;
    }
}
