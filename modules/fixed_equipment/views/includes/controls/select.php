<div class="row">
	<div class="col-md-12">
		<?php 
		$option_array = [];
		if(isset($option)){
			$option_array = json_decode($option);
		}

		$select_s = '';
		if(isset($select)){
			$select_s = $select;
		}
		$title_s = '';
		if(isset($title)){
			$title_s = $title;
		}

		$id_s = '';
		if(isset($id)){
			$id_s = $id;
		}
		$required_s = '';
		if(isset($required)){
			$required_s = $required;
		}
		
		?>
		<div class="form-group" app-field-wrapper="customfield[<?php echo html_entity_decode($id_s) ?>]">
			<label for="customfield[<?php echo html_entity_decode($id_s) ?>]" class="control-label">
				<?php 		
				if($required_s == 1){ ?>
					<small class="req text-danger">* </small>
				<?php }
				echo html_entity_decode($title_s);
				?>
			</label>
			<div class="dropdown bootstrap-select w100">
				<select id="customfield[<?php echo html_entity_decode($id_s) ?>]" name="customfield[<?php echo html_entity_decode($id_s) ?>]" class="selectpicker" data-width="100%" data-none-selected-text="Non selected" data-live-search="true" tabindex="-98" <?php echo (($required_s == 1) ? 'required' : '') ?>>
					<option value=""></option>
					<?php
					foreach ($option_array as $key => $value) {  	?>
						<option value="<?php echo html_entity_decode($value); ?>" <?php echo (($select_s != '' && $select_s == $value) ? 'selected' : '') ?>><?php echo html_entity_decode($value); ?></option>
					<?php }	?>
				</select>
			</div>
		</div>


	</div>
</div>