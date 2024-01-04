
(function($) {
	"use strict";
	appValidateForm($('#membership_program-form'),{loyalty_point:'required',membership:'required',discount:'required',program_name:'required',
		voucher_code: {
			required: true,
			remote: {
				url: site_url + "admin/loyalty/voucher_code_exists",
				type: 'post',
				data: {
					voucher_code: function() {
						return $('input[name="voucher_code"]').val();
					},
					id: function() {
						return $('input[name="id"]').val();
					}
				}
			}
		}
	});

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

		newkpi.find('input[id="percent_cate[0]"]').attr('name', 'percent_cate[' + addnewkpi + ']').val('');
		newkpi.find('input[id="percent_cate[0]"]').attr('id', 'percent_cate[' + addnewkpi + ']').val('');

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

		newkpi.find('input[id="percent_product[0]"]').attr('name', 'percent_product[' + addnewrule + ']').val('');
		newkpi.find('input[id="percent_product[0]"]').attr('id', 'percent_product[' + addnewrule + ']').val('');

		newkpi.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-remove');
		newkpi.find('button[name="add"]').removeClass('new_rule').addClass('remove_rule').removeClass('btn-success').addClass('btn-danger');

		newkpi.find('select').selectpicker('val', '');
		addnewrule++;

	});

	$("body").on('click', '.remove_rule', function() {
		$(this).parents('#new_rule').remove();
	});


	var discount = $('select[id="discount"]').val();
	if(discount != 'card_total'){
		$('#card_total_rule_div').addClass('hide');
		if(discount == 'product_category'){
			$('#product_category_rule_div').removeClass('hide');
			$('#product_rule_div').addClass('hide');
		}else if(discount == 'product'){
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
 * { discount change }
 *
 * @param        invoker  The invoker
 */
function discount_change(invoker) {
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
 * { formatNumber }
 *
 * @param             { string }
 * @return       { string }
 */
function formatNumber(n) {
	"use strict"; 
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
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
