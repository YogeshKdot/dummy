<script>
(function(){
    "use strict";
	var fnServerParams = {
	    "category_filter": "[name='category_filter']"
	}
	initDataTable('.table-bank_account', admin_url + 'team_password/bank_account_table/'+'<?php echo html_entity_decode($cate); ?>', false, false, fnServerParams, [0, 'desc']);

	$('select[name="category_filter"]').on('change', function() {
	   $('.table-bank_account').DataTable().ajax.reload()
	                    .columns.adjust()
	                    .responsive.recalc();
	  });
})(jQuery);
</script>
 