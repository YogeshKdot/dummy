<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'program_name',
    'voucher_code',
    'discount', 
    'membership',
    'loyalty_point_from',
    'loyalty_point_to',
    'start_date',
    'end_date',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'loy_mbs_program';
$join         = [ ];
$where = [];

if($this->ci->input->post('discount')){
    $discount = $this->ci->input->post('discount');
    $where_discount = '';
    foreach ($discount as $p) {
        if($p != '')
        {
            if($where_discount == ''){
                $where_discount .= ' AND (discount = "'.$p.'"';
            }else{
                $where_discount .= ' or discount = "'.$p.'"';
            }
        }
    }
    if($where_discount != '')
    {
        $where_discount .= ')';

        array_push($where, $where_discount);
    }
}

if($this->ci->input->post('membership')){
    $membership = $this->ci->input->post('membership');
    $where_membership = '';
    foreach ($membership as $p) {
        if($p != '')
        {
            if($where_membership == ''){
                $where_membership .= ' AND (find_in_set('.$p.', membership)';
            }else{
                $where_membership .= ' or find_in_set('.$p.', membership)';
            }
        }
    }
    if($where_membership != '')
    {
        $where_membership .= ')';

        array_push($where, $where_membership);
    }
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','note']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];

        if($aColumns[$i] == 'program_name'){
            $name = ' <a href="'.admin_url('loyalty/membership_program/'.$aRow['id']).'" >'.$aRow['program_name'].'</a>';
            

            $name .= '<div class="row-options">';
            $name .= '<a href="'.admin_url('loyalty/membership_program/'.$aRow['id']).'" >'._l('view').'</a>';
            if ( (has_permission('loyalty', '', 'edit') || is_admin())) {
                $name .= ' | <a href="'.admin_url('loyalty/mbs_program/'.$aRow['id']).'" >' . _l('edit') . '</a>';
            }

            if (has_permission('loyalty', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('loyalty/delete_membership_program/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        }elseif($aColumns[$i] == 'discount'){
            $_data = _l($aRow['discount']);
        }elseif ($aColumns[$i] == 'membership') {
            $mbs_rules = explode(',',  $aRow['membership']);
            $text = '';
            foreach($mbs_rules as $key => $rule){
                if(($key + 1)%2 == 0){
                    $text .= '<span class="label label-tag">'.get_membership_rule_name($rule).'</span><br /><br />';
                }else{
                    $text .= '<span class="label label-tag">'.get_membership_rule_name($rule).'</span>';
                }
                
            }

            $_data = $text;
        }elseif($aColumns[$i] == 'start_date'){
            $_data = '<span class="label label-success">'._d($aRow['start_date']).'</span>';
        }elseif($aColumns[$i] == 'end_date'){
            $_data = '<span class="label label-success">'._d($aRow['end_date']).'</span>';
        }
        

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
