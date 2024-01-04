<?php

defined('BASEPATH') || exit('No direct script access allowed');
$aColumns = [
    'description',
    'recorded_at',
    'staffid',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'saas_activity_log';

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [db_prefix().'saas_activity_log.id']);
$output       = $result['output'];
$rResult      = $result['rResult'];
foreach ($rResult as $aRow) {
    $row                = [];
    $row[]              = $aRow['description'];

    $row[]              = _dt($aRow['recorded_at']);

    $row[]              = $aRow['staffid'];

    $output['aaData'][] = $row;
}
