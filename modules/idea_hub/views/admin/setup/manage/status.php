<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                       <div class="_buttons">
                        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#conimp-status-modal"><?php echo _l('new_status'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="clearfix"></div>
                    <?php render_datatable(array(
                        _l('status'),
                        _l('color'),
                        _l('options'),
                    ),'conimp-status'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="conimp-status-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('idea_hub/statuses'),array('id'=>'conimp-status-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_status'); ?></span>
                    <span class="add-title"><?php echo _l('new_status'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','name'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_input('color','color', '', 'color'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo _l('close'); ?>
                </button>
                <button type="submit" class="btn btn-info">
                    <?php echo _l('submit'); ?>
                </button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    window.addEventListener('load',function(){
        appValidateForm($('#conimp-status-form'),{name:'required'},manage_status);
        $('#conimp-status-modal').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#conimp-status-modal input[name="name"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
        $('#conimp-status-modal').on('show.bs.modal', function(e) {
            var invoker = $(e.relatedTarget);
            var type_id = $('#conimp-status-modal').find('input[type="hidden"][name="id"]').val();
            if (typeof(type_id) !== 'undefined') {
                $('#conimp-status-modal .add-title').addClass('hide');
                $('#conimp-status-modal .edit-title').removeClass('hide');
            }else{
                $('#conimp-status-modal .add-title').removeClass('hide');
                $('#conimp-status-modal .edit-title').addClass('hide');
            }
        });
    });
    function manage_status(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
            }

            if($.fn.DataTable.isDataTable('.table-conimp-status')){
                $('.table-conimp-status').DataTable().ajax.reload();
            }

            $('#conimp-status-modal').modal('hide');
        });
        return false;
    }
    function new_status(){
        $('#conimp-status-modal').modal('show');
        $('.edit-title').addClass('hide');
    }
    function edit_status(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#conimp-status-modal input[name="name"]').val(name);
        $('#conimp-status-modal').modal('show');
        $('.add-title').addClass('hide');
    }
</script>
<?php init_tail(); ?>
<script>
 $(function(){
    initDataTable('.table-conimp-status', window.location.href, [1], [1]);
});
</script>
</body>
</html>