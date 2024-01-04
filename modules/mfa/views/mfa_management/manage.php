<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
	    <div class="row">
	    	<div class="panel_s ">
	    		<?php echo form_open('mfa/mfa_manage',array('id'=>'mfa_manage-form')); ?>
    			<div class="panel-body">
    				<h4><i class="fa fa-lock"></i><?php echo ' '._l('mfa_management'); ?></h4>
    				<hr class="mtop5" />
 
	    				<div class="col-md-12">

		    				<div class="col-md-4">
						 		<div class="mbot10 col-md-12 channel google_ath shadow <?php if($staff->mfa_google_ath_enable == 1){ echo 'active'; } ?> red">

									<div class="switch">
									    <div class="onoffswitch" data-toggle="tooltip" data-placement="top" data-title="<?php echo _l('active'); ?>">
									        <input type="checkbox" id="mfa_google_ath_enable" <?php if(get_mfa_option('enable_google_authenticator') != 1 || get_mfa_option('enable_mfa') != 1 || enable_gg_auth_with_role($staff->role) != 1){ echo 'disabled'; } ?> name="mfa_google_ath_enable" class="onoffswitch-checkbox" data-channel="google_ath" <?php if($staff->mfa_google_ath_enable == 1){ echo 'checked';} ?> value="mfa_google_ath_enable" >
									        <label class="onoffswitch-label" for="mfa_google_ath_enable">
									            <span class="onoffswitch-inner"></span>
									            <span class="onoffswitch-switch"></span>
									        </label>
									    </div>	
								    </div>
								    
							    </div>
			    				<div id="sr_key_div" class="<?php if($staff->mfa_google_ath_enable != 1){ echo 'hide';} ?>">  
								  	<div class="col-md-9">
								  		<?php echo render_input('gg_auth_secret_key', 'secret_key', $staff->gg_auth_secret_key,'', array('readonly' => 'true')); ?>
								  	</div>
								  	<div class="col-md-3">
								  		<?php if($staff->gg_auth_secret_key != ''){ ?>
								  		<button type="button" onclick="view_qr_code(); return false;" class="btn btn-icon btn-success mtop25 pull-right" data-toggle="tooltip" data-placement="top" title="<?php echo _l('mfa_view_qr_code'); ?>"><i class="fa fa-qrcode"></i></button>
								  		<?php } ?>
								  		<button type="button" onclick="create_secret_key(); return false;" class="btn btn-icon btn-info mtop25 mright5 pull-right" data-toggle="tooltip" data-placement="top" title="<?php echo _l('create_secret_key_tooltip'); ?>"><i class="fa fa-refresh"></i></button>
								  	
								  		
								  	</div>
								  	<div class="col-md-12">
								  	 	<p><span class="text-danger"><?php echo _l('google_authenticator_note'); ?></span></p>
								    </div>
	        					</div>
							</div>


							<div class="col-md-4">
						 		<div class="col-md-12 channel whatsapp shadow <?php if($staff->mfa_whatsapp_enable == 1){ echo 'active'; } ?> red mbot10">
	

									<div class="switch">
									    <div class="onoffswitch" data-toggle="tooltip" data-placement="top" data-title="<?php echo _l('active'); ?>">
									        <input type="checkbox" id="mfa_whatsapp_enable" <?php if(get_mfa_option('enable_whatsapp') != 1 || get_mfa_option('enable_mfa') != 1 ){ echo 'disabled'; } ?> name="mfa_whatsapp_enable" class="onoffswitch-checkbox" data-channel="whatsapp" <?php if($staff->mfa_whatsapp_enable == 1){ echo 'checked';} ?> value="mfa_whatsapp_enable" >
									        <label class="onoffswitch-label" for="mfa_whatsapp_enable">
									            <span class="onoffswitch-inner"></span>
									            <span class="onoffswitch-switch"></span>
									        </label>
									    </div>	
								    </div>
								    
							    </div>

							    <div id="whatsapp_number_div" class="<?php if($staff->mfa_whatsapp_enable != 1){ echo 'hide'; } ?>">
								  <?php 
								  $attr = array();
								  if($staff->mfa_whatsapp_enable == 1){ 
								  	 $attr = array('required' => 'true');
								  }
								  echo render_input('whatsapp_number', 'whatsapp_number', $staff->whatsapp_number, 'text', $attr); ?>
								</div>
							</div>


							<div class="col-md-4">
						 		<div class="col-md-12 channel sms shadow <?php if($staff->mfa_sms_enable == 1){ echo 'active'; } ?> red mbot10">

									<div class="switch">
									    <div class="onoffswitch" data-toggle="tooltip" data-placement="top" data-title="<?php echo _l('active'); ?>">
									        <input type="checkbox" id="mfa_sms_enable" <?php if(get_mfa_option('enable_sms') != 1 || get_mfa_option('enable_mfa') != 1 ){ echo 'disabled'; } ?> name="mfa_sms_enable" class="onoffswitch-checkbox" data-channel="google_ath" <?php if($staff->mfa_sms_enable == 1){ echo 'checked';} ?> value="mfa_sms_enable" >
									        <label class="onoffswitch-label" for="mfa_sms_enable">
									            <span class="onoffswitch-inner"></span>
									            <span class="onoffswitch-switch"></span>
									        </label>
									    </div>	
								    </div>
								    
							    </div>	
							    <div id="phonenumber_div" class="<?php if($staff->mfa_sms_enable != 1){ echo 'hide'; } ?>">
							      <?php 
								  $attr = array();
								  if($staff->mfa_sms_enable == 1){ 
								  	 $attr = array('required' => 'true');
								  }
								   echo render_input('phonenumber', 'mfa_phonenumber', $staff->phonenumber, 'text', $attr); ?>
								</div>        
							</div>

		    				
		    			</div>

	    		
    				<div class="col-md-12 pleft0 pright0">
						<hr class="mtop5" />
					</div>
					<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
					<?php echo form_close(); ?>
					
    			</div>
    		</div>
	    </div>
	</div>
</div>

<div class="modal fade" id="qr_code_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
	    <div class="modal-content modal_withd">
		    <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">
		          <span><?php echo _l('mfa_qr_code'); ?></span>
		        </h4>
		    </div>
	      	<div class="modal-body">
	      		<div class="row text-center">
	      			<label><?php echo _l('scan_qr_code_by_app'); ?></label>
	      			<div id="qr_div">
	      				
	      			</div>
	      		</div>
		    </div>
			<div class="modal-footer">
			  <button type=""class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
			</div>
		</div><!-- /.modal-content -->

	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>
</body>
</html>