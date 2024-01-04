(function(){
    "use strict";
      var fnServerParams = {
      "mgt_id": "[name='mgt_id']"
     }
     initDataTable('.table-bank_account_share', admin_url + 'team_password/bank_account_share_table', false, false, fnServerParams, [0, 'desc']);
})(jQuery);

  /**
   * open modal to add share
   */
  function add_bank_account_share(){
    "use strict";
    $('.add-title').removeClass('hide');
    $('.update-title').addClass('hide');
    $('input[name="id"]').val('');
    $('input[name="not_in_the_system"]').prop('checked',false); 
    $('.email_fr').addClass('hide');
    $('.client_fr').removeClass('hide');
  	$('#bank_account_share').modal();
  }
  /**
   * switch input client or email
   */
  function open_frame(el){
    "use strict";
      if($(el).is(':checked')){
        $('.email_fr').removeClass('hide');
        $('.client_fr').addClass('hide');
      }
      else{
        $('.email_fr').addClass('hide');
        $('.client_fr').removeClass('hide');
      }
  }
  /**
   * open modal to edit share
   */
  function update(el){
    "use strict";
    $('.add-title').addClass('hide');
    $('.update-title').removeClass('hide');
    var id = $(el).data('id');
    $('input[name="id"]').val(id);
    if($(el).data('not_in_the_system') == 'on'){
        $('input[name="not_in_the_system"]').prop('checked',true); 
        $('.email_fr').removeClass('hide');
        $('.client_fr').addClass('hide');
        $('input[name="email"]').val($(el).data('email'));
    }
    else{
        $('input[name="not_in_the_system"]').prop('checked',false); 
        $('.client_fr').removeClass('hide');
        $('.email_fr').addClass('hide');
        $('select[name="client"]').val($(el).data('client')).change();
    }
    $('select[name="share_id"]').val($(el).data('share_id')).change();
    $('input[name="effective_time"]').val($(el).data('effective_time'));
    if($(el).data('read')=='on'){
      $('input[name="read"]').prop('checked',true);
    }
    else{
      $('input[name="read"]').prop('checked',false);      
    }

    if($(el).data('write')=='on'){
      $('input[name="write"]').prop('checked',true);
    }
    else{
      $('input[name="write"]').prop('checked',false);      
    }

    $('#bank_account_share').modal();
  }