<?php
$manu_name = '';
$manu_url = '';
$manu_support_url = '';
$manu_support_phone = '';
$manu_support_email = '';

$depreciation_name = '';
$depreciation_term = '';

if(isset($asset)){
  $data_manu = $this->fixed_equipment_model->get_asset_manufacturers($asset->manufacturer_id);
  if($data_manu){
    $manu_name = $data_manu->name;
    $manu_url = $data_manu->url;
    $manu_support_url = $data_manu->support_url;
    $manu_support_phone = $data_manu->support_phone;
    $manu_support_email = $data_manu->support_email;
  }
}

?>


<div class="col-md-12">
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
      <td><?php echo '<strong>'._l('fe_product_key').': </strong>'.$asset->product_key; ?></td>
    </tr>
    <tr>
      <td><?php
      $category_name = '';
      if(is_numeric($asset->category_id)){
        $data_category = $this->fixed_equipment_model->get_categories($asset->category_id);
        if($data_category){
          $category_name = $data_category->category_name;
        }
      }
      echo '<strong>'._l('fe_category').': </strong>'.$category_name; ?></td>
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
      <td><?php echo '<strong>'._l('fe_licensed_to_name').': </strong>'.$asset->licensed_to_name; ?></td>
    </tr>
    <tr>
      <td><?php echo '<strong>'._l('fe_licensed_to_email').': </strong>'.$asset->licensed_to_email; ?></td>
    </tr>
    <tr>
      <td><?php echo '<strong>'._l('fe_expiration_date').': </strong>'._d($asset->expiration_date); ?></td>
    </tr>
    <tr>
      <td><?php echo '<strong>'._l('fe_termination_date').': </strong>'._d($asset->termination_date); ?></td>
    </tr>
    <tr>
      <td><?php echo '<strong>'._l('fe_purchase_order_number').': </strong>'.$asset->purchase_order_number; ?></td>
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
      <td><?php echo '<strong>'._l('fe_seats').': </strong>'.$asset->seats; ?></td>
    </tr>
    <tr>
      <td><?php echo '<strong>'._l('fe_reassignable').': </strong>'.($asset->reassignable == 1 ? 'Yes' : 'No'); ?></td>
    </tr>
    <tr>
      <td><?php echo '<strong>'._l('fe_notes').': </strong>'.$asset->description; ?></td>
    </tr>
  </table>
</div>


