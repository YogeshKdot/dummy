<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'group_name',
    'creator',
    'date_create',
    ];
$sIndexColumn = 'id';
$sTable       = 'tblresource_group';
$join = [];
$where = [];   
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','icon','description']);

$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'group_name') {
            $icon = $str = str_replace( 'fa-', '',  $aRow['icon'] );
            $_data = icon_btn('resource_booking/resource_group/' . $aRow['id'], $icon, 'btn-default', ['onclick' => 'edit_resource_group(this,' . $aRow['id'] . '); return false', 'data-group_name' => $aRow['group_name'],'data-icon' => $aRow['icon'],]).' <a href="#" onclick="edit_resource_group(this,' . $aRow['id'] . '); return false" data-group_name="' . $aRow['group_name'] . '", data-icon="' . $aRow['icon'] . '", data-description="' . $aRow['description'] . '">' . $_data . '</a>';
        }elseif($aColumns[$i] == 'creator'){
            $_data = '<a href="' . admin_url('staff/profile/' . $aRow['creator']) . '">' . staff_profile_image($aRow['creator'], [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['creator']) . '">' . get_staff_full_name($aRow['creator']) . '</a>';
        }elseif ($aColumns[$i] == 'date_create') {
            $_data = _d($aRow['date_create']);
        }
        $row[] = $_data;
    }
    $options = icon_btn('resource_booking/resource_group/' . $aRow['id'], 'pencil-square-o', 'btn-default', ['onclick' => 'edit_resource_group(this,' . $aRow['id'] . '); return false', 'data-group_name' => $aRow['group_name'],'data-icon' => $aRow['icon'],'data-description' => $aRow['description'],]);
    $options .= icon_btn('resource_booking/delete_resource_group/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[] = $options;

    $output['aaData'][] = $row;
}
