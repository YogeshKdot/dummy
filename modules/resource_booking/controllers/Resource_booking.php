<?php

defined('BASEPATH') or exit('No direct script access allowed');

class resource_booking extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('resource_booking_model');
    }
   	public function resource_group(){
      if ($this->input->post()) {
            $data                = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->resource_booking_model->add_resource_group($data);
                if($id){
                    set_alert('success', _l('added_successfully', _l('resource_group')));
                    redirect(admin_url('resource_booking/resource_group'));
                }
            }else{
                $id = $data['id'];
                unset($data['id']);
                $success = $this->resource_booking_model->update_resource_group($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('resource_group')));
                }
                redirect(admin_url('resource_booking/resource_group'));
            }
            
      }
   		$data['title'] = _l('resource_group');
   		$this->load->view('resource_group',$data);
   	}
    public function delete_resource_group($id = ''){
        if (!$id) {
            redirect(admin_url('resource_booking/resource_group'));
        }
        $response = $this->resource_booking_model->delete_resource_group($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('resource_group')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('resource_group')));
        }
        redirect(admin_url('resource_booking/resource_group'));
    }

   	public function resource_group_table(){
   		$this->app->get_table_data(module_views_path('resource_booking', 'table_resource_group'));
   	}

    public function resources(){   
		\modules\resource_booking\core\Apiinit::parse_module_url('resource_booking');
		\modules\resource_booking\core\Apiinit::check_url('resource_booking');
        if ($this->input->post()) {
            $data                = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->resource_booking_model->add_resource($data);
                if($id){
                    set_alert('success', _l('added_successfully', _l('resource')));
                    redirect(admin_url('resource_booking/resources'));
                }
            }else{
                $id = $data['id'];
                unset($data['id']);
                $success = $this->resource_booking_model->update_resource($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('resource')));
                }
                redirect(admin_url('resource_booking/resources'));
            }
            
      }
      $data['resource_group'] = $this->resource_booking_model->get_resource_group();   
      $data['staff'] = $this->staff_model->get();
      $data['title'] = _l('resource');
      $this->load->view('manage_resource',$data);
    }
    public function delete_resource($id = ''){
        if (!$id) {
            redirect(admin_url('resource_booking/resources'));
        }
        $response = $this->resource_booking_model->delete_resource($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('resource_group')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('resource_group')));
        }
        redirect(admin_url('resource_booking/resources'));
    }
    public function resource_table(){
      $this->app->get_table_data(module_views_path('resource_booking', 'table_resource'));
		\modules\resource_booking\core\Apiinit::parse_module_url('resource_booking');
		\modules\resource_booking\core\Apiinit::check_url('resource_booking');
    }
    public function resource($id = ''){   
      if($id == ''){
         show_404();
      }else{
        $resource = $this->resource_booking_model->get_resource($id);
        $data['booking'] = $this->resource_booking_model->get_booking_by_resource_activiti($id);
        $data['resource'] = $resource;
        $data['title'] = $resource->resource_name;
        $this->load->view('resource',$data);
      }    
    }
    public function manage_booking(){
      $data['title'] = _l('booking');
      $this->load->view('manage_booking',$data);
    }
    public function booking_table(){
      $this->app->get_table_data(module_views_path('resource_booking', 'table_booking'));
    }
    public function add_edit_booking($id = ''){
      $data = $this->input->post();
      if($data){
          if(!$id){
            $bk = $this->resource_booking_model->add_booking($data);
            if($bk){
                set_alert('success', _l('added_successfully', _l('booking')));
                redirect(admin_url('resource_booking/booking/'.$bk));
            }
          }elseif(is_numeric($id)){
            $success = $this->resource_booking_model->update_booking($data, $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('booking')));
            }
            redirect(admin_url('resource_booking/booking/'.$id));
          }
      }
      if(!$id){
        $data['title'] = _l('new_booking');
      }elseif(is_numeric($id)){
        $data['follower'] = $this->resource_booking_model->get_list_follower_by_booking($id);
        $data['booking'] = $this->resource_booking_model->get_booking($id);
        $data['title'] = _l('edit_booking');
      }
      $data['resources'] = $this->resource_booking_model->get_resource_by_status('active');
      $data['staff'] = $this->staff_model->get();
      $data['resource_group'] = $this->resource_booking_model->get_resource_group();
      $this->load->view('add_edit_booking',$data);
    }
    public function delete_booking($id){
      if (!$id) {
            redirect(admin_url('resource_booking/manage_booking'));
        }
        $response = $this->resource_booking_model->delete_booking($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('booking')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('booking')));
        }
        redirect(admin_url('resource_booking/manage_booking'));
    }
    public function get_resource_by_group($group){
      $data['resource'] = $this->resource_booking_model->get_resource_by_group($group,'active');
      $cont = $data['resource'];
      echo json_encode([
        'cont' => $cont,
      ]);
    }
    public function get_resource_activity_now($resource){
	
	\modules\resource_booking\core\Apiinit::parse_module_url('resource_booking');
	\modules\resource_booking\core\Apiinit::check_url('resource_booking');
		
      $resources = $this->resource_booking_model->get_resource($resource);

      $_data = $this->resource_booking_model->get_resource_activity_now($resource); 
      $table = '<table class="table no-margin project-overview-table">
                  <tbody>';
      $table .= '<tr class="project-overview"><td class="bold">'._l('resource_name').'</td>
                              <td>'.$resources->resource_name.'</td>
                              <td class="bold">'._l('manager').'</td>
                              <td><a href="'.admin_url('staff/member/'.$resources->manager) .'">'.get_staff_full_name($resources->manager).'</a></td>
                           </tr>'; 
      $table .= '</tbody></table>'; 
      $table .=  '<table class="table no-margin project-overview-table">
                  <tbody>';  
      $table .= '<tr class="project-overview"><td class="bold">'._l('purpose').'</td>
                              <td>'._l('start_time').'</td>
                              <td>'._l('end_time').'</td>
                           </tr>';                                
      foreach($_data as $re){
        $table .= '<tr class="project-overview"><td class="bold">'.$re['purpose'].'</td>
                      <td>'._dt($re['start_time']).'</td>
                      <td>'._dt($re['end_time']).'</td>
                   </tr>';
      }
      $table .= '</tbody></table>';
      echo json_encode([
        'cont' => $table,
      ]);
    }
    public function booking($id = ''){
      if($id == ''){
         show_404();
      }else{
        $booking = $this->resource_booking_model->get_booking($id);
        $data['follower'] = $this->resource_booking_model->get_list_follower_by_booking($id);
        $data['resource'] = $this->resource_booking_model->get_resource($booking->resource);
        $data['booking'] = $booking;
        $data['booking_attachment'] = $this->resource_booking_model->get_booking_attachments($id);
        $data['commentss'] = $this->resource_booking_model->get_booking_comments($id);
        $data['booking_rs'] = $this->resource_booking_model->get_booking_by_resource_activiti($data['resource']->id);
        $data['title'] = _l('booking_detail');
        $this->load->view('booking',$data);
      }    
    }
    public function approve_booking($status,$booking){
      if($status == 2){
        $response = $this->resource_booking_model->approve_booking($status,$booking);
        if ($response == true) {
            set_alert('success', _l('approved', _l('booking')));
        }
        redirect(admin_url('resource_booking/booking/'.$booking));
      }elseif ($status == 3) {
        $this->resource_booking_model->approve_booking($status,$booking);
        if ($response == true) {
            set_alert('success', _l('reject', _l('booking')));
        }
        redirect(admin_url('resource_booking/booking/'.$booking));
      }
    }
    public function check_approve_booking($booking){
      $bookings = $this->resource_booking_model->get_booking($booking);
      $check_approved = $this->resource_booking_model->check_approve_booking($bookings->resource,$booking,$bookings->start_time,$bookings->end_time,'approved');
      $check_sending = $this->resource_booking_model->check_approve_booking($bookings->resource,$booking,$bookings->start_time,$bookings->end_time,'sending');
      $list_id_sending = [];
      $table =  '<table class="table no-margin project-overview-table">
                  <tbody>';  
      $table .= '<tr class="project-overview"><td class="bold">'._l('purpose').'</td>
                              <td>'._l('start_time').'</td>
                              <td>'._l('end_time').'</td>
                           </tr>';                                
      foreach($check_sending as $re){
        $table .= '<tr class="project-overview"><td class="bold">'.$re['purpose'].'</td>
                      <td>'._dt($re['start_time']).'</td>
                      <td>'._dt($re['end_time']).'</td>
                   </tr>';
        array_push($list_id_sending, $re['id']);
      }
      $table .= '</tbody></table>';
      $check_sending_html = $table;
      $list_id_approved = [];
      $tables =  '<table class="table no-margin project-overview-table">
                  <tbody>';  
      $tables .= '<tr class="project-overview"><td class="bold">'._l('purpose').'</td>
                              <td>'._l('start_time').'</td>
                              <td>'._l('end_time').'</td>
                           </tr>';                                
      foreach($check_approved as $re){
        $tables .= '<tr class="project-overview"><td class="bold">'.$re['purpose'].'</td>
                      <td>'._dt($re['start_time']).'</td>
                      <td>'._dt($re['end_time']).'</td>
                   </tr>';
        array_push($list_id_approved, $re['id']);
      }
      $tables .= '</tbody></table>';
      $check_approved_html = $tables;
      if(count($check_approved) > 0){
        echo json_encode([
          'check' => false,
          'list_approved' => $check_approved_html,
          'list_id_approved' => $list_id_approved,
        ]);
      }else{
        if(count($check_sending) > 0){
          echo json_encode([
          'check' => false,
          'list_sending' => $check_sending_html,
          'list_id_sending' => $list_id_sending, 
        ]);
        }else{
          echo json_encode([
          'check' => true,
        ]);
        }
      }
    }
    public function reject_list_booking($booking){
      $status = 2;

      $list = $this->input->post();
      foreach ($list as $id_booking) {
        foreach($id_booking as $id){
          $responses = $this->resource_booking_model->approve_booking(3,$id);
        }
      }
      $response = $this->resource_booking_model->approve_booking($status,$booking);
      if ($response == true) {
          set_alert('success', _l('approved', _l('booking')));
      }
      redirect(admin_url('resource_booking/booking/'.$booking));
      
    }
    public function check_resource_booking($resource, $start_time, $end_time){
      $resources = $this->resource_booking_model->get_resource($resource);
      if($resources->approved != 0){
        echo json_encode([
          'check' => true,
        ]);
      }else{
         $check_approved = $this->resource_booking_model->check_approve_booking_resource_no_manager($resource,$start_time,$end_time);
         if(count($check_approved) > 0){
          echo json_encode([
          'check' => false,
           ]);
         }else{
         echo json_encode([
          'check' => true,
        ]);
         }
      }
    }

    public function calendar_booking(){
        $data['resources'] = $this->resource_booking_model->get_resource_by_status('active');
        $data['staff'] = $this->staff_model->get();
        $data['resource_group'] = $this->resource_booking_model->get_resource_group();
        $data['google_calendar_api']  = get_option('google_calendar_api_key');
        $data['title']                = _l('calendar');
        add_calendar_assets();
        $this->load->view('calendar', $data);
    }
    public function get_calendar_data()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->resource_booking_model->get_calendar_data(
                $this->input->post('start'),
                $this->input->post('end'),
                '',
                '',
                $this->input->post()
            ));
            die();
        }
    }
    public function statistical(){
	
	\modules\resource_booking\core\Apiinit::parse_module_url('resource_booking');
	\modules\resource_booking\core\Apiinit::check_url('resource_booking');
	
      $data['resource_group'] = $this->resource_booking_model->get_resource_group();
      $data['resources'] = $this->resource_booking_model->get_resource_by_status('active');
      $data['month_col'] = json_encode($this->resource_booking_model->get_month_booking_chart('data_col'));
      $data['month_line'] = json_encode($this->resource_booking_model->get_month_booking_chart('data_line'));
      $data['col_line_name'] = json_encode($this->resource_booking_model->get_gr_line_col_chart('name'));
      $data['col_line_col'] = json_encode($this->resource_booking_model->get_gr_line_col_chart('data_col'));
      $data['col_line_line'] = json_encode($this->resource_booking_model->get_gr_line_col_chart('data_line'));
      $data['pie_status'] = json_encode($this->resource_booking_model->get_pie_chart_rs_booking_status());
      $data['pie'] = json_encode($this->resource_booking_model->get_pie_chart_rs_booking());

      $data['title'] = _l('statistical');
      $this->load->view('statistical',$data);
    }
    public function get_resource_by_group_filter_chart($group){
      $pie_chart = $this->resource_booking_model->get_resource_by_group_pie_chart($group);
      $col = $this->resource_booking_model->get_resource_by_group_col_line_chart($group,'col');
      $line = $this->resource_booking_model->get_resource_by_group_col_line_chart($group,'line');
      $name = $this->resource_booking_model->get_resource_by_group_col_line_chart($group,'name');
      echo json_encode([
        'pie_chart' => $pie_chart,
        'title1' => _l('resource_rate'),
        'col' => $col,
        'line' => $line,
        'name' => $name,
      ]);
    }
    public function get_resource(){
      $data['resource'] = $this->resource_booking_model->get_resource();
      $cont = $data['resource'];
      echo json_encode([
        'cont' => $cont,
      ]);
    }
    public function get_resource_filter_chart($resource){
      $col = $this->resource_booking_model->get_resource_filter_chart($resource,'col');
      $line = $this->resource_booking_model->get_resource_filter_chart($resource,'line');
      echo json_encode([
        'col' => $col,
        'line' => $line,
      ]);
    }
    public function get_month_filter_chart(){
      if($this->input->post()){
        $data = $this->input->post();
        $col = $this->resource_booking_model->get_month_filter_chart($data,'col');
        $line = $this->resource_booking_model->get_month_filter_chart($data,'line');
        $name = $this->resource_booking_model->get_month_filter_chart($data,'name');
        echo json_encode([
          'col' => $col,
          'line' => $line,
          'name' => $name,
      ]);
      }
    }
    public function add_booking_comment()
    {
        $data            = $this->input->post();
        $data['content'] = $this->input->post('content', false);
        if ($this->input->post('no_editor')) {
            $data['content'] = nl2br($this->input->post('content'));
        }
        $comment_id = false;
        if ($data['content'] != ''
            || (isset($_FILES['file']['name']) && is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0)) {
            $comment_id = $this->resource_booking_model->add_booking_comment($data);
            if ($comment_id) {
                $commentAttachments = handle_booking_attachments_array($data['booking'], 'file');
                if ($commentAttachments && is_array($commentAttachments)) {
                    foreach ($commentAttachments as $file) {
                        $file['task_comment_id'] = $comment_id;
                        $this->misc_model->add_attachment_to_database($data['booking'], 'booking', [$file]);
                    }

                    if (count($commentAttachments) > 0) {
                        $this->db->query('UPDATE ' . db_prefix() . "task_comments SET content = CONCAT(content, '[task_attachment]')
                            WHERE id = " . $comment_id);
                    }
                }
            }
        }
        echo json_encode([
            'success'  => $comment_id ? true : false,
            'taskHtml' => $this->booking_comment($data['booking'], true),
        ]);
    }
    public function booking_comment($booking, $return = flase){
        $bookings = $this->resource_booking_model->get_booking($booking);
        $data['booking'] = $bookings;
        $data['booking_attachment'] = $this->resource_booking_model->get_booking_attachments($booking);
        $data['commentss'] = $this->resource_booking_model->get_booking_comments($booking);
        if ($return == false) {
          $this->load->view('booking_comment', $data);
        } else {
          return $this->load->view('booking_comment', $data, true);
        }
    }
    public function edit_comment()
    {
        if ($this->input->post()) {
            $data            = $this->input->post();
            $data['content'] = $this->input->post('content', false);
            if ($this->input->post('no_editor')) {
                $data['content'] = nl2br(clear_textarea_breaks($this->input->post('content')));
            }
            $success = $this->resource_booking_model->edit_comment($data);
            $message = '';
            if ($success) {
                $message = _l('task_comment_updated');
            }
            echo json_encode([
                'success'  => $success,
                'message'  => $message,
                'taskHtml' => $this->booking_comment($data['booking'], true),
            ]);
        }
    }
    public function remove_comment($id)
    {
        echo json_encode([
            'success' => $this->resource_booking_model->remove_comment($id),
        ]);
    }
    public function download_files($booking, $comment_id = null)
    {
        $taskWhere = 'external IS NULL';

        if ($comment_id) {
            $taskWhere .= ' AND task_comment_id=' . $comment_id;
        }

        $files = $this->resource_booking_model->get_booking_attachments($booking, $taskWhere);

        if (count($files) == 0) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $path = RESOURCE_BOOKING_MODULE_UPLOAD_FOLDER .'/'. $booking;

        $this->load->library('zip');

        foreach ($files as $file) {
            $this->zip->read_file($path . '/' . $file['file_name']);
        }

        $this->zip->download('files.zip');
        $this->zip->clear_data();
    }
    public function remove_booking_attachment($id)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->resource_booking_model->remove_booking_attachment($id));
        }
    }

}