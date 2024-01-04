<?php 


  $id = '';
  $name = '';
  $pin = '';
  $credit_card_type = '';
  $card_number = '';
  $card_cvc = '';
  $valid_from = '';
  $valid_to = '';
  $notice = '';
  $password = '';
  $enable_log = '';
  $mgt_id = '';
  $custom_field = [];


  if(isset($credit_card)){
    $id = $credit_card->id;
    $name = $credit_card->name;
    $password = AES_256_Decrypt($credit_card->password);
    $custom_field = json_decode($credit_card->custom_field);
    $enable_log = $credit_card->enable_log;
    $mgt_id = $credit_card->mgt_id;
    $pin = AES_256_Decrypt($credit_card->pin);
    $credit_card_type = $credit_card->credit_card_type;
    $card_number = $credit_card->card_number;
    $card_cvc = $credit_card->card_cvc;
    $valid_from = $credit_card->valid_from;
    $valid_to = $credit_card->valid_to;
    $notice = $credit_card->notice;
  }

 ?>
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
        <td class="bold"><?php echo _l('pin'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($pin); 

           ?>      
        </td>
    </tr>  
    <tr class="project-overview">
        <td class="bold"><?php echo _l('credit_card_type'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($credit_card_type); 

           ?>      
        </td>
     </tr>  
    <tr class="project-overview">
        <td class="bold"><?php echo _l('card_number'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($card_number); 

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('card_cvc'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($card_cvc); 

           ?>      
        </td>
     </tr> 


     <tr class="project-overview">
        <td class="bold"><?php echo _l('valid_from'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($valid_from); 

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('valid_to'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($valid_to); 

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
             if(isset($credit_card)){
                $attachments = get_item_tp_attachment($credit_card->id,'tp_credit_card');
                $file_html = '';
                $type_item = 'tp_credit_card';
                if(count($attachments) > 0){
                    $file_html .= '<hr />
                            <p class="bold text-muted">'._l('attachments').'</p>';
                    foreach ($attachments as $f) {
                        $href_url = site_url(TEAM_PASSWORD_PATH.'tp_credit_card/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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
                        $file_html .= '<a href="#" class="text-danger" onclick="delete_ic_attachment('. $f['id'].'); return false;"><i class="fa fa-times"></i></a>';
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