<?php

defined('BASEPATH') || exit('No direct script access allowed');

if (\PHP_SAPI !== 'cli' || !defined('STDIN')) {
    if (!function_exists('getMyBaseUrl')) {
        function getMyBaseUrl()
        {
            $scheme = $_SERVER['REQUEST_SCHEME'] ?? $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? (('on' == strtolower($_SERVER['HTTPS'])) ? 'https' : 'http');

            return $scheme."://{$_SERVER['HTTP_HOST']}".str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        }
    }
    (!defined('APP_URL')) ? define('APP_URL', getMyBaseUrl()) : '';
    $config['base_url'] = getMyBaseUrl();
} else {
    $config['base_url'] = '';
}
