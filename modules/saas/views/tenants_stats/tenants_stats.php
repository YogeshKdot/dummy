<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php
	$client_plan = getClientPlan($client->userid);
	get_instance()->load->config('saas/features_limitation_config');
	$limitations = config_item('limitations');
?>

<?php if (!empty($client_plan)) { ?>
	<div class="modal fade" id="change_plan_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php echo _l('change_saas_plan'); ?></h4>
				</div>
				<?php
				$attributes = ['id' => 'change-plan-form'];
				$hidden     = ['clientid' => $client_plan->userid];
				?>
				<?php echo form_open('', $attributes, $hidden); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<?php echo render_select('saas_plan', listChangeSaaSPlans($client_plan->userid), ['id', 'plan_name'], 'select_saas_plan'); ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
					<button group="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

	<?php
		$planDetails    = json_decode($client_plan->plan_details_json, true);

		$planExpiryDate     = getPlanExpiryDate($client_plan->trial_start_time, $client_plan->trial_days);
		$trialDiff          = getRemainingDays($planExpiryDate);
		$daysCount          = abs($trialDiff) . " days";

		$daysLabel          = 'remaining_days';
		if ($trialDiff > 0) {
			$daysLabel = 'passed_days';
		}
		
		if ($client_plan->is_invoiced && !empty($client_plan->invoices)) {
			$invoices = json_decode($client_plan->invoices);
			arsort($invoices);
			$last_invoice = $invoices[array_key_first($invoices)];
			$invoice = $this->invoices_model->get($last_invoice);
			
			if (!$invoice->last_recurring_date) {
				$last_recurring_date = date('Y-m-d', strtotime($invoice->date));
			} else {
				$last_recurring_date = date('Y-m-d', strtotime($invoice->last_recurring_date));
			}
			if ($invoice->custom_recurring == 0) {
				$invoice->recurring_type = 'MONTH';
			}

			$daysCount = date('Y-m-d', strtotime('+' . $invoice->recurring . ' ' . strtoupper($invoice->recurring_type), strtotime($last_recurring_date)));
			$daysLabel = "next_invoice_date";
		}

		$defined_limitation = get_limitations($client_plan->tenants_name);
	?>
	<div class="alert alert-success">
		<?php echo _l('subscribed_to_plan'); ?>:<b> <?php echo $planDetails['plan_name']; ?></b>
		<a href="#" data-target="#change_plan_modal" data-toggle="modal" class="pull-right"><?php echo _l('change_saas_plan'); ?></a>
	</div>
	<div class="">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><?php echo _l('plan_details'); ?></h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in">
							<div class="panel-body">
								<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-3 sm:tw-gap-5">
									<?php
									foreach ($limitations as $key => $value) {
										if ((int)$defined_limitation[$key] < 0) {
											$defined_limitation[$key] = _l('unlimited');
										}
										echo '<div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
										<div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
										<dt class="tw-font-medium text-success">' . _l('total', $value['label']) . '</dt>
										<dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
										<div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">' . total_rows(db_prefix() . $value['dbTable']) . '/' . ($defined_limitation[$key] ?? 0) . '</div>
										</dd>
										</div>
										</div>';
									}
									?>
								</dl>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php switchDatabase(); ?>

	<?php if (!getTenantDbNameByClientID($client->userid)) { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger">
					<strong><?= _l('saas_error') ?></strong> <?php echo _l('tenant_db_warning'); ?>
				</div>
			</div>
		</div>
	<?php exit; } ?>
	<hr />
	<div class="row mtop10">
		<div class="col-md-12">
			<h4 class="no-margin"><?php echo _l('tenant_information'); ?></h4>
			<hr />
		</div>
		<div class="col-md-8">
			<table class="table no-margin project-overview-table" style="font-size: 14px;">
				<tbody>
					<tr class="project-overview-start-date">
						<td class="bold"><?php echo _l('tenants_name'); ?></td>
						<td><?php echo $client_plan->tenants_name; ?></td>
					</tr>
					<tr class="project-overview-date-created">
						<td class="bold"><?php echo _l('tenant_domain'); ?></td>
						<td> <a href="<?php echo parse_url(base_url())['scheme'] . '://' . $client_plan->tenants_name . '.' . parse_url(base_url())['host'] . '/admin'; ?>" target="_blank">
								<i class="fa fa-external-link"></i> <?php echo $client_plan->tenants_name . '.' . parse_url(base_url())['host']; ?>
							</a></td>
					</tr>
					<tr class="project-overview-deadline">
						<td class="bold"><?php echo _l('tenant_plan'); ?></td>
						<td><?php echo $planDetails['plan_name']; ?></td>
					</tr>
					<tr class="project-overview-deadline">
						<td class="bold"><?php echo _l('created_at'); ?></td>
						<td><?php echo time_ago($client_plan->trial_start_time); ?></td>
					</tr>
					<tr class="project-overview-date-finished">
						<td class="bold"><?php echo _l('plan_expiry'); ?></td>
						<td class="text-danger"><?php echo $planExpiryDate; ?></td>
					</tr>
					<tr class="project-overview-date-finished">
						<td class="bold"><?php echo _l('active_tenant'); ?></td>
						<td class="text-danger">
							<div class="onoffswitch" data-toggle="tooltip" data-title="<?php echo _l('active_deactive_tenants'); ?>">
								<input type="checkbox" data-switch-url="<?php echo admin_url() . 'saas/superadmin/change_tenant_status'; ?>" name="onoffswitch" class="onoffswitch-checkbox" id="<?php echo $client_plan->userid; ?>" data-id="<?php echo $client_plan->userid; ?>" <?php echo 1 == $client_plan->is_active ? 'checked' : ''; ?>>
								<label class="onoffswitch-label" for="<?php echo $client_plan->userid; ?>"></label>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
				<div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
					<dt class="tw-font-medium text-success"><?php echo _l($daysLabel); ?></dt>
					<dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
						<div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600"><?php echo $daysCount; ?></div>
					</dd>
				</div>
			</div>
		</div>
	</div>

	<?php if (!empty($client_plan->allowed_modules)): ?>
		<hr />
		<div class="row mtop10">
			<div class="col-md-12">
				<h4 class="no-margin"><?php echo _l('available_add_ons_for_this_tenant'); ?></h4>
				<hr />
				<table class="table no-margin project-overview-table" style="font-size: 14px;">
					<tbody>
							<?php $i = 1; ?>
							<?php foreach (unserialize($client_plan->allowed_modules) as $key => $value): ?>
								<?php $moduleDetails = $this->app_modules->get($key); ?>
								<?php if (!empty($moduleDetails)): ?>
									<tr>
										<td width="1%"><?php echo $i; ?>.</td>
										<td width="99%"><?php echo $this->app_modules->get($key)['headers']['module_name']; ?></td>
									</tr>
								<?php endif ?>
							<?php $i++; ?>
							<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif ?>

	<hr />
	<div class="row">
		<div class="col-md-8">
			<h4 class="no-margin"><?php echo _l('last_activity'); ?></h4>
			<hr />
			<div class="activity-feed">
				<?php foreach (getTenantLastActivity($client->userid) as $activity) {
					foreach ($activity as $key => $value) { ?>
						<div class="feed-item">
							<div class="date"><span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($value->date); ?>">
									<?php echo time_ago($value->date); ?>
								</span>
							</div>
							<div class="text">
								<p class="bold no-mbot"><?php echo $value->description; ?></p>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<div class="col-md-4"></div>
	</div>
<?php } ?>

<?php
	if (empty($client_plan)) {
		get_instance()->load->view('saas/plans/client_plan');
	}
?>