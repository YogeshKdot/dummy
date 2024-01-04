<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 

  $id = '';
  $name = '';
  $url = '';
  $user_name = '';
  $pin = '';
  $bank_name = '';
  $bank_code = '';
  $account_holder = '';
  $account_number = '';
  $iban = '';
  $notice = '';
  $password = '';
  $enable_log = '';
  $custom_field = [];
  $datecreator = '';
  $mgt_id = '';

  if(isset($bank_account)){
  $id = $bank_account->id;
  $name = $bank_account->name;
  $url = $bank_account->url;
  $user_name = $bank_account->user_name;
  $pin = AES_256_Decrypt($bank_account->pin);
  $bank_name = $bank_account->bank_name;
  $bank_code = $bank_account->bank_code;
  $account_holder = $bank_account->account_holder;
  $account_number = $bank_account->account_number;
  $iban = $bank_account->iban;
  $notice = $bank_account->notice;
  $password =  AES_256_Decrypt($bank_account->password);
  $enable_log = $bank_account->enable_log;
  $custom_field =json_decode($bank_account->custom_field);
  $mgt_id = $bank_account->mgt_id;
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
	    <?php echo form_open(site_url('team_password/team_password_client/add_bank_account'),array('id'=>'form_category_management')); ?>	            
        <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
            	<input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">

                <?php echo render_input('name','name',$name,'',array('required'=>true)); ?>
            </div>
            <div class="col-md-12">
                <?php echo render_input('url','url',$url); ?>
                <?php echo render_input('user_name','user_name',$user_name,'',array('required'=>true)); ?>
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
        <?php echo render_input('bank_name','bank_name',$bank_name); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_input('bank_code','bank_code',$bank_code); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_input('account_holder','account_holder',$account_holder); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_input('account_number','account_number',$account_number); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_input('iban','IBAN',$iban); ?>
      </div>
      <div class="col-md-12">
        <?php echo render_textarea('notice','notice',$notice); ?>
      </div>

          <div class="col-md-12" id="ic_pv_file">
            <?php
             if(isset($bank_account)){
                $attachments = get_item_tp_attachment($bank_account->id,'tp_bank');
                $file_html = '';
                $type_item = 'tp_bank';
                if(count($attachments) > 0){
                    $file_html .= '<hr />
                            <p class="bold text-muted">'._l('attachments').'</p>';
                    foreach ($attachments as $f) {
                        $href_url = site_url(TEAM_PASSWORD_PATH.'tp_bank/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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
	</div>
        <?php echo form_close(); ?>
  </div>
 </div>
</div>



<div class="modal fade" id="custom_fields" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('add_custom_field'); ?></span>
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

