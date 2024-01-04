<script>
	(function(){
		"use strict";
		var fnServerParams = {
			"model": "[name='model_filter']",
			"status": "[name='status_filter']",
			"supplier": "[name='supplier_filter']",
			"location": "[name='location_filter']",
		}
		initDataTable('.table-assets_management', admin_url + 'fixed_equipment/all_asset_table', '', '', fnServerParams, [0, 'desc']);
		$( "select[name='model_filter'], select[name='status_filter'], select[name='supplier_filter'], select[name='location_filter']" ).change(function() {
			$('.table-assets_management').DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});

		$(document).on('click', '.serial-items button.add', function (e) {
			var max_item = $('#amount').val();
			var list_items = $('.serial-items');
			if(max_item == ''){
				return ; 
			}
			if(max_item == list_items.length){
				return ; 
			}			
			var obj = $(this).parents('.serial-items').clone().appendTo('.serial-lists');
			obj.find('.btn-primary.add').removeClass('btn-primary').removeClass('add').addClass('btn-danger').addClass('delete');
			obj.find('i.fa.fa-plus').removeClass('fa-plus').addClass('fa-minus');
			obj.find('i.fa.fa-plus').removeClass('fa-plus').addClass('fa-minus');
			obj.find('input[name="serial[]"]').val('');
		});
		
		$("body").on('click', '.serial-items button.delete', function() {
			$(this).parents('.serial-items').remove();
		});
		$(document).on('click', '.tab_block_main ul li a', function (e) {
			var curTabContentId = $(this).attr('href');
			$(this).parents('.tab_block_main').find('ul li a').removeClass('active');
			$(this).addClass('active');
			$(this).parents('.tab_block_main').find('.tab_content .tab_block').removeClass('active');
			$(curTabContentId).addClass("active");
			e.preventDefault();
		}); 

		$(document).on("keyup blur","input[data-type='currency']",function() {
			formatCurrency($(this));
		});

		appValidateForm($('#assets-form'), {
			'model_id': 'required',
			'status': 'required'
		})
		appValidateForm($('#check_out_assets-form'), {
			'staff_id': 'required',
			'status': 'required'
		})
		appValidateForm($('#check_in_assets-form'), {
			'checkin_date': 'required',
			'status': 'required'
		})
		
		$('input[name="checkout_to"]').click(function(){
			$('.checkout_to_fr').addClass('hide');
			var val = $(this).val();
			switch(val){
				case 'user':
				$('.checkout_to_staff_fr').removeClass('hide');
				appValidateForm($('#check_out_assets-form'), {
					'staff_id': 'required',
					'status': 'required'
				})
				break;
				case 'asset':
				$('.checkout_to_asset_fr').removeClass('hide');
				appValidateForm($('#check_out_assets-form'), {
					'asset_id': 'required',
					'status': 'required'
				})
				break;
				case 'location':
				$('.checkout_to_location_fr').removeClass('hide');
				appValidateForm($('#check_out_assets-form'), {
					'location_id': 'required',
					'status': 'required'
				})
				break;
			}
		});

		$(document).on("change",'select[name="model_id"]',function() {
			var id = $(this).val();
			var requestURL = (typeof(url) != 'undefined' ? url : 'fixed_equipment/get_custom_field_model/') + (typeof(id) != 'undefined' ? id : '');
			requestGetJSON(requestURL).done(function(response) {
				$('.customfields_fr').html(response);
				init_selectpicker();
			}).fail(function(data) {
				alert_float('danger', 'Error');
			});		
		});
			$(document).on("click","#mass_select_all",function() {
	     		var favorite = [];
	     		if($(this).is(':checked')){
	     			$('.individual').prop('checked', true);
	     			$.each($(".individual"), function(){ 
	     				favorite.push($(this).data('id'));
	     			});
	     		}else{
	     			$('.individual').prop('checked', false);
	     			favorite = [];
	     		}

	     		$("input[name='check']").val(favorite);
	     	});
	})(jQuery);

	/**
	 * add asset
	 */
	 function add(){
	 	"use strict";
	 	$('#add_new_assets').modal('show');
	 	$('#add_new_assets .edit-title').addClass('hide');
	 	$('#add_new_assets .add-title').removeClass('hide');
	 	$('#add_new_assets input[type="text"]').val('');
	 	$('#add_new_assets input[type="number"]').val('');
	 	$('#add_new_assets input[data-type="currency"]').val('');
	 	$('#add_new_assets select').val('').change();
	 	$('#add_new_assets textarea').val('');
	 	$('#add_new_assets input[type="checkbox"]').prop('checked', false);
	 	$('#serial-lists-items button.delete').click();
	 }

	/**
	 * edit asset
	 */
	 function edit(id){
	 	"use strict";
	 	$('#add_new_assets .modal-body').html('');
	 	$('#add_new_assets').modal('show');
	 	$('#add_new_assets .add-title').addClass('hide');
	 	$('#add_new_assets .edit-title').removeClass('hide');
	 	$('#add_new_assets input[name="id"]').val(id);
	 	var requestURL = (typeof(url) != 'undefined' ? url : 'fixed_equipment/get_modal_content_assets/') + (typeof(id) != 'undefined' ? id : '');
	 	requestGetJSON(requestURL).done(function(response) {
	 		$('#add_new_assets .modal-body').html(response.data);
	 		init_selectpicker();	
	 		init_datepicker();
	 		appValidateForm($('#assets-form'), {
	 			'model_id': 'required',
	 			'status': 'required'
	 		})
	 	}).fail(function(data) {
	 		alert_float('danger', 'Error');
	 	});
	 }

	/**
	 * format number
	 */
	 function formatNumber(n) {
	 	"use strict";
	 	return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
	 }

	/**
	 * format currency
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
	 * asset code
	 */
	 function get_asset_code(el){
	 	"use strict";
	 	var id = $(el).val();
	 	if(id.trim()){
	 		$('#loading').show();
	 		$.post(admin_url+'assets/get_assets_code_increment/'+id).done(function(response){
	 			response = JSON.parse(response);
	 			if(response.success == true) {
	 				$('input[name="assets_code"]').val(response.group_code+''+response.count);
	 				$('input[name="depreciation"]').val(response.depreciation);                  
	 				$('#group_code').text(response.group_code);
	 			}
	 			$('#loading').hide();
	 		});
	 	}
	 }

	/**
	 * compare list serial with current amount
	 * @return {boolean} 
	 */
	 function compare_list_serial_with_current_amount(){
	 	"use strict";
	 	var amount = $('#amount').val();
	 	var list_items = $('.serial-items');
	 	$('#serial-lists-items .panel .alert').remove();
	 	if(amount < list_items.length){
	 		var alert_msg = '<?php echo _l('the_number_has_changed_to'); ?> '+amount+', <?php echo _l('please_delete'); ?> '+(list_items.length - amount)+' <?php echo _l('non_existent_serial_numbers'); ?>';
	 		var alert = '<div class="alert alert-danger">'+alert_msg+'</div>';
	 		$('#serial-lists-items .panel').prepend(alert);
	 		return true;
	 	}
	 	else{
	 		return false;
	 	}
	 }

	/**
	 * validate Form
	 * @return {boolean} 
	 */
	 function validateForm(){
	 	"use strict";
	 	if(compare_list_serial_with_current_amount() == true){
	 		alert_float('danger', '<?php echo _l('please_readjust_the_serial_list'); ?>');
	 		return false;
	 	}
	 	if(check_valid_serial() == false){
	 		alert_float('danger', '<?php echo _l('fe_no_valid_serial_list'); ?>');
	 		return false;
	 	}
	 }

	/**
	 * check valid serial
	 * @return {boolean} 
	 */
	 function check_valid_serial(){
	 	"use strict";
	 	var count_error = 0;
	 	var list_error = $('.error-serial-list');
	 	for(let i = 0; i < list_error.length; i++){
	 		count_error++;
	 	}
	 	var list_error_check = $('.error-check-serial-list');
	 	for(let i = 0; i < list_error_check.length; i++){
	 		count_error++;
	 	}
	 	if(count_error != 0){
	 		return false;
	 	}
	 	return true;
	 }

	/**
	 * check duplicate
	 */
	 function check_duplicate(){
	 	"use strict";
	 	var list_serial = $('input[name="serial[]"]');
	 	var list_serial_check = [];
	 	for(let i = 0; i < list_serial.length; i++){
	 		list_serial.eq(i).closest('.form-group').find('.error-serial-list').remove();                
	 		var serial_row = list_serial.eq(i).val();
	 		if(serial_row != ''){
	 			if(!list_serial_check.includes(serial_row)){
	 				list_serial_check.push(list_serial.eq(i).val());
	 			}
	 			else{
	 				list_serial.eq(i).closest('.form-group').append('<p class="error-serial-list text-danger"><?php echo _l('fe_this_serial_number_is_duplicate'); ?></p>');          
	 			}
	 		}
	 	}
	 }

	/**
	 * check serial
	 */
	 function check_serial(el){
	 	"use strict";
	 	var val = $(el).val();
	 	if(val.trim() && val != ''){
	 		var asset_id = $('input[name="id"]').val();
	 		if(asset_id == ''){
	 			asset_id = 0;
	 		}
	 		$('#add_new_assets button[type="submit"]').attr('disabled', true);
	 		$.post(admin_url+'fixed_equipment/check_exist_serial/'+val+'/'+asset_id).done(function(response){
	 			response = JSON.parse(response);
	 			$('#add_new_assets button[type="submit"]').attr('disabled', false);
	 			$(el).closest('.form-group').find('.error-check-serial-list').remove();               
	 			if(response != ''){
	 				$(el).closest('.form-group').append('<p class="error-check-serial-list red-color text-danger">'+response+'</p>');           
	 			}
	 		});
	 	}
	 }

/**
 * check in
 */
 function check_in(el, id){
 	"use strict";
 	var asset_name = $(el).data('asset_name');
 	var model = $(el).data('model');
 	$('#check_in').modal('show');
 	$('#check_in input[name="item_id"]').val(id);
 	$('#check_in .modal-header .add-title').text(asset_name);
 	$('#check_in input[name="asset_name"]').val(asset_name);
 	$('#check_in input[name="model"]').val(model);

 }

	/**
	 * check out
	 */
	 function check_out(el, id){
	 	"use strict";
	 	var asset_name = $(el).data('asset_name');
	 	var serial = $(el).data('serial');
	 	var model = $(el).data('model');
	 	$('#check_out').modal('show');
	 	$('#check_out input[name="item_id"]').val(id);
	 	$('#check_out .modal-header .add-title').text(serial);
	 	$('#check_out input[name="model"]').val(model);
	 	$('#check_out input[name="asset_name"]').val(asset_name);
	 }

	 function checked_add(el){
	var id = $(el).data("id");
	var id_product = $(el).data("product");
	if ($(".individual").length == $(".individual:checked").length) {
		$("#mass_select_all").attr("checked", "checked");
		var value = $("input[name='check']").val();
		if(value != ''){
			value = value + ',' + id;
		}else{
			value = id;
		}
	} else {
		$("#mass_select_all").removeAttr("checked");
		var value = $("input[name='check']").val();
		var value_product = $("input[name='check_product']").val();
		var arr_val = value.split(',');
		if(arr_val.length > 0){
			$.each( arr_val, function( key, value ) {
				if(value == id){
					arr_val.splice(key, 1);
					value = arr_val.toString();
					$("input[name='check']").val(value);
				}
			});
		}
	}
	if($(el).is(':checked')){
		var value = $("input[name='check']").val();
		if(value != ''){
			value = value + ',' + id;
			value_product = value_product + ',' + id_product;
		}else{
			value = id;
			value_product = id_product;
		}
		$("input[name='check']").val(value);
		$("input[name='check_product']").val(value_product);
	}else{
		var value = $("input[name='check']").val();
		var value_product = $("input[name='check_product']").val();
		var arr_val = value.split(',');
		var arr_val_product = value_product.split(',');
		if(arr_val.length > 0){
			$.each( arr_val, function( key, value ) {
				if(value == id){
					arr_val.splice(key, 1);
					value = arr_val.toString();
					$("input[name='check']").val(value);
				}
			});

			$.each( arr_val_product, function( key, value_ ) {
				if(value_ == id_product){
					arr_val_product.splice(key, 1);
					value_ = arr_val_product.toString();
					$("input[name='check_product']").val(value_);
				}
			});
		}
	}
}


function bulk_sign(){
	"use strict";
	var checked_id = $('input[name="check"]').val();
	var data = {};
	data.id_list = checked_id;
	if(checked_id != ''){
		$('#create_sign_document_modal').modal('show');
		$('#create_sign_document_modal select[name="staffid"]').val($('#staff_id').val()).change();
	}
	else{
		alert_float('danger', $('input[name="please_select_at_least_one_item_from_the_list"]').val());
	}
}
	 function bulk_print(){
	 	var print_id = $('input[name="check"]').val();
	 	if(print_id != ''){
	 		window.location.href = admin_url+"fixed_equipment/print_qrcode_pdf/"+encodeURIComponent(print_id)+"?output_type=I";
	 	}
	 	else{
	 		alert_float('danger', '<?php echo _l('please_select_at_least_one_item_from_the_list'); ?>');
	 	}
	 }


	</script>
