<?php

defined('BASEPATH') || exit('No direct script access allowed');

$aColumns = [
    'plan_name',
    'plan_description',
    'plan_image',
    'price',
    'trial',
    'allowed_payment_modes',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'plan_management';

$additionalSelect = [
    'id',
    'recurring',
    'recurring_type',
];

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $descriptionOutput = '';
    $descriptionOutput .= '<a href="#" class="view" data-id="'.$aRow['id'].'">'.$aRow['plan_name'].'</a>';
    $descriptionOutput .= '</div>';
    $descriptionOutput .= '<div class="row-options">';
    $descriptionOutput .= '<a href="'.admin_url(SUPERADMIN_MODULE.'/plans/plan/').$aRow['id'].'" id="edit_saas_plan" data-id="'.$aRow['id'].'">'._l('edit').'</a>';
    $descriptionOutput .= ' | <a href="'.admin_url(SUPERADMIN_MODULE.'/plans/delete/'.$aRow['id']).'" class="text-danger _delete">'._l('delete').'</a>';
    $descriptionOutput .= '</div>';

    $row[] = $descriptionOutput;

    $row[] = $aRow['plan_description'];

    // $plan_image = '<div>';
    $plan_image = '';
    if (empty($aRow['plan_image']) || !file_exists(module_dir_path(SUPERADMIN_MODULE, 'uploads/'.$aRow['plan_image']))) {
        $plan_image .= '<span class="label" style="color:#a94442;border:1px solid #ddb4b3;background: #fcf8f8" task-status-table="4">'._l('no_image').'</span>';
    } else {
        $url = module_dir_url(SUPERADMIN_MODULE, '/uploads/'.$aRow['plan_image']);
        $plan_image .= '<a href="'.$url.'" target="_blank" data-lightbox="saas-plan-images" class="">';
        $plan_image .= '<img src='.$url.' width="150" height="auto" data-lightbox="test">';
        $plan_image .= '</a>';
    }

    $row[] = $plan_image;

    $row[] = app_format_money($aRow['price'], get_base_currency());

    if (1 == $aRow['trial']) {
        $trial = _l('yes');
    }
    if (0 == $aRow['trial']) {
        $trial = '-';
    }

    $row[] = $trial;

    $allowed_payment_modes = unserialize($aRow['allowed_payment_modes']);

    $replacements = [
        '1' => 'Bank',
    ];
    foreach ($allowed_payment_modes as $key => $value) {
        if (isset($replacements[$value])) {
            $allowed_payment_modes[$key] = $replacements[$value];
        }
    }

    $row[] = $allowed_payment_modes;

    $row[] = $aRow['recurring'].' '.$aRow['recurring_type'].'(s)';

    $row[] = '<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-title=' . site_url('authentication/register?plan=') . $aRow['id'] . ' data-placement="left" onclick="copyPlanURL(this)"><i class="fa-solid fa-copy"></i></button>';

    $output['aaData'][] = $row;
}
