<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 

  $id = '';
  $name = '';
  $version = '';
  $url = '';
  $license_key = '';
  $notice = '';
  $host = '';
  $port = '';
  $password = '';
  $enable_log = '';
  $mgt_id = '';
  $custom_field = [];
  $datecreator = '';
  if(isset($software_license)){
      $id = $software_license->id;
      $name = $software_license->name;
      $version = $software_license->version;
      $url = $software_license->url;
      $license_key = $software_license->license_key;
      $notice = $software_license->notice;
      $host = $software_license->host;
      $port = $software_license->port;
      $password =  AES_256_Decrypt($software_license->password);
      $enable_log = $software_license->enable_log;
      $mgt_id = $software_license->mgt_id;
      $custom_field = json_decode($software_license->custom_field);
      $datecreator = $software_license->datecreator;
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
	    <?php echo form_open(site_url('team_password/team_password_client/add_software_license'),admin_url('team_password/add_software_license'),array('id'=>'form_category_management')); ?>	            
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
              <?php echo render_input('url','url',$url); ?>
                <?php echo render_input('version','version',$version); ?>
                <?php echo render_input('license_key','license_key',$license_key,'',array('required'=>true)); ?>    
            </div>
      <div class="col-md-12">
          <?php echo render_textarea('notice','notice',$notice); ?>
      </div>

          <div class="col-md-12" id="ic_pv_file">
            <?php
             if(isset($software_license)){
                $attachments = get_item_tp_attachment($software_license->id,'tp_software_license');
                $file_html = '';
                $type_item = 'tp_software_license';
                if(count($attachments) > 0){
                    $file_html .= '<hr />
                            <p class="bold text-muted">'._l('attachments').'</p>';
                    foreach ($attachments as $f) {
                        $href_url = site_url(TEAM_PASSWORD_PATH.'tp_software_license/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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
                    <span class="add-title"><?php echo _l('custom_fields'); ?></span>
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
