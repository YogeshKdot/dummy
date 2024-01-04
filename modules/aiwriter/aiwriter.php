<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: AiWriter by SpaGreen
Description: Write content & support replay using AI.
Version: 2.0.0
Requires at least: 2.3.*
Author: SpaGreen Creative
Author URI: https://codecanyon.net/user/spagreen/portfolio
*/

include( __DIR__ . '/vendor/autoload.php');
use Orhanerday\OpenAi\OpenAi;

define('SPAGREEN_AIWRITER_MODULE_NAME', 'aiwriter');

define('SPAGREEN_AIWRITER_MODULE_UPLOAD_FOLDER', module_dir_path(SPAGREEN_AIWRITER_MODULE_NAME, 'uploads'));

hooks()->add_action('admin_init', 'aiwriter_permissions');
hooks()->add_action('app_admin_head', 'aiwriter_add_head_components');
hooks()->add_action('app_admin_footer', 'aiwriter_add_footer_components');
hooks()->add_action('admin_init', 'aiwriter_module_init_menu_items');

/**
* Register activation module hook
*/
register_activation_hook(SPAGREEN_AIWRITER_MODULE_NAME, 'aiwriter_module_activation_hook');

function aiwriter_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
    if (!$CI->db->table_exists(db_prefix() . 'aiwriter_usage_cases')) {
        $CI->db->query('CREATE TABLE `'.db_prefix() . 'aiwriter_usage_cases` (
      `id` int NOT NULL AUTO_INCREMENT,
      `usage_case` varchar(191) DEFAULT NULL, 
      `usage_case_key` varchar(191) DEFAULT NULL, 
      `is_default` int NOT NULL DEFAULT 0,
      `status` int NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`));');
    $CI->load->model(SPAGREEN_AIWRITER_MODULE_NAME.'/aiwriter_model');
    $CI->aiwriter_model->reset_usage_case();
    }
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(SPAGREEN_AIWRITER_MODULE_NAME, [SPAGREEN_AIWRITER_MODULE_NAME]);

function aiwriter_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'use' => _l('permission_use'),
            'setting'=> _l('update_setting')
    ];

    register_staff_capabilities('aiwriter', $capabilities, _l('aiwriter'));
}

function aiwriter_add_head_components(){
	
}


function aiwriter_add_footer_components(){

}

function aiwriter_module_init_menu_items()
{
    $CI = &get_instance();
    // Item for all clients
    if (has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'use') && has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'setting')):

        $CI->app_menu->add_sidebar_menu_item('AIWRITER', [
                'name'     => _l('AiWriter'),
                'icon'     => 'fa fa-pen-nib',
                'href'     => admin_url('#'),
                'position' => 150,
        ]);
        if (has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'use')):
            $CI->app_menu->add_sidebar_children_item('AIWRITER', [
                    'slug'     => 'writer',
                    'name'     => _l('writer'),
                    'href'     => admin_url('aiwriter/writer'),
            ]);
        endif;
        if (has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'setting')):
            $CI->app_menu->add_sidebar_children_item('AIWRITER', [
                'slug'     => 'aiwriter-usage-case',
                'name'     => _l('usage_cases'),
                'href'     => admin_url('aiwriter/case'),
            ]);
        endif;
        if (has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'setting')):
            $CI->app_menu->add_sidebar_children_item('AIWRITER', [
                    'slug'     => 'aiwriter-setting',
                    'name'     => _l('setting'),
                    'href'     => admin_url('aiwriter/setting'),
            ]);
        endif;

    endif;
    if (has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'use') && !has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'setting')):

        $CI->app_menu->add_sidebar_menu_item('AIWRITER', [
            'name'     => _l('AiWriter'),
            'icon'     => 'fa fa-pen-nib',
            'href'     => admin_url('aiwriter/writer'),
            'position' => 2,
        ]);
    endif;

    if (!has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'use') && has_permission(SPAGREEN_AIWRITER_MODULE_NAME, '', 'setting')):

        $CI->app_menu->add_sidebar_menu_item('AIWRITER', [
            'name'     => _l('aiwriter_setting'),
            'icon'     => 'fa fa-pen-nib',
            'href'     => admin_url('aiwriter/setting'),
            'position' => 2,
        ]);
    endif;
}
hooks()->add_action('app_init',SPAGREEN_AIWRITER_MODULE_NAME.'_init');
hooks()->add_action('pre_activate_module', SPAGREEN_AIWRITER_MODULE_NAME.'_pre_activate');
hooks()->add_action('pre_deactivate_module', SPAGREEN_AIWRITER_MODULE_NAME.'_pre_deactivate');

function aiwriter_init($module_name){

}

function aiwriter_pre_activate($module_name){

}

function aiwriter_pre_deactivate($module_name){

}
if(get_option('aiwriter_allow_for_client') == '1'):
    // client side
    hooks()->add_action('clients_init', 'aiwriter_clients_area_menu_items');

    function aiwriter_clients_area_menu_items()
    {
        // Item for all clients
        add_theme_menu_item('AIWRITER', [
            'name'     => 'AiWriter',
            'href'     => site_url('aiwriter/client'),
            'position' => 1,
        ]);
    }
endif;
hooks()->add_action('ticket_created', 'aiwriter_ticket_created');
function aiwriter_ticket_created($arg1='',$arg2=''){
    if(get_option('aiwriter_autoreply_on_opening_ticket') == '1'):
        $CI = & get_instance();
        $CI->load->model('tickets_model');
        $ticket_info = $CI->tickets_model->get_ticket_by_id($arg1);
        $message = $ticket_info->message;
        $company = $ticket_info->company;

        $apiKey             = get_option('aiwriter_openai_api_key');
        $limitText          = get_option('aiwriter_openai_limit_text');
        $prompt = "My Name is ".get_option('aiwriter_replay_from_name')."Our company name is ".get_option('companyname').".Client name is ".$company.".Write ticket replay about '".strip_tags($message)." '";

        $openAi = new OpenAi($apiKey);

        $result = $openAi->completion([
            'model'       => 'text-davinci-003',
            'prompt'      => $prompt,
            'max_tokens'  => (int)$limitText,
            'temperature' => 0
        ]);
        $result = json_decode($result, true);

        $text = '';
        foreach ($result['choices'] as $choice):
            $text .= $choice['text'];
        endforeach;
        $data['message']        = nl2br($text);
        $data['contactid']      = get_client_user_id();
        $data['userid']         = get_client_user_id();
        $data['status']         = 1;
        $CI->tickets_model->add_reply($data,$arg1,get_option('aiwriter_autoreply_staffid'));
        $CI->db->update(db_prefix().'tickets',array('adminread'=>0),array('ticketid'=>$arg1));
    endif;
}