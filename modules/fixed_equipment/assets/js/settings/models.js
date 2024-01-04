(function(){
	"use strict";
	var fnServerParams = {
		"manufacturer": "[name='manufacturer_filter[]']",
		"category": "[name='category_filter[]']",
		"depreciation": "[name='depreciation_filter[]']"
	}
	initDataTable('.table-models', admin_url + 'fixed_equipment/models_table', false, false, fnServerParams, [0, 'desc']);
	appValidateForm($('#form_models'), {
		'model_name': 'required',
		'category': 'required',
		'manufacturer': 'required'
	})
	$( "select[name='manufacturer_filter[]'], select[name='category_filter[]'], select[name='depreciation_filter[]']" ).change(function() {
		$('.table-models').DataTable().ajax.reload()
		.columns.adjust()
		.responsive.recalc();
	});
})(jQuery);

/**
 * add model
 */
function add(){
    "use strict";
	$('#add').modal('show');
	$('#add .add-title').removeClass('hide');
	$('#add .edit-title').addClass('hide');
	$('#add input[name="id"]').val('');
	$('#add input[type="text"], #add input[type="number"]').val('');
	$('#add select').val('').change();
	$('#add textarea').val('');

	$('#add input[name="may_request"]').prop('checked', false);
	$('#ic_pv_file').remove();
}

/**
 * edit model
*/
function edit(id){
	"use strict";
	$('#add').modal('show');
	$('#add .add-title').addClass('hide');
	$('#add .edit-title').removeClass('hide');
	$('#add input[name="id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_modal_content_models/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add .modal-body').html('');
		$('#add .modal-body').html(response.data);
		init_selectpicker();		
		appValidateForm($('#form_models'), {
			'model_name': 'required',
			'category': 'required',
			'manufacturer': 'required'
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
 	console.log(type);
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