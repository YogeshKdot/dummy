(function(){
	"use strict";
	var fnServerParams = {	}
	if($('.table-assets_management').length != 0){
		fnServerParams = {
			"id": "[name='id']",
			"model": "[name='model_filter']",
			"status": "[name='status_filter']",
			"supplier": "[name='supplier_filter']",
		}
		initDataTable('.table-assets_management', admin_url + 'fixed_equipment/asset_location_table', false, false, fnServerParams, [0, 'desc']);
		$( "select[name='model_filter'], select[name='status_filter'], select[name='supplier_filter']" ).change(function() {
			$('.table-assets_management').DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});
		appValidateForm($('#check_out_assets-form'), {
			'staff_id': 'required',
			'status': 'required'
		})
		appValidateForm($('#check_in_assets-form'), {
			'checkin_date': 'required',
			'status': 'required'
		})
		$('#check_out_assets-form input[name="checkout_to"]').click(function(){
			$('.checkout_to_fr').addClass('hide');
			var val = $(this).val();
			switch(val){
				case 'user':
				$('.checkout_to_staff_fr').removeClass('hide');
				appValidateForm($('#check_out_assets-form'), {
					'staff_id': 'required',
					'status': 'required'
				})
				break;
				case 'asset':
				$('.checkout_to_asset_fr').removeClass('hide');
				appValidateForm($('#check_out_assets-form'), {
					'asset_id': 'required',
					'status': 'required'
				})
				break;
				case 'location':
				$('.checkout_to_location_fr').removeClass('hide');
				appValidateForm($('#check_out_assets-form'), {
					'location_id': 'required',
					'status': 'required'
				})
				break;
			}
		});
	}

	if($('.table-accessories').length != 0){
		fnServerParams = {
			"manufacturer": "[name='manufacturer_filter']",
			"category": "[name='category_filter']",
			"location": "[name='id']"
		}
		initDataTable('.table-accessories', admin_url + 'fixed_equipment/accessories_location_table', false, false, fnServerParams, [0, 'desc']);
		$('select[name="category_filter"], select[name="manufacturer_filter"]').change(function(){
			$('.table-accessories').DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});
		appValidateForm($('#check_out_accessories-form'), {
			'staff_id': 'required'
		})
	}
	if($('.table-consumables').length != 0){
		fnServerParams = {
			"manufacturer": "[name='consumable_manufacturer_filter']",
			"category": "[name='consumable_category_filter']",
			"location": "[name='id']",
		}
		initDataTable('.table-consumables', admin_url + 'fixed_equipment/consumables_location_table', false, false, fnServerParams, [0, 'desc']);
		$('select[name="consumable_category_filter"], select[name="consumable_manufacturer_filter"]').change(function(){
			$('.table-consumables').DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});
		appValidateForm($('#check_out_consumables-form'), {
			'staff_id': 'required'
		})
	}

	if($('.table-components').length != 0){
		var fnServerParams = {
			"category": "[name='component_category_filter']",
			"location": "[name='id']",
		}
		initDataTable('.table-components', admin_url + 'fixed_equipment/components_location_table', false, false, fnServerParams, [0, 'desc']);
		$('select[name="component_category_filter"]').change(function(){
			$('.table-components').DataTable().ajax.reload()
			.columns.adjust()
			.responsive.recalc();
		});
		appValidateForm($('#check_out_components-form'), {
			'asset_id': 'required',
			'quantity': 'required'
		})
	}
	$(window).on('load', function() {
		initMap();
	});
})(jQuery);

function add(){
	"use strict";
	$('#add').modal('show');
	$('#add .add-title').removeClass('hide');
	$('#add .edit-title').addClass('hide');
	$('#add input[name="id"]').val('');
	$('#add input[name="location_name"]').val('');
	$('#add select[name="parent"]').val('').change();
	$('#add select[name="manager"]').val('').change();
	$('#add input[name="address"]').val('');
	$('#add input[name="city"]').val('');
	$('#add input[name="state"]').val('');
	$('#add select[name="country"]').val('').change();
	$('#add input[name="zip"]').val('');
	$('#add select[name="location_currency"]').val('').change();
	$('#ic_pv_file').remove();
}

function edit(id){
	"use strict";
	$('#add').modal('show');
	$('#add .add-title').addClass('hide');
	$('#add .edit-title').removeClass('hide');
	$('#add input[name="id"]').val(id);
	var requestURL = (typeof(url) != 'undefined' ? url : 'fixed_equipment/get_modal_content_locations/') + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add .modal-body').html('');
		$('#add .modal-body').html(response.data);
		init_selectpicker();		
		appValidateForm($('#form_locations'), {
			'location_name': 'required'
		})
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}

/**
 * { preview ic btn }
 *
 * @param        invoker  The invoker
 */
 function preview_ic_btn(invoker){
 	"use strict";
 	var id = $(invoker).attr('id');
 	var rel_id = $(invoker).attr('rel_id');
 	var type = $(invoker).attr('type_item');
 	view_ic_file(id, rel_id,type);
 }

/**
 * { view ic file }
 *
 * @param        id      The identifier
 * @param        rel_id  The relative identifier
 * @param        type    The type
 */
 function view_ic_file(id, rel_id,type) {
 	"use strict";
 	$('#ic_file_data').empty();
 	$("#ic_file_data").load(admin_url + 'fixed_equipment/file_item/' + id + '/' + rel_id + '/' + type, function(response, status, xhr) {
 		if (status == "error") {
 			alert_float('danger', xhr.statusText);
 		}
 	});
 }

/**
 * Closes a modal preview.
 */
 function close_modal_preview(){
 	"use strict";
 	$('._project_file').modal('hide');
 }

/**
 * { delete ic attachment }
 *
 * @param        id       The identifier
 * @param        invoker  The invoker
 */
 function delete_ic_attachment(id,invoker) {
 	"use strict";
 	var type = $(invoker).attr('type_item');
 	console.log(type);
 	if (confirm_delete()) {
 		requestGet('fixed_equipment/delete_file_item/' + id+'/'+type).done(function(success) {
 			if (success == 1) {
 				$("#ic_pv_file").find('[data-attachment-id="' + id + '"]').remove();
 			}
 		}).fail(function(error) {
 			alert_float('danger', error.responseText);
 		});
 	}
 }

// Initialize and add the map
function initMap() {
  // The location of Uluru
  var lat_s = $('input[name="lat"]').val();
  var lng_s = $('input[name="lng"]').val();
  if(lat_s != '' && lng_s != ''){
  	const uluru = { lat: -25.344, lng: 131.036 };
  // The map, centered at Uluru
  const map = new google.maps.Map(document.getElementById("map"), {
  	zoom: 4,
  	center: uluru,
  });
  // The marker, positioned at Uluru
  const marker = new google.maps.Marker({
  	position: uluru,
  	map: map,
  });  	
}
}


function check_in_asset(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	var model = $(el).data('model');
	$('#check_in_asset').modal('show');
	$('#check_in_asset input[name="item_id"]').val(id);
	$('#check_in_asset .modal-header .add-title').text(asset_name);
	$('#check_in_asset input[name="asset_name"]').val(asset_name);
	$('#check_in_asset input[name="asset_name"]').val(asset_name);
	$('#check_in_asset input[name="model"]').val(model);
}

function check_out_asset(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	var serial = $(el).data('serial');
	var model = $(el).data('model');
	$('#check_out_asset').modal('show');
	$('#check_out_asset input[name="item_id"]').val(id);
	$('#check_out_asset .modal-header .add-title').text(serial);
	$('#check_out_asset input[name="model"]').val(model);
	$('#check_out_asset input[name="asset_name"]').val(asset_name);
}
function check_in_accessory(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_in').modal('show');
	$('#check_in .modal-header .add-title').text(asset_name);
	$('#check_in input[name="id"]').val(id);
	$('#check_in input[name="asset_name"]').val(asset_name);
}

function check_out_accessory(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_out_accessory').modal('show');
	$('#check_out_accessory input[name="item_id"]').val(id);
	$('#check_out_accessory input[name="asset_name"]').val(asset_name);
}
function check_out_consumable(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_out_consumable').modal('show');
	$('#check_out_consumable input[name="item_id"]').val(id);
	$('#check_out_consumable input[name="asset_name"]').val(asset_name);
}
function check_out_component(el, id){
	"use strict";
	var asset_name = $(el).data('asset_name');
	$('#check_out_component').modal('show');
	$('#check_out_component input[name="item_id"]').val(id);
	$('#check_out_component input[name="asset_name"]').val(asset_name);
}
function edit_assets_location(id){
	"use strict";
	$('#add_new_assets .modal-body').html('');
	$('#add_new_assets').modal('show');
	$('#add_new_assets .add-title').addClass('hide');
	$('#add_new_assets .edit-title').removeClass('hide');
	$('#add_new_assets input[name="id"]').val(id);
	var requestURL = (typeof(url) != 'undefined' ? url : 'fixed_equipment/get_modal_content_assets/') + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add_new_assets .modal-body').html(response.data);
		init_selectpicker();		
		appValidateForm($('#assets-form'), {
			'model_id': 'required',
			'status': 'required'
		})
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}
function edit_accessories_location(id){
	"use strict";
	$('#add_new_accessories').modal('show');
	$('#add_new_accessories .add-title').addClass('hide');
	$('#add_new_accessories .edit-title').removeClass('hide');
	$('#add_new_accessories button[type="submit"]').attr('disabled', true);
	$('#add_new_accessories input[name="id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_data_accessories_modal/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add_new_accessories .modal-body').html('');
		$('#add_new_accessories button[type="submit"]').removeAttr('disabled');
		$('#add_new_accessories .modal-body').html(response);
		
		init_selectpicker();	
		init_datepicker();
		appValidateForm($('#accessories-form'), {
			'assets_name': 'required',
			'quantity': 'required',
			'category_id': 'required'
		})
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}
function edit_consumables_location(id){
	"use strict";
	$('#add_new_consumables').modal('show');
	$('#add_new_consumables .add-title').addClass('hide');
	$('#add_new_consumables .edit-title').removeClass('hide');
	$('#add_new_consumables button[type="submit"]').attr('disabled', true);
	$('#add_new_consumables input[name="id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_data_consumables_modal/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add_new_consumables .modal-body').html('');
		$('#add_new_consumables button[type="submit"]').removeAttr('disabled');
		$('#add_new_consumables .modal-body').html(response);
		
		init_selectpicker();	
		init_datepicker();
		appValidateForm($('#consumables-form'), {
			'assets_name': 'required',
			'quantity': 'required',
			'category_id': 'required'
		})
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}
function edit_component_location(id){
	"use strict";
	$('#add_new_components').modal('show');
	$('#add_new_components .add-title').addClass('hide');
	$('#add_new_components .edit-title').removeClass('hide');
	$('#add_new_components button[type="submit"]').attr('disabled', true);
	$('#add_new_components input[name="id"]').val(id);
	var requestURL = admin_url+'fixed_equipment/get_data_components_modal/' + (typeof(id) != 'undefined' ? id : '');
	requestGetJSON(requestURL).done(function(response) {
		$('#add_new_components .modal-body').html('');
		$('#add_new_components button[type="submit"]').removeAttr('disabled');
		$('#add_new_components .modal-body').html(response);
		
		init_selectpicker();	
		init_datepicker();
		appValidateForm($('#components-form'), {
			'assets_name': 'required',
			'quantity': 'required'
		})
	}).fail(function(data) {
		alert_float('danger', 'Error');
	});
}