(function($) {
"use strict";
 	var membershipruleServerParams = {  
        "client": "[name='client_filter[]']",
       	"client_group": "[name='client_group_filter[]']"
    };

   var table_membership_rule = $('table.table-table_membership_rule');
	initDataTable(table_membership_rule, admin_url+'loyalty/table_membership_rule', ['undefine'], ['undefine'], membershipruleServerParams);

    $.each(membershipruleServerParams, function(i, obj) {
        $('#client_filter' + obj).on('change', function() {  
            table_membership_rule.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#client_group_filter' + obj).on('change', function() {  
            table_membership_rule.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

    });
})(jQuery);

/**
 * { new mbs rule }
 */
function new_mbs_rule(){
	"use strict";
	$('#mbs_rule').modal('show');
	$('.add-title').removeClass('hide');
	$('.edit-title').addClass('hide');
	$('#additional_mbs_rule').html('');
}

/**
 * { edit membership rule }
 *
 * @param        id       The identifier
 * @param        invoker  The invoker
 */
function edit_mbs_rule(id,invoker){
	"use strict";
	$('#mbs_rule input[id="name"]').val($(invoker).data('name'));
	$('#mbs_rule textarea[id="description"]').val($(invoker).data('description'));
	$('#mbs_rule select[id="card"]').val($(invoker).data('card'));
  	$('#mbs_rule select[id="card"]').change();
  	$('#mbs_rule select[id="client_group"]').val($(invoker).data('client_group')).change();
  	
  	$('#mbs_rule input[id="loyalty_point_from"]').val($(invoker).data('loyalty_point_from'));
	$('#mbs_rule input[id="loyalty_point_to"]').val($(invoker).data('loyalty_point_to'));

  	var _mbs_rule_client = $(invoker).data('client');

	if(typeof(_mbs_rule_client) == "string"){
	    $('#mbs_rule select[name="client[]"]').val( ($(invoker).data('client')).split(',')).change();
	}else{
	    $('#mbs_rule select[name="client[]"]').val($(invoker).data('client')).change();
	}

	$('.add-title').addClass('hide');
	$('.edit-title').removeClass('hide');
	$('#additional_mbs_rule').html('');
	$('#additional_mbs_rule').append(hidden_input('id',id));
	$('#mbs_rule').modal('show');
}

/**
 * { client group change }
 *
 * @param      invoker  The invoker
 */
function client_group_change(invoker){
	"use strict";
	
	$.post(admin_url + 'loyalty/client_group_change/'+invoker.value).done(function(response){
		response = JSON.parse(response);
		$('select[id="client"]').html('');
		$('select[id="client"]').append(response.html);
		$('select[id="client"]').selectpicker('refresh');
	});
	
}