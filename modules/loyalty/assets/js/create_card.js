
(function($) {
"use strict";
    $("#wizard-picture").change(function(){
        readURL(this);
    });

    var attr = $('#custom_field').attr('checked');
    if (typeof attr !== typeof undefined && attr !== false) {
        $('#custom_field_content_div').removeClass('hide');
    }else{
        $('#custom_field_content_div').addClass('hide');
    }

})(jQuery);

/**
 * { custom field change }
 *
 * @param  invoker  The invoker
 */
function custom_field_change(invoker){
    "use strict";
    if(invoker.checked){
        $('#custom_field_content_div').removeClass('hide');
    }else{
        $('#custom_field_content_div').addClass('hide');
    }
}

/**
 * Reads an url.
 *
 * @param        input   The input
 */
function readURL(input) {
  "use strict";
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
        }
        reader.readAsDataURL(input.files[0]);
    }
}