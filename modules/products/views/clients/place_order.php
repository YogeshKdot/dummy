<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s section-heading section-products">
    <div class="panel-body">
        <h4 class="no-margin section-text"><?php echo _l('place_order'); ?></h4>
    </div>
</div>
<?php if (!empty($message)) { ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>
<?php echo form_open(uri_string()); ?>
<?php echo form_hidden('clientid', $this->session->client_user_id); ?>
<?php $total = 0; ?>
<div class="panel_s">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
               <div class="table-responsive">
                  <table class="table items items-preview invoice-items-preview" data-type="invoice">
                     <thead>
                        <tr>
                           <th align="center">#</th>
                           <th class="description" width="50%" align="left">Item</th>
                           <th align="">Qty</th>
                           <th align="right">Rate</th>
                           <th align="right">Tax</th>
                           <th align="right"></th>
                           <th align="right">Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($products as $key => $product) { ?>
                            <?php if (!empty($product->quantity)) { ?>
                            <tr nobr="true">
                                <td align="center">
                                    <?php echo form_hidden('newitems['.$product->id.'][unit]', ''); ?>
                                    <?php echo form_hidden('newitems['.$product->id.'][order]', $key + 1); ?>
                                    <?php echo form_hidden('product_items['.$product->id.'][product_id]', $product->id); ?>
                                    <?php echo $key + 1; ?>
                                </td>
                                <td class="description" align="left;">
                                    <span ><strong><?php echo htmlspecialchars($product->product_name); ?></strong></span>
                                    <?php echo form_hidden('newitems['.$product->id.'][description]', $product->product_name); ?>
                                    <br>
                                    <span ><?php echo htmlspecialchars($product->product_description); ?></span>
                                    <?php echo form_hidden('newitems['.$product->id.'][long_description]', $product->product_description); ?>
                                </td>
                                <td align="" width="10%">
                                    <div class="order_qty"><?php echo form_hidden('product_items['.$product->id.'][qty]', $product->quantity); ?></div>
                                    <?php echo render_input('newitems['.$product->id.'][qty]', '', $product->quantity, 'number', ['data-rate'=>$product->rate, 'min'=>0, 'data-old-qty'=>$product->quantity, 'data-product_id'=>$product->id, 'max'=>$product->quantity_number], [], '', 'quantity'); ?>
                                    <?php echo render_select('newitems['.$product->id.'][taxname][]', $product->taxes, ['name', 'name'], '', array_column($product->taxes, 'name'), ['multiple'=>true], [], '', 'hidden product_taxes'); ?>
                                </td>
                                <td align="right">
                                    <?php
                                        echo form_hidden('newitems['.$product->id.'][rate]', $product->rate);
                                        echo form_hidden('product_items['.$product->id.'][rate]', $product->rate);
                                        $recurring_type = '';
                                        $cycles_text    = '';
                                        if (0 != $product->recurring) {
                                            $recurring_type = $product->recurring_type;
                                            if ('' == $product->recurring_type) {
                                                $recurring_type = 'month';
                                            }
                                            if (0 == $product->cycles) {
                                                $cycles_text = 'Infinite recurring';
                                            }
                                            if (1 == $product->cycles) {
                                                $cycles_text = '1 time totally';
                                            }
                                            if ($product->cycles > 1) {
                                                $cycles_text = $product->cycles.' times totally';
                                            }
                                            $recurring_type = ' / '.((1 != $product->recurring) ? $product->recurring : '').' '.$recurring_type;
                                        }
                                        echo htmlspecialchars($product->rate.$recurring_type);
                                        echo '<br>';
                                        echo htmlspecialchars($cycles_text);

                                    ?>        
                                </td>
                                <td align="right">
                                    <?php
                                        foreach ($product->taxes as $tax) {
                                            echo htmlspecialchars($tax['taxname'].'('.$tax['taxrate'].'%)').'<br>';
                                        }
                                    ?>
                                </td>
                                <td align="right">
                                    <button type="button" class="btn btn-sm btn-danger remove_cart" data-product_id="<?php echo htmlspecialchars($product->id); ?>" data-toggle="tooltip"  data-placement="right" title="Remove">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <td class="amount" align="right"><?php $total += $product->quantity * $product->rate; echo app_format_money($product->quantity * $product->rate, $base_currency->name); ?></td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <div class="col-md-6 col-md-offset-6">
                  <table class="table text-right">
                     <tbody>
                        <tr id="subtotal">
                            <td>
                                <?php echo form_hidden('subtotal', $total); ?>
                                <span class="bold">Subtotal</span>
                            </td>
                            <td class="subtotal">
                                <?php echo app_format_money($total, $base_currency->name); ?>
                            </td>
                        </tr>
                        <?php foreach ($all_taxes as $tax) { ?>
                            <tr class="tax-area">
                                <td>
                                    <span class="bold"><?php echo htmlspecialchars($tax['taxname'].'('.$tax['taxrate'].'%)'); ?></span>
                                </td>
                                <td>
                                    <?php $total += array_sum($init_tax[$tax['name']]); echo app_format_money(array_sum($init_tax[$tax['name']]), $base_currency->name); ?>    
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                           <td><span class="text-danger bold">Payable Amount</span></td>
                           <td>
                                <?php echo form_hidden('total', $total); ?>
                                <span class="text-danger total"><?php echo app_format_money($total, $base_currency->name); ?></span>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
        </div>
    </div>
</div>
<div class="panel_s section-heading section-products">
    <div class="panel-body">
        <button class="btn btn-success pull-right"><i class="fa fa-check"></i> <?php echo _l('place_order'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/accounting.js/accounting.js'); ?>"></script>
<script type="text/javascript">
    "use strict";
    var base_currency = <?php echo htmlspecialchars($base_currency->id); ?>
</script>
<script type="text/javascript" src="<?php echo module_dir_url('products', 'assets/js/place_order.js'); ?>"></script>
<script type="text/javascript">
</script>