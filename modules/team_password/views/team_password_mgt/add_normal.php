<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 

  $id = '';
  $name = '';
  $url = '';
  $user_name = '';
  $notice = '';
  $password = '';
  $custom_field = [];
  $enable_log = '';
  $mgt_id ='';
  if(isset($normal)){
    $id = $normal->id;
    $name = $normal->name;
    $url = $normal->url;
    $user_name = $normal->user_name;
    $notice = $normal->notice;
    $password = AES_256_Decrypt($normal->password);
    $custom_field = json_decode($normal->custom_field);
    $enable_log = $normal->enable_log;
    $mgt_id = $normal->mgt_id;
    $relate_id = explode(',', $normal->relate_id);
  }
 ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
	 <div class="clearfix"></div><br>
	 <div class="col-md-12">
	 	<h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo html_entity_decode($title); ?></h4>
	 </div>

		<div class="clearfix"></div>
		<hr class="hr-panel-heading" />
		<div class="clearfix"></div>
	    <?php echo form_open_multipart(admin_url('team_password/add_normal'),array('id'=>'form_category_management')); ?>	            
        <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
                <?php echo render_input('name','name',$name,'',array('required'=>true)); ?>
            </div>
            <div class="col-md-12">
            <?php 
              echo render_select('mgt_id',$category,array('id','category_name'),'category_managements',$mgt_id,array('required'=>true));
            ?>
            </div>
            <div class="col-md-12">
                <?php echo render_input('url','url',$url); ?>
                <?php echo render_input('user_name','user_name',$user_name,'',array('required'=>true)); ?>
                <?php echo render_textarea('notice','notice',$notice); ?>
            </div>

            <div class="col-md-6">
                <label for="relate_to"><?php echo _l('relate_to'); ?></label>
                <select name="relate_to" id="relate_to" class="selectpicker" data-live-search="true" data-width="100%" onchange="relate_to_change(this); return false;" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                    <option value=""></option>
                    <option value="contract" <?php if(isset($normal) && $normal->relate_to == 'contract'){ echo 'selected'; }elseif(!isset($normal)){ echo 'selected';} ?>><?php echo _l('contract'); ?></option>
                    <option value="project" <?php if(isset($normal) && $normal->relate_to == 'project'){ echo 'selected'; } ?>><?php echo _l('project'); ?></option>
                </select>
                <br><br>
            </div>

            <div class="col-md-6 hide" id="relate_contract">
                <label for="relate_id_contract"><?php echo _l('contract'); ?></label>
                <select name="relate_id[]" id="relate_id_contract" class="selectpicker" data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >

                    <?php foreach($contracts as $ct){ ?>
                    <option value="<?php echo html_entity_decode($ct['id']); ?>" data-subtext="<?php echo get_company_name($ct['client']); ?>" <?php if(isset($normal) && $normal->relate_to == 'contract' && in_array($ct['id'], $relate_id)){ echo 'selected'; } ?>><?php echo html_entity_decode($ct['subject']); ?></option>
                    <?php } ?>
                </select>
                <br><br>
            </div>

            <div class="col-md-6 hide" id="relate_project">
                <label for="relate_id_project"><?php echo _l('projects'); ?></label>
                <select name="relate_id_project[]" id="relate_id_project" class="selectpicker" data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                    
                    <?php foreach($projects as $ct){ ?>
                    <option value="<?php echo html_entity_decode($ct['id']); ?>" data-subtext="<?php echo get_company_name($ct['clientid']); ?>" <?php if(isset($normal) && $normal->relate_to == 'project' && in_array($ct['id'], $relate_id)) { echo 'selected'; } ?>><?php echo html_entity_decode($ct['name']); ?></option>
                    <?php } ?>
                </select>
                <br><br>
            </div>

        	<div class="col-md-12">
        	    <div class="form-group">
        	         <label for="gst"><?php echo _l('password'); ?></label>					 	
					    <div class="input-group">
					    	<a href="#" class="input-group-addon view_passwords"><i class="fa fa-eye"></i></a>
					        <input type="password" class="form-control" name="password" required value="<?php echo html_entity_decode($password); ?>">
					        <a href="#" class="input-group-addon" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo html_entity_decode(_l('generate')) ?>

					        </a>
					        <div class="dropdown-menu generate-padding">
								  <div class="content">
						    		  <input type="checkbox" id="uppercase" value="uppercase">
									  <label for="uppercase"><?php echo html_entity_decode(_l('uppercase')); ?></label><br>
									  <input type="checkbox" id="characters" value="characters" checked="true">
									  <label for="characters"><?php echo html_entity_decode(_l('characters')); ?></label><br>
									  <input type="checkbox" id="numbers" value="numbers" checked="true">
									  <label for="numbers"><?php echo html_entity_decode(_l('numbers')); ?></label><br>
									  <input type="checkbox" id="special_characters" value="special_characters">
									  <label for="special_characters"><?php echo html_entity_decode(_l('special_characters')); ?></label>
									  <div class="row px-2 ">
									  <form class="range-field">
									    <input id="length" class="border-0 w-100" type="range" min="8" max="100" value="11" />
									  </form>
									  <span class="value_length"></span>
									</div>
									  <div class="dropdown-divider"></div>
									  <div class="d-flex justify-content-center">
									  	<button type="button" class="btn btn-info p-1 px-2 btn-password" onclick="generate_password();"><?php echo html_entity_decode(_l('create_new_password')); ?></button>
									  </div>
								  </div>
								</div>
					    </div>
				</div>
			</div>
            <div class="col-md-12">
              <div class="attachments">
                <div class="attachment">
                  <div class="mbot15">
                    <div class="form-group">
                      <label for="attachment" class="control-label"><?php echo _l('ticket_add_attachments'); ?></label>
                      <div class="input-group">
                        <input type="file" extension="<?php echo str_replace('.','',get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                        <span class="input-group-btn">
                          <button class="btn btn-success add_more_attachments p8-half" data-max="10" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group" app-field-wrapper="icon">
                  <label class="control-label"><?php echo _l('custom_fields'); ?></label>
                  <div class="input-group">
                  	<button type="button" onclick="open_fields();"><i class="fa fa-plus"></i>&nbsp;<?php echo html_entity_decode(_l('add_customfields')); ?></button>
                  </div>
              </div>
              <div id="add_field">
                <?php foreach ($custom_field as $key => $tag) { ?>
                       &nbsp;<span class="btn btn-default ptop-10 tag">
                       <label  name="field_name[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->name); ?></label>&nbsp; - &nbsp;<label  name="field_value[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->value); ?></label>&nbsp;
                       <input type="hidden" name="field_name[<?php echo html_entity_decode($key); ?>]" value="<?php echo html_entity_decode($tag->name); ?>">
                       <input type="hidden" name="field_value[<?php echo html_entity_decode($key); ?>]" value="<?php echo html_entity_decode($tag->value); ?>">
                       <label class="exit_tag" onclick="remove_field(this);" >&#10008;</label>
                       </span>&nbsp;
                <?php } ?>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">              
                    <input type="checkbox" <?php if($enable_log == 'on' || (!isset($normal))){ echo 'checked'; } ?> class="capability" name="enable_log" value="on">
                    <label><?php echo html_entity_decode(_l('enable_log')); ?></label>
              </div>
        	</div>


        </div>


        <div class="modal-footer">
            <a href="<?php echo admin_url('team_password/team_password_mgt?cate=all&type=normal'); ?>" class="btn btn-default" ><?php echo _l('close'); ?></a>
            <button type="submit" class="btn btn-info"><?php echo html_entity_decode(_l('submit')); ?></button>
        </div>
        <?php echo form_close(); ?>
	</div>
  </div>
 </div>
</div>



<div class="modal fade" id="custom_fields" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('add_custom_field'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
	        <div class="row">
               <div class="col-md-12"><?php echo render_input('field_name','field_name','','',array('required' => true)); ?></div>
               <div class="col-md-12"><?php echo render_input('field_value','field_value','','',array('required' => true)); ?></div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="create_customfield();" data-dismiss="modal"><?php echo  html_entity_decode(_l('save')); ?></button>
            </div>
          </div>
    </div>
</div>


<?php init_tail(); ?>
</body>
</html>

