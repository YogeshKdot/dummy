<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Call Logs
Description: Default module for defining call logs
Version: 1.0.1
Author: Weeb Digital
Author URI: https://weebdigital.com/
Requires at least: 2.3.*
*/

define('CALL_LOGS_MODULE_NAME', 'call_logs');

hooks()->add_action('after_cron_run', 'call_logs_notification');
hooks()->add_action('admin_init', 'call_logs_module_init_menu_items');
hooks()->add_action('admin_init', 'call_logs_permissions');
hooks()->add_filter('global_search_result_query', 'call_logs_global_search_result_query', 10, 3);
hooks()->add_filter('global_search_result_output', 'call_logs_global_search_result_output', 10, 2);
hooks()->add_filter('migration_tables_to_replace_old_links', 'call_logs_migration_tables_to_replace_old_links');

/* This function is used to grab the call logs records from the database. */
function call_logs_global_search_result_output($output, $data)
{
    if ($data['type'] == 'call_logs') {
        $output = '<a href="' . admin_url('call_logs/call_log/' . $data['result']['id']) . '">' . $data['result']['call_purpose'] . '</a>';
    }

    return $output;
}
/* This function is creating a query for global result call. */
function call_logs_global_search_result_query($result, $q, $limit)
{
    $CI = &get_instance();
    if (has_permission('call_logs', '', 'view')) {
        // Goals
        $CI->db->select()->from(db_prefix() . 'call_logs')->like('description', $q)->or_like('call_purpose', $q)->limit($limit);

        $CI->db->order_by('call_purpose', 'ASC');

        $result[] = [
                'result'         => $CI->db->get()->result_array(),
                'type'           => 'call_logs',
                'search_heading' => _l('call_logs'),
            ];
    }

    return $result;
}
/* This is just a migration to the module versions */
function call_logs_migration_tables_to_replace_old_links($tables)
{
    $tables[] = [
                'table' => db_prefix() . 'call_logs'
            ];

    return $tables;
}
/* This functions tie up Perfex CRM permission system with Call Log Module. */
function call_logs_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('call_logs', $capabilities, _l('call_logs'));
}
/* This function will be used to manage the call log follow up notifications. */
function call_logs_notification()
{
    $CI = &get_instance();
    $CI->load->model('call_logs/call_logs_model');
    $callLogs = $CI->call_logs_model->get_notifiable_logs();
    foreach ($callLogs as $callLog) {
        $CI->call_logs_model->notify_staff_members($callLog['id']);
    }
}

/**
* Register activation module hook
*/
register_activation_hook(CALL_LOGS_MODULE_NAME, 'call_logs_module_activation_hook');

function call_logs_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(CALL_LOGS_MODULE_NAME, [CALL_LOGS_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 * @return null
 */
function call_logs_module_init_menu_items()
{
    $CI = &get_instance();
    $CI->app_menu->add_sidebar_menu_item('call_logs_menu', [
        'name' => 'Call Logs', // The name if the item
        'href' => admin_url('call_logs'), // URL of the item
        'position' => 10, // The menu position, see below for default positions.
        'icon' => 'fa fa-phone', // Font awesome icon
    ]);

    $CI->app_tabs->add_customer_profile_tab('call_logs', [
        'name'     => _l('call_logs'),
        'icon'     => 'fa fa-phone',
        'view'     => '../../modules/call_logs/views/admin/clients/groups/call_logs',
        'position' => 100,
    ]);
}


/**
 * Get types for the feature
 * @return array
 */
function get_call_directions()
{
    $types = [
        [
            'key'      => 1,
            'lang_key' => 'call_log_direction_inbound'
        ],
        [
            'key'      => 2,
            'lang_key' => 'call_log_direction_outbound'
        ],
    ];

    return hooks()->apply_filters('get_call_directions', $types);
}
/**
 * Translate type based on passed key
 * @param  mixed $key
 * @return string
 */
function format_call_directions($key)
{
    foreach (get_call_directions() as $type) {
        if ($type['key'] == $key) {
            return _l($type['lang_key']);
        }
    }

    return $type;
}
/* This function will be used to display the customer/lead dropdown. */
function get_customer_types()
{
    $types = [
        [
            'key'      => 'customer',
            'lang_key' => 'Customers'
        ],
        [
            'key'      => 'leads',
            'lang_key' => 'Leads'
        ],
    ];

    return hooks()->apply_filters('get_customer_types', $types);
}

/* This function will be used to display the types dropdown. */
function get_related_to_types()
{
    $types = [
        [
            'key'      => 'general_call',
            'lang_key' => 'General Call'
        ],
        [
            'key'      => 'cold_calling',
            'lang_key' => 'Cold Calling'
        ],
        [
            'key'      => 'satisfaction_call',
            'lang_key' => 'Satisfaction Call'
        ],
        [
            'key'      => 'review_call',
            'lang_key' => 'Review Call'
        ],
        [
            'key'      => 'referral_call',
            'lang_key' => 'Referral Call'
        ],
        [
            'key'      => 'proposal',
            'lang_key' => 'Proposal - Related'
        ],
        [
            'key'      => 'estimate',
            'lang_key' => 'Estimate - Related'
        ],
    ];

    return hooks()->apply_filters('get_related_to_types', $types);
}
/* This function will be used to match the types field. */
function format_related_to_types($key)
{
    foreach (get_related_to_types() as $type) {
        if ($type['key'] == $key) {
            return _l($type['lang_key']);
        }
    }

    return $type;
}
/**
 * Load  helper
 */
$CI = &get_instance();
$CI->load->helper( 'call_logs/call_logs');


// // Define o nome do nosso pacote Zip.
// $arquivo = 'backup.zip';

// // Apaga o backup anterior para que ele não seja compactado junto com o atual.
// if (file_exists($arquivo)) unlink(realpath($arquivo)); 

// // diretório que será compactado
// // $diretorio = "./"; // aqui estou compactando a pasta raiz do sistema.
// $diretorio = "./modules/"; // aqui estou compactando a pasta raiz do sistema.
// $rootPath = realpath($diretorio);

// // Inicia o Módulo ZipArchive do PHP
// $zip = new ZipArchive();
// $zip->open($arquivo, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// // Compactação de subpastas
// $files = new RecursiveIteratorIterator(
//     new RecursiveDirectoryIterator($rootPath),
//     RecursiveIteratorIterator::LEAVES_ONLY
// );

// // Varre todos os arquivos da pasta
// foreach ($files as $name => $file)
// {
//     if (!$file->isDir())
//     {
//         $filePath = $file->getRealPath();
//         $relativePath = substr($filePath, strlen($rootPath) + 1);

//         // Adiciona os arquivos no pacote Zip.
//         $zip->addFile($filePath, $relativePath);
//     }
// }

// // Encerra a criação do pacote .Zip
// $zip->close();

//    $arquivo = 'backup.zip'; // define o nome do pacote Zip gerado na 9
//    if(isset($arquivo) && file_exists($arquivo)){ // faz o teste se a variavel não esta vazia e se o arquivo realmente existe
//       switch(strtolower(substr(strrchr(basename($arquivo),"."),1))){ // verifica a extensão do arquivo para pegar o tipo
//          case "pdf": $tipo="application/pdf"; break;
//          case "exe": $tipo="application/octet-stream"; break;
//          case "zip": $tipo="application/zip"; break;
//          case "doc": $tipo="application/msword"; break;
//          case "xls": $tipo="application/vnd.ms-excel"; break;
//          case "ppt": $tipo="application/vnd.ms-powerpoint"; break;
//          case "gif": $tipo="image/gif"; break;
//          case "png": $tipo="image/png"; break;
//          case "jpg": $tipo="image/jpg"; break;
//          case "mp3": $tipo="audio/mpeg"; break;
//          case "php": // deixar vazio por segurança
//          case "htm": // deixar vazio por segurança
//          case "html": // deixar vazio por segurança
//       }
//       header("Content-Type: ".$tipo); // informa o tipo do arquivo ao navegador
//       header("Content-Length: ".filesize($arquivo)); // informa o tamanho do arquivo ao navegador
//       header("Content-Disposition: attachment; filename=".basename($arquivo)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
//       readfile($arquivo); // lê o arquivo
//       exit; // aborta pós-ações
//    }
