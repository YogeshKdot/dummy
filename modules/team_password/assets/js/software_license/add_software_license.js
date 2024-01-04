(function(){
  'use strict';
var relate_to = $('select[id="relate_to"]').val();
    if(relate_to == 'contract'){
      $('#relate_contract').removeClass('hide');
      $('#relate_project').addClass('hide');
      $('select[id="relate_id_project"]').val('').change();
    }else if(relate_to == 'project'){
      $('#relate_contract').addClass('hide');
      $('#relate_project').removeClass('hide');
      $('select[id="relate_id_contract"]').val('').change();
    }else{
      $('#relate_contract').addClass('hide');
      $('#relate_project').addClass('hide');
    }
})(jQuery);
  /**
  * remove custom field
  */
  function remove_field(el){
   "use strict";
   $(el).parent().remove();
  }
    /**
     * [open fields]
     */
    function open_fields(){
      "use strict";
      $('#custom_fields').modal();
    }
    /**
     * open modal to add custom field
     */
    function create_customfield(){
      "use strict";
      var length = $('.tag').length;
      var name = $('input[name="field_name"]').val();
      var value = $('input[name="field_value"]').val();
     var html = '';
      html += '&nbsp;<span class="btn btn-default ptop-10 tag">';
      html += '<label  name="field_name['+length+']">'+name+'</label>&nbsp; - &nbsp;<label  name="field_value['+length+']">'+value+'</label>&nbsp;';
      html += '<input type="hidden" name="field_name['+length+']" value="'+name+'">';
      html += '<input type="hidden" name="field_value['+length+']" value="'+value+'">';
      html += '<label class="exit_tag" onclick="remove_field(this);" >&#10008;</label>';
      html += '</span>&nbsp;';
      $('#add_field').append(html);
    }
   
function relate_to_change(invoker){
  "use strict";
  if(invoker.value == 'contract'){
    $('#relate_contract').removeClass('hide');
    $('#relate_project').addClass('hide');
    $('select[id="relate_id_project"]').val('').change();
  }else if(invoker.value == 'project'){
    $('#relate_project').removeClass('hide');
    $('#relate_contract').addClass('hide');
    $('select[id="relate_id_contract"]').val('').change();
  }else{
    $('#relate_project').addClass('hide');
    $('#relate_contract').addClass('hide');
  }

}