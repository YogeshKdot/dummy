(function(){
	"use strict";
	var fnServerParams = {
		"manufacturer": "[name='manufacturer_filter']"
	}
	initDataTable('.table-licenses', admin_url + 'fixed_equipment/licenses_table', false, false, fnServerParams, [0, 'desc']);
	$('select[name="manufacturer_filter"]').change(function(){
		$('.table-licenses').DataTable().ajax.reload()
		.columns.adjust()
		.responsive.recalc();
	});
	appValidateForm($('#licenses-form'), {
		'assets_name': 'required',
		'seats': 'required',
		'manufacturer_id': 'required',
		'category_id': 'required'
	})
	appValidateForm($('#check_out_license-form'), {
		'staff_id': 'required',
		'status': 'required'
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

function add(){
	"use strict";
	$('#add_new_licenses').modal('show');
	$('#add_new_licenses .add-title').removeClass('hide');
	$('#add_new_licenses .edit-title').addClass('hide');
	$('#add_new_licenses input[name="id"]').val('');
	$('#add_new_licenses input[type="text"]').val('');
	$('#add_new_licenses select').val('').change();
	$('#add_new_licenses textarea').val('');
	$('#add_new_licenses input[type="checkbox"]').prop('checked', false);
}
function edit(id){
	"use strict";
	$('#add_new_licenses').modal('show');
	$('#add_new_licenses .add-title').addClass('hide');
	$('#add_new_licenses .edit-title').removeClass('hide');
	$('#add_new_licenses input[name="id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_data_licenses/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add_new_licenses input[name="id"]').val(response.id);
		$('#add_new_licenses input[name="assets_name"]').val(response.assets_name);

		if(response.category_id != 0){
			$('#add_new_licenses select[name="category_id"]').val(response.category_id).change();
		}
		else{
			$('#add_new_licenses select[name="category_id"]').val('').change();
		}
		$('#add_new_licenses textarea[name="product_key"]').val(response.product_key);
		$('#add_new_licenses input[name="seats"]').val(response.seats);
		if(response.manufacturer_id != 0){
			$('#add_new_licenses select[name="manufacturer_id"]').val(response.manufacturer_id).change();
		}
		else{
			$('#add_new_licenses select[name="manufacturer_id"]').val('').change();
		}
		$('#add_new_licenses input[name="licensed_to_name"]').val(response.licensed_to_name);
		$('#add_new_licenses input[name="licensed_to_email"]').val(response.licensed_to_email);
		if(response.reassignable == 1){
			$('#add_new_licenses input[name="reassignable"]').prop('checked', true);			
		}
		else{
			$('#add_new_licenses input[name="reassignable"]').prop('checked', false);						
		}

		if(response.supplier_id != 0){
			$('#add_new_licenses select[name="supplier_id"]').val(response.supplier_id).change();
		}
		else{
			$('#add_new_licenses select[name="supplier_id"]').val('').change();
		}
		$('#add_new_licenses input[name="order_number"]').val(response.order_number);
		$('#add_new_licenses input[name="purchase_order_number"]').val(response.purchase_order_number);
		$('#add_new_licenses input[name="unit_price"]').val(response.unit_price);
		$('#add_new_licenses input[name="date_buy"]').val(response.date_buy);
		$('#add_new_licenses input[name="expiration_date"]').val(response.expiration_date);
		$('#add_new_licenses input[name="termination_date"]').val(response.termination_date);
		if(response.depreciation != 0){
			$('#add_new_licenses select[name="depreciation"]').val(response.depreciation).change();
		}
		else{
			$('#add_new_licenses select[name="depreciation"]').val('').change();
		}
		if(response.maintained == 1){
			$('#add_new_licenses input[name="maintained"]').prop('checked', true);			
		}
		else{
			$('#add_new_licenses input[name="maintained"]').prop('checked', false);						
		}
		$('#add_new_licenses textarea[name="description"]').val(response.description);
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}

function check_in(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_in').modal('show');
	$('#check_in .modal-header .add-title').text(asset_name);
	$('#check_in input[name="id"]').val(id);
	$('#check_in input[name="asset_name"]').val(asset_name);
}

function check_out(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_out').modal('show');
	$('#check_out input[name="id"]').val(id);
	$('#check_out input[name="asset_name"]').val(asset_name);
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