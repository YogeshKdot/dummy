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
		<label for="customfield[<?php echo html_entity_decode($id_s) ?>]" class="control-label">
			<?php 		
			if($required_s == 1){ ?>
				<small class="req text-danger">* </small>
			<?php }
			echo html_entity_decode($title_s);
			?>
		</label>

		<div class="w100">
			<?php
			foreach ($option_array as $key => $value) {  	?>
				<input type="radio" name="customfield[<?php echo html_entity_decode($id_s) ?>]" id="customfield[<?php echo html_entity_decode($id_s) ?>][<?php echo html_entity_decode($key) ?>]" value="<?php echo html_entity_decode($value); ?>" <?php echo (($required_s == 1) ? 'required' : '') ?> <?php echo (($value == $select_s) ? 'checked' : '') ?>>
				<label for="customfield[<?php echo html_entity_decode($id_s) ?>][<?php echo html_entity_decode($key) ?>]"><?php echo html_entity_decode($value); ?></label>
				<br>
			<?php }	?>
		</div>
		<br>



	</div>
</div>