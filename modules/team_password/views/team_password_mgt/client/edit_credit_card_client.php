<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
    $valid_from = _d($credit_card->valid_from);
    $valid_to = _d($credit_card->valid_to);
    $notice = $credit_card->notice;
  }






 ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
   <div class="clearfix"></div><br>
   <div class="col-md-12">
    <h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo html_entity_decode($title); ?></h4>
   </div>

    <div class="clearfix"></div>
    <hr class="hr-panel-heading" />
    <div class="clearfix"></div>
      <?php echo form_open(site_url('team_password/team_password_client/add_credit_card'),array('id'=>'form_add_credit_card')); ?>              
        <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
                <?php echo render_input('name','name',$name,'',array('required'=>true)); ?>
            </div>
                        <div class="col-md-12">
            <?php 
              echo render_select('mgt_id',$category,array('id','category_name'),'category_managements',$mgt_id,array('required'=>true));
            ?>
            </div>
          <div class="col-md-12">
              <div class="form-group">
                   <label for="gst"><?php echo _l('pin'); ?></label>            
              <div class="input-group">
                <a href="#" class="input-group-addon view_password"><i class="fa fa-eye"></i></a>
                  <input type="password" disabled class="form-control" name="pin" required value="<?php echo html_entity_decode($pin); ?>">                    
              </div>
        </div>
      </div>
      <div class="col-md-12">
        <?php echo render_input('credit_card_type','credit_card_type',$credit_card_type,'',array('required'=>true)); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_input('card_number','card_number',$card_number,'',array('required'=>true)); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_input('card_cvc','card_cvc',$card_cvc,'',array('required'=>true)); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_date_input('valid_from','valid_from',$valid_from); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_date_input('valid_to','valid_to',$valid_to); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_textarea('notice','notice',$notice); ?>
      </div>

        
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
                      </div>';
                       $file_html .= '</div>';
                    }
                    $file_html .= '<hr />';
                    echo html_entity_decode($file_html);
                }
             ?>
          </div>
          <?php } ?>
          <div id="ic_file_data"></div>

        </div>
        <div class="modal-footer">
            <a href="javascript:history.back()" class="btn btn-danger"><?php echo _l('close'); ?></a>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        </div>
        <?php echo form_close(); ?>
  </div>
  </div>
 </div>
</div>



<div class="modal fade" id="custom_fields" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('add_field'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
          <div class="row">
               <div class="col-md-12"><?php echo render_input('field_name','field_name'); ?></div>
               <div class="col-md-12"><?php echo render_input('field_value','field_value'); ?></div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="create_customfield();" data-dismiss="modal"><?php echo _l('save'); ?></button>
            </div>
          </div>
    </div>
</div>
