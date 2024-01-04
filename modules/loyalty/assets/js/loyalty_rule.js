(function($) {	
  "use strict";
  $("input[data-type='currency']").on({
      keyup: function() {        
        formatCurrency($(this));
      },
      blur: function() { 
        formatCurrency($(this), "blur");
      }
  });

  var addnewkpi = $('.new-kpi-al').children().length;
  $("body").on('click', '.new_kpi', function() {

      var idrow = $(this).parents('.new-kpi-al').find('.get_id_row').attr("value");
         if ($(this).hasClass('disabled')) { return false; }

        var newkpi = $(this).parents('.new-kpi-al').find('#new_kpi').eq(0).clone().appendTo($(this).parents('.new-kpi-al'));

        newkpi.find('button[data-toggle="dropdown"]').remove();

        newkpi.find('select[id="product_category[0]"]').attr('name', 'product_category[' + addnewkpi + ']').val('');
        newkpi.find('select[id="product_category[0]"]').attr('id', 'product_category[' + addnewkpi + ']').val('');

        newkpi.find('input[id="point[0]"]').attr('name', 'point[' + addnewkpi + ']').val('');
        newkpi.find('input[id="point[0]"]').attr('id', 'point[' + addnewkpi + ']').val('');

        newkpi.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-remove');
        newkpi.find('button[name="add"]').removeClass('new_kpi').addClass('remove_kpi').removeClass('btn-success').addClass('btn-danger');

        newkpi.find('select').selectpicker('val', '');
        addnewkpi++;

    });

    $("body").on('click', '.remove_kpi', function() {
        $(this).parents('#new_kpi').remove();
    });


  var addnewrule = $('.new-product-rule').children().length;
  $("body").on('click', '.new_rule', function() {
    
      var idrow = $(this).parents('.new-product-rule').find('.get_id_row').attr("value");
         if ($(this).hasClass('disabled')) { return false; }

        var newkpi = $(this).parents('.new-product-rule').find('#new_rule').eq(0).clone().appendTo($(this).parents('.new-product-rule'));

        newkpi.find('button[data-toggle="dropdown"]').remove();

        newkpi.find('select[id="product[0]"]').attr('name', 'product[' + addnewrule + ']').val('');
        newkpi.find('select[id="product[0]"]').attr('id', 'product[' + addnewrule + ']').val('');

        newkpi.find('input[id="point_product[0]"]').attr('name', 'point_product[' + addnewrule + ']').val('');
        newkpi.find('input[id="point_product[0]"]').attr('id', 'point_product[' + addnewrule + ']').val('');

        newkpi.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-remove');
        newkpi.find('button[name="add"]').removeClass('new_rule').addClass('remove_rule').removeClass('btn-success').addClass('btn-danger');

        newkpi.find('select').selectpicker('val', '');
        addnewrule++;

    });

    $("body").on('click', '.remove_rule', function() {
        $(this).parents('#new_rule').remove();
    });


    var addnewredemp = $('.new-redemp-calcu').children().length;
  $("body").on('click', '.new_redemp', function() {
    
      var idrow = $(this).parents('.new-redemp-calcu').find('.get_id_row').attr("value");
         if ($(this).hasClass('disabled')) { return false; }

        var newkpi = $(this).parents('.new-redemp-calcu').find('#new_redemp').eq(0).clone().appendTo($(this).parents('.new-redemp-calcu'));

        newkpi.find('button[data-toggle="dropdown"]').remove();

        newkpi.find('select[id="status[0]"]').attr('name', 'status[' + addnewredemp + ']').val('');
        newkpi.find('select[id="status[0]"]').attr('id', 'status[' + addnewredemp + ']').val('');

        newkpi.find('input[id="rule_name[0]"]').attr('name', 'rule_name[' + addnewredemp + ']').val('');
        newkpi.find('input[id="rule_name[0]"]').attr('id', 'rule_name[' + addnewredemp + ']').val('');

        newkpi.find('input[id="point_from[0]"]').attr('name', 'point_from[' + addnewredemp + ']').val('');
        newkpi.find('input[id="point_from[0]"]').attr('id', 'point_from[' + addnewredemp + ']').val('');

        newkpi.find('input[id="point_to[0]"]').attr('name', 'point_to[' + addnewredemp + ']').val('');
        newkpi.find('input[id="point_to[0]"]').attr('id', 'point_to[' + addnewredemp + ']').val('');

        newkpi.find('input[id="point_weight[0]"]').attr('name', 'point_weight[' + addnewredemp + ']').val('');
        newkpi.find('input[id="point_weight[0]"]').attr('id', 'point_weight[' + addnewredemp + ']').val('');

        newkpi.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-remove');
        newkpi.find('button[name="add"]').removeClass('new_redemp').addClass('remove_redemp').removeClass('btn-success').addClass('btn-danger');

        newkpi.find('select').selectpicker('val', '');
        addnewredemp++;

    });

    $("body").on('click', '.remove_redemp', function() {
        $(this).parents('#new_redemp').remove();
    });

    var rule_base = $('select[id="rule_base"]').val();
    if(rule_base != 'card_total'){
      $('#card_total_rule_div').addClass('hide');
    if(rule_base == 'product_category'){
      $('#product_category_rule_div').removeClass('hide');
      $('#product_rule_div').addClass('hide');
    }else if(rule_base == 'product'){
      $('#product_rule_div').removeClass('hide');
      $('#product_category_rule_div').addClass('hide');
    }

  }else{
    $('#card_total_rule_div').removeClass('hide');
    $('#product_category_rule_div').addClass('hide');
    $('#product_rule_div').addClass('hide');
  }
  
})(jQuery);

/**
 * { formatNumber }
 *
 * @param               { string }
 * @return       { string }
 */
function formatNumber(n) {
  "use strict";
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

/**
 * formatCurrency 
 *
 * @param        input   The input
 * @param        blur    The blur
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

/**
 * { rule base change }
 *
 * @param       invoker  The invoker
 */
function rule_base_change(invoker) {
  "use strict";
  if(invoker.value != 'card_total'){
    $('#card_total_rule_div').addClass('hide');
    if(invoker.value == 'product_category'){
      $('#product_category_rule_div').removeClass('hide');
      $('#product_rule_div').addClass('hide');
    }else if(invoker.value == 'product'){
      $('#product_rule_div').removeClass('hide');
      $('#product_category_rule_div').addClass('hide');
    }

  }else{
    $('#card_total_rule_div').removeClass('hide');
    $('#product_category_rule_div').addClass('hide');
    $('#product_rule_div').addClass('hide');
  }
}

/**
 * { client group change }
 *
 * @param      invoker  The invoker
 */
function client_group_change(invoker){
  "use strict";
  
  $.post(admin_url + 'loyalty/client_group_change/'+invoker.value).done(function(response){
    response = JSON.parse(response);
    $('select[id="client"]').html('');
    $('select[id="client"]').append(response.html);
    $('select[id="client"]').selectpicker('refresh');
  });
  
}