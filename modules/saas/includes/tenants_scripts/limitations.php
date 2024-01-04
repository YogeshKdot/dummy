<?php

get_instance()->load->config('saas/features_limitation_config');
$limitations = config_item('limitations');

$defined_limitation = get_limitations();

foreach ($limitations as $module => $moduleDetails) {
    hooks()->add_filter($moduleDetails['hookName'], function ($data) use ($defined_limitation, $module, $moduleDetails) {
        if ($defined_limitation[$module] != -1 && $defined_limitation[$module] <= total_rows(db_prefix().$moduleDetails['dbTable'])) {
            access_denied();
        }

        return $data;
    });
}

hooks()->add_action('before_start_render_dashboard_content', function () {
    
    /* Load saas language line */
    get_instance()->db->select('name,value');
    get_instance()->db->where_in('name', ['mysql_host', 'mysql_port', 'active_language']);
    $row     = get_instance()->db->get(db_prefix().'options')->result();
    $options = array_column($row, 'value', 'name');

    get_instance()->lang->load('saas/saas', $options['active_language']);

    get_instance()->load->config('saas/features_limitation_config');
    $limitations = config_item('limitations');

    $defined_limitation = get_limitations();

    $html = '';
    foreach ($limitations as $key => $value) {
        if ((int)$defined_limitation[$key] < 0) {
            $defined_limitation[$key] = _l('unlimited');
        }
        $html .= '<div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium text-success">'. _l('total', $value['label']) .'</dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">'.total_rows(db_prefix().$value['dbTable']).'/'.($defined_limitation[$key] ?? 0).'</div>
                </dd>
            </div>
        </div>';
    }

    echo '<div class="" style="padding:20px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="tw-flex tw-justify-between tw-items-center tw-p-1.5"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                                <i class="fa-regular fa-folder"></i>
                                <span class="tw-text-neutral-700">'. _l('plan_details') .'</span>
                            </p>
                        </div>
                        <hr class="tw-my-0">
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-3 sm:tw-gap-5">'.$html.'</dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
});
