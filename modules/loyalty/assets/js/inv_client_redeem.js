(function(){
  "use strict";
  $('input[name="voucher"]').on('change', function(){ 
  	var voucher = $(this).val();
  	if(voucher != ''){
  		var id = $('input[name="clientid"]').val();
  		if(id != '' && id != null && id != undefined){
	        var data = {};
	        data.voucher = voucher; 
	        data.clientid = id;

	        var total = $('input[name="total"]').val();
	        var old_discount = $('input[name="discount_total"]').val();
	        var symbol = $('input[name="symbol"]').val();

	        $.post(site_url + 'loyalty/loyalty_portal/voucher_apply', data).done(function(response){
	        	response = JSON.parse(response);
	        	if(response.rs != null && response.rs != '' && response.rs != undefined){

	        		//Check minium order apply mbs program
	        		var  test = 0;
		            if(parseFloat(response.rs.minimum_order_value)>0){
		                if(total >= parseFloat(response.rs.minimum_order_value)){
		                  test = 0;
		                }else{
		                  test = 1;	
		                }
		            }

		            if(test == 0){
		            	var total_price_discount = 0;
	                    var new_total = $('input[name="new_total"]').val();
	                    var new_discount = $('input[name="new_discount"]').val();
		            	if(response.rs.formal == 1){ 

		            		total_price_discount = parseFloat(((total * response.rs.discount)/100));
	                      	if(new_discount != 0){
	                      		new_discount = parseFloat(new_discount) + round_loy(total_price_discount);
	                      	}else{
	                      		new_discount = round_loy(parseFloat(old_discount) + total_price_discount);
	                      	}

	                      	new_total = round_loy(parseFloat(total) - new_discount);
		            	}else if(response.rs.formal == 2){
		            		total_price_discount = parseFloat(response.rs.discount);
	                        if(new_discount != 0){
	                      		new_discount = parseFloat(new_discount) + round_loy(total_price_discount);
	                      	}else{
	                      		new_discount = round_loy(parseFloat(old_discount) + total_price_discount);
	                      	}

	                        new_total = round_loy(parseFloat(total) - new_discount);
		            	}

		            	$('input[name="voucher_value"]').val(total_price_discount);

		            	$('input[name="new_discount"]').val(new_discount);
						$('input[name="new_total"]').val(new_total);

						if($('td').hasClass('discount')){
							$('.discount').html('-'+symbol+numberWithCommas(new_discount)+' '+'<a href="javascript:void(0)" onclick="view_detail_discount(); return false;" ><i class="fa fa-question-circle"></i></a>');
						}else{
							var discount_row = '<tr><td><span class="bold">Discount</span></td><td class="discount">-'+symbol+numberWithCommas(new_discount)+' '+'<a href="javascript:void(0)" onclick="view_detail_discount(); return false;" ><i class="fa fa-question-circle"></i></a>'+'</td></tr>';
							$('#subtotal').after(discount_row);
						}

						$('.total').html(symbol+numberWithCommas(new_total));

						$('input[name="voucher"]').attr('readonly', 'true');
						$('.redeem_inv_btn').removeAttr('disabled');
						alert_float('success','Voucher applied');

		            }else{
		            	alert_float('warning','Your order is not eligible for this code');
		            }

	        	}else{
	        		alert_float('warning', 'voucher does not exist');
	        	}
	        });
	    }
  	}else{
  		alert_float('warning','Please enter the voucher code');
  	}

  });

  get_mbs_discount();
})(jQuery);


/**
 * Gets the infor item.
 *
 * @param         id      The identifier
 * @return       The infor item.
 */
function get_infor_item(id){
    "use strict";
    var data_result = {};
    var list_id = $('input[name="list_id_product"]').val();
    var list_qty = $('input[name="list_qty_product"]').val();
    var list_price = $('input[name="list_prices_product"]').val();  
    if(list_id != ''){
        var id_list = JSON.parse('['+list_id+']');
        var qty_list = JSON.parse('['+list_qty+']');
        var price_list = JSON.parse('['+list_price+']');

        var index_id = -1;
          $.each(id_list, function( key, value ) {
            if(value == id){
              index_id = key;
            }
        }); 
        var qty = 0;
          $.each(qty_list, function( key, value ) {
              if(index_id == key){
                qty = value;
                return false;
              }           
          });

        var prices = 0;
          $.each(price_list, function( key, value ) {
              if(index_id == key){
                prices = value;
                return false;
              }           
        });
        data_result.qty = qty;
        data_result.prices = prices;
        return data_result;
    }
    return false;
}

function numberWithCommas(x) {
    "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
 * { redeem order }
 */
function redeem_order(el){
	"use strict";
	var val_to = $('input[name="redeem_to"]').val();
	var total = $('input[name="total"]').val();
	var old_discount = $('input[name="discount_total"]').val();
	var new_total = $('input[name="new_total"]').val();
    var new_discount = $('input[name="new_discount"]').val();
	var max = 0;
	var rate_percent = $('input[name="rate_percent"]').val();
	var symbol = $(el).data('symbol');
	max = (total*rate_percent)/100;
	if(val_to != ''){
		if(val_to <= max){
			if(new_discount != 0){
				new_discount = parseFloat(new_discount) + parseFloat(val_to);
			}else{
				new_discount = parseFloat(old_discount) + parseFloat(val_to);
			}
			
			new_total = parseFloat(total) - new_discount;
			$('input[name="redeem_val"]').val(parseFloat(val_to));
		}else{
			if(new_discount != 0){
				new_discount = parseFloat(new_discount) + parseFloat(old_discount) + parseFloat(max);
			}else{
				new_discount = parseFloat(old_discount) + parseFloat(max);
			}
			new_total = parseFloat(total) - parseFloat(new_discount);
			$('input[name="redeem_val"]').val(parseFloat(max));
		}

		$('input[name="new_discount"]').val(new_discount);
		$('input[name="new_total"]').val(new_total);

		if($('td').hasClass('discount')){
			$('.discount').html('-'+symbol+numberWithCommas(new_discount)+' '+'<a href="javascript:void(0)" onclick="view_detail_discount(); return false;" ><i class="fa fa-question-circle"></i></a>');
		}else{
			var discount_row = '<tr><td><span class="bold">Discount</span></td><td class="discount">-'+symbol+numberWithCommas(new_discount)+' '+'<a href="javascript:void(0)" onclick="view_detail_discount(); return false;" ><i class="fa fa-question-circle"></i></a>'+'</td></tr>';
			$('#subtotal').after(discount_row);
		}

		$('.total').html(symbol+numberWithCommas(new_total));

		$('.redeem_inv_btn').removeAttr('disabled');
		$(el).attr('disabled', 'true');
	}else{
		alert_float('warning','Enter the number of points you want to redeem!');
	}
}

/**
 * { round loy }
 *
 * @param        val     The value
 *
 */
function round_loy(val){
  "use strict";
  return Math.round(val * 100) / 100;
}

/**
 * { view detail discount }
 */
function view_detail_discount(){
	"use strict";
	var new_discount = $('input[name="new_discount"]').val();
	var inv_discount = $('input[name="discount_total"]').val();
	var voucher_value = $('input[name="voucher_value"]').val();
	var redeem_val = $('input[name="redeem_val"]').val();
	var symbol = $('input[name="symbol"]').val();

	var html = '';

	if(inv_discount > 0){
		html += '<div class="col-md-6"><strong>Invoice discount:</strong></div><div class="col-md-6 text-right">'+symbol+numberWithCommas(inv_discount)+'</div>';
	}

	if(voucher_value != '' && voucher_value > 0){
		html += '<div class="col-md-6"><strong>Voucher value:</strong></div><div class="col-md-6 text-right">'+symbol+numberWithCommas(voucher_value)+'</div>';
	}

	if(redeem_val != '' && redeem_val > 0){
		html += '<div class="col-md-6"><strong>Redemption point discount:</strong></div><div class="col-md-6 text-right">'+symbol+numberWithCommas(redeem_val)+'</div>';
	}

	html += '<div class="col-md-12"><hr/></div><div class="col-md-6"><strong>Total discount:</strong></div><div class="col-md-6 text-right">'+symbol+numberWithCommas(new_discount)+'</div>';

	$('#discount_detail_div').html(html);
	$('#discount_detail_md').modal('show');
}