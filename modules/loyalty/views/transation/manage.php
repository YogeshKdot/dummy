<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="panel_s mbot10">
				<div class="panel-body">
          <div class="horizontal-scrollable-tabs preview-tabs-top">
                   
            <div class="horizontal-tabs">
            <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">

               <li role="presentation" class="active">
                   <a href="#transation" aria-controls="transation" role="tab" data-toggle="tab" aria-controls="transation">
                    <i class="fa fa-backward"></i>&nbsp;<?php echo _l('transation'); ?>
                   </a>
                </li>
                <li role="presentation">
                   <a href="#redeem_log" aria-controls="redeem_log" role="tab" data-toggle="tab" aria-controls="redeem_log">
                   <i class="fa fa-history"></i>&nbsp;<?php echo _l('redeem_log'); ?>
                   </a>
                </li>

             </ul>
             </div>
          </div>

          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="transation">
              <br>
            	<div class="row">    
                <div class="col-md-12"> 
                  	<?php if (has_permission('loyalty', '', 'create') || is_admin()) { ?>
                      <a href="#" onclick="new_transation(); return false;" class="btn btn-info pull-left mright10 display-block">
                          <?php echo _l('new'); ?>
                      </a>
                      <?php } ?>
                    <div class="col-md-3">
                      <select name="client_filter[]" id="client_filter" class="selectpicker"  data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('client'); ?>" >
                          <?php foreach($clients as $cli){ ?>
                            <option value="<?php echo html_entity_decode($cli['userid']); ?>" <?php if(isset($cus) && $cus == $cli['userid']){ echo 'selected'; } ?> ><?php echo html_entity_decode($cli['company']); ?></option>
                          <?php } ?>
                      </select>
                    </div>  

                    <div class="col-md-3">
                      <select name="reference[]" id="reference" class="selectpicker"  data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('reference'); ?>" >
                          <option value="manual_credit"><?php echo _l('manual_credit'); ?></option>
                          <option value="order_debit"><?php echo _l('order_debit'); ?></option>
                      </select>
                    </div> 
  		          </div>
    					<div class="col-md-12">
    						<hr>	
    					</div>
          	</div>
            <div class="row">
    						<div class="col-md-12" id="small-table">
    			                    <?php render_datatable(array(
    			                        _l('client'),
    			                        _l('reference'),
    			                        _l('invoice'),
                                  _l('loyalty_point'),
                                  _l('type'),
                                  _l('add_from'),
                                  _l('date_create'),
                                  _l('note'),
                                  _l('options'),
    			                        ),'table_transation'); ?>
    						</div>
        		</div>
          </div>

          <div role="tabpanel" class="tab-pane" id="redeem_log">

             <br>
              <div class="row">    
                
                   
                    <div class="col-md-3">
                      <select name="client_filter_rd[]" id="client_filter_rd" class="selectpicker"  data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('client'); ?>" >
                          <?php foreach($clients as $cli){ ?>
                            <option value="<?php echo html_entity_decode($cli['userid']); ?>" <?php if(isset($cus) && $cus == $cli['userid']){ echo 'selected'; } ?> ><?php echo html_entity_decode($cli['company']); ?></option>
                          <?php } ?>
                      </select>
                    </div>  

                    
              
              <div class="col-md-12">
                <hr>  
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <?php render_datatable(array(
                                  _l('client'),
                                  _l('invoice'),
                                  _l('old_point'),
                                  _l('new_point'),
                                  _l('redeem_from'),
                                  _l('redeem_to'),
                                  _l('time'),
                                  ),'table_redeem_log'); ?>
              </div>
            </div>
          </div>

        </div>


        </div>
      </div>
		</div>
	</div>
</div>
<div class="modal fade" id="transation_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
      <?php echo form_open(admin_url('loyalty/transation_form'),array('id'=>'transation-form')); ?>
      <div class="modal-content modal_withd">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">
                  <span class="add-title"><?php echo _l('add_transation'); ?></span>
                  <span class="edit-title"><?php echo _l('edit_transation'); ?></span>
              </h4>
          </div>
          <div class="modal-body">
          	  <div id="additional_transation"></div>
              <div class="row">

                <div class="col-md-12 form-group">
                  <label for="client"><span class="text-danger">* </span><?php echo _l('client'); ?></label>
                    <select name="client" id="client" class="selectpicker"  data-live-search="true" required data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                        <option value=""></option>
                        <?php foreach($clients as $cli){ ?>
                          <option value="<?php echo html_entity_decode($cli['userid']); ?>"><?php echo html_entity_decode($cli['company']); ?></option>
                        <?php } ?>
                    </select>
                    <br>
                </div>

                <div class="col-md-6">
                  <label for="loyalty_point"><span class="text-danger">* </span><?php echo _l('loyalty_point'); ?></label>
                  <?php echo render_input('loyalty_point','','','number',array('required' => 'true')); ?>
                </div> 

                <div class="col-md-6 form-group">
                  <label for="type"><span class="text-danger">* </span><?php echo _l('action'); ?></label>
                    <select name="type" id="type" class="selectpicker" data-live-search="true" required data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                       <option value=""></option>
                       <option value="credit"><?php echo _l('credit'); ?></option>
                       <option value="debit"><?php echo _l('debit'); ?></option>
                    </select>
                    <br>
                </div>
                
                <div class="col-md-12">
                  <?php echo render_textarea('note','note') ?>
                </div>     
                       
              </div>
          </div>
          <div class="modal-footer">
              <button type=""class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
              <button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
          </div>
      </div><!-- /.modal-content -->
          <?php echo form_close(); ?>
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
<?php init_tail(); ?>
</body>
</html>
