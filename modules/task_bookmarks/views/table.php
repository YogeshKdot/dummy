<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tbltask_bookmarks.id',
    'tbltask_bookmarks.name',
    'creator',
    ];
$sIndexColumn = 'id';
$sTable       = 'tbltask_bookmarks';
$join = ['Left join tbllist_widget on tbllist_widget.rel_id = tbltask_bookmarks.id and tbllist_widget.rel_type = "task_bookmarks"'];
$where = ['where tbltask_bookmarks.creator = '.get_staff_user_id()];   
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['tbltask_bookmarks.id','tbllist_widget.id as id','tbllist_widget.add_from', 'color','icon']);

$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tbltask_bookmarks.name') {
            $_data = '<a href="#" onclick="edit_task_bookmarks(this,' . htmlspecialchars($aRow['tbltask_bookmarks.id']) . '); return false" data-name="' . htmlspecialchars($aRow['tbltask_bookmarks.name']) . '">' . htmlspecialchars($_data) . '</a>';
        }
        elseif($aColumns[$i] == 'creator'){
            $_data = '<a href="' . admin_url('staff/profile/' . htmlspecialchars($aRow['creator'])) . '">' . staff_profile_image(htmlspecialchars($aRow['creator']), [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/member/' . htmlspecialchars($aRow['creator'])) . '">' . get_staff_full_name(htmlspecialchars($aRow['creator'])) . '</a>';
        }
        $row[] = $_data;
    }


    $icon = '<i class="fa ' . htmlspecialchars($aRow['icon']) . '" style="font-size: 30px; color: '.htmlspecialchars($aRow['color']).';"></i>';
    $row[] = $icon; 

    $options = icon_btn('task_bookmarks/view_task_bookmarks/' . htmlspecialchars($aRow['tbltask_bookmarks.id']), 'eye', 'btn-default', ["data-toggle"=>"tooltip", "title"=> _l('view_task_bookmarks')]);
    $options .= icon_btn('#', 'pencil-square-o', 'btn-default', [
        'onclick' => 'edit_task_bookmarks(this,' . htmlspecialchars($aRow['tbltask_bookmarks.id']) . '); return false', 'data-name' => $aRow['tbltask_bookmarks.name'], 'data-icon' => $aRow['icon'], 'data-color' => $aRow['color'], "data-toggle"=>"tooltip", "title"=>_l('edit_task_bookmarks')
        ]);
    $options .= icon_btn('task_bookmarks/delete_task_bookmarks/' . htmlspecialchars($aRow['tbltask_bookmarks.id']), 'remove', 'btn-danger _delete', ["data-toggle"=>"tooltip", "title"=>_l('delete_task_bookmarks')]);

    if(is_numeric($aRow['id']) && $aRow['add_from'] == get_staff_user_id()){
        $row[] = $options .= icon_btn('#', 'compress', 'btn-danger', ["data-toggle"=>"tooltip", "title"=>_l('added_to_dashboard'), "onclick" => "remove_dashboard(".htmlspecialchars($aRow['tbltask_bookmarks.id']).")"]);
    }else{
        $row[] = $options .= icon_btn('#', 'external-link', 'btn-success', ["data-toggle"=>"tooltip", "title"=>_l('add_dashboard'), "onclick" => "add_dashboard(".htmlspecialchars($aRow['tbltask_bookmarks.id']).")"]);
    }
    $output['aaData'][] = $row;
}
