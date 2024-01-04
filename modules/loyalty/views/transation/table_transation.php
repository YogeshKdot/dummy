<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'client',
    'reference',
    'invoice', 
    'loyalty_point',
    'type',
    'add_from',
    'date_create',
    'note',
    db_prefix().'loy_transation.id',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'loy_transation';
$join         = [ 'LEFT JOIN '.db_prefix().'invoices ON '.db_prefix().'invoices.id = '.db_prefix().'loy_transation.invoice' ];
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

if($this->ci->input->post('reference')){
    $reference = $this->ci->input->post('reference');
    $where_reference = '';
    foreach ($reference as $p) {
        if($p != '')
        {
            if($where_reference == ''){
                $where_reference .= ' AND (reference = "'.$p.'"';
            }else{
                $where_reference .= ' or reference = "'.$p.'"';
            }
        }
    }
    if($where_reference != '')
    {
        $where_reference .= ')';

        array_push($where, $where_reference);
    }
}



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [ db_prefix().'loy_transation.note', db_prefix().'invoices.number', db_prefix().'invoices.prefix']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];

        if($aColumns[$i] == 'client'){
            $_data = '<a href="'.admin_url('clients/client/'.$aRow['client']).'" >'.get_company_name($aRow['client']).'</a>';
        }elseif($aColumns[$i] == 'reference'){
            $_data =  _l($aRow['reference']);
        }elseif($aColumns[$i] == 'invoice'){
            if(is_numeric($aRow['invoice'])){
                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoice']) . '" target="_blank">'. format_invoice_number($aRow['invoice']).'</a>';
            }else{
                $_data = '';
            }

        }elseif($aColumns[$i] == 'type'){
            $_data =  _l($aRow['type']);
        }elseif($aColumns[$i] == 'add_from'){
            if(is_numeric($aRow['add_from']) && $aRow['add_from'] != 0){
                $_data = '<a href="' . admin_url('staff/profile/' . $aRow['add_from']) . '">' . staff_profile_image($aRow['add_from'], [
                    'staff-profile-image-small',
                    ]) . '</a>';
                $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['add_from']) . '">' . get_staff_full_name($aRow['add_from']) . '</a>';
            }else{
                $_data = '';
            }
        }elseif($aColumns[$i] == 'date_create'){
            $_data = '<span class="label label-info">'._dt($aRow['date_create']).'</span>';
        }elseif($aColumns[$i] == db_prefix().'loy_transation.id') {

            if(has_permission('loyalty', '', 'delete') || is_admin()){
                $_data = '<a href="'.admin_url('loyalty/delete_transation/'.$aRow[db_prefix().'loy_transation.id']).'" class="btn btn-icon btn-danger"><i class="fa fa-remove"></i></a>';
            }else{
                $_data = '';
            }
            
        }elseif($aColumns[$i] == 'loyalty_point'){
            $_data = '<span class="label label-success">'.$aRow['loyalty_point'].'</span>';
        }elseif($aColumns[$i] == 'note'){
            $_data = _l($aRow['note']);
        }


        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
