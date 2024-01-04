<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'invoice_id',
    get_sql_select_client_company(),
    'order_date',
    db_prefix().'order_master.total as total',
    db_prefix().'order_master.status as status',
];
$join = [
    'LEFT JOIN '.db_prefix().'clients ON '.db_prefix().'clients.userid = '.db_prefix().'order_master.clientid',
    'LEFT JOIN '.db_prefix().'invoices ON '.db_prefix().'invoices.id = '.db_prefix().'order_master.invoice_id',
];
$sIndexColumn = 'id';
$sTable       = db_prefix().'order_master';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], [
    db_prefix().'order_master.id',
    db_prefix().'order_master.clientid',
    'deleted_customer_name',
]);
$output  = $result['output'];
$rResult = $result['rResult'];
$CI      = &get_instance();
$CI->load->model(['currencies_model']);
$base_currency = $CI->currencies_model->get_base_currency();
foreach ($rResult as $aRow) {
    $row   = [];
    $row[] = '<a href="'.admin_url('invoices/list_invoices/'.$aRow['invoice_id']).'" target="_blank">'.format_invoice_number($aRow['invoice_id']).'</a>';
    if (empty($aRow['deleted_customer_name'])) {
        $row[] = '<a href="'.admin_url('clients/client/'.$aRow['clientid']).'">'.$aRow['company'].'</a>';
    } else {
        $row[] = $aRow['deleted_customer_name'];
    }
    $row[]              = _d($aRow['order_date']);
    $row[]              = app_format_money($aRow['total'], $base_currency->name);
    $row[]              = format_invoice_status($aRow['status']);
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
