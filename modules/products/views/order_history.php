<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo htmlspecialchars($title); ?></h4>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                     <?php render_datatable([
                        _l('Invoice'),
                        _l('Customer'),
                        _l('Order Date'),
                        _l('Total in').' '.$base_currency->name,
                        _l('Status'),
                        ], 'order-history'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-order-history', window.location.href, [1], [1]);
   });
</script>
</body>
</html>
