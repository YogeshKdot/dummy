
(function(){
    "use strict";
   var fnServerParams = {
      "obj_id": "[name='obj_id']",    
      "type": "[name='type']"    
   }
   initDataTable('.table-permission', admin_url + 'team_password/permission_table', false, false, fnServerParams, [0, 'desc']);
})(jQuery);
  /**
   * open modal to add permission
   */
  function add_permission(){
    "use strict";
    $('.add-title').removeClass('hide');
    $('.update-title').addClass('hide');
  	$('#permission').modal();
  }
    /**
   * open modal to edit permission
   */
  function update_permission(el){
    "use strict";
    $('.add-title').addClass('hide');
    $('.update-title').removeClass('hide');

    var list_id = [];
        list_id.push($(el).data('staff'));
    $('select[name="staff[]"]').val(list_id).change();
     if($(el).data('read')=='on'){
      $('input[name="read"]').prop('checked',true);
    }
    else{
      $('input[name="read"]').prop('checked',false);      
    }

    if($(el).data('write')=='on'){
      $('input[name="write"]').prop('checked',true);
    }
    else{
      $('input[name="write"]').prop('checked',false);      
    }
    $('#permission').modal();
  }
