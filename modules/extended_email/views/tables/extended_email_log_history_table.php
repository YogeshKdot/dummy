<?php

defined('BASEPATH') || exit('No direct script access allowed');

$aColumns = [
    'staffid',
    'email_userid',
    'description',
    'datetime',
    ];

$sIndexColumn = 'id';
$sTable       = db_prefix().'extended_email_log_activity';

$additionalSelect = [
    'id',
];

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row                = [];
    $row[]              = get_staff_full_name($aRow['staffid']);
    $row[]              = get_staff_full_name($aRow['email_userid']);
    $row[]              = _l($aRow['description']);
    $row[]              = date('Y-m-d h:i A', strtotime($aRow['datetime']));
    $output['aaData'][] = $row;
}
