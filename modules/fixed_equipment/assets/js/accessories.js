(function(){
	"use strict";
	var fnServerParams = {
		"manufacturer": "[name='manufacturer_filter']",
		"category": "[name='category_filter']",
		"location": "[name='location_filter']"
	}
	initDataTable('.table-accessories', admin_url + 'fixed_equipment/accessories_table', false, false, fnServerParams, [0, 'desc']);
	$('select[name="category_filter"], select[name="location_filter"], select[name="manufacturer_filter"]').change(function(){
		$('.table-accessories').DataTable().ajax.reload()
		.columns.adjust()
		.responsive.recalc();
	});
	appValidateForm($('#accessories-form'), {
		'assets_name': 'required',
		'quantity': 'required',
		'category_id': 'required'
	})

	appValidateForm($('#check_out_accessories-form'), {
		'staff_id': 'required'
	})

	$('input[name="checkout_to"]').click(function(){
		$('.checkout_to_fr').addClass('hide');
		var val = $(this).val();
		switch(val){
			case 'user':
			$('.checkout_to_staff_fr').removeClass('hide');
			appValidateForm($('#check_out_license-form'), {
				'staff_id': 'required',
				'status': 'required'
			})
			break;
			case 'asset':
			$('.checkout_to_asset_fr').removeClass('hide');
			appValidateForm($('#check_out_license-form'), {
				'asset_id': 'required',
				'status': 'required'
			})
			break;
		}
	});

	$("input[data-type='currency']").on({
		keyup: function() {        
			formatCurrency($(this));
		},
		blur: function() { 
			formatCurrency($(this), "blur");
		}
	});

})(jQuery);

/**
 * add accessories
 */
function add(){
	"use strict";
	$('#add_new_accessories').modal('show');
	$('#add_new_accessories .add-title').removeClass('hide');
	$('#add_new_accessories .edit-title').addClass('hide');
	$('#add_new_accessories input[name="id"]').val('');
	$('#add_new_accessories input[type="text"]').val('');
	$('#add_new_accessories input[type="number"]').val('');
	$('#add_new_accessories select').val('').change();
	$('#add_new_accessories textarea').val('');
	$('#add_new_accessories input[type="checkbox"]').prop('checked', false);
	$('#ic_pv_file').remove();
}

/**
 * edit accessories
 */
function edit(id){
	"use strict";
	$('#add_new_accessories').modal('show');
	$('#add_new_accessories .add-title').addClass('hide');
	$('#add_new_accessories .edit-title').removeClass('hide');
	$('#add_new_accessories button[type="submit"]').attr('disabled', true);
	$('#add_new_accessories input[name="id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_data_accessories_modal/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add_new_accessories .modal-body').html('');
		$('#add_new_accessories button[type="submit"]').removeAttr('disabled');
		$('#add_new_accessories .modal-body').html(response);
		
		init_selectpicker();	
		init_datepicker();
		appValidateForm($('#accessories-form'), {
			'assets_name': 'required',
			'quantity': 'required',
			'category_id': 'required'
		})
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}
/**
 * check in 
 * @param  {object} el
 * @param  {integer} id
 */
function check_in(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_in').modal('show');
	$('#check_in .modal-header .add-title').text(asset_name);
	$('#check_in input[name="id"]').val(id);
	$('#check_in input[name="asset_name"]').val(asset_name);
}

/**
 * check out 
 * @param  {object} el
 * @param  {integer} id
 */
function check_out(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_out').modal('show');
	$('#check_out input[name="item_id"]').val(id);
	$('#check_out input[name="asset_name"]').val(asset_name);
}

/**
 * formatNumber
 * @param  {string} n 
 */
function formatNumber(n) {
	"use strict";
	return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
/**
 * formatCurrency
 */
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