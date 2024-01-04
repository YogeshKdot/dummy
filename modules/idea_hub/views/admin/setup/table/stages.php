<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns       = ['name','color'];
$sIndexColumn   = 'id';
$sTable         = db_prefix().'idea_hub_stages';
$result         = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);
$output         = $result['output'];
$rResult        = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        if($aColumns[$i] == "color") {
            $_data = '<span class="color-span" style="background: ' . $aRow[$aColumns[$i]] . '"></span>';
        }else{
            $_data = '<a href="#" onclick="edit_stage(this,' . $aRow['id'] . '); return false" data-name="' . $aRow['name'] . '"  data-id="' . $aRow['id'] . '">' . $aRow[$aColumns[$i]] . '</a>';
        }
        $row[] = $_data;
    }
    $options = icon_btn('idea_hub/stages/' . $aRow['id'], 'pencil-square-o', 'btn-default', [
        'onclick' => 'edit_stage(this,' . $aRow['id'] . '); return false', 'data-name' => $aRow['name'],
    ]);
    $row[] = $options .= icon_btn('idea_hub/remove_stage/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}