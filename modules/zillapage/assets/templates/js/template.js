$(function(){
	"use strict"; // Start of use strict
    appValidateForm($('#template_form'), {
        name: 'required',
        block_category: 'required',
    });
});