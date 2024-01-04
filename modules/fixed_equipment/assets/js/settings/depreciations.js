(function(){
	"use strict";
	var fnServerParams = {
	}
	initDataTable('.table-depreciations', admin_url + 'fixed_equipment/depreciations_table', false, false, fnServerParams, [0, 'desc']);
	appValidateForm($('#form_depreciations'), {
		'name': 'required',
		'term': 'required',
	})
})(jQuery);

/**
 * add depreciation
 */
function add(){
    "use strict";
	$('#add').modal('show');
	$('#add .add-title').removeClass('hide');
	$('#add .edit-title').addClass('hide');
	$('#add input[name="id"]').val('');
}

/**
 * edit depreciation
 */
function edit(el){
    "use strict";
	$('#add').modal('show');
	$('#add .add-title').addClass('hide');
	$('#add .edit-title').removeClass('hide');
	$('#add input[name="id"]').val($(el).data('id'));
	$('#add input[name="name"]').val($(el).data('name'));
	$('#add input[name="term"]').val($(el).data('term'));
}