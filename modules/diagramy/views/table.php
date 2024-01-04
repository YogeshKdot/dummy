<?php

$CI            = &get_instance();
$custom_fields = get_table_custom_fields('diagramy');

$CI->db->query("SET sql_mode = ''");
$aColumns = [
    'title',
    db_prefix().'diagramy.description',
    'staffid',
    db_prefix().'diagramy_groups.name',
    db_prefix().'diagramy.dateadded',
    'related_to',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'diagramy';
$join         = [
    'LEFT JOIN '.db_prefix().'diagramy_groups ON '.db_prefix().'diagramy_groups.id = '.db_prefix().'diagramy.diagramy_group_id',
    'LEFT JOIN '.db_prefix().'projects ON '.db_prefix().'projects.id = '.db_prefix().'diagramy.rel_id',
    'LEFT JOIN '.db_prefix().'tasks ON '.db_prefix().'tasks.id = '.db_prefix().'diagramy.rel_id',
];
$where        = [];
// Add blank where all filter can be stored
$filter = [];

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_'.$key : 'cvalue_'.$key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_'.$key.'.value as '.$selectAs);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_'.$key.' ON '.db_prefix().'clients.userid = ctable_'.$key.'.relid AND ctable_'.$key.'.fieldto="'.$field['fieldto'].'" AND ctable_'.$key.'.fieldid='.$field['id']);
}

$join = hooks()->apply_filters('diagramy_table_sql_join', $join);

// Filter by Staff
$staffIds = [];
if ($CI->input->post('assigned')) {
    array_push($staffIds, $CI->input->post('assigned'));
}

if (count($staffIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'diagramy.staffid IN ('.implode(', ', $staffIds).')');
}

// Filter by Group
$groupIds = [];
if ($CI->input->post('group')) {
    array_push($groupIds, $CI->input->post('group'));
}

if (count($groupIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'diagramy.diagramy_group_id IN ('.implode(', ', $groupIds).')');
}

if (count($filter) > 0) {
    array_push($where, 'AND ('.prepare_dt_filter($filter).')');
}
if ($CI->input->post('my_mindmap')) {
    array_push($where, 'AND '.db_prefix().'diagramy.staffid = '.get_staff_user_id());
}

$aColumns = hooks()->apply_filters('diagramy_table_sql_columns', $aColumns);
// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$CI->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix().'diagramy.id',
    db_prefix().'diagramy.id',
    db_prefix().'projects.name as projects_name',
    db_prefix().'projects.id as projects_id',
    db_prefix().'tasks.id as tasks_id',
    db_prefix().'tasks.name as tasks_name',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); ++$i) {
        $_data = $aRow[$aColumns[$i]];
        if ('title' == $aColumns[$i]) {
            $hrefAttr = 'href="'.admin_url('diagramy/index/'.$aRow['id']).'" onclick="init_diagramy_modal('.$aRow['id'].');return false;"';

            $_data = '<a href="'.admin_url('diagramy/preview/'.$aRow['id']).'" >'.$_data.'</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a '.$hrefAttr.'>'._l('diagramy_view').'</a>';
            $_data .= ' | <a href="'.admin_url('diagramy/diagramy_create/'.$aRow['id']).'">'._l('diagramy_edit').'</a>';

            if (has_permission('diagramy', '', 'delete')) {
                $_data .= ' | <a href="'.admin_url('diagramy/delete/'.$aRow['id']).'" class="text-danger _delete">'._l('diagramy_delete').'</a>';
            }
            $_data .= '</div>';
        } elseif ($aColumns[$i] == db_prefix().'diagramy.description' || 'description' == $aColumns[$i]) {
            $_data = $_data;
        } elseif ('staffid' == $aColumns[$i]) {
            if ($_data > 0) {
                $oStaff = $this->ci->staff_model->get($_data);
                $_data  = staff_profile_image($oStaff->staffid, ['img', 'img-responsive', 'staff-profile-image-small', 'pull-left']).'<a href="'.admin_url('profile/'.$oStaff->staffid).'">'.$oStaff->firstname.' '.$oStaff->lastname.'</a><br>';
            } else {
                $_data = '';
            }
        } elseif ($aColumns[$i] == db_prefix().'diagramy.dateadded' || 'dateadded' == $aColumns[$i]) {
            $_data = _dt($_data);
        } elseif ('related_to' == $aColumns[$i]) {
            if ('project' == $aRow['related_to']) {
                $_data= '<span class="label label inline-block project-status-1" style="color:gray;border:1px solid gray">'._l($aRow['related_to']).'</span>'.'<a href="'.admin_url('projects/view/').$aRow['projects_id'].'"> '.$aRow['projects_name'].'</a>';
            } elseif ('task' == $aRow['related_to']) {
                $_data= '<span class="label label inline-block project-status-1" style="color:gray;border:1px solid gray">'._l($aRow['related_to']).'</span>'.'<a href="'.admin_url('tasks/view/').$aRow['tasks_id'].'"> '.$aRow['tasks_name'].'</a>';
            }
        } else {
            $_data = $_data;
        }
        $row[] = $_data;
    }
    ob_start(); ?>

    <?php
    $progress = ob_get_contents();
    ob_end_clean();
    $row[]              = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
