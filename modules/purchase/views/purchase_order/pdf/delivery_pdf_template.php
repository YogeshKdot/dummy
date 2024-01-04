<?php

defined('BASEPATH') or exit('No direct script access allowed');

$base_currency = get_base_currency_pur();
$address = '';
$vendor_name = '';
$ship_to = '';
if($vendor){
    $countryName = '';
    if($country = get_country($vendor->country) ){
        $countryName = $country->short_name;
    }

    $address = $vendor->address.', '.$countryName;
    $vendor_name = $vendor->company;

    $ship_country_name = '';
    if($ship_country = get_country($vendor->shipping_country)){
        $ship_country_name = $ship_country->short_name;
    }
    $ship_to = $vendor->shipping_street.'  '.$vendor->shipping_city.'  '.$vendor->shipping_state.'  '.$ship_country_name;
    if($vendor->shipping_street == '' && $vendor->shipping_city == '' && $vendor->shipping_state == ''){
        $ship_to = $address;
    }
}

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_left_column .= get_po_logo(150, "img img-responsive");
$info_left_column .= format_organization_info();

$info_right_column .= '<strong>'.mb_strtoupper($pur_order->pur_order_number).'</strong><br>';

// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(10);


$left_info = '';

// ship to to
$estimate_info = '';
$estimate_info .= '<br /><b>' . _l('pur_ship_to') . '</b>';
$estimate_info .= '<br>'. $ship_to;



$estimate_info .= '<br />' . _l('order_date') . ': ' . _d($pur_order->order_date) . '<br />';

pdf_multi_row($left_info, $estimate_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);


// The items table
$tblhtml = '<table class="table purorder-item">
        <thead>
          <tr style="background-color: #000000; color: white; height: 50px;">
            <th class="thead-dark">'._l('items').'</th>
            <th class="thead-dark">'._l('purchase_quantity').'</th>
          </tr>
          </thead>
          <tbody>';
           $t_mn = 0;
      foreach($pur_order_detail as $row){
        $items = get_items_by_id($row['item_code']);
        $des_html = ($items) ? $items->commodity_code.' - '.$items->description : '';

        $units = get_units_by_id($row['unit_id']);
        $unit_name = isset($units->unit_name) ? $units->unit_name : '';
        
        $tblhtml .= '<tr nobr="true" class="sortable">
            <td ><strong>'.$des_html.'</strong><br><span>'.$row['description'].'</span></td>
            <td >'.$row['quantity'].' '. $unit_name.'</td>
          </tr>';

        $t_mn += $row['total_money'];
      }  
      $tblhtml .=  '</tbody>
      </table><br><br>';

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$pdf->Ln(8);
