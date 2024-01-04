function loadGridView() {
    var formData = {
        search: $("input#search").val(),
        start: 0,
        length: _lnth,
        draw: 1
    }
    gridViewDataCall(formData, function (resposne) {
        $('div#grid-tab').html(resposne);
        setTimeout(__renderGridViewMindMaps, 900)
    })
}
function gridViewDataCall(formData, successFn, errorFn) {
   
    $.ajax({
        url:  admin_url + 'diagramy/grid/'+(formData.start+1),
        method: 'POST',
        data: formData,
        async: false,
        error: function (res, st, err) {
            console.log("error API", err)
        },
        beforeSend: function () {
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

function __renderGridViewMindMaps() {
    $('div[id^="map_"]').each(function( index ) {
        var mId= $(this).attr('id');
    });
}

// Init modal and get data from server
function init_diagramy_modal(id) {
    var $diagramyModal = $('#mindmap-modal');
    requestGet('diagramy/get_diagramy_data/' + id).done(function(response) {
        _task_append_html(response);
        setTimeout(__initDiagramy, 500)
    }).fail(function(data) {
        alert_float('danger', data.responseText);
    });
}

function __initDiagramy() {
   
}