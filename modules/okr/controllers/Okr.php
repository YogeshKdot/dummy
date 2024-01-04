<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Class Okr 
 */
class Okr extends AdminController
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('okr_model');
        $this->load->model('departments_model');
    }
    /**
     * dashboard
     * @return view
     */
    public function dashboard(){
      $this->load->model('staff_model');
      if(!has_permission('okr','','view') && !has_permission('okr','','view_own') && !is_admin()){
        access_denied('okr');
      }

      $data['title'] = _l('dashboard');
      $data['okrs'] = $this->okr_model->get_okrs();
      $data['progress_good'] = $this->okr_model->get_progress_dashboard(1)->count;
      $data['progress_risk'] = $this->okr_model->get_progress_dashboard(2)->count;
      $data['progress_develope'] = $this->okr_model->get_progress_dashboard(3)->count;
      $data['checkin_status'] = json_encode($this->okr_model->checkin_status_dashboard());
      $data['okrs_company'] = $this->okr_model->okrs_company_dasdboard();
      $data['okrs_user'] = $this->okr_model->okrs_user_dasdboard();
      $data['person_assigned'] = $this->staff_model->get();
      $data['circulation'] = $this->okr_model->get_circulation();
      $data['category'] = $this->okr_model->get_category();
      $data['department'] = $this->departments_model->get();
      $this->load->view('dashboard/manage_dashboard', $data);
    }
    /**
     * checkin
     * @return view
     */
    public function checkin(){
      if(!has_permission('okr','','view') && !has_permission('okr','','view_own') && !is_admin()){
        access_denied('okr');
      }
      $data['title'] = _l('checkin');
      $data['cky_current'] = $this->okr_model->get_cky_current();
      $data['array_tree'] = $this->okr_model->display_json_tree_checkin($this->okr_model->get_cky_current());
      $data['circulation'] = $this->okr_model->get_circulation();
      $data['staffs'] = $this->staff_model->get();
      $data['okrs'] = $this->okr_model->get_okrs();
      $data['department'] = $this->departments_model->get();
      $data['category'] = $this->okr_model->get_category();
      $this->load->view('checkin/manage_checkin', $data);
    }

    /**
     * okrs
     * @return view
     */
    public function okrs(){
      if(!has_permission('okr','','view') && !has_permission('okr','','view_own') && !is_admin()){
        access_denied('okr');
      }
      $this->load->model('staff_model');
      $data['title'] = _l('okrs');
      $data['cky_current'] = $this->okr_model->get_cky_current();
      $data['department'] = $this->departments_model->get();
      $data['category'] = $this->okr_model->get_category();
      $data['staffs'] = $this->staff_model->get();
      $data['okrs'] = $this->okr_model->get_okrs();
      $data['array_tree'] = $this->okr_model->display_json_tree_okrs($this->okr_model->get_cky_current());
      $data['circulation'] = $this->okr_model->get_circulation();
      $data['array_tree_chart'] = $this->okr_model->chart_tree_okrs($this->okr_model->get_cky_current());
      $this->load->view('okrs/manage_okrs', $data);
    }

    /**
     * setting
     * @return view
     */
    public function setting(){
      if(!is_admin()){
        access_denied('okr');
      }
        $data['tab'] = $this->input->get('tab');

        if($data['tab'] == ''){
                $data['tab'] = 'circulation';
        }

        $data['title'] = _l($data['tab']);
        $this->load->view('setting/manage_setting', $data);
    }
    /*
     * @return table
     */
    public function table_circulation(){
        $this->app->get_table_data(module_views_path('okr', 'table/table_circulation'));
    }
    /**
     * setting circulation 
     * @return redirect
     */
    public function setting_circulation(){
      if($this->input->post()){
        $data = $this->input->post();
        if($data['id'] == ''){
          unset($data['id']);
          $insert_id = $this->okr_model->setting_circulation($data);
          if ($insert_id) {
                  $message = _l('added_successfully');
                  set_alert('success', $message);
              }
        }else{
          $id = $data['id'];
          unset($data['id']);
          $success = $this->okr_model->update_setting_circulation($data, $id);
          if ($success) {
                  $message = _l('updated_successfully');
                  set_alert('success', $message);
              }
        }
          redirect(admin_url('okr/setting?tab=circulation'));
      }
    }
    /**
     * delete setting circulation 
     * @param  $id
     * @return  redirect 
     */
    public function delete_setting_circulation($id){
    $response = $this->okr_model->delete_setting_circulation($id);
        if($response == true){
            set_alert('success', _l('deleted', _l('setting')));
        }
        else{
            set_alert('warning', _l('problem_deleting'));            
        }
      redirect(admin_url('okr/setting?tab=circulation'));
    }
    /**
     * setting question 
     * @return redirect
     */
    public function setting_question(){
      if($this->input->post()){
        $data = $this->input->post();
        if($data['id'] == ''){
          unset($data['id']);
          $insert_id = $this->okr_model->setting_question($data);
          if ($insert_id) {
                  $message = _l('added_successfully');
                  set_alert('success', $message);
              }
        }else{
          $id = $data['id'];
          unset($data['id']);
          $success = $this->okr_model->update_setting_question($data, $id);
          if ($success) {
                  $message = _l('updated_successfully');
                  set_alert('success', $message);
              }
        }
          redirect(admin_url('okr/setting?tab=question'));
      }
    }
    /**
     * table question
     * @return table
     */
    public function table_question(){
        $this->app->get_table_data(module_views_path('okr', 'table/table_question'));
    }
    /**
     * delete setting question
     * @param  integer $id 
     * @return redicrect     
     */
    public function delete_setting_question($id){
    $response = $this->okr_model->delete_setting_question($id);
        if($response == true){
            set_alert('success', _l('deleted', _l('setting')));
        }
        else{
            set_alert('warning', _l('problem_deleting'));            
        }
      redirect(admin_url('okr/setting?tab=question'));
    }
    /**
     * setting evaluation criteria
     * @return redicrect
     */
    public function setting_evaluation_criteria(){
      if($this->input->post()){
        $data = $this->input->post();
        if($data['id'] == ''){
          unset($data['id']);
          $insert_id = $this->okr_model->setting_evaluation_criteria($data);
          if ($insert_id) {
                  $message = _l('added_successfully');
                  set_alert('success', $message);
              }
        }else{
          $id = $data['id'];
          unset($data['id']);
          $success = $this->okr_model->update_setting_evaluation_criteria($data, $id);
          if ($success) {
                  $message = _l('updated_successfully');
                  set_alert('success', $message);
              }
        }
          redirect(admin_url('okr/setting?tab=evaluation_criteria'));
      }
    }
    /**
     * table evaluation criteria 
     * @return table
     */
    public function table_evaluation_criteria(){
        $this->app->get_table_data(module_views_path('okr', 'table/table_evaluation_criteria'));
    }
    /**
     * delete setting evaluation criteria
     * @param  integer $id 
     * @return redirect
     */
    public function delete_setting_evaluation_criteria($id){
    $response = $this->okr_model->delete_setting_evaluation_criteria($id);
        if($response == true){
            set_alert('success', _l('deleted', _l('setting')));
        }
        else{
            set_alert('warning', _l('problem_deleting'));            
        }
      redirect(admin_url('okr/setting?tab=evaluation_criteria'));
    }
    /**
     * new object main
     * @param  string $id
     * @return view
     */
    public function new_object_main($id = ''){
      $this->load->model('departments_model');
      $this->load->model('staff_model');
      $data['title'] = _l('new_okrs');
      $data['circulation'] = $this->okr_model->get_circulation();
      $data['okrs'] = $this->okr_model->get_okrs();
      $data['staffs'] = $this->staff_model->get();
      $data['category'] = $this->okr_model->get_category();
      $data['department'] = $this->departments_model->get();
      $data['dateformat'] = $this->departments_model->get();
      $data['unit'] = $this->okr_model->get_unit();
      if($id != ''){
        $data['title'] = _l('update_okrs');
        $data['okrs_edit'] = $this->okr_model->get_okrs_detailt($id);
        if($data['okrs_edit']['object']->circulation){
          $data['okrs'] = $this->okr_model->get_edit_okrs_v101($data['okrs_edit']['object']->circulation);
        }
      }
      $this->load->view('okrs/new_object_main', $data);
    }
    /**
     * okrs_new_main 
     * @return redirect
     */
    public function okrs_new_main(){
      if($this->input->post()){
        $data = $this->input->post();
        if($data['id'] == ''){
          $insert_id = $this->okr_model->new_okrs_main($data);
          if ($insert_id) {
            unset($attachments);
            handle_okrs_attachments($insert_id);
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('okr/okrs'));
        }else{
          $id = $data['id'];
          unset($data['id']);
          unset($attachments);
          $success = $this->okr_model->update_okrs_main($data, $id);
          if ($success) {
              handle_okrs_attachments($id);
              $message = _l('updated_successfully');
              set_alert('success', $message);
          }else{
              $message = _l('OKR_selection_problem_above_invites_you_to_try_again');
              set_alert('danger', $message);
          }
          redirect(admin_url('okr/new_object_main/'.$id));
        }
      }
    }
    /**
     * get not child 
     * @return echo data
     */
    public function get_not_child()
    {
      $data = $this->okr_model->chart_tree_okrs_clone($this->input->post());
      echo ($data);
    }
    /**
     * objective show
     * @return json
     */
    public function objective_show()
    {
      $id = $this->input->post('id');
      $data = $this->okr_model->objective_show($id);
      echo json_encode([$data]);
    }
    /**
     * checkin detailt
     * @param  integer $id  
     * @return view 
     */
    public function checkin_detailt($id)
    {
      $data['tab'] = $this->input->get('tab');
        if($data['tab'] == ''){
                $data['tab'] = 'checkin';
        }
      $data['title'] = _l($data['tab']);
      $change = $this->okr_model->count_key_results($id)->count;
      $name = $this->okr_model->get_okrs($id)->your_target;
      $progress = $this->okr_model->get_okrs($id)->progress;
      $circulation = $this->okr_model->get_circulation($this->okr_model->get_okrs($id)->circulation);
      if(is_null($progress)){
          $progress = 0;
      }
      $question = $this->okr_model->get_question($id);
      $recently_checkin = $this->okr_model->get_okrs($id)->recently_checkin;
      $key_result_checkin = $this->okr_model->get_key_result_checkin($id);
      $get_key_result = $this->okr_model->get_key_result($id);
      $get_evaluation_criteria = $this->okr_model->get_evaluation_criteria(1);
      $data['id'] = $id;
      $data['name'] = $name;
      $data['change'] = $change;
      $data['progress'] = $progress;
      $data['question'] = $question;
      $data['count_question'] = count($question);
      $data['get_key_result'] = $get_key_result;
      $data['evaluation_criteria'] = $get_evaluation_criteria;
      $data['key_result_checkin'] = $key_result_checkin;
      $data['circulation'] = $circulation;
      $date_format   = get_option('dateformat');
      $date_format   = explode('|', $date_format);
      $date_format   = $date_format[0];

      $data['special_characters'] = substr($date_format, 1, 1);

      if(is_null($recently_checkin)){
        $recently_checkin = date($date_format);
      }
      $data['format'] = $date_format[0];

      $data['date_checkin'] = $recently_checkin;
      $this->load->view('checkin/checkin_detailt', $data);
    }
    /**
     * highcharts detailt checkin
     * @param  integer $id 
     * @return json   
     */
    public function highcharts_detailt_checkin($id)
    {
      echo json_encode($this->okr_model->highcharts_detailt_checkin_model($id));
    }
    /**
     * add checkin
     * @return redirect
     */
    public function add_check_in(){
      $success = $this->okr_model->add_check_in($this->input->post());
      if ($insert_id) {
          $message = _l('added_successfully');
          set_alert('success', $message);
      }
      redirect(admin_url('okr/checkin_detailt/'.$this->input->post()['id']));
    }
    /**
     * table history
     * @return table
     */
    public function table_history(){
        $this->app->get_table_data(module_views_path('okr', 'table/table_history'));
    }
    /**
     * get search okrs
     * @param  integer $id 
     * @return json    
     */
    public function get_search_okrs($id){
      $array_tree = $this->okr_model->display_json_tree_okrs_search($id);
      $array_tree_chart = $this->okr_model->chart_tree_search($id);
      echo json_encode(['array_tree_search' => $array_tree, 'array_tree_chart_search' => $array_tree_chart]);
    }
    /**
     * view details
     * @param  integer $id 
     * @return view    
     */
    public function view_details($id){
      $data['title'] = _l('view_details');
      $log_okr_id = $this->okr_model->result_checkin_log($id, true);

      $question = $this->okr_model->get_question($log_okr_id->okrs_id);
      $data['count_question'] = count($question);
      $get_key_result = $this->okr_model->get_key_result($log_okr_id->okrs_id);

      $log = $this->okr_model->result_checkin_log($id, '', count($get_key_result));
      $name = $this->okr_model->get_okrs($log_okr_id->okrs_id)->your_target;

      $change = $this->okr_model->count_key_results($log_okr_id->okrs_id)->count;
      $name = $this->okr_model->get_okrs($log_okr_id->okrs_id)->your_target;
      $progress = $log_okr_id->progress_total;
      $recently_checkin = $log_okr_id->recently_checkin;
      $key_result_checkin = $log;
      $get_evaluation_criteria = $this->okr_model->get_evaluation_criteria(1);
      $data['id'] = $log_okr_id->okrs_id;
      $data['name'] = $name;
      $data['change'] = $change;
      $data['progress'] = $progress;
      $data['question'] = $question;
      $data['get_key_result'] = $get_key_result;
      $data['evaluation_criteria'] = $get_evaluation_criteria;
      $data['key_result_checkin'] = $key_result_checkin;
      $date_format   = get_option('dateformat');
      $date_format   = explode('|', $date_format);
      $date_format   = $date_format[0];
      if(is_null($recently_checkin)){
        $recently_checkin = date($date_format);
      }

      $data['date_checkin'] = $recently_checkin;
      $this->load->view('checkin/view_details', $data);
    }
    /**
     * get search okrs staff
     * @param  integer $staffid 
     * @return           
     */
    public function get_search_okrs_staff($staffid){
      $flag = 0;
      if($staffid == 0){
        $array_id[] = 0;
      }else{
        $array_id = $this->okr_model->get_okr_staff($staffid);
        if(count($array_id) > 0){
          $flag = 2;
        }else{
          $flag = 1;
        }
      }
      $array_tree = $this->okr_model->display_json_tree_okrs_search_staff($array_id);
      $array_tree_chart = $this->okr_model->chart_tree_search_staff($array_id);
      echo json_encode(['array_tree_search' => $array_tree, 'array_tree_chart_search' => $array_tree_chart , 'flag' => $flag]);
    }
    /**
     * get search checkin
     * @param  integer $id 
     * @return json 
     */
    public function get_search_checkin($id){
      $array_tree = $this->okr_model->display_tree_okrs_search_checkin($id);
      echo json_encode(['array_tree_search' => $array_tree]);
    }
    /**
     * get search checkin staff
     * @param  integer $staffid
     * @return json         
     */
    public function get_search_checkin_staff($staffid){
      if($staffid == 0){
        $array_id[] = 0;
      }else{
        $array_id = $this->okr_model->get_okr_staff($staffid);
      }
      $array_tree = $this->okr_model->display_tree_checkin_search_staff($array_id);
      echo json_encode(['array_tree_search' => $array_tree]);
    }
    /**
     * get_search_okrs_circulation 
     * @param  integer $circulationid 
     * @return json             
     */
    public function get_search_okrs_circulation($circulationid){
      $array_tree = $this->okr_model->display_json_tree_okrs($circulationid);
      $array_tree_chart = $this->okr_model->chart_tree_okrs_circulation($circulationid);
      echo json_encode(['array_tree_search' => $array_tree, 'array_tree_chart_search' => $array_tree_chart]);
    }
    /**
     * table unit
     * @return table
     */
    public function table_unit(){
      $this->app->get_table_data(module_views_path('okr', 'table/table_unit'));
    }
    /**
     * setting unit
     * @return redirect
     */
    public function setting_unit(){
      if($this->input->post()){
        $data = $this->input->post();
        if($data['id'] == ''){
          unset($data['id']);
          $insert_id = $this->okr_model->setting_unit($data);
          if ($insert_id) {
                  $message = _l('added_successfully');
                  set_alert('success', $message);
              }
        }else{
          $id = $data['id'];
          unset($data['id']);
          $success = $this->okr_model->update_setting_unit($data, $id);
          if ($success) {
                  $message = _l('updated_successfully');
                  set_alert('success', $message);
              }
        }
          redirect(admin_url('okr/setting?tab=unit'));
      }
    }
    /**
     * delete setting unit
     * @param  integer $id
     * @return  redirect  
     */
    public function delete_setting_unit($id){
    $response = $this->okr_model->delete_setting_unit($id);
        if($response == true){
            set_alert('success', _l('deleted', _l('setting')));
        }
        else{
            set_alert('warning', _l('problem_deleting'));            
        }
      redirect(admin_url('okr/setting?tab=unit'));
    }

    /**
     * get search checkin circulation
     * @param  integer $circulation_id
     * @return json         
     */
    public function get_search_checkin_circulation($circulation_id){
      $array_tree = $this->okr_model->display_json_tree_checkin($circulation_id);

      echo json_encode(['array_tree_search' => $array_tree]);
    }
    /**
     * get staff profile
     * @param  integer $staffid
     * @return         
     */
    public function get_staff_profile($staffid){
      $full_name = 
        '<div class="pull-right">'.staff_profile_image($staffid,['img img-responsive staff-profile-image-small pull-left']).' <a href="#" class="pull-left name_class">'.get_staff_full_name($staffid).'</a> </div>';
      echo json_encode($full_name);  
    }
    /**
     * delete okrs
     * @param  integer $id
     * @return redirect
     */ 
    public function delete_okrs($id){
      $response = $this->okr_model->delete_okrs($id);
        if($response == true){
            set_alert('success', _l('deleted', _l('okr')));
        }
        else{
            set_alert('warning', _l('problem_deleting'));            
        }
      redirect(admin_url('okr/okrs'));
    }
    /**
     * show detail node
     * @param  integer $okrs_id 
     * @return  view         
     */
    public function show_detail_node($okrs_id){
      $data['html'] = $this->okr_model->get_info_node($okrs_id);
      $okr = $this->okr_model->get_okrs($okrs_id);

      $data['your_target'] = $okr->your_target;
      $data['okrs_id'] = $okr->id;
      $data['person_assigned'] = staff_profile_image($okr->person_assigned,['img img-responsive staff-profile-image-small pull-left']).'  '.'<span>'. get_staff_full_name($okr->person_assigned). '</span>';
      if($okr->status == 1){
        $data['status'] = '<span class="label label-success s-status ">'._l('finish').'</span>';
      }else{
        $data['status'] = '<span class="label label-warning s-status ">'._l('unfinished').'</span>';
      }
      if(is_null($okr->progress)){
        $okr->progress = 0;
      }

      $data['progress'] = 
      '<div class="progress no-margin cus_tran">
                  <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="'.$okr->progress.'" aria-valuemin="0" aria-valuemax="100" style="'.$okr->progress.'%;" data-percent="'.$okr->progress.'">
                  </div>
       </div>
      <div>  
       '.$okr->progress.'%
      </div> ';
      $circulation = $this->okr_model->get_circulation($okr->circulation);
      if($circulation){
        $data['circulation'] = $circulation->name_circulation.' ('.$circulation->from_date .' - '.$circulation->to_date .')' ;
      }else{
        $data['circulation'] = '';
      }
      if($okr->display == 1){
        $data['display'] = _l('public');
      }else{
        $data['display'] = _l('private');
      }

      $data['title'] = _l('okr_detail');
      $this->load->view('okrs/view_details', $data);

    }

    /**
     * table category
     * @return table
     */
    public function table_category(){
      $this->app->get_table_data(module_views_path('okr', 'table/table_category'));
    }

    /**
     * delete setting category
     * @param  integer $id
     * @return  redirect  
     */
    public function delete_setting_category($id){
    $response = $this->okr_model->delete_setting_category($id);
        if($response == true){
            set_alert('success', _l('deleted', _l('setting')));
        }
        else{
            set_alert('warning', _l('problem_deleting'));            
        }
      redirect(admin_url('okr/setting?tab=category'));
    }

    /**
     * setting categoty
     * @return redirect
     */
    public function setting_category(){
      if($this->input->post()){
        $data = $this->input->post();
        if($data['id'] == ''){
          unset($data['id']);
          $insert_id = $this->okr_model->setting_category($data);
          if ($insert_id) {
                  $message = _l('added_successfully');
                  set_alert('success', $message);
              }
        }else{
          $id = $data['id'];
          unset($data['id']);
          $success = $this->okr_model->update_setting_category($data, $id);
          if ($success) {
                  $message = _l('updated_successfully');
                  set_alert('success', $message);
              }
        }
          redirect(admin_url('okr/setting?tab=category'));
      }
    }
    /**
     *  preview file okrs 
     *
     * @param      <type>  $id      The identifier
     * @param      <type>  $rel_id  The relative identifier
     */
    public function file_okrs($id, $rel_id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin']             = is_admin();
        $data['file'] = $this->okr_model->get_okrs_attachments($id, $rel_id);

        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->load->view('okrs/_file_okrs', $data);
    }

    /**
     *  delete okrs attachment 
     *
     * @param    $id     The identifier
     */
    public function delete_okrs_attachment($id)
    {
        $this->load->model('misc_model');
        $file = $this->misc_model->get_file($id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo $this->okr_model->delete_okrs_attachment($id);
        } else {
            header('HTTP/1.0 400 Bad error');
            echo _l('access_denied');
            die;
        }
    }
    /**
     * table dashboard 
     * @return table
     */
    public function table_dashboard(){
      $this->app->get_table_data(module_views_path('okr', 'table/table_dashboard'));
    }

    /**
     * get_search_okrs_type
     * @param  integer $type 
     * @return json             
     */
    public function get_search_okrs_type($type){
      $array_tree = $this->okr_model->display_json_tree_okrs_type($type);
      $array_tree_chart = $this->okr_model->chart_tree_okrs_type($type);
      echo json_encode(['array_tree_search' => $array_tree, 'array_tree_chart_search' => $array_tree_chart]);
    }

    /**
     * get_search_okrs_category
     * @param  integer $category 
     * @return json             
     */
    public function get_search_okrs_category($category){
      $array_tree = $this->okr_model->display_json_tree_okrs_category($category);
      $array_tree_chart = $this->okr_model->chart_tree_okrs_category($category);
      echo json_encode(['array_tree_search' => $array_tree, 'array_tree_chart_search' => $array_tree_chart]);
    }


    /**
     * get_search_okrs_department
     * @param  integer $department 
     * @return json             
     */
    public function get_search_okrs_department($department){
      $array_tree = $this->okr_model->display_json_tree_okrs_department($department);
      $array_tree_chart = $this->okr_model->chart_tree_okrs_department($department);
      echo json_encode(['array_tree_search' => $array_tree, 'array_tree_chart_search' => $array_tree_chart]);
    }

    /**
     * get search checkin type
     * @param  integer $type
     * @return json         
     */
    public function get_search_checkin_type($type){
      $array_tree = $this->okr_model->display_json_tree_checkin_type($type);

      echo json_encode(['array_tree_search' => $array_tree]);
    }
     /**
     * get search checkin type
     * @param  integer $type
     * @return json         
     */
    public function get_search_checkin_department($department){
      $array_tree = $this->okr_model->display_json_tree_checkin_department($department);

      echo json_encode(['array_tree_search' => $array_tree]);
    }

     /**
     * get search checkin type
     * @param  integer $type
     * @return json         
     */
    public function get_search_checkin_category($category){
      $array_tree = $this->okr_model->display_json_tree_checkin_category($category);

      echo json_encode(['array_tree_search' => $array_tree]);
    }

    public function report(){
      $this->load->model('staff_model');
      if(!has_permission('okr','','view') && !has_permission('okr','','view_own') && !is_admin()){
        access_denied('okr');
      }

      $data['title'] = _l('report');
      $data['okrs'] = $this->okr_model->get_okrs();
      $data['progress_good'] = $this->okr_model->get_progress_dashboard(1)->count;
      $data['progress_risk'] = $this->okr_model->get_progress_dashboard(2)->count;
      $data['progress_develope'] = $this->okr_model->get_progress_dashboard(3)->count;
      $data['checkin_status'] = json_encode($this->okr_model->checkin_status_dashboard());
      $data['okrs_company'] = $this->okr_model->okrs_company_dasdboard();
      $data['okrs_user'] = $this->okr_model->okrs_user_dasdboard();
      $data['person_assigned'] = $this->staff_model->get();
      $data['circulation'] = $this->okr_model->get_circulation();
      $data['category'] = $this->okr_model->get_category();
      $data['department'] = $this->departments_model->get();
      $this->load->view('report/manage_report', $data);
    }

    /**
     * okrs
     * @return view
     */
    public function okrs_chart_org(){
      if(!has_permission('okr','','view') && !has_permission('okr','','view_own') && !is_admin()){
        access_denied('okr');
      }
      $this->load->model('staff_model');
      $data['title'] = _l('okrs');
      $data['cky_current'] = $this->okr_model->get_cky_current();
      $data['department'] = $this->departments_model->get();
      $data['category'] = $this->okr_model->get_category();
      $data['staffs'] = $this->staff_model->get();
      $data['okrs'] = $this->okr_model->get_okrs();
      $data['array_tree'] = $this->okr_model->display_json_tree_okrs($this->okr_model->get_cky_current());
      $data['circulation'] = $this->okr_model->get_circulation();
      $data['array_tree_chart'] = $this->okr_model->chart_tree_okrs($this->okr_model->get_cky_current());
      $this->load->view('okrs/manage_okrs_org', $data);
    }

    public function set_okr_superior($id)
    {
      $this->db->where('circulation', $id);
      $rs = $this->db->get(db_prefix().'okrs')->result_array();
      $html = '';
      foreach ($rs as $key => $value) {
        $html .= '<option value="'.$value['id'].'">'.$value['your_target'].'</option>';
      }
      echo json_encode($html);
    }
}