"use strict";

function save_support_contact() {
    $.post(admin_url + 'support_contact/save', {
        support_contact_viber: $('#support_contact_viber').val(),
        support_contact_whatsapp: $('#support_contact_whatsapp').val(),
        messenger_username: $('#support_contact_messenger_username').val(),
        email_address: $('#support_contact_email_address').val(),
    }).done(function(response) {
        window.location = admin_url + 'support_contact';
    });
}


function enable_frontend() {
    $.post(admin_url + 'support_contact/enablefrontend', {}).done(function() {
        window.location = admin_url + 'support_contact';
    });
}

function disable_frontend() {
    $.post(admin_url + 'support_contact/disablefrontend', {}).done(function() {
        window.location = admin_url + 'support_contact';
    });
}

function enable_backend() {
    $.post(admin_url + 'support_contact/enablebackend', {}).done(function() {
        window.location = admin_url + 'support_contact';
    });
}

function disable_backend() {
    $.post(admin_url + 'support_contact/disablebackend', {}).done(function() {
        window.location = admin_url + 'support_contact';
    });
}