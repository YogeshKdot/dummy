<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'subject',
    'meeting_id',
	'timezone',
    'start_time',
    'duration',
    'agenda',
    'join_url',
    'meeting_id',
];
$sIndexColumn = 'id';
$sTable       = db_prefix().'zoom_client_meetings';
$filter       = [];
$where        = [];
$statusIds    = [];
$join         = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];



foreach ($rResult as $aRow) {
	$row =[];
    $row[]        = $aRow['subject'];
    $row[]        = $aRow['meeting_id'];
	$row[]        = $aRow['timezone'];
	$row[]        = $aRow['start_time'];
	$row[]        = $aRow['duration'];
	$row[]        = $aRow['agenda'];
	$row[]        = '<a href="'.$aRow['join_url'].'">Join</a>';
	$row[]        = '<a href="'.admin_url('zoom_meetings/delete_client_meeting/'.$aRow['meeting_id']).'">Delete</a>';
	
    
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}


