<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'client',
    'invoice', 
    'old_point',
    'new_point',
    'redeep_from',
    'redeep_to',
    'time',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'loy_redeem_log';
$join         = [ ];
$where = [];

if($this->ci->input->post('client')){
    $client = $this->ci->input->post('client');
    $where_client = '';
    foreach ($client as $p) {
        if($p != '')
        {
            if($where_client == ''){
                $where_client .= ' AND (client = "'.$p.'"';
            }else{
                $where_client .= ' or client = "'.$p.'"';
            }
        }
    }
    if($where_client != '')
    {
        $where_client .= ')';
        array_push($where, $where_client);
    }
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];

        if($aColumns[$i] == 'client'){
            $_data = '<a href="'.admin_url('clients/client/'.$aRow['client']).'" >'.get_company_name($aRow['client']).'</a>';
        }elseif($aColumns[$i] == 'invoice'){
            if(is_numeric($aRow['invoice'])){
                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoice']) . '" target="_blank">'. format_invoice_number($aRow['invoice']).'</a>';
            }else{
                $_data = '';
            }

        }elseif($aColumns[$i] == 'time'){
            $_data = '<span class="label label-info">'._dt($aRow['time']).'</span>';
        }elseif($aColumns[$i] == 'redeep_to'){
            $_data = app_format_money($aRow['redeep_to'],'');
        }elseif($aColumns[$i] == 'redeep_from'){
            $_data = '<span class="label label-success">'.$aRow['redeep_from'].'</span>';
        }elseif($aColumns[$i] == 'new_point'){
            $_data = '<span class="label label-success">'.$aRow['new_point'].'</span>';
        }elseif($aColumns[$i] == 'old_point'){
            $_data = '<span class="label label-warning">'.$aRow['old_point'].'</span>';
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
