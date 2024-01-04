function approve_request(id){
  "use strict";
  change_request_approval_status(id,1);
}

function deny_request(id){
  "use strict";
  change_request_approval_status(id,2);
}

function change_request_approval_status(id, status){
  "use strict";
  var data = {};
  data.rel_id = id;
  data.rel_type = 'audit';
  data.approve = status;
  data.note = $('textarea[name="reason"]').val();
  $.post(admin_url + 'fixed_equipment/approve_request_form_audit/' + id, data).done(function(response){
    response = JSON.parse(response);
    if (response.success === true || response.success == 'true') {
      alert_float('success', response.message);
      window.location.reload();
    }
    else{
      alert_float('danger', response.message);
      window.location.reload();
    }
  });
}
function choose_approver(){
  "use strict";
  var id =  $('input[name="id"]').val();
  var approver =  $('select[name="approver"]').val();
  if(approver == ''){
    var text = $('input[name="choose_approver_text"]').val();
    alert_float('warning', text);
    return false;
  }
  var data = {};
  data.id = id;
  data.approver = approver;
  $.post(admin_url + 'fixed_equipment/choose_approver_request_audit/', data).done(function(response){
    response = JSON.parse(response);
    if (response.success == true) {
      alert_float('success', response.message);
    }
    else{
      alert_float('danger', response.message);      
    }
    window.location.reload();
  });
}