(function($) {
  "use strict";
  appValidateForm($('#sms-form'),{
    name:'required',
    category:'required',
    sms_template:'required',
  });

  $('select[name=sms_template]').on('change', function(){
    var id = $(this).val();
    get_exam_template(id);
  });

})(jQuery);

function get_exam_template(id){
    "use strict";
    $.get(admin_url+'ma/get_sms_template_preview/'+id, function(reponses){
      $('textarea[name=content]').text(reponses);
    });
}