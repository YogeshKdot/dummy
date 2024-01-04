window.addEventListener('load',function(){
        appValidateForm($('#mindmap-group-form'),{name:'required'},manage_groups);
        $('#mindmap-group-modal').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#mindmap-group-modal input[name="name"]').val('');
            $('#mindmap-group textarea').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });

        $('#mindmap-group-modal').on('show.bs.modal', function(e) {
            var type_id = $('#mindmap-group-modal').find('input[type="hidden"][name="id"]').val();
            if (typeof(type_id) !== 'undefined') {
                $('#mindmap-group-modal .add-title').addClass('hide');
                $('#mindmap-group-modal .edit-title').removeClass('hide');
            }else{
                $('#mindmap-group-modal .add-title').removeClass('hide');
                $('#mindmap-group-modal .edit-title').addClass('hide');
            }
        });
    });
    function manage_groups(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);

            if(response.success == true){
                alert_float('success',response.message);
                if($('body').hasClass('diagramy') && typeof(response.id) != 'undefined') {
                    var category = $('#diagramy_group_id');
                    category.find('option:first').after('<option value="'+response.id+'">'+response.name+'</option>');
                    category.selectpicker('val',response.id);
                    category.selectpicker('refresh');
                }
            }

            if($.fn.DataTable.isDataTable('.table-mindmap-group')){
                $('.table-mindmap-group').DataTable().ajax.reload();
            }

            $('#mindmap-group-modal').modal('hide');
        });
        return false;
    }

    function new_group(){
        $('#mindmap-group-modal').modal('show');
        $('.edit-title').addClass('hide');
    }

    function edit_group(invoker,id){
        var name = $(invoker).data('name');
        var description = $(invoker).data('description');
        $('#additional').append(hidden_input('id',id));
        $('#mindmap-group-modal input[name="name"]').val(name);
        $('#mindmap-group-modal textarea').val(description);
        $('#mindmap-group-modal').modal('show');
        $('.add-title').addClass('hide');
    }