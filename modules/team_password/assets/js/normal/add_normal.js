(function(){
  "use strict";
    /**
     * view password
     */
  $('body').on('click','.view_passwords',function(e){
        e.preventDefault();
        "use strict";
        var has = $(this).find('i').hasClass('fa-eye');
        if(has){
          $(this).find('i').removeClass('fa-eye');
          $(this).find('i').addClass('fa-eye-slash');
          $('input[name="password"]').attr('type', 'text');
        }
        else{
          $(this).find('i').removeClass('fa-eye-slash');
          $(this).find('i').addClass('fa-eye');
          $('input[name="password"]').attr('type', 'password');
        }
    });

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
  * remove field
  */
  function remove_field(el){
   "use strict";
   $(el).parent().remove();
  }
 /**
 * Random Number
 */
  function RandomNumber(string_length,chars) {
    "use strict";
          var randomstring = '';
          for (var i=0; i<string_length; i++) {
              var rnum = Math.floor(Math.random() * chars.length);
              randomstring += chars.substring(rnum,rnum+1);
          }
          return randomstring;
    }
   /**
   * generate password
   */
  function generate_password(){
    "use strict";
    var password = '';
    var length = $('#length').val();

    var number_regex = "0123456789";
    var character_regex = "abcdefghiABCDEjklmnFGHIJKLopqrstUVWXYZuvwxyzMNOPQRST";
    var special_characters_regex = "!@#$%^&*()_+~`|}{[]:;?><,./-=\\";
    var br = 0;
    if((br == 0)&&($('#characters').is(":checked"))&&($('#numbers').is(":checked"))&&($('#special_characters').is(":checked"))){
        br = 1;
        var length_character = parseInt(70*length/100);
        var length_number = parseInt(20*length/100);
        var length_special_characters = parseInt(length-(length_character+length_number));
        if(length_special_characters<1){
          length_special_characters = 1;
        }
        password += RandomNumber(length_number,shuffle(shuffle(number_regex)));
        password += RandomNumber(length_character,shuffle(shuffle(character_regex)));
        password += RandomNumber(length_special_characters,shuffle(shuffle(special_characters_regex)));
    }
    if((br == 0)&&($('#characters').is(":checked"))&&($('#numbers').is(":checked"))&&(!$('#special_characters').is(":checked"))){
      br = 1;
        var length_character = parseInt(80*length/100);
        var length_number = parseInt(length-length_character);
        if(length_number<1){
          length_number = 1;
        }
        password += RandomNumber(length_number,shuffle(shuffle(number_regex)));
        password += RandomNumber(length_character,shuffle(shuffle(character_regex)));
    }
    if((br == 0)&&($('#characters').is(":checked"))&&(!$('#numbers').is(":checked"))&&($('#special_characters').is(":checked"))){
        br = 1;
        var length_character = parseInt(90*length/100);
        var length_special_characters = parseInt(length-length_character);
        if(length_special_characters<1){
          length_special_characters = 1;
        }
        password += RandomNumber(length_character,shuffle(shuffle(character_regex)));
        password += RandomNumber(length_special_characters,shuffle(shuffle(special_characters_regex)));
    }
    if((br == 0)&&(!$('#characters').is(":checked"))&&($('#numbers').is(":checked"))&&($('#special_characters').is(":checked"))){
        br = 1;
        var length_number = parseInt(70*length/100);
        var length_special_characters = parseInt(length-length_number);
        if(length_special_characters<1){
          length_special_characters = 1;
        }
      password += RandomNumber(length_number,shuffle(shuffle(number_regex)));
        password += RandomNumber(length_special_characters,shuffle(shuffle(special_characters_regex)));
    }
    if((br == 0)&&($('#characters').is(":checked"))){
      br = 1;
      password += RandomNumber(length,shuffle(shuffle(character_regex)));
    }
    if((br == 0)&&($('#numbers').is(":checked"))){
      br = 1;
      password += RandomNumber(length,shuffle(shuffle(number_regex)));          
    }
    if((br == 0)&&($('#special_characters').is(":checked"))){
      br = 1;
      password += RandomNumber(length,shuffle(shuffle(special_characters_regex)));          
    }

    if($('#uppercase').is(":checked")){
      password = password.toUpperCase();
    }
    $('input[name="password"]').val(shuffle(shuffle(password)));
 }
  $(document).ready(function() {
  const $valueSpan = $('.value_length');
  const $value = $('#length');
  $valueSpan.html($value.val());
  $value.on('input change', () => {

    $valueSpan.html($value.val());
  });
});
 /**
 * shuffle
 */
function shuffle(string) {
  "use strict";
    var a = string.split(""),
        n = a.length;

    for(var i = n - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var tmp = a[i];
        a[i] = a[j];
        a[j] = tmp;
    }
    return a.join("");
}
 /**
 * open fields
 */
function open_fields(){
  "use strict";
  $('#custom_fields').modal();
}
    /**
 * create customfield
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