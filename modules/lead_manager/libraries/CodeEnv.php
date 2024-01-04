<?php

defined('BASEPATH') or exit('No direct script access allowed');

class CodeEnv
{
	private static $personal_token = 'msIvJdiClmzzEqmlA9hFWtIxGsRKo21e';

	function verifyPurchase($name=null, $code=null)
	{
		//LYMODFIX
		$data['status'] = true;
		//
		if(!is_null($name) && is_null($code)){
			$CI       = &get_instance();
			$verified = false;
			if(!option_exists($name.'_is_verified') || get_option($name.'_is_verified') != 1){
				$CI->app_modules->deactivate($name);
			}
			return $verified;
		}
		$code= trim($code);
		$url = "https://api.envato.com/v3/market/author/sale?code=".$code;
		$curl = curl_init($url);
		$header = array();
		$header[] = 'Authorization: Bearer '.self::$personal_token;
		$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
		$header[] = 'timeout: 20';
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		$envatoRes = curl_exec($curl);
		curl_close($curl);
		$envatoRes = json_decode($envatoRes);
		$data['status'] = false;
		if(isset($envatoRes) && !empty($envatoRes)){
			$date = new DateTime($envatoRes->supported_until);
			$boughtdate = new DateTime($envatoRes->sold_at);
			$bresult = $boughtdate->format('Y-m-d H:i:s');
			$sresult = $date->format('Y-m-d H:i:s');
			if (isset($envatoRes->item->name)) {
				$data['status'] = true;
			} else {
				$data['status'] = false;
			}
		}else{
			$data['message'] = 'Wrong purchase key found!';
		}
		return $data;
	}
}