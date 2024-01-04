<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Team password client controller
 */
class Team_password_client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('team_password_model');
    }
      /**
     * team password management
     * @return view
     */
      public function team_password_mgt(){
          if (!is_client_logged_in()) {
            redirect(site_url('authentication/login'));
          }

          $contact_id = get_contact_user_id();
          $data_contact = $this->team_password_model->get_contact($contact_id);
          $data['share'] = [];


          if($data_contact && isset($data_contact->email)){
            $email = $data_contact->email;
            if($email){
              $data['share'] = $this->team_password_model->get_data_share($email);
            }        
          }

          $data['title'] = _l('team_password');
          $this->data($data);
          $this->view('team_password_mgt/client/team_password_mgt');
          $this->layout();
      }
     /**
     * view share client
     * @param string $hash
     * @param string $type
     * @return view       
     */
    public function view_share_client($hash='',$type = ''){
      if (!is_client_logged_in() && !is_staff_logged_in()) {
        redirect(site_url('authentication/login'));
      }
      $data_share = $this->team_password_model->get_tp_share_hash($hash);
      if($data_share){
        $data['r'] = $data_share->r;
        $data['w'] = $data_share->w;
        $data['share_id'] = $data_share->share_id;
        $data['effective_time'] = $data_share->effective_time;

		    if(strtotime($data['effective_time'])<=strtotime(date('Y-m-d H:i:s'))){ 
          error_page(_l('team_password'),'<i class="fa fa-clock-o" aria-hidden="true"></i> '._l('this_page_has_expired'));  
        }
        else{

            $data['type'] = $data_share->type;
            switch ($data['type']) {
              case 'normal':
                $data['normal'] = $this->team_password_model->get_normal($data['share_id']);

                $name = '';     
                if($data['normal']){          
                  if($data['normal']->enable_log == 'on'){
                      $name = $data['normal']->name;
                      $data['title'] = $name;
                      $data['id'] = $data['share_id'];
                      
                      if(is_staff_logged_in()){
                        $this->data($data);
                        $this->view('team_password_mgt/client/view_normal_client');
                        $this->layout();
                      }else{
                      
                      if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
                            $data['category'] = $this->team_password_model->get_category_management();
                            $this->data($data);
                            $this->view('team_password_mgt/client/edit_normal_client');
                            $this->layout();
                      }
                      elseif($data['r'] == 'on'){
                            $this->data($data);
                            $this->view('team_password_mgt/client/view_normal_client');
                            $this->layout();                
                      }
                    }
                   } 
                   else{
                     error_page(_l('team_password'),'<i class="fa fa-pause-circle" aria-hidden="true"></i> '._l('this_page_has_not_been_activated'));
                   }      
                }  
                else{        
                    error_page(_l('team_password'),'<i class="fa fa-database" aria-hidden="true"></i> '._l('this_page_data_does_not_exist'));
                }
              break;  
              case 'bank_account':
                $data['bank_account'] = $this->team_password_model->get_bank_account($data['share_id']);
                $name = '';     
                if($data['bank_account']){          
                  if($data['bank_account']->enable_log == 'on'){


                      $name = $data['bank_account']->name;
                      $data['title'] = $name;
                      $data['id'] = $data['share_id'];

                      if(is_staff_logged_in()){
                        $this->data($data);
                        $this->view('team_password_mgt/client/view_bank_account_client');
                        $this->layout();
                      }else{

                      if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
                            $data['category'] = $this->team_password_model->get_category_management();
                            $this->data($data);
                            $this->view('team_password_mgt/client/edit_bank_account_client');
                            $this->layout();
                      }
                      elseif($data['r'] == 'on'){
                            $this->data($data);
                            $this->view('team_password_mgt/client/view_bank_account_client');
                            $this->layout();                
                      }
                    }
                   } 
                   else{
                      error_page(_l('team_password'),'<i class="fa fa-pause-circle" aria-hidden="true"></i> '._l('this_page_has_not_been_activated'));
                   }      
                }  
                else{
                     error_page(_l('team_password'),'<i class="fa fa-database" aria-hidden="true"></i> '._l('this_page_data_does_not_exist'));
                }
              break; 
               case 'credit_card':
                $data['credit_card'] = $this->team_password_model->get_credit_card($data['share_id']);
                $name = '';     
                if($data['credit_card']){          
                  if($data['credit_card']->enable_log == 'on'){


                      $name = $data['credit_card']->name;
                      $data['title'] = $name;
                      $data['id'] = $data['share_id'];

                      if(is_staff_logged_in()){
                        $this->data($data);
                        $this->view('team_password_mgt/client/view_credit_card_client');
                        $this->layout();
                      }else{

                      if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
                            $data['category'] = $this->team_password_model->get_category_management();
                            $this->data($data);
                            $this->view('team_password_mgt/client/edit_credit_card_client');
                            $this->layout();
                      }
                      elseif($data['r'] == 'on'){
                            $this->data($data);
                            $this->view('team_password_mgt/client/view_credit_card_client');
                            $this->layout();                
                      }
                    }
                   } 
                   else{
                      error_page(_l('team_password'),'<i class="fa fa-pause-circle" aria-hidden="true"></i> '._l('this_page_has_not_been_activated'));
                   }      
                }  
                else{
                     error_page(_l('team_password'),'<i class="fa fa-database" aria-hidden="true"></i> '._l('this_page_data_does_not_exist'));
                }
              break; 
              case 'email':
                          $data['email'] = $this->team_password_model->get_email($data['share_id']);
                          $name = '';     
                          if($data['email']){          
                            if($data['email']->enable_log == 'on'){


                                $name = $data['email']->name;
                                $data['title'] = $name;
                                $data['id'] = $data['share_id'];

                                if(is_staff_logged_in()){
                                  $this->data($data);
                                  $this->view('team_password_mgt/client/view_email_client');
                                  $this->layout(); 
                                }else{

                                if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
                                      $data['category'] = $this->team_password_model->get_category_management();
                                      $this->data($data);
                                      $this->view('team_password_mgt/client/edit_email_client');
                                      $this->layout();
                                }
                                elseif($data['r'] == 'on'){
                                      $this->data($data);
                                      $this->view('team_password_mgt/client/view_email_client');
                                      $this->layout();                
                                }
                              }
                             } 
                             else{
                                error_page(_l('team_password'),'<i class="fa fa-pause-circle" aria-hidden="true"></i> '._l('this_page_has_not_been_activated'));
                             }      
                          }  
                          else{
                               error_page(_l('team_password'),'<i class="fa fa-database" aria-hidden="true"></i> '._l('this_page_data_does_not_exist'));
                          }
                  break; 
                  case 'server':
                          $data['server'] = $this->team_password_model->get_server($data['share_id']);
                          $name = '';     
                          if($data['server']){          
                            if($data['server']->enable_log == 'on'){


                                $name = $data['server']->name;
                                $data['title'] = $name;
                                $data['id'] = $data['share_id'];
                                if(is_staff_logged_in()){
                                  $this->data($data);
                                  $this->view('team_password_mgt/client/view_email_client');
                                  $this->layout(); 
                                }else{

                                if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
                                      $data['category'] = $this->team_password_model->get_category_management();
                                      $this->data($data);
                                      $this->view('team_password_mgt/client/edit_server_client');
                                      $this->layout();
                                }
                                elseif($data['r'] == 'on'){
                                      $this->data($data);
                                      $this->view('team_password_mgt/client/view_server_client');
                                      $this->layout();                
                                }
                              }
                             } 
                             else{
                                error_page(_l('team_password'),'<i class="fa fa-pause-circle" aria-hidden="true"></i> '._l('this_page_has_not_been_activated'));
                             }      
                          }  
                          else{
                               error_page(_l('team_password'),'<i class="fa fa-database" aria-hidden="true"></i> '._l('this_page_data_does_not_exist'));
                          }
                  break; 
                  case 'software_license':
                          $data['software_license'] = $this->team_password_model->get_software_license($data['share_id']);
                          $name = '';     
                          if($data['software_license']){          
                            if($data['software_license']->enable_log == 'on'){


                                $name = $data['software_license']->name;
                                $data['title'] = $name;
                                $data['id'] = $data['share_id'];
                                if(is_staff_logged_in()){
                                  $this->data($data);
                                  $this->view('team_password_mgt/client/view_software_license_client');
                                  $this->layout();      
                                }else{

                                if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
                                      $data['category'] = $this->team_password_model->get_category_management();
                                      $this->data($data);
                                      $this->view('team_password_mgt/client/edit_software_license_client');
                                      $this->layout();
                                }
                                elseif($data['r'] == 'on'){
                                      $this->data($data);
                                      $this->view('team_password_mgt/client/view_software_license_client');
                                      $this->layout();                
                                }
                              }
                             } 
                             else{
                                error_page(_l('team_password'),'<i class="fa fa-pause-circle" aria-hidden="true"></i> '._l('this_page_has_not_been_activated'));
                             }      
                          }  
                          else{
                               error_page(_l('team_password'),'<i class="fa fa-database" aria-hidden="true"></i> '._l('this_page_data_does_not_exist'));
                          }
                  break; 

            }
        }      
      }
      else{
        die;
      }
    }
     /**
     * add normal
     * @param id
     * @return redirect
     */
    public function add_normal($id = '')
    {
        if (!is_client_logged_in()) {
            redirect(site_url('authentication/login'));
        }

        $data['title'] = _l('add_normal');
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            if (!$data['id'] == '') {
                $success = $this->team_password_model->update_normal($data);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
                redirect(site_url('team_password/team_password_client/team_password_mgt'));
            }
            die;
        }
    }
      /**
     * add bank account
     * @param id
     * @return redirect
     */
    public function add_bank_account($id = '')
    {   
      if (!is_client_logged_in()) {
        redirect(site_url('authentication/login'));
      }

        $data['title'] = _l('add_bank_account');
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            if ($data['id'] == '') {
                $insert_id = $this->team_password_model->add_bank_account($data);
                if ($insert_id) {
                    $success = true;
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
               redirect(site_url('team_password/team_password_client/team_password_mgt'));
            } else {
                $success = $this->team_password_model->update_bank_account($data);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
                redirect(site_url('team_password/team_password_client/team_password_mgt'));
            }
            die;
        }
    }
      /**
     * add credit card
     * @param id
     * @return redirect
     */
    public function add_credit_card($id = '')
    {   
        if (!is_client_logged_in()) {
          redirect(site_url('authentication/login'));
        }

        $data['title'] = _l('add_credit_card');
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            if ($data['id'] == '') {
                $insert_id = $this->team_password_model->add_credit_card($data);
                if ($insert_id) {
                    $success = true;
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
                redirect(site_url('team_password/team_password_client/team_password_mgt'));
            } else {
                $success = $this->team_password_model->update_credit_card($data);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
                redirect(site_url('team_password/team_password_client/team_password_mgt'));
            }
            die;
        }
    }

     /**
       * add email
       * @param id
       * @return redirect
      */
        public function add_email($id = '')
        {   
            if (!is_client_logged_in()) {
              redirect(site_url('authentication/login'));
            }
            $data['title'] = _l('add_email');
            if ($this->input->post()) {
                $message          = '';
                $data             = $this->input->post();
                if ($data['id'] == '') {
                    $insert_id = $this->team_password_model->add_email($data);
                    if ($insert_id) {
                        $success = true;
                        $message = _l('added_successfully');
                        set_alert('success', $message);
                    }
                    redirect(site_url('team_password/team_password_client/team_password_mgt'));
                } else {
                    $success = $this->team_password_model->update_email($data);
                    if ($success) {
                        $message = _l('updated_successfully');
                        set_alert('success', $message);
                    }
                    redirect(site_url('team_password/team_password_client/team_password_mgt'));
                }
                die;
            }
        }
               /**
     * add server
     * @param id
     * @return redirect
     */
    public function add_server($id = '')
    { 
        if (!is_client_logged_in()) {
          redirect(site_url('authentication/login'));
        }

        $data['title'] = _l('add_server');
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            if ($data['id'] == '') {
                $insert_id = $this->team_password_model->add_server($data);
                if ($insert_id) {
                    $success = true;
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
                redirect(site_url('team_password/team_password_client/team_password_mgt'));
            } else {
                $success = $this->team_password_model->update_server($data);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
                redirect(site_url('team_password/team_password_client/team_password_mgt'));
            }
            die;
        }
    }
     /**
     * add software license
     * @param int id
     * @return redirect
     */
    public function add_software_license($id = '')
    {   
        if (!is_client_logged_in()) {
          redirect(site_url('authentication/login'));
        }

        $data['title'] = _l('add_software_license');
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            if ($data['id'] == '') {
                $insert_id = $this->team_password_model->add_software_license($data);
                if ($insert_id) {
                    $success = true;
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
               redirect(site_url('team_password/team_password_client/team_password_mgt'));
            } else {
                $success = $this->team_password_model->update_software_license($data);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
               redirect(site_url('team_password/team_password_client/team_password_mgt'));
            }
            die;
        }
    }

    /**
     * { file item }
     *
     * @param        $id      The identifier
     * @param        $rel_id  The relative identifier
     */
    public function file_item($id, $rel_id, $type)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin']             = is_admin();
        $data['file'] = $this->team_password_model->get_file($id, $rel_id);
        $data['types'] = $type;
        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->load->view('team_password_mgt/_file', $data);
    }
}