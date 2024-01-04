<?php

defined('BASEPATH') or exit('No direct script access allowed');

$customRoles = [
    [
        'name'=>'accounting',
        'permissions'=>'a:41:{s:17:"bulk_pdf_exporter";a:1:{i:0;s:4:"view";}s:9:"contracts";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:12:"credit_notes";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:9:"customers";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:15:"email_templates";a:2:{i:0;s:4:"view";i:1;s:4:"edit";}s:9:"estimates";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:8:"expenses";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:8:"invoices";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:5:"items";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:14:"knowledge_base";a:3:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";}s:8:"payments";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:8:"projects";a:7:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";i:4;s:17:"create_milestones";i:5;s:15:"edit_milestones";i:6;s:17:"delete_milestones";}s:9:"proposals";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:7:"reports";a:2:{i:0;s:4:"view";i:1;s:15:"view-timesheets";}s:5:"roles";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:5:"staff";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:13:"subscriptions";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:5:"tasks";a:8:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";i:4;s:14:"edit_timesheet";i:5;s:18:"edit_own_timesheet";i:6;s:16:"delete_timesheet";i:7;s:20:"delete_own_timesheet";}s:19:"checklist_templates";a:2:{i:0;s:6:"create";i:1;s:6:"delete";}s:16:"estimate_request";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:5:"leads";a:2:{i:0;s:4:"view";i:1;s:6:"delete";}s:3:"hrm";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:14:"purchase_items";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:16:"purchase_vendors";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:21:"purchase_vendor_items";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:16:"purchase_request";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:19:"purchase_quotations";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:15:"purchase_orders";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:18:"purchase_contracts";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:17:"purchase_invoices";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:20:"purchase_debit_notes";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:16:"purchase_reports";a:1:{i:0;s:4:"view";}s:20:"accounting_dashboard";a:1:{i:0;s:4:"view";}s:22:"accounting_transaction";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:24:"accounting_journal_entry";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:19:"accounting_transfer";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:28:"accounting_chart_of_accounts";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:20:"accounting_reconcile";a:3:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";}s:17:"accounting_budget";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:17:"accounting_report";a:1:{i:0;s:4:"view";}s:18:"accounting_setting";a:2:{i:0;s:4:"view";i:1;s:4:"edit";}}'
    ],
    [
        'name'=>'commercial',
        'permissions'=> 'a:10:{s:9:"customers";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:15:"email_templates";a:2:{i:0;s:4:"view";i:1;s:4:"edit";}s:8:"expenses";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:8:"invoices";a:4:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:5:"items";a:1:{i:0;s:4:"view";}s:8:"projects";a:7:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";i:4;s:17:"create_milestones";i:5;s:15:"edit_milestones";i:6;s:17:"delete_milestones";}s:9:"proposals";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:5:"tasks";a:8:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";i:4;s:14:"edit_timesheet";i:5;s:18:"edit_own_timesheet";i:6;s:16:"delete_timesheet";i:7;s:20:"delete_own_timesheet";}s:16:"estimate_request";a:4:{i:0;s:8:"view_own";i:1;s:6:"create";i:2;s:4:"edit";i:3;s:6:"delete";}s:5:"leads";a:2:{i:0;s:4:"view";i:1;s:6:"delete";}}'
    ],
    [
        'name'=>'employee',
        'permissions'=> 'a:3:{s:9:"customers";a:1:{i:0;s:4:"view";}s:8:"projects";a:3:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";}s:5:"tasks";a:3:{i:0;s:4:"view";i:1;s:6:"create";i:2;s:4:"edit";}}'
    ]
 ];

foreach ($customRoles as $customRole) {
    $existingRole = get_instance()->db->get_where(db_prefix() . 'roles', ['name' => $customRole['name']])->result_array();
    if (empty($existingRole)) {
        get_instance()->db->insert(db_prefix() . 'roles', ['name' => $customRole['name'] , 'permissions' => $customRole['permissions']]);
    }

}

if (file_exists(APPPATH.'controllers/admin/Tasks.php')) {
    rename(APPPATH.'controllers/admin/Tasks.php', APPPATH.'controllers/admin/Tasks.php.backup');
}

if (!file_exists(APPPATH.'controllers/admin/Tasks.php')) {
    copy(module_dir_path(CUSTOM_CHANGES_MODULE, '/resources/application/controllers/admin/Tasks.php'), APPPATH.'controllers/admin/Tasks.php');
}

if (!file_exists(VIEWPATH.'admin/clients/my_import.php')) {
    copy(module_dir_path(CUSTOM_CHANGES_MODULE, '/resources/application/views/admin/clients/my_import.php'), VIEWPATH.'admin/clients/my_import.php');
}

/*End of file install.php */