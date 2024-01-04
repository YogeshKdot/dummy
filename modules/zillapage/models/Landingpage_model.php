<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Landingpage_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_landing_page_code($code = '')
    {
        $this->db->where('code', $code);
        return $this->db->get(db_prefix() . 'landing_pages')->row();
    }
    public function get_form_lead($id)
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'landing_page_form_data')->row();
    }
    public function get_all_landing_pages()
    {
        $this->db->order_by('created_at', 'desc');
        $data = $this->db->get(db_prefix() . 'landing_pages')->result_array();
        return array_values($data);
    }

    public function get_all_templates()
    {
        $this->db->where('active', 1);
        $this->db->order_by('created_at', 'desc');
        $data = $this->db->get(db_prefix() . 'landing_page_templates')->result_array();
        return array_values($data);
    }
    public function find_template($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'landing_page_templates')->row();
        }
        return $this->db->get(db_prefix() . 'landing_page_templates')->result_array();
    }
    

    public function get_template($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get(db_prefix() . 'landing_page_templates')->row();
        return $data;       
    }

    public function add_template($data)
    {
        $data['active']    = isset($data['active']) ? 1 : 0;
        $thumb_uploaded  = handle_thumb_template_upload();

        if ($thumb_uploaded) {
            $data['thumb'] = $thumb_uploaded;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix().'landing_page_templates', $data);

        $id = $this->db->insert_id();
        if (!$id) {
            return false;
        }
        log_activity('New Template Landing Page Added [ID: ' . $id . ', Name: ' . $data['name'] . ']');
        return $id;
    }

    public function update_template($data, $item)
    {

        $data['active']    = isset($data['active']) ? 1 : 0;
        $thumb_uploaded  = handle_thumb_template_upload($item->thumb);

        if ($thumb_uploaded) {
            $data['thumb'] = $thumb_uploaded;
        }
        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $item->id);

        $this->db->update(db_prefix() . 'landing_page_templates', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function delete_template($item)
    {
        $this->db->where('id', $item->id);
        $this->db->delete(db_prefix() . 'landing_page_templates');

        if ($this->db->affected_rows() > 0) {
            // delete thumb
            $file_path = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/thumb_templates/".$item->thumb;;
            handle_delete_file_zillapage($file_path);

            log_activity('Template Landing Page Deleted [ID:' . $item->id . ']');

            return true;
        }
        return false;
    }
    
   

    public function get_block($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get(db_prefix() . 'landing_page_blocks')->row();
        return $data;       
    }

    public function add_block($data)
    {
        $data['active']    = isset($data['active']) ? 1 : 0;
        $thumb_uploaded  = handle_thumb_block_upload();

        if ($thumb_uploaded) {
            $data['thumb'] = $thumb_uploaded;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix().'landing_page_blocks', $data);

        $id = $this->db->insert_id();
        if (!$id) {
            return false;
        }
        log_activity('New Block Landing Page Added [ID: ' . $id . ', Name: ' . $data['name'] . ']');
        return $id;
    }

    public function update_block($data, $item)
    {

        $data['active']    = isset($data['active']) ? 1 : 0;
        $thumb_uploaded  = handle_thumb_block_upload($item->thumb);

        if ($thumb_uploaded) {
            $data['thumb'] = $thumb_uploaded;
        }
        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $item->id);

        $this->db->update(db_prefix() . 'landing_page_blocks', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }
    public function delete_blocks($item)
    {
        $this->db->where('id', $item->id);
        $this->db->delete(db_prefix() . 'landing_page_blocks');

        if ($this->db->affected_rows() > 0) {
            // delete thumb
            $file_path = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/thumb_blocks/".$item->thumb;;
            handle_delete_file_zillapage($file_path);

            log_activity('Blocks Landing Page Deleted [ID:' . $item->id . ']');

            return true;
        }
        return false;
    }

    public function get_all_blocks()
    {
        $this->db->where('active', true);
        $data = $this->db->get(db_prefix() . 'landing_page_blocks')->result_array();
        return $data;       
    }

    public function get_landing_page_setting($key)
    {
        $this->db->where('key', $key);
        $data = $this->db->get(db_prefix() . 'landing_page_settings')->row();
        return $data;
    }

     public function update_settings($data,$id)
    {
        if (isset($data['blockscss'])) {
            # code...
            $this->db->where('id', $id);
            $data['value'] = $data['blockscss'];
            unset($data['blockscss']);
            $this->db->update(db_prefix() . 'landing_page_settings', $data);

        }
        return true;
    }


    public function get_settings($arr)
    {
        $this->db->where_in('key',$arr);
        $data = $this->db->get(db_prefix() . 'landing_page_settings')->result_array();
        return $data;
    }

    

    public function delete_landing_page($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'landing_pages');
        if ($this->db->affected_rows() > 0) {
            log_activity('Landing Page Deleted [ID:' . $id . ']');

            return true;
        }
        return false;
    }
    
    public function delete_lead($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'landing_page_form_data');
        if ($this->db->affected_rows() > 0) {
            log_activity('Landing Page Lead Deleted [ID:' . $id . ']');

            return true;
        }
        return false;
    }

    

    public function update_landing_page($data, $page)
    {
        $data                  = $this->_do_landingpage_notify_responsibles($data);

        $data['is_publish']    = isset($data['is_publish']) ? 1 : 0;
        //$social_image_uploaded     = (handle_social_image_upload() ? true : false);
        $favicon_name_uploaded  = handle_favicon_landingpage_upload($page->favicon);
        if ($favicon_name_uploaded) {
            $data['favicon'] = $favicon_name_uploaded;
        }
        $social_image_uploaded  = handle_social_image_landingpage_upload($page->social_image);
        if ($social_image_uploaded) {
            $data['social_image'] = $social_image_uploaded;
        }

        $this->db->where('code', $page->code);
        $this->db->update(db_prefix() . 'landing_pages', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function form_landingpage_submit_notification($lead_id, $assigned, $integration = false)
    {
        if ((!empty($assigned) && $assigned != 0)) {
            if ($integration == false) {
                if ($assigned == get_staff_user_id()) {
                    return false;
                }
            }

            $name = $this->db->select('name')->from(db_prefix() . 'leads')->where('id', $lead_id)->get()->row()->name;

            $notification_data = [
                'description'     => ($integration == false) ? 'not_assigned_lead_to_you' : 'not_lead_assigned_from_form',
                'touserid'        => $assigned,
                'link'            => '#leadid=' . $lead_id,
                'additional_data' => ($integration == false ? serialize([
                    $name,
                ]) : serialize([])),
            ];

            if ($integration != false) {
                $notification_data['fromcompany'] = 1;
            }

            if (add_notification($notification_data)) {
                pusher_trigger_notification([$assigned]);
            }

            $this->db->select('email');
            $this->db->where('staffid', $assigned);
            $email = $this->db->get(db_prefix() . 'staff')->row()->email;

            send_mail_template('lead_assigned', $lead_id, $email);

            $this->db->where('id', $lead_id);
            $this->db->update(db_prefix() . 'leads', [
                'dateassigned' => date('Y-m-d'),
            ]);

            $not_additional_data = [
                get_staff_full_name(),
                '<a href="' . admin_url('profile/' . $assigned) . '" target="_blank">' . get_staff_full_name($assigned) . '</a>',
            ];

            if ($integration == true) {
                unset($not_additional_data[0]);
                array_values(($not_additional_data));
            }

            $not_additional_data = serialize($not_additional_data);

            $not_desc = ($integration == false ? 'not_lead_activity_assigned_to' : 'not_lead_activity_assigned_from_form');
            $this->log_lead_activity($lead_id, $not_desc, $integration, $not_additional_data);
        }
    }

    private function _do_landingpage_notify_responsibles($data)
    {
        if (isset($data['notify_lead_imported'])) {
            $data['notify_lead_imported'] = 1;
        } else {
            $data['notify_lead_imported'] = 0;
        }

        if ($data['responsible'] == '') {
            $data['responsible'] = 0;
        }
        if ($data['notify_lead_imported'] != 0) {
            if ($data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids']  = serialize([]);
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        return $data;
    }
    public function add_convert_to_lead($data)
    {
        if (isset($data['custom_contact_date']) || isset($data['custom_contact_date'])) {
            if (isset($data['contacted_today'])) {
                $data['lastcontact'] = date('Y-m-d H:i:s');
                unset($data['contacted_today']);
            } else {
                $data['lastcontact'] = to_sql_date($data['custom_contact_date'], true);
            }
        }

        if (isset($data['is_public']) && ($data['is_public'] == 1 || $data['is_public'] === 'on')) {
            $data['is_public'] = 1;
        } else {
            $data['is_public'] = 0;
        }

        if (!isset($data['country']) || isset($data['country']) && $data['country'] == '') {
            $data['country'] = 0;
        }

        if (isset($data['custom_contact_date'])) {
            unset($data['custom_contact_date']);
        }

        $data['description'] = nl2br($data['description']);
        $data['dateadded']   = date('Y-m-d H:i:s');
        $data['addedfrom']   = get_staff_user_id();

        $data = hooks()->apply_filters('before_lead_added', $data);

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['address'] = trim($data['address']);
        $data['address'] = nl2br($data['address']);

        $data['email'] = trim($data['email']);
        $this->db->insert(db_prefix() . 'leads', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
        

        return false;
    }
    

}


