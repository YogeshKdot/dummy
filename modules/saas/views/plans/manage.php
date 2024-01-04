<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row mbot10">
            <div class="col-md-12">
                <a href="<?php echo admin_url(SUPERADMIN_MODULE.'/plans/plan'); ?>"
                    class="btn btn-primary pull-left new new-invoice-list mright5">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    <?php echo _l('add_new_saas_plan'); ?>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo render_datatable([
                                _l('plan_name'),
                                _l('plan_description'),
                                _l('plan_image'),
                                _l('price'),
                                _l('trial'),
                                _l('allowed_payment_modes'),
                                _l('repeat_every'),
                                _l('plan_link'),
                            ], 'plan_management');
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    // Initialize the plan management datatable
    initDataTable('.table-plan_management', admin_url + "saas/plans/plan_management_table", undefined, undefined);
    init_lightbox();
    function copyPlanURL(btn) {
        var textToCopy = $(btn).attr('data-title');
        var tempTextarea = $('<textarea>');
        tempTextarea.val(textToCopy);
        $('body').append(tempTextarea);
        tempTextarea.select();
        document.execCommand('copy');
        tempTextarea.remove();
        $(btn).text('Copied!').attr('disabled', true);;
        setTimeout(function() {
            $(btn).html('<i class="fa-solid fa-copy"></i>').attr('disabled', false);;
        }, 1000);
    }
</script>