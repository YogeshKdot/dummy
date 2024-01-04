<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

// open AI API key
$aiwriter_openai_api_key = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_openai_api_key";')->row();
if(!$aiwriter_openai_api_key){
    add_option('aiwriter_openai_api_key', '');
}

// open AI API limit text
$aiwriter_openai_limit_text = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_openai_limit_text";')->row();
if(!$aiwriter_openai_limit_text){
    add_option('aiwriter_openai_limit_text', 256);
}

// Allow for client
$aiwriter_allow_for_client = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_allow_for_client";')->row();
if(!$aiwriter_allow_for_client){
    add_option('aiwriter_allow_for_client', '1');
}

// Allow for client without login
$aiwriter_allow_for_client_without_login = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_allow_for_client_without_login";')->row();
if(!$aiwriter_allow_for_client_without_login){
    add_option('aiwriter_allow_for_client_without_login', '1');
}

// send auto replay on opening ticket
$aiwriter_autoreply_on_opening_ticket = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_autoreply_on_opening_ticket";')->row();
if(!$aiwriter_autoreply_on_opening_ticket){
    add_option('aiwriter_autoreply_on_opening_ticket', '1');
}

// send auto replay staffid
$aiwriter_autoreply_on_opening_ticket = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_autoreply_on_opening_ticket";')->row();
if(!$aiwriter_autoreply_on_opening_ticket){
    add_option('aiwriter_autoreply_on_opening_ticket', '1');
}

// replay from name
$aiwriter_replay_from_name = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_replay_from_name";')->row();
if(!$aiwriter_replay_from_name){
    add_option('aiwriter_replay_from_name', 'Mr.AiWriter');
}
// demo_mode
$aiwriter_demo_mode = $CI->db->query('SELECT * FROM '.db_prefix() . 'options where name = "aiwriter_demo_mode";')->row();
if(!$aiwriter_demo_mode){
    add_option('aiwriter_demo_mode', '0');
}



