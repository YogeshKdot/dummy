<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">

          	<div class="row">
             <div class="col-md-12">
              <h4 class="no-margin font-bold"><i class="fa <?php if(isset($pur_invoice)){ echo 'fa-pencil-square-o';}else{ echo 'fa-plus';} ?>" aria-hidden="true"></i> <?php echo _l($title); ?> <?php if(isset($pur_invoice)){ echo ' '.html_entity_decode($pur_invoice->invoice_number); } ?></h4>
              <hr />
             </div>
            </div>
            <?php echo form_open_multipart(admin_url('purchase/pur_invoice_form'),array('id'=>'pur_invoice-form','class'=>'_pur_invoice_form')); ?>

            <div class="row">
            	<div class="col-md-6">
            		<?php echo form_hidden('id', (isset($pur_invoice) ? $pur_invoice->id : '') ); ?>
	            	<div class="col-md-6 pad_left_0">
	            		<label for="invoice_number"><span class="text-danger">* </span><?php echo _l('invoice_number'); ?></label>
		            	<?php
	                    $prefix = get_purchase_option('pur_inv_prefix');
	                    $next_number = get_purchase_option('next_inv_number');
	                    $number = (isset($pur_invoice) ? $pur_invoice->number : $next_number);
	                    echo form_hidden('number',$number); ?> 
	                           
	                    <?php $invoice_number = ( isset($pur_invoice) ? $pur_invoice->invoice_number : $prefix.str_pad($next_number,5,'0',STR_PAD_LEFT));
	                    echo render_input('invoice_number','',$invoice_number ,'text',array('readonly' => '', 'required' => 'true')); ?>
	                </div>

	                <div class="col-md-6 pad_right_0 form-group">
	                	<label for="vendor"><span class="text-danger">* </span><?php echo _l('pur_vendor'); ?></label>
	                    <select name="vendor" id="vendor" class="selectpicker" onchange="pur_vendor_change(this); return false;" required="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
	                        <option value=""></option>
	                        <?php foreach($vendors as $ven){ ?>
	                        	<option value="<?php echo html_entity_decode($ven['userid']); ?>" <?php if(isset($pur_invoice) && $pur_invoice->vendor == $ven['userid']){ echo 'selected'; } ?>><?php echo html_entity_decode($ven['vendor_code'].' '.$ven['company']); ?></option>
	                        <?php } ?>
	                    </select>
	                </div>
	                <?php if(get_purchase_option('create_invoice_by') == 'pur_order'){ ?>
		               <div class="col-md-12 form-group pad_left_0 pad_right_0">
		                	<label for="pur_order"><span class="text-danger">* </span><?php echo _l('pur_order'); ?></label>
		                    <select name="pur_order" id="pur_order" class="selectpicker" onchange="pur_order_change(this); return false;" required="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
		                        <option value=""></option>
		                        <?php foreach($pur_orders as $ct){ ?>
		                        	<option value="<?php echo html_entity_decode($ct['id']); ?>" <?php if(isset($pur_invoice) && $pur_invoice->pur_order == $ct['id']){ echo 'selected'; } ?>><?php echo html_entity_decode($ct['pur_order_number']); ?></option>
		                        <?php } ?>
		                    </select>
		                </div>
		            <?php }else{ ?>
		            	 <div class="col-md-12 form-group pad_left_0 pad_right_0">
		                	<label for="contract"><?php echo _l('contract'); ?></label>
		                    <select name="contract" id="contract" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
		                        <option value=""></option>
		                        <?php foreach($contracts as $ct){ ?>
		                        	<option value="<?php echo html_entity_decode($ct['id']); ?>" <?php if(isset($pur_invoice) && $pur_invoice->contract == $ct['id']){ echo 'selected'; } ?>><?php echo html_entity_decode($ct['contract_number']); ?></option>
		                        <?php } ?>
		                    </select>
		                </div>
		            <?php } ?>

	                <div class="col-md-6 pad_left_0">
	                	<label for="invoice_date"><span class="text-danger">* </span><?php echo _l('invoice_date'); ?></label>
	                	<?php $invoice_date = ( isset($pur_invoice) ? _d($pur_invoice->invoice_date) : _d(date('Y-m-d')) );
	                	 echo render_date_input('invoice_date','',$invoice_date,array( 'required' => 'true')); ?>
	                </div>

	                <div class="col-md-6 pad_right_0">
	                	<label for="invoice_date"><?php echo _l('pur_due_date'); ?></label>
	                	<?php $duedate = ( isset($pur_invoice) ? _d($pur_invoice->duedate) : _d(date('Y-m-d')) );
	                	 echo render_date_input('duedate','',$duedate); ?>
	                </div>

	                <div class="col-md-6 pad_left_0 ">
	                	<?php $subtotal = ( isset($pur_invoice) ? app_format_money($pur_invoice->subtotal, '') : '');
	                	echo render_input('subtotal','invoice_amount',$subtotal,'text', array('onchange' => 'subtotal_change(this); return false;', 'data-type' => 'currency' ),[],'','text-right'); ?>
	                </div>

	                <div class="col-md-6 form-group pad_right_0">
	                	<label for="tax_rate"><?php echo _l('tax'); ?></label>
	                    <select name="tax_rate" <?php if(get_purchase_option('create_invoice_by') == 'pur_order' && !isset($pur_invoice)){ echo 'disabled="true"'; }?> id="tax_rate" class="selectpicker" onchange="tax_rate_change(this); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
	                        <option value=""></option>
	                        <?php foreach($taxes as $t){ ?>
	                        	<option value="<?php echo html_entity_decode($t['id']); ?>" <?php if(isset($pur_invoice) && $pur_invoice->tax_rate == $t['id']){ echo 'selected'; } ?> ><?php echo html_entity_decode($t['label']); ?></option>
	                        <?php } ?>
	                    </select>
	                </div>
	                <div class="col-md-6 pad_left_0">
	                	<?php $attr = array();
	                		  if(get_purchase_option('create_invoice_by') == 'pur_order' && !isset($pur_invoice)){ 
	                		  	$attr = array( 'readonly' => 'true');
	                		  }else{
	                		  	$attr = array();
	                		  }
	                	$tax = ( isset($pur_invoice) ? app_format_money($pur_invoice->tax, '') : '');
	                	echo render_input('tax','tax_value',$tax,'text', $attr,[],'','text-right'); ?>
	                </div>

	                <div class="col-md-6 pad_right_0">
	                	<?php $total = ( isset($pur_invoice) ? app_format_money($pur_invoice->total, '') : '');
	                	echo render_input('total','total_included_tax',$total,'text', array('data-type' => 'currency'),[],'','text-right'); ?>
	                </div>

	                <?php if(get_purchase_option('create_invoice_by') == 'contract'){ ?>

	                <div class="form-group col-md-12 pad_left_0 pad_right_0">
	                	<label for="recurring"><?php echo _l('invoice_add_edit_recurring'); ?></label>
	                    <select name="recurring" id="recurring" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
	                        <?php for($i = 0; $i <=12; $i++){ ?>
	                        	<?php
                              $selected = '';
                              if(isset($pur_invoice)){
                                
	                             if($pur_invoice->recurring == $i){
	                               $selected = 'selected';
	                             }
                              }
                              if($i == 0){
                               $reccuring_string =  _l('invoice_add_edit_recurring_no');
                              } else if($i == 1){
                               $reccuring_string = _l('invoice_add_edit_recurring_month',$i);
                              } else {
                               $reccuring_string = _l('invoice_add_edit_recurring_months',$i);
                              }
                              ?>

	                        	<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $reccuring_string; ?></option>
	                        <?php } ?>
	                    </select>
	                </div>

	                <div id="cycles_wrapper" class="<?php if(!isset($pur_invoice) || (isset($pur_invoice) && $pur_invoice->recurring == 0)){echo ' hide';}?>">
	                     <div class="col-md-12 pad_left_0 pad_right_0">
	                        <?php $value = (isset($pur_invoice) ? $pur_invoice->cycles : 0); ?>
	                        <div class="form-group recurring-cycles">
	                          <label for="cycles"><?php echo _l('recurring_total_cycles'); ?>
	                            <?php if(isset($pur_invoice) && $pur_invoice->total_cycles > 0){
	                              echo '<small>' . _l('cycles_passed', $pur_invoice->total_cycles) . '</small>';
	                            }
	                            ?>
	                          </label>
	                          <div class="input-group">
	                            <input type="number" class="form-control"<?php if($value == 0){echo ' disabled'; } ?> name="cycles" id="cycles" value="<?php echo $value; ?>" <?php if(isset($pur_invoice) && $pur_invoice->total_cycles > 0){echo 'min="'.($pur_invoice->total_cycles).'"';} ?>>
	                            <div class="input-group-addon">
	                              <div class="checkbox">
	                                <input type="checkbox"<?php if($value == 0){echo ' checked';} ?> id="unlimited_cycles">
	                                <label for="unlimited_cycles"><?php echo _l('cycles_infinity'); ?></label>
	                              </div>
	                            </div>
	                          </div>
	                        </div>
	                     </div>
	                </div>

	             	<?php } ?>

	                <div class="col-md-12 pad_left_0 pad_right_0">
	                	<?php $adminnote = ( isset($pur_invoice) ? $pur_invoice->adminnote : '');
	                	echo render_textarea('adminnote','adminnote',$adminnote, array('rows' => 7)) ?>
	                </div>

	            </div>

	            <div class="col-md-6">
	                <div class="form-group">
	                    <div id="inputTagsWrapper">
	                       <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
	                       <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($pur_invoice) ? prep_tags_input(get_tags_in($pur_invoice->id,'pur_invoice')) : ''); ?>" data-role="tagsinput">
	                    </div>
	                </div>
	                <div class="col-md-6 pad_left_0">
	                	<?php $transactionid = ( isset($pur_invoice) ? $pur_invoice->transactionid : '');
	                	echo render_input('transactionid','transaction_id',$transactionid); ?>
	                </div>
	                <div class="col-md-6 pad_right_0">
	                	<?php $transaction_date = ( isset($pur_invoice) ? $pur_invoice->transaction_date : '');
	                	echo render_date_input('transaction_date','transaction_date',$transaction_date); ?>
	                </div>
	                <div class="col-md-12 pad_left_0 pad_right_0">
	                	<?php $vendor_note = ( isset($pur_invoice) ? $pur_invoice->vendor_note : '');
	                	echo render_textarea('vendor_note','vendor_note',$vendor_note, array('rows' => 7)) ?>
	                </div>
	                <div class="col-md-12 pad_left_0 pad_right_0">
	                	<?php $terms = ( isset($pur_invoice) ? $pur_invoice->terms : '');
	                	echo render_textarea('terms','terms',$terms, array('rows' => 7)) ?>
	                </div>
	                <div class="col-md-12 pad_left_0 pad_right_0">
				        <div class="attachments">
				          <div class="attachment">
				            <div class="mbot15">
				              <div class="form-group">
				                <label for="attachment" class="control-label"><?php echo _l('ticket_add_attachments'); ?></label>
				                <div class="input-group">
				                  <input type="file" extension="<?php echo str_replace('.','',get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
				                  <span class="input-group-btn">
				                    <button class="btn btn-success add_more_attachments p8-half" data-max="10" type="button"><i class="fa fa-plus"></i></button>
				                  </span>
				                </div>
				              </div>
				            </div>
				          </div>
				        </div>
				    </div>
	            </div>

            </div>

           	
              <h4 class="no-margin font-bold mtop5"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><?php echo _l('custom_fields'); ?></h4>
              <hr />
             

					<?php $rel_id=( isset($pur_invoice) ? $pur_invoice->id : false); ?>
                    <?php echo render_custom_fields( 'pur_invoice',$rel_id); ?>

            <div class="row">
            	<hr>
        	</div>
        	<div class="text-right">
                  <button type="submit" class="btn btn-info mbot15"><?php echo _l('submit'); ?></button>
             </div>
        	<?php echo form_close(); ?>
          </div>
      	</div>
  	</div>
  </div>
</div>

<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/pur_invoice_js.php';?>
