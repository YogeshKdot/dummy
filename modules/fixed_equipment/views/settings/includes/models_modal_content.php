 <?php 
 $id = '';
 $model_name = '';
 $manufacturer = '';
 $category = '';
 $model_no = '';
 $depreciation = '';
 $eol = '';
 $note = '';
 $may_request = 0;
 $custom_field = [];
 $fieldset_id = 0;
 if(isset($model)){
   $id = $model->id;
   $model_name = $model->model_name;
   $manufacturer = $model->manufacturer;
   $category = $model->category;
   $model_no = $model->model_no;
   $depreciation = $model->depreciation;
   $eol = $model->eol;
   $note = $model->note;
   $may_request = $model->may_request;
   $custom_field = $model->custom_field;
   $fieldset_id = $model->fieldset_id;
 }
 ?>


 <div class="row">
   <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">  
   <div class="col-md-12">
    <?php echo render_input('model_name', 'fe_model_name', $model_name); ?>
  </div>
  <div class="col-md-6">
    <?php echo render_select('manufacturer', $manufacturers, array('id', 'name'), 'fe_manufacturer', $manufacturer); ?>
  </div>
  <div class="col-md-6">
    <?php echo render_select('category', $categories, array('id', 'category_name'), 'fe_category', $category); ?>
  </div>
  <div class="col-md-6">
    <?php echo render_input('model_no', 'fe_model_no', $model_no); ?>
  </div>
  <div class="col-md-6">
    <?php echo render_select('depreciation', $depreciations, array('id', 'name'), 'fe_depreciation', $depreciation); ?>
  </div>
  <div class="col-md-6">
   <div class="form-group">
    <label for="gst">EOL</label>            
    <div class="input-group">
      <input type="text" class="form-control" name="eol" value="<?php echo html_entity_decode($eol); ?>">
      <span class="input-group-addon"><?php echo _l('fe_months'); ?></span>
    </div>
  </div>
</div>
<div class="col-md-6">
  <br>
  <div class="checkbox mtop15">              
    <input type="checkbox" class="capability" name="may_request" value="1" <?php if($may_request == 1){ echo "checked"; } ?>>
    <label><?php echo _l('fe_users_may_request_this_model'); ?></label>
  </div>
</div>
<div class="col-md-12">
  <?php echo render_select('fieldset_id', $field_sets, array('id', 'name'), 'fe_fieldset', $fieldset_id); ?>
</div>
<div class="col-md-12">
  <?php echo render_textarea('note', 'fe_note', $note); ?>
</div>

<div class="col-md-12" id="ic_pv_file">
  <?php
  if(isset($model)){
    $attachments = fe_get_item_file_attachment($model->id, 'models');
    $file_html = '';
    $type_item = 'models';
    if(count($attachments) > 0){
      $file_html .= '<div class="list-file">';
      foreach ($attachments as $f) {
        $href_url = site_url(FIXED_EQUIPMENT_PATH.'models/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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
        $file_html .= '<a href="#" class="text-danger" onclick="delete_ic_attachment('. $f['id'].',this); return false;" type_item = "'. $type_item. '"><i class="fa fa-times"></i></a>';
        $file_html .= '</div></div>';
      }
      $file_html .= '</div>';
      echo html_entity_decode($file_html);
    }
    ?>
  <?php } ?>
</div>
<div id="ic_file_data"></div>
<div class="col-md-12">
  <div class="attachments">
    <div class="attachment">
      <div class="mbot15">
        <label for="attachment" class="control-label"><?php echo _l('fe_upload_image'); ?></label>
        <input type="file" extension="<?php echo str_replace('.','',get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments" accept="image/*">
      </div>
    </div>
  </div>
</div>

</div>