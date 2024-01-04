 <?php 
 $id = '';
 $category_name = '';
 $type = '';
 $category_eula = '';
 $primary_default_eula = 0;
 $confirm_acceptance = 0;
 $send_mail_to_user = 0;
 if(isset($category)){
  $id = $category->id;
  $category_name = $category->category_name;
  $type = $category->type;
  $category_eula = $category->category_eula;
  $primary_default_eula = $category->primary_default_eula;
  $confirm_acceptance = $category->confirm_acceptance;
  $send_mail_to_user = $category->send_mail_to_user;
}
?>





<div class="row">
 <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">  
 <div class="col-md-12">
  <?php echo render_input('category_name', 'fe_category_name', $category_name); ?>
</div>
 <div class="col-md-12">
<?php 
$list_type = [
  ['id' => 'accessory', 'label' => _l('fe_accessory')],
  ['id' => 'asset', 'label' => _l('fe_asset')],
  ['id' => 'consumable', 'label' => _l('fe_consumable')],
  ['id' => 'component', 'label' => _l('fe_component')],
  ['id' => 'license', 'label' => _l('fe_license')]
];
?>
<?php echo render_select('type', $list_type, array('id', 'label'), 'fe_category_type', $type); ?>
</div>
<div class="col-md-12">
  <?php echo render_textarea('category_eula', 'fe_category_eula', $category_eula); ?>
</div>
<div class="col-md-12">
  <div class="checkbox">              
    <input type="checkbox" class="capability" name="primary_default_eula" value="1" <?php if($primary_default_eula == 1){ echo "checked"; } ?>>
    <label><?php echo _l('fe_use_the_primary_default_eula_instead'); ?></label>
  </div>
</div>


<div class="col-md-12">
  <div class="checkbox">              
    <input type="checkbox" class="capability" name="confirm_acceptance" value="1" <?php if($confirm_acceptance == 1){ echo "checked"; } ?>>
    <label><?php echo _l('fe_require_users_to_confirm_acceptance_of_assets_in_this_category'); ?></label>
  </div>
</div>

<div class="col-md-12">
  <div class="checkbox">              
    <input type="checkbox" class="capability" name="send_mail_to_user" value="1" <?php if($send_mail_to_user == 1){ echo "checked"; } ?>>
    <label><?php echo _l('fe_send_email_to_user_on_checkin_checkout'); ?></label>
  </div>
</div>

<div class="col-md-12" id="ic_pv_file">
  <?php
  if(isset($category)){
    $attachments = fe_get_item_file_attachment($category->id, 'categories');
    $file_html = '';
    $type_item = 'categories';
    if(count($attachments) > 0){
      $file_html .= '<div class="list-file">';
      foreach ($attachments as $f) {
        $href_url = site_url(FIXED_EQUIPMENT_PATH.'categories/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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