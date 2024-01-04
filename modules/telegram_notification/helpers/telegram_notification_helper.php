<?php
defined('BASEPATH') or exit('No direct script access allowed');

function send_telegram_notification($notification_id)
{
	$CI = &get_instance();
	$CI->load->model(Telegram_Notification . '/Telegram_notification_model');
	$CI->load->library('session');
	$notification = $CI->Telegram_notification_model->get_notification($notification_id);

	$additional_data = '';
	if (!empty($notification['additional_data'])) {
		$additional_data = unserialize($notification['additional_data']);

		$i = 0;
		foreach ($additional_data as $data) {
			if (strpos($data, '<lang>') !== false) {
				$lang = get_string_between($data, '<lang>', '</lang>');
				$temp = _l($lang);
				if (strpos($temp, 'project_status_') !== FALSE) {
					$status = get_project_status_by_id(strafter($temp, 'project_status_'));
					$temp = $status['name'];
				}
				$additional_data[$i] = $temp;
			}
			$i++;
		}
	}
	$description = $notification['from_fullname'] . " - " . _l($notification['description'], $additional_data);
	$link = admin_url($notification['link']);

	$message = $description . "\n\n" . $link;

	$telegram_enabled = get_option('telegram_notification_enabled');
	if ($telegram_enabled) {
		require(__DIR__ . "/../libraries/telegram-bot/vendor/autoload.php");
		$api_key = get_option('telegram_notification_token');
		$channel_link = get_option('telegram_notification_channel_link');
		$channel_link = str_replace('https://t.me/', '', $channel_link);
		if (!empty($api_key) && !empty($channel_link)) {
			$bot = new \TelegramBot\Api\BotApi($api_key);
			$chatId = "@$channel_link";
			$botresponse = $bot->sendMessage($chatId, $message);
			$CI->session->set_userdata('telegram_sent_link', $notification['link']);
		}
		
	}
}
