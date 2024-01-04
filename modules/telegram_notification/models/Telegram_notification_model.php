<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Telegram_notification_model extends App_Model
{


	public function __construct()
	{
		parent::__construct();
	}

	public function get_notification($notification_id)
	{
		$this->db->where('id', $notification_id);
		$result=$this->db->get(db_prefix() . 'notifications')->result_array();
		return $result[0];
	}
}
