 <div class="row">
     		<div class="col-md-3"> 
     		<?php if( (has_permission('team_password','','create') || is_admin()) && $cate != 'all' ){ ?>
			    <a href="javascript:void(0)" onclick="add_permission();" class="btn btn-info pull-left">
			        <?php echo _l('add'); ?>
			    </a>
			<?php } ?>
			<input type="hidden" name="cate" value="<?php echo html_entity_decode($cate); ?>">
		    <div class="clearfix"></div><br>
		 </div>
		<div class="clearfix"></div>
		<hr class="hr-panel-heading" />
		<div class="clearfix"></div>
		<div class="col-md-12">
		<table class="table table-permission scroll-responsive">
		      <thead>

			    <th><?php echo _l('staff'); ?></th>
			    <th><?php echo _l('name'); ?></th>
			    <th><?php echo _l('type'); ?></th>
			    <th><?php echo _l('category'); ?></th>
			    <th><?php echo _l('read'); ?></th>
			    <th><?php echo _l('write'); ?></th>
			    <th><?php echo _l('options'); ?></th>
		      </thead>
		      <tbody></tbody>
		      <tfoot>
		         <td></td>
		         <td></td>
		         <td></td>
		         <td></td>		        
		         <td></td>	
		         <td></td>		        
		      </tfoot>
		</table>
		</div>
     </div>
