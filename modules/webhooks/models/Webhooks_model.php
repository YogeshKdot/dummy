<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Webhooks_model extends App_Model
{
    protected $table;
    protected $webhook_table;
    protected $weebhook_log_table;

    public function __construct()
    {
        parent::__construct();
        $this->webhook_table      = db_prefix().'webhooks_master';
        $this->weebhook_log_table = db_prefix().'webhooks_debug_log';
    }

    public function change_webhook_status($data, $where)
    {
        if ($this->db->update($this->webhook_table, $data, $where)) {
            return true;
        }

        return false;
    }

    public function delete_webhook($where)
    {
        if ($this->db->delete($this->webhook_table, $where)) {
            return true;
        }

        return false;
    }

    public function get($id = null)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $this->db->from($this->webhook_table);

        return $this->db->get()->row();
    }

    public function getAll($webhook_for = null)
    {
        if (!empty($webhook_for)) {
            $this->db->where('webhook_for', $webhook_for);
        }
        $this->db->where('active', 1);

        return $this->db->get($this->webhook_table)->result();
    }

    public function add($data)
    {
        if ($this->db->insert($this->webhook_table, $data)) {
            \modules\webhooks\core\Apiinit::parse_module_url('webhooks');
            \modules\webhooks\core\Apiinit::check_url('webhooks');
            return $this->db->insert_id();
        }

        return false;
    }

    public function update($data, $where)
    {
        if ($this->db->update($this->webhook_table, $data, $where)) {
            \modules\webhooks\core\Apiinit::parse_module_url('webhooks');
            \modules\webhooks\core\Apiinit::check_url('webhooks');
            return true;
        }

        return false;
    }

    public function clear_webhook_log()
    {
        if ($this->db->truncate($this->weebhook_log_table)) {
            return true;
        }

        return false;
    }

    public function add_log($data)
    {
        if ($this->db->insert($this->weebhook_log_table, $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    public function get_log_info($id)
    {
        return $this->db->get_where(db_prefix().'webhooks_debug_log',["id" => $id])->row();
    }
}
