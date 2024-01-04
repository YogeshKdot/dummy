<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI     = & get_instance();
$status = $CI->input->post('status');
$sortby = $CI->input->post('sortby');
$cat_ids_arr = $CI->input->post('cat_ids_arr');
$aColumns = [
                db_prefix() . 'idea_hub_challenges.id',
				'title',
				db_prefix(). 'idea_hub_category.name',
				'deadline',
				'status',
				'user_id',
				db_prefix() . 'idea_hub_challenges.added_at'
			];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'idea_hub_challenges';
$where        = [];
$join 		  = [
				    'LEFT JOIN ' . db_prefix() . 'idea_hub_category ON ' . db_prefix() . 'idea_hub_category.id = '.$sTable.'.category_id'
				];
if(!$CI->input->post('include_archieved') && is_admin()){
	array_push($where, 'AND '.$sTable.'.status != "archived"');
}

if(!is_admin()){
	if($CI->input->post('include_archieved')){
		$arc = 'OR '.db_prefix() . 'idea_hub_challenges.status = "archived" ';
	}else{
		$arc = '';
	}
	array_push($where, 'AND (' . db_prefix() . 'idea_hub_challenges.status != "archived" '.$arc.')');
	if(!has_permission('idea_hub', '', 'view')){
		array_push($where, 'AND ' .db_prefix(). 'idea_hub_challenges.user_id = '.get_staff_user_id());
	}
}

if(!empty($cat_ids_arr)){
	array_push($where, 'AND '.$sTable.'.category_id IN('.$cat_ids_arr.')');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
		db_prefix(). 'idea_hub_challenges.id',
		db_prefix(). 'idea_hub_category.name as category'
		]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'title') {
            $_data = '<a href="' . admin_url('idea_hub/ideas/' . $aRow['id']) . '">' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            
            if (is_admin() || (has_permission('idea_hub', '', 'edit') && get_staff_user_id() == $aRow['user_id'])) {
                $_data .= ' <a href="' . admin_url('idea_hub/challenge/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }
            if (is_admin() || (has_permission('idea_hub', '', 'delete') && get_staff_user_id() == $aRow['user_id'])) {
                $_data .= ' | <a href="' . admin_url('idea_hub/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'category_id') {
            $_data = '<span style="background-color: '.$aRow['color'].'" title="'._l('category').'">'.$aRow['name'].'</span>';
        } elseif ($aColumns[$i] == 'status') {
        	$_data = strtoupper($aRow['status']);
        }elseif ($aColumns[$i] == 'user_id') {
            $oStaff = $this->ci->staff_model->get($_data);
            $_data =  staff_profile_image($oStaff->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')). '<a href="'.admin_url('profile/'.$oStaff->staffid).'">'.$oStaff->firstname.' '. $oStaff->lastname. '</a><br>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}