<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Automation_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get automations all or by id
     * Get automations with triggers and actions
     * @param  int $id automation id
     * @param  boolean $withTriggers  return with triggers data
     * @param  boolean $withActions  return with actions data
     * @return object|array Automation/s
     */
    public function get($id = 0, $withTriggers = true, $withActions = true)
    {
        if ($id) {
            $this->db->where('id', $id);
        }

        $automations = $this->db->get(db_prefix() . 'automations')->result_array();

        if (!$automations) {
            return $id ? false : [];
        }

        if ($withTriggers || $withActions) {
            foreach ($automations as &$automation) {
                if ($withTriggers) {
                    $automation['triggers'] = $this->getTriggers($automation['id']);
                }
                if ($withActions) {
                    $automation['actions'] = $this->getActions($automation['id']);
                }
            }
        }

        return $id ? $automations[0] : $automations;
    }

    /**
     * Get automation triggers by automation id
     * @param  int $id automation id
     * @return array Triggers array containing: id, type, value, additional_parametr
     */
    public function getTriggers($automationId)
    {
        $this->db->where('automation_id', $automationId);
        return $this->db->get(db_prefix() . 'automation_triggers')->result_array();
    }

    /**
     * Get automation actions by automation id
     * @param  int $id automation id
     * @return array Actions array containing: id, type, value, additional_parametr
     */
    public function getActions($automationId)
    {
        $this->db->where('automation_id', $automationId);
        return $this->db->get(db_prefix() . 'automation_actions')->result_array();
    }

    /**
     * Add new automation
     * @param array $data automation $_POST data
     * @return mixed
     */
    public function store($data)
    {
        $this->db->insert(db_prefix() . 'automations', [
            'name' => $data['name'],
            'type' => 'task',
            'join' => $data['join'] ?? 'and'
        ]);

        $automationId = $this->db->insert_id();

        if (!$automationId) {
            return false;
        }

        if (isset($data['triggers'])) {
            $this->storeTriggers($automationId, $data['triggers']);
        }

        if (isset($data['actions'])) {
            $this->storeActions($automationId, $data['actions']);
        }

        log_activity('New Automation added [ID: ' . $automationId . ', Name: ' . $data['name'] . ']');

        return $automationId;
    }

    /**
     * Add triggers to automation
     * @param  int $statusId automation id
     * @param  array $staffIds array of triggers containing: type, value, additional_argument (optional)
     * @return boolean
     */
    public function storeTriggers($automationId, $triggers)
    {
        return $this->db->insert_batch(db_prefix() . 'automation_triggers', array_filter(array_map(fn ($trigger) => $trigger['type'] ? [
            'automation_id' => $automationId,
            'type' => $trigger['type'],
            'value' => $this->parseAdditionalInfo($trigger['value'] ?? NULL),
            'additional_argument' =>  $this->parseAdditionalInfo($trigger['additional_argument'] ?? NULL),
        ] : false, $triggers)));
    }

    /**
     * Prepare data to store in db
     * If array, implode with coma
     */
    private function parseAdditionalInfo($info = null): ?string
    {
        if (!$info) {
            return NULL;
        }

        if (is_array($info)) {
            return implode(',', $info);
        }

        return $info;
    }

    /**
     * Add actions to automation
     * @param  int $statusId automation id
     * @param  array $staffIds array of actions containing: type, value, additional_argument (optional)
     * @return boolean
     */
    public function storeActions($automationId, $actions)
    {
        return $this->db->insert_batch(db_prefix() . 'automation_actions', array_filter(array_map(fn ($action) =>  $action['type'] ? [
            'automation_id' => $automationId,
            'type' => $action['type'],
            'value' => $this->parseAdditionalInfo($action['value'] ?? NULL),
            'additional_argument' => $this->parseAdditionalInfo($action['additional_argument'] ?? NULL),
            'additional_argument_2' => $this->parseAdditionalInfo($action['additional_argument_2'] ?? NULL),
        ] : false, $actions)));
    }

    /**
     * Update automation
     * @param  int $automationId automation id
     * @param  array $data       automation $_POST data
     * @return int automationId 
     */
    public function update($automationId, $data)
    {
        $this->db->where('id', $automationId);
        $this->db->update(db_prefix() . 'automations', [
            'name' => $data['name'],
            'type' => 'task',
            'join' => $data['join'] ?? 'and'
        ]);

        $this->db->where('automation_id', $automationId);
        $this->db->delete(db_prefix() . 'automation_triggers');

        if (isset($data['triggers'])) {
            $this->storeTriggers($automationId, $data['triggers']);
        }

        $this->db->where('automation_id', $automationId);
        $this->db->delete(db_prefix() . 'automation_actions');

        if (isset($data['actions'])) {
            $this->storeActions($automationId, $data['actions']);
        }

        log_activity('Task Status Updated [ID: ' . $automationId . ', Name: ' . $data['name'] . ']');

        return $automationId;
    }

    /**
     * Delete automations with all relations
     * @param  mixed $automationId automation id
     * @return boolean
     */
    public function delete($automationId)
    {
        $this->db->where('id', $automationId);
        $this->db->delete(db_prefix() . 'automations');

        $this->db->where('automation_id', $automationId);
        $this->db->delete(db_prefix() . 'automation_triggers');

        $this->db->where('automation_id', $automationId);
        $this->db->delete(db_prefix() . 'automation_actions');

        return true;
    }

    /**
     * Activate or deactivate automation. 
     * By default activate, if you want to deactivate pass second argument
     */
    public function activate($automationId, $active = true)
    {
        $this->db->where('id', $automationId);
        $this->db->update(db_prefix() . 'automations', [
            'active' => $active,
        ]);

        return true;
    }
}
