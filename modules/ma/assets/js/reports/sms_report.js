(function($) {
  "use strict";
    $.get(admin_url + 'ma/get_data_text_message_chart').done(function(res) {
    res = JSON.parse(res);

    Highcharts.chart('container_chart', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'Text message over time'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },
        time: {
            timezone: $('input[name=timezone]').val()
        },
        xAxis: {
            type: 'datetime',
            labels: {
              format: '{value:%Y-%m-%d}',
              rotation: 45,
              align: 'left'
            }
        },
        yAxis: {
            title: {
                text: 'Text message'
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'Text message',
            data: res.data_text_message
        }]
    });
  });

  init_sms_log_table();
})(jQuery);

function init_sms_log_table() {
"use strict";

 if ($.fn.DataTable.isDataTable('.table-sms-logs')) {
   $('.table-sms-logs').DataTable().destroy();
 }
 initDataTable('.table-sms-logs', admin_url + 'ma/sms_log_table', false, false, [], [3, 'desc']);
}