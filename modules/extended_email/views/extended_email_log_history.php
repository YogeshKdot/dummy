<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="panel_s">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<h4 class="no-margin"><?php echo _l('extended_email_log_history'); ?></h4>
					</div>
					<div class="col-md-8 text-right">
						<button class="btn btn-danger clear_log"><?php echo _l('clear_log'); ?></button>
					</div>
				</div>
				<div class="clearfix"></div>
                <hr class="hr-panel-heading" />
                <div class="row">
                	<div class="col-md-12">
                		<?php render_datatable([
                            _l('staff'),
                            _l('staff_email_changes'),
                            _l('description'),
                            _l('datetime'),
                            ], 'log_history_table', ['table-hover', 'table-condensed']); ?>
                	</div>
                </div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>

<script type="text/javascript">
	initDataTable('.table-log_history_table',admin_url+'extended_email/extended_email_log_history_table',undefined,undefined,undefined,[0,'desc']);
	$(document).ready(function() {
		$(document).on('click','.clear_log',function(){

			if (confirm_delete()) {
				$.ajax({
					url: admin_url + 'extended_email/extended_email_clear_log',
					type: 'POST',
					dataType: 'json',
				})
				.done(function(response) {
					if(response.success){
						$('.table-log_history_table').DataTable().ajax.reload();
						alert_float('success',response.message);
					}
				});
			}

		})
	});
</script>