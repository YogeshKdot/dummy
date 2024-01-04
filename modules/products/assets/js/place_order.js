"use strict";
$(function() {
    var update_cart;
    var rate, quantity, max, product_id, amount_text;
    $(".quantity").change(function(event) {
        var quantity = $(this).val();
        max = $(this).attr('max');
        if (quantity <= 0 || !$.isNumeric(quantity)) {
            alert_float("danger","Quantity Must Be Greater Than 0 ");
            $(this).val($(this).data('old-qty'));
            return false;
        }
        if (parseInt(quantity) > parseInt(max)) {
            alert_float("danger",`Only ${max} Items are in stock for this Product`);
            $(this).val($(this).data('old-qty'));
            return false;
        }
        if (update_cart) {
            update_cart.abort();
        }
        product_id = $(this).data('product_id');
        var update_cart = $.post(site_url+'products/client/add_cart', {quantity: quantity, product_id: product_id}, function(data, textStatus, xhr) {
        });
            rate = $(this).data('rate');
        $(this).data('old-qty',quantity);
        amount_text = rate * quantity;
        $(this).parents('td').children('.order_qty').children('input').val(quantity);
        $(this).parents('tr').children('.amount').text(format_money(amount_text));
        var total = 0; 
        var subtotal = 0;
        var tax_values = []; 
        var taxes = {}; 
        var amount = 0;
        var tax_row = '';
        $('.tax-area').remove();
        $(".quantity").each(function(index, el) {
            rate = $(this).data('rate');
            tax_values = $(this).parents('td').find('.product_taxes').selectpicker('val');
            amount = rate * $(this).val();
            subtotal+= amount;
            total += amount;
            $.each(tax_values, function(i, taxname) {
                var tax_name = taxname.split('|');
                var calculated_tax = (amount / 100 * tax_name[1]);
                if (taxes[taxname]) {
                    taxes[taxname] = parseFloat(taxes[taxname]) + parseFloat(calculated_tax);
                } else {
                    taxes[taxname] = parseFloat(calculated_tax);
                }
            });
        });

        $.each(taxes, function(taxname, total_tax) {
            total += total_tax;
            var tax_name = taxname.split('|');
            total_tax = format_money(total_tax);
            tax_row += `<tr class="tax-area">
                                <td>
                                    <span class="bold">${tax_name[0]}(${tax_name[1]}%)</span>
                                </td>
                                <td id="tax_id_${slugify(taxname)}">${total_tax}</td>
                            </tr>`;
            $('#tax_id_' + slugify(taxname)).html(total_tax);
        });
        $('#subtotal').after(tax_row);

        $(".total").text(format_money(total))
        $(".subtotal").text(format_money(subtotal));
        $("input[name='subtotal']").val(subtotal);
        $("input[name='total']").val(total);
        $("input[name='sub_total_disabled']").val(subtotal);
    });
    $(".remove_cart").on('click', function(event) {
        var button;
        product_id = $(this).data('product_id');
        button = $(this);
        $.post(site_url+'products/client/remove_cart', {product_id: product_id}, function(data, textStatus, xhr) {
            data = $.parseJSON(data);
            if (data.status == false) {
                window.location.href = site_url+'products/client';
                return false;
            }
            var next_tr = button.parents('tbody').children('tr');
            if (next_tr.length != 0) {
                console.log(next_tr.find('.quantity'));
                button.parents('tr').remove();
                next_tr.find('.quantity').change();
                alert_float("success","Item Removed from Cart");
            }
        });
    });
});
init_currency();
function format_money(total, excludeSymbol) {
    if (typeof(excludeSymbol) != 'undefined' && excludeSymbol) {
        return accounting.formatMoney(total, { symbol: '' });
    }
    return accounting.formatMoney(total);
}
function init_currency() {
    $.get(site_url + 'products/client/get_currency/'+base_currency)
        .done(function(currency) {
            currency = $.parseJSON(currency);
            accounting.settings.currency.decimal = currency.decimal_separator;
            accounting.settings.currency.thousand = currency.thousand_separator;
            accounting.settings.currency.symbol = currency.symbol;
            accounting.settings.currency.format = currency.placement == 'after' ? '%v %s' : '%s%v';
        });
}