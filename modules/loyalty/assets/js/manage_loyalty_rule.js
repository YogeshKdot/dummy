(function($) {
"use strict";   	
    var loyalruleServerParams = {  
        "redeemp_type": "[name='redeemp_type[]']",
        "rule_base": "[name='rule_base[]']",
    };

   var table_loyalty_rule = $('table.table-table_loyalty_rule');
	initDataTable(table_loyalty_rule, admin_url+'loyalty/table_loyalty_rule', ['undefine'], ['undefine'], loyalruleServerParams);

    $.each(loyalruleServerParams, function(i, obj) {
        $('#redeemp_type' + obj).on('change', function() {  
            table_loyalty_rule.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });

        $('#rule_base' + obj).on('change', function() {  
            table_loyalty_rule.DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
    });
})(jQuery);