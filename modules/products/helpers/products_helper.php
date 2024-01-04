<?php

function handle_product_upload($product_id)
{
    $CI = &get_instance();
    if (isset($_FILES['product']['name']) && '' != $_FILES['product']['name']) {
        $path        = get_upload_path_by_type('products');
        $tmpFilePath = $_FILES['product']['tmp_name'];
        if (!empty($tmpFilePath) && '' != $tmpFilePath) {
            $path_parts  = pathinfo($_FILES['product']['name']);
            $extension   = $path_parts['extension'];
            $extension   = strtolower($extension);
            $filename    = 'product_'.$product_id.'.'.$extension;
            $newFilePath = $path.$filename;
            _maybe_create_upload_path($path);
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI->products_model->edit_product(['product_image'=>$filename], $product_id);

                return true;
            }
        }
    }

    return false;
}

function toPlainArray($arr)
{
    $output = "['";
    foreach ($arr as $val) {
        $output .= $val."', '";
    }
    $plain_array = substr($output, 0, -3).']';

    return $plain_array;
}
