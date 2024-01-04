
	 "use strict";

   window.addEventListener('load',function(){
   	appValidateForm($('#product-category-modal'), {
        p_category_name: 'required',
        p_category_description: 'required'
	}, manage_product_category);
		$('#product_category_modal').on('show.bs.modal', function(e) {
			var invoker = $(e.relatedTarget);
			var group_id = $(invoker).data('id');
			$('#product_category_modal .add-title').removeClass('hide');
			$('#product_category_modal .edit-title').addClass('hide');
			$('#product_category_modal input[name="p_category_id"]').val('');
			$('#product_category_modal input[name="p_category_name"]').val('');
			$('#product_category_modal :input[name="p_category_description"]').val('');
	        if (typeof(group_id) !== 'undefined') {
	            $('#product_category_modal input[name="p_category_id"]').val(group_id);
	            $('#product_category_modal .add-title').addClass('hide');
	            $('#product_category_modal .edit-title').removeClass('hide');
	            $('#product_category_modal input[name="p_category_name"]').val($(invoker).parents('tr').find('td').eq(0).text());
	            $('#product_category_modal :input[name="p_category_description"]').val($(invoker).parents('tr').find('td').eq(1).text());
	        }
    	});
   });
	function manage_product_category(form) {
	    var data = $(form).serialize();
	    var url = form.action;
	    $.post(url, data).done(function(response) {
	        response = JSON.parse(response);
	        if (response.success == true) {
	            if($.fn.DataTable.isDataTable('.table-product-category')){
	                $('.table-product-category').DataTable().ajax.reload();
	            }
	            alert_float('success', response.message);
	            $('#product_category_modal').modal('hide');
	        } else {
	            alert_float('danger', response.message);
	        }
	    });
	    return false;
	}