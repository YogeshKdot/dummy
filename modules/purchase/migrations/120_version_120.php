<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_120 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // check if version 1.1.0 is not available yet
        if(function_exists('row_purchase_tbl_options_exist')){
          if (row_purchase_tbl_options_exist('"show_item_cf_on_pdf"') == 0){
            $CI->db->query('INSERT INTO `tbloptions` (`name`, `value`, `autoload`) VALUES ("show_item_cf_on_pdf", "0", "1");
              ');
          }
        }
        if (!$CI->db->field_exists('active' ,db_prefix() . 'items')) { 
          $CI->db->query('ALTER TABLE `' . db_prefix() . "items`
            ADD COLUMN `active` INT(11) NULL DEFAULT 1
            ;");
        }

        //check if version 1.1.7 is not available yet
               if (!$CI->db->table_exists(db_prefix() . 'pur_debit_notes')) {
        $CI->db->query('CREATE TABLE `' . db_prefix() . "pur_debit_notes` (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `vendorid` INT(11) NULL,
          `deleted_vendor_name` VARCHAR(100) NULL,
          `number` INT(11) NULL,
          `prefix` varchar(50) NULL,
          `number_format` INT(11) NULL,
          `datecreated` datetime NULL,
          `date` date NULL,
          `adminnote` text NULL,
          `terms` text NULL,
          `vendornote` text NULL,
          `currency` INT(11) NULL,
          `subtotal` decimal(15,2) NULL,
          `total_tax` decimal(15,2) NULL,
          `total` decimal(15,2) NULL,
          `adjustment` decimal(15,2) NULL,
          `addedfrom` int(11) NULL,
          `status` int(11) NULL,
          `discount_percent` decimal(15,2) NULL,
          `discount_total` decimal(15,2) NULL,
          `discount_type` varchar(30) NULL,
          `billing_street` varchar(200) NULL,
          `billing_city` varchar(100) NULL,
          `billing_state` varchar(100) NULL,
          `billing_zip` varchar(100) NULL,
          `billing_country` int(11) NULL,
          `shipping_street` varchar(200) NULL,
          `shipping_city` varchar(100) NULL,
          `shipping_state` varchar(100) NULL,
          `shipping_zip` varchar(100) NULL,
          `shipping_country` int(11) NULL,
          `include_shipping` tinyint(1) NULL,
          `show_shipping_on_debit_note` tinyint(1) NULL,
          `show_quantity_as` int(11) NULL,
          `reference_no` varchar(100) NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
    }

    if (!$CI->db->table_exists(db_prefix() . 'pur_debits')) {
        $CI->db->query('CREATE TABLE `' . db_prefix() . "pur_debits` (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `invoice_id` INT(11) NULL,
          `debit_id` INT(11) NULL,
          `staff_id` INT(11) NULL,
          `date_applied` datetime NULL,
          `date` date NULL,
          `amount` decimal(15,2) NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
    }

    if (!$CI->db->table_exists(db_prefix() . 'pur_debits_refunds')) {
        $CI->db->query('CREATE TABLE `' . db_prefix() . "pur_debits_refunds` (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `debit_note_id` INT(11) NULL,
          `staff_id` INT(11) NULL,
          `refunded_on` date NULL,
          `payment_mode` varchar(40) NULL,
          `note` text NULL,
          `amount` decimal(15,2) NULL,
          `created_at` datetime NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
    }

    add_option('next_debit_note_number', 1);
    add_option('debit_note_number_format', 1);
    add_option('debit_note_prefix', 'DN-');

    create_email_template('Debit Note', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\"> We would like to share with you a link of Debit Note information with the number {dn_number} </span><br /><br /><span style=\"font-size: 12pt;\"><br />{additional_content}
      </span><br /><br />', 'purchase_order', 'Debit Note (Sent to contact)', 'debit-note-to-contact');

      //check if version 1.1.8 is not available yet
      create_email_template('Purchase Statement', '<span style=\"font-size: 12pt;\"> Dear {contact_firstname} {contact_lastname} !. </span><br /><br /><span style=\"font-size: 12pt;\">Its been a great experience working with you. </span><br /><br /><span style=\"font-size: 12pt;\"><br />Attached with this email is a list of all transactions for the period between {statement_from} to {statement_to}<br/ ><br/ >For your information your account balance due is total: {statement_balance_due}<br /><br/ > Please contact us if you need more information.<br/ > <br />{additional_content}
  </span><br /><br />', 'purchase_order', 'Purchase Statement (Sent to contact)', 'purchase-statement-to-contact');

    add_option('show_purchase_tax_column', 1);
    add_option('po_only_prefix_and_number', 0);


    //check if version 1.1.9 is not available yet
    if ($CI->db->field_exists('address' ,db_prefix() . 'pur_vendor')) { 
      $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_vendor`
          CHANGE COLUMN `address` `address` TEXT NULL DEFAULT NULL
      ;");
    }

        //Ver 1.2.0
        if ($CI->db->field_exists('unit_price' ,db_prefix() . 'pur_estimate_detail')) { 
          $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_estimate_detail`
            CHANGE COLUMN `unit_price` `unit_price` DECIMAL(15,2) NULL DEFAULT NULL
          ;");
        }
    }
}