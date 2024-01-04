(function($) {
"use strict";
 	var permissionrpServerParams = {  
        "staff": "[name='staff_filter[]']",
       	"category": "[name='category_filter[]']",
        "type": "[name='type_filter[]']"
    };

   var table_permission_rp = $('table.table-table_permission_rp');
	initDataTable(table_permission_rp, admin_url+'team_password/table_permission_rp', ['undefine'], ['undefine'], permissionrpServerParams);

    $.each(permissionrpServerParams, function(i, obj) {
        $('#staff_filter' + obj).on('change', function() {  
            table_permission_rp.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#category_filter' + obj).on('change', function() {  
            table_permission_rp.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#type_filter' + obj).on('change', function() {  
            table_permission_rp.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });


    var shareServerParams = {  
        "client": "[name='contact_filter[]']",
        "effective_time": "[name='effective_time']",
        "type": "[name='type_sh_filter[]']"
    };

   var table_share_rp = $('table.table-table_share_rp');
    initDataTable(table_share_rp, admin_url+'team_password/table_share_rp', ['undefine'], ['undefine'], shareServerParams);

    $.each(shareServerParams, function(i, obj) {
        $('#contact_filter' + obj).on('change', function() {  
            table_share_rp.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#effective_time' + obj).on('change', function() {  
            table_share_rp.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#type_sh_filter' + obj).on('change', function() {  
            table_share_rp.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });
})(jQuery);