<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'card',
    'client_group', 
    'client', 
    'loyalty_point_from',
    'loyalty_point_to',
    'date_create',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'loy_mbs_rule';
$join         = [ ];
$where = [];

if($this->ci->input->post('client')){
    $client = $this->ci->input->post('client');
    $where_client = '';
    foreach ($client as $p) {
        if($p != '')
        {
            if($where_client == ''){
                $where_client .= ' AND (find_in_set('.$p.', client)';
            }else{
                $where_client .= ' or find_in_set('.$p.', client)';
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
    $where_client_group = '';
    foreach ($client_group as $p) {
        if($p != '')
        {
            if($where_client_group == ''){
                $where_client_group .= ' AND (client_group = '.$p;
            }else{
                $where_client_group .= ' or client_group = '.$p;
            }
        }
    }
    if($where_client_group != '')
    {
        $where_client_group .= ')';

        array_push($where, $where_client_group);
    }
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','description']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];
        
        if($aColumns[$i] == 'name'){

            $name = '<a href="#">'.$aRow['name']. '</a>';

            if($aRow['client_group'] == 0){
                $aRow['client_group'] = '';
            }else{
                $aRow['client_group'] = $aRow['client_group'];
            }

            if ( (has_permission('loyalty', '', 'edit') || is_admin())) {
                $name = ' <a href="#" onclick="edit_mbs_rule('.$aRow['id'].',this); return false;" data-name="'.$aRow['name'].'" data-card="'.$aRow['card'].'" data-loyalty_point_from="'.$aRow['loyalty_point_from'].'" data-loyalty_point_to="'.$aRow['loyalty_point_to'].'" data-description="'.$aRow['description'].'"  data-client="' . $aRow['client'] . '" data-client_group="' . $aRow['client_group'] . '">'.$aRow['name'].'</a>';
            }

            $name .= '<div class="row-options">';

            if ( (has_permission('loyalty', '', 'edit') || is_admin())) {
                $name .= ' <a href="#" onclick="edit_mbs_rule('.$aRow['id'].',this); return false;" data-name="'.$aRow['name'].'" data-card="'.$aRow['card'].'" data-loyalty_point_from="'.$aRow['loyalty_point_from'].'" data-loyalty_point_to="'.$aRow['loyalty_point_to'].'" data-description="'.$aRow['description'].'" data-client="' . $aRow['client'] . '" data-client_group="' . $aRow['client_group'] . '" >' . _l('edit') . '</a>';
            }

            if (has_permission('loyalty', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('loyalty/delete_membership_rule/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        }elseif($aColumns[$i] == 'card'){
            $_data = name_card_by_id($aRow['card']);
        }elseif($aColumns[$i] == 'client'){
            $clients = explode(',', $aRow['client']);
            $text = '';
            foreach($clients as $key => $cli){
                if($cli != ''){
                    if(($key + 1)%2 == 0){
                        $text .= '<span class="label label-tag"> '.get_company_name($cli).'</span><br /><br />';
                    }else{
                        $text .= '<span class="mtop5 label label-tag"> '.get_company_name($cli).'</span> ';
                    }
                }
            }
            $_data = $text;
        }elseif($aColumns[$i] == 'date_create'){
            $_data = '<span class="label label-success">' ._d($aRow['date_create']).'</span>';
        }elseif($aColumns[$i] == 'client_group'){
            $_data = client_group_name($aRow['client_group']);
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
