<script>
  "use strict";
  var qrcode_list = [];
  function onScanSuccess(decodedText, decodedResult) {
    // Handle on success condition with the decoded text or result.
    try {
     var result = `${decodedText}`;
     var split_result = result.split('QR code :');
     var split_result2 = split_result[1].split('\n');
     if(split_result2[0] != ''){
      var qr_code = split_result2[0].trim();
      if(!qrcode_list.includes(qr_code)){

        qrcode_list.push(qr_code);
        var data = {};
        data.qrcode = qr_code;
        $.post(admin_url + 'fixed_equipment/get_asset_info_from_qr_code/', data).done(function(response){
          response = JSON.parse(response);
          if(response.success == true){
            $('#scanned-result').prepend(response.html);     
            //Update Adjust column for item on hansontable  
            var hanson_data = JSON.parse(JSON.stringify(hot.getData()));
            $.each(hanson_data, function(index, value) {
              if($.isNumeric(value[0])){
                if(parseInt(response.id) == parseInt(value[0])){
                  hot.setDataAtCell(index, 4, 1);
                }
              }
              else{
                return false;
              }
            });
          }
        });
      }
    }
  }
  catch(err) {

  }
}

var html5QrcodeScanner = new Html5QrcodeScanner(
  "reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess);

var hot;
var dataObject = [];
dataObject = <?php echo json_encode($data_hanson); ?>;
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
  colWidths: [50,100,50,50,50,20,20],
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
  '<?php echo _l('fe_adjust'); ?>',
  '<?php echo _l('fe_broken_state'); ?>',
  '<?php echo _l('fe_accept'); ?>'
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
    data: 'adjust',
    type: 'text'
  },
 {
    data: 'maintenance',
    type: 'text'
  },
  {
    data: 'accept',
    type: 'checkbox',
    checkedTemplate: 1,
    uncheckedTemplate: 0,
  }
  ],
  className: "htCenter",
  hiddenColumns: {
    columns: 
    <?php 
    if(!$is_approver){ ?>
      [0,6],
    <?php }else{ ?>
      [0],
    <?php } ?>
    indicators: true
  }
});


$("#close_audit_request-form").submit(function( event ) {
  $('#submit').text('<?php echo _l('fe_waiting'); ?>').attr('disabled', true);
  if($('select[name="approver"]').length > 0){
    var id =  $('input[name="id"]').val();
    var approver =  $('select[name="approver"]').val();
    if(approver == ''){
      alert_float('warning', '<?php echo _l('fe_please_choose_approver'); ?>');
      $('#submit').text('<?php echo _l('fe_submit'); ?>').removeAttr('disabled');
      event.preventDefault();
    }
    var data = {};
    data.id = id;
    data.approver = approver;
    $.post(admin_url + 'fixed_equipment/choose_approver_request_close_audit/', data).done(function(response){
      response = JSON.parse(response);
    });
  }
  $('input[name="assets_detailt"]').val(JSON.stringify(hot.getData()));   
});

$('.scan_qrcode').on('click', function(){
  $('#scan_qr_code_modal').modal('show');
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

// Approve

function approve_request(id){
  "use strict";
  change_request_approval_status(id,1);
}

function deny_request(id){
  "use strict";
  change_request_approval_status(id,2);
}

function change_request_approval_status(id, status){
  "use strict";
  $(window).unbind('beforeunload');
  var data = {};
  data.rel_id = id;
  data.rel_type = 'close_audit';
  data.approve = status;
  data.note = $('textarea[name="reason"]').val();
  data.data_hanson = JSON.stringify(hot.getData());
  $.post(admin_url + 'fixed_equipment/approve_request_close_audit/' + id, data).done(function(response){
    response = JSON.parse(response);
    if (response.success === true || response.success == 'true') {
      alert_float('success', response.message);
      window.location.reload();
    }
    else{
      alert_float('danger', response.message);
      window.location.reload();
    }
  });
}


</script>

