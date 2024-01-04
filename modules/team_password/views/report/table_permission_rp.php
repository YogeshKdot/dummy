<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'obj_id',
    'staff',
    'r',
    'w', 
    'type',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'permission';
$join         = [];
$where = [];

if($this->ci->input->post('staff')){
    $staff = $this->ci->input->post('staff');
    $where_staff = '';
    foreach ($staff as $p) {
        if($p != '')
        {
            if($where_staff == ''){
                $where_staff .= ' AND (staff = "'.$p.'"';
            }else{
                $where_staff .= ' or staff = "'.$p.'"';
            }
        }
    }
    if($where_staff != '')
    {
        $where_staff .= ')';

        array_push($where, $where_staff);
    }
}

if($this->ci->input->post('type')){
    $type = $this->ci->input->post('type');
    $where_type = '';
    foreach ($type as $p) {
        if($p != '')
        {
            if($where_type == ''){
                $where_type .= ' AND (type = "'.$p.'"';
            }else{
                $where_type .= ' or type = "'.$p.'"';
            }
        }
    }
    if($where_type != '')
    {
        $where_type .= ')';

        array_push($where, $where_type);
    }
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i] == 'obj_id'){
            $_data = '<a href="'.admin_url('team_password/view_'.$aRow['type'].'/'.$aRow['obj_id']).'">'. item_name_by_objid($aRow['obj_id'],$aRow['type']).'</a>';
        }elseif($aColumns[$i] == 'staff'){
            $_data = '<a href="'.admin_url('profile/'.$aRow['staff']).'">'.get_staff_full_name($aRow['staff']).'</a>';
        }elseif($aColumns[$i] == 'r'){
            $_data = _l($aRow['r']);
        }elseif ($aColumns[$i] == 'w') {
            $_data = _l($aRow['w']);
        }elseif ($aColumns[$i] == 'type') {
            $_data = _l($aRow['type']);
        }
        
        

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
