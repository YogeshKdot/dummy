<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="row">
   	<?php if (is_admin()) { ?>
   		<div class="col-md-10 col-md-offset-1" id="small-table">
			 <div class="panel_s">
			    <div class="panel-body">
	          		<div class="row">
	          			<div class="col-md-12">
	          		    	<h4 style="margin-top:10px;"><?php echo _l('extended_smtp_settings_subheading'); ?>
	          				</h4>
	          			</div>
	          		</div>
	          		<div class="clearfix"></div>
	          		<hr class="hr-panel-heading"/>
	          		<div class="row">
	          			<div class="col-md-5">
	          				<?php echo render_select('staff', get_all_staff_members(), ['staffid', ['firstname', 'lastname']], _l('extended_smtp_settings_select_staff')); ?>
	          			</div>
	          			<div class="col-md-5 pull-right">
	          				<div class="row">
	          					<label><?php echo _l('allow_staff_member_mods'); ?></label>
	          				</div>
	          				<div class="row">
	          					<div class="onoffswitch">
	          					    <input type="checkbox" name="active" class="onoffswitch-checkbox" id="active">
	          					    <label class="onoffswitch-label" for="active"></label>
	          					</div>
	          				</div>
	          			</div>
	          		</div>
			    </div>
			 </div>
		</div>
   	<?php } ?>

   	<?php if ((isset($email_settings) && 0 == $email_settings->active) || (!isset($email_settings) && !is_admin())) { ?>

			<div class="col-md-12">
				<div class="alert alert-warning">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<h4><b><i class="fa fa-warning"></i> <?php echo _l('email_settings_not_allowed'); ?></b>!</h4>
					<hr class="hr-10"><?php echo _l('contact_to_admin'); ?>
				</div>
			</div>

   	<?php } else { ?>

			<?php echo form_open(admin_url('extended_email/save_staff_email_settings'), ['id'=>'staff_email_info_form']); ?>
		   <div class="col-md-10 col-md-offset-1" id="small-table">
		     	<div class="panel_s">
		        	<div class="panel-body">
	          		<div class="row">
	          			<div class="col-md-12">
	          		    	<h4 style="margin-top:10px;"><?php echo _l('extended_smtp_settings_desc'); ?>
	          				</h4>
	          			</div>
	          		</div>
	          		<div class="clearfix"></div>
	          		<hr class="hr-panel-heading"/>
	          		<div class="row">
	              		<div class="col-md-6">
	              			<?php if (is_admin()) { ?>
	              				<?php echo form_hidden('userid'); ?>
							<?php } else { ?>
	              				<?php echo form_hidden('userid', get_staff_user_id()); ?>
	              			<?php } ?>
	           				<label for="mail_engine"><strong><?php echo _l('mail_engine'); ?></strong></label><br />
		    					<div class="radio radio-inline radio-primary">
									<input type="radio" name="mail_engine" id="phpmailer" value="phpmailer" <?php if (isset($email_settings) && 'phpmailer' == $email_settings->mail_engine) {
									    echo 'checked';
									} ?> checked>
									<label for="phpmailer">PHPMailer</label>
								</div>
								<div class="radio radio-inline radio-primary">
									<input type="radio" name="mail_engine" id="codeigniter" value="codeigniter" <?php if (isset($email_settings) && 'codeigniter' == $email_settings->mail_engine) {
									    echo 'checked';
									} ?> >
									<label for="codeigniter">CodeIgniter</label>
								</div>
							</div>
							<div class="col-md-6">
								<label for="email_protocol"><strong><?php echo _l('email_protocol'); ?></strong></label><br />
								<div class="radio radio-inline radio-primary">
									<input type="radio" name="email_protocol" id="smtp" value="smtp" <?php if (isset($email_settings) && 'smtp' == $email_settings->email_protocol) {
									    echo 'checked';
									} ?> checked>
									<label for="smtp">SMTP</label>
								</div>

								<div class="radio radio-inline radio-primary">
									<input type="radio" name="email_protocol" id="sendmail" value="sendmail" <?php if (isset($email_settings) && 'sendmail' == $email_settings->email_protocol) {
									    echo 'checked';
									} ?> >
									<label for="sendmail">Sendmail</label>
								</div>

								<div class="radio radio-inline radio-primary">
									<input type="radio" name="email_protocol" id="mail" value="mail" <?php if (isset($email_settings) && 'mail' == $email_settings->email_protocol) {
									    echo 'checked';
									} ?>>
									<label for="mail">Mail</label>
								</div>
							</div>
	          		</div>
	          		<div class="row">
	          			<div class="smtp-fields">
	          				<div class="col-md-12">
	          					<div class="form-group mtop15">
	          						<label for="smtp_encryption"><?php echo _l('smtp_encryption'); ?></label><br />
	          						<select name="smtp_encryption" class="selectpicker" data-width="100%">
	          							<option value=""><?php echo _l('smtp_encryption_none'); ?></option>
										<option value="ssl" <?php if (isset($email_settings) && 'ssl' == $email_settings->smtp_encryption) {
										    echo 'selected';
										} ?> >SSL</option>
										<option value="tls" <?php if (isset($email_settings) && 'tls' == $email_settings->smtp_encryption) {
										    echo 'selected';
										} ?> >TLS</option>
	          						</select>
	          					</div>
	          				</div>
	          				<div class="col-md-12">
	          					<div class="form-group">
	        					<?php echo render_input('smtp_host', _l('settings_email_host'), $email_settings->smtp_host ?? ''); ?>
	          					</div>
	          				</div>
	          				<div class="col-md-12">
	          					<div class="form-group">
	        					<?php echo render_input('smtp_port', _l('settings_email_port'), $email_settings->smtp_port ?? ''); ?>
	          					</div>
	          				</div>
	          			</div>
	          			<div class="col-md-12">
	          				<div class="form-group">
	        					<?php echo render_input('email', _l('settings_email'), $email_settings->email ?? ''); ?>
	          				</div>
	          			</div>
	          			<div class="smtp-fields">
	          				<div class="col-md-12">
	          					<i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('smtp_username_help'); ?>"></i>
								<div class="form-group">
	        					<?php echo render_input('smtp_username', _l('smtp_username'), $email_settings->smtp_username ?? ''); ?>
								</div>
	          				</div>
	          				<div class="col-md-12">
	          					<div class="form-group">

	          					<?php if (isset($email_settings)) {
	          					    $smtp_password = $this->encryption->decrypt($email_settings->smtp_password);
	          					}
   	    ?>

	        						<?php echo render_input('smtp_password', _l('settings_email_password'), $smtp_password ?? '', 'password', ['autocomplete' => 'off']); ?>
	          					</div>
	          				</div>
	          			</div>
	          			<div class="col-md-12">
	          				<div class="form-group">
	        					<?php echo render_input('email_charset', _l('settings_email_charset'), $email_settings->email_charset ?? 'utf-8'); ?>
	          				</div>
	          			</div>
	          			<div class="clearfix"></div>
	          			<hr/>
	          			<div class="col-md-12">
	          				<h4><?php echo _l('settings_send_test_email_heading'); ?></h4>
	          				<p class="text-muted"><?php echo _l('settings_send_test_email_subheading'); ?></p>
	          				<div class="form-group">
	          					<div class="input-group">
	        							<?php echo render_input('test_email'); ?>
	          						<div class="input-group-btn">
	          							<button type="button" class="btn btn-default test_email p7">Test</button>
	          						</div>
	          					</div>
	          				</div>
	          			</div>
	          			<div class="clearfix"></div>
							<hr/>
	          		</div>
		        </div>
		     </div>
		  </div>
	      <div class="btn-bottom-toolbar text-right btn-toolbar-container-out">
	         <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
	      </div>
	      <?php echo form_close(); ?>

   	<?php } ?>

   </div>
   <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
</body>
</html>

<script type="text/javascript">

	$(document).ready(function() {

		/*
		* fill email settings in form field
		* only for admin
		*/
		$(document).on('change', '#staff', function(event) {
			event.preventDefault();
			$('input[name="userid"]').val($(this).val());
			var staffid = { staff_id :$(this).val()};
			$.ajax({
	           type: "post",
	           url: admin_url + "extended_email/get_email_settings",
	           data: staffid,
	           dataType: "json",
	           success: function (response) {
	     			if(response)
	     			{
	     				$.each(response, function(key, value) {
	     					$(':text[name ='+key+']').val(value);
	     					$(':password[name ='+key+']').val(value);
	     				});

	     				$(":radio[value='" + response.mail_engine + "']").prop('checked', true);
	     				$(":radio[value='" + response.email_protocol + "']").prop('checked', true);

	     				if (response.active==1) {
	     					$(":checkbox[name='active']").prop('checked',true);
	     				}

	     				$('select[name="smtp_encryption"]').val(response.smtp_encryption);
	     				$('.selectpicker').selectpicker('refresh');
	     			}
	           }
	        });
		});

		/*
		* change active status using ajax
		*/
		$(document).on('change', '#active', function(event) {
			event.preventDefault();
			var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
			    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
			var staffid = $('input[name="userid"]').val();

			var active = 0;
			if ($(this).is(':checked')) {
				active = 1;
			}
			var dataString = { [csrfName]: csrfHash, "staffid" : staffid, "active" : active};

			if (staffid != null && staffid != 0) {
				$.ajax({
					url: '<?php echo admin_url('extended_email/change_active_status'); ?>',
					type: 'POST',
					dataType: 'json',
					data: dataString,
				})
				.done(function(response) {
					if (response.success==true) {
						alert_float("success",response.message);
					}
				})
			}

		});

		/*
		* this event for send test mail
		*/
		$(document).on('click', '.test_email', function(event) {
			event.preventDefault();

			$.ajax({
				url: '<?php echo admin_url('extended_email/sent_smtp_test_email'); ?>',
				type: 'POST',
				dataType: 'json',
				data : $('#staff_email_info_form').serialize(),
			})
			.done(function(response) {
				alert_float(response.status,response.message);
			})

		});

	});
</script>
