<?php

$config['limitations'] = [
    'per_plan_invoices'            => ['label' => _l('invoices'), 'dbTable' => 'invoices', 'hookName' => 'before_invoice_added'],
    'per_plan_customers'           => ['label' => _l('clients'), 'dbTable' => 'clients', 'hookName' => 'before_client_added'],
    'per_plan_contracts'           => ['label' => _l('contracts'), 'dbTable' => 'contracts', 'hookName' => 'before_contract_added'],
    'per_plan_projects'            => ['label' => _l('projects'), 'dbTable' => 'projects', 'hookName' => 'before_add_project'],
    'per_plan_estimates'           => ['label' => _l('estimates'), 'dbTable' => 'estimates', 'hookName' => 'before_estimate_added'],
    'per_plan_credit_notes'        => ['label' => _l('credit_notes'), 'dbTable' => 'creditnotes', 'hookName' => 'before_create_credit_note'],
    'per_plan_payments'            => ['label' => _l('payments'), 'dbTable' => 'invoicepaymentrecords', 'hookName' => 'before_payment_recorded'],
    'per_plan_items'               => ['label' => _l('items'), 'dbTable' => 'items', 'hookName' => 'before_item_created'],
    'per_plan_proposals'           => ['label' => _l('proposals'), 'dbTable' => 'proposals', 'hookName' => 'before_create_proposal'],
    'per_plan_expenses'            => ['label' => _l('expenses'), 'dbTable' => 'expenses', 'hookName' => 'before_expense_added'],
    'per_plan_tasks'               => ['label' => _l('tasks'), 'dbTable' => 'tasks', 'hookName' => 'before_add_task'],
    'per_plan_support_tickets'     => ['label' => _l('support_tickets'), 'dbTable' => 'tickets', 'hookName' => 'before_ticket_created'],
    'per_plan_leads'               => ['label' => _l('leads'), 'dbTable' => 'leads', 'hookName' => 'before_lead_added'],
    'per_plan_staff'               => ['label' => _l('staffs'), 'dbTable' => 'staff', 'hookName' => 'before_create_staff_member'],
];
