<script>
(function(){
	    "use strict";
	var fnServerParams = {
	    "category_filter": "[name='category_filter']"
	}
	initDataTable('.table-software_license', admin_url + 'team_password/software_license_table/'+'<?php echo html_entity_decode($cate); ?>', false, false, fnServerParams, [0, 'desc']);

	$('select[name="category_filter"]').on('change', function() {
	   $('.table-software_license').DataTable().ajax.reload()
	                    .columns.adjust()
	                    .responsive.recalc();
	  });

})(jQuery);
</script>