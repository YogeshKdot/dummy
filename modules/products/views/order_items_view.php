                  <table class="table items items-preview invoice-items-preview" data-type="invoice" border=1>
                     <thead>
                        <tr>
                           <th align="center">#</th>
                           <th class="description" width="50%" align="left">Item</th>
                           <th align="">Qty</th>
                           <th align="right">Rate</th>
                           <th align="right">Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($items as $key => $product) { ?>
                            <?php if (!empty($product->qty)) { ?>
                            <tr nobr="true">
                                <td align="center">
                                    <?php echo $key + 1; ?>
                                </td>
                                <td class="description" align="left;">
                                    <span ><strong><?php echo htmlspecialchars($product->product_name); ?></strong></span>
                                    <br>
                                    <span ><?php echo htmlspecialchars($product->product_description); ?></span>
                                </td>
                                <td align="right">
                                    <?php echo htmlspecialchars($product->qty); ?>        
                                </td>
                                <td align="right">
                                    <?php echo htmlspecialchars($product->rate); ?>        
                                </td>
                                <td class="amount" align="right"><?php echo app_format_money($product->qty * $product->rate, $base_currency->name); ?></td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                     </tbody>
                  </table>