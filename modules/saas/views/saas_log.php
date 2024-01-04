<?php
defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                	<div class="panel-body">
                		<div class="_buttons">
                			<div class="row">
                				<div class="col-md-6">
                					<h4 class="tw-mt-0 tw-font-semibold"><?php echo _l('saas_activity_log'); ?> </h4>
                				</div>
                				<div class="col-md-6">
                					<a href="<?php echo admin_url(SUPERADMIN_MODULE.'/saas_log_details/clear_saas_log'); ?>" class="btn btn-danger pull-right _delete"><?php echo _l('clear_activity_log'); ?></a>
                				</div>
                			</div>
                		</div>
                		<div class="clearfix"></div>
                		<hr class="hr-panel-heading" />
                		<div class="clearfix"></div>
                		<?php render_datatable([
                            _l('description'),
                            _l('date'),
                            _l('staff'),
                        ], 'saas_log_details_table');
?>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    initDataTable('.table-saas_log_details_table', admin_url+"saas/saas_log_details/saas_log_details_table", undefined, undefined,undefined ,[1 , 'desc']);
</script>

