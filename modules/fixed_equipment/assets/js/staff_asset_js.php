<script>
	(function(){
		"use strict";
		var fnServerParams = {
			"staffid": "[name='staffid']"
		}
		initDataTable('.table-staff_asset', admin_url + 'fixed_equipment/staff_asset_table', false, false, fnServerParams, [0, 'desc']);
		if($('.table-history').length != 0){
			initDataTable('.table-history', admin_url + 'fixed_equipment/asset_staff_history_table', false, false, fnServerParams, [0, 'desc']);
		}
	})(jQuery);
</script>