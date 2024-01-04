(function(){
	"use strict";
	var fnServerParams = {
	}
	initDataTable('.table-status_labels', admin_url + 'fixed_equipment/status_labels_table', false, false, fnServerParams, [0, 'desc']);
	appValidateForm($('#form_status_labels'), {
		'name': 'required',
		'status_type': 'required',
	})
	$('select[name="status_type"]').change(function(){
		var val = $(this).val();
		$('.hide_frame').fadeOut(500).addClass('hide');
		switch(val){
			case 'deployable':
			$('.hide_deployable').fadeIn(500).removeClass('hide');
			break;
			case 'pending':
			$('.hide_pending').fadeIn(500).removeClass('hide');
			break;
			case 'undeployable':
			$('.hide_undeployable').fadeIn(500).removeClass('hide');
			break;
			case 'archived':
			$('.hide_archived').fadeIn(500).removeClass('hide');
			break;
		}
	});
})(jQuery);

/**
 * add status label
 */
function add(){
    "use strict";
	$('#add').modal('show');
	$('#add .add-title').removeClass('hide');
	$('#add .edit-title').addClass('hide');
	$('#add input[name="id"]').val('');
	$('#add input[type="text"], #add input[type="number"]').val('');
	$('#add textarea').val('');
	$('#add select').val('').change();
}

/**
 * edit status label
 */
function edit(el){
    "use strict";
	$('#add').modal('show');
	$('#add .add-title').addClass('hide');
	$('#add .edit-title').removeClass('hide');
	$('#add input[name="id"]').val($(el).data('id'));
	$('#add input[name="name"]').val($(el).data('name'));
	$('#add select[name="status_type"]').val($(el).data('status_type')).change();
	$('#add input[name="chart_color"]').val($(el).data('chart_color'));
	$('#add textarea[name="note"]').val($(el).data('note'));
	if($(el).data('default_label') == 1){
		$('#add input[name="default_label"]').prop('checked', true);		
	}
	else{
		$('#add input[name="default_label"]').prop('checked', false);		
	}
}