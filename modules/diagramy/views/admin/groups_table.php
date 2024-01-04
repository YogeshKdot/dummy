<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = ['name'];

$sIndexColumn = 'id';
$sTable       = db_prefix().'diagramy_groups';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id', 'description']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); ++$i) {
        $_data = '<a href="#" onclick="edit_group(this,'.$aRow['id'].'); return false" data-name="'.$aRow['name'].'"  data-description="'.$aRow['description'].'"  data-id="'.$aRow['id'].'">'.$aRow[$aColumns[$i]].'</a>';
        $row[] = $_data;
    }

    $options = icon_btn('diagramy/group/'.$aRow['id'], 'pencil-square-o', 'btn-default', [
        'onclick' => 'edit_group(this,'.$aRow['id'].'); return false', 'data-name' => $aRow['name'], 'data-description' => $aRow['description'],
    ]);
    $row[] = $options .= icon_btn('diagramy/delete_group/'.$aRow['id'], 'remove', 'btn-danger _delete');

    $output['aaData'][] = $row;
}
