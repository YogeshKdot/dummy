<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'id',
    'name',
    'color',
    '`order`',
    'filter_default',
    '(SELECT count(id) FROM ' . db_prefix() . 'project_status_can_change WHERE ' . db_prefix() . 'project_status_can_change.project_status_id = ' . db_prefix() . 'project_statuses.id)',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'project_statuses';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], []);

$output  = $result['output'];
$rResult = $result['rResult'];

$CI = &get_instance();
$CI->load->model('Project_status_model');

// Replace the columns to a readable format to use in a loop later
$aColumns[count($aColumns) - 1] = 'status_can_change_to';
$aColumns[3] = 'order';

// Create map with status id as key and status info as value
// We use this map in status_can_change_to column
$statusMap = [];
foreach ($CI->Project_status_model->get('', false, false) as $aRow) {
    $statusMap[$aRow['id']] = ['name' => $aRow['name'], 'color' => $aRow['color']];
}

foreach ($rResult as &$aRow) {
    $row = [];

    // Get related data
    $aRow['status_can_change_to'] =  $CI->Project_status_model->getAvalibleStatusesForChange($aRow['id']);

    for ($i = 0; $i < count($aColumns); $i++) {
        // For some of the fields we need to insert custom content
        if ($aColumns[$i] == 'color') {
            $_data = "<span style='color:{$aRow[$aColumns[$i]]}'>{$aRow[$aColumns[$i]]}</span>";
        } elseif ($aColumns[$i] == 'filter_default') {
            $_data = $aRow[$aColumns[$i]] ? "Yes" : "No";
        } elseif ($aColumns[$i] == 'name') {
            $_data = $aRow[$aColumns[$i]];
            $_data .= '<div class="row-options">';
            $_data .= '<a class="cursor-pointer" onclick="edit_status(' . $aRow['id'] . ',`project`)">' . _l('edit') . '</a>';
            $_data .= ' | <a href="' . admin_url('status_manager/delete_project_status/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'status_can_change_to') {
            $_data = '<span class="flex-wrap">';
            foreach ($aRow[$aColumns[$i]] as $statusId) {
                $_data .= "<span class='label m2' style='border: 1px solid {$statusMap[$statusId]['color']};color:{$statusMap[$statusId]['color']};'>{$statusMap[$statusId]['name']}</span>";
            }
            $_data .= "</span>";
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
