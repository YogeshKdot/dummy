<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Handles upload for expenses receipt
 * @param  mixed $id expense id
 * @return void
 */
function ma_handle_asset_attachments($id)
{
    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = MA_MODULE_UPLOAD_FOLDER . '/assets/' . $id . '/';
    $CI   = & get_instance();

    if (isset($_FILES['file']['name'])) {
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = $_FILES['file']['name'];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];

                $CI->misc_model->add_attachment_to_database($id, 'ma_asset', $attachment);
            }
        }
    }
}

function ma_get_category_name($id){
    $CI             = &get_instance();
    $sql = 'select * from '.db_prefix().'ma_categories where id = "'.$id.'"';
    $category = $CI->db->query($sql)->row();

    if($category){
        return $category->name;
    }else{
        return '';
    }
}


function ma_get_email_template_name($id){
    $CI             = &get_instance();
    $sql = 'select * from '.db_prefix().'ma_email_templates where id = "'.$id.'"';
    $category = $CI->db->query($sql)->row();

    if($category){
        return $category->name;
    }else{
        return '';
    }
}

function ma_get_asset_name($id){
    $CI             = &get_instance();
    $sql = 'select * from '.db_prefix().'ma_assets where id = "'.$id.'"';
    $category = $CI->db->query($sql)->row();

    if($category){
        return $category->name;
    }else{
        return '';
    }
}

function ma_get_text_message_name($id){
    $CI             = &get_instance();
    $sql = 'select * from '.db_prefix().'ma_text_messages where id = "'.$id.'"';
    $category = $CI->db->query($sql)->row();

    if($category){
        return $category->name;
    }else{
        return '';
    }
}