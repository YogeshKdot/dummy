$(function() {
    "use strict";
    
    var CustomersServerParams = {};
    $.each($('._hidden_inputs._filters input'), function() {
        CustomersServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
    });

    initDataTable('.table-landingpage-leads', window.location.href, [5], [5], CustomersServerParams);
    $('.btn-default-dt-options:first').remove();

    $(document).on('click', '.btn-convert-ldp-to-customer', function() {

        var id = $(this).attr('data-id');
        if (!id) {
            alert('not found id');
            return false;
        }

        requestGet('zillapage/leads/get_convert_data_landingpage_leads/' + id).done(function(response) {
            $('#landingpage_lead_to_customer').html(response);
            $('#convert_landingpage_lead_to_customer_modal').modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        }).always(function() {});

    });



    $(document).on('click', '.btn-convert-ldp-to-lead', function() {

        var id = $(this).attr('data-id');
        if (!id) {
            alert('not found id');
            return false;
        }
      
        requestGet('zillapage/leads/get_convert_form_data_to_leads/' + id).done(function(response) {
            $('#landingpage_form_data_to_lead').html(response);
            $('#convert_landingpage_form_data_to_lead_modal').modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        }).always(function() {});

    });
});