<div class="col-md-12">
	<h4 class="bold well email-template-heading">
		<?php echo _l('saas_tenant'); ?>
		<?php if ($hasPermissionEdit) { ?>
			<a href="<?php echo admin_url('emails/disable_by_type/tenants'); ?>" class="pull-right mleft5 mright25"><small><?php echo _l('disable_all'); ?></small></a>
			<a href="<?php echo admin_url('emails/enable_by_type/tenants'); ?>" class="pull-right"><small><?php echo _l('enable_all'); ?></small></a>
		<?php } ?>

	</h4>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th><?php echo _l('email_templates_table_heading_name'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tenants as $tenants_template) { ?>
					<tr>
						<td class="<?php if (0 == $tenants_template['active']) {
						    echo 'text-throught';
						} ?>">
							<a href="<?php echo admin_url('emails/email_template/'.$tenants_template['emailtemplateid']); ?>"><?php echo $tenants_template['name']; ?></a>
							<?php if (ENVIRONMENT !== 'production') { ?>
								<br/><small><?php echo $tenants_template['slug']; ?></small>
							<?php } ?>
							<?php if ($hasPermissionEdit) { ?>
								<a href="<?php echo admin_url('emails/'.('1' == $tenants_template['active'] ? 'disable/' : 'enable/').$tenants_template['emailtemplateid']); ?>" class="pull-right"><small><?php echo _l(1 == $tenants_template['active'] ? 'disable' : 'enable'); ?></small></a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>