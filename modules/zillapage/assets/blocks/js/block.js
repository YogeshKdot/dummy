$(function(){
    "use strict"; // Start of use strict
    appValidateForm($('#block_form'), {
        name: 'required',
        block_category: 'required',
    });
});