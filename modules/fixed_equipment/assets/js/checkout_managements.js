	     var hidden_columns = [0];
	     var wrapper = document.getElementById("identityConfirmationForm");
	     var canvas = document.getElementById("signature");
	     var clearButton = wrapper.querySelector("[data-action=clear]");
	     var undoButton = wrapper.querySelector("[data-action=undo]");
	     var identityFormSubmit = document.getElementById('identityConfirmationForm');
	     var signaturePad = new SignaturePad(canvas, {
	     	maxWidth: 2,
	     	onEnd:function(){
	     		signaturePadChanged();
	     	}
	     });

	     (function(){
	     	"use strict";
	     	var fnServerParams = {
	     		"location_id": "[name='location_id']",
	     		"staff_id": "[name='staff_id']",
	     		"asset_id": "[name='asset_id']",
	     		"check_type": "[name='check_type']",
	     		"sign_document": "[name='sign_document']",
	     		"from_date": "[name='from_date']",
	     		"to_date": "[name='to_date']"
	     	}
	     	initDataTable('.table-checkout_managements', admin_url + 'fixed_equipment/checkout_management_table', '', '', fnServerParams, [1, 'desc']);
	     	$('.table-checkout_managements').DataTable().columns(hidden_columns).visible(false, false);

	     	$('select[name="location_id"], select[name="staff_id"], select[name="asset_id"], select[name="check_type"], input[name="from_date"], input[name="to_date"], select[name="sign_document"]').change(function(){
	     		var name = $(this).attr('name');
	     		var val = $(this).val();
	     		var _table = $('.table-checkout_managements').DataTable();
	     		var _visible = false;
	     		var staff_id = $('select[name="staff_id"]').val();
	     		if(staff_id != ''){
	     			_visible = true;
	     		}
	     		hidden_columns = [0];
	     		_table.columns(hidden_columns).visible(_visible, false);
	     		_table.ajax.reload()
	     		.columns.adjust();
	     		$('input[name="check"]').val('');
	     	});

	     	$(document).on("click","#mass_select_all",function() {
	     		var favorite = [];
	     		if($(this).is(':checked')){
	     			$('.individual').prop('checked', true);
	     			$.each($(".individual"), function(){ 
	     				favorite.push($(this).data('id'));
	     			});
	     		}else{
	     			$('.individual').prop('checked', false);
	     			favorite = [];
	     		}

	     		$("input[name='check']").val(favorite);
	     	});

	     	$(document).on("change",'select[name="status"]',function() {
	     		var status = $(this).val();
	     		var id = $('input[name="id"]').val();
	     		if(status != '' && id != ''){
	     			$.get(admin_url+'fixed_equipment/change_sign_document_status/'+id+'/'+status).done(function(response){
	     				response = JSON.parse(response);
	     				if(response.success == true) {
	     					alert_float('success', response.message);
	     				}
	     				else{
	     					alert_float('danger', response.message);
	     				}	     				
	     			}).fail(function(data) {

	     			});
	     		}
	     	});

	     	$('select[name="staffid"]').on('change', function(){
	     		var staff_id = $(this).val();
	     		if(staff_id != ''){
	     			$.get(admin_url+'fixed_equipment/get_check_in_out_staff_option/'+staff_id).done(function(response){
	     				$('select[name="check_in_out_id[]"]').html(response);
	     				var check_id = $('input[name="check"]').val();
	     				if(check_id != ''){
	     					var arr_id = check_id.split(',');
	     					$('select[name="check_in_out_id[]"]').val(arr_id).change();
	     				}
	     				$('.selectpicker').selectpicker('refresh');
	     			}).fail(function(data) {

	     			});
	     		}
	     	});

	     	$(window).on('load', function () {
	     		var detail_id = window.location.href.split('#')[1];
	     		if(detail_id !== undefined){
	     			view_detail_sign(detail_id);
	     		}
	     	});

	     	SignaturePad.prototype.toDataURLAndRemoveBlanks = function() {
	     		var canvas = this._ctx.canvas;
       // First duplicate the canvas to not alter the original
       var croppedCanvas = document.createElement('canvas'),
       croppedCtx = croppedCanvas.getContext('2d');

       croppedCanvas.width = canvas.width;
       croppedCanvas.height = canvas.height;
       croppedCtx.drawImage(canvas, 0, 0);

       // Next do the actual cropping
       var w = croppedCanvas.width,
       h = croppedCanvas.height,
       pix = {
       	x: [],
       	y: []
       },
       imageData = croppedCtx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height),
       x, y, index;

       for (y = 0; y < h; y++) {
       	for (x = 0; x < w; x++) {
       		index = (y * w + x) * 4;
       		if (imageData.data[index + 3] > 0) {
       			pix.x.push(x);
       			pix.y.push(y);

       		}
       	}
       }
       pix.x.sort(function(a, b) {
       	return a - b
       });
       pix.y.sort(function(a, b) {
       	return a - b
       });
       var n = pix.x.length - 1;

       w = pix.x[n] - pix.x[0];
       h = pix.y[n] - pix.y[0];
       var cut = croppedCtx.getImageData(pix.x[0], pix.y[0], w, h);

       croppedCanvas.width = w;
       croppedCanvas.height = h;
       croppedCtx.putImageData(cut, 0, 0);

       return croppedCanvas.toDataURL();
   };




   clearButton.addEventListener("click", function(event) {
   	signaturePad.clear();
   	signaturePadChanged();
   });

   undoButton.addEventListener("click", function(event) {
   	var data = signaturePad.toData();
   	if (data) {
           data.pop(); // remove the last dot or line
           signaturePad.fromData(data);
           signaturePadChanged();
       }
   });
   $('#identityConfirmationForm').submit(function() {
   	signaturePadChanged();
   });

})(jQuery);
function checked_add(el){
	var id = $(el).data("id");
	var id_product = $(el).data("product");
	if ($(".individual").length == $(".individual:checked").length) {
		$("#mass_select_all").attr("checked", "checked");
		var value = $("input[name='check']").val();
		if(value != ''){
			value = value + ',' + id;
		}else{
			value = id;
		}
	} else {
		$("#mass_select_all").removeAttr("checked");
		var value = $("input[name='check']").val();
		var value_product = $("input[name='check_product']").val();
		var arr_val = value.split(',');
		if(arr_val.length > 0){
			$.each( arr_val, function( key, value ) {
				if(value == id){
					arr_val.splice(key, 1);
					value = arr_val.toString();
					$("input[name='check']").val(value);
				}
			});
		}
	}
	if($(el).is(':checked')){
		var value = $("input[name='check']").val();
		if(value != ''){
			value = value + ',' + id;
			value_product = value_product + ',' + id_product;
		}else{
			value = id;
			value_product = id_product;
		}
		$("input[name='check']").val(value);
		$("input[name='check_product']").val(value_product);
	}else{
		var value = $("input[name='check']").val();
		var value_product = $("input[name='check_product']").val();
		var arr_val = value.split(',');
		var arr_val_product = value_product.split(',');
		if(arr_val.length > 0){
			$.each( arr_val, function( key, value ) {
				if(value == id){
					arr_val.splice(key, 1);
					value = arr_val.toString();
					$("input[name='check']").val(value);
				}
			});

			$.each( arr_val_product, function( key, value_ ) {
				if(value_ == id_product){
					arr_val_product.splice(key, 1);
					value_ = arr_val_product.toString();
					$("input[name='check_product']").val(value_);
				}
			});
		}
	}
}


function bulk_sign(){
	"use strict";
	var checked_id = $('input[name="check"]').val();
	var data = {};
	data.id_list = checked_id;
	if(checked_id != ''){
		$('#create_sign_document_modal').modal('show');
		$('#create_sign_document_modal select[name="staffid"]').val($('#staff_id').val()).change();
	}
	else{
		alert_float('danger', $('input[name="please_select_at_least_one_item_from_the_list"]').val());
	}
}

function detail_checkout(id){
	$.post(admin_url+'fixed_equipment/get_sign_modal', data).done(function(response){
		$('#sign_modal').modal('show');
		$('#sign_modal .modal-body').html(response);
	}).fail(function(data) {

	});
}
function toggle_small_view(table, main_data) {
	"use strict";
	var tablewrap = $('#small-table');
	if (tablewrap.length === 0) { return; }
	var _visible = false;
	if (tablewrap.hasClass('col-md-5')) {
		tablewrap.removeClass('col-md-5').addClass('col-md-12');
		_visible = true;
		$('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
		$('#check_in_out_detail').removeClass('hide');
		$('#filter').removeClass('hide');
	} else {
		tablewrap.addClass('col-md-5').removeClass('col-md-12');
		$('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
		$('#check_in_out_detail').addClass('hide');
		$('#filter').addClass('hide');
	}
	var _table = $(table).DataTable();
	_table.columns.adjust();
	$(main_data).toggleClass('hide');
	$(window).trigger('resize');
}

function init_pur_order(id) {
	"use strict";
	load_small_table_item(id, '#check_in_out_detail', '.table-checkout_managements');
}

function load_small_table_item(id, url, table) {
	"use strict";
	$(selector).load(admin_url + url + '/' + id);
	if (is_mobile()) {
		$('html, body').animate({
			scrollTop: $(selector).offset().top + 150
		}, 600);
	}
}

function create_sign_document(){
	"use strict";
	$('#create_sign_document_modal').modal('show');
}

function view_detail_sign(detail_id){
	"use strict";
	$.post(admin_url+'fixed_equipment/get_sign_document_detail/'+detail_id).done(function(response){
		$('#check_in_out_detail').html(response);
		$('.selectpicker').selectpicker('refresh');
		$('#small-table').removeClass('col-md-12').addClass('col-md-5');
		$('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
		$('#check_in_out_detail').removeClass('hide');
		$('#filter').addClass('hide');
	}).fail(function(data) {

	});
}

function detail_sign_document(id){
	"use strict";
	var url = window.location.href.split('#')[0];
	if(url !== undefined){
		window.location.replace(url+'#'+id);
		view_detail_sign(id);
	}
}

function staff_sign_document(el, document_id, id){
	"use strict";
	$('#identityConfirmationModal').modal('show');
	$('#identityConfirmationModal input[name="id"]').val(id);
	$('#identityConfirmationModal input[name="document_id"]').val(document_id);
	$('#identityConfirmationModal input[name="firstname"]').val($(el).data('firstname'));
	$('#identityConfirmationModal input[name="lastname"]').val($(el).data('lastname'));
	$('#identityConfirmationModal input[name="email"]').val($(el).data('email'));
}

function signaturePadChanged() {
	"use strict";
	var input = document.getElementById('signatureInput');
	var $signatureLabel = $('#signatureLabel');
	$signatureLabel.removeClass('text-danger');

	if (signaturePad.isEmpty()) {
		$signatureLabel.addClass('text-danger');
		input.value = '';
		return false;
	}

	$('#signatureInput-error').remove();
	var partBase64 = signaturePad.toDataURLAndRemoveBlanks();
	partBase64 = partBase64.split(',')[1];
	input.value = partBase64;
}