<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'subject',
    'redeemp_type',
    'start_date', 
    'end_date',
    'min_poin_to_redeem',
    'rule_base',
    'minium_purchase',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'loy_rule';
$join         = [ ];
$where = [];

if($this->ci->input->post('redeemp_type')){
    $redeemp_type = $this->ci->input->post('redeemp_type');
    $where_redeemp_type = '';
    foreach ($redeemp_type as $p) {
        if($p != '')
        {
            if($where_redeemp_type == ''){
                $where_redeemp_type .= ' AND (redeemp_type = "'.$p.'"';
            }else{
                $where_redeemp_type .= ' or redeemp_type = "'.$p.'"';
            }
        }
    }
    if($where_redeemp_type != '')
    {
        $where_redeemp_type .= ')';

        array_push($where, $where_redeemp_type);
    }
}

if($this->ci->input->post('rule_base')){
    $rule_base = $this->ci->input->post('rule_base');
    $where_rule_base = '';
    foreach ($rule_base as $p) {
        if($p != '')
        {
            if($where_rule_base == ''){
                $where_rule_base .= ' AND (rule_base = "'.$p.'"';
            }else{
                $where_rule_base .= ' or rule_base = "'.$p.'"';
            }
        }
    }
    if($where_rule_base != '')
    {
        $where_rule_base .= ')';

        array_push($where, $where_rule_base);
    }
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];
        
        if($aColumns[$i] == 'subject'){
            $name = '<a href="' . admin_url('loyalty/loyalty_program_detail/' . $aRow['id'] ).'">'.$aRow['subject']. '</a>';

            $name .= '<div class="row-options">';

            $name .= '<a href="' . admin_url('loyalty/loyalty_program_detail/' . $aRow['id'] ).'">'._l('view'). '</a>';
            if ( (has_permission('loyalty', '', 'edit') || is_admin())) {
                $name .= ' | <a href="' . admin_url('loyalty/create_loyalty_rule/' . $aRow['id'] ).'" >' . _l('edit') . '</a>';
            }

            if (has_permission('loyalty', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('loyalty/delete_loyalty_rule/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        }elseif($aColumns[$i] == 'redeemp_type'){
            $_data = _l($aRow['redeemp_type']);
        }elseif($aColumns[$i] == 'rule_base'){
            $_data = _l($aRow['rule_base']);
        }elseif($aColumns[$i] == 'start_date'){
            $_data = '<span class="label label-info">'._d($aRow['start_date']).'</span>';
        }elseif($aColumns[$i] == 'end_date'){
            $_data = '<span class="label label-warning">'._d($aRow['end_date']).'</span>';
        }elseif($aColumns[$i] == 'minium_purchase'){
            $_data = app_format_money($aRow['minium_purchase'],'');
        }elseif($aColumns[$i] == 'min_poin_to_redeem'){
            $_data = '<span class="label label-success">'.$aRow['min_poin_to_redeem'].'</span>';
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
