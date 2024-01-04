<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                <?php if($pur_request->status == 1){ ?>
                    <div class="ribbon info"><span class="fontz9" ><?php echo _l('purchase_not_yet_approve'); ?></span></div>
                <?php }elseif($pur_request->status == 2){ ?>
                  <div class="ribbon success"><span><?php echo _l('purchase_approved'); ?></span></div>
                <?php }elseif($pur_request->status == 3){ ?>  
                  <div class="ribbon danger"><span><?php echo _l('purchase_reject'); ?></span></div>
                <?php } ?>
                  <h4 class="customer-profile-group-heading"><?php echo _l($title); ?>
                  </h4>
                  <div class="row">
                    <div class="horizontal-scrollable-tabs preview-tabs-top">
                      <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                      <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                      <div class="horizontal-tabs">
                         <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                            <li role="presentation" class="active">
                               <a href="#information" aria-controls="information" role="tab" data-toggle="tab">
                               <?php echo _l('pur_information'); ?>
                               </a>
                            </li>
                            

                            <li role="presentation" class="">
                               <a href="#compare_quotes" aria-controls="compare_quotes" role="tab" data-toggle="tab">
                               <?php echo _l('compare_quotes'); ?>
                               </a>
                            </li> 
                            
                         </ul>
                      </div>
                   </div>
                   <div class="tab-content">
                    <div role="tabpanel" class="tab-pane ptop10 active" id="information">

                    <div class="row">
                  <div class="col-md-12">
                    <p class="bold col-md-9 p_style"><?php echo _l('information'); ?></p>
                    <div class="col-md-3 pull-right">
                        <select name="status" id="status" class="selectpicker" onchange="change_status_pur_request(this,<?php echo html_entity_decode($pur_request->id); ?>); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('pur_change_status_to'); ?>">
                          <option value=""></option>
                          <option value="1" class="<?php if($pur_request->status == 1) { echo 'hide';}?>"><?php echo _l('purchase_not_yet_approve'); ?></option>
                          <option value="2" class="<?php if($pur_request->status == 2) { echo 'hide';}?>"><?php echo _l('purchase_approved'); ?></option>
                          <option value="3" class="<?php if($pur_request->status == 3) { echo 'hide';}?>"><?php echo _l('purchase_reject'); ?></option>
                        </select>
                    </div>
                  <div class=" col-md-12">
                    <hr class="hr_style" />
                  </div>
                  </div></div>

                  <div class=" col-md-6">
                  <div class="panel panel-warning">
                    <div class="panel-heading"><?php echo _l('general_infor'); ?></div>
                    <div class="panel-body">
                      <table class="table border table-striped martop0">
                    <tbody>
                       <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('pur_rq_code'); ?></td>
                          <td><?php echo html_entity_decode($pur_request->pur_rq_code); ?></td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold"><?php echo _l('pur_rq_name'); ?></td>
                          <td><?php echo _l($pur_request->pur_rq_name); ?></td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold"><?php echo _l('purchase_requestor'); ?></td>
                          <td><?php $_data = '<a href="' . admin_url('staff/profile/' . $pur_request->requester) . '">' . staff_profile_image($pur_request->requester, [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/profile/' . $pur_request->requester) . '">' . get_staff_full_name($pur_request->requester) . '</a>'; 
            echo html_entity_decode($_data);
            ?></td>
                       </tr>
                       
                       <tr class="project-overview">
                          <td class="bold"><?php echo _l('request_date'); ?></td>
                          <td><?php echo _dt($pur_request->request_date); ?></td>
                       </tr>
                       <tr>
                        <td class="bold"><?php echo _l('pdf'); ?></td>
                        <td><div class="btn-group">
                           <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                           <ul class="dropdown-menu dropdown-menu-right">
                              <li class="hidden-xs"><a href="<?php echo admin_url('purchase/pur_request_pdf/'.$pur_request->id.'?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a></li>
                              <li class="hidden-xs"><a href="<?php echo admin_url('purchase/pur_request_pdf/'.$pur_request->id.'?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                              <li><a href="<?php echo admin_url('purchase/pur_request_pdf/'.$pur_request->id); ?>"><?php echo _l('download'); ?></a></li>
                           </ul>
                           </div>
                               
                              
                        </td>
                      </tr>
                      <tr class="project-overview">
                          <td class="bold"><?php echo _l('public_link'); ?></td>
                          <td>
                            <div class="pull-right _buttons mright5">
                              <a href="javascript:void(0)" onclick="copy_public_link(<?php echo html_entity_decode($pur_request->id); ?>); return false;" class="btn btn-warning btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('copy_public_link'); ?>" data-placement="bottom"><i class="fa fa-clone "></i></a>
                           </div>
                            <div class="col-md-9">
                              <?php if($pur_request->hash != '' && $pur_request->hash != null){
                               echo render_input('link_public','', site_url('purchase/vendors_portal/pur_request/'.$pur_request->id.'/'.$pur_request->hash)); 
                              }else{
                                  echo render_input('link_public','', ''); 
                              } ?>
                           </div>
                           </td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold"><?php echo _l('rq_description'); ?></td>
                          <td><?php echo html_entity_decode($pur_request->rq_description); ?></td>
                       </tr>

                    </tbody>
                </table>
                    </div>
                  </div>
                  </div>

                  <div class=" col-md-6">
                  <div class="panel panel-warning">
                    <div class="panel-heading"><?php echo _l('pur_approval_infor'); ?></div>
                    <div class="panel-body">
                      <div class="project-overview-right">
                        <?php if(count($list_approve_status) > 0){ ?>
                          
                         <div class="row">
                           <div class="col-md-12 project-overview-expenses-finance">
                            <?php 
                              $this->load->model('staff_model');
                              $enter_charge_code = 0;
                            foreach ($list_approve_status as $value) {
                              $value['staffid'] = explode(', ',$value['staffid'] ?? '');
                              if($value['action'] == 'sign'){
                             ?>
                             <div class="col-md-6 apr_div">
                                 <p class="text-uppercase text-muted no-mtop bold">
                                  <?php
                                  $staff_name = '';
                                  $st = _l('status_0');
                                  $color = 'warning';
                                  foreach ($value['staffid'] as $key => $val) {
                                    if($staff_name != '')
                                    {
                                      $staff_name .= ' or ';
                                    }
                                    $staff_name .= isset($this->staff_model->get($val)->firstname) ? $this->staff_model->get($val)->firstname : '';
                                  }
                                  echo html_entity_decode($staff_name); 
                                  ?></p>
                                 <?php if($value['approve'] == 2){ 
                                  ?>
                                  <img src="<?php echo site_url(PURCHASE_PATH.'pur_request/signature/'.$pur_request->id.'/signature_'.$value['id'].'.png'); ?>" class="img_style">
                                   <br><br>
                                 <p class="bold text-center text-success"><?php echo _l('signed').' '._dt($value['date']); ?></p>
                                 <?php } ?> 
                                    
                            </div>
                            <?php }else{ ?>
                            <div class="col-md-6 apr_div">
                                 <p class="text-uppercase text-muted no-mtop bold">
                                  <?php
                                  $staff_name = '';
                                  foreach ($value['staffid'] as $key => $val) {
                                    if($staff_name != '')
                                    {
                                      $staff_name .= ' or ';
                                    }
                                    $staff_name .= isset($this->staff_model->get($val)->firstname) ? $this->staff_model->get($val)->firstname : '';
                                  }
                                  echo html_entity_decode($staff_name); 
                                  ?></p>
                                 <?php if($value['approve'] == 2){ 
                                  ?>
                                  <img src="<?php echo site_url(PURCHASE_PATH.'approval/approved.png'); ?>" class="img_style">
                                 <?php }elseif($value['approve'] == 3){ ?>
                                    <img src="<?php echo site_url(PURCHASE_PATH.'approval/rejected.png'); ?>" class="img_style">
                                <?php } ?> 
                                <br><br>
                                <p><?php echo html_entity_decode($value['note']) ?></p>  
                                <p class="bold text-center text-<?php if($value['approve'] == 2){ echo 'success'; }elseif($value['approve'] == 3){ echo 'danger'; } ?>"><?php echo _dt($value['date']); ?></p> 
                            </div>
                            <?php }
                            } ?>
                           </div>
                        </div>
                        
                        <?php } ?>
                        </div>
                        <div class="pull-right">
                            <?php 
                            if($check_appr && $check_appr != false){
                            if($pur_request->status != 2 && ($check_approve_status == false || $check_approve_status == 'reject')){ ?>
                        <a data-toggle="tooltip" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-success lead-top-btn lead-view" data-placement="top" href="#" onclick="send_request_approve(<?php echo html_entity_decode($pur_request->id); ?>); return false;"><?php echo _l('send_request_approve_pur'); ?></a>
                      <?php } }
                        if(isset($check_approve_status['staffid'])){
                            ?>
                            <?php 
                        if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && !in_array(get_staff_user_id(), $get_staff_sign)){ ?>
                            <div class="btn-group" >
                                   <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?><span class="caret"></span></a>
                                   <ul class="dropdown-menu dropdown-menu-right ul_style">
                                    <li>
                                      <div class="col-md-12">
                                        <?php echo render_textarea('reason', 'reason'); ?>
                                      </div>
                                    </li>
                                      <li>
                                        <div class="row text-right col-md-12">
                                          <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="approve_request(<?php echo html_entity_decode($pur_request->id); ?>); return false;" class="btn btn-success mright15"><?php echo _l('approve'); ?></a>
                                         <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="deny_request(<?php echo html_entity_decode($pur_request->id); ?>); return false;" class="btn btn-warning"><?php echo _l('deny'); ?></a></div>
                                      </li>
                                   </ul>
                                </div>
                          <?php }
                            ?>
                            
                          <?php
                           if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && in_array(get_staff_user_id(), $get_staff_sign)){ ?>
                            <button onclick="accept_action();" class="btn btn-success pull-right action-button"><?php echo _l('e_signature_sign'); ?></button>
                          <?php }
                            ?>
                            <?php 
                             }
                            ?>
                          </div>
                    </div>
                  </div>
                  </div>

                  
                  
                  <div class="col-md-12">
                    <p class="bold p_style"><?php echo _l('pur_detail'); ?></p>
                    <hr class="hr_style" />
                    <div class="col-md-12" id="example">
                      <hr>
                    </div>

                  </div>
                   <div class="col-md-6 col-md-offset-6">
                     <table class="table text-right mbot0">
                       <tbody>
                          <tr id="subtotal">
                             <td class="td_style"><span class="bold"><?php echo _l('subtotal'); ?></span>
                             </td>
                             <td width="65%" id="total_td">
                              
                               <div class="input-group" id="discount-total">

                                      <input type="text" readonly="true"  class="form-control text-right" name="subtotal" value="<?php if(isset($pur_request)){ echo app_format_money($pur_request->subtotal,''); } ?>">

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
                          <?php if(isset($pur_request)){ 
                            echo $taxes_data['html'];
                            ?>
                          <?php } ?>
                       </tbody>
                      </table>

                      <table class="table text-right">
                       <tbody id="tax_area_body">
                          <tr id="total">
                             <td class="td_style"><span class="bold"><?php echo _l('total'); ?></span>
                             </td>
                             <td width="65%" id="total_td">
                               <div class="input-group" id="total">
                                     <input type="text" readonly="true" class="form-control text-right" name="total_mn" value="<?php if(isset($pur_request)){ echo app_format_money($pur_request->total,''); } ?>">
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
                  <?php echo form_hidden('request_detail'); ?>

                </div>

                <div role="tabpanel" class="tab-pane ptop10 " id="compare_quotes">
                  <?php if(total_rows(db_prefix().'pur_estimates', ['pur_request' => $pur_request->id]) > 0){ ?>
                    <div class="col-md-6">
                        <table class="table border table-striped martop0">
                          <tbody>
                             <tr class="project-overview">
                                <td class="bold" width="30%"><?php echo _l('pur_rq_code'); ?></td>
                                <td><?php echo html_entity_decode($pur_request->pur_rq_code); ?></td>
                             </tr>
                             <tr class="project-overview">
                                <td class="bold"><?php echo _l('pur_rq_name'); ?></td>
                                <td><?php echo _l($pur_request->pur_rq_name); ?></td>
                             </tr>
                             <tr class="project-overview">
                                <td class="bold"><?php echo _l('purchase_requestor'); ?></td>
                                <td><?php $_data = '<a href="' . admin_url('staff/profile/' . $pur_request->requester) . '">' . staff_profile_image($pur_request->requester, [
                      'staff-profile-image-small',
                      ]) . '</a>';
                  $_data .= ' <a href="' . admin_url('staff/profile/' . $pur_request->requester) . '">' . get_staff_full_name($pur_request->requester) . '</a>'; 
                  echo html_entity_decode($_data);
                  ?></td>
                             </tr>
                             
                             <tr class="project-overview">
                                <td class="bold"><?php echo _l('request_date'); ?></td>
                                <td><?php echo _dt($pur_request->request_date); ?></td>
                             </tr>
                          </tbody>
                      </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table border table-striped martop0">
                          <tbody>
                             <tr class="project-overview">
                                <td colspan="3" class="bold text-center" width="30%"><?php echo _l('vendors'); ?></td>
                             </tr>
                             <?php 
                             $quotations = get_quotations_by_pur_request($pur_request->id);
                             $arr_vendors = get_arr_vendors_by_pr($pur_request->id); ?>
                             <?php foreach($arr_vendors as $vendor){ ?>
                              <tr class="project-overview">
                                <td class="" ><span class="bold"><?php echo html_entity_decode($vendor->company); ?></span></td>
                                <td class="" ><span class="bold"><?php echo _l('vendor_code').': ' ?></span><span class=""><?php echo html_entity_decode($vendor->vendor_code); ?></span></td>
                                <td class="" ><span class="bold"><?php echo _l('phonenumber').': '; ?></span><span class=""><?php echo html_entity_decode($vendor->phonenumber); ?></span></td>
                             </tr>
                             <?php } ?>
                          </tbody>
                      </table>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <?php echo form_open(admin_url('purchase/compare_quote_pur_request/'.$pur_request->id),array('id'=>'compare_quote_pur_request-form'));  ?>
                          <table class="table table-bordered compare_quotes_table">
                              <thead class="bold">
                               <tr>
                                <th rowspan="2" scope="col"><span class="bold"><?php echo _l('items'); ?></span></th>
                                <th rowspan="2" scope="col"><span class="bold"><?php echo _l('pur_qty'); ?></span></th>
                                <th rowspan="2" scope="col"><span class="bold"><?php echo _l('unit'); ?></span></th>
                                <th rowspan="2" scope="col"><span class="bold"><?php echo _l('description'); ?></span></th>

                                <?php foreach($quotations as $quote){ ?>
                                <th colspan="2" class="text-center"><span class="bold text-danger"><?php echo format_pur_estimate_number($quote['id']). ' - '.get_vendor_company_name($quote['vendor']); ?></span></th>
                                 <?php } ?>
                              </tr>

                              <tr>
                                <?php foreach($quotations as $quote){ ?>
                                <th class="text-right"><span class="bold"><?php echo _l('purchase_unit_price'); ?></span></th>
                                <th class="text-right"><span class="bold"><?php echo _l('total') ?></span></th>
                                 <?php } ?>
                                
                              </tr>
                              </thead>
                            <tbody>
                              <?php 
                                $this->load->model('purchase/purchase_model'); 
                                $list_items = $this->purchase_model->get_pur_request_detail($pur_request->id);
                              ?>
                              <?php foreach($list_items as $key => $item){ ?>
                                <tr>
                                  <td><?php echo html_entity_decode($key + 1); ?></td>
                                  <td><?php echo html_entity_decode($item['quantity']); ?></td>
                                  <td><?php $unit_name = isset(get_unit_type_item($item['unit_id'])->unit_name) ? get_unit_type_item($item['unit_id'])->unit_name : '';
                                  echo html_entity_decode($unit_name); ?></td>
                                  <td><?php $item_name = isset(get_item_hp($item['item_code'])->description) ? get_item_hp($item['item_code'])->description : '';
                                  echo html_entity_decode($item_name); ?></td>

                                  <?php foreach($quotations as $quote){ ?>
                                    <?php  $item_quote = get_item_detail_in_quote($item['item_code'], $quote['id']); ?>
                                    <?php if(isset($item_quote)){ ?>
                                      <td class="text-right"><?php echo app_format_money($item_quote->unit_price, $base_currency->name); ?></td>
                                      <td class="text-right"><?php echo app_format_money($item_quote->total_money, $base_currency->name); ?></td>
                                    <?php }else{ ?>
                                      <td>-</td>
                                      <td>-</td>
                                    <?php } ?>
                                  <?php } ?>

                                </tr>
                              <?php } ?>
                              <tr>
                                <td colspan="4" class="text-center"><span class="bold"><?php echo _l('mark_a_contract'); ?></span></td>
                                <?php foreach($quotations as $quote){ ?>
                                  <td colspan="2"><input name="mark_a_contract[<?php echo html_entity_decode($quote['id']); ?>]" type="text" value="<?php echo html_entity_decode($quote['make_a_contract']); ?>" /></td>
                                <?php } ?>
                              </tr>
                              <tr>
                                <td colspan="4" class="text-center"><span class="bold"><?php echo _l('total_purchase_amount'); ?></span></td>
                                <?php foreach($quotations as $quote){ ?>
                                  <td colspan="2" class="text-right"><span class="bold text-info"><?php echo app_format_money($quote['total'], $base_currency->name); ?></span></td>
                                <?php } ?>
                              </tr>
                            </tbody>  
                   
                          </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                      <br>
                      <p><span class="bold"><?php echo _l('purchase_request_description').': '; ?></span><span><?php echo html_entity_decode($pur_request->rq_description); ?></span></p>
                      <?php echo render_textarea('compare_note', 'comparison_notes', clear_textarea_breaks($pur_request->compare_note)) ?>
                    </div>
                    <div class="col-md-12">
                    <button id="sm_btn" class="btn btn-info save_detail pull-right"><?php echo _l('pur_confirm'); ?></button>
                    </div>
                  <?php echo form_close(); ?>

                  <?php }else{ ?>
                   
                      <div class="col-md-12">
                        <span class="text-bold"><?php echo _l('this_purchase_request_does_not_have_a_quote_yet'); ?></span>
                      </div>
                      
                    
                  <?php } ?>
                </div>

              </div>

                    
                </div>
               </div>
            </div>
            
         </div>
        
      </div>
   </div>
</div>
<div class="modal fade" id="add_action" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
  
        <div class="modal-body">
         <p class="bold" id="signatureLabel"><?php echo _l('signature'); ?></p>
            <div class="signature-pad--body">
              <canvas id="signature" height="130" width="550"></canvas>
            </div>
            <input type="text" class="ip_style" tabindex="-1" name="signature" id="signatureInput">
            <div class="dispay-block">
              <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" onclick="signature_clear();"><?php echo _l('clear'); ?></button>
              
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('cancel'); ?></button>
           <button onclick="sign_request(<?php echo html_entity_decode($pur_request->id); ?>);" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
          </div>


      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?> 
</body>
</html>
<?php require 'modules/purchase/assets/js/view_pur_request_js.php';?>
