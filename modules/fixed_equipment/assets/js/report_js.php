<script>
	"use strict";
	var month_report = $('select[name="months-report"]');
	function init_report(el, type){
		$('.filter_fr').addClass('hide');
		$('.report-content').addClass('hide');
		month_report.closest('.form-group').addClass('hide');
		$('.'+type).removeClass('hide');
		$('#report-time').removeClass('hide');
		$('select[name="months-report"]').val('').change();
		$('#date-range').addClass('hide');
		switch(type){
			case 'activity':
			init_activity();
			$('.report_title h4').text('<?php echo _l('fe_activity'); ?>');
			break;
			case 'inventory_report':			
			init_inventory_report();
			$('.filter2').removeClass('hide');
			$('.report_title h4').text('<?php echo _l('fe_inventory_report'); ?>');
			break;
			case 'unaccepted_assets':
			init_unaccepted_assets();
			$('.filter1').removeClass('hide');
			$('.report_title h4').text('<?php echo _l('fe_unaccepted_assets'); ?>');
			break;
		}
	}

	$('select[name="months-report"]').change(function(){
		var val = $(this).val();
		$('#date-range').addClass('hide');
		if(val == 'custom'){
			$('#date-range').removeClass('hide');
		}
	});


	function init_activity(){
		"use strict";
		var deprServerParams = {
			"months_report": "[name='months-report']",
			"report_from": "[name='report-from']",
			"report_to": "[name='report-to']"
		};		
		initDataTable('.table-activity', admin_url + 'fixed_equipment/table_activity_report', false, false, deprServerParams, [0, 'desc']);
		$('select[name="months-report"],input[name="report-from"],input[name="report-to"]').change(function(){
			$('.table-activity').DataTable().ajax.reload()
			.columns.adjust()
		});
	}

	function init_unaccepted_assets(){
		"use strict";
		var deprServerParams = {
			"checkout_for": "[name='checkout_for_filter[]']",
			"months_report": "[name='months-report']",
			"report_from": "[name='report-from']",
			"report_to": "[name='report-to']"
		};
		
		initDataTable('.table-unaccepted_assets', admin_url + 'fixed_equipment/table_unaccepted_assets_report', false, false, deprServerParams, [0, 'desc']);
		$('select[name="checkout_for_filter[]"],select[name="months-report"],input[name="report-from"],input[name="report-to"]').change(function(){
			$('.table-unaccepted_assets').DataTable().ajax.reload()
			.columns.adjust()
		});
	}

</script>	