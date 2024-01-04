<?php

defined('BASEPATH') or exit('No direct script access allowed');

 add_option('products_enabled', 1);
 add_option('product_menu_disabled', 0);
 add_option('product_low_quantity', 10);
$CI->db->query('SET foreign_key_checks = 0');
 if (!$CI->db->table_exists(db_prefix().'product_master')) {
     $CI->db->query('CREATE TABLE `'.db_prefix().'product_master` (
      `id` INT NOT NULL AUTO_INCREMENT ,
      `product_name` VARCHAR(200) NOT NULL , 
      `product_description` VARCHAR(200) NOT NULL , 
      `product_category_id` INT NOT NULL , 
      `rate` DECIMAL(15,2) NOT NULL , 
      `quantity_number` INT NOT NULL ,
      `product_image` VARCHAR(200) NULL DEFAULT NULL ,
       PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');

     $CI->db->query('ALTER TABLE `'.db_prefix().'product_master` ADD INDEX(`product_category_id`);');

     $CI->db->query('ALTER TABLE `'.db_prefix().'product_master` 
    	ADD FOREIGN KEY (`product_category_id`) 
    	REFERENCES `'.db_prefix().'product_categories`(`p_category_id`) 
    	ON DELETE CASCADE 
    	ON UPDATE CASCADE');
 }

if (!$CI->db->table_exists(db_prefix().'product_categories')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'product_categories` (
		`p_category_id` INT NOT NULL AUTO_INCREMENT , 
		`p_category_name` VARCHAR(50) NOT NULL ,
		`p_category_description` TEXT NOT NULL , 
		PRIMARY KEY (`p_category_id`)
	) ENGINE = InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'order_master')) {
    $CI->db->query('CREATE TABLE `'.db_prefix()."order_master` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `invoice_id` INT NOT NULL , 
    `clientid` INT NOT NULL , 
    `datecreated` DATETIME NOT NULL , 
    `order_date` DATE NOT NULL , 
    `subtotal` DECIMAL(15,2) NOT NULL , 
    `total` DECIMAL(15,2) NOT NULL , 
    `status` INT NOT NULL DEFAULT '1' , 
    PRIMARY KEY (`id`),
    INDEX (`invoice_id`), 
    INDEX (`clientid`)
    ) ENGINE = InnoDB DEFAULT CHARSET=".$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'order_items')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'order_items` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `order_id` INT NOT NULL , 
    `product_id` INT NOT NULL , 
    `qty` DECIMAL(15,2) NOT NULL , 
    `rate` DECIMAL(15,2) NOT NULL , 
    PRIMARY KEY (`id`), 
    INDEX (`order_id`), 
    INDEX (`product_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->field_exists('taxes', db_prefix().'product_master')) {
    $CI->db->query('ALTER TABLE `'.db_prefix().'product_master`
    ADD `taxes` VARCHAR(255) NOT NULL AFTER `rate`,
    ADD `recurring` INT NOT NULL DEFAULT "0" AFTER `product_image`,
    ADD `recurring_type` VARCHAR(10) NOT NULL AFTER `recurring`,
    ADD `custom_recurring` TINYINT(1) NOT NULL DEFAULT "0" AFTER `recurring_type`,
    ADD `cycles` INT NOT NULL DEFAULT "0" AFTER `custom_recurring`');
}

$CI->db->query('SET foreign_key_checks = 1');

$email_template[0]['type']     = 'order';
$email_template[0]['slug']     = 'order-to-admin';
$email_template[0]['language'] = 'english';
$email_template[0]['name']     = 'Success Order For Admin';
$email_template[0]['subject']  = 'Order Paid Successfully';
$email_template[0]['message']  = '<em>You received a new order {order_id} with a total amount of {total} {currency}  {invoice_number}{invoice_link}</em>';
$email_template[0]['fromname'] = '{companyname}';
$email_template[0]['active']   = '1';

$email_template[1]['type']     = 'order';
$email_template[1]['slug']     = 'order-to-client';
$email_template[1]['language'] = 'english';
$email_template[1]['name']     = 'Success Order For Customer';
$email_template[1]['subject']  = 'Order Placed Successfully';
$email_template[1]['message']  = '<em>Your payment for order {order_id} is paid through {invoice_number}{invoice_link} with a total amount of {total} {currency}</em>';
$email_template[1]['fromname'] = '{companyname}';
$email_template[1]['active']   = '1';

$CI->db->where('type', 'order');
$result = $CI->db->get(db_prefix().'emailtemplates')->row();
if (empty($result)) {
    $CI->db->insert_batch(db_prefix().'emailtemplates', $email_template);
}
