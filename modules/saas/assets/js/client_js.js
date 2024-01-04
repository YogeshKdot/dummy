$(function() {
    "use strict";   
    init_ajax_search('contact', '#contactid.ajax-search', {
        contact_userid: userid
    });

    appValidateForm($("#assign_plan_to_client_create_tenant"), {
        contactid: "required",
        tenant_plan: "required",
        tenants_name: {
            required: true,
            remote: {
                url: admin_url + "saas/superadmin/validateTenantsName",
                type: 'post',
                data: {
                    tenants_name: function() {
                        return $('input[name="tenants_name"]').val();
                    },
                    userid: function() {
                        return userid;
                    }
                }
            }
        },
    }, manage_create_tenant_form, {
        tenants_name: {
            remote: "Tenant name already exist! Please try another name"
        }
    });

    $("#tenants_name").trigger("keyup");

});

function manage_create_tenant_form(form) {
    $("#submit").prop('disabled', true).prepend('<i class="fa fa-spinner fa-pulse"></i> ');
    $.post(form.action, $(form).serialize()).done(function(response) {
        location.reload();
    });
}

$(document).on('keyup', '#tenants_name', function(event) {
    value = $(this).val().replace(/[^a-zA-Z0-9 ]/g, "").toLowerCase()
    $(this).val(value);
    value = value.replace(/ /g,'');
    $("#tenants_name").val(value);
    $("#display_subdomain").html(value);
});