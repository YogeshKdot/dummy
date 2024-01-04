<script>
var culture = '';
  <?php if(get_option('decimal_separator') == ','){ ?>
    culture = 'de-DE';
  <?php }else if(get_option('decimal_separator') == '.'){ ?>
    culture = 'en-US';
  <?php } ?>  

function removeCommas(str) {
  "use strict";
  return(str.replace(/,/g,''));
}

function dc_percent_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = (t_mn*invoker.value)/100;

  $('input[name="dc_total"]').val(numberWithCommas(rs));
  $('input[name="after_discount"]').val(numberWithCommas(t_mn - rs));

}

function dc_total_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = t_mn - parseFloat(removeCommas(invoker.value));

   $('input[name="after_discount"]').val(numberWithCommas(rs));
}

<?php if(!isset($estimate)){
 ?> 

function numberWithCommas(x) {
  "use strict";
    x = x.toString().replace('.', "<?php echo get_option('decimal_separator'); ?>");

    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "<?php echo get_option('thousand_separator'); ?>");
}

var dataObject = [
      
    ];
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
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
          width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
          
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50,
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
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
          width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
          readOnly: true
        },
        {
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
      maxRows: 22,
      rowHeaders: true,
      colHeaders: [
        '<?php echo _l('items'); ?>',
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
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
  if(changes !== null){
      changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
        if(prop == 'item_code'){
          $.post(site_url + 'purchase/vendors_portal/items_change/'+newValue).done(function(response){
            response = JSON.parse(response);

            hot.setDataAtCell(row,1, response.value.unit_id);
            hot.setDataAtCell(row,2, response.value.purchase_price);
            hot.setDataAtCell(row,4, response.value.purchase_price*hot.getDataAtCell(row,3));
          });
        }else if(prop == 'quantity'){
          hot.setDataAtCell(row,4, newValue*hot.getDataAtCell(row,2));
          hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,2));
          hot.setDataAtCell(row,10, newValue*hot.getDataAtCell(row,2));
        }else if(prop == 'unit_price'){
          hot.setDataAtCell(row,4, newValue*hot.getDataAtCell(row,3));
          hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,3));
          hot.setDataAtCell(row,10, newValue*hot.getDataAtCell(row,3));
        }else if(prop == 'tax'){
           
            var tax_arr = [];
            var tax_val_arr = [];

            $.post(site_url + 'purchase/vendors_portal/tax_change/'+newValue).done(function(response){
              response = JSON.parse(response);
              hot.setDataAtCell(row,6, (response.total_tax*parseFloat(hot.getDataAtCell(row,4)))/100 );
              hot.setDataAtCell(row,7, (response.total_tax*parseFloat(hot.getDataAtCell(row,4)))/100 + parseFloat(hot.getDataAtCell(row,4)));
              hot.setDataAtCell(row,10, (response.total_tax*parseFloat(hot.getDataAtCell(row,4)))/100 + parseFloat(hot.getDataAtCell(row,4)));
              
              for (var row_i = 0; row_i <= 40; row_i++) { 
                var tax_cell_dt = hot.getDataAtCell(row_i, 5);
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
                  var tax_cell = hot.getDataAtCell(row_i,5);
                  if(tax_cell != '' && tax_cell != null && tax_cell != undefined){
                    if(tax_cell.indexOf(v) != -1){
                      tax_val_arr[k] += (taxrate*parseFloat(hot.getDataAtCell(row_i,4))/100);
                    }
                  }
                }
                
                html += '<tr class="tax-area"><td>'+get_tax_name_by_id(v)+'</td><td width="65%">'+numberWithCommas(Math.round(tax_val_arr[k]*100)/100)+' <?php echo html_entity_decode($base_currency->symbol); ?></td></tr>';
              });

              $('#tax_area_body').html(html);
            });
        }else if(prop == 'discount_%'){
          hot.setDataAtCell(row,9, (newValue*parseFloat(hot.getDataAtCell(row,7)))/100);

        }else if(prop == 'discount_money'){
           hot.setDataAtCell(row,10, (parseFloat(hot.getDataAtCell(row,7)) - newValue));

           var discount_val = 0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseFloat(hot.getDataAtCell(row_index, 9)) > 0){
                discount_val += (parseFloat(hot.getDataAtCell(row_index, 9)));
              }
            }
            $('input[name="dc_total"]').val('-'+numberWithCommas(Math.round(discount_val*100)/100));
        }else if(prop == 'into_money'){
            var grand_tt = 0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseFloat(hot.getDataAtCell(row_index, 4)) > 0){
                grand_tt += (parseFloat(hot.getDataAtCell(row_index, 4)));
              }
            }
             $('input[name="total_mn"]').val(numberWithCommas(Math.round(grand_tt*100)/100));
        }else if(prop == 'total_money'){
         var total_money = 0;
          for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 10)) > 0){
              total_money += (parseFloat(hot.getDataAtCell(row_index, 10)));
            }
          }
          $('input[name="grand_total"]').val(numberWithCommas(Math.round(total_money*100)/100 ));
        }
      }

      });
  }
  });
 function get_hs_data() {
 "use strict";
  $('input[name="estimate_detail"]').val(JSON.stringify(hot.getData()));   
}
<?php } else{ ?>

  function numberWithCommas(x) {
  "use strict";
    x = x.toString().replace('.', "<?php echo get_option('decimal_separator'); ?>");

    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "<?php echo get_option('thousand_separator'); ?>");
}

  var dataObject = <?php echo html_entity_decode($estimate_detail); ?>;
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_estimate',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
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
          width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
          
        },
        {
          data: 'quantity',
          type: 'numeric',
          width: 50,
        },
        {
          data: 'into_money',
          type: 'numeric',
          width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
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
          width: 50,
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
          readOnly: true
        },
        {
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
      maxRows: 22,
      rowHeaders: true,
      colWidths: [0,0,200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '',
        '',
        '<?php echo _l('items'); ?>',
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


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
  if(changes !== null){
      changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue !== ''){
        if(prop == 'item_code'){
          $.post(site_url + 'purchase/vendors_portal/items_change/'+newValue).done(function(response){
            response = JSON.parse(response);

            hot.setDataAtCell(row,3, response.value.unit_id);
            hot.setDataAtCell(row,4, response.value.purchase_price);
            hot.setDataAtCell(row,6, response.value.purchase_price*hot.getDataAtCell(row,5));
          });
        }else if(prop == 'quantity'){
          hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,4));

          var old_tax_value = hot.getDataAtCell(row,8);
          var unit_tax_value = old_tax_value/oldValue;
          if(!isNaN(newValue*unit_tax_value)){
            hot.setDataAtCell(row,8, newValue*unit_tax_value);
          }else{
            hot.setDataAtCell(row,8, 0);
          }

          //hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,4));
          hot.setDataAtCell(row,12, newValue*hot.getDataAtCell(row,4));
        }else if(prop == 'unit_price'){
          hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,5));
          hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,5));
          hot.setDataAtCell(row,12, newValue*hot.getDataAtCell(row,5));
        }else if(prop == 'tax'){
            $.post(site_url + 'purchase/vendors_portal/tax_change/'+newValue).done(function(response){
              response = JSON.parse(response);
              hot.setDataAtCell(row,8, (response.total_tax*parseFloat(hot.getDataAtCell(row,6)))/100 );
              hot.setDataAtCell(row,9, (response.total_tax*parseFloat(hot.getDataAtCell(row,6)))/100 + parseFloat(hot.getDataAtCell(row,6)));
              hot.setDataAtCell(row,12, (response.total_tax*parseFloat(hot.getDataAtCell(row,6)))/100 + parseFloat(hot.getDataAtCell(row,6)));
              
              render_tax_html();
            });
        }else if(prop == 'tax_value'){
          hot.setDataAtCell(row,9, newValue+(hot.getDataAtCell(row,6)) );
          render_tax_html();
        }else if(prop == 'total'){
          hot.setDataAtCell(row,10, 0);
          hot.setDataAtCell(row,11, 0);
          hot.setDataAtCell(row,12, newValue);
        }else if(prop == 'discount_%'){
          hot.setDataAtCell(row,11, (newValue*parseFloat(hot.getDataAtCell(row,9)))/100);

        }else if(prop == 'discount_money'){
           hot.setDataAtCell(row,12, (parseFloat(hot.getDataAtCell(row,9)) - newValue));

           var discount_val = 0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseFloat(hot.getDataAtCell(row_index, 11)) > 0){
                discount_val += (parseFloat(hot.getDataAtCell(row_index, 11)));
              }
            }
            $('input[name="dc_total"]').val('-'+numberWithCommas(Math.round(discount_val*100)/100));
        }else if(prop == 'into_money'){
            var grand_tt = 0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseFloat(hot.getDataAtCell(row_index, 6)) > 0){
                grand_tt += (parseFloat(hot.getDataAtCell(row_index, 6)));
              }
            }
             $('input[name="total_mn"]').val(numberWithCommas(Math.round(grand_tt*100)/100));
        }else if(prop == 'total_money'){
          var total_money = 0;
          for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 12)) > 0){
              total_money += (parseFloat(hot.getDataAtCell(row_index, 12)));
            }
          }
          $('input[name="grand_total"]').val(numberWithCommas(Math.round(total_money*100)/100 ));
        }
      }
      });
  }
  });
function get_hs_data() {
  "use strict";
  $('input[name="estimate_detail"]').val(JSON.stringify(hot.getData()));   
}

<?php } ?>

function render_tax_html(){
  var tax_arr = [];
            var tax_val_arr = [];
  for (var row_i = 0; row_i <= 40; row_i++) { 
    var tax_cell_dt = hot.getDataAtCell(row_i, 7);
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
      var tax_cell = hot.getDataAtCell(row_i,7);
      if(tax_cell != '' && tax_cell != null && tax_cell != undefined){
        if(tax_cell.indexOf(v) != -1){
          tax_val_arr[k] += (taxrate*parseFloat(hot.getDataAtCell(row_i,6))/100);
        }
      }
    }
    
    html += '<tr class="tax-area"><td>'+get_tax_name_by_id(v)+'</td><td width="65%">'+numberWithCommas(Math.round(tax_val_arr[k]*100)/100)+' <?php echo html_entity_decode($base_currency->symbol); ?></td></tr>';
  });

  $('#tax_area_body').html(html);
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