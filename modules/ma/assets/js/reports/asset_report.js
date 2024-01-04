(function($) {
  "use strict";
$.get(admin_url + 'ma/get_data_asset_chart').done(function(res) {
    res = JSON.parse(res);
    Highcharts.chart('container_download_chart', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'Number of downloads over time'
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
                text: 'Number of downloads'
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
            name: 'Number of downloads',
            data: res.data_asset_download
        }]
    });
  });

    init_asset_download_table();
})(jQuery);

function init_asset_download_table() {
"use strict";

 if ($.fn.DataTable.isDataTable('.table-download-management')) {
   $('.table-download-management').DataTable().destroy();
 }
 initDataTable('.table-download-management', admin_url + 'ma/asset_download_table', false, false, [], [3, 'desc']);
}