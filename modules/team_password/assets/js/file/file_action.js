function preview_ic_btn(invoker){
    "use strict";
    var id = $(invoker).attr('id');
    var rel_id = $(invoker).attr('rel_id');
    var type = $(invoker).attr('type_item');
    view_ic_file(id, rel_id,type);
}

function view_ic_file(id, rel_id,type) {
    "use strict";
      $('#ic_file_data').empty();
      $("#ic_file_data").load(admin_url + 'team_password/file_item/' + id + '/' + rel_id + '/' + type, function(response, status, xhr) {
          if (status == "error") {
              alert_float('danger', xhr.statusText);
          }
      });
}

function close_modal_preview(){
    "use strict";
 $('._project_file').modal('hide');
}

function delete_ic_attachment(id,invoker) {
    "use strict";
    var type = $(invoker).attr('type_item');
    if (confirm_delete()) {
        requestGet('team_password/delete_file_item/' + id+'/'+type).done(function(success) {
            if (success == 1) {
                $("#ic_pv_file").find('[data-attachment-id="' + id + '"]').remove();
            }
        }).fail(function(error) {
            alert_float('danger', error.responseText);
        });
  }
}