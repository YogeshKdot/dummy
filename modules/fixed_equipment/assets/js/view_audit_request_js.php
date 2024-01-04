<script>
  "use strict";
  var hot;
  var dataObject = [];

  dataObject = <?php echo json_encode($data_hanson); ?>;
  var container = document.getElementById('example');
  hot = new Handsontable(container, {
    data: dataObject,
    contextMenu: false,
    manualRowMove: false,
    manualColumnMove: false,
    stretchH: 'all',
    autoWrapRow: false,
    rowHeights: 30,
    defaultRowHeight: 100,
    minRows: 15,
    maxRows: 400,
    width: '100%',
    height: 500,
    colWidths: [10,100,50,50,50],
    rowHeaders: false,
    autoColumnSize: {
      samplingRatio: 23
    },
    licenseKey: 'non-commercial-and-evaluation',
    filters: false,
    manualRowResize: false,
    manualColumnResize: false,
    allowInsertRow: false,
    allowRemoveRow: false,
    columnHeaderHeight: 30,
    colHeaders: [
    '<?php echo 'ID'; ?>',
    '<?php echo _l('fe_item'); ?>',
    '<?php echo _l('fe_type'); ?>',
    '<?php echo _l('fe_quantity'); ?>',
    '<?php echo _l('fe_broken_state'); ?>'
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
    },
    {
      data: 'maintenance',
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

  function create_maintenance(id) {
    "use strict";
    $('#add_new_assets_maintenances').modal('show');
    $('#add_new_assets_maintenances #asset_id').val(id);
    $('#add_new_assets_maintenances #asset_id').attr('disabled', true).selectpicker('refresh');
    if($('input[name="asset_id"]').length > 0){
      $('input[name="asset_id"]').val(id);
    }
    else{
      $('#add_new_assets_maintenances .modal-body').append('<input type="hidden" name="asset_id" value="'+id+'">');    
    }
  }

  $('#submit').on('click', function() {
    $('input[name="assets_detailt"]').val(JSON.stringify(hot.getData()));   
  });

  appValidateForm($('#create_audit_request-form'), {
   'title': 'required',
   'auditor': 'required'
 })

  $('.lead-top-btn').click(function() {
    $(window).unbind('beforeunload');
  });

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

  appValidateForm($('#assets_maintenances-form'), {
    'asset_id': 'required',
    'supplier_id': 'required',
    'maintenance_type': 'required',
    'start_date': 'required',
    'title': 'required'
  })

  $("input[data-type='currency']").on({
    keyup: function() {        
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
  });
  /**
 * format Number
 */
function formatNumber(n) {
  "use strict";
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

/**
 * format Currency
 */
function formatCurrency(input, blur) {
  "use strict";
  var input_val = input.val();
  if (input_val === "") { return; }
  var original_len = input_val.length;
  var caret_pos = input.prop("selectionStart");
  if (input_val.indexOf(".") >= 0) {
    var decimal_pos = input_val.indexOf(".");
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);
    left_side = formatNumber(left_side);

    right_side = formatNumber(right_side);
    right_side = right_side.substring(0, 2);
    input_val = left_side + "." + right_side;

  } else {
    input_val = formatNumber(input_val);
    input_val = input_val;
  }
  input.val(input_val);
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}
</script>