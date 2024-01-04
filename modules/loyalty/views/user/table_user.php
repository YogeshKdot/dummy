<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'company',
    'email',
    'zip', 
    'country',
    'city',
    ];
$sIndexColumn = 'userid';
$sTable       = db_prefix().'clients';
$join         = [ 'LEFT JOIN '.db_prefix().'contacts ON '.db_prefix().'contacts.userid='.db_prefix().'clients.userid AND '.db_prefix().'contacts.is_primary=1', ];
$where = [];

if($this->ci->input->post('client')){
    $client = $this->ci->input->post('client');
    $where_client = '';
    foreach ($client as $p) {
        if($p != '')
        {
            if($where_client == ''){
                $where_client .= ' AND ('.db_prefix().'clients.userid = "'.$p.'"';
            }else{
                $where_client .= ' or '.db_prefix().'clients.userid = "'.$p.'"';
            }
        }
    }
    if($where_client != '')
    {
        $where_client .= ')';
        array_push($where, $where_client);
    }
}

if($this->ci->input->post('client_group')){
    $client_group = $this->ci->input->post('client_group');

    if (count($client_group) > 0) {
        array_push($where, 'AND '.db_prefix().'clients.userid IN (SELECT customer_id FROM '.db_prefix().'customer_groups WHERE groupid IN (' . implode(', ', $client_group) . '))');
    }

}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix().'clients.userid as client_id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];

        if($aColumns[$i] == 'country'){
            $point = client_loyalty_point($aRow['client_id']);
            if($point > 0){
                $_data = '<span class="label label-success">'.$point.'</span>';
            }else{
                $_data = '';
            }
        }elseif ($aColumns[$i] == 'company') {
            $_data = '<a href="'.admin_url('clients/client/'.$aRow['client_id']).'" >'.$aRow['company'].'</a>';
        }elseif($aColumns[$i] == 'zip'){
            $_data = client_membership($aRow['client_id']);
        }elseif ($aColumns[$i] == 'city') {
            $_data = '<a href="'.admin_url('loyalty/transation?cus='.$aRow['client_id']).'" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view_transation').'"><i class="fa fa-eye"></i></a>';
        }elseif($aColumns[$i] == 'email'){
            $_data = ($aRow['email'] ? '<a href="mailto:' . $aRow['email'] . '">' . $aRow['email'] . '</a>' : '');
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
