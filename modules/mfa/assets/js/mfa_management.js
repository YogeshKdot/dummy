(function($) {	
  "use strict";
  var whatsapp_check = $('#mfa_whatsapp_enable');
  var sms_check = $('#mfa_sms_enable');
  var gg_check = $('#mfa_google_ath_enable');

  gg_check.click(function(){
    if(this.checked == true){
      $('#sr_key_div').removeClass('hide');
    }else{
      $('#sr_key_div').addClass('hide');
    }
  });

  whatsapp_check.click(function(){
  	if(this.checked == true){
		  $('input[name="whatsapp_number"]').attr('required', 'true');
      $('#whatsapp_number_div').removeClass('hide');
  	}else{
  		$('input[name="whatsapp_number"]').removeAttr('required');
      $('#whatsapp_number_div').addClass('hide');
  	}
  });

  sms_check.click(function(){
  	if(this.checked == true){
		  $('input[name="phonenumber"]').attr('required', 'true');
		  $('#phonenumber_div').removeClass('hide');
  	}else{
  		$('input[name="phonenumber"]').removeAttr('required');
      $('#phonenumber_div').addClass('hide');
		
  	}
  });
})(jQuery);

/**
 * Creates a secret key.
 */
function create_secret_key() {
  "use strict";
  requestGet('mfa/create_secret_key').done(function(response) {
    response = JSON.parse(response);
    $('input[name="gg_auth_secret_key"]').val(response.secret_key);
  });
}

/**
 * { view qr code }
 */
function view_qr_code(){
  "use strict";
  var secret_key = $('input[name="gg_auth_secret_key"]').val();
  if(secret_key != '' && secret_key != null && secret_key != undefined){
    requestGet('mfa/create_qr_code/'+secret_key).done(function(response) {
      response = JSON.parse(response);
      $('#qr_div').html(response.html);

    });
    $('#qr_code_modal').modal('show');
  }else{  
    alert_float('warning', 'Please create secret key!');
  }
}