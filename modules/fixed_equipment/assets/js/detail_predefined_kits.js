(function(){
	"use strict";
	var fnServerParams = {
		"id": "[name='parent_id']"
	}
	initDataTable('.table-model_predefined_kits', admin_url + 'fixed_equipment/model_predefined_kits_table', false, false, fnServerParams, [0, 'desc']);

	appValidateForm($('#model_predefined_kits-form'), {
		'model_id': 'required',
		'quantity': 'required'
	})

})(jQuery);

function add(){
	"use strict";
	$('#append_model').modal('show');
	$('#append_model .add-title').removeClass('hide');
	$('#append_model .edit-title').addClass('hide');
	$('#append_model input[name="id"]').val('');
	$('#append_model input[type="number"]').val('');
	$('#append_model select').val('').change().removeAttr('disabled');
	$('#ic_pv_file').remove();
}

function edit(el){
	"use strict";
	$('#append_model').modal('show');
	$('#append_model .add-title').addClass('hide');
	$('#append_model .edit-title').removeClass('hide');
	$('#append_model input[name="id"]').val($(el).data('id'));
	$('#append_model select[name="model_id"]').val($(el).data('model_id')).attr('disabled', 'disabled').change();
	$('#append_model input[name="quantity"]').val($(el).data('quantity'));
}
