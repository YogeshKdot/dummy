<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'quantity_number',
    'product_name',
    'product_description',
    'p_category_name',
    'rate',
    'product_image',
];
$sIndexColumn = 'id';
$sTable       = db_prefix().'product_master';
$filter       = [];
$where        = [];
$statusIds    = [];
$join         = [
    'LEFT JOIN '.db_prefix().'product_categories ON '.db_prefix().'product_categories.p_category_id='.db_prefix().'product_master.product_category_id',
];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];
$CI      = &get_instance();
$CI->load->model(['currencies_model']);
$base_currency = $CI->currencies_model->get_base_currency();
foreach ($rResult as $aRow) {
    $row                = [];
    $low_stock_limit    = get_option('product_low_quantity');
    $middle_stock_limit = $low_stock_limit * 2;
    if ($aRow['quantity_number'] <= $low_stock_limit) {
        $row[] = "<button class='btn btn-xs btn-danger'><i class='fa fa-arrow-circle-down'></i> "._l('low_stock')." <i class='fa fa-arrow-circle-down'></i></button>";
    } elseif ($aRow['quantity_number'] <= $middle_stock_limit && $aRow['quantity_number'] >= $low_stock_limit) {
        $row[] = "<button class='btn btn-xs btn-warning'> <i class='fa fa-arrow-circle-left'></i> "._l('middle_stock')." <i class='fa fa-arrow-circle-right'></i></button>";
    } else {
        $row[] = "<button class='btn btn-xs btn-success'><i class='fa fa-arrow-circle-up'></i> "._l('high_stock')." <i class='fa fa-arrow-circle-up'></i></button>";
    }
    $row[]      = "<h5 class='bold'>".$aRow['quantity_number'].'</h5>';
    $outputName = $aRow['product_name'];
    $outputName .= '<div class="row-options">';
    $outputName .= '</div>';
    $row[]              = $outputName;
    $row[]              = "<img src='".module_dir_url('products', 'uploads')."/{$aRow['product_image']}' class='img-thumbnail img-responsive zoom' onerror=\"this.src='".module_dir_url('products', 'uploads')."/image-not-available.png'\">";
    $row[]              = $aRow['p_category_name'];
    $row[]              = app_format_money($aRow['rate'], $base_currency->name);
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
