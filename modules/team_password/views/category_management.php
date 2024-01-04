<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
	 <div class="clearfix"></div><br>
	 <div class="col-md-12">
	 	<h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo html_entity_decode($title); ?></h4>
	 	<hr>
	 </div>
	     <div class="col-md-3"> 
	     	<?php if(has_permission('team_password','','create') || is_admin()) { ?>
		    <a href="javascript:void(0)" onclick="add(); return false;" class="btn btn-info pull-left">
		        <?php echo _l('add'); ?>
		    </a>
			<?php } ?>
		    <div class="clearfix"></div><br>
		 </div>
		<div class="clearfix"></div>
		<hr class="hr-panel-heading" />
		<div class="clearfix"></div>
		<div class="col-md-12">
		<table class="table table-category_management scroll-responsive">
		      <thead>
		        <th>ID#</th>
			    <th><?php echo _l('category_name'); ?></th>
			    <th><?php echo _l('icon'); ?></th>
			    <th><?php echo _l('parent'); ?></th>
			    <th><?php echo _l('description_tp'); ?></th>
			    <th><?php echo _l('options'); ?></th>
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
  </div>
 </div>
</div>

	<div class="modal fade" id="category_management" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">
	                    <span class="add-title"><?php echo _l('add_category_management'); ?></span>
	                    <span class="update-title hide"><?php echo _l('update_category_management'); ?></span>
	                </h4>
	            </div>
	        <?php echo form_open(admin_url('team_password/category_management'),array('id'=>'form_category_management')); ?>	            
	            <div class="modal-body">
		        <div class="row">
                    <div class="col-md-12">
                    	<input type="hidden" name="id">
                        <?php echo render_input('category_name','category_name'); ?>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group" app-field-wrapper="icon">
		                  <label class="control-label"><?php echo _l('icon'); ?></label>
		                  <div class="input-group">
		                   <input type="text" name="icon" autocomplete="off" class="form-control main-item-icon icon-picker">
		                   <span class="input-group-addon">
		                     <i id="icon"></i>
		                   </span>
		                  </div>
	                  </div>
	                </div>
	                
	                <div class="col-md-12">
                    	<div class="form-group">
                          <label for="parent" class="control-label"><?php echo _l('parent'); ?></label>
                          <select name="parent" class="selectpicker" id="parent" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
                            <option value="" ></option>
                                <?php foreach($cates as $s){ ?>
                                <option value="<?php echo html_entity_decode($s['id']); ?>" ><?php echo html_entity_decode($s['category_name']); ?></option>
                             	<?php } ?>
                           </select> 
                      	</div> 
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-12">
                    	<?php  echo render_textarea('description','description_tp','',array(),array(),'','tinymce'); ?>
                    </div>

                    

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
<?php init_tail(); ?>
</body>
</html>
