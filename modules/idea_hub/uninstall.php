<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_challenges`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_ideas`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_stages`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_status`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_category`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_challenges_votes`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_ideas_visibility`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_ideas_votes`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_ideas_comments`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_ideas_tags`');
$CI->db->query('DROP TABLE `' . db_prefix() . 'idea_hub_attachments`');