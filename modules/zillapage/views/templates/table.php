<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'thumb',
    'name',
    'active',
    'created_at',
    'updated_at',
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'landing_page_templates';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'thumb') {
            $_data = '<a href="' . admin_url('zillapage/templates/template/'. $aRow['id']) . '"><img src="'.base_url(ZILLAPAGE_ASSETS_PATH.'/images/thumb_templates')."/".$_data.'" class="fluid" width="120"></a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . base_url('zillapage/templates/template/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            if (has_permission('landingpages-blocks', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('zillapage/templates/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        }
        else if ($aColumns[$i] == 'active') {
            if ($aRow['active']) {
                $_data = '<span class="badge bg-success">'._l('active').'</span>';
            }
            else{
                $_data = '<span class="badge bg-warning">'._l('not_active').'</span>';
            }
            $_data .= '</div>';
        }
        elseif($aColumns[$i] == 'created_at' || $aColumns[$i] == 'updated_at'){
            $_data = _dt($_data);
        }

        $row[] = $_data;
    }

    $row[] = $_data;
    $output['aaData'][] = $row;
}

