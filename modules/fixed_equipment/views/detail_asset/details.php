<?php
$manu_name = '';
$manu_url = '';
$manu_support_url = '';
$manu_support_phone = '';
$manu_support_email = '';

$depreciation_name = '';
$depreciation_term = '';

if(isset($model)){
  $data_manu = $this->fixed_equipment_model->get_asset_manufacturers($model->manufacturer);
  if($data_manu){
    $manu_name = $data_manu->name;
    $manu_url = $data_manu->url;
    $manu_support_url = $data_manu->support_url;
    $manu_support_phone = $data_manu->support_phone;
    $manu_support_email = $data_manu->support_email;
  }

  $data_depreciation = $this->fixed_equipment_model->get_depreciations($model->depreciation);
  if($data_depreciation){
    $depreciation_name = $data_depreciation->name;
    $depreciation_term = $data_depreciation->term;
  }
}

?>

<div class="row">
  <div class="col-md-8">
    <table class="table table-striped">
      <tr>
        <td>
          <table>
            <tr>
              <td>
                <?php echo '<strong>'._l('fe_manufacturer').':&nbsp;</strong>'; ?> 
              </td>
              <td>
                <?php echo html_entity_decode($manu_name); ?> 
              </td>
            </tr>
            <tr>
              <td></td>
              <td class="ptop10"><strong><i class="fa fa-globe"></i></strong> <a href="<?php echo html_entity_decode($manu_url); ?>"><?php echo html_entity_decode($manu_url); ?></a></td>
            </tr>
            <tr>
              <td></td>
              <td class="ptop10"><strong><i class="fa fa-life-ring"></i></strong> <a href="<?php echo html_entity_decode($manu_support_url); ?>"><?php echo html_entity_decode($manu_support_url); ?></a></td>
            </tr>
            <tr>
              <td></td>
              <td class="ptop10"><strong><i class="fa fa-phone"></i></strong> <a href="tel:<?php echo html_entity_decode($manu_support_phone); ?>"><?php echo html_entity_decode($manu_support_phone); ?></a></td>
            </tr>
            <tr>
              <td></td>
              <td class="ptop10"><strong><i class="fa fa-envelope"></i></strong> <a href="mailto:<?php echo html_entity_decode($manu_support_email); ?>"><?php echo html_entity_decode($manu_support_email); ?></a></td>
            </tr>
          </table>
          <br>
        </td>
      </tr>
      <tr>
        <td><?php
        $category_name = '';
        if(is_numeric($model->category)){
          $data_category = $this->fixed_equipment_model->get_categories($model->category);
          if($data_category){
            $category_name = $data_category->category_name;
          }
        }
        echo '<strong>'._l('fe_category').': </strong>'.$category_name; ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_purchase_date').': </strong>'.$asset->date_buy; ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_purchase_cost').': </strong>'.app_format_money($asset->unit_price,''); ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_order_number').': </strong>'.$asset->order_number; ?></td>
      </tr>
      <tr>
        <td><?php
        $supplier_name = '';
        if(is_numeric($asset->supplier_id)){
          $data_supplier = $this->fixed_equipment_model->get_suppliers($asset->supplier_id);
          if($data_supplier){
            $supplier_name = $data_supplier->supplier_name;
          }
        }
        echo '<strong>'._l('fe_supplier').': </strong>'.$supplier_name; ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_depreciation').': </strong>'.$depreciation_name.' ('.$depreciation_term.' '._l('months').')'; ?></td>
      </tr>   
      <tr>
        <td><?php echo '<strong>'._l('fe_fully_depreciated').': </strong>'.$manu_name; ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_eol_rate').': </strong>'.(is_numeric($model->eol) ? $model->eol.' '._l('months') : ''); ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_eol_date').': </strong>'._d($model->date_creator); ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_notes').': </strong>'.$asset->description; ?></td>
      </tr>
      <tr>
        <td><?php
        $location_name = '';
        if(is_numeric($asset->asset_location)){
          $data_location = $this->fixed_equipment_model->get_locations($asset->asset_location);
          if($data_location){
            $location_name = $data_location->location_name;
          }
        }

        echo '<strong>'._l('fe_default_location').': </strong>'.$location_name; ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_created_at').': </strong>'.($asset->date_creator != '' ? _dt($asset->date_creator) : ''); ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_updated_at').': </strong>'.($asset->updated_at != '' ? _dt($asset->updated_at) : ''); ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_checkouts').': </strong>'.$this->fixed_equipment_model->count_log_detail($asset->id, 'checkout', 0); ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_checkins').': </strong>'.$this->fixed_equipment_model->count_log_detail($asset->id, 'checkin'); ?></td>
      </tr>
      <tr>
        <td><?php echo '<strong>'._l('fe_requests').': </strong>'.$this->fixed_equipment_model->count_log_detail($asset->id, 'checkout', 1, 1);; ?></td>
      </tr>
      <?php 
      $data_list_custom_field = $this->fixed_equipment_model->get_custom_field_value_assets($asset->id);
      if($data_list_custom_field){
        foreach ($data_list_custom_field as $key => $customfield) {
          switch ($customfield['type']) {
            case 'select':
            ?>

            <tr>
              <td><?php echo '<strong>'.$customfield['title'].': </strong>'.$customfield['value']; ?></td>
            </tr>
            <?php
            break;
            case 'multi_select':
            $array_value = (($customfield['value'] != '') ? json_decode($customfield['value']) : []);
            $value = '';
            foreach ($array_value as $key => $val) {
              $value .= $val.', ';
            }
            if($value != ''){
              $value = rtrim($value,', ');
            }
            ?>

            <tr>
              <td><?php echo '<strong>'.$customfield['title'].': </strong>'.$value; ?></td>
            </tr>
            <?php
            break;
            case 'checkbox':
            $array_value = (($customfield['value'] != '') ? json_decode($customfield['value']) : []);
            $value = '';
            foreach ($array_value as $key => $val) {
              $value .= $val.', ';
            }
            if($value != ''){
              $value = rtrim($value,', ');
            }
            ?>
            <tr>
              <td><?php echo '<strong>'.$customfield['title'].': </strong>'.$value; ?></td>
            </tr>
            <?php
            break;
            case 'radio_button':
            ?>

            <tr>
              <td><?php echo '<strong>'.$customfield['title'].': </strong>'.$customfield['value']; ?></td>
            </tr>
            <?php
            break;
            case 'textarea':
            ?>

            <tr>
              <td><?php echo '<strong>'.$customfield['title'].': </strong>'.$customfield['value']; ?></td>
            </tr>
            <?php
            break;
            case 'numberfield':
            ?>

            <tr>
              <td><?php echo '<strong>'.$customfield['title'].': </strong>'.$customfield['value']; ?></td>
            </tr>
            <?php
            break;
            case 'textfield':
            ?>

            <tr>
              <td><?php echo '<strong>'.$customfield['title'].': </strong>'.$customfield['value']; ?></td>
            </tr>
            <?php
            break;
          }
        }
      }
      ?>
    </table>
  </div>
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-12 text-center">
        <img class="img img-rounded mtop10 img-thumbnail" src="<?php echo html_entity_decode($this->fixed_equipment_model->get_image_items($model->id, 'models'));  ?>">          
      </div>
    </div>
    <br>
    <table class="table table-striped">
      <tbody>
        <tr>
          <td><?php 
          $status_name = '';
          if(is_numeric($asset->status)){
            $status_data = $this->fixed_equipment_model->get_status_labels($asset->status); 
            if($status_data){
              $status_name = $status_data->name;
            }
          }
          echo '<strong>'._l('fe_status').': </strong>'. $status_name; ?></td>
        </tr>
        <tr>
          <td><?php echo '<strong>'._l('fe_asset_tag').': </strong> '.$asset->series; ?></td>
        </tr>
        <tr>
          <td><?php echo '<strong>'._l('fe_model_no').': </strong>'.$model->model_no; ?></td>
        </tr>
        <tr>
          <td>
            <br>
            <img class="img img-thumbnail" width="250px" src="<?php echo fe_get_image_qrcode($asset->id);  ?>">         
            <br>
            <small><?php echo html_entity_decode($asset->qr_code);  ?></small> 
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>


