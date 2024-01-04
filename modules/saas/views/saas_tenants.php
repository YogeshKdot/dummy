<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style>
	.client-padding-10 {
		padding: 10px !important;
	}
</style>

<?php

$client_plan = getClientPlan(get_client()->userid);

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
	                    	<div class="form-group" app-field-wrapper="saas_plan">
	                    		<label for="saas_plan" class="control-label">
                    			<small class="req text-danger">* </small>Select SaaS Plan</label>
                    			<div class="dropdown bootstrap-select bs3" style="width: 100%;">
                    				<select id="saas_plan" name="saas_plan" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                    					<option value=""></option>
                    					<?php foreach (listChangeSaaSPlans($client_plan->userid) as $key => $value): ?>
                    						<option value="<?php echo $value['id'] ?>"><?php echo $value['plan_name'] ?></option>
                    					<?php endforeach ?>
                    				</select>
                    			</div>
	                    	</div>
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
        $planDetails    	= json_decode($client_plan->plan_details_json, true);

	    $planExpiryDate      = getPlanExpiryDate($client_plan->trial_start_time, $client_plan->trial_days);
	    $trialDiff           = getRemainingDays($planExpiryDate);
	    $daysCount           = abs($trialDiff);
	    $daysLabel           = 'remaining_days';

	    if ($trialDiff > 0) {
	        $daysLabel = 'passed_days';
	    }
	    
		if($client_plan->is_invoiced && !empty($client_plan->invoices)){
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

		$defined_limitation  = get_limitations($client_plan->tenants_name);
    ?>

	<div class="alert alert-success">
		<?php echo _l('your_selected_plan'); ?>:<b> <?php echo $planDetails['plan_name']; ?></b>
		<a href="#" data-target="#change_plan_modal" data-toggle="modal" class="pull-right"><?php echo _l('change_saas_plan'); ?></a >
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel_s">
				<div class="panel-body client-padding-10">
					<div class="tw-flex tw-justify-between tw-items-center tw-p-1.5">
			            <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse">
			            	<span class="tw-text-neutral-700"><?php echo _l('plan_details'); ?></span>
			            </p>
			        </div>
			        <hr class="-tw-mx-3 tw-mt-2 tw-mb-6">
					<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-3 sm:tw-gap-5">
						<?php
                            foreach ($limitations as $key => $value) {
                            	if ((int)$defined_limitation[$key] < 0) {
									$defined_limitation[$key] = _l('unlimited');
								}
                                echo '<div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
									<div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
										<dt class="tw-font-medium text-success">'. _l('total', $value['label']) .'</dt>
										<dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
											<div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">'.total_rows(db_prefix().$value['dbTable']).'/'.($defined_limitation[$key] ?? 0).'</div>
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


	<?php switchDatabase(); ?>

	<?php if (!getTenantDbNameByClientID(get_client()->userid)) { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger">
					<strong><?= _l('saas_error') ?></strong> <?php echo _l('tenant_db_warning'); ?>
				</div>
			</div>
		</div>
	<?php exit;
	} ?>

	<div class="row">
		<div class="col-md-12">
			<div class="panel_s">
			    <div class="panel-body client-padding-10">
			        <div class="tw-flex tw-justify-between tw-items-center tw-p-1.5">
			            <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse">
			            	<span class="tw-text-neutral-700"><?php echo _l('subscription_information'); ?></span>
			            </p>
			        </div>
			        <hr class="-tw-mx-3 tw-mt-2 tw-mb-6">
			        <div class="row">
			        	<div class="col-md-8">
							<table class="table no-margin project-overview-table" style="font-size: 14px;">
								<tbody>
									<tr class="project-overview-start-date">
										<td class="bold"><?php echo _l('company_name'); ?></td>
										<td><?php echo $client_plan->tenants_name; ?></td>
									</tr>
									<tr class="project-overview-date-created">
										<td class="bold"><?php echo _l('company_domain'); ?></td>
										<td> <a href="<?php echo parse_url(base_url())['scheme'].'://'.$client_plan->tenants_name.'.'.parse_url(base_url())['host'].'/admin'; ?>" target="_blank">
												<i class="fa fa-external-link"></i> <?php echo $client_plan->tenants_name.'.'.parse_url(base_url())['host']; ?>
											</a></td>
									</tr>
									<tr class="project-overview-deadline">
										<td class="bold"><?php echo _l('selected_plan'); ?></td>
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
								</tbody>
							</table>
						</div>
						<div class="col-md-4">
							<div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
								<div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
									<dt class="tw-font-medium text-success"><?php echo _l($daysLabel); ?></dt>
									<dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
										<div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600"><?php echo $daysCount; ?> days</div>
									</dd>
								</div>
							</div>
						</div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
<?php } ?>

<script>
	$(document).ready(function() {
		$('body').on('show.bs.modal', '#change_plan_modal', function(event) {
			$('#change-plan-form')[0].reset();
			$('.selectpicker').selectpicker('refresh');
		});

		function changeSaasPlan(form) {
			$.ajax({
				url: `${site_url}saas/saas_tenants/changeSaasPlan`,
				type: 'POST',
				dataType: 'json',
				data: $(form).serialize(),
			})
			.done(function(res) {
				var type = (res.status) ? 'success' : 'danger';
				alert_float(type,res.message);
				$('#change_plan_modal').modal('hide');
				setTimeout(function () {
					location.reload();
		        }, 2000);
			});
		}

		appValidateForm($('#change-plan-form'), {
			saas_plan: "required"
		}, changeSaasPlan);
	});
</script>