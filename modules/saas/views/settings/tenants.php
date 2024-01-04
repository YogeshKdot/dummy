<div class="horizontal-scrollable-tabs panel-full-width-tabs">
	<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
	<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
	<div class="horizontal-tabs">
		<ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
			<li role="presentation" class="active">
				<a href="#server_settings" aria-controls="server_settings" role="tab" data-toggle="tab"><?php echo _l('mysql_server_settings'); ?></a>
			</li>
			<li role="presentation">
				<a href="#tenants_settings" aria-controls="tenants_settings" role="tab" data-toggle="tab"><?php echo _l('tenants_settings'); ?></a>
			</li>
			<li role="presentation">
				<a href="#landing_page_settings" aria-controls="landing_page_settings" role="tab" data-toggle="tab"><?php echo _l('landing_page_settings'); ?></a>
			</li>
		</ul>
	</div>
</div>
<div class="tab-content mtop15">
	<!-- Server settings section -->
	<div role="tabpanel" class="tab-pane active" id="server_settings">
		<?php if (!check_server_settings()) { ?>
			<div class="alert alert-warning" font-medium="">
				<?php echo _l('mysql_server_warning'); ?>
			</div>
		<?php } ?>
		<?php if ('' != get_option('mysql_verification_message')) { ?>
			<div class="alert alert-danger" font-medium="">
				<?php echo get_option('mysql_verification_message'); ?>
			</div>
		<?php } ?>
		<div class="clearfix"></div>
		<div class="mysql_server_details">
			<div class="row">
				<div class="form-group col-md-6" app-field-wrapper="settings[mysql_host]">
					<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('mysql_host_tooltip'); ?>"></i>
					<label for="settings[mysql_host]" class="control-label"><?php echo _l('mysql_host'); ?></label>
					<input type="text" id="settings[mysql_host]" name="settings[mysql_host]" class="form-control" value="<?php echo get_option('mysql_host'); ?>">
				</div>
				<div class="form-group col-md-6" app-field-wrapper="settings[mysql_port]">
					<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('mysql_port_tooltip'); ?>"></i>
					<label for="settings[mysql_port]" class="control-label"><?php echo _l('mysql_port'); ?></label>
					<input type="text" id="settings[mysql_port]" name="settings[mysql_port]" class="form-control" value="<?php echo get_option('mysql_port'); ?>">
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-6" app-field-wrapper="settings[mysql_root_username]">
					<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('mysql_root_username_tooltip'); ?>"></i>
					<label for="settings[mysql_root_username]" class="control-label"><?php echo _l('mysql_root_username'); ?></label>
					<input type="" id="settings[mysql_root_username]" name="settings[mysql_root_username]" class="form-control" value="<?php echo get_option('mysql_root_username'); ?>">
				</div>
				<div class="form-group col-md-6" app-field-wrapper="settings[mysql_password]">
					<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('mysql_password_tooltip'); ?>"></i>
					<?php
                    $ps = get_option('mysql_password');
				if (!empty($ps)) {
				    if (false == $this->encryption->decrypt($ps)) {
				        $ps = $ps;
				    } else {
				        $ps = $this->encryption->decrypt($ps);
				    }
				}
				?>
					<label for="settings[mysql_password]" class="control-label"><?php echo _l('mysql_password'); ?></label>
					<input type="password" id="settings[mysql_password]" name="settings[mysql_password]" class="form-control" value="<?php echo $ps; ?>">
				</div>
			</div>
			<div class="pull-right">
				<span class="loader" style="display:none"><i class="fas fa-spinner rotate"></i></span>&nbsp;
				<button class="label label-info" id="checkDbUser"><?php echo _l('verify_mysql_server_details'); ?></button>
			</div>
		</div>
	</div>
	<!-- Tenants settings section -->
	<div role="tabpanel" class="tab-pane" id="tenants_settings">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group" app-field-wrapper="settings[inactive_tenants_limit]">
					<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('inactive_tenants_limit_tooltip'); ?>"></i>
					<label for="settings[inactive_tenants_limit]" class="control-label"><?php echo _l('inactive_tenants_limit'); ?></label>
					<input type="number" id="settings[inactive_tenants_limit]" name="settings[inactive_tenants_limit]" class="form-control" value="<?php echo get_option('inactive_tenants_limit'); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group" app-field-wrapper="settings[trial_period_days]">
					<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('trial_period_tooltip'); ?>"></i>
					<label for="settings[trial_period_days]" class="control-label"><?php echo _l('trial_period_days'); ?></label><input type="number" id="settings[trial_period_days]" name="settings[trial_period_days]" class="form-control" value="<?php echo get_option('trial_period_days'); ?>">
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="row">
			<div class="col-md-4">
				<?php render_yes_no_option('allow_registration', 'allow_tenant_to_register', 'allow_registration_tooltip'); ?>
			</div>
			<div class="col-md-4">
				<?php render_yes_no_option('email_verification_require_after_tenant_register', 'email_verification_require_after_tenant_register', 'email_verification_require_after_tenant_register_tooltip'); ?>
			</div>
			<div class="col-md-4">
				<?php render_yes_no_option('tenants_landing', 'tenants_landing', 'tenants_landing_tooltip'); ?>
			</div>
		</div>
	</div>
	<!-- LANDING PAGE CONTENT SETTINGS -->
	<div role="tabpanel" class="tab-pane" id="landing_page_settings">
		<div class="tw-flex tw-flex-col">
	        <div class="form-group">
	            <div class="proxy row">
	                <div class="col-sm-8">
	                    <?php $value = get_option('perfex_saas_landing_page_url'); ?>
	                    <?= render_input('settings[perfex_saas_landing_page_url]', _l('landing_page_url') . perfex_saas_form_label_hint('landing_page_url_hint'), $value, 'text', ['placeholder' => 'https://mycrm.com/home']); ?>
	                </div>
	                <div class="col-sm-4">
	                    <?php $value = get_option('perfex_saas_landing_page_url_mode'); ?>
	                    <?= render_select('settings[perfex_saas_landing_page_url_mode]', [['key' => 'proxy'], ['key' => 'redirection']], ['key', ['key']], _l('mode') . perfex_saas_form_label_hint('landing_page_url_mode_hint'), empty($value) ? 'proxy' : $value); ?>
	                </div>
	            </div>
	            <div class="tw-mt-3">
	                <hr>
	            </div>
	            <div class="row tw-flex">
	                <div class="col-sm-8">
	                    <?= render_select('settings[perfex_saas_landing_page_theme]', get_landing_pages(), ['file', ['name']], 'select_active_landing_page', get_option('perfex_saas_landing_page_theme')); ?>
	                </div>
	                <div class="col-sm-4 tw-flex tw-items-center">
	                    <a href="<?= admin_url('saas/landing_page_builder/builder'); ?>"
	                        class="btn btn-primary" target="_blank"><?= _l('edit_landing_pages'); ?></a>
	                </div>
	            </div>
	            <div class="tw-mt-2">
	                <hr>
	            </div>
        		<div class="alert alert-danger" font-medium="">
					<?php echo _l('this_feature_remove_in_next_version'); ?>
				</div>
	            <div class="row">
	        		<div class="col-md-3">
	        			<?php render_yes_no_option('saas_default_landing_page', 'saas_default_landing_page', 'saas_default_landing_page'); ?>
	        		</div>
	        	</div>
	        </div>
    	</div>
	</div>
	<!-- LANDING PAGE SETTINGS OVER HERE -->
</div>