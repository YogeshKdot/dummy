<?php

defined('BASEPATH') or exit('No direct script access allowed');


$table_ldpfdata = db_prefix() . 'landing_page_form_data';
$table_ldp = db_prefix() . 'landing_pages';



$aColumnsQuery = [
    $table_ldp.'.name as landing_page_name',
    $table_ldpfdata.'.field_values as field_values',
    $table_ldpfdata.'.browser as browser',
    $table_ldpfdata.'.os as os',
    $table_ldpfdata.'.device as device',
    $table_ldpfdata.'.created_at as created_at',
];

$aColumnsEach = [
    'landing_page_name',
    'field_values',
    'browser',
    'os',
    'device',
    'created_at',
];
$where        = [];

// Add blank where all filter can be stored
$filter = [];

$landingpages         = $this->ci->landingpage_model->get_all_landing_pages();
$landingpagesIds = [];
foreach ($landingpages as $item) {
    if ($this->ci->input->post('landing_page_id_' . $item['id'])) {
        array_push($landingpagesIds, $item['id']);
    }
}

if (count($landingpagesIds) > 0) {
    array_push($filter, 'AND landing_page_id IN (' . implode(',', $landingpagesIds) . ')');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

$sIndexColumn = 'id';

$sTable       = $table_ldpfdata;

$join = ['LEFT JOIN ' . $table_ldp . ' ON ' . $table_ldp . '.id = ' . $table_ldpfdata . '.landing_page_id'];

$result = data_tables_init($aColumnsQuery, $sIndexColumn, $sTable, $join, $where, [$table_ldpfdata.'.id as id', $table_ldp.'.code as code']);

$output  = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumnsEach); $i++) {
        $_data = $aRow[$aColumnsEach[$i]];
        if ($aColumnsEach[$i] == 'landing_page_name') {
            $_data = '<a href="' . admin_url('zillapage/landingpages/setting/'. $aRow['code']) . '">' . $aRow['landing_page_name'] . '</a>';
            
            $_data .= '<div class="row-options">';
            //$_data .= '<a target="_blank" href="' . base_url('publish/' . $aRow['code']) . '">' . _l('preview') . '</a>';
            if (has_permission('landingpages-leads', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('zillapage/leads/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        }
        else if ($aColumnsEach[$i] == 'field_values') {
            $field_values = json_decode($aRow['field_values']);
            $_data = '';
            foreach ($field_values as $key => $value) {
                $_data .= '<span class="text-small">'.$key.': '.$value.'</span></br>';
            }
        }

        $row[] = $_data;
    }

    $_data = '<a href="#" class="btn btn-default btn-convert-ldp-to-lead mbot5 mright5" data-id='.$aRow['id'].'>
            '._l('convert_to_lead').'</a>';
    $_data .= '<a href="#" class="btn btn-success btn-convert-ldp-to-customer" data-id='.$aRow['id'].'>
            '._l('convert_to_customer').'</a>';

    $row[] = $_data;
    $output['aaData'][] = $row;
}