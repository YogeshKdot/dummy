<?php
/*
 * Inject css file for superadmin module
*/
hooks()->add_action('app_admin_head', 'superadmin_add_head_components');
function superadmin_add_head_components()
{
    // check module is enable or not (refer install.php)
    if ('1' == get_option('superadmin_enabled')) {
        $CI = &get_instance();
        echo '<link href="'.module_dir_url(SUPERADMIN_MODULE, 'assets/css/superadmin.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
    }
}

/*
 * Inject Javascript file for superadmin module
*/
hooks()->add_action('app_admin_footer', 'tenants_load_js');
function tenants_load_js()
{
    if ('1' == get_option('superadmin_enabled')) {
        $CI = &get_instance();
        echo '<script src="'.module_dir_url(SUPERADMIN_MODULE, 'assets/js/superadmin.js').'?v='.$CI->app_scripts->core_version().'"></script>';
    }
}
