<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php $hidden = ['id'=>$saas_plan->id ?? '']; ?>
        <?php echo form_open_multipart(admin_url(SUPERADMIN_MODULE.'/plans/save'), ['id' => 'saas_plan_form'], $hidden); ?>
        <div class="row">
            <div class="col-md-6">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                                <div class="clearfix"></div>
                                <hr class="">
                                <?php echo render_input('plan_name', 'plan_name', $saas_plan->plan_name ?? '', 'text', [], [], 'mbot10'); ?>
                                <?php echo render_textarea('plan_description', 'plan_description', $saas_plan->plan_description ?? '', [], [], 'mbot10'); ?>
                                <div class="form-group mbot10">
                                    <div class="">
                                        <div class="attachment">
                                            <div class="form-group">
                                                <label for="plan_image" class="control-label"><?php echo _l('product_image'); ?></label>
                                                <input type="file" extension="png,jpg,jpeg,gif" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="plan_image" id="plan_image">
                                                <div class="mtop10">
                                                    <img id="imgPreview" src="#" alt="pic" class="img img-responsive hide" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (isset($saas_plan)) { ?>
                                        <?php if (!empty($saas_plan->plan_image) && file_exists(module_dir_path(SUPERADMIN_MODULE, 'uploads/'.$saas_plan->plan_image))) { ?>
                                            <div class="existing_image">
                                                <img src="<?php echo base_url('modules/'.SUPERADMIN_MODULE.'/uploads/'.$saas_plan->plan_image); ?>" style='max-width: 90%;'/>
                                                <span id="remove_product_image" data-id="<?php echo $saas_plan->id; ?>" class="close mleft10"><i class="fa-solid fa-xmark text-danger"></i></span>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="form-group mbot10">
                                    <label for="price"><?php echo _l('price'); ?></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="price" value="<?php echo $saas_plan->price ?? ''; ?>">
                                        <div class="input-group-addon">
                                            <?php echo get_base_currency()->symbol; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    foreach ($currencies as $currency) {
                                        if (0 == $currency['isdefault']) { ?>
                                        <div class="form-group">
                                            <label for="price_<?php echo $currency['id']; ?>" class="control-label">
                                                <?php echo _l('invoice_item_add_edit_rate_currency', $currency['name']); ?></label>
                                                <input type="number" id="price_<?php echo $currency['id']; ?>" name="price_<?php echo $currency['id']; ?>" class="form-control" value="<?php echo $saas_plan->{'price_'.$currency['id']} ?? ''; ?>">
                                            </div>
                                    <?php }
                                        }
?>
                                <div class="form-group mbot10">
                                    <label>Tax</label>
                                    <?php
        $selected_taxes ='';
if (!empty($saas_plan->taxes)) {
    $selected_taxes = (!empty($saas_plan->taxes)) ? unserialize($saas_plan->taxes) : '';
}
echo $this->misc_model->get_taxes_dropdown_template('taxes[]', $selected_taxes);
?>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group select-placeholder"<?php if (isset($saas_plan) && !empty($saas_plan->is_recurring_from)) { ?> data-toggle="tooltip" data-title="<?php echo _l('create_recurring_from_child_error_message', [_l('invoice_lowercase'), _l('invoice_lowercase'), _l('invoice_lowercase')]); ?>"<?php } ?>>
                                            <label for="recurring" class="control-label">
                                            <?php echo _l('invoice_add_edit_recurring'); ?>
                                            </label>
                                            <select class="selectpicker"
                                                data-width="100%"
                                                name="recurring"
                                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                                                <?php
                if (isset($saas_plan) && !empty($saas_plan->is_recurring_from)) {
                    echo 'disabled';
                } ?>
                                                >
                                                <?php for ($i = 1; $i <= 12; ++$i) { ?>
                                                <?php
                $selected = '';
                                                    if (isset($saas_plan)) {
                                                        if (0 == $saas_plan->custom_recurring) {
                                                            if ($saas_plan->recurring == $i) {
                                                                $selected = 'selected';
                                                            }
                                                        }
                                                    }
                                                    if (0 == $i) {
                                                        $reccuring_string =  _l('invoice_add_edit_recurring_no');
                                                    } elseif (1 == $i) {
                                                        $reccuring_string = _l('invoice_add_edit_recurring_month', $i);
                                                    } else {
                                                        $reccuring_string = _l('invoice_add_edit_recurring_months', $i);
                                                    }
                                                    ?>
                                                <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $reccuring_string; ?></option>
                                                <?php } ?>
                                                <option value="custom" <?php if (isset($saas_plan) && 0 != $saas_plan->recurring && 1 == $saas_plan->custom_recurring) {
                                                    echo 'selected';
                                                } ?>><?php echo _l('recurring_custom'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="recurring_custom <?php if ((isset($saas_plan) && 1 != $saas_plan->custom_recurring) || (!isset($saas_plan))) {
                                        echo 'hide';
                                    } ?>">
                                        <div class="col-md-2">
                                            <?php $value = (isset($saas_plan) && 1 == $saas_plan->custom_recurring ? $saas_plan->recurring : 1); ?>
                                            <?php echo render_input('repeat_every_custom', 'Number', $value, 'number', ['min'=>1]); ?>
                                        </div>
                                        <div class="col-md-5">
                                            <label>Select</label>
                                            <select name="repeat_type_custom" id="repeat_type_custom" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                <option value="day" <?php if (isset($saas_plan) && 1 == $saas_plan->custom_recurring && 'day' == $saas_plan->recurring_type) {
                                                    echo 'selected';
                                                } ?>><?php echo _l('invoice_recurring_days'); ?></option>
                                                <option value="week" <?php if (isset($saas_plan) && 1 == $saas_plan->custom_recurring && 'week' == $saas_plan->recurring_type) {
                                                    echo 'selected';
                                                } ?>><?php echo _l('invoice_recurring_weeks'); ?></option>
                                                <option value="month" <?php if (isset($saas_plan) && 1 == $saas_plan->custom_recurring && 'month' == $saas_plan->recurring_type) {
                                                    echo 'selected';
                                                } ?>><?php echo _l('invoice_recurring_months'); ?></option>
                                                <option value="year" <?php if (isset($saas_plan) && 1 == $saas_plan->custom_recurring && 'year' == $saas_plan->recurring_type) {
                                                    echo 'selected';
                                                } ?>><?php echo _l('invoice_recurring_years'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label><?php echo _l('mark_as_most_popular'); ?></label>
                                        <div class="onoffswitch" data-toggle="tooltip" data-title="<?php echo _l('most_popular_switch'); ?>">
                                            <input type="checkbox" name="most_popular" class="onoffswitch-checkbox" id="most_popular" value="1" <?php echo (isset($saas_plan) && 1 == $saas_plan->most_popular) ? 'checked' : ''; ?>>
                                            <label class="onoffswitch-label" for="most_popular"></label>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <label><?php echo _l('enable_trial'); ?></label>
                                        <div class="onoffswitch" data-toggle="tooltip" data-title="<?php echo _l('trial_switch'); ?>">
                                            <input type="checkbox" name="trial" class="onoffswitch-checkbox" id="trial" value="1" <?php echo (isset($saas_plan) && 1 == $saas_plan->trial) ? 'checked' : ''; ?>>
                                            <label class="onoffswitch-label" for="trial"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h4 class="no-margin"><?php echo _l('payment_modes'); ?></h4>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading"/>
                                <div class="form-group mbot15 select-placeholder">
                                    <label for="allowed_payment_modes[]"
                                    class="control-label"><?php echo _l('invoice_add_edit_allowed_payment_modes'); ?></label>
                                    <br />
                                    <?php if (count($payment_modes) > 0) { ?>
                                        <select class="selectpicker"
                                        name="allowed_payment_modes[]" data-actions-box="true" multiple="true" data-width="100%"
                                        data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <?php foreach ($payment_modes as $mode) {
                                            $selected = '';
                                            if (isset($saas_plan)) {
                                                if ($saas_plan->allowed_payment_modes) {
                                                    $saas_modes = unserialize($saas_plan->allowed_payment_modes);
                                                    if (is_array($saas_modes)) {
                                                        foreach ($saas_modes as $_allowed_payment_mode) {
                                                            if ($_allowed_payment_mode == $mode['id']) {
                                                                $selected = ' selected';
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                if (1 == $mode['selected_by_default']) {
                                                    $selected = ' selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $mode['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo $mode['name']; ?></option>
                                                <?php
                                        } ?>
                                        </select>
                                    <?php } else { ?>
                                        <p><?php echo _l('invoice_add_edit_no_payment_modes_found'); ?></p>
                                        <a class="btn btn-primary" href="<?php echo admin_url('paymentmodes'); ?>">
                                            <?php echo _l('new_payment_mode'); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

             </div>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('limitations'); ?></h4>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <?php
                                $selected_limitations = [];
if (isset($saas_plan->limitations) && !empty($saas_plan->limitations)) {
    $selected_limitations = json_decode($saas_plan->limitations, true);
}
?>

                            <?php foreach ($limitations as $key => $value) { ?>
                                <?php
        $limitation_value = 0;
                                if (!empty($selected_limitations)) {
                                    $limitation_value = $selected_limitations[$key] ?? 0;
                                }
                                ?>
                                <div class="col-xs-6">
                                    <?php echo render_input('limitations['.$key.']', 'Allowed '.$value['label'], $limitation_value, 'number'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('allowed_modules'); ?></h4>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <?php if (!empty($modules)): ?>
                                <div class="col-md-12">
                                    <p class="text-danger"><?php echo _l('allowed_modules_notice') ?></p>
                                </div>
                                <?php foreach ($modules as $key => $value): ?>
                                    <div class="col-md-6">
                                        <div class="checkbox last:tw-mb-0">
                                            <input type="checkbox" name="modules[<?php echo $value['system_name'] ?>]" <?php echo (in_array($value['system_name'], $allowed_modules)) ? 'checked' : '' ?>>
                                            <label for="<?php echo $value['system_name'] ?>"><?php echo $value['headers']['module_name'] ?></label>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php else: ?>
                                <div class="col-md-12">
                                    <?php echo _l('no_modules_available'); ?>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-bottom-pusher"></div>
        <div class="btn-bottom-toolbar text-right">
            <button type="submit" class="btn btn-primary pull-right"><?php echo _l('save'); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php init_tail(); ?>

<script>
    $(function() {
        $('body').on('click', '#remove_product_image', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            if(confirm("Are you sure you want to remove this image")) {
                $.ajax({
                    url: `${admin_url}saas/plans/remove_product_image/${id}`,
                    type: 'POST',
                    dataType: 'json',
                })
                .done(function(res) {
                    if(res == true) {
                        alert_float('success', 'Image removed successfully');
                        $('.existing_image').hide();
                    }
                    if(res == false) {
                        alert_float('danger', 'Something went wrong');
                    }
                });
            }
        });
        
        appValidateForm($('#saas_plan_form'), {
            "plan_name"               : "required",
            "plan_description"        : "required",
            "price"                   : "required",
            "recurring"               : "required",
            "plan_description"        : "required",
            "allowed_payment_modes[]" : "required"
        });
    });
</script>
