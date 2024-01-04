(function(){
  "use strict";
    var addMoreVendorsInputKey = $('.list_approve select[name^="type"]').length+1;

  $("body").on('click', '.new_vendor_requests', function() {
    if ($(this).hasClass('disabled')) { return false; }    
    var newattachment = $('.list_approve').find('#item_approve').eq(0).clone().appendTo('.list_approve');
    newattachment.find('button[role="combobox"]').remove();
    newattachment.find('select').selectpicker('refresh');

    newattachment.find('button[data-id="type[0]"]').attr('data-id', 'type[' + addMoreVendorsInputKey + ']');
    newattachment.find('label[for="type[0]"]').attr('for', 'type[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[name="type[0]"]').attr('name', 'type[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[id="type[0]"]').attr('id', 'type[' + addMoreVendorsInputKey + ']').selectpicker('refresh');

    newattachment.find('button[data-id="sub_type_2[0]"]').attr('data-id', 'sub_type_2[' + addMoreVendorsInputKey + ']');
    newattachment.find('label[for="sub_type_2[0]"]').attr('for', 'sub_type_2[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[name="sub_type_2[0]"]').attr('name', 'sub_type_2[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[id="sub_type_2[0]"]').attr('id', 'sub_type_2[' + addMoreVendorsInputKey + ']').selectpicker('refresh');

    newattachment.find('button[data-id="sub_type_1[0]"]').attr('data-id', 'sub_type_1[' + addMoreVendorsInputKey + ']');
    newattachment.find('label[for="sub_type_1[0]"]').attr('for', 'sub_type_1[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[name="sub_type_1[0]"]').attr('name', 'sub_type_1[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[id="sub_type_1[0]"]').attr('id', 'sub_type_1[' + addMoreVendorsInputKey + ']').selectpicker('refresh');

    newattachment.find('label[for="value[0]"]').attr('for', 'value[' + addMoreVendorsInputKey + ']');
    newattachment.find('input[name="value[0]"]').attr('name', 'value[' + addMoreVendorsInputKey + ']');
    newattachment.find('input[id="value[0]"]').attr('id', 'value[' + addMoreVendorsInputKey + ']').val('');

    newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newattachment.find('button[name="add"]').removeClass('new_vendor_requests').addClass('remove_vendor_requests').removeClass('btn-success').addClass('btn-danger');

    $('select[name="approver[' + addMoreVendorsInputKey + ']"]').change(function(){
        if($(this).val() == 'specific_personnel'){
          $('#is_staff_' + $(this).attr('data-id')).removeClass('hide');
        }else{
          $('#is_staff_' + $(this).attr('data-id')).addClass('hide');
        }
    });

    addMoreVendorsInputKey++;
  });

    $("body").on('click', '.remove_vendor_requests', function() {
        $(this).parents('#item_approve').remove();
    });

    appValidateForm($('.segment-form'), 
    {
      name: 'required', 
      category: 'required', 
    });
})(jQuery);