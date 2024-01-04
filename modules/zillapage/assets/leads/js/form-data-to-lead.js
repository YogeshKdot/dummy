$(function(){
    "use strict"; // Start of use strict
    
    init_selectpicker();
    init_datepicker();
    appTagsInput();
    
    var validationObject = {
        name: 'required',
        source: 'required',
        status: {
            required: {
                depends: function (element) {
                    if ($('[lead-is-junk-or-lost]').length > 0) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        },
    };
    appValidateForm($('#form_data_to_lead'), validationObject);
});
