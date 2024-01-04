<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Okr model
 */
class Okr_model extends App_Model
{
    //create
    /**
     * setting circulation
     * @param  array $data
     * @return $insert_id       
     */
    public function setting_circulation($data)
    {
        $data['to_date'] = to_sql_date($data['to_date']);
        $data['from_date'] = to_sql_date($data['from_date']);
        $this->db->insert('okr_setting_circulation', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**
     * setting question
     * @param  array $data 
     * @return $insert_id       
     */
    public function setting_question($data)
    {
        $this->db->insert('okr_setting_question', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**
     * setting question
     * @param  array $data 
     * @return $insert_id       
     */
    public function setting_unit($data)
    {
        $this->db->insert('okr_setting_unit', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**
     * setting category
     * @param  array $data 
     * @return $insert_id       
     */
    public function setting_category($data)
    {
        $this->db->insert('okr_setting_category', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**
     * setting evaluation criteria
     * @param  array $data 
     * @return $insert_id       
     */
    public function setting_evaluation_criteria($data)
    {
        $this->db->insert('okr_setting_evaluation_criteria', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**
     * new okrs main
     * @param  array $data 
     * @return $insert_id       
     */
    public function new_okrs_main($data)
    {
        $main_results = '';
        $target = '';
        $departments = '';
        $unit = '';
        $plan = '';
        $results = '';

        if(isset($data['main_results'])){
            $main_results = $data['main_results'];
            unset($data['main_results']);
        }
        if(isset($data['target'])){
            $target = $data['target'];
            unset($data['target']);
        }
        if(isset($data['unit'])){
            $unit = $data['unit'];
            unset($data['unit']);
        }

        if(isset($data['plan'])){
            $plan = $data['plan'];
            unset($data['plan']);
        }

        if(isset($data['results'])){
            $results = $data['results'];
            unset($data['results']);
        }

        if(isset($data['okr_cross'])){
            $data['okr_cross'] = implode(',', $data['okr_cross']);
        }
        $data['creator'] = get_staff_user_id();
        $data['datecreator'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix().'okrs', $data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            $this->notifications($data['person_assigned'],'okr/show_detail_node/'.$insert_id, _l('designates_you_as_the_okr_manager'));
            if(count($main_results) > 0){
                foreach ($main_results as $key => $value) {
                    $this->db->insert(db_prefix().'okrs_key_result', [
                        'okrs_id' => $insert_id,
                        'main_results' => $value,
                        'target' => $target[$key],
                        'unit' => $unit[$key],
                        'plan' => $plan[$key],
                        'results' => $results[$key],
                    ]);
                }
            }
        }
        return $insert_id;
    }

    //update
    /**
     * update setting circulation
     * @param  array $data 
     * @param  integer $id   
     * @return bolean       
     */
    public function update_setting_circulation($data, $id){
        $data['to_date'] = to_sql_date($data['to_date']);
        $data['from_date'] = to_sql_date($data['from_date']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'okr_setting_circulation', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /**
     * update setting question
     * @param  array $data 
     * @param  integer $id   
     * @return bolean       
     */
    public function update_setting_question($data, $id){
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'okr_setting_question', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /**
     * update setting unit
     * @param  array $data 
     * @param  integer $id   
     * @return bolean       
     */
    public function update_setting_unit($data, $id){
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'okr_setting_unit', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /**
     * update setting category
     * @param  array $data 
     * @param  integer $id   
     * @return bolean       
     */
    public function update_setting_category($data, $id){
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'okr_setting_category', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * update setting evaluation criteria
     * @param  array $data 
     * @param  integer $id   
     * @return bolean       
     */
    public function update_setting_evaluation_criteria($data, $id){
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'okr_setting_evaluation_criteria', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /**
     * update okrs main
     * @param  array $data 
     * @param  integer $id   
     * @return bolean       
     */
    public function update_okrs_main($data, $id)
    {
        $main_results = '';
        $target = '';
        $departments = '';
        $unit = '';
        $plan = '';
        $results = '';

        if(isset($data['main_results'])){
            $main_results = $data['main_results'];
            unset($data['main_results']);
        }
        if(isset($data['target'])){
            $target = $data['target'];
            unset($data['target']);
        }
        if(isset($data['unit'])){
            $unit = $data['unit'];
            unset($data['unit']);
        }

        if(isset($data['plan'])){
            $plan = $data['plan'];
            unset($data['plan']);
        }

        if(isset($data['results'])){
            $results = $data['results'];
            unset($data['results']);
        }

        if(isset($data['okr_cross'])){
            $data['okr_cross'] = implode(',', $data['okr_cross']);
        }  

       
        $data['creator'] = get_staff_user_id();
        $change = $this->get_okrs($id)->change;
        $data['change'] = $change + 1;
        $data['datecreator'] = date('Y-m-d H:i:s');
        if($data['okr_superior'] && $data['okr_superior'] != ''){
            $okr_superior_check = $this->get_okrs($data['okr_superior']);
        }
        if(isset($okr_superior_check)){
            $rs = $this->dq_v101($okr_superior_check);
            if(in_array($id, $rs) || $id == $data['okr_superior']){
                return 0;
            }
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'okrs', $data);


        //insert log edit okrs
        $data['editor'] = $data['creator'];
        unset($data['creator']);
        unset($data['datecreator']);

        $this->db->insert(db_prefix().'okrs_log', $data);
        $editor = get_staff_user_id();
        if(count($main_results) > 0){
            $this->db->where('okrs_id', $id);
            $results_ = $this->db->get(db_prefix().'okrs_key_result')->result_array();

            if(count($results_) > 0){
                $results_[0]['status'] = 'old';
                $results_[0]['editor'] = $editor;
                unset($results_[0]['datecreator']);
                unset($results_[0]['id']);
                $this->db->insert(db_prefix().'okrs_key_result_log', $results_[0]);
            }
            $this->db->where('okrs_id', $id);
            $this->db->delete(db_prefix().'okrs_key_result');
            
            foreach ($main_results as $key => $value) {
                $this->db->insert(db_prefix().'okrs_key_result', [
                    'okrs_id' => $id,
                    'main_results' => $value,
                    'target' => $target[$key],
                    'unit' => $unit[$key],
                    'plan' => $plan[$key],
                    'results' => $results[$key],
                ]);

                //insert log edit okrs
                $this->db->insert(db_prefix().'okrs_key_result_log', [
                    'okrs_id' => $id,
                    'main_results' => $value,
                    'target' => $target[$key],
                    'unit' => $unit[$key],
                    'plan' => $plan[$key],
                    'editor' => $editor,
                    'status' => 'new',
                    'results' => $results[$key],
                ]);

            }
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    //delete
    /**
     * delete setting circulation
     * @param  integer $id 
     * @return bolean     
     */
    public function delete_setting_circulation($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'okr_setting_circulation');
        if ($this->db->affected_rows() > 0) {           
            return true;
        }
        return false;
    }
    /**
     * delete setting question
     * @param  integer $id 
     * @return bolean     
     */
    public function delete_setting_question($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'okr_setting_question');
        if ($this->db->affected_rows() > 0) {           
            return true;
        }
        return false;
    }
    /**
     * delete setting unit
     * @param  integer $id 
     * @return bolean     
     */
    public function delete_setting_unit($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'okr_setting_unit');
        if ($this->db->affected_rows() > 0) {           
            return true;
        }
        return false;
    }
    /**
     * delete setting category
     * @param  integer $id 
     * @return bolean     
     */
    public function delete_setting_category($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'okr_setting_category');
        if ($this->db->affected_rows() > 0) {           
            return true;
        }
        return false;
    }
    /**
     * delete setting evaluation criteria
     * @param  integer $id 
     * @return bolean     
     */
    public function delete_setting_evaluation_criteria($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'okr_setting_evaluation_criteria');
        if ($this->db->affected_rows() > 0) {           
            return true;
        }
        return false;
    }

    //get
    /**
     * get circulation
     * @param  string $id 
     * @return bolean     
     */
    public function get_circulation($id = '')
    {
        if($id != ''){
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'okr_setting_circulation')->row();
        }   
        return $this->db->get(db_prefix().'okr_setting_circulation')->result_array();
    }
    /**
     * get okrs
     * @param  string $id 
     * @return bolean     
     */
    public function get_okrs($id = '')
    {
        if($id != ''){
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'okrs')->row();
        }   
        return $this->db->get(db_prefix().'okrs')->result_array();
    }
    
    /**
     * display json tree okrs
     * @return $html 
     */
    public function display_json_tree_okrs($flag = ''){
        $okrs = $this->get_okrs();
        $root = $this->get_node_root($flag);

        $json = [];
        $html = '';
        if(count($root) > 0){
            foreach ($root as $key => $okr) {
                $html .= $this->dq_html('', $okr);
            }
        }

        return $html;
    }
    /**
     * check node child
     * @param  integer $okr 
     * @return bolean      
     */
    public function check_node_child($okr){
        $okrs = $this->get_okrs($okr);
        if($okrs->okr_superior != ''){
            return true;
        }
        return false;
    }
    /**
     * get node root
     * @return $root 
     */
    public function get_node_root($flag = '')
    {
        $okrs = $this->get_okrs();
        $root = [];
        foreach ($okrs as $key => $value) {

            if($flag != ''){
                if(($value['okr_superior'] == '' || is_null($value['okr_superior'])) && $value['circulation'] == $flag){
                    $root[] = $value;
                }
            }else{
                if($value['okr_superior'] == '' || is_null($value['okr_superior'])){
                    $root[] = $value;
                }
            }
        }


        return $root;
    }
    /**
     * dq html
     * @param  string $html   
     * @param  array $node   
     * @return $html         
     */
    public function dq_html($html, $node){
        $key_results = $this->count_key_results($node['id']);
       
        $progress = $this->okr_model->get_okrs($node['id'])->progress;
        if(is_null($progress)){
            $progress = 0;
        }
        $display = '';
        if($node['display'] == 2){
            $display = 'hide';
        }
        if($node['person_assigned'] == get_staff_user_id() || is_admin()){
            $display = '';
        }
        $full_name = 
        '<div class="pull-right">'.staff_profile_image($node['person_assigned'],['img img-responsive staff-profile-image-small pull-left']).' <a href="#" class="pull-left name_class">'.get_staff_full_name($node['person_assigned']).'</a> </div>';

        $rattings = '
        <div class="progress no-margin progress-bar-mini cus_tran">
                  <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="'.$progress.'%;" data-percent="'.$progress.'">
                  </div>
               </div>
               '.$progress.'%
       </div>
        ';  

        $category = category_view($node['category']);
        $type = $node['type'] != '' ? ($node['type'] == 1 ? _l('personal') : ($node['type'] == 2 ? _l('department') : _l('company'))) : '';

        $department = $node['department'] != '' && $node['department'] != 0 ? get_department_name_of_okrs($node['department'])->name : '';


        if($node['status'] == 0){
            $status = '<span class="label label-warning s-status ">'._l('unfinished').'</span>';
        }else{
            $status = '<span class="label label-success s-status ">'._l('finish').'</span>';
        }

        $option = '';
        $option .= '<a href="' . admin_url('okr/show_detail_node/' . $node['id']) . '" class="btn btn-default btn-icon">';
        $option .= '<i class="fa fa-eye"></i>';
        $option .= '</a>';
        if($this->okr_model->get_okrs($node['id'])->status != 1){
            if(has_permission('okr','','edit') || is_admin()){ 
            $option .= '<a href="'.admin_url('okr/new_object_main/'.$node['id']).'" class="btn btn-default btn-icon">';
            $option .= '<i class="fa fa-edit"></i>';
            $option .= '</a>';
            }
        }
        if(has_permission('okr','','delete') || is_admin()){ 
        $option .= '<a href="' . admin_url('okr/delete_okrs/'.$node['id']) . '" class="btn btn-danger btn-icon _delete">';
        $option .= '<i class="fa fa-remove"></i>';
        $option .= '</a>';
        }
        $row[] = $option; 
        if($node['okr_superior'] == ''){
        $html .= 
        '<tr class="treegrid-'.$node['id'].' expanded '.$display.'" >
            <td class="text-left "><a href="#" class="trigger" data-id="'.$node['person_assigned'].'" ><div class="okr__">'.staff_profile_image($node['person_assigned'],['img staff-profile-image-small ']).'</a> <span class="your-target-content">'.$node['your_target'].'</span></div></td>
            <td><div class="box effect8" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content="">
                <span>'.$key_results->count.' '._l('key_results').'</span>
                </div>
            </td>
            <td class="text-danger">+'.$node['change'].'</td>
            <td>'.$rattings.'  </td>
            <td>'.$category.'  </td>
            <td>'.$type.'  </td>
            <td>'.$department.'  </td>
            <td>'.$status.'</td>';
        if(has_permission('okr','','edit') || is_admin() || has_permission('okr','','delete')){ 
            $html .= '<td>'.$option.'</td>';
        }
        $html .= '</tr>';
        }else{
            $html .= '
            <tr class="treegrid-'.$node['id'].' treegrid-parent-'.$node['okr_superior'].' '.$display.'" >
                <td class="text-left "><a href="#" class="trigger" data-id="'.$node['person_assigned'].'" ><div class="okr__">'.staff_profile_image($node['person_assigned'],['img staff-profile-image-small ']).'</a> <span class="your-target-content">'.$node['your_target'].'</span></div></td>
                <td><div class="box effect8" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content="">
                    <span>'.$key_results->count.' '._l('key_results').'</span>
                    </div>
                </td>
                <td class="text-danger">+'.$node['change'].'</td>
                <td>'.$rattings.'</td>
                <td>'.$category.'  </td>
                <td>'.$type.'  </td>
                <td>'.$department.'  </td>
                <td>'.$status.'</td>';
                if(has_permission('okr','','edit') || is_admin() || has_permission('okr','','delete')){ 
                    $html .= '<td>'.$option.'</td>';
                }
        $html .= '</tr>';
        }
        $this->db->where('okr_superior', $node['id']);
        $child_note = $this->db->get(db_prefix().'okrs')->result_array();

        if(count($child_note) > 0){
            $html_ = '';
            foreach ($child_note as $key => $value) {
                $html_ .= $this->dq_html('', $value);
            }
            $html .= $html_;
        }

        return $html;
    }
    /**
     * get okrs detailt
     * @param  integer $okr 
     * @return $results      
     */
    public function get_okrs_detailt($okr){
        $this->db->where('okrs_id', $okr);
        $key_results = $this->db->get(db_prefix().'okrs_key_result')->result_array();
        $object = $this->get_okrs($okr);
        $results['object'] = $object;
        $results['key_results'] = $key_results;
        return $results;
    }

    /**
     * chart tree okrs
     * @return json 
     */
    public function chart_tree_okrs($flag = ''){
        $root = $this->get_node_root($flag);
        $okrs = $this->get_okrs();
        $json = [];
        if(count($root) > 0){
            foreach ($root as $key => $okr) {
                $json[] = $this->dq_json($okr, []);
            }
        }
        return json_encode($json);
    }

    /**
     * dq json
     * @param  array $node   
     * @param  array $array_ 
     * @return $array         
     */
    public function dq_json($node, $array_){
        $data_popover = $this->objective_show($node['id']);
        $progress = $this->okr_model->get_okrs($node['id'])->progress;
        if(is_null($progress)){
            $progress = 0;
        }
        $test = '
            <div class="progress-json">
               <div class="project-progress relative" data-value="'.($progress/100).'" data-size="55" data-thickness="5">
                  <strong class="okr-percent"></strong>
               </div>
               <span >'._l('progress').'</span>
            </div>

        ';

        $display = '';
        if($node['display'] == 2){
            $display = 'hide';
        }
        if($node['person_assigned'] == get_staff_user_id()){
            $display = '';
        }

        $count_key_results = $this->count_key_results($node['id']);
        $rattings = '<div class="devicer">';
            $rattings .= $test;
            $rattings .= '<div class="box-json">';
            if($count_key_results->count >0){
                $rattings .= '<div class="demo_box" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content=""><div class="bg-1 pull-right"><span class="rate-box-value-1">'.$count_key_results->count.'</span></div></div>';
            }else{
                $rattings .= '<div class="demo_box" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content="" > <div class="bg-2"><span class="rate-box-value-2">'.$count_key_results->count.'</span></div></div>';
            }
            $rattings .= '<span>'._l('key_results').'</span>';
            $rattings .= '</div>';

        $rattings .= '</div>';

        if($display == 'hide'){
            $rattings = '';
        }
        $role = get_role_name_staff($node['person_assigned']);
        $name = '<a href="'.admin_url('okr/show_detail_node/'.$node['id']).'">'.$node['your_target'].'</a>';   
        if($display == 'hide'){
            $name = '<i class="fa fa-lock lagre-lock" aria-hidden="true"></i>';
        }     
        $title = '<div class="position-absolute mleft-22"><a href="#" class="name_class_chart">'.get_staff_full_name($node['person_assigned']).'</a><div class="role_name">'.$role.'</div></div>';
        $image = staff_profile_image($node['person_assigned'],['img img-responsive staff-profile-image-small pull-left position-absolute']);
        $array = array('name' => $name, 'title' => $title, 'job_position_name' => '', 'dp_user_icon' => $rattings, 'display' => $display,'image' => $image);
       
        $this->db->where('okr_superior', $node['id']);
        $child_node = $this->db->get(db_prefix().'okrs')->result_array();

        if(count($child_node) > 0){
            foreach ($child_node as $key => $node_) {
                $array['children'][] = $this->dq_json($node_, []); 
            }
        }

        return $array;
    }
    /**
     * count key results
     * @param  integer $okr   
     * @param  string $where 
     * @return count       
     */
    public function count_key_results($okr, $where = ''){
        return $this->db->query('select count(*) as count from '.db_prefix().'okrs_key_result where okrs_id ='.$okr)->row();
    }
    /**
     * chart tree okrs clone
     * @param  integer $okr_id 
     * @return json         
     */
    public function chart_tree_okrs_clone($okr_id){
        $json = [];
        $this->db->where('id', $okr_id['id']);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json[] = $this->dq_json($okrs[0], []);
        return json_encode($json);
    }
    /**
     * objective show
     * @param  integer $okrs 
     * @return $html       
     */
    public function objective_show($okrs)
    {
        $main = $this->get_okrs($okrs);
        $html = '';
        $html .= '<div class="name_objective"><h4><i class="fa">&#xf247;</i>  '.$main->your_target.'</h4></div>'; 
        $progress = $this->okr_model->get_okrs($okrs)->progress;
        if(is_null($progress)){
            $progress = 0;
        }
        $test = '
               <div class="project-progress relative" data-value="'.($progress/100).'" data-size="50" data-thickness="5">
                  <strong class="okr-percent"></strong>
               </div>
        ';      
        $confidence_level = $this->okr_model->get_okrs($okrs)->confidence_level;
        switch ($confidence_level) {
            case 1:
                $confidence_level_html = '
                    <div class="default">
                        <div class="changed_1">
                            <label>
                              <input type="radio" checked><span> '._l('is_fine').'</span>
                            </label>
                        </div>
                      </div>
                    ';
                break;
                case 2:
                $confidence_level_html = '
                    <div class="default">
                        <label>
                          <input type="radio" checked><span class="default_ct"> '._l('not_so_good') .'</span>
                        </label>
                        
                      </div>
                    ';
                break;
            default:
                $confidence_level_html = '
                    <div class="default">
                        <div class="changed_2">
                            <label>
                              <input type="radio"  checked><span> '. _l('very_good') .'</span>
                            </label>
                        </div>
                      </div>
                    ';
                break;
        }
        $this->db->where('okrs_id', $okrs);
        $objective = $this->db->get(db_prefix().'okrs_key_result')->result_array();
        $html .= '<table border="1">
                   <tr>
                    <th>'._l('main_results').'</th>
                    <th>'._l('target').'</th>
                    <th>'._l('progress').'</th>
                    <th>'._l('confidence_level').'</th>
                    <th>'._l('plan').'</th>
                    <th>'._l('results').'</a></th>
                  </tr>';
        foreach ($objective as $key => $value) {
            $unit_ = (isset($this->get_unit($value['unit'])->unit) ? $this->get_unit($value['unit'])->unit : '');
            $html .= '
                  <tr>
                    <td>'.$value['main_results'].'</td>
                    <td>'.$value['target'].'('.$unit_.')</td>        
                    <td>'.$test.'</td>
                    <td>'.$confidence_level_html.'</td>
                     <td><a href="#" id="plan_view" data-toggle="popover" data-placement="bottom" data-content="'.$value['plan'].'" data-original-title="'._l('plan').'">'.$value['plan'].'</a></td>
                    <td><a href="#" id="results_view" data-toggle="popover" data-placement="bottom" data-content="'.$value['results'].'"  data-original-title="'._l('results').'">'.$value['results'].'</a></td>      
                  </tr>
            ';
        }
        $html .= '</table>';
        return $html;
    }
    /**
     * display json tree checkin
     * @return $html 
     */
    public function display_json_tree_checkin($flag = ''){
        $okrs = $this->get_okrs();
        $root = $this->get_node_root($flag);
        $json = [];
        $html = '';
        foreach ($root as $key => $okr) {
            $html .= $this->dq_html_checkin('', $okr);
        }
        return $html;
    }
    /**
     * dq html checkin
     * @param  string $html 
     * @param  array $node 
     * @return $html       
     */
    public function dq_html_checkin($html, $node){
        $this->load->model('departments_model');
        $progress = $this->okr_model->get_okrs($node['id'])->progress;
        if(is_null($progress)){
            $progress = 0;
        }
        $display = '';
        if($node['display'] == 2){
            $display = 'hide';
        }
        if($node['person_assigned'] == get_staff_user_id() || is_admin()){
            $display = '';
        }
        $confidence_level = $this->okr_model->get_okrs($node['id'])->confidence_level;
        $upcoming_checkin = $this->okr_model->get_okrs($node['id'])->upcoming_checkin;
        $type = $this->okr_model->get_okrs($node['id'])->type;

        $key_results = $this->count_key_results($node['id']);
        $department = $this->departments_model->get_staff_departments($node['person_assigned']);
        $role = get_role_name_staff($node['person_assigned']);
        $department_name = '';
        if(count($department) > 0){
            $department_name = $department[0]['name'];
        }else{
            $department_name = '';
        }
        if(!isset($role)){
            $role = '';
        };

       
        $category = category_view($node['category']);

        $type = $node['type'] != '' ? ($node['type'] == 1 ? _l('personal') : ($node['type'] == 2 ? _l('department') : _l('company'))) : '';

        $department = $node['department'] != '' && $node['department'] != 0 ? get_department_name_of_okrs($node['department'])->name : '';
        $full_name = 
        '<div class="pull-right">'.staff_profile_image($node['person_assigned'],['img img-responsive staff-profile-image-small pull-left']).' <a href="#" class="pull-left name_class">'.get_staff_full_name($node['person_assigned']).'</a> </div>';


        $rattings = '
        <div class="progress no-margin progress-bar-mini cus_tran">
                  <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="'.$progress.'%;" data-percent="'.$progress.'">
                  </div>
               </div>
               '.$progress.'%
       </div>
        ';     
        switch ($confidence_level) {
            case 1:
                $confidence_level_html = '
                    <div class="default">
                        <div class="changed_1">
                            <label>
                              <input type="radio" checked><span> '._l('is_fine').'</span>
                            </label>
                        </div>
                      </div>
                    ';
                break;
                case 2:
                $confidence_level_html = '
                    <div class="default">
                        <label>
                          <input type="radio" checked><span class="default_ct"> '._l('not_so_good') .'</span>
                        </label>
                        
                      </div>
                    ';
                break;
            default:
                $confidence_level_html = '
                    <div class="default">
                        <div class="changed_2">
                            <label>
                              <input type="radio"  checked><span> '. _l('very_good') .'</span>
                            </label>
                        </div>
                      </div>
                    ';
                break;
        }
        $today = date("Y-m-d");

        if (strtotime($today) > strtotime($upcoming_checkin)) {
            $checkin_html_status = '
                <button class="checkin_button2 select-option" data-node="'.$node['id'].'" data-name="'.$node['your_target'].'" data-change="'.$key_results->count.'" data-progress="'.$node['progress'].'"><i class="fa fa-map-marker" aria-hidden="true"></i> 
                    '._l('checkin').'</button>
                ';
          } else if((strtotime($today) < strtotime($upcoming_checkin) )&& $type == 2){
            $checkin_html_status = '
                <button class="checkin_button1 select-option" data-node="'.$node['id'].'" data-name="'.$node['your_target'].'" data-change="'.$key_results->count.'" data-progress="'.$node['progress'].'"><i class="fa fa-map-marker" aria-hidden="true"></i> 
                    '._l('checkin').'</button>
                ';
          }else if((strtotime($today) < strtotime($upcoming_checkin) )&& $type == 1){
            $checkin_html_status = '
                <button class="checkin_button select-option" data-node="'.$node['id'].'" data-name="'.$node['your_target'].'" data-change="'.$key_results->count.'" data-progress="'.$node['progress'].'"><i class="fa fa-map-marker" aria-hidden="true"></i> 
                    '._l('checkin').'</button>
                ';
          }
        if($this->okr_model->get_okrs($node['id'])->status == 1){
            $checkin_html_status = '';
        }
        $option = '';
        $option .= '<a href="'.admin_url('okr/new_object_main/'.$node['id']).'" class="btn btn-default btn-icon">';
        $option .= '<i class="fa fa-edit"></i>';
        $option .= '</a>';
        $row[] = $option; 
        if($node['okr_superior'] == ''){
        $html .= 
        '<tr class="treegrid-'.$node['id'].' expanded '.$display.'" >
            <td class="text-left "><a href="#" class="trigger" data-id="'.$node['person_assigned'].'" ><div class="okr__">'.staff_profile_image($node['person_assigned'],['img staff-profile-image-small ']).'</a> <span class="your-target-content">'.$node['your_target'].'</span></div></td>
            <td><div class="box effect8" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content="">
                <span>'.$key_results->count.' '._l('key_results').'</span>
                </div>
            </td>
            <td>'.$rattings.'</td>
            <td class="text-danger">+ '.$node['change'].'</td>
            <td>'.$confidence_level_html.'</td>
            <td>'.$category.'  </td>
            <td>'.$type.'  </td>
            <td>'.$department.'  </td>
            <td>'.$checkin_html_status.'</td>
            <td>'.$node['recently_checkin'].'</td>
            <td>'.$node['upcoming_checkin'].'</td>
        </tr>';
        }else{
            $html .= '
            <tr class="treegrid-'.$node['id'].' treegrid-parent-'.$node['okr_superior'].' '.$display.'" >
                <td class="text-left "><a href="#" class="trigger" data-id="'.$node['person_assigned'].'"><div class="okr__">'.staff_profile_image($node['person_assigned'],['img staff-profile-image-small ']).'</a> <span class="your-target-content">'.$node['your_target'].'</span></div></td>
                <td><div class="box effect8" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content="">
                    <span>'.$key_results->count.' '._l('key_results').'</span>
                    </div>
                </td>
                <td>'.$rattings.'</td>
                <td class="text-danger">+ '.$node['change'].'</td>
                <td>'.$confidence_level_html.'</td>
                <td>'.$category.'  </td>
                <td>'.$type.'  </td>
                <td>'.$department.'  </td>
                <td>'.$checkin_html_status.'</td>
                <td>'.$node['recently_checkin'].'</td>
                <td>'.$node['upcoming_checkin'].'</td>
            </tr>';
        }
        $this->db->where('okr_superior', $node['id']);
        $child_note = $this->db->get(db_prefix().'okrs')->result_array();

        if(count($child_note) > 0){
            $html_ = '';
            foreach ($child_note as $key => $value) {
                $html_ .= $this->dq_html_checkin('', $value);
            }
            $html .= $html_;
        }

        return $html;
    }
    /**
     * get question
     * @return array 
     */
    public function get_question()
    {
        return $this->db->get(db_prefix().'okr_setting_question')->result_array();
    }
    /**
     * get key result
     * @param  integer $okrs 
     * @return array       
     */
    public function get_key_result($okrs)
    {
        $this->db->where('okrs_id', $okrs);
        return $this->db->get(db_prefix().'okrs_key_result')->result_array();
    }
    /**
     * get evaluation criteria
     * @param  string $type 
     * @return array       
     */
    public function get_evaluation_criteria($type)
    {
        $this->db->where('group_criteria', $type);
        return $this->db->get(db_prefix().'okr_setting_evaluation_criteria')->result_array();
    }
    /**
     * add check in
     * @param array $data 
     */
    public function add_check_in($data){
      $data['recently_checkin'] = to_sql_date($data['recently_checkin']);
      $data['upcoming_checkin'] = to_sql_date($data['upcoming_checkin']);
        if($data){
            $main_results = [];
            $target = [];
            $unit = [];
            $achieved = [];
            $progress = [];
            $confidence_level = [];
            $answer = [];
            if(isset($data['main_results'])){
                $main_results = $data['main_results'];
            }
            if(isset($data['target'])){
                $target = $data['target'];
            }
            if(isset($data['unit'])){
                $unit = $data['unit'];
            }
            if(isset($data['achieved'])){
                $achieved = $data['achieved'];
            }
            if(isset($data['progress'])){
                $progress = $data['progress'];
            }
            if(isset($data['confidence_level'])){
                $confidence_level = $data['confidence_level'];
            }
            if(isset($data['answer'])){
                $answer = $data['answer'];
            }
            if(isset($data['evaluation_criteria'])){
                $evaluation_criteria = $data['evaluation_criteria'];
            }
            if(isset($data['comment'])){
                $comment = $data['comment'];
            }
            if(isset($data['rs_id'])){
                $key_results_id = $data['rs_id'];
            }
            if(isset($data['complete_okrs'])){
                $complete_okrs = 1;
            }else{
                $complete_okrs = 0;
            }
            if(!isset($data['upcoming_checkin'])){
                $data['upcoming_checkin'] = $data['recently_checkin'];
            }
        }
        $count_key_results = count($main_results);
        $arr_id_add = [];
        $total = 0; 
        $array = []; 
        if(count($main_results) > 0){
            $this->db->where('okrs_id', $data['id']);
            $this->db->delete(db_prefix().'okrs_checkin');
            foreach ($main_results as $key => $value) {
                $confidence_level_check = isset($confidence_level[$key])?$confidence_level[$key]:1;
                $data_new = ['okrs_id' => $data['id'], 'main_results' => $value, 'target' => $target[$key], 'achieved' => $achieved[$key], 'progress' => number_format( (float) $progress[$key], 2, '.', ''), 'confidence_level' => $confidence_level_check, 'unit' => $unit[$key], 'answer' => json_encode($answer[$key]), 'evaluation_criteria' => $evaluation_criteria[$key], 'comment' => $comment[$key], 'type' => $data['type'], 'recently_checkin' => $data['recently_checkin'], 'upcoming_checkin' => $data['upcoming_checkin'], 'editor' => get_staff_user_id(), 'key_results_id' => $key_results_id[$key], 'complete_okrs' => $complete_okrs];
                $this->db->insert(db_prefix().'okrs_checkin', $data_new);
                $array[] = $confidence_level_check;
                $insert_id = $this->db->insert_id();
                $arr_id_add[] = $insert_id;
                if($insert_id){
                    $this->db->where('id', $key_results_id[$key]);
                    $this->db->update(db_prefix().'okrs_key_result', ['progress' => $progress[$key], 'achieved' => $achieved[$key], 'confidence_level' => $confidence_level_check]);
                    $total +=  $progress[$key];
                }
            }
            $vals = array_count_values($array); 
            $one = 0;
            $two = 0;
            $three = 0;
            $confidence_level_main = 1;
            foreach ($vals as $in => $val) {
                switch ($in) {
                    case '1':
                        $one = $val;
                        break;
                    case '2':
                        $two = $val;
                        break;
                    default:
                        $three = $val;
                        break;
                }
            }

            $maxValue = $one;
            if($two > $maxValue){
                $confidence_level_main = 2;
            }
            if($three > $maxValue){
                $confidence_level_main = 3;
            }

            $total_progress_main =  ($total/($count_key_results * 100)) * 100;
            if($total_progress_main == 100){
                $complete_okrs = 1;
                foreach ($arr_id_add as $index => $id_add) {
                    $this->db->where('id', $id_add);
                    $this->db->update(db_prefix().'okrs_checkin', ['complete_okrs' => $complete_okrs]);
                }
            }
            $created_date = date('Y-m-d H:i:s');
            foreach ($main_results as $key => $value) {
                $confidence_level_check = isset($confidence_level[$key])?$confidence_level[$key]:1;

                $data_new_log = ['okrs_id' => $data['id'], 'main_results' => $value, 'target' => $target[$key], 'achieved' => $achieved[$key], 'progress' => number_format( (float) $progress[$key], 2, '.', ''), 'confidence_level' => $confidence_level_check, 'unit' => $unit[$key], 'answer' => json_encode($answer[$key]), 'evaluation_criteria' => $evaluation_criteria[$key], 'comment' => $comment[$key], 'type' => $data['type'], 'recently_checkin' => $data['recently_checkin'], 'upcoming_checkin' => $data['upcoming_checkin'], 'editor' => get_staff_user_id(), 'key_results_id' => $key_results_id[$key], 'progress_total' => $total_progress_main, 'complete_okrs' => $complete_okrs, 'created_date' => $created_date];
                  $this->db->insert(db_prefix().'okrs_checkin_log', $data_new_log);
            }

            $this->db->where('id', $data['id']);
            $this->db->update(db_prefix().'okrs', ['progress' => $total_progress_main, 'confidence_level' => $confidence_level_main, 'recently_checkin' => ($data['recently_checkin']), 'upcoming_checkin' => ($data['upcoming_checkin']), 'status' => $complete_okrs, 'type' => $data['type']]);
            return true;
        }
    }
    /**
     * get key result checkin
     * @param  integer $okrs 
     * @return array       
     */
    public function get_key_result_checkin($okrs){
        $this->db->where('okrs_id', $okrs);
        return $this->db->get(db_prefix().'okrs_checkin')->result_array();
    }
    /**
     * get key result checkin log
     * @param  integer $okrs 
     * @return  array     
     */
    public function get_key_result_checkin_log($okrs){
        $this->db->distinct();
        $this->db->select('recently_checkin, progress_total');
        $this->db->where('okrs_id', $okrs);
        return $this->db->get(db_prefix().'okrs_checkin_log')->result_array();
    }
    /**
     * highcharts detailt checkin model
     * @param  integer $okrs 
     * @return $value_final       
     */
    public function highcharts_detailt_checkin_model($okrs){
        $value_final = [];
        $result_checkin_log = $this->get_key_result_checkin_log($okrs);
        if(count($result_checkin_log) > 0){
            foreach ($result_checkin_log as $key => $value) {
                $value_final['recently_checkin'][] = $value['recently_checkin'];
                $value_final['progress_total'][] = (int)$value['progress_total'];
            }
        }
        return $value_final;
    }
    /**
     * display json tree okrs search
     * @param  integer $okr 
     * @return $html      
     */
    public function display_json_tree_okrs_search($okr){
        if($okr == 0){
            return $this->display_json_tree_okrs();
        }
        $this->db->where('id', $okr);
        $array = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        $html = '';
        $html .= $this->dq_html('', $array[0]);
        return $html;
    }
    /**
     * chart tree search
     * @param  integer $okr 
     * @return $json      
     */
    public function chart_tree_search($okr){
        if($okr == 0){
            return $this->chart_tree_okrs1();
        }
        $this->db->where('id', $okr);
        $array = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        $json[] = $this->dq_json($array[0], []);
        return $json;
    }
    /**
     * chart tree okrs1
     * @return $json 
     */
    public function chart_tree_okrs1(){
        $root = $this->get_node_root();
        $okrs = $this->get_okrs();
        $json = [];
        foreach ($root as $key => $okr) {
            $json[] = $this->dq_json($okr, []);
        }
        return $json;
    }
    /**
     * result checkin log
     * @param  integer  $id    
     * @param  string  $flag  
     * @param  integer $count 
     * @return $log or array       
     */
    public function result_checkin_log($id, $flag = '', $count = 1){
        $this->db->where('id', $id);
        $log = $this->db->get(db_prefix().'okrs_checkin_log')->row();
        if($flag != ''){
            return $log;
        }
        $upcoming_checkin = $log->upcoming_checkin;
        $recently_checkin = $log->recently_checkin;
        $okrs_id = $log->okrs_id;
       
        return $this->db->query('SELECT * FROM '.db_prefix().'okrs_checkin_log where recently_checkin = "'.$recently_checkin.'" and upcoming_checkin = "'.$upcoming_checkin.'" limit '.$count.'')->result_array();
    }
    /**
     * get okr staff
     * @param  integer $staffid 
     * @return array          
     */
    public function get_okr_staff($staffid){
        $query = 'SELECT id FROM '.db_prefix().'okrs where person_assigned = '.$staffid.'';
        return $this->db->query($query)->result_array();
    }
    /**
     * display json tree okrs search staff
     * @param  array $arr_okr 
     * @return $html          
     */
    public function display_json_tree_okrs_search_staff($arr_okr){
        $html = '';
        $root = [];
        if(count($arr_okr) > 0){
            foreach ($arr_okr as $key => $okrs) {
                if($okrs == 0){
                    return $this->display_json_tree_okrs();
                }
                $this->db->where('id', $okrs['id']);

                $root[] = $this->db->get(db_prefix().'okrs')->result_array();
            }

            foreach ($root as $key => $okr) {
                $html .= $this->dq_html('', $okr[0]);
            }

        }
        return $html;
    }
    /**
     * chart tree search staff
     * @param  array $arr_okr 
     * @return $json          
     */
    public function chart_tree_search_staff($arr_okr){

        $html = '';
        $root = [];
        $json = [];
        if(count($arr_okr) > 0){
            foreach ($arr_okr as $key => $okrs) {
                if($okrs == 0){
                    return $this->chart_tree_okrs1();
                }
                $this->db->where('id', $okrs['id']);
                $root[] = $this->db->get(db_prefix().'okrs')->result_array();
            }
            foreach ($root as $key => $okr) {

                $json[] = $this->dq_json($okr[0], []);
            }
        }
        
        return $json;
    }

    /**
     * display tree okrs search checkin
     * @param  integer $okr 
     * @return $html     
     */
    public function display_tree_okrs_search_checkin($okr){
        if($okr == 0){
            return $this->display_json_tree_checkin();
        }
        $this->db->where('id', $okr);
        $array = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        $html = '';
        $html .= $this->dq_html_checkin('', $array[0]);
        return $html;
    }
    /**
     * display tree checkin search staff
     * @param  array $arr_okr 
     * @return $html          
     */
    public function display_tree_checkin_search_staff($arr_okr){
        $html = '';
        $root = [];
        if(count($arr_okr) > 0){
            foreach ($arr_okr as $key => $okrs) {
                if($okrs == 0){
                    return $this->display_json_tree_checkin();
                }
                $this->db->where('id', $okrs['id']);
                $root[] = $this->db->get(db_prefix().'okrs')->result_array();
            }
            foreach ($root as $key => $okr) {
                $html .= $this->dq_html_checkin('', $okr[0]);
            }
        }
        return $html;
    }
    /**
     * get progress dashboard
     * @param  string $type 
     * @return json       
     */
    public function get_progress_dashboard($type){
        switch ($type) {
            case 1:
                $progress = '50.00';
                $query = 'SELECT count(*) as count FROM '.db_prefix().'okrs 
                WHERE (recently_checkin <= CAST(DATE(NOW()) AS DATE)
                AND recently_checkin >= CAST((DATE(NOW()) - INTERVAL 7 DAY) AS DATE)) AND `progress` > '.$progress.'';
                break;
            case 2:
                $progress = '50.00';
                $query = 'SELECT count(*) as count FROM '.db_prefix().'okrs 
                WHERE (recently_checkin <= CAST(DATE(NOW()) AS DATE)
                AND recently_checkin >= CAST((DATE(NOW()) - INTERVAL 7 DAY) AS DATE)) AND `progress` < '.$progress.'';
                break;
            default:
                $progress = '50.00';
                $query = 'SELECT count(*) as count FROM '.db_prefix().'okrs 
                WHERE (recently_checkin <= CAST(DATE(NOW()) AS DATE)
                AND recently_checkin >= CAST((DATE(NOW()) - INTERVAL 7 DAY) AS DATE)) AND `progress` >= '.$progress.' and `progress` <= "70.00"';
                break;
        }
        return $this->db->query($query)->row();
    }
    /**
     * checkin status dashboard
     * @return array
     */
    public function checkin_status_dashboard(){
        $query1 = 'SELECT count(*) as count FROM '.db_prefix().'okrs_checkin_log where confidence_level = 1';
        $query2 = 'SELECT count(*) as count FROM '.db_prefix().'okrs_checkin_log where confidence_level = 2';
        $query3 = 'SELECT count(*) as count FROM '.db_prefix().'okrs_checkin_log where confidence_level = 3';

        $is_fine = $this->db->query($query1)->row()->count;
        $not_so_good = $this->db->query($query2)->row()->count;
        $very_good = $this->db->query($query3)->row()->count;

        $total = $is_fine + $not_so_good + $very_good;  
        
        if($total == 0){
            $percent_1 = 0;
            $percent_2 = 0;
            $percent_3 = 0;
        }else{
            $percent_1 = ($is_fine/($total))*100;
            $percent_2 = ($not_so_good/($total))*100;
            $percent_3 = ($very_good/($total))*100;
        }
        return $final = [['name' => _l('is_fine'), 'y' => $percent_1],['name' => _l('not_so_good'), 'y' => $percent_2],['name' => _l('very_good'), 'y' => $percent_3]];
    }
    /**
     * okrs company dasdboard
     * @return array
     */
    public function okrs_company_dasdboard(){
        $query_oks = 'SELECT count(*) as count FROM '.db_prefix().'okrs';
        $query_progress = 'SELECT (sum(progress)/((select count(*) from '.db_prefix().'okrs)*100)*100) as progress  FROM '.db_prefix().'okrs';
        $query_keyres = 'SELECT count(*) as count FROM '.db_prefix().'okrs_key_result';

        $query1 = 'SELECT count(*) as count FROM '.db_prefix().'okrs where confidence_level = 1';
        $query2 = 'SELECT count(*) as count FROM '.db_prefix().'okrs where confidence_level = 2';
        $query3 = 'SELECT count(*) as count FROM '.db_prefix().'okrs where confidence_level = 3';

        $is_fine = $this->db->query($query1)->row()->count;
        $not_so_good = $this->db->query($query2)->row()->count;
        $very_good = $this->db->query($query3)->row()->count;
        $okrs_count = $this->db->query($query_oks)->row()->count;
        $okrs_keyres = $this->db->query($query_keyres)->row()->count;
        $okrs_progress = $this->db->query($query_progress)->row()->progress;
        $total = $is_fine + $not_so_good + $very_good;  
        $percent_1 = ($is_fine/($total))*100;
        $percent_2 = ($not_so_good/($total))*100;
        $percent_3 = ($very_good/($total))*100;

        if($total == 0){
            $total = 3;
            $percent_1 = ($is_fine/($total))*100;
            $percent_2 = ($not_so_good/($total))*100;
            $percent_3 = ($very_good/($total))*100;
        }

        $html = '
              <div class="progress progress_cus_tranform">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.round($percent_3, 2).'"  style="'.$percent_3.'%;" data-percent="'.round($percent_3, 2).'">
                      </div>
                   <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'.round($percent_1, 2).'"  style="'.$percent_1.'%;" data-percent="'.round($percent_1, 2).'">
                      </div>
                   <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="'.round($percent_2, 2).'"  style="'.$percent_2.'%;" data-percent="'.round($percent_2, 2).'">
                      </div>
              </div>
        ';

        $test = '
              <div class="progress progress_cus_tranform">

        <div class="progress-bar" role="progressbar" aria-valuenow="'.(int)$okrs_progress.'"  style="'.$okrs_progress.'%;" data-percent="'.(int)$okrs_progress.'">
                      </div>
                      </div>
        ';
        return ['okrs_count' => $okrs_count, 'okrs_progress' => $test, 'okrs_keyres' => $okrs_keyres, 'html' => $html];
    }
    /**
     * okrs user dasdboard
     * @return array 
     */
    public function okrs_user_dasdboard(){
        $staff_current = get_staff_user_id();
        $query_oks = 'SELECT count(*) as count FROM '.db_prefix().'okrs where person_assigned = '.$staff_current;
        $query_progress = 'SELECT (sum(progress)/((select count(*) from '.db_prefix().'okrs where person_assigned = '.$staff_current.')*100)*100) as progress  FROM '.db_prefix().'okrs';
        $query_keyres = 'SELECT count(*) as count FROM '.db_prefix().'okrs_key_result a left join '.db_prefix().'okrs b ON b.id = a.okrs_id
            where b.person_assigned = 2';

        $query1 = 'SELECT count(*) as count FROM '.db_prefix().'okrs where confidence_level = 1 and person_assigned = '.$staff_current;
        $query2 = 'SELECT count(*) as count FROM '.db_prefix().'okrs where confidence_level = 2 and person_assigned = '.$staff_current;
        $query3 = 'SELECT count(*) as count FROM '.db_prefix().'okrs where confidence_level = 3 and person_assigned = '.$staff_current;

        $is_fine = $this->db->query($query1)->row()->count;
        $not_so_good = $this->db->query($query2)->row()->count;
        $very_good = $this->db->query($query3)->row()->count;
        $okrs_count = $this->db->query($query_oks)->row()->count;
        $okrs_keyres = $this->db->query($query_keyres)->row()->count;
        $okrs_progress = $this->db->query($query_progress)->row()->progress;
        $total = $is_fine + $not_so_good + $very_good;  
        if($total == 0){
            $percent_1 = 0;
            $percent_2 = 0;
            $percent_3 = 0;
        }else{
            $percent_1 = ($is_fine/($total))*100;
            $percent_2 = ($not_so_good/($total))*100;
            $percent_3 = ($very_good/($total))*100;
        }

        $html = '
              <div class="progress progress_cus_tranform">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.round($percent_3, 2).'"  style="'.$percent_3.'%;" data-percent="'.round($percent_3, 2).'">
                      </div>
                   <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'.round($percent_1, 2).'"  style="'.$percent_1.'%;" data-percent="'.round($percent_1, 2).'">
                      </div>
                   <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="'.round($percent_2, 2).'"  style="'.$percent_2.'%;" data-percent="'.round($percent_2, 2).'">
                      </div>
              </div>
        ';

        $test = '
              <div class="progress progress_cus_tranform">

        <div class="progress-bar" role="progressbar" aria-valuenow="'.(int)$okrs_progress.'"  style="'.$okrs_progress.'%;" data-percent="'.(int)$okrs_progress.'">
                      </div>
                      </div>
        ';
        return ['okrs_count' => $okrs_count, 'okrs_progress' => $test, 'okrs_keyres' => $okrs_keyres, 'html' => $html];
    }
    /**
     * get cky current
     * @return id 
     */
    public function get_cky_current(){
        $query = 'SELECT id FROM '.db_prefix().'okr_setting_circulation where MONTH(NOW()) = month(from_date) and year(from_date) = year(NOW()) Order by id ASC limit 1';
        if(!isset($this->db->query($query)->row()->id)){
            return '';
        }
        return $this->db->query($query)->row()->id;
    }

    /**
     * chart tree okrs
     * @return json 
     */
    public function chart_tree_okrs_circulation($flag = ''){
        $root = $this->get_node_root($flag);
        $okrs = $this->get_okrs();
        $json = [];
        if(count($root) > 0){
            foreach ($root as $key => $okr) {
                $json[] = $this->dq_json($okr, []);
            }
        }
        return $json;
    }
    /**
     * get unit
     * @param  string $id 
     * @return json or array     
     */
    public function get_unit($id = '')
    {
        if($id != ''){
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'okr_setting_unit')->row();
        }   
        return $this->db->get(db_prefix().'okr_setting_unit')->result_array();
    }

    /**
     * delete okrs
     * @param  integer $id 
     * @return bolean     
     */
    public function delete_okrs($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'okrs');

        $this->db->where('okrs_id',$id);
        $this->db->delete(db_prefix().'okrs_key_result');

        $this->db->where('okrs_id',$id);
        $this->db->delete(db_prefix().'okrs_checkin');

        $this->db->where('okr_superior', $id);
        $this->db->update(db_prefix() . 'okrs', ['okr_superior' => '']);

        return true;
    }

    public function get_info_node($okrs){
        $this->db->where('id', $okrs);
        $okr = $this->db->get(db_prefix().'okrs')->row();
        $this->db->where('okrs_id', $okr->id);
        $key_results = $this->db->get(db_prefix().'okrs_key_result')->result_array();
        $progress = $okr->progress;
        if(is_null($progress)){
            $progress = 0;
        }
        $test = '
        <div class="progress no-margin progress-bar-mini cus_tran">
                  <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="'.$progress.'%;" data-percent="'.$progress.'">
                  </div>
               </div>
               '.$progress.'%
       </div>
        ';  
        $confidence_level = $okr->confidence_level;
        switch ($confidence_level) {
            case 1:
                $confidence_level_html = '
                    <div class="default">
                        <div class="changed_1">
                            <label>
                              <input type="radio" checked><span> '._l('is_fine').'</span>
                            </label>
                        </div>
                      </div>
                    ';
                break;
                case 2:
                $confidence_level_html = '
                    <div class="default">
                        <label>
                          <input type="radio" checked><span class="default_ct"> '._l('not_so_good') .'</span>
                        </label>
                        
                      </div>
                    ';
                break;
            default:
                $confidence_level_html = '
                    <div class="default">
                        <div class="changed_2">
                            <label>
                              <input type="radio"  checked><span> '. _l('very_good') .'</span>
                            </label>
                        </div>
                      </div>
                    ';
                break;
        }
        $html = '';
        $html .= '<table border="1" class="w-100">
               <tr>
                <th>'._l('main_results').'</th>
                <th>'._l('target').'</th>
                <th>'._l('progress').'</th>
                <th>'._l('confidence_level').'</th>
                <th>'._l('plan').'</th>
                <th>'._l('results').'</a></th>
              </tr>';

        if(count($key_results) > 0){
            foreach ($key_results as $key => $value) {
                $unit_ = (isset($this->get_unit($value['unit'])->unit) ? $this->get_unit($value['unit'])->unit : '');
                $html .= '
                      <tr>
                        <td>'.$value['main_results'].'</td>
                        <td>'.$value['target'].'('.$unit_.')</td>        
                        <td class="view_detail_okr_progress">'.$test.'</td>
                        <td>'.$confidence_level_html.'</td>
                         <td><a href="#" id="plan_view" data-toggle="popover" data-placement="bottom" data-content="'.$value['plan'].'" data-original-title="'._l('plan').'">'.$value['plan'].'</a></td>
                        <td><a href="#" id="results_view" data-toggle="popover" data-placement="bottom" data-content="'.$value['results'].'"  data-original-title="'._l('results').'">'.$value['results'].'</a></td>      
                      </tr>
                ';
                }
            }
            $html .= '</table>';

        return $html;
    }

    public function notifications($id_staff, $link, $description){
        $notifiedUsers = [];
        $id_userlogin = get_staff_user_id();

        $notified = add_notification([
            'fromuserid'      => $id_userlogin,
            'description'     => $description,
            'link'            => $link,
            'touserid'        => $id_staff,
            'additional_data' => serialize([
               $description,
            ]),
        ]);
        if ($notified) {
            array_push($notifiedUsers, $id_staff);
        }
        pusher_trigger_notification($notifiedUsers);
    }

    public function get_okrs_attachments($id, $rel_id = false)
    {
        $this->db->where('id', $id);
        $file = $this->db->get(db_prefix().'files')->row();

        if ($file && $rel_id) {
            if ($file->rel_id != $rel_id) {
                return false;
            }
        }
        return $file;
    }

    /**
     * delete okrs attachment 
     *
     * @param      $id     The identifier
     *
     * @return     bolean  
     */
    public function delete_okrs_attachment($id)
    {
        $attachment = $this->get_okrs_attachments($id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(OKR_MODULE_UPLOAD_FOLDER .'/okrs_attachments/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix().'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }
            if (is_dir(OKR_MODULE_UPLOAD_FOLDER .'/okrs_attachments/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(OKR_MODULE_UPLOAD_FOLDER .'/okrs_attachments/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(OKR_MODULE_UPLOAD_FOLDER .'/okrs_attachments/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * get category
     * @param  integer $id 
     * @return array or json       
     */
    public function get_category($id = '')
    {
        if($id != ''){
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'okr_setting_category')->row();
        }
        return $this->db->get(db_prefix().'okr_setting_category')->result_array();
    }


    /**
     * display json tree okrs
     * @return $html 
     */
    public function display_json_tree_okrs_type($type = ''){
        $this->db->where('type', $type);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        $html = '';
        if(count($okrs) > 0){
            foreach ($okrs as $key => $okr) {
                $html .= $this->dq_html('', $okr);
            }
        }

        return $html;
    }
    /**
     * chart tree okrs
     * @return json 
     */
    public function chart_tree_okrs_type($type = ''){
        $this->db->where('type', $type);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        if(count($okrs) > 0){
            foreach ($okrs as $key => $okr) {
                $json[] = $this->dq_json($okr, []);
            }
        }
        return $json;
    }


    /**
     * display json tree okrs
     * @return $html 
     */
    public function display_json_tree_okrs_category($category = ''){
        $this->db->where('category', $category);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();

        $json = [];
        $html = '';
        if(count($okrs) > 0){
            foreach ($okrs as $key => $okr) {
                $html .= $this->dq_html_category('', $okr);
            }
        }
        return $html;

    }
    /**
     * chart tree okrs
     * @return json 
     */
    public function chart_tree_okrs_category($category = ''){
        $this->db->where('category', $category);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        if(count($okrs) > 0){
            foreach ($okrs as $key => $okr) {
                $json[] = $this->dq_json_category($okr, []);
            }
        }
        return $json;
    }
    /**
     * dq html
     * @param  string $html   
     * @param  array $node   
     * @return $html         
     */
    public function dq_html_category($html, $node){
        $key_results = $this->count_key_results($node['id']);
       
        $progress = $this->okr_model->get_okrs($node['id'])->progress;
        if(is_null($progress)){
            $progress = 0;
        }
        $display = '';
        if($node['display'] == 2){
            $display = 'hide';
        }
        if($node['person_assigned'] == get_staff_user_id() || is_admin()){
            $display = '';
        }
        $full_name = 
        '<div class="pull-right">'.staff_profile_image($node['person_assigned'],['img img-responsive staff-profile-image-small pull-left']).' <a href="#" class="pull-left name_class">'.get_staff_full_name($node['person_assigned']).'</a> </div>';

        $rattings = '
        <div class="progress no-margin progress-bar-mini cus_tran">
                  <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="'.$progress.'%;" data-percent="'.$progress.'">
                  </div>
               </div>
               '.$progress.'%
       </div>
        ';  
       
        $type = $node['type'] != '' ? ($node['type'] == 1 ? _l('personal') : ($node['type'] == 2 ? _l('department') : _l('company'))) : '';

        $department = $node['department'] != '' && $node['department'] != 0 ? get_department_name_of_okrs($node['department'])->name : '';


        if($node['status'] == 0){
            $status = '<span class="label label-warning s-status ">'._l('unfinished').'</span>';
        }else{
            $status = '<span class="label label-success s-status ">'._l('finish').'</span>';
        }

        $option = '';
        $option .= '<a href="' . admin_url('okr/show_detail_node/' . $node['id']) . '" class="btn btn-default btn-icon">';
        $option .= '<i class="fa fa-eye"></i>';
        $option .= '</a>';
        if($this->okr_model->get_okrs($node['id'])->status != 1){
            if(has_permission('okr','','edit') || is_admin()){ 
            $option .= '<a href="'.admin_url('okr/new_object_main/'.$node['id']).'" class="btn btn-default btn-icon">';
            $option .= '<i class="fa fa-edit"></i>';
            $option .= '</a>';
            }
        }
        if(has_permission('okr','','delete') || is_admin()){ 
        $option .= '<a href="' . admin_url('okr/delete_okrs/'.$node['id']) . '" class="btn btn-danger btn-icon _delete">';
        $option .= '<i class="fa fa-remove"></i>';
        $option .= '</a>';
        }
        $row[] = $option; 
        
        $html .= '
        <tr class="treegrid-'.$node['id'].' treegrid-parent-'.$node['okr_superior'].' '.$display.'" >
            <td class="text-left "><a href="#" class="trigger" data-id="'.$node['person_assigned'].'" ><div class="okr__">'.staff_profile_image($node['person_assigned'],['img staff-profile-image-small ']).'</a> <span class="your-target-content">'.$node['your_target'].'</span></div></td>
            <td><div class="box effect8" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content="">
                <span>'.$key_results->count.' '._l('key_results').'</span>
                </div>
            </td>
            <td class="text-danger">+'.$node['change'].'</td>
            <td>'.$rattings.'</td>
            <td>'.category_view($node['category']).'  </td>
            <td>'.$type.'  </td>
            <td>'.$department.'  </td>
            <td>'.$status.'</td>';
            if(has_permission('okr','','edit') || is_admin() || has_permission('okr','','delete')){ 
                $html .= '<td>'.$option.'</td>';
            }
        $html .= '</tr>';

        return $html;
    }

    /**
     * dq json
     * @param  array $node   
     * @param  array $array_ 
     * @return $array         
     */
    public function dq_json_category($node, $array_){
        $data_popover = $this->objective_show($node['id']);
        $progress = $this->okr_model->get_okrs($node['id'])->progress;
        if(is_null($progress)){
            $progress = 0;
        }
        $test = '
            <div class="progress-json">
               <div class="project-progress relative" data-value="'.($progress/100).'" data-size="55" data-thickness="5">
                  <strong class="okr-percent"></strong>
               </div>
               <span >'._l('progress').'</span>
            </div>

        ';

        $display = '';
        if($node['display'] == 2){
            $display = 'hide';
        }
        if($node['person_assigned'] == get_staff_user_id()){
            $display = '';
        }

        $count_key_results = $this->count_key_results($node['id']);
        $rattings = '<div class="devicer">';
            $rattings .= $test;
            $rattings .= '<div class="box-json">';
            if($count_key_results->count >0){
                $rattings .= '<div class="demo_box" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content=""><div class="bg-1 pull-right"><span class="rate-box-value-1">'.$count_key_results->count.'</span></div></div>';
            }else{
                $rattings .= '<div class="demo_box" data-okr="'.$node['id'].'" data-toggle="popover" title="'._l('objective').'" data-content="" > <div class="bg-2"><span class="rate-box-value-2">'.$count_key_results->count.'</span></div></div>';
            }
            $rattings .= '<span>'._l('key_results').'</span>';
            $rattings .= '</div>';

        $rattings .= '</div>';

        if($display == 'hide'){
            $rattings = '';
        }
        $role = get_role_name_staff($node['person_assigned']);
        $name = '<a href="'.admin_url('okr/show_detail_node/'.$node['id']).'">'.$node['your_target'].'</a>';   
        if($display == 'hide'){
            $name = '<i class="fa fa-lock lagre-lock" aria-hidden="true"></i>';
        }     
        $title = '<div class="position-absolute mleft-22"><a href="#" class="name_class_chart">'.get_staff_full_name($node['person_assigned']).'</a><div class="role_name">'.$role.'</div></div>';
        $image = staff_profile_image($node['person_assigned'],['img img-responsive staff-profile-image-small pull-left position-absolute']);
        $array = array('name' => $name, 'title' => $title, 'job_position_name' => '', 'dp_user_icon' => $rattings, 'display' => $display,'image' => $image);
       
        return $array;
    }

    /**
     * display json tree okrs
     * @return $html 
     */
    public function display_json_tree_okrs_department($department = ''){
        $this->db->where('department', $department);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();

        $json = [];
        $html = '';
        if(count($okrs) > 0){
            foreach ($okrs as $key => $okr) {
                $html .= $this->dq_html_category('', $okr);
            }
        }
        return $html;

    }
    /**
     * chart tree okrs
     * @return json 
     */
    public function chart_tree_okrs_department($department = ''){
        $this->db->where('department', $department);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        if(count($okrs) > 0){
            foreach ($okrs as $key => $okr) {
                $json[] = $this->dq_json_category($okr, []);
            }
        }
        return $json;
    }
    /**
     * display json tree checkin
     * @return $html 
     */
    public function display_json_tree_checkin_type($type = ''){
        $this->db->where('type', $type);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        $html = '';
        foreach ($okrs as $key => $okr) {
            $html .= $this->dq_html_checkin('', $okr);
        }
        return $html;
    }

    /**
     * display json tree checkin
     * @return $html 
     */
    public function display_json_tree_checkin_category($category = ''){
        $this->db->where('category', $category);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        $html = '';
        foreach ($okrs as $key => $okr) {
            $html .= $this->dq_html_checkin('', $okr);
        }
        return $html;
    }

    /**
     * display json tree checkin
     * @return $html 
     */
    public function display_json_tree_checkin_department($department = ''){
        $this->db->where('department', $department);
        $okrs = $this->db->get(db_prefix().'okrs')->result_array();
        $json = [];
        $html = '';
        foreach ($okrs as $key => $okr) {
            $html .= $this->dq_html_checkin('', $okr);
        }
        return $html;
    }
    public function get_edit_okrs_v101($id){
        $this->db->where('circulation', $id);
        return $rs = $this->db->get(db_prefix().'okrs')->result_array();
    }

    public function dq_v101($okrs){
        $sup = [];
        if($okrs->okr_superior != ''){
            $sup[] = $okrs->okr_superior;
            $okr = $this->get_okrs($okrs->okr_superior);
            $sup[] = $okr->okr_superior;
            $this->dq_v101($okr);
        }
        return $sup;
    }
}