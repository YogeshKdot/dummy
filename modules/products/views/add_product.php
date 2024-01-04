<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo htmlspecialchars($title); ?>
                        </h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open_multipart($this->uri->uri_string()); ?>
                        <?php echo render_input('product_name', 'product_name', $product->product_name ?? ''); ?>
                        <?php echo render_textarea('product_description', 'product_description', $product->product_description ?? ''); ?>
                        <div class="row">
                            <div class="col-md-5">
                                <?php echo render_select('product_category_id', $product_categories, ['p_category_id', 'p_category_name'], 'products_categories', !empty(set_value('product_category_id')) ? set_value('product_category_id') : $product->product_category_id ?? ''); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('rate', _l('invoice_item_add_edit_rate_currency'), $product->rate ?? '', 'number'); ?>
                            </div>
                            <div class="col-md-2">
                                <label>Tax</label>
                                <?php
                                    $selected_taxes ='';
                                    if (!empty($product->taxes)) {
                                        $selected_taxes = (!empty(($product->taxes))) ? unserialize($product->taxes) : '';
                                    }
                                      echo $this->misc_model->get_taxes_dropdown_template('taxes[]', $selected_taxes);
                                      ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo render_input('quantity_number', 'quantity', $product->quantity_number ?? '', 'number'); ?>
                            </div>
                        </div>
                        <?php
                            $existing_image_class = 'col-md-4';
                            $input_file_class     = 'col-md-8';
                            if (empty($product->product_image)) {
                                $existing_image_class = 'col-md-12';
                                $input_file_class     = 'col-md-12';
                            }
                            ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group select-placeholder"<?php if (isset($product) && !empty($product->is_recurring_from)) { ?> data-toggle="tooltip" data-title="<?php echo _l('create_recurring_from_child_error_message', [_l('invoice_lowercase'), _l('invoice_lowercase'), _l('invoice_lowercase')]); ?>"<?php } ?>>
                                    <label for="recurring" class="control-label">
                                    <?php echo _l('invoice_add_edit_recurring'); ?>
                                    </label>
                                    <select class="selectpicker"
                                        data-width="100%"
                                        name="recurring"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                                        <?php
                                            if (isset($product) && !empty($product->is_recurring_from)) {
                                                echo 'disabled';
                                            } ?>
                                        >
                                        <?php for ($i = 0; $i <= 12; ++$i) { ?>
                                        <?php
                                            $selected = '';
                                            if (isset($product)) {
                                                if (0 == $product->custom_recurring) {
                                                    if ($product->recurring == $i) {
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
                                        <option value="custom" <?php if (isset($product) && 0 != $product->recurring && 1 == $product->custom_recurring) {
                                                echo 'selected';
                                            } ?>><?php echo _l('recurring_custom'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="recurring_custom <?php if ((isset($product) && 1 != $product->custom_recurring) || (!isset($product))) {
                                                echo 'hide';
                                            } ?>">
                                <div class="col-md-2">
                                    <?php $value = (isset($product) && 1 == $product->custom_recurring ? $product->recurring : 1); ?>
                                    <?php echo render_input('repeat_every_custom', 'Number', $value, 'number', ['min'=>1]); ?>
                                </div>
                                <div class="col-md-5">
                                    <label>Select</label>
                                    <select name="repeat_type_custom" id="repeat_type_custom" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value="day" <?php if (isset($product) && 1 == $product->custom_recurring && 'day' == $product->recurring_type) {
                                                echo 'selected';
                                            } ?>><?php echo _l('invoice_recurring_days'); ?></option>
                                        <option value="week" <?php if (isset($product) && 1 == $product->custom_recurring && 'week' == $product->recurring_type) {
                                                echo 'selected';
                                            } ?>><?php echo _l('invoice_recurring_weeks'); ?></option>
                                        <option value="month" <?php if (isset($product) && 1 == $product->custom_recurring && 'month' == $product->recurring_type) {
                                                echo 'selected';
                                            } ?>><?php echo _l('invoice_recurring_months'); ?></option>
                                        <option value="year" <?php if (isset($product) && 1 == $product->custom_recurring && 'year' == $product->recurring_type) {
                                                echo 'selected';
                                            } ?>><?php echo _l('invoice_recurring_years'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div id="cycles_wrapper" class="<?php if (!isset($product) || (isset($product) && 0 == $product->recurring)) {
                                                echo ' hide';
                                            }?>">
                                <div class="col-md-12">
                                    <?php $value = (isset($product) ? $product->cycles : 0); ?>
                                    <div class="form-group recurring-cycles">
                                        <label for="cycles"><?php echo _l('recurring_total_cycles'); ?>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control"<?php if (0 == $value) {
                                                echo ' disabled';
                                            } ?> name="cycles" id="cycles" value="<?php echo $value; ?>" <?php if (isset($product) && $product->cycles > 0) {
                                                echo 'min="'.($product->cycles).'"';
                                            } ?>>
                                            <div class="input-group-addon">
                                                <div class="checkbox">
                                                    <input type="checkbox"<?php if (0 == $value) {
                                                echo ' checked';
                                            } ?> id="unlimited_cycles">
                                                    <label for="unlimited_cycles"><?php echo _l('cycles_infinity'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (!empty($product->product_image)) { ?>
                            <div class="<?php echo htmlspecialchars($existing_image_class); ?>">
                                <div class="existing_image">
                                    <label class="control-label">Existing Image</label>
                                    <img src="<?php echo base_url('modules/'.PRODUCTS_MODULE_NAME.'/uploads/'.$product->product_image); ?>" class="img img-responsive img-thubnail zoom"/>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="<?php echo htmlspecialchars($input_file_class); ?>">
                                <div class="attachment">
                                    <div class="form-group">
                                        <label for="attachment" class="control-label"><small class="req text-danger">* </small><?php echo _l('product_image'); ?></label>
                                        <input type="file" extension="png,jpg,jpeg,gif" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="product" id="product" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    var mode = '<?php echo $this->uri->segment(3, 0); ?>';
    (mode == 'add_product') ? $('input[type="file"]').prop('required',true) : $('input[type="file"]').prop('required',false);
    $(function () {
    appValidateForm($('form'), {
      product_name        : "required",
      product_description : "required",
      product_category_id : "required",
      rate                : "required",
      quantity_number     : "required"
    });
    });
</script>