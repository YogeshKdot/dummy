<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'is_publish',
    'created_at',
    'updated_at',
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'landing_pages';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id','code']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name') {
            $_data = '<a href="' . admin_url('zillapage/landingpages/builder/'. $aRow['code']) . '">' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a target="_blank" href="' . base_url('publish/' . $aRow['code']) . '">' . _l('preview') . '</a>';

            if (has_permission('landingpages', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('zillapage/landingpages/deletelandingpage/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        }
        else if ($aColumns[$i] == 'is_publish') {
        	if ($aRow['is_publish']) {
        		$_data = '<span class="badge bg-success">'._l('published').'</span>';
        	}
        	else{
        		$_data = '<span class="badge bg-warning">'._l('not_publish').'</span>';
        	}
            $_data .= '</div>';
        }
        elseif($aColumns[$i] == 'created_at' || $aColumns[$i] == 'updated_at'){
            $_data = _dt($_data);
        }

        $row[] = $_data;
    }
    
    $_data = '<a href="'.admin_url('zillapage/landingpages/builder/'. $aRow['code']).'" class="btn btn-sm btn-success mright5">'._l('builder').'</a>';
    $_data .= '<a href="'.admin_url('zillapage/landingpages/setting/'. $aRow['code']).'" class="btn btn-sm btn-default mright5">'._l('settings').'</a>';
    $_data .= '<a href="'.base_url('publish/'. $aRow['code']).'" class="btn btn-sm btn-default" target="_blank">'._l('preview').'</a>';

    $row[] = $_data;
    $output['aaData'][] = $row;
}

