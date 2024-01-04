(function($) {
	"use strict";

    appValidateForm($('#point-action-form'), 
    {
      name: 'required', 
      category: 'required', 
      change_points: 'required', 
    });
})(jQuery);