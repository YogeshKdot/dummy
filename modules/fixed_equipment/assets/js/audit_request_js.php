<script>
  "use strict";
  var hot;
  var dataObject = [];
  var container = document.getElementById('example');
  hot = new Handsontable(container, {
    data: dataObject,
    contextMenu: true,
    manualRowMove: true,
    manualColumnMove: true,
    stretchH: 'all',
    autoWrapRow: true,
    rowHeights: 30,
    defaultRowHeight: 100,
    minRows: 15,
    maxRows: 400,
    width: '100%',
    height: 500,
    colWidths: [50,100,50,50],
    rowHeaders: true,
    autoColumnSize: {
      samplingRatio: 23
    },
    licenseKey: 'non-commercial-and-evaluation',
    filters: true,
    manualRowResize: true,
    manualColumnResize: true,
    allowInsertRow: true,
    allowRemoveRow: true,
    columnHeaderHeight: 30,
    colHeaders: [
    '<?php echo 'ID'; ?>',
    '<?php echo _l('fe_item'); ?>',
    '<?php echo _l('fe_type'); ?>',
    '<?php echo _l('fe_quantity'); ?>',
    ],
    columns: [
    {
      data: 'id',
      type: 'text',
      readOnly: true
    },  
    {
      data: 'item',
      type: 'text',
      readOnly: true
    },  
    {
      data: 'type',
      type: 'text',
      readOnly: true
    },  
    {
      data: 'quantity',
      type: 'text',
      readOnly: true
    }
    ],
    className: "htCenter",
    hiddenColumns: {
      columns: [0],
      indicators: true
    }
  });

  $('#submit').on('click', function() {
    $('input[name="assets_detailt"]').val(JSON.stringify(hot.getData()));   
  });

  appValidateForm($('#create_audit_request-form'), {
   'title': 'required',
   'auditor': 'required'
 })

  function get_data_form(){
    "use strict";
    var data = {};
    data.asset_location = $('select[name="asset_location"]').val();
    data.model_id = $('select[name="model_id"]').val();
    data.asset_id = $('select[name="asset_id[]"]').val();
    data.checkin_checkout_status = $('select[name="checkin_checkout_status"]').val();
    $.post(admin_url+'fixed_equipment/get_data_hanson_audit',data).done(function(response){
      response = JSON.parse(response);
      if(response.success == true) {
        hot.updateSettings({
          data: JSON.parse(response.data_hanson)
        }) 
      }
    }); 
  }

  $('select[name="asset_location"], select[name="model_id"], select[name="asset_id[]"], select[name="checkin_checkout_status"]').change(function(){
    get_data_form();
  });
</script>