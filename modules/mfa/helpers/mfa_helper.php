<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * { MFA module helpers }
 */

/**
 * Gets the mfa option.
 *
 * @param              $name   The name
 *
 * @return     array|string  The mfa option.
 */
function get_mfa_option($name)
{
    $CI = & get_instance();
    $options = [];
    $val  = '';
    $name = trim($name);
    

    if (!isset($options[$name])) {
        // is not auto loaded
        $CI->db->select('option_val');
        $CI->db->where('option_name', $name);
        $row = $CI->db->get(db_prefix() . 'mfa_options')->row();
        if ($row) {
            $val = $row->option_val;
        }
    } else {
        $val = $options[$name];
    }

    return $val;
}

/**
 * { row mfa options exist }
 *
 * @param         $name   The name
 *
 * @return     integer  ( 1 or 0 )
 */
function row_mfa_options_exist($name){
    $CI = & get_instance();
    $i = count($CI->db->query('Select * from '.db_prefix().'mfa_options where option_name = '.$name)->result_array());
    if($i == 0){
        return 0;
    }
    if($i > 0){
        return 1;
    }
}

/**
 * { generate security code }
 *
 * @param      $staff  The staff
 *
 * @return     boolean  
 */
function generate_security_code($staff, $type){
    $CI = & get_instance();
    $result = 0;
    $security_code = rand(100000,999999);
    $CI->db->where('staff', $staff);
    $CI->db->where('type', $type);
    $old_code = $CI->db->get(db_prefix().'mfa_security_code')->row();

    if($old_code){
        if($security_code != $old_code->code){
            $CI->db->where('staff', $staff);
            $CI->db->where('type', $type);
            $CI->db->update(db_prefix().'mfa_security_code',[
                'staff' => $staff,
                'code' => $security_code,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if ($CI->db->affected_rows() > 0) {
                $result++;
            }
        }else{
            $security_code = rand(100000,999999);
            $CI->db->where('staff', $staff);
            $CI->db->where('type', $type);
            $CI->db->update(db_prefix().'mfa_security_code',[
                'staff' => $staff,
                'code' => $security_code,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if ($CI->db->affected_rows() > 0) {
                $result++;
            }
        }
    }else{
        $security_code = rand(100000,999999);
        $CI->db->insert(db_prefix().'mfa_security_code', [
            'staff' => $staff,
            'code' => $security_code,
            'created_at' => date('Y-m-d H:i:s'),
            'type' => $type
        ]);
        $insert_id = $CI->db->insert_id();
        if($insert_id){
            $result++;
        }
    }

    if($result > 0){
        return true;
    }
    return false;
}

/**
 * { check security code }
 *
 * @param         $staff  The staff
 * @param         $code   The code
 *
 * @return     boolean  
 */
function check_security_code($staff, $code, $type){
    $CI = & get_instance();
    $CI->db->where('staff', $staff);
    $CI->db->where('type', $type);
    $CI->db->where('code', $code);
    $result = $CI->db->get(db_prefix().'mfa_security_code')->row();
    if($result){
        $CI->db->where('staff', $staff);
        $CI->db->delete(db_prefix().'mfa_security_code');
        return true;
    }
    return false;
}

/**
 * Gets the security code.
 *
 * @param        $staff  The staff
 * @param        $type   The type
 *
 * @return     string  The security code.
 */
function get_security_code($staff, $type){
    $CI = & get_instance();
    $CI->db->where('staff', $staff);
    $CI->db->where('type', $type);
    $result = $CI->db->get(db_prefix().'mfa_security_code')->row();

    if($result){
        return $result->code;
    }
    return '';
}

/**
 * Gets the timestamp by now.
 *
 * @return     int   The timestamp by now.
 */
function get_timestamp_by_now(){
    $cur_hours = date('H');
    $cur_min = date('i');
    $cur_sc = date('s');

    $total_sc = $cur_hours*60*60 + $cur_min*60 + $cur_sc;

    $min_date = strtotime('1970-01-01');
    $cur_date = strtotime(date('Y-m-d 00:00:00'));

    return $cur_date - $min_date + $total_sc;
}

/**
 * Enables the gg auth with role.
 *
 * @param      int|string  $role   The role
 *
 * 
 */
function enable_gg_auth_with_role($role){
    if($role != '' && $role != 0){
        $CI = & get_instance();
        $CI->db->where('roleid', $role);
        $r = $CI->db->get(db_prefix().'roles')->row();
        if($r){
            return $r->enable_gg_auth;
        }
    }else{
        return get_mfa_option('enable_gg_auth_for_users_have_not_role');
    }
   
}