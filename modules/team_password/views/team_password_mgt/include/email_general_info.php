<?php 

    $id = '';
  $name = '';
  $pin = '';
  $credit_card_type = '';
  $card_number = '';
  $card_cvc = '';
  $notice = '';
  $email_type = '';
  $auth_method = '';
  $host = '';
  $port = '';
  $smtp_auth_method = '';
  $smtp_host = '';
  $smtp_port = '';
  $smtp_user_name = '';
  $smtp_password = '';
  $password = '';
  $enable_log = '';
  $mgt_id = '';
  $custom_field = [];
  $datecreator = '';
  $user_name = '';
  if(isset($email)){
      $id = $email->id;
      $name = $email->name;
      $pin = $email->pin;
      $credit_card_type = $email->credit_card_type;
      $card_number = $email->card_number;
      $card_cvc = $email->card_cvc;
      $notice = $email->notice;
      $email_type = $email->email_type;
      $auth_method = $email->auth_method;
      $host = $email->host;
      $port = $email->port;
      $smtp_auth_method = $email->smtp_auth_method;
      $smtp_host = $email->smtp_host;
      $smtp_port = $email->smtp_port;
      $smtp_user_name = $email->smtp_user_name;
      $smtp_password = AES_256_Decrypt($email->smtp_password);
      $password = AES_256_Decrypt($email->password);
      $enable_log = $email->enable_log;
      $mgt_id = $email->mgt_id;
      $custom_field = json_decode($email->custom_field);
      $datecreator = $email->datecreator;
      $user_name = $email->user_name;
  }
 ?>
<div class="col-md-8">
<table class="table border table-striped ">
 <tbody>                                  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('name'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($name); 

           ?>      
        </td>
     </tr> 
     <tr class="project-overview">
        <td class="bold"><?php echo _l('category'); ?>
        </td>
        <td>                                        
          <?php
            
             $category_name = '';
            if($mgt_id){
              $data_category = $this->team_password_model->get_category_management($mgt_id); 
              if($data_category){
                   $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
              }      
            }
            echo html_entity_decode($category_name); 

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('email_type'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($email_type); 

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('auth_method'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($auth_method); 

           ?>      
        </td>
     </tr>  
          <tr class="project-overview">
        <td class="bold"><?php echo _l('host'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($host); 

           ?>      
        </td>
     </tr> 
          <tr class="project-overview">
        <td class="bold"><?php echo _l('port'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($port); 

           ?>      
        </td>
     </tr> 
          <tr class="project-overview">
        <td class="bold"><?php echo _l('user_name'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($user_name); 

           ?>      
        </td>
     </tr> 
     <tr class="project-overview">
        <td class="bold"><?php echo _l('password'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($password); 

           ?>      
        </td>
     </tr> 
     <tr class="project-overview">
        <td class="bold"><?php echo _l('smtp_auth_method'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($smtp_auth_method); 

           ?>      
        </td>
     </tr>
     <tr class="project-overview">
        <td class="bold"><?php echo _l('smtp_host'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($smtp_host); 

           ?>      
        </td>
     </tr>
     <tr class="project-overview">
        <td class="bold"><?php echo _l('smtp_port'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($smtp_port); 

           ?>      
        </td>
     </tr>
     <tr class="project-overview">
        <td class="bold"><?php echo _l('smtp_user_name'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($smtp_user_name); 

           ?>      
        </td>
     </tr>
          <tr class="project-overview">
        <td class="bold"><?php echo _l('smtp_password'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($smtp_password); 

           ?>      
        </td>
     </tr>
      <tr class="project-overview">
        <td class="bold"><?php echo _l('related').' '._l($email->relate_to); ?>

        </td>
        <td>                                        
          <?php
            $contracts = get_contract_relate('email',$email->id);

            if($contracts){
              if($email->relate_to == 'contract'){
                foreach($contracts as $ctr){
                  $contract = get_contract_id($ctr);
                  if($contract){
                    echo '<a href="'.site_url('contract/'.$contract->id.'/'.$contract->hash).'" ><span class="label label-tag"> '.html_entity_decode( get_company_name($contract->client).' - '.$contract->subject).'</span></a>'; 
                  }else{
                    echo '';
                  }
                }
              }elseif($email->relate_to == 'project'){
                foreach($contracts as $pj){
                  $project = get_project_id($pj);
                  if($project){
                    echo '<a href="'.admin_url('projects/view/'.$project->id).'" ><span class="label label-tag"> '.html_entity_decode( get_company_name($project->id).' - '.$project->name).'</span></a>'; 
                  }else{
                    echo '';
                  }
                }
              }else{
                echo '';
              }
            }else{
              echo '';
            }

           ?>      
        </td>
     </tr> 


     <tr class="project-overview">
        <td class="bold"><?php echo _l('notice'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($notice); 

           ?>      
        </td>
     </tr>  
   
     <?php if(count($custom_field)>0){ ?>
     <tr class="project-overview">
        <td class="bold"><?php echo _l('custom_field'); ?>

        </td>
        <td>                                        
           <?php foreach ($custom_field as $key => $tag) { ?>
             &nbsp;<span class="btn btn-default ptop-10 tag">
             <label  name="field_name[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->name); ?></label>&nbsp; - &nbsp;<label  name="field_value[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->value); ?></label>&nbsp;
             </span>&nbsp;
            <?php } ?>     
        </td>
     </tr>   
     <?php } ?> 
      <tr class="project-overview">
        <td class="bold"><?php echo _l('enable_log'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($enable_log); 

           ?>      
        </td>
     </tr>       
    </tbody>
  </table>

    <div class="col-md-12" id="ic_pv_file">
  <?php
   if(isset($email)){
      $attachments = get_item_tp_attachment($email->id,'tp_email');
      $file_html = '';
      $type_item = 'tp_email';
      if(count($attachments) > 0){
          $file_html .= '<hr />
                  <p class="bold text-muted">'._l('attachments').'</p>';
          foreach ($attachments as $f) {
              $href_url = site_url(TEAM_PASSWORD_PATH.'tp_email/'.$f['rel_id'].'/'.$f['file_name']).'" download';
                              if(!empty($f['external'])){
                                $href_url = $f['external_link'];
                              }
             $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="'. $f['id'].'">
            <div class="col-md-8">
               <a name="preview-ic-btn" onclick="preview_ic_btn(this); return false;" rel_id = "'. $f['rel_id']. '" type_item = "'. $type_item. '" id = "'.$f['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left mright5" data-toggle="tooltip" title data-original-title="'. _l('preview_file').'"><i class="fa fa-eye"></i></a>
               <div class="pull-left"><i class="'. get_mime_class($f['filetype']).'"></i></div>
               <a href=" '. $href_url.'" target="_blank" download>'.$f['file_name'].'</a>
               <br />
               <small class="text-muted">'.$f['filetype'].'</small>
            </div>
            <div class="col-md-4 text-right">';
              if($f['staffid'] == get_staff_user_id() || is_admin()){
              $file_html .= '<a href="#" class="text-danger" onclick="delete_ic_attachment('. $f['id'].',this); return false;" type_item = "'. $type_item. '"><i class="fa fa-times"></i></a>';
              } 
             $file_html .= '</div></div>';
          }
          $file_html .= '<hr />';
          echo html_entity_decode($file_html);
      }
   ?>
</div>
<?php } ?>
<div id="ic_file_data"></div>
</div>

<?php if(has_permission('team_password','','view') || is_admin()){ ?>
<div class="col-md-4">
  <h4 class="no-margin font-bold" ><?php echo _l('activity_log'); ?></h4>
  <hr />
  <div class="activity-feed">

    <?php foreach($logs as $b){ ?>
    <div class="feed-item">
      <div class="date"><span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($b['time']); ?>">
        <?php echo time_ago($b['time']); ?>
      </span>
    </div>
      <div class="text">
       <p class="bold no-mbot">
        <a href="<?php echo admin_url('profile/'.$b['staff']); ?>"><?php echo get_staff_full_name($b['staff']); ?></a> -
        <?php echo _l($b['type']); ?></p>
        <?php echo html_entity_decode($name); ?><br>
      </div>
    </div>
    <?php } ?>
  </div>
  <a href="<?php echo admin_url('team_password/clear_logs/'.$id.'/email'); ?>" class="btn btn-danger pull-right mtop5 mbot5"><?php echo _l('clear_log'); ?></a>
</div>
<?php } ?>
<br>