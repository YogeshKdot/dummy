<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="panel_s mbot10">
                <div class="panel-body _buttons">
                    <a href="<?php echo admin_url('call_logs'); ?>" class="btn btn-default pull-left mleft5">
                        <?php echo _l('go_back'); ?>
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-4">
                            <div class="widget-wq">
                                <h4>Today's Calls</h4>
                                <div class="row text-center">
                                    <div class="col-xs-6"><div class="text-primary"><?php echo $daily_count['inbound'];?></div> <span style="display: block;">Inbound</span></div>
                                    <div class="col-xs-6"><div class="text-success"><?php echo $daily_count['outbound'];?></div> <span style="display: block;">Outbound</span></div>
                                </div>
                            </div>
                            <div class="widget-wq">
                                <h4>Weekly Calls</h4>
                                <div class="row text-center">
                                    <div class="col-xs-6"><div class="text-primary"><?php echo $week_count['inbound'];?></div> <span style="display: block;">Inbound</span></div>
                                    <div class="col-xs-6"><div class="text-success"><?php echo $week_count['inbound'];?></div> <span style="display: block;">Outbound</span></div>
                                </div>
                            </div>
                            <div class="widget-wq">
                                <h4>Monthly Calls</h4>
                                <div class="row text-center">
                                    <div class="col-xs-6"><div class="text-primary"><?php echo $month_count['inbound'];?></div> <span style="display: block;">Inbound</span></div>
                                    <div class="col-xs-6"><div class="text-success"><?php echo $month_count['inbound'];?></div> <span style="display: block;">Outbound</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="widget-wq">
                                <h3>Weekly calls</h3>
                                <div class="relative" style="max-height:335px;">
                                    <canvas class="chart" height="335" id="report-weekly-call-logs"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="clear10px"></div>
                            <div class="widget-wq">
                                <h3>Monthly calls</h3>
                                <div class="relative" style="max-height:400px;">
                                    <canvas class="chart" height="400" id="report-monthly-call-logs"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<link href="<?php echo module_dir_url('call_logs', 'assets/css/cl.css'); ?>" rel="stylesheet">

<script>
    $(function(){
        chartWeeklyCallLogs = new Chart($('#report-weekly-call-logs'),{
            type:'bar',
            data:<?php echo $weekly_chart_Date; ?>,
            options:{maintainAspectRatio:false,scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },}
        });

        chartMonthlyCallLogs = new Chart($('#report-monthly-call-logs'),{
            type:'bar',
            data:<?php echo $monthly_chart_Date; ?>,
            options:{maintainAspectRatio:false,scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },}
        });
    });
</script>
</body>
</html>
