<script>
var history_login_table_rp, history_send_code_table_rp,
login_per_month_rp, fnServerParams, report_from_choose;

var report_from = $('input[name="report-from"]');
var report_to = $('input[name="report-to"]');
var date_range = $('#date-range');

(function($) {
  "use strict";
  history_login_table_rp = $('#history_login_table_rp');
  history_send_code_table_rp = $('#history_send_code_table_rp');
  login_per_month_rp = $('#login_per_month_rp');
  report_from_choose = $('#report-time');

  fnServerParams = {
    "staff": '[name="staff"]',
    "staff_sc": '[name="staff_sc"]',
    "report_months": '[name="months-report"]',
    "report_from": '[name="report-from"]',
    "report_to": '[name="report-to"]',
    "year_requisition": "[name='year_requisition']",
  }

  $('select[name="staff"]').on('change', function() {
    gen_reports();
  });

  $('select[name="staff_sc"]').on('change', function() {
    gen_reports();
  });

  $('select[name="months-report"]').on('change', function() {
    if($(this).val() != 'custom'){
     gen_reports();
    }
   });

   $('select[name="year_requisition"]').on('change', function() {
     gen_reports();
   });

   $('select[name="login_status"]').on('change', function() {
     gen_reports();
   });

   $('select[name="staff_login"]').on('change', function() {
     gen_reports();
   });


   report_from.on('change', function() {
     var val = $(this).val();
     var report_to_val = report_to.val();
     if (val != '') {
       report_to.attr('disabled', false);
       if (report_to_val != '') {
         gen_reports();
       }
     } else {
       report_to.attr('disabled', true);
     }
   });

   report_to.on('change', function() {
     var val = $(this).val();
     if (val != '') {
       gen_reports();
     }
   });

   $('select[name="months-report"]').on('change', function() {
     var val = $(this).val();
     report_to.attr('disabled', true);
     report_to.val('');
     report_from.val('');
     if (val == 'custom') {
       date_range.addClass('fadeIn').removeClass('hide');
       return;
     } else {
       if (!date_range.hasClass('hide')) {
         date_range.removeClass('fadeIn').addClass('hide');
       }
     }
     gen_reports();
   });

})(jQuery);

function init_report(e, type) {
  "use strict";

   var report_wrapper = $('#report');

   if (report_wrapper.hasClass('hide')) {
        report_wrapper.removeClass('hide');
   }

   $('head title').html($(e).text());
   

   report_from_choose.addClass('hide');

   $('#year_requisition').addClass('hide');
   $('#login_status_div').addClass('hide');

  history_login_table_rp.addClass('hide');
  history_send_code_table_rp.addClass('hide');
  login_per_month_rp.addClass('hide');
  
  $('select[name="months-report"]').selectpicker('val', 'this_month').change();
   if (type == 'history_login_table_rp') {
   		report_from_choose.removeClass('hide');
   		history_login_table_rp.removeClass('hide');
   } else if(type == 'history_send_code_table_rp'){
   		report_from_choose.removeClass('hide');
   		history_send_code_table_rp.removeClass('hide');
   } else if(type == 'login_per_month_rp'){
   		login_per_month_rp.removeClass('hide');
   		$('#year_requisition').removeClass('hide');
   		$('#login_status_div').removeClass('hide');
   }
  gen_reports();
}

/**
 * { history login table rp post }
 */
function history_login_table_rp_post() {
  "use strict";
 if ($.fn.DataTable.isDataTable('.table-history_login')) {
   $('.table-history_login').DataTable().destroy();
 }
 initDataTable('.table-history_login', admin_url + 'mfa/history_login_table_rp', false, false, fnServerParams, [0, 'desc']);
}


/**
 * { history send code table rp post }
 */
function history_send_code_table_rp_post() {
  "use strict";
 if ($.fn.DataTable.isDataTable('.table-history_send_code')) {
   $('.table-history_send_code').DataTable().destroy();
 }
 initDataTable('.table-history_send_code', admin_url + 'mfa/history_send_code_table_rp', false, false, fnServerParams, [0, 'desc']);
}

function login_per_month_rp_post() {
  "use strict";

  var data = {};
  data.year = $('select[name="year_requisition"]').val();
  data.login_status = $('select[name="login_status"]').val();
  <?php if(is_admin()){ ?>
  data.staff_login = $('select[name="staff_login"]').val();
  <?php } ?>
  $.post(admin_url + 'mfa/login_per_month_rp', data).done(function(response) {
     response = JSON.parse(response);
        Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        
     Highcharts.chart('container_login_per_month_rp', {
         chart: {
             type: 'column'
         },
         title: {
             text: '<?php echo _l('login_per_month_rp'); ?>'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: ['<?php echo _l('mfa_month_1') ?>',
                '<?php echo _l('mfa_month_2') ?>',
                '<?php echo _l('mfa_month_3') ?>',
                '<?php echo _l('mfa_month_4') ?>',
                '<?php echo _l('mfa_month_5') ?>',
                '<?php echo _l('mfa_month_6') ?>',
                '<?php echo _l('mfa_month_7') ?>',
                '<?php echo _l('mfa_month_8') ?>',
                '<?php echo _l('mfa_month_9') ?>',
                '<?php echo _l('mfa_month_10') ?>',
                '<?php echo _l('mfa_month_11') ?>',
                '<?php echo _l('mfa_month_12') ?>'],
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: ''
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr><td>{series.name}: </td>' +
                 '<td><b>{point.y:.0f}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
            line: {
              dataLabels: {
                enabled: true
              },
              enableMouseTracking: false
            }
         },

         series:  response,
     });
        
  })
}

/**
 * { gen reports }
 */
function gen_reports() {
  "use strict";
 if (!history_login_table_rp.hasClass('hide')) {
   history_login_table_rp_post();
 } else if(!history_send_code_table_rp.hasClass('hide')){
   history_send_code_table_rp_post();
 } else if(!login_per_month_rp.hasClass('hide')){
   login_per_month_rp_post();
 }

}

</script>