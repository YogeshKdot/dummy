(function(){
	"use strict";
	$(document).on("change",'.tab-pane.active select[name="client_id"]',function() {
		var total_cart = $('.tab-pane.active input[name="total_cart"]').val();
		var max = 0;
		if(this.value != ''){
			$.post(admin_url + 'loyalty/get_client_info_loy/' +this.value).done(function(response) {
				response = JSON.parse(response);

				if(response.type == 'partial'){
					$('.tab-pane.active span[id="point_span"]').html('');
					$('.tab-pane.active span[id="point_span"]').append(response.point);

					$('.tab-pane.active input[name="data_max"]').val(response.point);
					$('.tab-pane.active input[name="weight"]').val(response.weight);
					
				}else{
					max = (response.max_received*total_cart)/100;
					$('.tab-pane.active span[id="point_span"]').html('');
					$('.tab-pane.active span[id="point_span"]').append(response.point);

					$('.tab-pane.active input[name="redeem_from"]').val(response.val);

					if(response.val_to <= max){
						$('.tab-pane.active input[name="redeem_to"]').val(response.val_to);
					}else{
						$('.tab-pane.active input[name="redeem_to"]').val(max);
					}

					if( $('.tab-pane.active input[name="redeem_from"]').val() != '' &&  $('.tab-pane.active input[name="redeem_to"]').val() != ''){
						$('.tab-pane.active input[name="redeem_from"]').attr(response.disabled,true);
						$('.tab-pane.active input[name="redeem_to"]').attr(response.disabled,true);
					}

					$('.tab-pane.active input[name="weight"]').val(response.weight);
				}

				$('.tab-pane.active input[name="rate_percent"]').val(response.max_received);
				if(response.hide != ''){
					$('.tab-pane.active #div_pos_redeem').addClass(response.hide);
				}else{
					$('.tab-pane.active #div_pos_redeem').removeClass('hide');
				}
			});
		}else{
			$('.tab-pane.active span[id="point_span"]').html('');
			$('.tab-pane.active span[id="point_span"]').append(0);
			$('.tab-pane.active input[name="data_max"]').val(0);
			$('.tab-pane.active input[name="weight"]').val(0);
			$('.tab-pane.active #div_pos_redeem').addClass('hide');
		}
	});

})(jQuery);

/**
 * { auto redeem pos }
 *
 * @param        invoker  The invoker
 */
function auto_redeem_pos(invoker) {
	"use strict";
	var val_to = 0;
	var weight = $('.tab-pane.active input[name="weight"]').val();
	var rate_percent = $('.tab-pane.active input[name="rate_percent"]').val();
	var total_cart = $('.tab-pane.active input[name="total_cart"]').val();
	var max = 0;
	var data_max = $('.tab-pane.active input[name="data_max"]').val();
	if(invoker.value > data_max){
		$('#alert').modal('show').find('.alert_content').text('Point invalid!');
	}else{
		if( $('.tab-pane.active select[name="client_id"]').val() != ''){
			max = (rate_percent*total_cart)/100;
			if(invoker.value != ''){
				val_to = invoker.value*weight;
				if(val_to <= max){
					$('input[name="redeem_to"]').val(round_loy(val_to));
				}else{
					$('input[name="redeem_to"]').val(round_loy(max));
				}
			}
			
		}else{
			$('#alert').modal('show').find('.alert_content').text('Please choose customer!');
		}
	}
}

/**
 * { auto redeem }
 *
 * @param        invoker  The invoker
 * @param        weight   The weight
 */
function auto_redeem(invoker, weight) {
	"use strict";
	var total = $('input[name="total"]').val();
	var val_to = 0;
	var max = 0;
	var rate_percent = $('input[name="rate_percent"]').val();
	if(invoker.value != ''){
		val_to = invoker.value*weight;
	}
	max = (total*rate_percent)/100;

	if(val_to > max){
		$('input[name="redeem_to"]').val(round_loy(max));
	}else{
		$('input[name="redeem_to"]').val(round_loy(val_to));
	}
}

/**
 * Removes a commas.
 *
 * @param        str     The string
 * @return       { string }
 */
function removeCommas(str) {
	"use strict";
	return(str.replace(/,/g,''));
}

/**
 * { redeem order }
 */
function redeem_order(){
	"use strict";
	var val_to = $('input[name="redeem_to"]').val();
	var total = $('input[name="total"]').val();
	var max = 0;
	var rate_percent = $('input[name="rate_percent"]').val();
	max = (total*rate_percent)/100;
	if(val_to != ''){
		if(val_to <= max){
			if($('input[name="voucher"]').val() != ''){
				$('input[name="discount_type"]').val(2);
			}else{
				$('input[name="discount_type"]').val(0);
			}
			
			$('input[name="other_discount"]').val(val_to);
			total_cart();
		}else{
			if($('input[name="voucher"]').val() != ''){
				$('input[name="discount_type"]').val(2);
			}else{
				$('input[name="discount_type"]').val(0);
			}
			$('input[name="other_discount"]').val(round_loy(max));
			$('input[name="redeem_to"]').val(round_loy(max));
			total_cart();
		}
	}else{
		alert_float('warning','Enter the number of points you want to redeem!');
	}
}

/**
 * { redeem pos order }
 */
function redeem_pos_order(){
	"use strict";
	var val_to = $('.tab-pane.active input[name="redeem_to"]').val();
	var total = $('.tab-pane.active input[name="total_cart"]').val();
	var max = 0;
	var rate_percent = $('.tab-pane.active input[name="rate_percent"]').val();
	max = (total*rate_percent)/100;
	if(val_to != ''){
		if(val_to <= max){
			$('input[name="discount_type"]').val(0);
			$('input[name="other_discount"]').val(round_loy(val_to));
			total_cart();
		}else{
			$('input[name="discount_type"]').val(0);
			$('input[name="other_discount"]').val(round_loy(max));
			total_cart();
		}
	}else{
		alert_float('warning','Enter the number of points you want to redeem!');
	}
}

function round_loy(val){
  "use strict";
  return Math.round(val * 100) / 100;
}