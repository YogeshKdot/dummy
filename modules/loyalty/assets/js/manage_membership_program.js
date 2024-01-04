(function($) {
"use strict";
 	var membershipruleServerParams = {  
        "discount": "[name='discount_filter[]']",
        "membership": "[name='membership_filter[]']",
       
    };

   var table_membership_program = $('table.table-table_membership_program');
	initDataTable(table_membership_program, admin_url+'loyalty/table_membership_program', ['undefine'], ['undefine'], membershipruleServerParams);

    $.each(membershipruleServerParams, function(i, obj) {
        $('#discount_filter' + obj).on('change', function() {  
            table_membership_program.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#membership_filter' + obj).on('change', function() {  
            table_membership_program.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

    });

appValidateForm($('#membership_program-form'),{loyalty_point:'required',membership:'required',discount:'required',program_name:'required',
    voucher_code: {
       required: true,
       remote: {
        url: site_url + "admin/loyalty/voucher_code_exists",
        type: 'post',
        data: {
            voucher_code: function() {
                return $('input[name="voucher_code"]').val();
            },
            id: function() {
                return $('input[name="id"]').val();
            }
        }
    }
   }
  });
})(jQuery);


