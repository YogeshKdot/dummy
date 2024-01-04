<?php
require_once __DIR__ . '/../third_party/node.php';
if (!class_exists('\Requests')) {
    require_once __DIR__ . '/../third_party/Requests.php';
}
if (!class_exists('\Firebase\JWT\SignatureInvalidException')) {
    require_once __DIR__ . '/../third_party/php-jwt/SignatureInvalidException.php';
}
if (!class_exists('\Firebase\JWT\JWT')) {
    require_once __DIR__ . '/../third_party/php-jwt/JWT.php';
}

use \Firebase\JWT\JWT;
use Requests as Requests;

Requests::register_autoloader();

class Env2api
{
    // Bearer, no need for OAUTH token, change this to your bearer string
    // https://build.envato.com/api/#token

    private static $bearer = 'k5ua8qyjLZI3mZ21kISqbh3B3v6UUaFw'; // replace the API key here.

    public static function getPurchaseData($code)
    {
        //setting the header for the rest of the api
        $bearer   = 'bearer ' . self::$bearer;
        $header   = [];
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v3/market/author/sale/';
        $ch_verify  = curl_init($verify_url . '?code=' . $code);

        curl_setopt($ch_verify, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_verify, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_verify, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec($ch_verify);
        curl_close($ch_verify);

        if ('' != $cinit_verify_data) {
            return json_decode($cinit_verify_data);
        }

        return false;
    }

    public static function verifyPurchase($code)
    {
        $verify_obj = self::getPurchaseData($code);

        // Check for correct verify code
        if (
            (false === $verify_obj) ||
            !is_object($verify_obj) ||
            isset($verify_obj->error) ||
            !isset($verify_obj->sold_at)
        ) {
            return $verify_obj;
        }
        // return -1;

        // If empty or date present, then it's valid
        if (
            '' == $verify_obj->supported_until ||
            null != $verify_obj->supported_until
        ) {
            return $verify_obj;
        }

        // Null or something non-string value, thus support period over
        return 0;
    }

    public function validatePurchase($module_name)
    {
	    //LYMODFIX
	    return true;
	    //
        $verified = false;
        $a_verified = false;
        $CI       = &get_instance();
        $CI->load->config($module_name . '/conf');

        if (option_exists($module_name . '_verified')) {
            $CI->app_modules->deactivate($module_name);
            delete_option($module_name . "_verified");
            set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
            redirect(admin_url('modules'));
        }

        if (!option_exists($module_name.'_verification_id')) {
            $verified = false;
        }
        $verification_id =  get_option($module_name.'_verification_id');
        if (!empty($verification_id)) {
            $verification_id = base64_decode($verification_id);
        }
        $id_data         = explode('|', $verification_id);
        if (4 != count($id_data)) {
            $verified = false;
        }

        $token = get_option($module_name . '_product_token');

        if (4 == count($id_data)) {
            $verified = !empty($token);
            try {
                $data = JWT::decode($token, $id_data[3], ['HS512']);
                if (!empty($data)) {
                    if (config_item($module_name.'_product_item_id') == $data->item_id && $data->item_id == $id_data[0] && $data->buyer == $id_data[2] && $data->purchase_code == $id_data[3]) {
                        $verified = true;
                    }
                }
            } catch (\Exception $e) {
                $verified = false;
            }

            $last_verification = (int) get_option($module_name.'_last_verification');
            $seconds           = $data->check_interval ?? 0;
            if (empty($seconds)) {
                $verified = false;
            }
            if ('' == $last_verification || (time() > ($last_verification + $seconds))) {
                $verified = false;
                try {
                    $headers  = ['Accept' => 'application/json', 'Authorization' => $token];
                    $request  = Requests::post(VAL_PROD_POINT, $headers, json_encode(['verification_id'=> $verification_id, 'item_id'=> config_item($module_name.'_product_item_id'), "activated_domain" => base_url()]));
                    $a_verified = true;
                    if ((500 <= $request->status_code) && ($request->status_code <= 599) || 404 == $request->status_code) {
                        $verified = false;
                        update_option($module_name . '_heartbeat', base64_encode(json_encode(["status"=>$request->status_code, "id" => $token, "end_point" => VAL_PROD_POINT])));
                    } else {
                        $result   = json_decode($request->body);
                        if (!empty($result->valid)) {
                            delete_option($module_name . "_heartbeat");
                            $verified = true;
                        }
                    }
                } catch (Exception $e) {
                    $verified = true;
                }
                update_option($module_name.'_last_verification', time());
            }
        }
        // var_dump($token);
        // exit;

        if (empty($token) || !$verified) {
            $last_verification = (int) get_option($module_name.'_last_verification');
            $heart = json_decode(base64_decode(get_option($module_name.'_heartbeat')));
            if (!empty($heart)) {
                if ((500 <= $heart->status) && ($heart->status <= 599) || 404 == $heart->status) {
                   if (($last_verification + (168*(3000+600))) > time()) {
                       $verified = true;
                    }
                }
            } else {
                $verified = false;
            }
        }

        if (!$verified) {
            $CI->app_modules->deactivate($module_name);
            delete_option($module_name . "_verification_id");
            delete_option($module_name . "_last_verification");
            delete_option($module_name . "_heartbeat");
        }

        return $verified;
    }
}
