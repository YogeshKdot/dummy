  <?php 
  if(is_admin() || has_permission('fixed_equipment_assets', '', 'view')){
    ?>
    <button class="btn btn-primary" onclick="upload_file()"><?php echo _l('fe_upload_file'); ?></button>
    <div class="clearfix"></div>
    <br>
    <div class="clearfix"></div>
  <?php  } ?>
  <div id="ic_pv_file">
    <?php
    $attachments = fe_get_item_file_attachment($id, 'asset_files');
    $file_html = '';
    $type_item = 'asset_files';
    if(count($attachments) > 0){
      $file_html .= '<div class="list-file">';
      foreach ($attachments as $f) {
        $file_html .= '<hr><br/>';
        $href_url = site_url(FIXED_EQUIPMENT_PATH.'asset_files/'.$f['rel_id'].'/'.$f['file_name']).'" download';
        if(!empty($f['external'])){
          $href_url = $f['external_link'];
        }
        $file_html .= '<div class="mbot5 row inline-block full-width" data-attachment-id="'. $f['id'].'">
        <div class="col-md-8">
        <a name="preview-ic-btn" onclick="preview_ic_btn(this); return false;" rel_id = "'. $f['rel_id']. '" type_item = "'. $type_item. '" id = "'.$f['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left mright5" data-toggle="tooltip" title data-original-title="'. _l('fe_preview_file').'"><i class="fa fa-eye"></i></a>
        <div class="pull-left"><i class="'. get_mime_class($f['filetype']).'"></i></div>
        <a href=" '. $href_url.'" target="_blank" download>'.$f['file_name'].'</a>
        <br />
        <small class="text-muted">'.$f['filetype'].'</small>
        </div>
        <div class="col-md-4 text-right">';
        if(is_admin() || has_permission('fixed_equipment_assets', '', 'view')){
          $file_html .= '<a href="#" class="text-danger mtop15" onclick="delete_ic_attachment('. $f['id'].',this); return false;" type_item = "'. $type_item. '"><i class="fa fa-times"></i></a>';
        }
        $file_html .= '</div></div>';
        $file_html .= '<hr>';
      }
      $file_html .= '</div>';
      echo html_entity_decode($file_html);
    }
    ?>
  </div>
  <div class="modal fade" id="upload_file" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">
           <span class="add-title"><?php echo _l('fe_upload_file'); ?></span>
         </h4>
       </div>
       <?php echo form_open_multipart(admin_url('fixed_equipment/upload_asset_file'),array('id'=>'asset_file-form')); ?>
       <div class="modal-body">
        <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
        <input type="file" accept="image/*,.csv,.zip,.rar,.doc,.docx,.xls,.xlsx,.xml,.lic,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/plain,.pdf,application/rtf" data-maxsize="536870912" class="form-control" name="attachments">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info"><?php echo _l('fe_upload'); ?></button>
      </div>
      <?php echo form_close(); ?>                 
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="ic_file_data"></div>