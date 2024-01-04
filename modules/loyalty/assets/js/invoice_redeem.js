(function(){
	"use strict";
	$('select[name="clientid"]').on('change', function() {
		if(this.value != '' && this.value != null && this.value){
			$.post(admin_url + 'loyalty/get_client_info_loy_inv/' +this.value).done(function(response) {
				response = JSON.parse(response);
				if(response.type == 'partial'){
					$('span[id="point_span"]').html('');
					$('span[id="point_span"]').append(response.point);

					$('input[name="data_max"]').val(response.point);
					$('input[name="weight"]').val(response.weight);
					
				}else{
					
					$('span[id="point_span"]').html('');
					$('span[id="point_span"]').append(response.point);

					$('input[name="redeem_from"]').val(response.val);
					$('input[name="redeem_to"]').val(response.val_to);
					
					if( $('input[name="redeem_from"]').val() != '' &&  $('input[name="redeem_to"]').val() != ''){
						$('input[name="redeem_from"]').attr(response.disabled,true);
						$('input[name="redeem_to"]').attr(response.disabled,true);
					}

					$('input[name="weight"]').val(response.weight);
				}

				$('input[name="rate_percent"]').val(response.max_received);
				if(response.hide != ''){
					$('#redeem_tr').addClass(response.hide);
				}else{
					$('#redeem_tr').removeClass('hide');
				}
			});

			get_mbs_discount();
		}
	});

	$('input[name="voucher"]').on('change', function(){ 
	  	var voucher = $(this).val();
	  	if(voucher != ''){
	  		var id = $('select[name="clientid"]').val();
	  		if(id != '' && id != null && id != undefined){
		        var data = {};
		        data.voucher = voucher; 
		        data.clientid = id;

		        var total = $('input[name="subtotal"]').val();
		        var program_discount = $('input[name="program_discount"]').val();
		        var redeem_discount = $('input[name="redeem_discount"]').val();
		        var symbol = $('input[name="symbol"]').val();

		        $.post(admin_url + 'loyalty/voucher_apply', data).done(function(response){
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
		           			var total_discount = 0;
			            	if(response.rs.formal == 1){ 
			            		total_discount = parseFloat(((total * response.rs.discount)/100)) + parseFloat(program_discount) + parseFloat(redeem_discount);
			            		total_price_discount = parseFloat(((total * response.rs.discount)/100));
			            	}else if(response.rs.formal == 2){
			            		total_discount = parseFloat(response.rs.discount) + parseFloat(program_discount) + parseFloat(redeem_discount);
			            		total_price_discount = parseFloat(response.rs.discount);
			            	}

			            	$('input[name="discount_total"]').val(total_discount);
			            	$('input[name="voucher_value"]').val(total_price_discount);
			            	calculate_total();
							
							$('.discount-total').html('-'+symbol+numberWithCommas(total_discount)+' '+'<a href="javascript:void(0)" onclick="view_detail_discount(); return false;" ><i class="fa fa-question-circle"></i></a>');
							

							$('input[name="voucher"]').attr('readonly', 'true');

							alert_float('success','Voucher applied');

			            }else{
			            	alert_float('warning','Your order is not eligible for this code');
			            }

		        	}else{
		        		alert_float('warning', 'voucher does not exist');
		        	}
		        });
		    }else{
		    	alert_float('warning', 'Please choose customer!');
		    }
	  	}else{
	  		alert_float('warning','Please enter the voucher code');
	  	}

	  });


	var old_subtotal = $('input[name="subtotal"]').val();
	var new_subtotal = 0;
	$(document).on('sales-total-calculated', function(){
		new_subtotal = $('input[name="subtotal"]').val();
		if($('.ui-sortable >tr').length > 1){
			var total_items = ($('.ui-sortable >tr').length - 1);
			var data = {};
			data.description = [];
			data.long_description = [];
			data.qty = [];
			data.rate = [];
			for(var i = 1; i <= total_items; i++){ 
				var des = $('textarea[name="newitems['+i+'][description]"]').val();
				var long_des = $('textarea[name="newitems['+i+'][long_description]"]').val();
				var qty = $('input[name="newitems['+i+'][qty]"]').val();
				var rate = $('input[name="newitems['+i+'][rate]"]').val();
				data.description.push(des);
				data.long_description.push(long_des);
				data.qty.push(qty);
				data.rate.push(rate);
			}

			$.post(admin_url + 'loyalty/get_list_product_id', data).done(function(response){
            	response = JSON.parse(response); 
            	$('input[name="list_id_product"]').val(response.product_ids);
            	$('input[name="list_qty_product"]').val(response.list_qty);
            	$('input[name="list_prices_product"]').val(response.list_rate);
        	});

			if(new_subtotal != old_subtotal){
				get_mbs_discount();
				old_subtotal = new_subtotal;
			}
		}
	});

})(jQuery);

var mbs_discount_html = '';
/**
 * { auto redeem pos }
 *
 * @param        invoker  The invoker
 */
function auto_redeem_pos(invoker) {
	"use strict";
	var val_to = 0;
	var weight = $('input[name="weight"]').val();
	var rate_percent = $('input[name="rate_percent"]').val();
	var total = $('input[name="total"]').val();
	var max = 0;
	var data_max = $('input[name="data_max"]').val();
	if(invoker.value > data_max){
		alert_float('warning','Point invalid!');
	}else{
		if( $('select[name="clientid"]').val() != ''){
			max = (rate_percent*total)/100;
			if(invoker.value != ''){
				val_to = invoker.value*weight;
				if(val_to <= max){
					$('input[name="redeem_to"]').val(round_loy(val_to));
				}else{
					$('input[name="redeem_to"]').val(round_loy(max));
				}
			}
			
		}else{
			alert_float('warning', 'Please choose customer!');
		}
	}
}

/**
 * { redeem pos order }
 */
function redeem_pos_order(){
	"use strict";
	var val_to = $('input[name="redeem_to"]').val();
	var total = $('input[name="total"]').val();
	var program_discount = $('input[name="program_discount"]').val();
	var voucher_value = $('input[name="voucher_value"]').val();
	var max = 0;
	var rate_percent = $('input[name="rate_percent"]').val();
	var symbol = $('input[name="symbol"]').val();
	max = (total*rate_percent)/100;
	if(val_to != '' && val_to > 0){

		$('.discount-type-fixed').addClass('selected');
		$('.discount-type-percent').removeClass('selected');
		$('.discount-total-type-selected').html('Fixed Amount');

		$('input[name="discount_percent"]').addClass('hide');
		$('input[name="discount_total"]').removeClass('hide');

		if(max > 0){
			var redeem_discount = 0;
			if(val_to <= max){
				$('input[name="redeem_discount"]').val(round_loy(val_to));
				redeem_discount = parseFloat(round_loy(val_to)) + parseFloat(program_discount) + parseFloat(voucher_value);
			}else{

				$('input[name="redeem_to"]').val(round_loy(max));
				$('input[name="redeem_discount"]').val(round_loy(max));
				redeem_discount = parseFloat(program_discount) + parseFloat(round_loy(max)) + parseFloat(voucher_value);
			}

			$('input[name="discount_total"]').val(redeem_discount);
			calculate_total();

			$('.discount-total').html('-'+symbol+numberWithCommas(redeem_discount)+' '+'<a href="javascript:void(0)" onclick="view_detail_discount(); return false;" ><i class="fa fa-question-circle"></i></a>');
		}

		if($('input[name="discount_total"]').val() > 0){
			$('button[id="redeem_btn"]').attr('disabled','true');
		}
	}else{
		alert_float('warning','Enter the number of points you want to redeem!');
	}
}

function round_loy(val){
  "use strict";
  return Math.round(val * 100) / 100;
}

/**
 * Gets the mbs discount.
 */
function get_mbs_discount(){
	"use strict";
	var list_discount = [];
	var total = $('input[name="subtotal"]').val();
	var old_discount = $('input[name="discount_total"]').val();
	var new_total = 0;
	var new_discount = 0;
	var redeem_discount = $('input[name="redeem_discount"]').val();
	var voucher_value = $('input[name="voucher_value"]').val();
	var symbol = $('input[name="symbol"]').val();

	var id = $('select[name="clientid"]').val();
	if(id != '' && id != null && id != undefined){
		
		
		var data = {};
		data.clientid = id;
		$.post(admin_url + 'loyalty/get_mbs_discount', data).done(function(response){
            response = JSON.parse(response);
            for(var i = 0; i < response[0].length;i++){               
                list_discount.push({item:response[0][i].items, formal:response[0][i].formal,group_list:response[0][i].group_items, discount:response[0][i].discount, voucher:response[0][i].voucher, minimum_order_value:response[0][i].minimum_order_value, reason:response[0][i].name_trade_discount, program_id:response[0][i].program_id});
            }
  
            var result = 0;
	  		
  			$.each(list_discount, function( key, value ) { 
  				if(value.item !=''){ 
  					var array = value.item.split(',');
	                var array_group = value.group_list.split(',');
	                var list_id_product_cart =  $('input[name="list_id_product"]').val();     
	                var list_group_product =  $('input[name="list_group_product"]').val();     
	                var list_id = JSON.parse('['+list_id_product_cart+']');
	                var list_group_product = JSON.parse('['+list_group_product+']');

	                $.each(list_id, function( k, idp ) { 
	                	var gid = list_group_product[k];   
	                    if(array.includes(idp.toString()) || array_group.includes(gid)){
	                        var data_info_item = get_infor_item(parseInt(idp));
	                        var price_discount = 0;
                            if(parseFloat(value.minimum_order_value)>0){
	                            if(total>=parseFloat(value.minimum_order_value)){
	                                if(parseInt(value.formal) == 1){
	                                  var discount_item = parseFloat(data_info_item.prices) * value.discount / 100;
	                                  price_discount = parseFloat(data_info_item.prices) - discount_item; 
	                                  result += discount_item * parseInt(data_info_item.qty);
	                                  mbs_discount_html += '<div class="col-md-8"><strong>'+value.reason+':</strong></div><div class="col-md-4 text-right">'+symbol+numberWithCommas(discount_item * parseInt(data_info_item.qty))+'</div>';
	                                  $('#pro_discount_div').html(hidden_input('program_'+value.program_id, parseFloat(discount_item * parseInt(data_info_item.qty)))); 
	                                }
	                                else{
	                                  price_discount = parseFloat(data_info_item.prices) - value.discount;                                  
	                                  result += parseFloat(value.discount) * parseFloat(data_info_item.qty);
	                                }
	                            }
                            }else{
                                if(parseInt(value.formal) == 1){
                                  var discount_item = parseFloat(data_info_item.prices) * value.discount / 100;
                                  price_discount = parseFloat(data_info_item.prices) - discount_item; 
                                  result += discount_item * parseInt(data_info_item.qty); 
                                  mbs_discount_html += '<div class="col-md-8"><strong>'+value.reason+':</strong></div><div class="col-md-4 text-right">'+symbol+numberWithCommas(discount_item * parseInt(data_info_item.qty))+'</div>';
                                  $('#pro_discount_div').html(hidden_input('program_'+value.program_id, parseFloat(discount_item * parseInt(data_info_item.qty))));
                                }
                                else{
                                  price_discount = parseFloat(data_info_item.prices) - value.discount;                                  
                                  result += parseFloat(value.discount) * parseFloat(data_info_item.qty);  
                                }
                            }

                            if(parseFloat(price_discount) > 0){
	                            var new_price = numberWithCommas(price_discount);
                            }
	                    } 
	                });
  				}else{
	                if(parseFloat(value.minimum_order_value)>0){
	                    if(total>=parseFloat(value.minimum_order_value)){
	                        if(parseInt(value.formal) == 1){

	                            result += total * value.discount / 100;
	                            mbs_discount_html += '<div class="col-md-8"><strong>'+value.reason+':</strong></div><div class="col-md-4 text-right">'+symbol+numberWithCommas(total * value.discount / 100)+'</div>';
	                            $('#pro_discount_div').html(hidden_input('program_'+value.program_id, parseFloat(total * value.discount / 100)));
	                        }else{

	                            result += parseFloat(value.discount);                          
	                        }
	                    }                    
	                }else{
	                        if(parseInt(value.formal) == 1){
	                            result += total * value.discount / 100;
	                            mbs_discount_html += '<div class="col-md-8"><strong>'+value.reason+':</strong></div><div class="col-md-4 text-right">'+symbol+numberWithCommas(total * value.discount / 100)+'</div>';
	                            $('#pro_discount_div').html(hidden_input('program_'+value.program_id, parseFloat(total * value.discount / 100)));
	                        }else{
	                            result += parseFloat(value.discount);                          
	                        }
	                }
	            }
  			});
	  		

	  		if(result > 0){
	  			
	  			new_discount =  parseFloat(result) + parseFloat(redeem_discount) + parseFloat(voucher_value);
	  			
		  		new_total = parseFloat(total) - parseFloat(new_discount);

		  		$('input[name="new_discount"]').val(new_discount);
				$('input[name="new_total"]').val(new_total);
				$('input[name="program_discount"]').val(result);

				$('.discount-type-fixed').addClass('selected');
				$('.discount-type-percent').removeClass('selected');
				$('.discount-total-type-selected').html('Fixed Amount');

				$('input[name="discount_percent"]').addClass('hide');
				$('input[name="discount_total"]').removeClass('hide');

			
				$('input[name="discount_total"]').val(new_discount);

				calculate_total();

				$('.discount-total').html('-'+symbol+numberWithCommas(new_discount)+' '+'<a href="javascript:void(0)" onclick="view_detail_discount(); return false;" ><i class="fa fa-question-circle"></i></a>');
			}

        });
		
	}
}

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

/**
 * { numberWithCommas }
 *
 * @param      {string}  x       
 * @return     {string}  
 */
function numberWithCommas(x) {
    "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * { view detail discount }
 */
function view_detail_discount(){
	"use strict";

	var inv_discount = $('input[name="discount_total"]').val();
	var program_discount = $('input[name="program_discount"]').val();
	var voucher_value = $('input[name="voucher_value"]').val();
	var redeem_val = $('input[name="redeem_discount"]').val();
	var symbol = $('input[name="symbol"]').val();

	var html = '';


	if(program_discount != '' && program_discount > 0 && mbs_discount_html != ''){
		html += mbs_discount_html;
	}

	if(voucher_value != '' && voucher_value > 0){
		html += '<div class="col-md-6"><strong>Voucher value:</strong></div><div class="col-md-6 text-right">'+symbol+numberWithCommas(voucher_value)+'</div>';
	}

	if(redeem_val != '' && redeem_val > 0){
		html += '<div class="col-md-6"><strong>Redemption point discount:</strong></div><div class="col-md-6 text-right">'+symbol+numberWithCommas(redeem_val)+'</div>';
	}

	html += '<div class="col-md-12"><hr/></div><div class="col-md-6"><strong>Total discount:</strong></div><div class="col-md-6 text-right">'+symbol+numberWithCommas(inv_discount)+'</div>';

	$('#discount_detail_div').html(html);
	$('#discount_detail_md').modal('show');
}