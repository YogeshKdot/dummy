   (function(){
    "use strict";
    var fnServerParams = {
      "asset": "[name='asset_id[]']",
      "status": "[name='status_filter']"
    }
    initDataTable('.table-assets_management', admin_url + 'fixed_equipment/depreciation_table', false, false, fnServerParams, [0, 'desc']);
    $( "select[name='asset_id[]'], select[name='status_filter']" ).change(function() {
      $('.table-assets_management').DataTable().ajax.reload()
      .columns.adjust()
      .responsive.recalc();
    });
  })(jQuery);

