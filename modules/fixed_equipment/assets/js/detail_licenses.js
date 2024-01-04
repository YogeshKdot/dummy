(function(){
	"use strict";
	var fnServerParams = {
		"id": "[name='id']",
	}
	if($('.table-seat').length != 0){
		initDataTable('.table-seat', admin_url + 'fixed_equipment/license_seat_table', false, false, fnServerParams, [0, 'desc']);
	}
	if($('.table-history').length != 0){
		initDataTable('.table-history', admin_url + 'fixed_equipment/license_history_table', false, false, fnServerParams, [0, 'desc']);
	}
	if($('.table-license-files').length != 0){
		initDataTable('.table-license-files', admin_url + 'fixed_equipment/license_files_table', false, false, fnServerParams, [0, 'desc']);
	}

	appValidateForm($('#check_out_license-form'), {
		'staff_id': 'required',
		'status': 'required'
	})

	appValidateForm($('#licenses_file-form'), {
		'attachments': 'required'
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
})(jQuery);

function check_in(el, id){
	"use strict";
	var license_name = $(el).data('license_name');
	$('#check_in').modal('show');
	$('#check_in input[name="item_id"]').val(id);
	$('#check_in input[name="asset_name"]').val(license_name);
}

function check_out(el, id){
	"use strict";
	var license_name = $(el).data('license_name');
	$('#check_out').modal('show');
	$('#check_out input[name="item_id"]').val(id);
	$('#check_out input[name="asset_name"]').val(license_name);
}
function upload_file(){
	"use strict";
	$('#upload_file').modal('show');
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

 function close_modal_preview() {
 	"use strict";
 	$('._project_file').modal('hide');
 }
