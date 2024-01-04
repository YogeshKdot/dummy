(function(){
	"use strict";
	var fnServerParams = {
		"parent_id": "[name='parent_id']"
	}
	initDataTable('.table-detail_consumables', admin_url + 'fixed_equipment/detail_consumables_table', false, false, fnServerParams, [0, 'desc']);

	appValidateForm($('#check_in_consumables-form'), {
		'checkin_date': 'required'
	})
	appValidateForm($('#check_out_consumables-form'), {
		'staff_id': 'required'
	})

})(jQuery);

function check_in(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_in').modal('show');
	$('#check_in input[name="id"]').val(id);
	$('#check_in input[name="asset_name"]').val(asset_name);
}

function check_out(el){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_out').modal('show');
	$('#check_out input[name="asset_name"]').val(asset_name);
}