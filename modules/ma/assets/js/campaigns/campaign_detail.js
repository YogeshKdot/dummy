var fnServerParams;
(function($) {
		"use strict";

		fnServerParams = {
        "campaign_id": '[name="campaign_id"]',
    };

    init_lead_table();

    $.get(admin_url + 'ma/get_data_campaign_detail_chart/'+$('input[name=campaign_id]').val()).done(function(res) {
        res = JSON.parse(res);
        Highcharts.chart('container_email', {
          chart: {
              type: 'area'
          },
          title: {
              text: 'Email Stats'
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
                  text: ''
              }
          },
          credits: {
              enabled: false
          },
          series: res.data_email
        });

        Highcharts.chart('container_text_message', {
          chart: {
              zoomType: 'x'
          },
          title: {
              text: 'SMS over time'
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
                  text: 'SMS'
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
              name: 'SMS',
              data: res.data_text_message
          }]
      });
        
Highcharts.chart('container_point_action', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'Point Action over time'
        },
        time: {
            timezone: $('input[name=timezone]').val()
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
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
                text: 'Point Action'
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
            name: 'Point Action',
            data: res.data_point_action
        }]
    });
    });
})(jQuery);

function init_lead_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-leads-campaign')) {
    $('.table-leads-campaign').DataTable().destroy();
  }
  initDataTable('.table-leads-campaign', admin_url + 'ma/leads_table', false, false, fnServerParams);
}