<script>
var hot;
var taxes = [];
  var taxes_val = [];
  var old_row = [];
  var tax_name = [];
  var culture = '';
  <?php if(get_option('decimal_separator') == ','){ ?>
    culture = 'de-DE';
  <?php }else if(get_option('decimal_separator') == '.'){ ?>
    culture = 'en-US';
  <?php } ?>

function removeCommas(str) {
  "use strict";
  var decimal_separator = '<?php echo get_option('decimal_separator'); ?>';

  if(decimal_separator == ','){
    str = str.split('.').join('');
    return(str.replace(',','.'));
  }else{
    return(str.replace(/,/g,''));
  }
}

function dc_percent_change(invoker){
  "use strict";
  var total_mn = $('input[name="grand_total"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = (t_mn*invoker.value)/100;
  var cur_dc = 0;
  <?php if(isset($pur_order)){ ?>
    for (var row_index = 0; row_index <= 40; row_index++) {
      var value = parseFloat(hot.getDataAtCell(row_index, 12))
      if(value !== null && value !== '' && !isNaN(value)){
        cur_dc += value;
      }
    }
  <?php }else{ ?>
    for (var row_index = 0; row_index <= 40; row_index++) {
      var value = parseFloat(hot.getDataAtCell(row_index, 10))
      if(value !== null && value !== '' && !isNaN(value)){
        cur_dc += value;
      }
    }
  <?php } ?>

  var grand_total = t_mn - (parseFloat(rs) + parseFloat(cur_dc)) ;

  $('input[name="grand_total"]').val(numberWithCommas(grand_total));

  $('input[name="dc_total"]').val('-'+numberWithCommas(parseFloat(rs) + parseFloat(cur_dc)));
}

function tax_percent_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = (t_mn*invoker.value)/100;
  var dc_total = $('input[name="dc_total"]').val();
  if(dc_total == ''){
    dc_total = '0';
  }

  var grand_total = t_mn + rs - parseFloat(removeCommas(dc_total));

  $('input[name="tax_order_amount"]').val(numberWithCommas(rs));
  $('input[name="grand_total"]').val(numberWithCommas(grand_total));
}

function dc_total_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = t_mn - parseFloat(removeCommas(invoker.value));

  var tax_order_amount = $('input[name="tax_order_amount"]').val();

  if(tax_order_amount == ''){
    tax_order_amount = '0';
  }

  var grand_total = rs + parseFloat(removeCommas(tax_order_amount));

  $('input[name="grand_total"]').val(numberWithCommas(grand_total));
}

$(function(){
  "use strict";
    validate_purorder_form();
    function validate_purorder_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;

        appValidateForm($(selector), {
            pur_order_name: 'required',
            pur_order_number: 'required',
            order_date: 'required',
            vendor: 'required',
        });
    }


});

<?php if(!isset($pur_order)){ // NEW PO
 ?> 

function estimate_by_vendor(invoker){
  "use strict";
  var po_number = '<?php echo html_entity_decode( $pur_order_number); ?>';
  if(invoker.value != 0){
    $.post(admin_url + 'purchase/estimate_by_vendor/'+invoker.value, {client: $('#clients').val()}).done(function(response){
      response = JSON.parse(response);
      $('select[name="estimate"]').html('');
      $('select[name="estimate"]').append(response.result);
      $('select[name="estimate"]').selectpicker('refresh');
      $('#vendor_data').html('');
      $('#vendor_data').append(response.ven_html);
      <?php if(get_option('po_only_prefix_and_number') != 1){ ?>
      $('input[name="pur_order_number"]').val(po_number+'-'+response.company);
      <?php } ?>
      <?php if(get_purchase_option('item_by_vendor') == 1){ ?>
      hot.updateSettings({ 
         columns: [
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: response.items
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          allowEmpty: false,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 70,
        },
        {
          data: 'quantity',
          type: 'numeric',
           width: 50,
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        }
      
      ],
      });

    <?php } ?>

    });

  }
}

function numberWithCommas(x) {
  "use strict";
    x = x.toString().replace('.', "<?php echo get_option('decimal_separator'); ?>");

    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "<?php echo get_option('thousand_separator'); ?>");
}
var dataObject = [];

  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          allowEmpty: false,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '<?php echo _l('items'); ?>',
        '<?php echo _l('item_description'); ?>',
        '<?php echo _l('pur_unit'); ?>',
        '<?php echo _l('purchase_unit_price'); ?>',
        '<?php echo _l('purchase_quantity'); ?>',
        '<?php echo _l('subtotal_before_tax'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('tax_value'); ?>',
        '<?php echo _l('subtotal_after_tax'); ?>',
        '<?php echo _l('discount(%)').'(%)'; ?>',
        '<?php echo _l('discount(money)'); ?>',
        '<?php echo _l('total'); ?>',
      ],
      columnSorting : true,
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
  if(changes !== null && changes !== undefined){
      changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
          if(prop == 'item_code'){
            $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
              response = JSON.parse(response);
              hot.setDataAtCell(row,1, response.value.long_description);
              hot.setDataAtCell(row,2, response.value.unit_id);
              hot.setDataAtCell(row,3, response.value.purchase_price);
              hot.setDataAtCell(row,5, response.value.purchase_price*hot.getDataAtCell(row,4));
            });
          }else if(prop == 'quantity'){
            hot.setDataAtCell(row,5, newValue*hot.getDataAtCell(row,3));
            hot.setDataAtCell(row,8, newValue*hot.getDataAtCell(row,3));
            hot.setDataAtCell(row,11, newValue*hot.getDataAtCell(row,3));
          }else if(prop == 'unit_price'){
            hot.setDataAtCell(row,5, newValue*hot.getDataAtCell(row,4));
            hot.setDataAtCell(row,8, newValue*hot.getDataAtCell(row,4));
            hot.setDataAtCell(row,11, newValue*hot.getDataAtCell(row,4));
          }else if(prop == 'tax'){
           
            var tax_arr = [];
            var tax_val_arr = [];

            $.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
              response = JSON.parse(response);
              hot.setDataAtCell(row,7, (response.total_tax*parseFloat(hot.getDataAtCell(row,5)))/100 );
              hot.setDataAtCell(row,8, (response.total_tax*parseFloat(hot.getDataAtCell(row,5)))/100 + parseFloat(hot.getDataAtCell(row,5)));
              hot.setDataAtCell(row,11, (response.total_tax*parseFloat(hot.getDataAtCell(row,5)))/100 + parseFloat(hot.getDataAtCell(row,5)));
              
              for (var row_i = 0; row_i <= 40; row_i++) { 
                var tax_cell_dt = hot.getDataAtCell(row_i, 6);
                var tax_t = (tax_cell_dt + "").split("|");
                if(tax_t != "null"){
                  $.each(tax_t, function(i,val){
                    if(tax_arr.indexOf(val) == -1 && val != '' && val != null && val != undefined){
                      tax_arr.push(val);
                     
                    }
                  });
                }
              }

              var html = ''; 
              $.each(tax_arr, function(k, v){
                var taxrate = tax_rate_by_id(v);
                tax_val_arr[k] = 0;
                for (var row_i = 0; row_i <= 40; row_i++) { 
                  var tax_cell = hot.getDataAtCell(row_i,6);
                  if(tax_cell != '' && tax_cell != null && tax_cell != undefined){
                    if(tax_cell.indexOf(v) != -1){
                      tax_val_arr[k] += (taxrate*parseFloat(hot.getDataAtCell(row_i,5))/100);
                    }
                  }
                }
                
                html += '<tr class="tax-area"><td>'+get_tax_name_by_id(v)+'</td><td width="65%">'+numberWithCommas(Math.round(tax_val_arr[k]*100)/100)+' <?php echo html_entity_decode($base_currency->symbol); ?></td></tr>';
              });

              $('#tax_area_body').html(html);
            });
          }else if(prop == 'discount_%'){
            hot.setDataAtCell(row,10, (newValue*parseFloat(hot.getDataAtCell(row,8)))/100 );

          }else if(prop == 'discount_money'){
             hot.setDataAtCell(row,11, (parseFloat(hot.getDataAtCell(row,8)) - newValue));

            var discount_val = 0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseFloat(hot.getDataAtCell(row_index, 10)) > 0){
                discount_val += (parseFloat(hot.getDataAtCell(row_index, 10)));
              }
            }
            $('input[name="dc_total"]').val('-'+numberWithCommas(Math.round(discount_val*100)/100));

          }else if(prop == 'into_money'){
            var grand_tt = 0;

            for (var row_index = 0; row_index <= 40; row_index++) {
              var value = parseFloat(hot.getDataAtCell(row_index, 5))
              if(value !== null && value !== '' && !isNaN(value)){
                grand_tt += value
              }
            }

            $('input[name="total_mn"]').val(numberWithCommas((Math.round(grand_tt*100)/100).toFixed(2)));

          }else if(prop == 'total_money'){
            var total_money = 0;

            for (var row_index = 0; row_index <= 40; row_index++) {
              var value = parseFloat(hot.getDataAtCell(row_index, 11))

              if(value !== null && value !== '' && !isNaN(value)) {
                total_money += value
              }

            }
            
            $('input[name="grand_total"]').val(numberWithCommas((Math.round(total_money*100)/100).toFixed(2)));
          }
        }
      });
  }
  });
$('.save_detail').on('click', function() {
  $('input[name="pur_order_detail"]').val(JSON.stringify(hot.getData()));
  var data_dt = 0;
  for (var row_i = 0; row_i <= 40; row_i++) { 
    var item_dt = hot.getDataAtCell(row_i,0);
    if(item_dt != '' && item_dt != null && item_dt != undefined){
      data_dt++;
    }
  }
  if(data_dt > 0){
    $('#pur_order-form').submit();
  }else{
    alert_float('warning','<?php echo _l('please_enter_data'); ?>')
  }   
});

function coppy_pur_estimate(){
  "use strict";
  var pur_estimate = $('select[name="estimate"]').val();
  if(pur_estimate != ''){
     hot.alter('remove_row',0,hot.countRows ());
      hot.updateSettings({  
        columns: [
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          allowEmpty: false,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        }
      
      ],
     });
    $.post(admin_url + 'purchase/coppy_pur_estimate/'+pur_estimate).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({          
        data: response.result,
        });

        $('input[name="total_mn"]').val(response.subtotal);
        $('input[name="grand_total"]').val(response.total);
        $('input[name="dc_percent"]').val(numberWithCommas(response.dc_percent));
        $('input[name="dc_total"]').val(numberWithCommas(response.dc_total));
        $('#tax_area_body').html(response.tax_html);  
    });

    
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_estimate'); ?>')
  }
}

function coppy_pur_request(){
  "use strict";
  var pur_request = $('select[name="pur_request"]').val();
  if(pur_request != ''){
     hot.alter('remove_row',0,hot.countRows ());
     hot.updateSettings({
        columns: [
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
         
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          allowEmpty: false,
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        }
      
      ],
     });
    $.post(admin_url + 'purchase/coppy_pur_request_for_po/'+pur_request).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });

        $('input[name="total_mn"]').val(response.subtotal);
        $('input[name="grand_total"]').val(response.total);
        $('input[name="dc_percent"]').val(0);
        $('input[name="dc_total"]').val(0);
        $('input[name="tax_order_rate"]').val(0);
        $('input[name="tax_order_amount"]').val(0);
        $('#tax_area_body').html(response.tax_html);    
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_request'); ?>')
  }
}

<?php } else{ ?> // EXISTING PO

function estimate_by_vendor(invoker){
  "use strict";
  var po_number = '<?php echo html_entity_decode( $pur_order_number); ?>';
  if(invoker.value != 0){
    $.post(admin_url + 'purchase/estimate_by_vendor/'+invoker.value).done(function(response){
      response = JSON.parse(response);
      $('select[name="estimate"]').html('');
      $('select[name="estimate"]').append(response.result);
      $('select[name="estimate"]').selectpicker('refresh');
      $('#vendor_data').html('');
      $('#vendor_data').append(response.ven_html);
      $('input[name="pur_order_number"]').val(po_number+'-'+response.company);
    <?php if(get_purchase_option('item_by_vendor') == 1){ ?>
      hot.updateSettings({ 
         columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: response.items
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
         
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          allowEmpty: false,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
      
        }
      
      ],
      });
    <?php } ?>
    });

  }
}

function numberWithCommas(x) {
  "use strict";
    x = x.toString().replace('.', "<?php echo get_option('decimal_separator'); ?>");

    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "<?php echo get_option('thousand_separator'); ?>");
}

function get_data(){
  var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;
  console.log(dataObject);
  return dataObject;
}


var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: get_data(),
      columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          allowEmpty: false,
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
      
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [0,0,200,50,100,50,100,50,100,50,100,100],
      colHeaders: [
        '',
        '',
        '<?php echo _l('items'); ?>',
        '<?php echo _l('item_description'); ?>',
        '<?php echo _l('pur_unit'); ?>',
        '<?php echo _l('purchase_unit_price'); ?>',
        '<?php echo _l('purchase_quantity'); ?>',
        '<?php echo _l('subtotal_before_tax'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('tax_value'); ?>',
        '<?php echo _l('subtotal_after_tax'); ?>',
        '<?php echo _l('discount(%)').'(%)'; ?>',
        '<?php echo _l('discount(money)'); ?>',
        '<?php echo _l('total'); ?>',
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      hiddenColumns: {
        columns: [0,1],
        indicators: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
  if(changes !== null){
      changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
        if(prop == 'item_code'){
          $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
            response = JSON.parse(response);
            hot.setDataAtCell(row,3, response.value.long_description);
            hot.setDataAtCell(row,4, response.value.unit_id);
            hot.setDataAtCell(row,5, response.value.purchase_price);
            hot.setDataAtCell(row,7, response.value.purchase_price*hot.getDataAtCell(row,6));
          });
        }else if(prop == 'quantity'){
          hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,5));
          hot.setDataAtCell(row,10, newValue*hot.getDataAtCell(row,5));
          hot.setDataAtCell(row,13, newValue*hot.getDataAtCell(row,5));
        }else if(prop == 'unit_price'){
          hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,6));
          hot.setDataAtCell(row,10, newValue*hot.getDataAtCell(row,6));
          hot.setDataAtCell(row,13, newValue*hot.getDataAtCell(row,6));
        }else if(prop == 'tax'){
          var tax_arr = [];
            var tax_val_arr = [];

            $.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
              response = JSON.parse(response);
              hot.setDataAtCell(row,9, (response.total_tax*parseFloat(hot.getDataAtCell(row,7)))/100 );
              hot.setDataAtCell(row,10, (response.total_tax*parseFloat(hot.getDataAtCell(row,7)))/100 + parseFloat(hot.getDataAtCell(row,7)));
              hot.setDataAtCell(row,13, (response.total_tax*parseFloat(hot.getDataAtCell(row,7)))/100 + parseFloat(hot.getDataAtCell(row,7)));
              
              for (var row_i = 0; row_i <= 40; row_i++) { 
                var tax_cell_dt = hot.getDataAtCell(row_i, 8);
                var tax_t = (tax_cell_dt + "").split("|");
                if(tax_t != "null"){
                  $.each(tax_t, function(i,val){
                    if(tax_arr.indexOf(val) == -1 && val != '' && val != null && val != undefined){
                      tax_arr.push(val);
                     
                    }
                  });
                }
              }

              var html = ''; 
              $.each(tax_arr, function(k, v){
                var taxrate = tax_rate_by_id(v);
                tax_val_arr[k] = 0;
                for (var row_i = 0; row_i <= 40; row_i++) { 
                  var tax_cell = hot.getDataAtCell(row_i,8);
                  if(tax_cell != '' && tax_cell != null && tax_cell != undefined){
                    if(tax_cell.indexOf(v) != -1){
                      tax_val_arr[k] += (taxrate*parseFloat(hot.getDataAtCell(row_i,7))/100);
                    }
                  }
                }
                
                html += '<tr class="tax-area"><td>'+get_tax_name_by_id(v)+'</td><td width="65%">'+numberWithCommas(Math.round(tax_val_arr[k]*100)/100)+' <?php echo html_entity_decode($base_currency->symbol); ?></td></tr>';
              });

              $('#tax_area_body').html(html);
            });
        }else if(prop == 'discount_%'){
          hot.setDataAtCell(row,12, (newValue*parseFloat(hot.getDataAtCell(row,10)))/100 );

        }else if(prop == 'discount_money'){
            hot.setDataAtCell(row,13, (parseFloat(hot.getDataAtCell(row,10)) - newValue));

            var discount_val = 0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0){
                discount_val += (parseFloat(hot.getDataAtCell(row_index, 12)));
              }
            }
            $('input[name="dc_total"]').val('-'+numberWithCommas(Math.round(discount_val*100)/100));

        }else if(prop == 'into_money'){
          var grand_tt = 0;

          for (var row_index = 0; row_index <= 40; row_index++) {
            var value = parseFloat(hot.getDataAtCell(row_index, 7))

            if(value !== null && value !== '' && !isNaN(value)){
              grand_tt += (parseFloat(hot.getDataAtCell(row_index, 7)));
            }
          }

          $('input[name="total_mn"]').val(numberWithCommas((Math.round(grand_tt*100)/100).toFixed(2)));

        }else if(prop == 'total_money'){
          var total_money = 0;
          for (var row_index = 0; row_index <= 40; row_index++) {
            var value = parseFloat(hot.getDataAtCell(row_index, 13))
            if(value !== null && value !== '' && !isNaN(value)) {
              total_money += (parseFloat(hot.getDataAtCell(row_index, 13)));
            }
          }
          
          $('input[name="grand_total"]').val(numberWithCommas((Math.round(total_money*100)/100).toFixed(2)));
        }
      }
    });
  }
});
$('.save_detail').on('click', function() {
  $('input[name="pur_order_detail"]').val(JSON.stringify(hot.getData()));   
  var data_dt = 0;
  for (var row_i = 0; row_i <= 40; row_i++) { 
    var item_dt = hot.getDataAtCell(row_i,2);
    if(item_dt != '' && item_dt != null && item_dt != undefined){
      data_dt++;
    }
  }
  if(data_dt > 0){
    $('#pur_order-form').submit();
  }else{
    alert_float('warning','<?php echo _l('please_enter_data'); ?>')
  }
});

id = $('select[name="vendor"]').val();
$.post(admin_url + 'purchase/estimate_by_vendor/'+id).done(function(response){
  response = JSON.parse(response);
  $('select[name="estimate"]').html('');
  $('select[name="estimate"]').append(response.result);
  $('select[name="estimate"]').val(<?php echo html_entity_decode($pur_order->estimate); ?>).change();
  $('select[name="estimate"]').selectpicker('refresh');
  $('#vendor_data').html('');
  $('#vendor_data').append(response.ven_html);
  

});



function coppy_pur_estimate(){
  "use strict";
  var pur_estimate = $('select[name="estimate"]').val();
  if(pur_estimate != ''){
     hot.alter('remove_row',0,hot.countRows ());
     hot.updateSettings({ 
         columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
      
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          allowEmpty: false,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
      
        }
      
      ],
      });
    $.post(admin_url + 'purchase/coppy_pur_estimate/'+pur_estimate).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });

        $('input[name="total_mn"]').val(response.subtotal);
        $('input[name="grand_total"]').val(response.total);
        $('input[name="dc_percent"]').val(numberWithCommas(response.dc_percent));
        $('input[name="dc_total"]').val(numberWithCommas(response.dc_total));
        $('#tax_area_body').html(response.tax_html);  
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_estimate'); ?>')
  }
}

function coppy_pur_request(){
  "use strict";
  var pur_request = $('select[name="pur_request"]').val();
  if(pur_request != ''){
     hot.alter('remove_row',0,hot.countRows ());
     hot.updateSettings({ 
         columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
         
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          allowEmpty: false,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
      
        }
      
      ],
      });
    $.post(admin_url + 'purchase/coppy_pur_request_for_po/'+pur_request).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });

        $('input[name="total_mn"]').val(response.subtotal);
        $('input[name="grand_total"]').val(response.total);
        $('input[name="dc_percent"]').val(0);
        $('input[name="dc_total"]').val(0);
        $('input[name="tax_order_rate"]').val(0);
        $('input[name="tax_order_amount"]').val(0);
        $('#tax_area_body').html(response.tax_html);  
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_request'); ?>')
  }
}

<?php } ?>
function customRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if(td.innerHTML != ''){
      td.innerHTML = td.innerHTML + '%'
      td.className = 'htRight';
    }
}

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
    var selectedId;
    var optionsList = cellProperties.chosenOptions.data;
    
    if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
        Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
        return td;
    }

    var values = (value + "").split("|");
    value = [];
    for (var index = 0; index < optionsList.length; index++) {

        if (values.indexOf(optionsList[index].id + "") > -1) {
            selectedId = optionsList[index].id;
            value.push(optionsList[index].label);
        }
    }
    value = value.join(", ");

    Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
    return td;
}

function client_change(el){
  "use strict";

  var client = $(el).val();
  var data = {};
  data.client = client;
  
  $.post(admin_url + 'purchase/inv_by_client', data).done(function(response){
    response = JSON.parse(response);
    $('select[name="sale_invoice"]').html(response.html);
    $('select[name="sale_invoice"]').selectpicker('refresh');
  });
  
}

/**
 * { coppy sale invoice }
 */
function coppy_sale_invoice(){
  "use strict";
  var sale_invoice = $('select[name="sale_invoice"]').val();

  if(sale_invoice != ''){

    hot.alter('remove_row',0,hot.countRows());
    
    $.post(admin_url + 'purchase/coppy_sale_invoice_po/'+sale_invoice).done(function(response){
          response = JSON.parse(response);

          <?php if(isset($pur_order)){ ?>
      hot.updateSettings({ 
         columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          allowEmpty: false,
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          allowEmpty: false,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
      
        }
      
      ],
      });
    <?php }else{ ?>
       hot.updateSettings({ 
         columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: response.items
          }
        },
        {
          data: 'description',
          type: 'text',
           width: 100,
         
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
              multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'tax_value',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 50,
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          width: 70,
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          width: 70,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
           width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          }
      
        }
      
      ],
      });
    <?php } ?>

          hot.updateSettings({          
        data: response.result,
        });

        $('input[name="total_mn"]').val(response.subtotal);
        $('input[name="grand_total"]').val(response.total);
        $('input[name="dc_percent"]').val(0);
        $('input[name="dc_total"]').val(0);
        $('input[name="tax_order_rate"]').val(0);
        $('input[name="tax_order_amount"]').val(0);
        $('#tax_area_body').html(response.tax_html);
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_sale_invoice'); ?>');
  }

}

function get_tax_name_by_id(tax_id){
  "use strict";
  var taxe_arr = <?php echo json_encode($taxes); ?>;
  var name_of_tax = '';
  $.each(taxe_arr, function(i, val){
    if(val.id == tax_id){
      name_of_tax = val.label;
    }
  });
  return name_of_tax;
}

function tax_rate_by_id(tax_id){
  "use strict";
  var taxe_arr = <?php echo json_encode($taxes); ?>;
  var tax_rate = 0;
  $.each(taxe_arr, function(i, val){
    if(val.id == tax_id){
      tax_rate = val.taxrate;
    }
  });
  return tax_rate;
}

</script>