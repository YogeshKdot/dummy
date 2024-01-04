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
      $("#ic_file_data").load(site_url + 'team_password/team_password_client/file_item/' + id + '/' + rel_id + '/' + type, function(response, status, xhr) {
          if (status == "error") {
              alert_float('danger', xhr.statusText);
          }
      });
}

function close_modal_preview(){
    "use strict";
 $('._project_file').modal('hide');
}
