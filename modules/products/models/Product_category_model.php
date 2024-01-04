<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Product_category_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('p_category_id', $id);
            $category = $this->db->get(db_prefix().'product_categories')->row();

            return $category;
        }
        $categories = $this->db->get(db_prefix().'product_categories')->result_array();

        return $categories;
    }

    public function add($data)
    {
        $this->db->insert(db_prefix().'product_categories', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Product Category Added[ID:'.$insert_id.', '.$data['p_category_name'].', Staff id '.get_staff_user_id().']');

            return $insert_id;
        }

        return false;
    }

    public function edit($data)
    {
        $this->db->where('p_category_id', $data['p_category_id']);
        $res = $this->db->update(db_prefix().'product_categories', [
            'p_category_name'        => $data['p_category_name'],
            'p_category_description' => $data['p_category_description'],
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Product Category updated[ID:'.$data['p_category_id'].', '.$data['p_category_name'].', Staff id '.get_staff_user_id().']');
        }

        return $res;
    }

    public function delete($id)
    {
        $original_category = $this->get($id);
        $this->db->where('p_category_id', $id);
        $this->db->delete(db_prefix().'product_categories');
        if ($this->db->affected_rows() > 0) {
            log_activity('Product Category deleted[ID:'.$id.', '.$original_category->p_category_name.', Staff id '.get_staff_user_id().']');

            return true;
        }

        return false;
    }
}
