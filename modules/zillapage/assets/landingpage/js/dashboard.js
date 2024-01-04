(function($) {
    "use strict"; // Start of use strict

    Chart.defaults.global.defaultFontColor = '#858796';
    // Pie Chart Example
    var ctx = document.getElementById("devicePieChart");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: arr_str_device,
            datasets: [{
                data: arr_total_device,
                backgroundColor: arr_colors,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true
            },
            cutoutPercentage: 80,
        },
    });

})(jQuery); // End of use strict