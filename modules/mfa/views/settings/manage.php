<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
	    <div class="row">

	    	<div class="col-md-3 pleft0 pright0">
	    		<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
				   <?php
				      $i = 0;
				      foreach($tabs as $group){
				        ?>
				        <li <?php if($i == 0){echo " class='active'"; } ?> >
				        <a href="<?php echo admin_url('mfa/settings?group='.$group['name']); ?>" data-group="<?php echo html_entity_decode($group['name']); ?>">
				         <?php echo html_entity_decode($group['icon']).' '._l($group['name']); ?></a>
				        </li>
				        <?php $i++; } ?>
				</ul>
	    	</div>

	    	<div class="col-md-9">
		    	<div class="panel_s ">
		    		<?php echo form_open('mfa/mfa_setting/'.$tab,array('id'=>'mfa_setting-form')); ?>
	    			<div class="panel-body">
	    				<h4><i class="fa fa-cog"></i><?php echo ' '._l('mfa').' '._l('mfa_setting'); ?></h4>
	    				<hr class="mtop5 mbot5" />

	    				<?php if($tab == 'mfa_general') { ?>
		    				<div class="col-md-4">
							  <div class="checkbox checkbox-primary">
							    <input type="checkbox" id="enable_mfa" name="enable_mfa" <?php if(get_mfa_option('enable_mfa') == 1 ){ echo 'checked';} ?> value="enable_mfa">
							    <label for="enable_mfa"><?php echo _l('enable_mfa'); ?></label>
							  </div>
		    				</div>
		    				<div class="col-md-8">
		    					<div class="col-md-6">
		    						<?php echo render_input('delete_history_after_months', 'delete_history_after_months', get_mfa_option('delete_history_after_months'), 'number'); ?>
		    					</div>
		    					<div class="col-md-6 pright0">
		    						<a href="javascript:void(0)" onclick="clear_mfa_logs(); return false;" class="btn btn-danger pull-right mtop25"><?php echo _l('clear_logs'); ?></a>
		    					</div>
		    				</div>
		    			<?php }else if($tab == 'mfa_google_authenticator') { ?>
		    				<div class="col-md-12">
		    				  <div class="checkbox checkbox-primary">
							    <input type="checkbox" id="enable_google_authenticator" name="enable_google_authenticator" <?php if(get_mfa_option('enable_google_authenticator') == 1 ){ echo 'checked';} ?> value="enable_google_authenticator">
							    <label for="enable_google_authenticator"><?php echo _l('mfa_enable').' '. _l('google_authenticator'); ?></label>
							  </div>
							  
							  <table class="table table-bordered table-striped">
							  	<thead>
							  		<tr>
							  			<th><?php echo _l('mfa_role_name'); ?></th>
							  			<th><?php echo _l('mfa_total_users'); ?></th>
							  			<th><?php echo _l('mfa_enable_gg_auth'); ?></th>
							  		</tr>
							  	</thead>
							  	<tbody>
							  		
							  		<?php foreach($roles as $role){ ?>
							  			<tr>
							  				<td><?php echo html_entity_decode($role['name']); ?></td>
							  				<td><?php echo '<a href="javascript:void(0)" onclick="list_users_of_role('.$role['roleid'].'); return false;">'.total_rows(db_prefix().'staff', ['role' => $role['roleid']]).'</a>'; ?></td>
							  				<td>
							  					<div class="switch">
												    <div class="onoffswitch" data-toggle="tooltip" data-placement="top" data-title="<?php echo _l('active'); ?>">
												        <input type="checkbox" id="enable_gg_auth_<?php echo html_entity_decode($role['roleid']); ?>" name="enable_gg_auth_<?php echo html_entity_decode($role['roleid']); ?>" class="onoffswitch-checkbox" data-channel="google_ath" <?php if($role['enable_gg_auth'] == 1){ echo 'checked';} ?> value="enable_gg_auth_<?php echo html_entity_decode($role['roleid']); ?>" >
												        <label class="onoffswitch-label" for="enable_gg_auth_<?php echo html_entity_decode($role['roleid']); ?>">
												            <span class="onoffswitch-inner"></span>
												            <span class="onoffswitch-switch"></span>
												        </label>
												    </div>	
											    </div>
							  				</td>
							  			</tr>
							  		<?php } ?>

							  		<?php if(total_rows(db_prefix().'staff', 'role = 0 or role IS NULL') > 0){ ?>
								  		<tr>
								  			<td><?php echo _l('users_have_not_role_on_crm'); ?></td>
								  			<td><?php echo '<a href="javascript:void(0)" onclick="list_users_of_role(0); return false;">'.total_rows(db_prefix().'staff', 'role = 0 or role IS NULL').'</a>'; ?></td>
								  			<td>
							  					<div class="switch">
												    <div class="onoffswitch" data-toggle="tooltip" data-placement="top" data-title="<?php echo _l('active'); ?>">
												        <input type="checkbox" id="enable_gg_auth_0" name="enable_gg_auth_0" class="onoffswitch-checkbox" data-channel="google_ath" <?php if(get_mfa_option('enable_gg_auth_for_users_have_not_role') == 1){ echo 'checked';} ?> value="enable_gg_auth_0" >
												        <label class="onoffswitch-label" for="enable_gg_auth_0">
												            <span class="onoffswitch-inner"></span>
												            <span class="onoffswitch-switch"></span>
												        </label>
												    </div>	
											    </div>
							  				</td>
								  		</tr>
								  	<?php } ?>
							  	</tbody>
							  </table>
		    				</div>

		    			<?php }else if($tab == 'mfa_whatsapp'){ ?>

		    				<div class="col-md-12">
			    				<div class="col-md-12 pleft0">
			    				  <div class="checkbox checkbox-primary">
								    <input type="checkbox" id="enable_whatsapp" name="enable_whatsapp" <?php if(get_mfa_option('enable_whatsapp') == 1 ){ echo 'checked';} ?> value="enable_whatsapp">
								    <label for="enable_whatsapp"><?php echo _l('mfa_enable').' '._l('mfa_whatsapp'); ?></label>
								  </div>
			    				</div>

			    				<div class="col-md-12 pleft0 pright0">
			    					<hr class="mtop5">
			    				</div>

			    				<?php 
			    					$attr = array(); 
			    					if(get_mfa_option('enable_whatsapp') == 1){
			    						$attr = array('required' => 'true');
			    					}
			    				?>

			    				<label for="twilio_account_sid"><span class="text-danger"><?php if(get_mfa_option('enable_whatsapp') == 1 ){ echo '* ';} ?> </span><?php echo _l('twilio_account_sid'); ?></label>
			    				<?php echo render_input('twilio_account_sid', '', get_mfa_option('twilio_account_sid'), 'text', $attr); ?>

			    				<label for="twilio_auth_token"><span class="text-danger"><?php if(get_mfa_option('enable_whatsapp') == 1 ){ echo '* ';} ?> </span><?php echo _l('twilio_auth_token'); ?></label>
			    				<?php echo render_input('twilio_auth_token', '', get_mfa_option('twilio_auth_token'), 'text', $attr); ?>

			    				<label for="twilio_phone_number"><span class="text-danger"><?php if(get_mfa_option('enable_whatsapp') == 1 ){ echo '* ';} ?> </span><?php echo _l('twilio_phone_number_for_whatsapp'); ?> <a href="https://www.twilio.com/docs/whatsapp/tutorial/connect-number-business-profile" target="_blank" data-toggle="tooltip" data-placement="top" title="<?php echo _l('sender_phonenumber_tooltip'); ?>" ><i class="fa fa-question-circle"></i></a></label>
			    				<?php echo render_input('twilio_phone_number', '', get_mfa_option('twilio_phone_number'), 'text', $attr); ?>

			    				<label for="whatsapp_message_template"><span class="text-danger"><?php if(get_mfa_option('enable_whatsapp') == 1 ){ echo '* ';} ?> </span><?php echo _l('whatsapp_message_template'); ?></label>
			    				<?php echo render_input('whatsapp_message_template', '', get_mfa_option('whatsapp_message_template'), 'text', $attr); ?>

			    				<div class="panel-group">
								  <div class="panel panel-warning">
								    <div class="panel-heading">
								    	<?php 
								    	$template = '<span class="text-danger">'.get_mfa_option('whatsapp_message_template').'</span>';
								    	$link = '<a href="https://www.twilio.com/docs/whatsapp/tutorial/send-whatsapp-notification-messages-templates" target="_blank">'._l('mfa_here').'</a>'; ?>
								    	<?php echo _l('whatsapp_template_note', $template); ?><br>
								    	<?php echo _l('you_can_refer_this_template').': "Your login code for {{1}} is {{2}}"'; ?><br>
								    	{{1}} <?php echo _l('will_be_replaced_with').' '._l('your_site_ex').' "'.admin_url().'"'; ?><br>
								    	{{2}} <?php echo _l('will_be_replaced_with').' '._l('security_code_ex', '<span class="text-danger">123456</span>'); ?> <i class="fa fa-arrow-right"></i> Your login code for <?php echo admin_url(); ?> is 123456<br>
								    	<?php echo _l('click_here_to_view_detail', $link); ?>
								    </div>
								  </div>
								</div>

								<div class="panel-group">
								  <div class="panel panel-info">
								    <div class="panel-heading">
								    	<h4><?php echo _l('test_whatsapp_config'); ?></h4>
								    	<?php echo render_input('your_whatsapp_phonenumber', 'your_whatsapp_phonenumber'); ?>
								    	<button type="button" onclick="send_test_message(this); return false;" class="btn btn-info"><?php echo _l('send_test_message'); ?></button>
								    </div>
								  </div>
								</div>

			    			</div>

		    			<?php }else if($tab == 'mfa_sms'){ ?>
		    				<div class="col-md-12 pleft0 mtop5">
		    				  <div class="checkbox checkbox-primary">
							    <input type="checkbox" id="enable_sms" name="enable_sms" <?php if(get_mfa_option('enable_sms') == 1 ){ echo 'checked';} ?> value="enable_sms">
							    <label for="enable_sms"><?php echo _l('mfa_enable').' '._l('mfa_sms'); ?></label>
							  </div>
		    				</div>

		    				<div class="row col-md-12">
		    				<?php 
								hooks()->do_action('before_sms_gateways_settings');

								$gateways = $this->app_sms->get_gateways();
								$total_gateways = count($gateways);

								if($total_gateways > 1) { ?>
								    <div class="alert alert-info">
								        <?php echo _l('notice_only_one_active_sms_gateway'); ?>
								    </div>
								<?php } ?>

								<div class="panel-group" id="sms_gateways_options" role="tablist" aria-multiselectable="false">
								    <?php foreach($gateways as $gateway) { ?>
								    <div class="panel panel-default">
								        <div class="panel-heading" role="tab" id="<?php echo 'heading'.$gateway['id']; ?>">
								          <h4 class="panel-title">
								            <a role="button" data-toggle="collapse" data-parent="#sms_gateways_options" href="#sms_<?php echo $gateway['id']; ?>" aria-expanded="true" aria-controls="sms_<?php echo $gateway['id']; ?>">
								                <?php echo html_entity_decode($gateway['name']); ?> <span class="pull-right"><i class="fa fa-sort-down"></i></span>
								            </a>
								        </h4>
								    </div>
								    <div id="sms_<?php echo html_entity_decode($gateway['id']); ?>" class="panel-collapse collapse<?php if($this->app_sms->get_option($gateway['id'],'active') == 1 || $total_gateways == 1){echo ' in';} ?>" role="tabpanel" aria-labelledby="<?php echo 'heading'.$gateway['id']; ?>">
								      <div class="panel-body no-br-tlr no-border-color">

								        <?php
								        if(isset($gateway['info']) && $gateway['info'] != '') {
								            echo html_entity_decode($gateway['info']);
								        }

								        foreach($gateway['options'] as $g_option){
								            echo render_input('settings['.$this->app_sms->option_name($gateway['id'],$g_option['name']).']',$g_option['label'],$this->app_sms->get_option($gateway['id'],$g_option['name']));
								            if(isset($g_option['info'])) {
								                echo html_entity_decode($g_option['info']);
								            }
								        }
								        echo '<div class="sms_gateway_active">';

								        echo render_yes_no_option($this->app_sms->option_name($gateway['id'],'active'),'Active');
								        echo '</div>';
								            if(get_option($this->app_sms->option_name($gateway['id'],'active')) == '1') {
								                echo '<hr />';
								                echo '<h4 class="mbot15">'._l('test_sms_config').'</h4>';
								                echo '<div class="form-group"><input type="text" placeholder="'._l('staff_add_edit_phonenumber').'" class="form-control test-phone" data-id="'.$gateway['id'].'"></div>';
								                echo '<div class="form-group"><textarea class="form-control sms-gateway-test-message" placeholder="'._l('test_sms_message').'" data-id="'.$gateway['id'].'" rows="4"></textarea></div>';
								                echo '<button type="button" class="btn btn-info send-test-sms" data-id="'.$gateway['id'].'">'._l('send_test_sms').'</button>';
								                echo '<div id="sms_test_response" data-id="'.$gateway['id'].'"></div>';
								            }
								        ?>
								    </div>
								</div>
								</div>
								<?php } ?>
								
								</div>

							</div>
		    			<?php } ?>
	    			</div>
	    		</div>
	    	</div>
	    </div>

	    <div class="btn-bottom-toolbar text-right mbtn_bot">
          <button type="submit" class="btn btn-info">
            <?php echo _l('settings_save'); ?>
          </button>
        </div>
		<?php echo form_close(); ?>
	</div>
</div>

<div class="modal fade" id="list_role_user" tabindex="-1" role="dialog">
	<div class="modal-dialog">
	    
	    <div class="modal-content ">
		    <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">
		          <span id="list_users_title"></span>
		        </h4>
		    </div>
	      	<div class="modal-body">
	      		<div class="row ">
	      			<div class="col-md-12 mtop5" id="list_user_div">
	      				
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