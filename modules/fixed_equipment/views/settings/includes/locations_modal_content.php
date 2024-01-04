 <?php 
 $id = '';
 $location_name = '';
 $parent = '';
 $manager = '';
 $address = '';
 $city = '';
 $state = '';
 $zip = '';
 $country =  get_option('customer_default_country');

 $location_currency = '';
 $currency_attr = array('data-show-subtext'=>true);
 $currency_attr = apply_filters_deprecated('invoice_currency_disabled', [$currency_attr], '2.3.0', 'invoice_currency_attributes');

 foreach($currencies as $currency){
   if($currency['isdefault'] == 1){
     $currency_attr['data-base'] = $currency['id'];
   }
   if(isset($invoice)){
    if($currency['id'] == $invoice->currency){
     $location_currency = $currency['id'];
   }
 } else {
   if($currency['isdefault'] == 1){
     $location_currency = $currency['id'];
   }
 }
}
if(isset($location)){
  $id = $location->id;
  $location_name = $location->location_name;
  $parent = $location->parent;
  $manager = $location->manager;
  $address = $location->address;
  $city = $location->city;
  $state = $location->state;
  $country =  $location->country;
  $location_currency = $location->location_currency;
  $zip = $location->zip;
}
?>
<div class="row">
 <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">  
 <div class="col-md-12">
  <?php echo render_input('location_name', 'fe_location_name', $location_name); ?>
</div>
<div class="col-md-12">
  <?php echo render_select('parent', $locations, array('id', 'location_name'), 'fe_parent', $parent); ?>
</div>
<div class="col-md-12">
  <?php echo render_select('manager', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_manager', $manager); ?>
</div>
<div class="col-md-12">
  <?php echo render_input('address', 'fe_address', $address); ?>
</div>
<div class="col-md-12">
  <?php echo render_input('city', 'fe_city', $city); ?>
</div>
<div class="col-md-12">
  <?php echo render_input('state', 'fe_state', $state); ?>
</div>
<div class="col-md-12">
 <?php $countries= get_all_countries();
 echo render_select('country',$countries,array( 'country_id',array( 'short_name')), 'fe_country',$country,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
 ?>
</div>
<div class="col-md-6">
  <?php echo render_input('zip', 'fe_zip', $zip); ?>
</div>
<div class="col-md-6">
 <?php
 echo render_select('location_currency', $currencies, array('id','name','symbol'), 'fe_location_currency', $location_currency, $currency_attr); ?>
</div>
<div class="col-md-12" id="ic_pv_file">
  <?php
  if(isset($location)){
    $attachments = fe_get_item_file_attachment($location->id, 'locations');
    $file_html = '';
    $type_item = 'locations';
    if(count($attachments) > 0){
      $file_html .= '<div class="list-file">';
      foreach ($attachments as $f) {
        $href_url = site_url(FIXED_EQUIPMENT_PATH.'locations/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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