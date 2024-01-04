<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div class="row">
	<?php $base_currency = get_base_currency_pur(); ?>
	<div class="col-md-12">
		<div class="panel_s">
			<div class="panel-body">
				<h4 class="mtop5"><?php echo html_entity_decode($title); ?></h4>
				

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
		                        $totalComments = total_rows(db_prefix().'pur_comments',['rel_id' => $pur_invoice->id, 'rel_type' => 'pur_invoice']);
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
						<div class="col-md-12 pad_left_0">
							<div class="col-md-6 pad_left_0 border-right align_div">
								<p><?php echo _l('invoice_number').':'; ?><span class="pull-right bold"><?php echo html_entity_decode($pur_invoice->invoice_number); ?></span></p>
							</div>
							<div class="col-md-6 pad_right_0 align_div">
								<p><?php echo _l('invoice_date').':'; ?><span class="label label-info pull-right bold"><?php echo _d($pur_invoice->invoice_date); ?></span></p>
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>

							<div class="col-md-6 pad_left_0 border-right align_div">
								<?php if($pur_invoice->contract != ''){ ?>
								<p><?php echo _l('contract').':'; ?><span class="pull-right bold"><a href="<?php echo site_url('purchase/vendors_portal/view_contract/'.$pur_invoice->contract); ?>" ><?php echo get_pur_contract_number($pur_invoice->contract); ?></a></span></p>
								<?php }else{ ?>
								<p><?php echo _l('pur_order').':'; ?><span class="pull-right bold"><a href="<?php echo site_url('purchase/vendors_portal/pur_order/'.$pur_invoice->pur_order); ?>" ><?php echo get_pur_order_subject($pur_invoice->pur_order); ?></a></span></p>	
								<?php } ?>
							</div>
							<div class="col-md-6 pad_right_0 align_div">
								<p><?php echo _l('invoice_amount').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->subtotal,$base_currency->symbol); ?></span></p>
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>

							<div class="col-md-6 pad_left_0 border-right align_div">
								<p><?php echo _l('tax').':'; ?><span class="pull-right bold"><?php echo html_entity_decode($pur_invoice->tax_rate).'%'; ?></span></p>
							</div>
							<div class="col-md-6 pad_right_0 align_div">
								<p><?php echo _l('tax_value').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->tax,$base_currency->symbol); ?></span></p>
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>

							<div class="col-md-6 pad_left_0 border-right align_div">
								<p><?php echo _l('pur_vendor').':'; ?><span class="pull-right bold"><?php echo get_vendor_company_name(get_vendor_user_id()); ?></span></p>
							</div>

							<div class="col-md-6  pad_right_0 align_div">
								<p><?php echo _l('total').':'; ?><span class="pull-right bold"><?php echo app_format_money($pur_invoice->total,$base_currency->symbol); ?></span></p>
							</div>
							
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>
						</div>

						<div class="col-md-12 pad_left_0">
							<div class="col-md-6 pad_left_0 border-right align_div">
								<p><?php echo _l('transaction_id').':'; ?><span class="pull-right bold"><?php echo html_entity_decode($pur_invoice->transactionid); ?></span></p>
							</div>
							<div class="col-md-6 pad_right_0 align_div">
								<p><?php echo _l('transaction_date').':'; ?><span class="pull-right bold"><?php echo _d($pur_invoice->transaction_date); ?></span></p>
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>
							<div class="col-md-6 pad_left_0 border-right align_div">
								<p><?php echo _l('add_from').':'; ?><span class="pull-right bold"><?php echo get_staff_full_name($pur_invoice->add_from); ?></span></p>
							</div>
							<div class="col-md-6 pad_right_0 align_div">
								<p><?php echo _l('date_add').':'; ?><span class="label label-info pull-right bold"><?php echo _d($pur_invoice->date_add); ?></span></p>
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>

							<div class="col-md-12 pad_left_0 pad_right_0 align_div">
								<p><span class="bold"><?php echo _l('vendor_note').': '; ?></span><span><?php echo html_entity_decode($pur_invoice->vendor_note); ?></span></p>
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0 align_div">
								<p><span class="bold"><?php echo _l('terms').': '; ?></span><span><?php echo html_entity_decode($pur_invoice->terms); ?></span></p>
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0">
								<hr class="mtop5 mbot5">
							</div>
							<div class="col-md-12 pad_left_0 pad_right_0 align_div">
								<p><span class="bold"><?php echo _l('adminnote').': '; ?></span><span><?php echo html_entity_decode($pur_invoice->adminnote); ?></span></p>
							</div>
							
						</div>
					</div>

					<div role="tabpanel" class="tab-pane <?php if($this->input->get('tab') === 'discussion'){echo ' active';} ?>" id="discuss">
		              <?php echo form_open($this->uri->uri_string()) ;?>
		               <div class="contract-comment">
		                  <textarea name="content" rows="4" class="form-control"></textarea>
		                  <button type="submit" class="btn btn-info mtop10 pull-right" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('proposal_add_comment'); ?></button>
		                  <?php echo form_hidden('action','inv_comment'); ?>
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
		</div>
	</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>