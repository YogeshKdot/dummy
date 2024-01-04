	(function(){
		"use strict";
		var fnServerParams = {
			"checkout_for": "[name='checkout_for_filter[]']",
			"status": "[name='status_filter']",
			"create_from_date": "[name='create_from_date_filter']",
			"create_to_date": "[name='create_to_date_filter']"
		}
		initDataTable('.table-request', admin_url + 'fixed_equipment/request_table', false, false, fnServerParams, [0, 'desc']);

		$('select[name="checkout_for_filter[]"], select[name="status_filter"], input[name="create_from_date_filter"], input[name="create_to_date_filter"]').change(function(){
			$('.table-request').DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});
		appValidateForm($('#add_new_request-form'), {
			'request_title': 'required',
			'item_id': 'required',
			'staff_id': 'required'
		})

	})(jQuery);

	function add(){
		"use strict";
		$('#add_new_request').modal('show');
	}

	