(function($) {
    "use strict";
    
    // Predefined global variables
    var side_bar = $('#side-menu');
    $("body").on('change', '#mass_select_all', function() {
        var to, rows, checked;
        to = $(this).data('to-table');

        rows = $('.table-' + to).find('tbody tr');
        checked = $(this).prop('checked');
        $.each(rows, function() {
            var input = $($($(this).find('td').eq(0)).find('input'));
            if(!input.is(':disabled')){
                input.prop('checked', checked);
            }
        });
    });
    // Check for active class in sidebar links
    var $linkSidebarActive = side_bar.find('li > a[href="' + location + '"]');
    if ($linkSidebarActive.length) {
        $linkSidebarActive.parents('li').not('.quick-links').addClass('active');
        // Set aria expanded to true
        $linkSidebarActive.prop('aria-expanded', true);
        $linkSidebarActive.parents('ul.nav-second-level').prop('aria-expanded', true);
        $linkSidebarActive.parents('li').find('a:first-child').prop('aria-expanded', true);
    }

    // Handle minimalize sidebar menu
    $('.hide-menu').click(function(e) {

        e.preventDefault();
        if ($('body').hasClass('hide-sidebar')) {
            $('body').removeClass('hide-sidebar').addClass('show-sidebar');
        } else {
            $('body').removeClass('show-sidebar').addClass('hide-sidebar');
        }
        
        // Fix columns going out of the table
        delay(function(){
            $($.fn.dataTable.tables(true)).DataTable().responsive.recalc();
        }, 300)
    });

if($('#dashboard-commission-chart').length > 0){
    var data = {};
  requestGet('affiliate/usercontrol/dashboard_commission_chart').done(function(response) {
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
        Highcharts.chart('commission_chart', {
         chart: {
             type: 'column'
         },
         title: {
             text: 'Transactions chart'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: response.month,
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: response.unit,
            data: response.data,
            showInLegend: false
         }]
     });
        
  });
} 
})(jQuery);

// General helper function for $.get ajax requests
function requestGet(uri, params) {
    "use strict";
    
    params = typeof(params) == 'undefined' ? {} : params;
    var options = {
        type: 'GET',
        url: uri.indexOf(site_url) > -1 ? uri : site_url + uri
    };
    return $.ajax($.extend({}, options, params));
}
