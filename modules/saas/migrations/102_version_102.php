<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
    public function up()
    {
        if (table_exists('contacts')) {
            if (!get_instance()->db->field_exists('expiration_reminder_mail', db_prefix() . 'contacts')) {
                get_instance()->db->query('ALTER TABLE `' . db_prefix() . 'contacts` ADD `expiration_reminder_mail` timestamp NULL DEFAULT NULL');
            }
        }

        if (total_rows(db_prefix() . 'emailtemplates', ['slug' => 'inactive-tenant-reminder'])) {
            get_instance()->db->delete(db_prefix() . 'emailtemplates', ['slug' => 'inactive-tenant-reminder']);
        }

        if (table_exists('client_plan')) {
            if (!get_instance()->db->field_exists('invoices', db_prefix() . 'client_plan')) {
                get_instance()->db->query('ALTER TABLE `' . db_prefix() . 'client_plan` ADD `invoices` JSON NULL DEFAULT NULL AFTER `is_invoiced`;');

                get_instance()->db->query('ALTER TABLE `' . db_prefix() . 'client_plan` ADD `adjustmentAmount` decimal(15,2) AFTER `is_invoiced`;');

                get_instance()->db->query('UPDATE `tblclient_plan` SET `invoices` = IF(`is_invoiced` != 0, concat(\'["\',is_invoiced,\'"]\'), null)');
                get_instance()->db->query('UPDATE
                                            tblclient_plan p
                                            JOIN (select tblclient_plan.id, IF(`is_invoiced` != 0, concat(\'["\',is_invoiced,\'","\',GROUP_CONCAT(tblinvoices.id  SEPARATOR \'","\'),\'"]\'), null) as invoice_json from tblclient_plan
                                                join tblinvoices ON tblclient_plan.is_invoiced = tblinvoices.is_recurring_from
                                                GROUP by tblinvoices.is_recurring_from
                                                ) as invoices
                                        SET p.invoices = invoices.invoice_json
                                        where p.id = invoices.id');
                get_instance()->db->query('UPDATE `tblclient_plan` SET `is_invoiced` = IF(`is_invoiced` != 0, 1, 0)');
            }
        }
    }
}
