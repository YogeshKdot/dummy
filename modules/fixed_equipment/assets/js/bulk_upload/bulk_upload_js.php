<script>
(function($) {
  "use strict";
  appValidateForm($('#import_form'),{bank_account: 'required', file_csv:{required:true,extension: "csv,xlsx"},source:'required',status:'required',company_id:'required'});
      // function 
      if('<?php echo html_entity_decode($active_language) ?>' == 'vietnamese')
      {
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'modules/fixed_equipment/uploads/file_sample/sample_import_<?php echo html_entity_decode($type) ?>_vi.xlsx" class="btn btn-primary" ><?php echo _l('fe_download_a_sample') ?></a>&nbsp;&nbsp;&nbsp;' );
      }else{
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'modules/fixed_equipment/uploads/file_sample/sample_import_<?php echo html_entity_decode($type) ?>_en.xlsx" class="btn btn-primary" ><?php echo _l('fe_download_a_sample') ?></a>&nbsp;&nbsp;&nbsp;' );
      }

    })(jQuery);
    function uploadfilecsv(){
      "use strict";
      if($("select[name=bank_account]").val() == ''){
        alert_float('warning', "<?php echo _l('please_select_bank_account') ?>");
        return false;
      }

      if(($("#file_csv").val() != '') && ($("#file_csv").val().split('.').pop() == 'xlsx')){
        var formData = new FormData();
        formData.append("file_csv", $('#file_csv')[0].files[0]);
        formData.append("csrf_token_name", $('input[name="csrf_token_name"]').val());
        formData.append("leads_import", $('input[name="leads_import"]').val());
        formData.append("bank_account", $('select[name="bank_account"]').val());
        formData.append("company_id", $('select[name="company_id"]').val());

    //show box loading
    
    $('#box-loading').show();
    $('button[id="uploadfile"]').attr( "disabled", "disabled" );

    $.ajax({ 
      url: admin_url + 'fixed_equipment/import_xlsx_item/<?php echo html_entity_decode($type) ?>', 
      method: 'post', 
      data: formData, 
      contentType: false, 
      processData: false
      
    }).done(function(response) {
      response = JSON.parse(response);
      //hide boxloading
      $('#box-loading').hide();
      $('button[id="uploadfile"]').removeAttr('disabled');

      $("#file_csv").val(null);
      $("#file_csv").change();
      $(".panel-body").find("#file_upload_response").html();

      if($(".panel-body").find("#file_upload_response").html() != ''){
        $(".panel-body").find("#file_upload_response").empty();
      };
      $( "#file_upload_response" ).append( "<h4><?php echo _l("fe_result") ?></h4><h5><?php echo _l('fe_line_number_entered') ?> :"+response.total_rows+" </h5>" );
      $( "#file_upload_response" ).append( "<h5><?php echo _l('fe_number_of_lines_successfully_entered') ?> :"+response.total_row_success+" </h5>" );
      $( "#file_upload_response" ).append( "<h5><?php echo _l('fe_line_number_entered_was_unsuccessful') ?> :"+response.total_row_error+" </h5>" );
      if(response.total_row_error > 0)
      {
        $( "#file_upload_response" ).append( '<a href="'+site_url +response.error_filename+'" class="btn btn-warning"  ><?php echo _l('fe_download_error_file') ?></a>' );
      }
      if(response.total_rows < 1){
        alert_float('warning', response.message);
      }
    });
  }else if($("#file_csv").val() == ''){
    alert_float('warning', "<?php echo _l('_please_select_a_file') ?>");
    $('button[id="uploadfile"]').prop('disabled', false);
  }
}

</script>