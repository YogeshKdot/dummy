<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_status_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get project statuses all or by id
     * @param  mixed $id task status id
     * @param  boolean $withAvalibleStatusesForChange  return with ids of statuses avalible to change
     * @return object|array Tasks status/es
     */
    public function get($id = '', $withAvalibleStatusesForChange = true)
    {
        if ($id) {
            $this->db->where('id', $id);
        }

        $statuses = $this->db->get(db_prefix() . 'project_statuses')->result_array();

        if (!$statuses) {
            return false;
        }


        if ($withAvalibleStatusesForChange) {
            foreach ($statuses as &$status) {
                $status['avalibleStatusesForChange'] = $this->getAvalibleStatusesForChange($status['id']);
            }
        }

        if ($id) {
            $statuses = $statuses[0];
        }

        return $statuses;
    }


    /**
     * Get ids of statuses avalible to change
     * @param  mixed $id project status id
     * @return array Statuses which can be changed to when project has this status. Array of project statuses ids 
     */
    public function getAvalibleStatusesForChange($statusId)
    {
        $this->db->where('project_status_id', $statusId);
        $statusesToChange = $this->db->get(db_prefix() . 'project_status_can_change')->result_array();
        return array_map(fn ($staff) => $staff['project_status_id_can_change_to'], $statusesToChange);
    }

    /**
     * Update project status
     * @param  array $data     project status $_POST data
     * @param  mixed $statusId project status id
     * @return boolean
     */
    public function update($data, $statusId)
    {

        $this->db->where('id', $statusId);
        $this->db->update(db_prefix() . 'project_statuses', [
            'name'         => $data['name'],
            'order'         => $data['order'],
            'color'         => $data['color'],
            'filter_default'         =>  isset($data['filter_default']),
        ]);


        $this->db->where('project_status_id', $statusId);
        $this->db->delete(db_prefix() . 'project_status_can_change');
        if (isset($data['avalibleStatusesForChange'])) {
            $this->storeAvalibleStatusesForChange($statusId, $data['avalibleStatusesForChange']);
        }

        log_activity('Project Status Updated [ID: ' . $statusId . ', Name: ' . $data['name'] . ']');

        return true;
    }

    /**
     * Add new project status
     * @param array $data project status $_POST data
     * @return mixed
     */
    public function store($data)
    {
        $this->db->insert(db_prefix() . 'project_statuses', [
            'name'         => $data['name'],
            'order'         => $data['order'],
            'color'         => $data['color'],
            'filter_default'         =>  isset($data['filter_default']),
        ]);
        $statusId = $this->db->insert_id();

        if (!$statusId) {
            return false;
        }


        if (isset($data['avalibleStatusesForChange'])) {
            $this->storeAvalibleStatusesForChange($statusId, $data['avalibleStatusesForChange']);
        }


        log_activity('New Project status added [ID: ' . $statusId . ', Name: ' . $data['name'] . ']');

        return $statusId;
    }



    /**
     * Add avalible statuses for change to project status
     * @param  int $statusId project status id
     * @param  array $statusesIds array of project statuses ids
     * @return boolean
     */
    public function storeAvalibleStatusesForChange($statusId, $statusesIds)
    {
        return $this->db->insert_batch(db_prefix() . 'project_status_can_change', array_map(fn ($statusId2) => [
            'project_status_id' => $statusId,
            'project_status_id_can_change_to' => $statusId2
        ], $statusesIds));
    }

    /**
     * Delete statuses and all connections
     * @param  mixed $statusId project status id
     * @return boolean
     */
    public function delete($statusId)
    {
        $this->db->where('id', $statusId);
        $this->db->delete(db_prefix() . 'project_statuses');


        $this->db->where('project_status_id', $statusId);
        $this->db->delete(db_prefix() . 'project_status_can_change');

        $this->db->where('project_status_id_can_change_to', $statusId);
        $this->db->delete(db_prefix() . 'project_status_can_change');

        return true;
    }
}
