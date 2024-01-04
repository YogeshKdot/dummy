<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      
      <div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
          
       
            <div class="horizontal-scrollable-tabs preview-tabs-top">
            
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="<?php if($this->input->get('tab') != 'discussion'){echo 'active';} ?>">
                     <a href="#general_infor" aria-controls="general_infor" role="tab" data-toggle="tab">
                     <?php echo _l('general_infor'); ?>
                     </a>
                  </li>
                  
                  <li role="presentation" class="tab-separator <?php if($this->input->get('tab') === 'discussion'){echo 'active';} ?>">
                    <?php
                              $totalComments = total_rows(db_prefix().'pur_comments',['rel_id' => $pur_order->id, 'rel_type' => 'pur_order']);
                              ?>
                     <a href="#discuss" aria-controls="discuss" role="tab" data-toggle="tab">
                     <?php echo _l('pur_discuss'); ?>
                      <span class="badge comments-indicator<?php echo $totalComments == 0 ? ' hide' : ''; ?>"><?php echo $totalComments; ?></span>
                     </a>
                  </li> 
                  
               </ul>
            </div>
         </div>
          <div class="tab-content">
             <div role="tabpanel" class="tab-pane ptop10 <?php if($this->input->get('tab') != 'discussion'){echo 'active';} ?>" id="general_infor">
              <div class="row">
               <div class="col-md-6">
                  <table class="table table-striped table-bordered">
                    <tr>
                      <td width="30%"><?php echo _l('pur_order_number'); ?></td>
                      <td><?php echo html_entity_decode($pur_order->pur_order_number) ?></td>
                    </tr>
                    <tr>
                      <td><?php echo _l('pur_order_name'); ?></td>
                      <td><?php echo html_entity_decode($pur_order->pur_order_name) ?></td>
                    </tr>
                    <tr>
                      <td><?php echo _l('status'); ?></td>
                      <td><?php echo get_status_approve($pur_order->approve_status) ?></td>
                    </tr>
                  </table>
               </div>
               <div class="col-md-6">
                  <table class="table table-striped table-bordered">
                    <tr>
                      <td width="30%"><?php echo _l('order_date'); ?></td>
                      <td><?php echo _d($pur_order->order_date) ?></td>
                    </tr>
                    <tr>
                      <td><?php echo _l('delivery_date'); ?></td>
                      <td><?php echo _d($pur_order->delivery_date) ?></td>
                    </tr>
                    <tr>
                      <td><?php echo _l('total'); ?></td>
                      <td><?php echo app_format_money($pur_order->total,'') ?></td>
                    </tr>
                  </table>
               </div>
               </div>
            </div>

            <div role="tabpanel" class="tab-pane <?php if($this->input->get('tab') === 'discussion'){echo ' active';} ?>" id="discuss">
              <?php echo form_open($this->uri->uri_string()) ;?>
               <div class="contract-comment">
                  <textarea name="content" rows="4" class="form-control"></textarea>
                  <button type="submit" class="btn btn-info mtop10 pull-right" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('proposal_add_comment'); ?></button>
                  <?php echo form_hidden('action','po_comment'); ?>
               </div>
               <?php echo form_close(); ?>
               <div class="clearfix"></div>
               <?php
                  $comment_html = '';
                  foreach ($comments as $comment) {
                   $comment_html .= '<div class="contract_comment mtop10 mbot20" data-commentid="' . $comment['id'] . '">';
                   if($comment['staffid'] != 0){
                    $comment_html .= staff_profile_image($comment['staffid'], array(
                     'staff-profile-image-small',
                     'media-object img-circle pull-left mright10'
                  ));
                  }
                  $comment_html .= '<div class="media-body valign-middle">';
                  $comment_html .= '<div class="mtop5">';
                  $comment_html .= '<b>';
                  if($comment['staffid'] != 0){
                    $comment_html .= get_staff_full_name($comment['staffid']);
                  } else {
                    $comment_html .= _l('pur_vendor');
                  }
                  $comment_html .= '</b>';
                  $comment_html .= ' - <small class="mtop10 text-muted">' . time_ago($comment['dateadded']) . '</small>';
                  $comment_html .= '</div>';
              
                  $comment_html .= check_for_links($comment['content']) . '<br />';
                  $comment_html .= '</div>';
                  $comment_html .= '</div>';
                  $comment_html .= '<hr />';
                  }
                  echo $comment_html; ?>
            </div>

          </div>
          
        </div>
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
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

                              <input type="text" disabled="true" class="form-control text-right" name="total_mn" value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->subtotal,''); } ?>">

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
        </div>
        <div class="row">
          <div class="col-md-12 mtop15">
             <div class="panel-body bottom-transaction">
                <?php $value = (isset($pur_order) ? $pur_order->vendornote : ''); ?>
                <?php echo render_textarea('vendornote','estimate_add_edit_vendor_note',$value,array(),array(),'mtop15'); ?>
                <?php $value = (isset($pur_order) ? $pur_order->terms : ''); ?>
                <?php echo render_textarea('terms','terms_and_conditions',$value,array(),array(),'mtop15'); ?>
               
             </div>
               <div class="btn-bottom-pusher"></div>
          </div>
        </div>
        </div>

      </div>
    
      
    </div>
  </div>
</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/pur_order_vendor_js.php';?>
