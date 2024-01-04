(function(){
	"use strict";
	var fnServerParams = {
		"model_id": "[name='id']"
	}
	initDataTable('.table-view_model', admin_url + 'fixed_equipment/view_model_table', false, false, fnServerParams, [0, 'desc']);
})(jQuery);
