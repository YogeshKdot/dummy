"use strict"
$(function() {
    filter_data();
    $(document).on('click', '.add_cart', function(event) {
        var button = $(this);
        var quantity = $(this).parents('.input_data').find('input[name="quantity"]').val();
        var max = $(this).parents('.input_data').find('input[name="quantity"]').attr('max');
        if (quantity <= 0 || !$.isNumeric(quantity)) {
            alert_float("danger","Quantity Must Be Greater Than 0 ");
            return false;
        }
        if (parseInt(quantity) > parseInt(max)) {
            alert_float("danger",`Only ${max} Items are in stock for this Product`);
            return false;
        }
        var product_id = $(this).parents('.input_data').find('input[name="product_id"]').val();
        $.post(site_url+'products/client/add_cart', {quantity: quantity, product_id: product_id}, function(data, textStatus, xhr) {
            button.text("Update Cart");
            alert_float("success","Item Added to Cart");
        });
    });

    $(document).on('change', '#product_categories', function(event) {
        filter_data({'p_category_id':$(this).val()});
    });

});

function filter_data(post_data = {}) {
    $(".no_product").addClass('hidden');
    $("#filter_html").html("");
    $(".image_loader").show();
    $.ajax({
        url: site_url+'products/client/filter',
        type: 'POST',
        dataType: 'json',
        data : post_data,
        success : function (data) {
            render_product_data(data);
        }
    })
}

function render_product_data(data) {
    var html = "";
    var total_taxes = "";
    $.each(data, function(index, val) {
        var cart_data_quantity = "";
        var button = "";

        if(val.total_tax != 0){
            total_taxes = `<span class='total_taxes text-warning'>(+ ${val.total_tax}% taxes)</span>`;
        }
        else{
            total_taxes = "";
        }

        if (parseInt(val.quantity_number) < 1) {
            button = `<button class="btn btn-danger pull-right">Stock Out</button>`;
        } else {
            var label  = "Add to";
            if (val.cart_data && val.cart_data.quantity) {
                label  = "Update";
                cart_data_quantity = val.cart_data.quantity;
            }
            button = `<button class="btn btn-warning add_cart pull-right">${label} Cart</button>`
        }
        var recurring_type = "";
        var cycles_text = "&nbsp;";
        if (val.recurring != 0) {
            recurring_type = val.recurring_type;
            if (val.recurring_type == "") {
                recurring_type = "month";
            }
            recurring_type = "/ "+ ((val.recurring != 1) ? val.recurring:"") + ' '+ recurring_type;
            if (val.cycles == 0) {
                cycles_text = "Infinite recurring";
            }
            if (val.cycles == 1) {
                cycles_text = "1 time totally";
            }
            if (val.cycles > 1) {
                cycles_text = val.cycles+" times totally";
            }
        }
        html += `<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="thumbnail shadow">
                <div>
                    <img src="${val.product_image_url}" alt="${val.product_image}" class="img1" onerror="this.src='${val.no_image_url}'">
                    <br>
                    <div class="title text-center text-warning">
                        <h4>${val.product_name}</h4>
                    </div>
                    <div class="description">
                        <span>${val.product_description}</span>
                    </div>
                    <br>
                    <div>
                        <div class="text-center">${val.p_category_name}</div>
                    </div>
                    <div class="rates">
                        <h4>${val.base_currency_name} ${val.rate} ${recurring_type} ${total_taxes}</h4>
                        <h5>${cycles_text} </h5>
                    </div>
                    <div class="row input_data" id="">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <input type="number" name="quantity" min="1" max="${val.quantity_number}" value="${cart_data_quantity}" class="form-control" placeholder="Quantity">
                            <input type="hidden" name="product_id" value="${val.id}" class="form-control">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            ${button}
                        </div>
                    </div>
                </div>
            </div>   
        </div>`;
    });
    $("#filter_html").hide();
    $(".image_loader").hide();
    $("#filter_html").html(html).fadeIn('slow');
    if (html=="") {
        $(".no_product").removeClass('hidden');
    }
}