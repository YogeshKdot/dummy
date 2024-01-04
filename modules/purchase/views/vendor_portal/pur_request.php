<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      
      <div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
          
          <div class="row">
            <div class="col-md-12">
              <p class="bold p_style"><?php echo _l('pur_general_infor'); ?></p>
              <hr class="hr_style"/>
            </div>
             <div class="col-md-6">
                <table class="table table-striped table-bordered">
                  <tr>
                    <td width="30%"><?php echo _l('pur_rq_code'); ?></td>
                    <td><?php echo html_entity_decode($pur_request->pur_rq_code); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('pur_rq_name'); ?></td>
                    <td><?php echo html_entity_decode($pur_request->pur_rq_name); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('description'); ?></td>
                    <td><?php echo html_entity_decode($pur_request->rq_description); ?></td>
                  </tr>
                </table>
             </div>
             <div class="col-md-6">
                <table class="table table-striped table-bordered">
                  <tr>
                    <td width="30%"><?php echo _l('request_date'); ?></td>
                    <td><?php echo _dt($pur_request->request_date); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('requester'); ?></td>
                    <td><?php echo get_staff_full_name($pur_request->requester); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('status'); ?></td>
                    <td><?php echo get_status_approve($pur_request->status); ?></td>
                  </tr>
                </table>
             </div>  
               
          </div>
        </div>
        <div class="panel-body mtop10">
        <div class="row">

        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('pur_detail'); ?></p>
        <hr class="hr_style"/>
         <div class="" id="example">
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
<?php require 'modules/purchase/assets/js/pur_request_vendor_js.php';?>
