
(function($) {  
  "use strict";  
  var transationServerParams = {  
    "client": "[name='client_filter[]']",
    "reference": "[name='reference[]']",
};

var table_transation = $('table.table-table_transation');
initDataTable(table_transation, admin_url+'loyalty/table_transation', ['undefine'], ['undefine'], transationServerParams);

$.each(transationServerParams, function(i, obj) {
    $('#client_filter' + obj).on('change', function() {  
        table_transation.DataTable().ajax.reload()
        .columns.adjust()
        .responsive.recalc();
    });

    $('#reference' + obj).on('change', function() {  
        table_transation.DataTable().ajax.reload()
        .columns.adjust()
        .responsive.recalc();
    });
});


var redeemlogServerParams = {  
    "client": "[name='client_filter_rd[]']",
};

var table_rd_log = $('table.table-table_redeem_log');
initDataTable(table_rd_log, admin_url+'loyalty/table_rd_log', ['undefine'], ['undefine'], redeemlogServerParams);

$.each(redeemlogServerParams, function(i, obj) {
    $('#client_filter_rd' + obj).on('change', function() {  
        table_rd_log.DataTable().ajax.reload()
        .columns.adjust()
        .responsive.recalc();
    });

});
})(jQuery);

/**
 * { new transation }
 */
function new_transation() {
    "use strict";
    $('#transation_modal').modal('show');

    $('select[id="client"]').val('').change();
    $('select[id="type"]').val('').change();
    $('input[id="loyalty_point"]').val('');
    $('textarea[id="note"]').val('');
    $('.add-title').removeClass('hide');
    $('.edit-title').addClass('hide');
    $('#additional_transation').html('');
}
