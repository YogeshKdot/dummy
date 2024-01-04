 <div class="row">
     		<div class="col-md-3">
     		<?php if(has_permission('team_password','','create') || is_admin()){ ?> 
		    <a href="javascript:void(0)" onclick="add_permission();" class="btn btn-info pull-left">
		        <?php echo _l('add'); ?>
		    </a>
		<?php } ?>
		    <div class="clearfix"></div><br>
		 </div>
		<div class="clearfix"></div>
		<hr class="hr-panel-heading" />
		<div class="clearfix"></div>
		<div class="col-md-12">
		<table class="table table-permission scroll-responsive">
		      <thead>
		        <th><?php echo _l('id'); ?></th>
			    <th><?php echo html_entity_decode(_l('staff')); ?></th>
			    <th><?php echo html_entity_decode(_l('read')); ?></th>
			    <th><?php echo html_entity_decode(_l('write')); ?></th>
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
 <div class="modal fade" id="permission" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">
	                    <span class="add-title"><?php echo _l('add_permission'); ?></span>
	                    <span class="update-title"><?php echo _l('update_permission'); ?></span>
	                </h4>
	            </div>
	        <?php echo form_open(admin_url('team_password/add_permission'),array('id'=>'permission')); ?>	            
	            <div class="modal-body">
			        <div class="row">
	                    <div class="col-md-12">
	                    	<input type="hidden" name="obj_id" value="<?php echo html_entity_decode($id);  ?>">
	                    	<input type="hidden" name="type" value="software_license">
	                    	<input type="hidden" name="view_name" value="view_software_license">
		                    <div class="form-group">
	                            <label for="creator" class="control-label"><?php echo html_entity_decode(_l('staff')); ?></label>
	                            <select name="staff[]" class="selectpicker" id="patient_id" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" multiple required > 
	                              <option value="" ></option>
	                                  <?php foreach($staffs as $s){ ?>
	                                    <option value="<?php echo html_entity_decode($s['staffid']); ?>" ><?php echo html_entity_decode($s['lastname']).' '.html_entity_decode($s['firstname']); ?></option>
	                               <?php } ?>
	                              </select> 
	                        </div> 
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
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_entity_decode(_l('close')); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
	            <?php echo form_close(); ?>	                
	          </div>
	        </div>
	    </div>