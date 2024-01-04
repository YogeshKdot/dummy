<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'share_id',
    'client',
    'effective_time',
    'r',
    'w', 
    'type',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'tp_share';
$join         = [];
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

if($this->ci->input->post('effective_time')){
    $effective_time = $this->ci->input->post('effective_time');

    if($effective_time == 'expired'){
        array_push($where, ' AND effective_time <= "'.date('Y-m-d H:i:s').'"');
    }elseif($effective_time == 'unexpired'){
        array_push($where, ' AND effective_time > "'.date('Y-m-d H:i:s').'"');
    }
    
}

$this->ci->load->model('team_password/team_password_model');
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','email']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i] == 'share_id'){
            $_data = '<a href="'.admin_url('team_password/view_'.$aRow['type'].'/'.$aRow['share_id']).'">'. item_name_by_objid($aRow['share_id'],$aRow['type']).'</a>';
        }elseif($aColumns[$i] == 'r'){
            $_data = _l($aRow['r']);
        }elseif ($aColumns[$i] == 'w') {
            $_data = _l($aRow['w']);
        }elseif ($aColumns[$i] == 'type') {
            $_data = _l($aRow['type']);
        }elseif ($aColumns[$i] == 'client') {
           $name = '';
           $client_name = '';
            if($aRow['client'] != ''){
                $contact = $this->ci->team_password_model->get_contact_by_email($aRow['client']);
            }else{
                $contact = '';
            }

            if($contact != ''){
              $name = $contact->lastname.' '.$contact->firstname;
              $client_id = get_user_id_by_contact_id($contact->id);
              $client_name = get_company_name($client_id);
            }else{
              $name = $aRow['email'];
            }

            if($aRow['client'] != ''){
                $_data = $client_name.' - '. $name.' ['.$aRow['client'].']';
            }else{
                $_data = $name;
            }
           
        }elseif ($aColumns[$i] == 'effective_time'){
            $_data = _dt($aRow['effective_time']);
        }
        
        

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
