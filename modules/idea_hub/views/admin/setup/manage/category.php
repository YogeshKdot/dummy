<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                     <div class="_buttons">
                        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#conimp-category-modal"><?php echo _l('new_category'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="clearfix"></div>
                    <?php render_datatable(array(
                        _l('category'),
                        _l('color'),
                        _l('options'),
                        ),'conimp-category'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="conimp-category-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('idea_hub/categories'),array('id'=>'conimp-category-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_category'); ?></span>
                    <span class="add-title"><?php echo _l('new_category'); ?></span>
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
        appValidateForm($('#conimp-category-form'),{name:'required'},manage_categorys);
        $('#conimp-category-modal').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#conimp-category-modal input[name="name"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
        $('#conimp-category-modal').on('show.bs.modal', function(e) {
            var invoker = $(e.relatedTarget);
            var type_id = $('#conimp-category-modal').find('input[type="hidden"][name="id"]').val();
            if (typeof(type_id) !== 'undefined') {
                $('#conimp-category-modal .add-title').addClass('hide');
                $('#conimp-category-modal .edit-title').removeClass('hide');
            }else{
                $('#conimp-category-modal .add-title').removeClass('hide');
                $('#conimp-category-modal .edit-title').addClass('hide');
            }
        });
    });
    function manage_categorys(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
            }

            if($.fn.DataTable.isDataTable('.table-conimp-category')){
                $('.table-conimp-category').DataTable().ajax.reload();
            }

            $('#conimp-category-modal').modal('hide');
        });
        return false;
    }

    function new_category(){
        $('#conimp-category-modal').modal('show');
        $('.edit-title').addClass('hide');
    }

    function edit_category(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#conimp-category-modal input[name="name"]').val(name);
        $('#conimp-category-modal').modal('show');
        $('.add-title').addClass('hide');
    }
</script>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-conimp-category', window.location.href, [1], [1]);
   });
</script>
</body>
</html>
