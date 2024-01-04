 <div class="row">
     		<div class="col-md-3"> 
     	<?php if(has_permission('team_password','','create') || is_admin()){ ?>		
		    <a href="javascript:void(0)" onclick="add_share();" class="btn btn-info pull-left">
		        <?php echo _l('add'); ?>
		    </a>
		<?php } ?>
		    <div class="clearfix"></div><br>
		 </div>
		<div class="clearfix"></div>
		<hr class="hr-panel-heading" />
		<div class="clearfix"></div>
		<div class="col-md-12">
		<table class="table table-share scroll-responsive">
		      <thead>
		        <th><?php echo _l('id'); ?></th>
			    <th><?php echo html_entity_decode(_l('client')); ?></th>
			    <th><?php echo html_entity_decode(_l('date_create')); ?></th>
			    <th><?php echo html_entity_decode(_l('effective_time')); ?></th>
			    <th><?php echo html_entity_decode(_l('options')); ?></th>
		      </thead>
		      <tbody></tbody>
		      <tfoot>
		         <td></td>
		         <td></td>
		         <td></td>
		         <td></td>		        
		         <td></td>		        
		      </tfoot>
		</table>
		</div>
     </div>
 <div class="modal fade" id="share" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">
	                    <span class="add-title"><?php echo _l('add_share'); ?></span>
	                    <span class="update-title"><?php echo _l('update_share'); ?></span>
	                </h4>
	            </div>
	        <?php echo form_open(admin_url('team_password/add_share'),array('id'=>'share')); ?>	            
	            <div class="modal-body">
			        <div class="row">
			        	<div class="col-md-12">
	                			<div class="checkbox">							
						              <input type="checkbox" class="capability" name="not_in_the_system" onchange="open_frame(this);" value="on">
						              <label><?php echo _l('not_in_the_system'); ?></label>
						        </div>
	                    </div>
	                    <div class="col-md-12 client_fr">
	                    	<input type="hidden" name="id">
	                    	<input type="hidden" name="share_id" value="<?php echo html_entity_decode($id);  ?>">
	                    	<input type="hidden" name="type" value="bank_account">
	                    	<input type="hidden" name="view" value="view_bank_account">
		                    <div class="form-group">
	                            <label for="creator" class="control-label"><?php echo _l('client'); ?></label>
	                            <select name="client" class="selectpicker" id="client" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
	                              <option value="" ></option>
	                                  <?php foreach($contact as $s){ ?>
	                                    <option value="<?php echo html_entity_decode($s['email']); ?>" ><?php echo get_company_name(get_user_id_by_contact_id($s['id'])).' - '.html_entity_decode($s['lastname']).' '.html_entity_decode($s['firstname']); ?></option>
	                               <?php } ?>
	                              </select> 
	                        </div> 
		                </div>
		                <div class="col-md-12 email_fr hide">
		                	<?php echo render_input('email','email'); ?>
		                </div>
		                <div class="col-md-12">
		                	<?php echo render_datetime_input('effective_time','effective_time','',array('required' => true)); ?>
		                </div>
		                <div class="col-md-12">
	                    	<label for="creator" class="control-label"><?php echo html_entity_decode(_l('permission')); ?></label>
	                    	<div class="col-md-12">
	                			<div class="checkbox">							
						              <input type="checkbox" class="capability" name="read" value="on">
						              <label><?php echo html_entity_decode(_l('read')); ?></label>
						        </div>
						        <div class="checkbox">							
						              <input type="checkbox" class="capability" name="write" value="on">
						              <label><?php echo html_entity_decode(_l('write')); ?></label>
						        </div>
						    </div>
	                    </div>
	                    <div class="clearfix"></div>
		            </div>
	            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
	            <?php echo form_close(); ?>	                
	          </div>
	        </div>
	    </div>