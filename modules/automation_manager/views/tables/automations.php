<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'id',
    'name',
    '(SELECT count(id) FROM ' . db_prefix() . 'automation_triggers WHERE ' . db_prefix() . 'automation_triggers.automation_id = ' . db_prefix() . 'automations.id)',
    '(SELECT count(id) FROM ' . db_prefix() . 'automation_actions WHERE ' . db_prefix() . 'automation_actions.automation_id = ' . db_prefix() . 'automations.id)',
    '(SELECT max(last_triggered) FROM ' . db_prefix() . 'automation_triggers WHERE ' . db_prefix() . 'automation_triggers.automation_id = ' . db_prefix() . 'automations.id) as lt',
    '(SELECT last_triggered_by FROM ' . db_prefix() . 'automation_triggers WHERE ' . db_prefix() . 'automation_triggers.automation_id = ' . db_prefix() . 'automations.id AND last_triggered=lt limit 1)',
    'active'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'automations';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], []);

$output  = $result['output'];
$rResult = $result['rResult'];

$CI = &get_instance();


// var_dump($rResult, $aColumns);
// die();
foreach ($rResult as &$aRow) {
    $row = [];


    for ($i = 0; $i < count($aColumns); $i++) {
        // For some of the fields we need to insert custom content
        if ($aColumns[$i] == 'name') {
            $_data = $aRow[$aColumns[$i]];
            $_data .= '<div class="row-options">';
            $_data .= '<a  href="' . admin_url('automation_manager/edit/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            if ($aRow['active']) {
                $_data .= ' | <a href="' . admin_url('automation_manager/deactivate/' . $aRow['id']) . '" class="text-danger">' . _l('Deactivate') . '</a>';
            } else {
                $_data .= ' | <a href="' . admin_url('automation_manager/activate/' . $aRow['id']) . '" class="text-success">' . _l('Activate') . '</a>';
            }
            $_data .= ' | <a href="' . admin_url('automation_manager/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';


            $_data .= '</div>';
        } elseif ($aColumns[$i] == '(SELECT max(last_triggered) FROM ' . db_prefix() . 'automation_triggers WHERE ' . db_prefix() . 'automation_triggers.automation_id = ' . db_prefix() . 'automations.id) as lt') {
            $_data = $aRow['lt'];
        } elseif ($aColumns[$i] == '(SELECT last_triggered_by FROM ' . db_prefix() . 'automation_triggers WHERE ' . db_prefix() . 'automation_triggers.automation_id = ' . db_prefix() . 'automations.id AND last_triggered=lt limit 1)') {
            if ($aRow[$aColumns[$i]]) {
                $_data = '<a href="' . admin_url('tasks/view/' . $aRow[$aColumns[$i]]) . '" class="display-block " onclick="init_task_modal(' . $aRow[$aColumns[$i]] . '); return false;">#' . $aRow[$aColumns[$i]] . '</a>';
            } else {
                $_data = "";
            }
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
