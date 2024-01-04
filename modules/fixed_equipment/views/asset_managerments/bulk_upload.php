<?php defined('BASEPATH') or exit('No direct script access allowed'); 
?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body backdrop">
						<div id ="dowload_file_sample">
						</div>
						<hr>   
						<?php 
							$this->load->view('includes/guide/'.$type);
						 ?>
						<hr>
						<div class="row">
							<div class="col-md-4">
								<?php echo form_open_multipart(admin_url('accounting/import_xlsx_banking'),array('id'=>'import_form')) ;?>
								<?php echo form_hidden('type', $type); ?>
								<?php echo render_input('file_csv','choose_excel_file','','file'); ?> 

								<div class="form-group">
									<button id="uploadfile" type="button" class="btn btn-info import" onclick="return uploadfilecsv();" ><?php echo _l('import'); ?></button>
								</div>
								<?php echo form_close(); ?>
							</div>
							<div class="col-md-8">
								<div id="file_upload_response"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<!-- box loading -->
<div id="box-loading">
	<img src="<?php echo site_url('modules/fixed_equipment/assets/images/loading.gif'); ?>" alt="">
</div>
<?php init_tail(); ?>

<?php require 'modules/fixed_equipment/assets/js/bulk_upload/bulk_upload_js.php';?>
</body>
</html>
