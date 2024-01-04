function save_inject_javascript() {
	"use strict"; 
    $.post(admin_url + 'inject_javascript/save', {
        admin_area: $('#inject_javascript_admin_area').val(),
        clients_area: $('#inject_javascript_clients_area').val(),
        clients_and_admin: $('#inject_javascript_clients_and_admin_area').val(),
    }).done(function(response) {
        window.location = admin_url + 'inject_javascript';
    });
}

function enable_inject_javascript() {
	"use strict"; 
    $.post(admin_url + 'inject_javascript/enable', {}).done(function() {
        window.location = admin_url + 'inject_javascript';
    });
}

function disable_inject_javascript() {
	"use strict"; 
    $.post(admin_url + 'inject_javascript/disable', {}).done(function() {
        window.location = admin_url + 'inject_javascript';
    });
}