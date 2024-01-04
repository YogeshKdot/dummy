
<script>

	"use strict";

	var InvoiceServerParams={
		"bill_of_material_id": "[name='bill_of_material_id']",
		"bill_of_material_product_id": "[name='bill_of_material_product_id']",
		"bill_of_material_routing_id": "[name='bill_of_material_routing_id']",
	};
	var bill_of_material_detail_table = $('.table-bill_of_material_detail_table');
	initDataTable(bill_of_material_detail_table, admin_url+'manufacturing/bill_of_material_detail_table',[0],[0], InvoiceServerParams, [1 ,'asc']);

	$('#date_add').on('change', function() {
		bill_of_material_detail_table.DataTable().ajax.reload().columns.adjust().responsive.recalc();
	});

	var hidden_columns = [0,1];
	$('.table-bill_of_material_detail_table').DataTable().columns(hidden_columns).visible(false, false);



	function add_component(bill_of_material_id, component_id, product_id, routing_id, type) {
	"use strict";

	  $("#modal_wrapper").load("<?php echo admin_url('manufacturing/manufacturing/bill_of_material_detail_modal'); ?>", {
	       bill_of_material_id: bill_of_material_id,
	       component_id: component_id,
	       bill_of_material_product_id: product_id,
	       routing_id: routing_id,
	       type: type
	  }, function() {

	       $("body").find('#appointmentModal').modal({ show: true, backdrop: 'static' });
	  });

	  init_selectpicker();
	  $(".selectpicker").selectpicker('refresh');

	}

	$('input[name="bom_type"]').on('click', function() {
	"use strict";
		
		var bom_type =$(this).val();

		if(bom_type == 'manufacture_this_product'){
			$('.kit_hide').addClass('hide');
		}else if(bom_type == 'kit'){
			$('.kit_hide').removeClass('hide');

		}
	});   


</script>