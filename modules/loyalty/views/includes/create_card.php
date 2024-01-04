<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
    <div class="row">
  		<div class="col-md-12">
	    <div class="panel_s ">
		    <div class="panel-body">
		    
						
			<?php echo form_open_multipart( admin_url('loyalty/card_config'),array('id'=>'card-setting-form')); ?>
			<?php echo form_hidden('card_id', (isset($card) ? $card->id : '')); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<?php $name = (isset($card) ? $card->name : '');
						echo render_input('name','card_name',$name,'text',array('required' => 'true')); ?>
					</div>
					<div class="col-md-12 ">
                       	<div class="container col-md-6 pad_0">
                          <div class="picture-container pull-left">
                              <div class="picture pull-left">
                                  <img src="<?php if(isset($card) && isset($card->card_picture)){ echo site_url(LOYALTY_PATH . 'card_picture/'.$card->card_picture->rel_id.'/'.$card->card_picture->file_name);  }else{ echo site_url(LOYALTY_PATH . 'nul_image.jpg'); } ?>" class="picture-src" id="wizardPicturePreview" title="">
                                  <input name="card_picture" type="file" id="wizard-picture" <?php if(!isset($card)){ echo 'required="true"'; } ?> accept=".png, .jpg, .jpeg" class="">
                              </div>
                              <h5 class=""><?php echo _l('choose_picture'); ?></h5>
                          </div>
                      	</div>

                      	<div class="col-md-6">
                      		<p class="p_style"><?php echo _l('information_displayed'); ?></p>
              				<hr class="hr_style"/>

              				<div class="col-md-6">
	              				<div class="form-group">
								  <div class="checkbox checkbox-primary">
								    <input type="checkbox" id="subject_card" name="subject_card" <?php if(isset($card) && $card->subject_card == 1){ echo 'checked';} ?> value="1" >
								    <label for="subject_card"><?php echo _l('subject_card'); ?>
								    </label>
								  </div>
								</div>

								<div class="form-group">
								  <div class="checkbox checkbox-primary">
								    <input type="checkbox" id="client_name" name="client_name" <?php if(isset($card) && $card->client_name == 1){ echo 'checked';} ?>  value="1" >
								    <label for="client_name"><?php echo _l('client_name'); ?>
								    </label>
								  </div>
								</div>

								<div class="form-group">
								  <div class="checkbox checkbox-primary">
								    <input type="checkbox" id="membership" name="membership" <?php if(isset($card) && $card->membership == 1){ echo 'checked';} ?> value="1" >
								    <label for="membership"><?php echo _l('membership'); ?>
								    </label>
								  </div>
								</div>
								
							</div>

							<div class="col-md-6">
								<div class="form-group">
								  <div class="checkbox checkbox-primary">
								    <input type="checkbox" id="company_name" name="company_name" <?php if(isset($card) && $card->company_name == 1){ echo 'checked';} ?> value="1" >
								    <label for="company_name"><?php echo _l('company_name'); ?>
								    </label>
								  </div>
								</div>
								<div class="form-group">
								  <div class="checkbox checkbox-primary">
								    <input type="checkbox" id="member_since" name="member_since" <?php if(isset($card) && $card->member_since == 1){ echo 'checked';} ?> value="1" >
								    <label for="member_since"><?php echo _l('member_since'); ?>
								    </label>
								  </div>
								</div>

								<div class="form-group">
								  <div class="checkbox checkbox-primary">
								    <input type="checkbox" onchange="custom_field_change(this); return false;" id="custom_field" name="custom_field"  <?php if(isset($card) && $card->custom_field == 1){ echo 'checked';} ?> value="1">
								    <label for="custom_field"><?php echo _l('custom_field'); ?>
								    </label>
								  </div>
								</div>
                              
							</div>

							<div class="col-md-12 hide" id="custom_field_content_div">
								<?php  $custom_field_content = (isset($card) ? $card->custom_field_content : '');
								 echo render_input('custom_field_content','custom_field_content',$custom_field_content); ?>

							</div>

							<div class="col-md-12">
								<?php $text_color = (isset($card) ? $card->text_color : '');
								 echo render_color_picker('text_color',  _l('text_color'), $text_color); ?>

							</div>
							

                      	</div>
                    </div>
				</div>
			</div>
			<div class="modal-footer">
		
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				<?php echo form_close(); ?>
			</div>
			
		    </div>
		</div>
	</div>
	</div>
 </div>
</div>
<?php init_tail(); ?>
</body>
</html>