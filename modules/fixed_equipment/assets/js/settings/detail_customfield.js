(function(){
	"use strict";
	var fnServerParams = {
		"id": "[name='fieldset_id']"
	}
	initDataTable('.table-customfield', admin_url + 'fixed_equipment/custom_field_table', false, false, fnServerParams, [0, 'desc']);

	appValidateForm($('#add_custom_field-form'), {
		'title': 'required',
		'type': 'required'
	})
	$('.add_new_row').click(function(){
		var parent = $(this).parents('.list-option');
		var row = parent.find('.row').eq(0).clone().appendTo('.list-option');
		row.find('button').removeClass('add_new_row').addClass('remove_row').removeClass('btn-success').addClass('btn-danger').find('i').removeClass('fa-plus').addClass('fa-minus');
		row.find('input').val('');
	});
	$(document).on("click", ".remove_row", function() { 
		$(this).parents('.row').remove();
	});
	$('select[name="type"]').change(function(){
		var val = $(this).val();
		$('.list-option').addClass('hide').find('input[name="option[]"]').removeAttr('required');
		$('.remove_row').click();
		$('.list-option').find('input').val('');
		switch(val){
			case 'textfield':
			break;
			case 'numberfield':
			break;
			case 'textarea':
			break;
			case 'select':
			$('.list-option').removeClass('hide').find('input[name="option[]"]').attr('required', true);
			break;
			case 'multi_select':
			$('.list-option').removeClass('hide').find('input[name="option[]"]').attr('required', true);
			break;
			case 'checkbox':
			$('.list-option').removeClass('hide').find('input[name="option[]"]').attr('required', true);
			break;
			case 'radio_button':
			$('.list-option').removeClass('hide').find('input[name="option[]"]').attr('required', true);
			break;
		}
	});
})(jQuery);

/**
 * add detail customfield
 */
function add(){
    "use strict";
	$('#add').modal('show');
	$('#add .add-title').removeClass('hide');
	$('#add .edit-title').addClass('hide');
	$('#add input[type="text"]').val('');
	$('#add input[name="location_name"]').val('');
	$('#add select').val('').change();
	$('.remove_row').click();
	$('.list-option').find('input').val('');
}

/**
 * edit detail customfield
 */
function edit(id){
	"use strict";
	$('#add').modal('show');
	$('#add .add-title').addClass('hide');
	$('#add .edit-title').removeClass('hide');
	$('#add input[name="id"]').val(id);
	var requestURL = (typeof(url) != 'undefined' ? url : 'fixed_equipment/get_custom_field_data/') + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {

		$('#add input[name="id"]').val(response.id);
		$('#add input[name="title"]').val(response.title);
		$('#add select[name="type"]').val(response.type).change();

		if(response.required == 1){
			$('#add input[name="required"]').prop('checked', true);
		}
		else{
			$('#add input[name="required"]').prop('checked', false);
		}

		var array_option = jQuery.parseJSON(response.option);
		jQuery.each(array_option,function(key, value){
			if(key > 0){
				$('.add_new_row').click();
			}
			$('input[name="option[]"]').eq(key).val(value);
		});  
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}
