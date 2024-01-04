<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-self-end">
                        <?php echo $title; ?>
                    </h4>
                    <div>
                        <a href="#" data-toggle="modal" data-target="#reset_usage_case"
                           class="btn btn-warning mright5">
                            <?php echo _l('reset_usage_case'); ?>
                        </a>
                        <a href="#" data-toggle="modal" data-target="#auto_backup_config"
                           class="btn btn-primary mright5">
                            <?php echo _l('add_usage_case'); ?>
                        </a>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <table class="table dt-table" data-order-col="0" data-order-type="asc">
                            <thead>
                            <th>#</th>
                            <th><?php echo _l('usage_case'); ?></th>
                            <th><?php echo _l('is_default'); ?></th>
                            <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                            <?php $i= 1; foreach($this->aiwriter_model->get_usage_case_as_array() as $raw): ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $raw['usage_case']; ?></td>
                                    <td><?php if($raw['is_default'] =='1'): echo '<a href="#" class="btn btn-sm btn-primary">Yes</a>'; else: echo '<a href="#" class="btn btn-sm btn-default">No</a>'; endif;?></td>
                                    <td>
                                        <a href="<?php echo admin_url('aiwriter/edit_case/'.$raw['id']); ?>"
                                           class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 mright15">
                                            <i class="fa fa-pencil fa-lg"></i>
                                        </a>
                                        <a href="<?php echo admin_url('aiwriter/delete_case/'.$raw['id']); ?>"
                                           class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                            <i class="fa-regular fa-trash-can fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php $i++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="auto_backup_config" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('aiwriter/add_case')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('add_usage_case'); ?></h4>
            </div>
            <div class="modal-body">

                <?php echo render_input('usage_case', 'usage_case', '', 'text'); ?>
                <div class="form-group select-placeholder">
                    <label for="is_default" class="control-label"><?php echo _l('is_default'); ?></label>
                    <select name="is_default" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value="1" ><?php echo _l('yes'); ?></option>
                        <option value="0" selected><?php echo _l('no'); ?></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="reset_usage_case" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('aiwriter/reset_case')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('reset_usage_case_confirmation'); ?></h4>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-info"><?php echo _l('reset_hints'); ?></div>

                <button type="submit" class="btn btn-lg btn-danger "><?php echo _l('reset'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
</body>

</html>