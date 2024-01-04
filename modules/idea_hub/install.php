<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* Idae Hub challenge Table */
if (!$CI->db->table_exists(db_prefix() . 'idea_hub_challenges')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_challenges` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`user_id` int(11) NOT NULL,
		`title` varchar(100) DEFAULT NULL,
		`cover_image` varchar(100) DEFAULT NULL,
		`description` text DEFAULT NULL,
		`deadline` DATETIME DEFAULT NULL,
		`status` ENUM("active","inactive","archived","closed") DEFAULT "active",
		`category_id` int(11) NOT NULL,
		`instruction` text DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

/* Idae Hub idea Table */
if (!$CI->db->table_exists(db_prefix() . 'idea_hub_ideas')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_ideas` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`challenge_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`user_type` ENUM("staff","customer") DEFAULT "staff",
		`title` varchar(100) DEFAULT NULL,
		`image` varchar(100) DEFAULT NULL,
		`cover_type` ENUM("image","video") DEFAULT "image",
		`video_thumbnail` varchar(100) DEFAULT NULL,
		`description` text DEFAULT NULL,
		`visibility` ENUM("private","public","custom") DEFAULT "private",
		`status_id` int(11) NOT NULL,
		`stage_id` int(11) NOT NULL,
		`bussiness_impact` text DEFAULT NULL,
		`goal` text DEFAULT NULL,
		`additional_info` text DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'idea_hub_ideas_visibility')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_ideas_visibility` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`customer_id` int(11) NOT NULL,
		`idea_id` int(11) NOT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'idea_hub_ideas_votes')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_ideas_votes` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`user_id` int(11) NOT NULL,
		`user_type` varchar(100) DEFAULT NULL,
		`idea_id` int(11) NOT NULL,
		`vote` ENUM("up","down") DEFAULT NULL,
		`rank` int(11) DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

/* Idea hub stage Table */
if (!$CI->db->table_exists(db_prefix() . 'idea_hub_stages')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_stages` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(100) DEFAULT NULL,
		`color` varchar(100) DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}
if (!$CI->db->table_exists(db_prefix() . 'idea_hub_status')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_status` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(100) DEFAULT NULL,
		`color` varchar(100) DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}
if (!$CI->db->table_exists(db_prefix() . 'idea_hub_category')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_category` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(100) DEFAULT NULL,
		`color` varchar(100) DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'idea_hub_attachments')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_attachments` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`idea_id` int(11) NOT NULL,
		`file_name` varchar(100) NOT NULL,
		`file_title` varchar(195) DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}
if (!$CI->db->table_exists(db_prefix() . 'idea_hub_ideas_tags')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_ideas_tags` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`idea_id` int(11) NOT NULL,
		`tag_name` varchar(100) NOT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'idea_hub_ideas_comments')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_ideas_comments` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`idea_id` int(11) NOT NULL,
		`discussion_type` varchar(10) NOT NULL,
		`parent` int(11) DEFAULT NULL,
		`created` datetime NOT NULL,
		`modified` datetime DEFAULT NULL,
		`content` text NOT NULL,
		`user_id` varchar(195) NOT NULL,
		`user_type` varchar(195) DEFAULT NULL,
		`contact_id` int(11) DEFAULT "0",
		`fullname` varchar(191) DEFAULT NULL,
		`file_name` varchar(191) DEFAULT NULL,
		`file_mime_type` varchar(70) DEFAULT NULL,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'idea_hub_challenges_votes')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'idea_hub_challenges_votes` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`challenge_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`user_type` varchar(100) DEFAULT NULL,
		`vote` ENUM("up","down") DEFAULT NULL,
		`added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if(!is_dir(IDEA_HUB_UPLOADS_FOLDER)){
	mkdir(IDEA_HUB_UPLOADS_FOLDER, 0777, TRUE);
	fopen(IDEA_HUB_UPLOADS_FOLDER . 'index.html', 'w');
	$fp = fopen(IDEA_HUB_UPLOADS_FOLDER . 'index.html', 'a+');
	if ($fp) {
		fclose($fp);
	}
}

if(!is_dir(IDEA_HUB_UPLOADS_CHALLENGES_FOLDER)){
	mkdir(IDEA_HUB_UPLOADS_CHALLENGES_FOLDER, 0777, TRUE);
	fopen(IDEA_HUB_UPLOADS_CHALLENGES_FOLDER . 'index.html', 'w');
	$fp = fopen(IDEA_HUB_UPLOADS_CHALLENGES_FOLDER . 'index.html', 'a+');
	if ($fp) {
		fclose($fp);
	}
}

if(!is_dir(IDEA_HUB_UPLOADS_IDEAS_FOLDER)){
	mkdir(IDEA_HUB_UPLOADS_IDEAS_FOLDER, 0777, TRUE);
	fopen(IDEA_HUB_UPLOADS_IDEAS_FOLDER . 'index.html', 'w');
	$fp = fopen(IDEA_HUB_UPLOADS_IDEAS_FOLDER . 'index.html', 'a+');
	if ($fp) {
		fclose($fp);
	}
}

if(!is_dir(IDEA_HUB_IDEAS_ATTACHMENT_FOLDER)){
	mkdir(IDEA_HUB_IDEAS_ATTACHMENT_FOLDER, 0777, TRUE);
	fopen(IDEA_HUB_IDEAS_ATTACHMENT_FOLDER . 'index.html', 'w');
	$fp = fopen(IDEA_HUB_IDEAS_ATTACHMENT_FOLDER . 'index.html', 'a+');
	if ($fp) {
		fclose($fp);
	}
}

if(!is_dir(IDEA_HUB_IDEAS_DISCUSSION_FOLDER)){
	mkdir(IDEA_HUB_IDEAS_DISCUSSION_FOLDER, 0777, TRUE);
	fopen(IDEA_HUB_IDEAS_DISCUSSION_FOLDER . 'index.html', 'w');
	$fp = fopen(IDEA_HUB_IDEAS_DISCUSSION_FOLDER . 'index.html', 'a+');
	if ($fp) {
		fclose($fp);
	}
}

if(!is_dir(IDEA_HUB_DISCUSSION_ATTACHMENT_FOLDER)){
	mkdir(IDEA_HUB_DISCUSSION_ATTACHMENT_FOLDER, 0777, TRUE);
	fopen(IDEA_HUB_DISCUSSION_ATTACHMENT_FOLDER . 'index.html', 'w');
	$fp = fopen(IDEA_HUB_DISCUSSION_ATTACHMENT_FOLDER . 'index.html', 'a+');
	if ($fp) {
		fclose($fp);
	}
}
if(!is_dir(IDEA_HUB_V_THUMBNAILS_FOLDER)){
	mkdir(IDEA_HUB_V_THUMBNAILS_FOLDER, 0777, TRUE);
	fopen(IDEA_HUB_V_THUMBNAILS_FOLDER . 'index.html', 'w');
	$fp = fopen(IDEA_HUB_V_THUMBNAILS_FOLDER . 'index.html', 'a+');
	if ($fp) {
		fclose($fp);
	}
}