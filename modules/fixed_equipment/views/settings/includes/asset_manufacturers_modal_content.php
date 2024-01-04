 <?php 
 $id = '';
 $name = '';
 $url = '';
 $support_url = '';
 $support_phone = '';
 $support_email = '';
 
 if(isset($asset_manufacturer)){
  $id = $asset_manufacturer->id;
  $name = $asset_manufacturer->name;
  $url = $asset_manufacturer->url;
  $support_url = $asset_manufacturer->support_url;
  $support_phone = $asset_manufacturer->support_phone;
  $support_email = $asset_manufacturer->support_email;
}
?>
<div class="row">
 <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">  
 <div class="col-md-12">
  <?php echo render_input('name', 'fe_name', $name); ?>
</div>
<div class="col-md-12">
  <?php echo render_input('url', 'fe_url', $url); ?>
</div>
<div class="col-md-12">
  <?php echo render_input('support_url', 'fe_support_url', $support_url); ?>
</div>
<div class="col-md-12">
  <?php echo render_input('support_phone', 'fe_support_phone', $support_phone); ?>
</div>
<div class="col-md-12">
  <?php echo render_input('support_email', 'fe_support_email', $support_email); ?>
</div>

<div class="col-md-12" id="ic_pv_file">
  <?php
  if(isset($asset_manufacturer)){
    $attachments = fe_get_item_file_attachment($asset_manufacturer->id, 'asset_manufacturers');
    $file_html = '';
    $type_item = 'asset_manufacturers';
    if(count($attachments) > 0){
      $file_html .= '<div class="list-file">';
      foreach ($attachments as $f) {
        $href_url = site_url(FIXED_EQUIPMENT_PATH.'asset_manufacturers/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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