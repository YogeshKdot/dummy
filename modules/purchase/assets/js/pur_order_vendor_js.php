<script>
var culture = '';
  <?php if(get_option('decimal_separator') == ','){ ?>
    culture = 'de-DE';
  <?php }else if(get_option('decimal_separator') == '.'){ ?>
    culture = 'en-US';
  <?php } ?> 

(function($) {
"use strict";  

var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;
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
        data: 'pur_order',
        type: 'numeric',
    
      },
      {
        data: 'item_code',
        renderer: customDropdownRenderer,
        editor: "chosen",
        width: 100,
        chosenOptions: {
            data: <?php echo json_encode($items); ?>
        },
        readOnly: true

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
        readOnly: true
      },
      {
        data: 'quantity',
        type: 'numeric',
         width: 50,
        readOnly: true
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
        },
        readOnly: true
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
        readOnly: true
      },{
        data: 'discount_%',
        type: 'numeric',
         width: 50,
        renderer: customRenderer,
        readOnly: true
      },
      {
        data: 'discount_money',
        type: 'numeric',
         width: 50,
        numericFormat: {
          pattern: '0,0.00',
          culture: culture
        },
        readOnly: true
      },
      {
        data: 'total_money',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00',
          culture: culture
        },
        readOnly: true
    
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


})(jQuery);  


function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


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

</script>