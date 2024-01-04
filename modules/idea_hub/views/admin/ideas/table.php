<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI          = & get_instance();
$CI->db->query("SET sql_mode = ''");
$aColumns = [
                db_prefix().'idea_hub_ideas.id as idea_id',
                db_prefix().'idea_hub_ideas.title as title',
                'image',
                'status_id',
                '(select sum('.db_prefix().'idea_hub_ideas_votes.rank) from '.db_prefix().'idea_hub_ideas_votes WHERE idea_id = '.db_prefix().'idea_hub_ideas.id) as points',
                db_prefix().'idea_hub_stages.id as stage_id',
                db_prefix().'idea_hub_category.name as category',
                '(select count('.db_prefix().'idea_hub_ideas_comments.id) from '.db_prefix().'idea_hub_ideas_comments WHERE idea_id = '.db_prefix().'idea_hub_ideas.id) as comments',
                db_prefix().'idea_hub_ideas.user_id as user_id',
                db_prefix().'idea_hub_ideas.added_at as ch_added_at'
            ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'idea_hub_ideas';
$where        = [];
$filter       = [];

$join         = [
                    'LEFT JOIN ' . db_prefix() . 'idea_hub_challenges ON ' . db_prefix() . 'idea_hub_ideas.challenge_id = ' . db_prefix() . 'idea_hub_challenges.id',
                    'LEFT JOIN ' . db_prefix() . 'idea_hub_status ON ' . db_prefix() . 'idea_hub_ideas.status_id = ' . db_prefix() . 'idea_hub_status.id',
                    'LEFT JOIN ' . db_prefix() . 'idea_hub_stages ON ' . db_prefix() . 'idea_hub_ideas.stage_id = ' . db_prefix() . 'idea_hub_stages.id',
                    'LEFT JOIN ' . db_prefix() . 'idea_hub_category ON ' . db_prefix() . 'idea_hub_challenges.category_id = ' . db_prefix() . 'idea_hub_category.id'
                ];
if(!is_admin()){
	if(has_permission('idea_hub', '', 'view')){
        array_push($where, 'AND (visibility = "public" OR visibility = "custom" OR visibility = "private")');
	}else if(has_permission('idea_hub', '', 'view_own')){
        array_push($where, 'AND ' .db_prefix(). 'idea_hub_ideas.user_id = '.get_staff_user_id());	
	} 
}
array_push($where, 'AND challenge_id = ' . $challenge_id);
if(!empty($catids)) {
  array_push($where, 'AND '.db_prefix() . 'idea_hub_challenges.category_id IN (' . implode(',', $catids) . ')');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'idea_hub_ideas.id', db_prefix().'idea_hub_status.name as status_name', db_prefix().'idea_hub_ideas.challenge_id', db_prefix().'idea_hub_challenges.user_id as ch_created_by'],'GROUP BY '.db_prefix().'idea_hub_ideas.id');
$output  = $result['output'];
$rResult = $result['rResult'];
unset($aColumns[0]);$aColumns[0] = 'idea_id';
unset($aColumns[1]);$aColumns[1] = 'title';
unset($aColumns[4]);$aColumns[4] = 'points';
unset($aColumns[5]);$aColumns[5] = 'stage_id';
unset($aColumns[6]);$aColumns[6] = 'category';
unset($aColumns[7]);$aColumns[7] = 'comments';
unset($aColumns[8]);$aColumns[8] = 'user_id';
unset($aColumns[9]);$aColumns[9] = 'ch_added_at';
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'status') {
            $_data = ucwords($_data);
        }elseif ($aColumns[$i] == 'user_id') {
            $oStaff = $this->ci->staff_model->get($_data);
            $_data =  staff_profile_image($oStaff->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')). '<a href="'.admin_url('profile/'.$oStaff->staffid).'">'.$oStaff->firstname.' '. $oStaff->lastname. '</a><br>';
        }elseif ($aColumns[$i] == 'image') {
            $imagePath = $this->ci->idea_hub_model->getCoverImagePath($aRow['id']);
            $_data =  '<img src="'.$imagePath.'" class="img-responsive staff-profile-image-small pull-left" width="60px" height="60px">';
        }elseif ($aColumns[$i] == 'status_id') {
            $_data = $aRow['status_name'];
        }elseif ($aColumns[$i] == 'title') {
            $_data = '<a href="' . admin_url('idea_hub/idea_detail/' . $aRow['id']) . '" >' . $aRow['title'] . '</a>';
            $_data .= '<div class="row-options">';
            if (is_admin() || (has_permission('idea_hub', '', 'edit') && get_staff_user_id() == $aRow['user_id'])) {
                $_data .= '  <a href="' . admin_url('idea_hub/idea/' . $aRow['challenge_id'] . '/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }
            if (is_admin() || (has_permission('idea_hub', '', 'delete') && get_staff_user_id() == $aRow['user_id'])) {
                $_data .= ' | <a href="' . admin_url('idea_hub/delete_idea/' . $aRow['id'].'/'.$challenge_id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        }elseif ($aColumns[$i] == 'stage_id') {
            $stage = get_stage_by_id($_data);
            $_data = '<p><span style="background-color:'.$stage['color'].';color: white;padding:6px 10px;border-radius: 25px;font-size: 12px;">'.$stage['name'].'</span></p>';
        }
        $row[] = $_data;
    }
    ob_start();
    $progress = ob_get_contents();
    ob_end_clean();
    $row[]              = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}