<?php
/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Rave by flutterwave
Description: rave module for invoive payment for more info click on Author Name
Author: Techy4m
Author URI: https://codecanyon.net/user/techy4m/portfolio
Version: 1.0.0
Requires at least: 2.3.*
*/
/**
 * Module URL
 * e.q. https://crm-installation.com/module_name/
 * @param  string $module  module system name
 * @param  string $segment additional string to append to the URL
 * @return string
 */
register_payment_gateway('rave_gateway', 'rave');
register_language_files('rave', ['rave']);


