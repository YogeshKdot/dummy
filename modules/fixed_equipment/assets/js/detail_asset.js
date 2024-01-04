(function(){
	"use strict";
	var fnServerParams = {
		"id": "[name='id']",
		"maintenance_type": "[name='maintenance_type_filter']",
		"from_date": "[name='from_date_filter']",
		"to_date": "[name='to_date_filter']",
		
	}
	if($('.table-history').length != 0){
		initDataTable('.table-history', admin_url + 'fixed_equipment/asets_history_table', false, false, fnServerParams, [0, 'desc']);
	}
	if($('.table-asset-licenses').length != 0){
		initDataTable('.table-asset-licenses', admin_url + 'fixed_equipment/table_asset_licenses_table', false, false, fnServerParams, [0, 'desc']);
	}
	if($('.table-asset-component').length != 0){
		initDataTable('.table-asset-component', admin_url + 'fixed_equipment/table_asset_component_table', false, false, fnServerParams, [0, 'desc']);
	}
	if($('.table-asset-files').length != 0){
		initDataTable('.table-asset-files', admin_url + 'fixed_equipment/asset_files_table', false, false, fnServerParams, [0, 'desc']);
	}
	if($('.table-assets_maintenances').length != 0){
		initDataTable('.table-assets_maintenances', admin_url + 'fixed_equipment/detail_assets_table', false, false, fnServerParams, [0, 'desc']);
		$('select[name="maintenance_type_filter"], input[name="from_date_filter"], input[name="to_date_filter"]').change(function(){
			$('.table-assets_maintenances').DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});
	}
	if($('.table-asset_checkout').length != 0){
		fnServerParams = {
			"id": "[name='id']",
			"model": "[name='model_filter']",
			"status": "[name='status_filter']",
			"supplier": "[name='supplier_filter']",
			"location": "[name='location_filter']",
		}
		initDataTable('.table-asset_checkout', admin_url + 'fixed_equipment/asset_checkout_table', false, false, fnServerParams, [0, 'desc']);
	}

	appValidateForm($('#asset_file-form'), {
		'attachments': 'required'
	})

	appValidateForm($('#assets_maintenances-form'), {
		'asset_id': 'required',
		'supplier_id': 'required',
		'maintenance_type': 'required',
		'start_date': 'required',
		'title': 'required'
	})
	appValidateForm($('#check_in_assets-form'), {
		'checkin_date': 'required',
		'status': 'required'
	})

	$("input[data-type='currency']").on({
		keyup: function() {        
			formatCurrency($(this));
		},
		blur: function() { 
			formatCurrency($(this), "blur");
		}
	});
})(jQuery);

function add(){
	"use strict";
	$('#add').modal('show');
	$('#add .add-title').removeClass('hide');
	$('#add .edit-title').addClass('hide');
	$('#add input[name="id"]').val('');
	$('#add input[name="category_name"]').val('');
	$('#add select[name="type"]').val('').change();
	$('#add textarea[name="category_eula"]').val('');

	$('#add input[name="primary_default_eula"]').prop('checked', false);
	$('#add input[name="confirm_acceptance"]').prop('checked', false);
	$('#add input[name="send_mail_to_user"]').prop('checked', false);

	$('#ic_pv_file').remove();
}
function edit(id){
	"use strict";
	$('#add').modal('show');
	$('#add .add-title').addClass('hide');
	$('#add .edit-title').removeClass('hide');
	$('#add input[name="id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_modal_content_categories/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add .modal-body').html('');
		$('#add .modal-body').html(response.data);
		init_selectpicker();		
		appValidateForm($('#form_categories'), {
			'category_name': 'required',
			'type': 'required'
		})
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}

/**
 * { preview ic btn }
 *
 * @param        invoker  The invoker
 */
 function preview_ic_btn(invoker){
 	"use strict";
 	var id = $(invoker).attr('id');
 	var rel_id = $(invoker).attr('rel_id');
 	var type = $(invoker).attr('type_item');
 	view_ic_file(id, rel_id,type);
 }

/**
 * { view ic file }
 *
 * @param        id      The identifier
 * @param        rel_id  The relative identifier
 * @param        type    The type
 */
 function view_ic_file(id, rel_id,type) {
 	"use strict";
 	$('#ic_file_data').empty();
 	$("#ic_file_data").load(admin_url + 'fixed_equipment/file_item/' + id + '/' + rel_id + '/' + type, function(response, status, xhr) {
 		if (status == "error") {
 			alert_float('danger', xhr.statusText);
 		}
 	});
 }

/**
 * Closes a modal preview.
 */
 function close_modal_preview(){
 	"use strict";
 	$('._project_file').modal('hide');
 }

/**
 * { delete ic attachment }
 *
 * @param        id       The identifier
 * @param        invoker  The invoker
 */
 function delete_ic_attachment(id,invoker) {
 	"use strict";
 	var type = $(invoker).attr('type_item');
 	if (confirm_delete()) {
 		requestGet('fixed_equipment/delete_file_item/' + id+'/'+type).done(function(success) {
 			if (success == 1) {
 				$("#ic_pv_file").find('[data-attachment-id="' + id + '"]').remove();
 			}
 		}).fail(function(error) {
 			alert_float('danger', error.responseText);
 		});
 	}
 }
 function check_in(el, id){
 	"use strict";
 	var license_name = $(el).data('license_name');
 	$('#check_in').modal('show');
 	$('#check_in input[name="item_id"]').val(id);
 	$('#check_in input[name="asset_name"]').val(license_name);
 }
 function upload_file(){
 	"use strict";
 	$('#upload_file').modal('show');
 }

 function add_maintenance(){
 	"use strict";
 	$('#add_new_assets_maintenances').modal('show');
 	$('#add_new_assets_maintenances .add-title').removeClass('hide');
 	$('#add_new_assets_maintenances .edit-title').addClass('hide');
 	$('#add_new_assets_maintenances input[name="maintenance_id"]').val('');
 	$('#add_new_assets_maintenances input[type="text"]').val('');
 	$('#add_new_assets_maintenances input[name="cost"]').val('');
 	$('#add_new_assets_maintenances select').val('').change();
 	$('#add_new_assets_maintenances textarea').val('');
 	$('#add_new_assets_maintenances input[type="checkbox"]').prop('checked', false);
 	var asset_id = $('input[name="id"]').val();
 	$('#add_new_assets_maintenances select[name="asset_id"]').val(asset_id).change();
 }
 function edit_maintenance(id){
	"use strict";
	$('#add_new_assets_maintenances').modal('show');
	$('#add_new_assets_maintenances .add-title').addClass('hide');
	$('#add_new_assets_maintenances .edit-title').removeClass('hide');
	$('#add_new_assets_maintenances input[name="maintenance_id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_data_assets_maintenances/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		
		$('select[name="asset_id"]').val(response.asset_id).change();
		$('select[name="supplier_id"]').val(response.supplier_id).change();
		$('select[name="maintenance_type"]').val(response.maintenance_type).change();

		$('input[name="title"]').val(response.title);
		$('input[name="start_date"]').val(response.start_date);
		$('input[name="completion_date"]').val(response.completion_date);
		$('input[name="cost"]').val(response.cost);
		$('textarea[name="notes"]').val(response.notes);
		
		if(response.warranty_improvement == 1){
			$('input[name="warranty_improvement"]').prop('checked', true);
		}
		else{
			$('input[name="warranty_improvement"]').prop('checked', false);
		}
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}
 function detal_asset_check_in(el, id){
 	"use strict";
 	var asset_name = $(el).data('asset_name');
 	var model_name = $(el).data('model');
 	$('#check_in').modal('show');
 	$('#check_in input[name="item_id"]').val(id);
 	$('#check_in .modal-header .add-title').text(asset_name);
 	$('#check_in input[name="asset_name"]').val(asset_name);
 	$('#check_in input[name="model"]').val(model_name);
 }

 function close_modal_preview() {
 	"use strict";
 	$('._project_file').modal('hide');
 }

 function formatNumber(n) {
 	"use strict";
 	return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
 }

 function formatCurrency(input, blur) {
 	"use strict";
 	var input_val = input.val();
 	if (input_val === "") { return; }
 	var original_len = input_val.length;
 	var caret_pos = input.prop("selectionStart");
 	if (input_val.indexOf(".") >= 0) {
 		var decimal_pos = input_val.indexOf(".");
 		var left_side = input_val.substring(0, decimal_pos);
 		var right_side = input_val.substring(decimal_pos);
 		left_side = formatNumber(left_side);

 		right_side = formatNumber(right_side);
 		right_side = right_side.substring(0, 2);
 		input_val = left_side + "." + right_side;

 	} else {
 		input_val = formatNumber(input_val);
 		input_val = input_val;
 	}
 	input.val(input_val);
 	var updated_len = input_val.length;
 	caret_pos = updated_len - original_len + caret_pos;
 	input[0].setSelectionRange(caret_pos, caret_pos);
 }