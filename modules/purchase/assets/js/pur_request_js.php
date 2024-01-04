<script>
var hot;
var taxes = [];
var culture = '';
  <?php if(get_option('decimal_separator') == ','){ ?>
    culture = 'de-DE';
  <?php }else if(get_option('decimal_separator') == '.'){ ?>
    culture = 'en-US';
  <?php } ?>

(function($) {
"use strict";  

appValidateForm($('#add_edit_pur_request-form'),{pur_rq_code:'required', pur_rq_name:'required', department:'required'});
<?php if(isset($pur_request)){
 ?>
 taxes = <?php echo json_encode($taxes_data['taxes']); ?>;

var dataObject = <?php echo html_entity_decode($pur_request_detail); ?>;
var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'prd_id',
          type: 'numeric',
      
        },
        {
          data: 'pur_request',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
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
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
      
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
        '<?php echo ''; ?>',
        '<?php echo ''; ?>',
        '<?php echo _l('items'); ?>',
        '<?php echo _l('pur_unit'); ?>',
        '<?php echo _l('purchase_unit_price'); ?>',
        '<?php echo _l('purchase_quantity'); ?>',
        '<?php echo _l('subtotal_before_tax'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('tax_value'); ?>',
        '<?php echo _l('total'); ?>',
        '<?php echo _l('inventory_quantity'); ?>'
        
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
        columns: [0,1,10],
        indicators: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
  if(changes !== null && changes !== undefined){
    changes.forEach(([row, prop, oldValue, newValue]) => {
      if(prop == 'item_code'){
        if(is_Numeric(newValue) == true || is_Numeric(newValue) === true){
          $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
            response = JSON.parse(response);

            hot.setDataAtCell(row,3, response.value.unit_id);
            hot.setDataAtCell(row,4, response.value.purchase_price);
            hot.setDataAtCell(row,6, response.value.purchase_price*hot.getDataAtCell(row,5));
            hot.setDataAtCell(row,10, response.value.inventory);
          });
        }
      }else if(prop == 'quantity'){
        hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,4));
        hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,4));
      }else if(prop == 'unit_price'){
        hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,5));
        hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,5));
      }else if(prop == 'tax'){
           
            var tax_arr = [];
            var tax_val_arr = [];

            $.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
              response = JSON.parse(response);
              var tax_value = (response.total_tax*parseFloat(hot.getDataAtCell(row,6)))/100;
              hot.setDataAtCell(row,9, (response.total_tax*parseFloat(hot.getDataAtCell(row,6)))/100 + parseFloat(hot.getDataAtCell(row,6)));
              hot.setDataAtCell(row,8, tax_value);
              
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
            });
      }else if(prop == 'total'){
        var total_money = 0;

        for (var row_index = 0; row_index <= 40; row_index++) {
          var value = parseFloat(hot.getDataAtCell(row_index, 9));

          if(value !== null && value !== '' && !isNaN(value)){
            total_money += value;
          }
        }

        $('input[name="total_mn"]').val(numberWithCommas((Math.round(total_money*100)/100).toFixed(2)));
      }else if(prop == 'into_money'){
        var subtotal_mn = 0;

        for (var row_index = 0; row_index <= 40; row_index++){
          var value = parseFloat(hot.getDataAtCell(row_index, 6));

          if(value !== null && value !== '' && !isNaN(value)){
            subtotal_mn += value;
          }

        }
        $('input[name="subtotal"]').val(numberWithCommas((Math.round(subtotal_mn*100)/100).toFixed(2)));
      }

    });
  }
  });
$('.save_detail').on('click', function() {
  $('input[name="request_detail"]').val(JSON.stringify(hot.getData()));   
});

var checked = $('input[id="from_items"]:checked').length;
if(checked <= 0){
  hot.updateSettings({
       columns: [
        {
          data: 'prd_id',
          type: 'numeric',
      
        },
        {
          data: 'pur_request',
          type: 'numeric',
      
        },
        {
          data: 'item_text',
          
          width: 150,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
      
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
      
      ],  
  });
}else{
  hot.updateSettings({
    columns: [
        {
          data: 'prd_id',
          type: 'numeric',
      
        },
        {
          data: 'pur_request',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
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
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
      
      ],
  });
}

<?php }else{ ?>
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
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 100,
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
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          width: 100,
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },{
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
       
      
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
        '<?php echo _l('total'); ?>',
        '<?php echo _l('inventory_quantity'); ?>'
        
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
        columns: [8],
        indicators: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
  if(changes !== null && changes !== undefined){
    changes.forEach(([row, prop, oldValue, newValue]) => {
      if(prop == 'item_code'){
        if(is_Numeric(newValue) == true || is_Numeric(newValue) === true){
          $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
            response = JSON.parse(response);

            hot.setDataAtCell(row,1, response.value.unit_id);
            hot.setDataAtCell(row,2, response.value.purchase_price);
            hot.setDataAtCell(row,4, response.value.purchase_price*hot.getDataAtCell(row,3));
            hot.setDataAtCell(row,8, response.value.inventory);
          });
        }
      }else if(prop == 'quantity'){
        hot.setDataAtCell(row,4, newValue*hot.getDataAtCell(row,2));
        hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,2));
      }else if(prop == 'unit_price'){
        hot.setDataAtCell(row,4, newValue*hot.getDataAtCell(row,3));
        hot.setDataAtCell(row,7, newValue*hot.getDataAtCell(row,3));
      }else if(prop == 'tax'){
           
            var tax_arr = [];
            var tax_val_arr = [];

            $.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
              response = JSON.parse(response);
              var tax_value = (response.total_tax*parseFloat(hot.getDataAtCell(row,4)))/100;
              hot.setDataAtCell(row,7, (response.total_tax*parseFloat(hot.getDataAtCell(row,4)))/100 + parseFloat(hot.getDataAtCell(row,4)));
              hot.setDataAtCell(row,6, tax_value);
              
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
      }else if(prop == 'total'){
        var total_money = 0;

        for (var row_index = 0; row_index <= 40; row_index++) {
          var value = parseFloat(hot.getDataAtCell(row_index, 7));

          if(value !== null && value !== '' && !isNaN(value)){
            total_money += value;
          }
        }

        $('input[name="total_mn"]').val(numberWithCommas((Math.round(total_money*100)/100).toFixed(2)));

      }else if(prop == 'into_money'){
        var subtotal_mn = 0;

        for (var row_index = 0; row_index <= 40; row_index++){
          var value = parseFloat(hot.getDataAtCell(row_index, 4));

          if(value !== null && value !== '' && !isNaN(value)){
            subtotal_mn += value;
          }
        }
        $('input[name="subtotal"]').val(numberWithCommas((Math.round(subtotal_mn*100)/100).toFixed(2)));
      }

    });
  }
  });
  
  /*hot.addHook('afterRemoveRow', function(removes, src) { 
    console.log(removes);
    
  });
*/  
$('.save_detail').on('click', function() {
  $('input[name="request_detail"]').val(JSON.stringify(hot.getData()));   
});
<?php } ?>
})(jQuery); 

function is_Numeric(num) {
  "use strict";
  return !isNaN(parseFloat(num)) && isFinite(num);
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

function numberWithCommas(x) {
  "use strict";
    x = x.toString().replace('.', "<?php echo get_option('decimal_separator'); ?>");

    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "<?php echo get_option('thousand_separator'); ?>");
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

function department_change(invoker){
  "use strict";
  if(invoker.value != ''){
    $.post(admin_url + 'purchase/dpm_name_in_pur_request_number/'+invoker.value).done(function(response){
      response = JSON.parse(response);
      $('#pur_rq_code').html('');
      $('#pur_rq_code').val('<?php echo html_entity_decode($pur_rq_code); ?>-' + response.rs);
    });
  }
}

function from_list_items(){
  "use strict";
   var checked = $('input[id="from_items"]:checked').length;
   <?php if(isset($pur_request)){ ?>
    if(checked <= 0){
    hot.updateSettings({
            columns: [
        {
          data: 'prd_id',
          type: 'numeric',
      
        },
        {
          data: 'pur_request',
          type: 'numeric',
      
        },    
        {
          data: 'item_code',
         
          
          width: 150,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },

        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
      
      ],
          });
   }else{
      hot.updateSettings({
        columns: [
        {
          data: 'prd_id',
          type: 'numeric',
      
        },
        {
          data: 'pur_request',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
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
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
      
      ],
          });
   }
   <?php }else{ ?> 
   if(checked <= 0){
    hot.updateSettings({
            columns: [
        {
          data: 'item_code',
         
          
          width: 150,
          
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },

        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
      
      ],
          });
   }else{
      hot.updateSettings({
        columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
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
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
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
      
          width: 100,
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
           width: 90,
          readOnly: true
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: culture
          },
           width: 90,
          readOnly: true
        },
        {
          data: 'inventory_quantity',
          type: 'numeric',
          readOnly: true
        },
      
      ],
          });
   }

 <?php } ?>
}

/**
 * { coppy sale invoice }
 */
function coppy_sale_invoice(){
  "use strict";
  var sale_invoice = $('select[name="sale_invoice"]').val();

  if(sale_invoice != ''){
    $('input[id="from_items"]').prop("checked", false);
    from_list_items();

    hot.alter('remove_row',0,hot.countRows());
    $.post(admin_url + 'purchase/coppy_sale_invoice/'+sale_invoice).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({          
        data: response.result,
        });

        taxes = response.taxes;
        $('input[name="subtotal"]').val(response.subtotal);
        $('input[name="total_mn"]').val(response.total);
        $('#tax_area_body').html(response.tax_html);
          
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_sale_invoice'); ?>');
  }

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