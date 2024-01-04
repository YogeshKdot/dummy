(function($) {	
  "use strict";
  var whatsapp_check = $('#enable_whatsapp');
  var sms_check = $('#enable_sms');
  whatsapp_check.click(function(){
  	if(this.checked == true){
		$('input[name="twilio_account_sid"]').attr('required', 'true');
		$('input[name="twilio_auth_token"]').attr('required', 'true');
		$('input[name="twilio_phone_number"]').attr('required', 'true');
  	}else{
  		$('input[name="twilio_account_sid"]').removeAttr('required');
		$('input[name="twilio_auth_token"]').removeAttr('required');
		$('input[name="twilio_phone_number"]').removeAttr('required');
  	}
  });

  sms_check.click(function(){
  	if(this.checked == true){
		$('input[name="twilio_account_sid"]').attr('required', 'true');
		$('input[name="twilio_auth_token"]').attr('required', 'true');
		$('input[name="twilio_phone_number"]').attr('required', 'true');
  	}else{
  		$('input[name="twilio_account_sid"]').removeAttr('required');
		$('input[name="twilio_auth_token"]').removeAttr('required');
		$('input[name="twilio_phone_number"]').removeAttr('required');
  	}
  });

  	if(whatsapp_check.is(':checked') == true || sms_check.is(':checked') == true){
		$('input[name="twilio_account_sid"]').attr('required', 'true');
		$('input[name="twilio_auth_token"]').attr('required', 'true');
		$('input[name="twilio_phone_number"]').attr('required', 'true');
  	}else{
  		$('input[name="twilio_account_sid"]').removeAttr('required');
		$('input[name="twilio_auth_token"]').removeAttr('required');
		$('input[name="twilio_phone_number"]').removeAttr('required');
  	}

})(jQuery);

/**
 * Creates a secret key.
 */
function create_secret_key() {
	"use strict";
	requestGet('mfa/create_secret_key').done(function(response) {
		response = JSON.parse(response);
		$('input[name="google_authenticator_secret_key"]').val(response.secret_key);
	});
}

/**
 * { clear login & sending security code logs }
 */
function clear_mfa_logs(){
	"use strict";
	var r = confirm("Are you sure you want to delete all history (login & send code)!");
	if (r == true) {
		$.post(admin_url + 'mfa/delete_history').done(function(response) {
			response = JSON.parse(response);
			if(response.success == true) {
				alert_float('success', response.message);
			}
			else{
				alert_float('warning', response.message);
			}
		});
	}
}

/**
 * Sends a test message.
 *
 * @param      {this}  
 */
function send_test_message(el){
	"use strict";
	var phone_number = $('input[name="your_whatsapp_phonenumber"]').val();
	var account_sid = $('input[name="twilio_account_sid"]').val();
	var auth_token = $('input[name="twilio_auth_token"]').val();
	var twilio_phone_number = $('input[name="twilio_phone_number"]').val();
	var mess_template = $('input[name="whatsapp_message_template"]').val();
	var data = {};
	var that = $(el);
	if(phone_number == '' || account_sid == '' || auth_token == '' || twilio_phone_number == '' || mess_template == ''){
		alert_float('warning', 'Please enter complete information!');
	}else{
		data.phone_number = phone_number;
		data.account_sid = account_sid;
		data.auth_token = auth_token;
		data.twilio_phone_number = twilio_phone_number;
		data.mess_template = mess_template;
		that.prop('disabled', true);
		$.post(admin_url + 'mfa/send_test_message', data).done(function(response) {
			response = JSON.parse(response);
			if(response.success == true){
				alert_float('success', response.message);
			}else{
				alert_float('warning', response.message);
			}
		}).always(function() {
                that.prop('disabled', false);
            });
	}
}

/**
 * { list users of role }
 *
 * @param        role    The role
 */
function list_users_of_role(role){
	"use strict";
	$('#list_role_user').modal('show');
	$.post(admin_url + 'mfa/list_users_of_role/' + role).done(function(response) {
		response = JSON.parse(response);
		$('#list_user_div').html(response.html);
		$('#list_users_title').html(response.role_name);
	});
}