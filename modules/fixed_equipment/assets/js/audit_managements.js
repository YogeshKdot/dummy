(function(){
	"use strict";
	var fnServerParams = {
		"auditor": "[name='auditor_filter[]']",
		"status": "[name='status_filter']",
		"audit_from_date": "[name='audit_from_date_filter']",
		"audit_to_date": "[name='audit_to_date_filter']"
	}
	initDataTable('.table-audit_management', admin_url + 'fixed_equipment/audit_managements_table', false, false, fnServerParams, [0, 'desc']);

	$('select[name="auditor_filter[]"], select[name="status_filter"], input[name="audit_from_date_filter"], input[name="audit_to_date_filter"]').change(function(){
		$('.table-audit_management').DataTable().ajax.reload()
		.columns.adjust()
		.responsive.recalc();
	});

})(jQuery);
