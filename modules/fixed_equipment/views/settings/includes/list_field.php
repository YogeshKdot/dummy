<?php
$control = [
	['id' => 'attachment', 'label' => 'Attachment'],
	['id' => 'number_field', 'label' => 'Number Field'],
	['id' => 'check_box', 'label' => 'Check Box'],
	['id' => 'date', 'label' => 'Date'],
	['id' => 'datetime', 'label' => 'Date Time'],
	['id' => 'declaration', 'label' => 'Declaration'],
	['id' => 'hidden_field', 'label' => 'Hidden Field'],
	['id' => 'radio_button', 'label' => 'Radio Button'],
	['id' => 'single_select', 'label' => 'Single Select'],
	['id' => 'multi_select', 'label' => 'Multi Select'],
	['id' => 'department_list_from_master', 'label' => 'Department List From Master'],
	['id' => 'text_area', 'label' => 'Text Area'],
	['id' => 'text_field', 'label' => 'Text Field'],
	['id' => 'yes_no_na', 'label' => 'Yes No N/A']
];
 foreach ($permit_form as $key => $value) {  ?>
       	<div class="frame-design frame-design-<?php echo html_entity_decode($key); ?> tab-<?php echo html_entity_decode($value['id']); ?> hide">
          <?php echo form_open(admin_url('hira/add_update_design_permit_form'), array('class' => 'design_permit_form', 'id' => 'design_permit_form-'.$value['id'], 'autocomplete' => 'off')); ?>
			<input type="hidden" name="template_id" value="<?php echo html_entity_decode($id); ?>">
          	<input type="hidden" name="pid" value="<?php echo html_entity_decode($value['id']); ?>">
          	<input type="hidden" name="id_deleted" value="">
          	<div class="row">
          		<div class="col-md-6 title">
          			<input type="text" name="name" value="<?php echo html_entity_decode($value['name']); ?>">&nbsp;
          			<div class="edit-title" onclick="edit_title();"><i class="fa fa-pencil-square-o"></i></div>
          		</div>
          		<div class="col-md-6">
          			<input type="hidden" name="accept_delete" value="0">         		
			        <button class="btn btn-danger pull-right btn-delete-all" type="button" data-id="<?php echo html_entity_decode($value['id']); ?>"><?php echo _l('delete_all'); ?></button>
					<div class="checkbox enable-checkbox pull-right">							
			              <input type="checkbox" class="capability" name="enable_s" value="1" <?php if($value['enable'] == 1){ echo 'checked'; } ?> >
			              <label><?php echo _l('enable'); ?>&nbsp;&nbsp;&nbsp;</label>
			        </div>
          		</div>
          	</div>
          	<div class="clearfix"></div>
          	<br>
          	<table class="design">
          		<thead>
	          		<tr>
	          			<th><?php echo _l('questions'); ?></th>
	          			<th><?php echo _l('response_type'); ?></th>
	          		</tr>
          		<thead>
          		<tbody>

          		<?php 
          			$has_data = false;
          			$detail = $this->hira_model->get_permit_form_details_by_master_id($value['id']);
          			  foreach ($detail as $dtkey => $detailvalue) { $has_data = true;  ?>
          				<tr class="item-controls item-controls-<?php echo html_entity_decode($dtkey);  ?>" data-index="<?php echo html_entity_decode($dtkey); ?>">
		          			<td class="lefts">
								<input type="hidden" name="id[<?php echo html_entity_decode($dtkey); ?>]"  value="<?php echo html_entity_decode($detailvalue['id']); ?>">	
		          				<div class="frame-draps">
		          					<div class="drap">
		          						<i class="fa fa-bars"></i>
		          					</div>
		          					<div class="control">
		          						<div>
		          							<?php 
												echo render_textarea('content['.$dtkey.']','', $detailvalue['content']);
		          							 ?>
		          						</div>
		          						<div class="add-control">	
		          							<?php switch ($detailvalue['control']) {
												    case 'attachment':
												    	$data_control['file_type'] = $detailvalue['file_type'];
												    	$data_control['no_of_files'] = $detailvalue['no_of_files'];
												    	$data_control['file_size'] = $detailvalue['file_size'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/attachment', $data_control);
												       break;
												    case 'number_field':
														$data_control['min_value'] = $detailvalue['min_value'];
												    	$data_control['max_value'] = $detailvalue['max_value'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/number_field', $data_control);
												       break;
												    case 'check_box':
													   	$data_control['add_field'] = $detailvalue['add_field'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/add_field', $data_control);
												       break;
												    case 'date':
														$data_control['start_date'] = $detailvalue['from_date'];
														$data_control['end_date'] = $detailvalue['to_date'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/date', $data_control);
												       break;
												    case 'datetime':
														$data_control['start_date'] = $detailvalue['from_date'];
														$data_control['end_date'] = $detailvalue['to_date'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/datetime', $data_control);
												       break;
												    case 'declaration':
												    	$data_control['declaration_text'] = $detailvalue['declaration_text'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/declaration', $data_control);
												       break;
												    case 'radio_button':
														$data_control['add_field'] = $detailvalue['add_field'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/add_field', $data_control);
												       break;
												    case 'single_select':
												    	$data_control['add_field'] = $detailvalue['add_field'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/add_field', $data_control);
												       break;
												    case 'multi_select':
												    	$data_control['add_field'] = $detailvalue['add_field'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/add_field', $data_control);
												       break;
												    case 'yes_no_na':
									   					$data_control['add_field'] = $detailvalue['add_field'];
												    	$data_control['index'] = $dtkey;
														$this->load->view('setting/include/controls/add_field', $data_control);
												       break;
		          							} ?>

		          						</div>
		          						<div class="action-checkbox">
		          							<div class="checkbox">							
									              <input type="checkbox" name="enable[<?php echo html_entity_decode($dtkey); ?>]" value="1" <?php if($detailvalue['enable'] == 1){ echo 'checked'; } ?> >
									              <label><?php echo _l('enable'); ?></label>
									        </div>
									        &nbsp;
									        &nbsp;
									        &nbsp;
									        <div class="checkbox">							
									              <input type="checkbox" name="mandatory[<?php echo html_entity_decode($dtkey); ?>]" value="1" <?php if($detailvalue['mandatory'] == 1){ echo 'checked'; } ?>>
									              <label><?php echo _l('mandatory'); ?></label>
									        </div>
		          						</div>
		          					</div>
		          				</div>
		          			</td>
		          			<td class="rights">   
		          				<div class="frame-control">      				
								  <?php 
									echo render_select('control['.$dtkey.']',$control,array('id','label'),'', $detailvalue['control'] , array('onchange' => 'change_design(this, '.$dtkey.')'));
								  ?> 
								  <span class="delete" onclick="delete_item(this)">
								  	<i class="fa fa-remove"></i>
								  </span>
								</div> 
		          			</td>
		          		</tr>
          		<?php } 
          			if($has_data == false){ ?>
          				<tr class="item-controls item-controls-0" data-index="0">
		          			<td class="lefts">
								<input type="hidden" name="id[0]"  value="">	
		          				<div class="frame-draps">
		          					<div class="drap">
		          						<i class="fa fa-bars"></i>
		          					</div>
		          					<div class="control">
		          						<div>
		          							<?php 
												echo render_textarea('content[0]','','');
		          							 ?>
		          						</div>
		          						<div class="add-control">	
		          						</div>
		          						<div class="action-checkbox">
		          							<div class="checkbox">							
									              <input type="checkbox" name="enable[0]" value="1" checked>
									              <label><?php echo _l('enable'); ?></label>
									        </div>
									        &nbsp;
									        &nbsp;
									        &nbsp;
									        <div class="checkbox">							
									              <input type="checkbox" name="mandatory[0]" value="1">
									              <label><?php echo _l('mandatory'); ?></label>
									        </div>
		          						</div>
		          					</div>
		          				</div>
		          			</td>
		          			<td class="rights">      
		          				<div class="frame-control">     				
								  <?php 
									echo render_select('control[0]',$control,array('id','label'),'','', array('onchange' => 'change_design(this, 0)'));
								  ?> 
								  <span class="delete" onclick="delete_item(this)">
								  	<i class="fa fa-remove"></i>
								  </span>
								</div>
		          			</td>
		          		</tr>
          			<?php  } ?>        		
          		</tbody>
          		<tfoot>
          			<tr>
	          			<td colspan="2">
	          				<span class="add-question btn" onclick="add_question(<?php echo html_entity_decode($value['id']); ?>);">&#10010; <?php echo _l('add_question'); ?></span>
	          				<button type="submit" class="btn btn-primary pull-right"><?php echo _l('save'); ?></button>
	          			</td>
	          		</tr>
          		</tfoot>
          	</table>
          <?php echo form_close(); ?>
        </div>
<?php  } ?>
<div class="modal right fade" id="accept_delete" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo _l('are_you_sure_you_want_to_delete').'?';?></h4>
			</div>
			<div class="modal-body">
                <button class="btn btn-success pull-right" id="accept" data-id=""><?php echo _l('yes'); ?></button>
                <button class="btn btn-primary cancel-delete pull-right" data-dismiss="modal"><?php echo _l('no'); ?></button>
                <div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<div class="components hide">
<?php 
	$data_control['start_date'] = null;
	$data_control['end_date'] = null;
	$data_control['index'] = 0;
	$this->load->view('setting/include/controls/date', $data_control); ?>
<?php 
	$data_control['start_date'] = null;
	$data_control['end_date'] = null;
	$data_control['index'] = 0;
	$this->load->view('setting/include/controls/datetime', $data_control); ?>
<?php
	 $data_control['add_field'] = null;
	 $data_control['index'] = 0;
	 $this->load->view('setting/include/controls/add_field', $data_control); ?>
<?php 
	$data_control['declaration_text'] = '';
	$data_control['index'] = 0;
	$this->load->view('setting/include/controls/declaration', $data_control); ?>
<?php 
	$data_control['min_value'] = 1;
	$data_control['max_value'] = 10;
	$data_control['index'] = 0;
	$this->load->view('setting/include/controls/number_field', $data_control); ?>
</div>
