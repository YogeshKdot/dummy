$(function(){
	"use strict"; // Start of use strict

    $('#notify_lead_imported').on('change',function(){
          $('.select-notification-settings').toggleClass('hide');
    });

    appValidateForm($('#form_setting'), {
        name: 'required',
        redirect_url: {
            required: {
                depends:function(element) {
                    return $('select[name="type_form_submit"]').val() == 'url';
                }
            }
        },
        responsible: {
         required: {
            depends:function(element){
             var isRequiredByNotifyType = ($('input[name="notify_type"]:checked').val() == 'assigned') ? true : false;
             var isRequired = isRequiredByNotifyType;
             if(isRequired) {
                $('[for="responsible"]').find('.req').removeClass('hide');
             } else {
                $(element).next('p.text-danger').remove();
                $('[for="responsible"]').find('.req').addClass('hide');
             }
             return isRequired;
           }
         }
       }
    });
    var $notifyTypeInput = $('input[name="notify_type"]');
    $notifyTypeInput.on('change',function(){
        $('#form_setting').validate().checkForm()
    });
    $notifyTypeInput.trigger('change');

    $('#type_form_submit').on('change', function (e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;

        if (valueSelected) {
            // subdomain
            if (valueSelected == 'thank_you_page') {
              $("#form_redirect_url").addClass("d-none");
            }
            // custom_domain
            else if(valueSelected == 'url'){
              $("#form_redirect_url").removeClass("d-none");
             
            }
        }
      });
});    
