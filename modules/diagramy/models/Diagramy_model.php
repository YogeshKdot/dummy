<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Diagramy_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_data_by_rel_id($table, $where)
    {
        $query=$this->db->get_where(db_prefix().$table, $where);

        return $query->result_array();
    }

    public function get_staff_counts($staffid)
    {
        $count = 0;

        $sql = 'SELECT count(`staffid`) as total_count
                from '.db_prefix()."diagramy where staffid= '".$staffid."' ";
        $query = $this->db->query($sql);
        $row   = $query->row();
        if (isset($row)) {
            $count = $row->total_count;
        }

        return $count;
    }

    /**
     * Get groups.
     *
     * @param mixed $id group id (Optional)
     *
     * @return mixed object or array
     */
    public function get_groups($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix().'diagramy_groups')->row();
        }
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix().'diagramy_groups')->result_array();
    }

    /**
     * Add new group.
     *
     * @param mixed $data All $_POST data
     *
     * @return bool
     */
    public function add_group($data)
    {
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix().'diagramy_groups', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('diagramy Group Added [ID: '.$insert_id.']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update group.
     *
     * @param mixed $data All $_POST data
     * @param mixed $id   group id to update
     *
     * @return bool
     */
    public function update_group($data, $id)
    {
        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'diagramy_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('diagramy Group Updated [ID: '.$id.']');

            return true;
        }

        return false;
    }

    /**
     * @param  int ID
     * @param mixed $id
     *
     * @return mixed
     *               Delete type from database, if used return array with key referenced
     */
    public function delete_group($id)
    {
        if (is_reference_in_table('diagramy_group_id', db_prefix().'diagramy', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix().'diagramy_groups');
        if ($this->db->affected_rows() > 0) {
            log_activity('Group Deleted ['.$id.']');

            return true;
        }

        return false;
    }

    /**
     * @param  int (optional)
     * @param mixed $id
     *
     * @return object
     *                Get single
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix().'diagramy')->row();
        }

        return $this->db->get(db_prefix().'diagramy')->result_array();
    }

    /**
     * Add new.
     *
     * @param mixed $data All $_POST dat
     *
     * @return mixed
     */
    public function add($data)
    {
        $text                     = str_replace('[removed]', 'data:image/png;base64,', $data['diagramy_content']);
        $data['diagramy_content'] = $text;
        $data['staffid']          = '' == $data['staffid'] ? 0 : $data['staffid'];
        $data['dateadded']        = date('Y-m-d H:i:s');
        $data['diagramy_slug']    = str_replace(' ', '_', $data['title']);
        $this->db->insert(db_prefix().'diagramy', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New diagramy Added [ID:'.$insert_id.']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update.
     *
     * @param mixed $data All $_POST data
     * @param mixed $id   id
     *
     * @return bool
     */
    public function update($data, $id)
    {
        $text                     = str_replace('[removed]', 'data:image/png;base64,', $data['diagramy_content']);
        $data['diagramy_content'] = $text;
        $data['staffid']          = '' == $data['staffid'] ? 0 : $data['staffid'];
        $data['dateaupdated']     = date('Y-m-d H:i:s');
        $data['diagramy_slug']    = str_replace(' ', '_', $data['title']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'diagramy', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('diagramy Updated [ID:'.$id.']');

            return true;
        }

        return false;
    }

    /**
     * Delete.
     *
     * @param mixed $id id
     *
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix().'diagramy');
        if ($this->db->affected_rows() > 0) {
            log_activity('diagramy Deleted [ID:'.$id.']');

            return true;
        }

        return false;
    }

    public function get_diagramy_data_byslug($slug)
    {
        $this->db->where('diagramy_slug', $slug);

        return $result = $this->db->get(db_prefix().'diagramy')->row();
    }

    public function get_all_projects()
    {
        $this->db->select('tbldiagramy.title,tbldiagramy.description,tbldiagramy_groups.name,tbldiagramy.dateadded,tbldiagramy.diagramy_slug,tbldiagramy.diagramy_group_id,tblstaff.firstname,tblstaff.lastname,tbldiagramy.staffid,tbldiagramy.id,tbldiagramy.diagramy_content');

        $this->db->from('tbldiagramy');
        $this->db->join('tbldiagramy_groups', 'tbldiagramy.diagramy_group_id = tbldiagramy_groups.id', 'left');
        $this->db->join('tblstaff', 'tbldiagramy.staffid=tblstaff.staffid', 'left');
        $this->db->group_by('tbldiagramy.id');

        return $result = $this->db->get()->result_array();
    }
}
