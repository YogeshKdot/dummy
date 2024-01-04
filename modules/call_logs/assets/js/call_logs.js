function loadGridView() {
    var formData = {
        search: $("input#search").val(),
        start: 0,
        length: _lnth,
        draw: 1
    }
    gridViewDataCall(formData, function (resposne) {
        $('div#grid-tab').html(resposne)
    })
}
function gridViewDataCall(formData, successFn, errorFn) {
    $.ajax({
        url:  admin_url + 'call_logs/grid/'+(formData.start+1),
        method: 'POST',
        data: formData,
        async: false,
        // cache: false,
        error: function (res, st, err) {
            console.log("error API", err)
        },
        beforeSend: function () {
            // showalert('Please wait...', 'alert-info');
        },
        complete: function () {
        },
        success: function (response) {
            if ($.isFunction(successFn)) {
                successFn.call(this, response);
            }
        }
    });
}

// Init modal and get data from server
function init_call_log_modal(id) {
    var $callLogModal = $('#call_log-modal');

    requestGet('call_logs/get_call_log_data/' + id).done(function(response) {
        _task_append_html(response);
    }).fail(function(data) {
        alert_float('danger', data.responseText);
    });
}