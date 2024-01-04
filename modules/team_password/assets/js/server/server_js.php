<script>
(function(){
    "use strict";
	var fnServerParams = {
	    "category_filter": "[name='category_filter']"
	}
	initDataTable('.table-server', admin_url + 'team_password/server_table/'+'<?php echo html_entity_decode($cate); ?>', false, false, fnServerParams, [0, 'desc']);

	$('select[name="category_filter"]').on('change', function() {
	   $('.table-server').DataTable().ajax.reload()
	                    .columns.adjust()
	                    .responsive.recalc();
	  });
})(jQuery);
</script>