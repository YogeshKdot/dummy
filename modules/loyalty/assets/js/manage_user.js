
(function($) { 
  "use strict";   
    var userServerParams = {  
        "client": "[name='client_filter[]']",
        "client_group": "[name='client_group_filter[]']",
    };

   var table_user = $('table.table-table_user');
  initDataTable(table_user, admin_url+'loyalty/table_user', ['undefine'], ['undefine'], userServerParams);

    $.each(userServerParams, function(i, obj) {
        $('#client_filter' + obj).on('change', function() {  
            table_user.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#client_group_filter' + obj).on('change', function() {  
            table_user.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });
})(jQuery);

