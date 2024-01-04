<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_120 extends App_module_migration
{
    public function up()
    {
        add_option('enable_subscription_products_view', '1');
        add_option('enable_invoice_products_view', '0');
        add_option('enable_products_more_info_button', '0');
        add_option('show_product_quantity_field', '0');

        $CI = &get_instance();

        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "products_groups(
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
            `name` VARCHAR(100) NOT NULL,
            `order` VARCHAR(100) DEFAULT NULL,
            `color` VARCHAR(100) DEFAULT NULL,
            PRIMARY KEY (`id`)
        
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );

        $CI->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "invoice_products(
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
            `name` VARCHAR(255) NOT NULL,    
            `description` VARCHAR(255) NOT NULL,    
            `long_description` TEXT,
            `price` VARCHAR(15),
            `group` INT DEFAULT NULL,
            `itemid` INT DEFAULT NULL,
            `image` VARCHAR(255) DEFAULT NULL,
            `tax_1` INT DEFAULT NULL,   
            `tax_2` INT DEFAULT NULL,
            `is_recurring` INT DEFAULT NULL,
            `interval` INT DEFAULT NULL,
            `interval_type` varchar(15) DEFAULT NULL,
            `cycle` INT DEFAULT NULL,
            `created_from` INT DEFAULT NULL,
            `currency` INT DEFAULT NULL,
            `client_id` INT DEFAULT NULL,
            `customer_group` INT DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );

        if (!$CI->db->field_exists('group', 'subscription_products')) {
            $CI->load->dbforge();
            $fields = [
                'group' => [
                    'type'       => 'INT',
                    'constraint' => 9,
                    'null'       => true
                ]
            ];
            $CI->dbforge->add_column('subscription_products', $fields);
        }

        if (!$CI->db->field_exists('long_description', 'subscription_products')) {
            $CI->load->dbforge();
            $fields = [
                'long_description' => [
                    'type' => 'TEXT',
                    'null' => true
                ]
            ];
            $CI->dbforge->add_column('subscription_products', $fields);
        }

        if (!$CI->db->field_exists('client_id', 'invoice_products')) {
            $CI->load->dbforge();
            $fields = [
                'client_id' => [
                    'type'       => 'INT',
                    'constraint' => 9,
                    'null'       => true
                ]
            ];
            $CI->dbforge->add_column('invoice_products', $fields);
        }
    }
}
