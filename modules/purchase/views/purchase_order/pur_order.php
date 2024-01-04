<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <?php
      echo form_open($this->uri->uri_string(),array('id'=>'pur_order-form','class'=>'_transaction_form'));
      
      ?>
      <div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
          <div class="horizontal-scrollable-tabs preview-tabs-top">
            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="active">
                     <a href="#general_infor" aria-controls="general_infor" role="tab" data-toggle="tab">
                     <?php echo _l('pur_general_infor'); ?>
                     </a>
                  </li>
                  <?php
                  $customer_custom_fields = false;
                  if(total_rows(db_prefix().'customfields',array('fieldto'=>'pur_order','active'=>1)) > 0 ){
                       $customer_custom_fields = true;
                   ?>
               <li role="presentation" >
                  <a href="#custom_fields" aria-controls="custom_fields" role="tab" data-toggle="tab">
                  <?php echo _l( 'custom_fields'); ?>
                  </a>
               </li>
               <?php } ?>
                </ul>
            </div>
          </div>
            <div class="tab-content">
                <?php if($customer_custom_fields) { ?>
                 <div role="tabpanel" class="tab-pane" id="custom_fields">
                    <?php $rel_id=( isset($pur_order) ? $pur_order->id : false); ?>
                    <?php echo render_custom_fields( 'pur_order',$rel_id); ?>
                 </div>
                <?php } ?>
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                   <div class="col-md-6">
                      <div class="row">
                        <div class="col-md-6">
                          <?php $pur_order_name = (isset($pur_order) ? $pur_order->pur_order_name : '');
                          echo render_input('pur_order_name','pur_order_description',$pur_order_name); ?>
                
                        </div>
                        <div class="col-md-6 form-group">
                          <label for="vendor"><?php echo _l('vendor'); ?></label>
                          <select name="vendor" id="vendor" class="selectpicker" onchange="estimate_by_vendor(this); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                              <option value=""></option>
                              <?php foreach($vendors as $s) { ?>
                              <option value="<?php echo html_entity_decode($s['userid']); ?>" <?php if(isset($pur_order) && $pur_order->vendor == $s['userid']){ echo 'selected'; }else{ if(isset($ven) && $ven == $s['userid']){ echo 'selected';} } ?>><?php echo html_entity_decode($s['company']); ?></option>
                                <?php } ?>
                          </select>              
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="form-group col-md-<?php if(get_purchase_option('purchase_order_setting') == 1 ){ echo '12' ;}else{ echo '6' ;} ;?>">
                          <?php $prefix = get_purchase_option('pur_order_prefix');
                                $next_number = get_purchase_option('next_po_number');

                          $pur_order_number = (isset($pur_order) ? $pur_order->pur_order_number : $prefix.'-'.str_pad($next_number,5,'0',STR_PAD_LEFT).'-'.date('M-Y'));      
                          if(get_option('po_only_prefix_and_number') == 1){
                             $pur_order_number = (isset($pur_order) ? $pur_order->pur_order_number : $prefix.'-'.str_pad($next_number,5,'0',STR_PAD_LEFT));
                          }      
                            
                          
                          $number = (isset($pur_order) ? $pur_order->number : $next_number);
                          echo form_hidden('number',$number); ?> 
                          
                          <label for="pur_order_number"><?php echo _l('pur_order_number'); ?></label>
                          
                              <input type="text" readonly class="form-control" name="pur_order_number" value="<?php echo html_entity_decode($pur_order_number); ?>">
                          
                        </div>
                        <?php if(get_purchase_option('purchase_order_setting') == 0 ){ ?>
                          <div class="col-md-5 form-group">
                            <label for="estimate"><?php echo _l('estimates'); ?></label>
                            <select name="estimate" id="estimate" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                              
                            </select>
                            
                          </div>
                          <div class="col-md-1 pad_div_0">
                            <a href="#" onclick="coppy_pur_estimate(); return false;" class="btn btn-success mtop25" data-toggle="tooltip" title="<?php echo _l('coppy_pur_estimate'); ?>">
                            <i class="fa fa-clone"></i>
                            </a>
                          </div>
                      <?php } ?>

                      </div>

                      <div class="row">
                        <div class="col-md-6 form-group">
                        <label for="project"><?php echo _l('project'); ?></label>
                          <select name="project" id="project" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                            <option value=""></option>
                            <?php foreach($projects as $s) { ?>
                              <option value="<?php echo html_entity_decode($s['id']); ?>" <?php if(isset($pur_order) && $s['id'] == $pur_order->project){ echo 'selected'; } ?>><?php echo html_entity_decode($s['name']); ?></option>
                              <?php } ?>
                          </select>
                       
                      </div>

                      <div class="col-md-5 form-group">
                        <label for="pur_request"><?php echo _l('pur_request'); ?></label>
                        <select name="pur_request" id="pur_request" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                          <option value=""></option>
                            <?php foreach($pur_request as $s) { ?>
                            <option value="<?php echo html_entity_decode($s['id']); ?>" <?php if(isset($pur_order) && $pur_order->pur_request != '' && $pur_order->pur_request == $s['id']){ echo 'selected'; } ?> ><?php echo html_entity_decode($s['pur_rq_code'].' - '.$s['pur_rq_name']); ?></option>
                              <?php } ?>
                        </select>
                       </div>
                      <div class="col-md-1 pad_div_0">
                        <a href="#" onclick="coppy_pur_request(); return false;" class="btn btn-success mtop25" data-toggle="tooltip" title="<?php echo _l('coppy_pur_request_to_purorder'); ?>">
                        <i class="fa fa-clone"></i>
                        </a>
                      </div> 

                      

                      </div>

                      <div class="row">
                        <div class="col-md-6 form-group">
                        <label for="department"><?php echo _l('department'); ?></label>
                          <select name="department" id="department" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                            <option value=""></option>
                            <?php foreach($departments as $s) { ?>
                              <option value="<?php echo html_entity_decode($s['departmentid']); ?>" <?php if(isset($pur_order) && $s['departmentid'] == $pur_order->department){ echo 'selected'; } ?>><?php echo html_entity_decode($s['name']); ?></option>
                              <?php } ?>
                          </select>
                          <br><br>
                        </div>

                        <div class="col-md-6 form-group">
                          <label for="type"><?php echo _l('type'); ?></label>
                            <select name="type" id="type" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                              <option value=""></option>
                              <option value="capex" <?php if(isset($pur_order) && $pur_order->type == 'capex'){ echo 'selected';} ?>><?php echo _l('capex'); ?></option>
                              <option value="opex" <?php if(isset($pur_order) && $pur_order->type == 'opex'){ echo 'selected';} ?>><?php echo _l('opex'); ?></option>
                            </select>
                        </div>
                      </div>
                     
                   </div>
                   <div class="col-md-6">
                      <div class="col-md-12 mbot10 form-group">
                       
                        <div id="inputTagsWrapper">
                           <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
                           <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($pur_order) ? prep_tags_input(get_tags_in($pur_order->id,'pur_order')) : ''); ?>" data-role="tagsinput">
                        </div>
                     </div>

                      <div class="col-md-6">
                        <?php $order_date = (isset($pur_order) ? _d($pur_order->order_date) : _d(date('Y-m-d')));
                        echo render_date_input('order_date','order_date',$order_date); ?>
                      </div>

                      <div class="col-md-6">
                                   <?php
                                  $selected = '';
                                  foreach($staff as $member){
                                   if(isset($pur_order)){
                                     if($pur_order->buyer == $member['staffid']) {
                                       $selected = $member['staffid'];
                                     }
                                   }else{
                                    if($member['staffid'] == get_staff_user_id()){
                                      $selected = $member['staffid'];
                                    }
                                   }
                                  }
                                  echo render_select('buyer',$staff,array('staffid',array('firstname','lastname')),'buyer',$selected);
                                  ?>
                      </div>
                      <div class="col-md-6">
                        <?php $days_owed = (isset($pur_order) ? $pur_order->days_owed : '');
                         echo render_input('days_owed','days_owed',$days_owed,'number'); ?>
                      </div>
                      <div class="col-md-6">
                        <?php $delivery_date = (isset($pur_order) ? _d($pur_order->delivery_date) : '');
                         echo render_date_input('delivery_date','delivery_date',$delivery_date); ?>
                      </div>

                      <?php $clients_ed = (isset($pur_order) ? explode(',',$pur_order->clients ?? '') : []); ?>
                      <div class="col-md-6 form-group">
                        <label for="clients"><?php echo _l('clients'); ?></label>
                        <select name="clients" id="clients" class="selectpicker" onchange="client_change(this); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >

                            <?php foreach($clients as $s) { ?>
                            <option value="<?php echo html_entity_decode($s['userid']); ?>" <?php if(isset($pur_order) && in_array($s['userid'], $clients_ed)){ echo 'selected'; } ?>><?php echo html_entity_decode($s['company']); ?></option>
                              <?php } ?>
                        </select>
                      </div>
                      <div class="col-md-5 form-group pright0">
                      <label for="sale_invoice"><?php echo _l('sale_invoice'); ?></label>
                        <select name="sale_invoice" id="sale_invoice" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                          <option value=""></option>
                          <?php foreach($invoices as $inv) { ?>
                            <option value="<?php echo html_entity_decode($inv['id']); ?>" <?php if(isset($pur_order) && $inv['id'] == $pur_order->sale_invoice){ echo 'selected'; } ?>><?php echo format_invoice_number($inv['id']); ?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <div class="col-md-1" >
                         <a href="javascript:void(0)" onclick="coppy_sale_invoice(); return false;" class="btn btn-success mtop25" data-toggle="tooltip" title="<?php echo _l('coppy_sale_invoice'); ?>">
                              <i class="fa fa-clone"></i></a>
                      </div>
                      
                   </div>  
                </div>

              </div>
            </div>
        </div>
        <div class="panel-body mtop10">
        <div class="row col-md-12">
        <p class="bold p_style"><?php echo _l('pur_order_detail'); ?></p>
        <hr class="hr_style"/>
         <div class="" id="example">
         </div>
         <?php echo form_hidden('pur_order_detail'); ?>
         <div class="col-md-6 col-md-offset-6">
            <table class="table text-right">
               <tbody>
                  <tr id="subtotal">
                     <td class="td_style"><span class="bold"><?php echo _l('subtotal'); ?></span>
                     </td>
                     <td width="65%" id="total_td">
                      
                       <div class="input-group" id="discount-total">

                              <input type="text" readonly="true" class="form-control text-right" name="total_mn" value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->subtotal,''); } ?>">

                             <div class="input-group-addon">
                                <div class="dropdown">
                                   
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->symbol) ;?>
                                   </span>
                                   
                                   
                                </div>
                             </div>

                          </div>
                     </td>
                  </tr>
                  </tbody>
                </table>

                <table class="table text-right">
                 <tbody id="tax_area_body">
                    <?php if(isset($pur_order) && $tax_data['html'] != ''){
                      echo html_entity_decode($tax_data['html']);
                    } ?>
                 </tbody>
                </table>

                <table class="table text-right">
                  <tbody>
                    <tr id="discount_percent_area">
                        <td>
                            <span class="bold"><?php echo _l('estimate_discount').'(%)'; ?></span>
                        </td>
                        <td width="65%">  
                            <div class="input-group" id="discount-percent">

                               <input type="number" onchange="dc_percent_change(this); return false;" value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_percent,''); } ?>" class="form-control pull-left text-right" d name="discount_percent">

                               <div class="input-group-addon">
                                  <div class="dropdown">
                                     
                                     <span class="discount-type-selected">
                                      %
                                     </span>
                                     
                                     
                                  </div>
                               </div>

                            </div>
                       </td>
                    </tr>
                    <tr id="discount_area">
                        <td>
                            <span class="bold"><?php echo _l('estimate_discount'); ?></span>
                        </td>
                        <td width="65%">  
                            <div class="input-group" id="discount-total">

                               <input type="text" value="-<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_total,''); } ?>" class="form-control pull-left text-right" data-type="currency" name="dc_total">

                               <div class="input-group-addon">
                                  <div class="dropdown">
                                     
                                     <span class="discount-type-selected">
                                      <?php echo html_entity_decode($base_currency->symbol) ;?>
                                     </span>
                                     
                                     
                                  </div>
                               </div>

                            </div>
                       </td>
                    </tr>
                  </tbody>
                </table>

              <table class="table text-right">
                <tbody>
                  <tr>
                     <td class="td_style"><span class="bold"><?php echo _l('grand_total'); ?></span>
                     </td>
                     <td width="65%" id="total_td">
                       <div class="input-group" id="discount-total">

                             <input type="text" readonly="true" class="form-control text-right" name="grand_total" value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->total,''); } ?>">

                             <div class="input-group-addon">
                                <div class="dropdown">
                                   
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->symbol) ;?>
                                   </span>
                                </div>
                             </div>

                          </div>
                     </td>
                  </tr>
                </tbody>
              </table>
         </div> 
        </div>
        </div>
        <div class="row">
          <div class="col-md-12 mtop15">
             <div class="panel-body bottom-transaction">
                <?php $value = (isset($pur_order) ? $pur_order->vendornote : get_purchase_option('vendor_note')); ?>
                <?php echo render_textarea('vendornote','estimate_add_edit_vendor_note',$value,array(),array(),'mtop15'); ?>
                <?php $value = (isset($pur_order) ? $pur_order->terms :  get_purchase_option('terms_and_conditions')); ?>
                <?php echo render_textarea('terms','terms_and_conditions',$value,array(),array(),'mtop15'); ?>
                <div id="vendor_data">
                  
                </div>

                <div class="btn-bottom-toolbar text-right">
                  
                  <button type="button" class="btn-tr save_detail btn btn-info mleft10 ">
                  <?php echo _l('submit'); ?>
                  </button>
                </div>
             </div>
               <div class="btn-bottom-pusher"></div>
          </div>
        </div>
        </div>

      </div>
      <?php echo form_close(); ?>
      
    </div>
  </div>
</div>
</div>
<?php init_tail(); ?>
</body>
</html>

<?php require 'modules/purchase/assets/js/pur_order_js.php';?>
