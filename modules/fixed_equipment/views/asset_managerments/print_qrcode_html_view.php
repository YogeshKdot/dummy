<table cellpadding="5">
	<tbody>
		<?php 
		$row_index = 0;
		foreach ($list_id as $key => $id) {
			if($row_index == 0){
				$row_index++;
				?>
				<tr>
				<?php } ?>
				<td width="25%">
					<?php $asset_data = $this->fixed_equipment_model->get_assets($id);
					if($asset_data){
						?>
						<img src="<?php echo fe_get_image_qrcode_pdf($asset_data->id); ?>"> 
						<table cellpadding="5" align="center">
							<tr>
								<td>
									<?php echo (($asset_data->series != null && $asset_data->series != '') ? $asset_data->series.' ' : '').$asset_data->assets_name; ?>									
								</td>
							</tr>
						</table>
					<?php } ?>
				</td>
				<?php if(($key+1) % 4 == 0){
					$row_index = 0;
					?>
				</tr>
			<?php } ?>
		<?php }
		if($row_index != 0){
			for ($i=0; $i < (4-$row_index); $i++) { ?>
				<td></td>
			<?php } ?>
		</tr>
	<?php } ?>
</tbody>
</table>