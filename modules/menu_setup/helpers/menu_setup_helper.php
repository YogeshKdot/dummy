<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

function app_admin_sidebar_custom_options($items)
{
    return _apply_menu_items_options($items, json_decode(get_option('aside_menu_active')));
}

function app_admin_sidebar_custom_positions($items)
{
    return _apply_menu_items_position($items, json_decode(get_option('aside_menu_active')));
}

function app_admin_setup_menu_custom_options($items)
{
    return _apply_menu_items_options($items, json_decode(get_option('setup_menu_active')));
}

function app_admin_setup_menu_custom_positions($items)
{
    return _apply_menu_items_position($items, json_decode(get_option('setup_menu_active')));
}

function _apply_menu_items_options($items, $options)
{
    foreach ($items as $key => $item) {
        if (isset($options->{$item['slug']})) {
            if (isset($options->{$item['slug']}->disabled)
                && $options->{$item['slug']}->disabled === 'true') {
                // Main item is disabled
                unset($items[$key]);
            } else {
                // Main item has custom icon
                if (isset($options->{$item['slug']}->icon) && $options->{$item['slug']}->icon === false) {
                    // False is when user set the icon empty from the builder
                    $items[$key]['icon'] = '';
                } elseif (!empty($options->{$item['slug']}->icon)) {
                    $items[$key]['icon'] = $options->{$item['slug']}->icon;
                }
            }

            foreach ($item['children'] as $childKey => $child) {
                if (isset($options->{$item['slug']}->children->{$child['slug']})) {
                    if (isset($options->{$item['slug']}->children->{$child['slug']}->disabled)
                        && $options->{$item['slug']}->children->{$child['slug']}->disabled === 'true') {
                        // Is disabled
                        unset($items[$key]['children'][$childKey]);
                    } else {
                        // Has custom icon
                        if ($options->{$item['slug']}->children->{$child['slug']}->icon === false) {
                            $items[$key]['children'][$childKey]['icon'] = '';
                        } elseif (!empty($options->{$item['slug']}->children->{$child['slug']}->icon)) {
                            $items[$key]['children'][$childKey]['icon'] = $options->{$item['slug']}->children->{$child['slug']}->icon;
                        }
                    }
                }
            }
        }
    }

    return $items;
}

function _apply_menu_items_position($items, $options)
{
    if (!is_array($options)) {
        $CI = &get_instance();
        // Has applied options
        $newItems          = [];
        $newItemsAddedKeys = [];

        foreach ($options as $key => $item) {
            // Check if the item is found because can be removed
            if ($newItem = $CI->app_menu->filter_item($items, $item->id)) {
                $newItems[$key]      = $newItem;
                $newItemsAddedKeys[] = $key;

                $newItems[$key]['children'] = [];

                if (isset($item->children)) {
                    foreach ($item->children as $child) {
                        if ($newChildItem = $CI->app_menu->filter_item($items, $child->id)) {
                            $newItems[$key]['children'][] = $newChildItem;
                            $newItemsAddedKeys[]          = $newChildItem['slug'];
                        }
                    }
                }
            }
        }

        // Let's check if item is missed from $items to $newItems
        foreach ($items as $key => $item) {
            if (!in_array($key, $newItemsAddedKeys)) {
                $newItems[$key] = $item;
            }

            if (isset($item['collapse'])) {
                foreach ($item['children'] as $childKey => $child) {
                    if (!in_array($child['slug'], $newItemsAddedKeys)) {
                        $newItems[$key]['children'][] = $child;
                    }
                }

                $newItems[$key]['children'] = Arr::uniqueByKey($newItems[$key]['children'], 'slug');
            }
        }

        $items = $newItems;
    }

    // Finally apply the positions
    foreach ($items as $key => $item) {
        if (isset($options->{$item['slug']})) {
            $items[$key]['position'] = (int) $options->{$item['slug']}->position;

            foreach ($item['children'] as $childKey => $child) {
                if (isset($options->{$item['slug']}->children->{$child['slug']})) {
                    $items[$key]['children'][$childKey]['position'] = (int) $options->{$item['slug']}->children->{$child['slug']}->position;
                }
            }
        }
    }

    return $items;
}

function _menu_options_filter_child($menu_options, $slug)
{
    foreach ($menu_options as $option) {
        if (isset($option->children)) {
            foreach ($option->children as $childKey => $child) {
                if (!empty($child->id)) {
                    if ($child->id == $slug) {
                        return $child;
                    }
                }
            }
        }
    }

    return false;
}

function app_get_menu_setup_icon($menu_options, $slug, $group)
{
    $child = _menu_options_filter_child($menu_options, $slug);

    // No options applied
    if (!isset($menu_options->{$slug}) && $child === false) {
        return get_instance()->app_menu->get_initial_icon($slug, $group);
    }

    // Icon is set empty by user on parent item
    if (isset($menu_options->{$slug})
        && $menu_options->{$slug}->icon === false) {
        return '';
    }

    // Icon is set empty by user on child item
    if ($child !== false && $child->icon === false) {
        return '';
    }

    // no icon applied, get the initial icon
    if (isset($menu_options->{$slug}) && $menu_options->{$slug}->icon === '') {
        return get_instance()->app_menu->get_initial_icon($slug, $group);
    } elseif (isset($menu_options->{$slug})) {
        // Custom icon is set on parent
        return $menu_options->{$slug}->icon;
    }
    // no icon applied, get the initial icon
    if ($child && $child->icon === '') {
        return get_instance()->app_menu->get_initial_icon($slug, $group);
    } elseif ($child) {
        // Custom icon is set on child
        return $child->icon;
    }

    return '';
}

if (!function_exists('aside_menu_active_json')) {
    function aside_menu_active_json()
    {
        return '{"omni_sales":{"id":"omni_sales","icon":"","tooltip":"","disabled":"false","position":"5","children":{"omni_sales_order_list":{"disabled":"false","id":"omni_sales_order_list","icon":"","tooltip":"","position":"5"},"omni_sales_channel":{"disabled":"false","id":"omni_sales_channel","icon":"","tooltip":"","position":"10"},"trade_discount":{"disabled":"false","id":"trade_discount","icon":"","tooltip":"","position":"15"},"omni_sales_diary_sync":{"disabled":"false","id":"omni_sales_diary_sync","icon":"","tooltip":"","position":"20"},"omni_sales_report":{"disabled":"false","id":"omni_sales_report","icon":"","tooltip":"","position":"25"},"omni_sales_pos":{"disabled":"false","id":"omni_sales_pos","icon":"","tooltip":"","position":"30"},"omni_sales_portal":{"disabled":"false","id":"omni_sales_portal","icon":"","tooltip":"","position":"35"},"omni_setting":{"disabled":"false","id":"omni_setting","icon":"","tooltip":"","position":"40"}}},"dashboard":{"id":"dashboard","icon":"fa-solid fa-house","tooltip":"Visi\u00f3n general de  todos  los datos de los campos, incliudos gr\u00e1ficos, del ERP.","disabled":"false","position":"10"},"mailbox":{"id":"mailbox","icon":"","tooltip":"","disabled":"false","position":"15"},"purchase":{"id":"purchase","icon":"","tooltip":"Modulo de gesti\u00f3n de compras, incluyendo productos, proveedores, ordenes de compra, etc.","disabled":"false","position":"20","children":{"purchase-items":{"disabled":"false","id":"purchase-items","icon":"fa-solid fa-list","tooltip":"Modulo de gesti\u00f3n de productos, incluyendo servicios","position":"5"},"vendors":{"disabled":"false","id":"vendors","icon":"fa-regular fa-address-book","tooltip":"Modulo de gesti\u00f3n de los proveedores","position":"10"},"vendors-items":{"disabled":"false","id":"vendors-items","icon":"fa-solid fa-layer-group","tooltip":"Articulos del proveedor","position":"15"},"purchase-request":{"disabled":"false","id":"purchase-request","icon":"","tooltip":"M\u00f3dulo de solicitud de compra a proveedores","position":"20"},"purchase-quotation":{"disabled":"false","id":"purchase-quotation","icon":"","tooltip":"Modulo de cotizaciones registradas de los proveedores","position":"25"},"purchase-order":{"disabled":"false","id":"purchase-order","icon":"","tooltip":"Modulo de ordenes de compra a proveedores","position":"30"},"purchase-contract":{"disabled":"false","id":"purchase-contract","icon":"","tooltip":"Modulo de contractos con los proveedores","position":"35"},"purchase-debit-note":{"disabled":"false","id":"purchase-debit-note","icon":"","tooltip":"Notas de debito a proveedores","position":"40"},"purchase-invoices":{"disabled":"false","id":"purchase-invoices","icon":"","tooltip":"Entrada de facturas manuales de proveedores","position":"45"},"purchase_reports":{"disabled":"false","id":"purchase_reports","icon":"","tooltip":"Informes del modulo de compras","position":"50"},"purchase-settings":{"disabled":"false","id":"purchase-settings","icon":"","tooltip":"Configuraci\u00f3n del modulo de compras","position":"55"}}},"lead_manager":{"id":"lead_manager","icon":"","tooltip":"","disabled":"false","position":"25","children":{"lead_manager_dashboard":{"disabled":"false","id":"lead_manager_dashboard","icon":"","tooltip":"","position":"5"},"lead_manager_appointment":{"disabled":"false","id":"lead_manager_appointment","icon":"","tooltip":"","position":"10"},"lead_manager_leads":{"disabled":"false","id":"lead_manager_leads","icon":"","tooltip":"","position":"15"},"lead_manager_chats":{"disabled":"false","id":"lead_manager_chats","icon":"","tooltip":"","position":"20"},"lead_manager_mailbox":{"disabled":"false","id":"lead_manager_mailbox","icon":"","tooltip":"","position":"25"}}},"appointly":{"id":"appointly","icon":"","tooltip":"","disabled":"false","position":"30","children":{"appointly-user-dashboard":{"disabled":"false","id":"appointly-user-dashboard","icon":"","tooltip":"","position":"5"},"appointly-user-history":{"disabled":"false","id":"appointly-user-history","icon":"","tooltip":"","position":"10"},"appointly-callbacks":{"disabled":"false","id":"appointly-callbacks","icon":"","tooltip":"","position":"15"},"appointly-user-settings":{"disabled":"false","id":"appointly-user-settings","icon":"","tooltip":"","position":"20"},"appointly-link-menu-form":{"disabled":"false","id":"appointly-link-menu-form","icon":"","tooltip":"","position":"25"}}},"feedback":{"id":"feedback","icon":"","tooltip":"","disabled":"false","position":"35","children":{"send-feedback":{"disabled":"false","id":"send-feedback","icon":"","tooltip":"","position":"5"},"feedback-received":{"disabled":"false","id":"feedback-received","icon":"","tooltip":"","position":"10"}}},"HRM":{"id":"HRM","icon":"","tooltip":"Modulo de gesti\u00f3n de personal y nominas.","disabled":"false","position":"40","children":{"hrm_dashboard":{"disabled":"false","id":"hrm_dashboard","icon":"","tooltip":"Panel gen\u00e9rico de visi\u00f3n del m\u00f3dulo de gesti\u00f3n de recursos humanos.","position":"5"},"hrm_staff":{"disabled":"false","id":"hrm_staff","icon":"","tooltip":"Modulo de gesti\u00f3n de personal \/ usuarios de la plataforma de la empresa.","position":"10"},"hrm_staff_contract":{"disabled":"false","id":"hrm_staff_contract","icon":"","tooltip":"Modulo para la gesti\u00f3n de los contratos de personal de la empresa","position":"15"},"hrm_insurrance":{"disabled":"false","id":"hrm_insurrance","icon":"","tooltip":"Modulo de gesti\u00f3n de seguros contractados por la empresa.","position":"20"},"hrm_timekeeping":{"disabled":"false","id":"hrm_timekeeping","icon":"","tooltip":"M\u00f3dulo de gesti\u00f3n de los turnos del personal.","position":"25"},"hrm_payroll":{"disabled":"false","id":"hrm_payroll","icon":"","tooltip":"Modulo de gesti\u00f3n de las n\u00f3minas","position":"30"},"hrm_setting":{"disabled":"false","id":"hrm_setting","icon":"","tooltip":"Modulo para los ajustes del modulo de gestti\u00f3n de personal","position":"35"}}},"products_":{"id":"products_","icon":"","tooltip":"","disabled":"false","position":"45","children":{"services_products_invoice":{"disabled":"false","id":"services_products_invoice","icon":"","tooltip":"","position":"5"},"services_products_subscription":{"disabled":"false","id":"services_products_subscription","icon":"","tooltip":"","position":"10"},"services_products_groups":{"disabled":"false","id":"services_products_groups","icon":"","tooltip":"","position":"15"},"product_purchase_log":{"disabled":"false","id":"product_purchase_log","icon":"","tooltip":"","position":"20"}}},"accounting":{"id":"accounting","icon":"","tooltip":"Modulo para la gesti\u00f3n de la contabilidad de la empresa de manera sencilla.","disabled":"false","position":"50","children":{"accounting_dashboard":{"disabled":"false","id":"accounting_dashboard","icon":"","tooltip":"Visualiza las analiticas principales del modulo de contabilidad.","position":"5"},"accounting_banking":{"disabled":"false","id":"accounting_banking","icon":"","tooltip":"Revisa las relaciones y el estado del banco","position":"10"},"accounting_transaction":{"disabled":"false","id":"accounting_transaction","icon":"fa-solid fa-sheet-plastic","tooltip":"Revisa el estado de las ventas, compras o gastos de un solo vistazo","position":"15"},"accounting_journal_entry":{"disabled":"false","id":"accounting_journal_entry","icon":"","tooltip":"Revisa o crea asentamientos dentro de la entrada de diaro contable","position":"20"},"accounting_transfer":{"disabled":"false","id":"accounting_transfer","icon":"","tooltip":"Transfiere cantidades entre unas cuentas contables y otras.","position":"25"},"accounting_chart_of_accounts":{"disabled":"false","id":"accounting_chart_of_accounts","icon":"","tooltip":"Revisa o crea las cuentas contables que necessites.","position":"30"},"accounting_reconcile":{"disabled":"false","id":"accounting_reconcile","icon":"","tooltip":"Concilia balances en las diferentes cuentas","position":"35"},"accounting_budget":{"disabled":"false","id":"accounting_budget","icon":"","tooltip":"Crea un pressupuesto contable inicialmente para poder gestionar bien tus partidas pressupuestarias.","position":"40"},"accounting_report":{"disabled":"false","id":"accounting_report","icon":"","tooltip":"Revisa los informes contables para saber el estado en todo momento","position":"45"},"accounting_setting":{"disabled":"false","id":"accounting_setting","icon":"","tooltip":"Configura reglas bancarias, mapeo, entre otras cosas.","position":"50"}}},"mfa":{"id":"mfa","icon":"","tooltip":"","disabled":"false","position":"55","children":{"mfa-management":{"disabled":"false","id":"mfa-management","icon":"","tooltip":"","position":"5"},"mfa-report":{"disabled":"false","id":"mfa-report","icon":"","tooltip":"","position":"10"},"mfa-setting":{"disabled":"false","id":"mfa-setting","icon":"","tooltip":"","position":"15"}}},"loyalty":{"id":"loyalty","icon":"","tooltip":"","disabled":"false","position":"60","children":{"loyalty-user":{"disabled":"false","id":"loyalty-user","icon":"","tooltip":"","position":"5"},"loyalty-transation":{"disabled":"false","id":"loyalty-transation","icon":"","tooltip":"","position":"10"},"loyalty-mbs":{"disabled":"false","id":"loyalty-mbs","icon":"","tooltip":"","position":"15"},"loyalty-rule":{"disabled":"false","id":"loyalty-rule","icon":"","tooltip":"","position":"20"},"loyalty-config":{"disabled":"false","id":"loyalty-config","icon":"","tooltip":"","position":"25"}}},"reports":{"id":"reports","icon":"","tooltip":"Revisa todo tipo de informes de cada uno de los modulos.","disabled":"false","position":"65","children":{"sales-reports":{"disabled":"false","id":"sales-reports","icon":"fa-solid fa-chart-simple","tooltip":"Revisa el estado de todas las ventas de la empresa.","position":"5"},"expenses-reports":{"disabled":"false","id":"expenses-reports","icon":"fa-solid fa-chart-simple","tooltip":"Revisa el estado de todas los gastos de la empresa.","position":"10"},"expenses-vs-income-reports":{"disabled":"false","id":"expenses-vs-income-reports","icon":"fa-solid fa-chart-simple","tooltip":"Revisa el estado de todos los ingresos y gastos de la empresa.","position":"15"},"leads-reports":{"disabled":"false","id":"leads-reports","icon":"fa-solid fa-chart-simple","tooltip":"Revisa el estado de todos los clientes potenciales y su estado.","position":"20"},"timesheets-reports":{"disabled":"false","id":"timesheets-reports","icon":"fa-solid fa-chart-simple","tooltip":"Revisa el estado de como tus empleados est\u00e1n rindiendo","position":"25"},"knowledge-base-reports":{"disabled":"false","id":"knowledge-base-reports","icon":"fa-solid fa-chart-simple","tooltip":"Revisa el estado de la base de conocimiento de la empresa.","position":"30"}}},"subscriptions":{"id":"subscriptions","icon":"fa-solid fa-clipboard-check","tooltip":"Modulo de subcripciones de clientes","disabled":"true","position":"70"},"affiliate":{"id":"affiliate","icon":"","tooltip":"","disabled":"false","position":"75","children":{"affiliate-dashboard":{"disabled":"false","id":"affiliate-dashboard","icon":"","tooltip":"","position":"5"},"affiliate-members":{"disabled":"false","id":"affiliate-members","icon":"","tooltip":"","position":"10"},"affiliate-programs":{"disabled":"false","id":"affiliate-programs","icon":"","tooltip":"","position":"15"},"affiliate-orders":{"disabled":"false","id":"affiliate-orders","icon":"","tooltip":"","position":"20"},"affiliate-logs":{"disabled":"false","id":"affiliate-logs","icon":"","tooltip":"","position":"25"},"affiliate-wallet":{"disabled":"false","id":"affiliate-wallet","icon":"","tooltip":"","position":"30"},"affiliate-reports":{"disabled":"false","id":"affiliate-reports","icon":"","tooltip":"","position":"35"},"affiliate-setting":{"disabled":"false","id":"affiliate-setting","icon":"","tooltip":"","position":"40"}}},"ma":{"id":"ma","icon":"","tooltip":"","disabled":"false","position":"80","children":{"ma-dashboard":{"disabled":"false","id":"ma-dashboard","icon":"","tooltip":"","position":"5"},"ma-segments":{"disabled":"false","id":"ma-segments","icon":"","tooltip":"","position":"10"},"ma-components":{"disabled":"false","id":"ma-components","icon":"","tooltip":"","position":"15"},"ma-campaigns":{"disabled":"false","id":"ma-campaigns","icon":"","tooltip":"","position":"20"},"ma-channels":{"disabled":"false","id":"ma-channels","icon":"","tooltip":"","position":"25"},"ma-points":{"disabled":"false","id":"ma-points","icon":"","tooltip":"","position":"30"},"ma-stages":{"disabled":"false","id":"ma-stages","icon":"","tooltip":"","position":"35"},"ma-reports":{"disabled":"false","id":"ma-reports","icon":"","tooltip":"","position":"40"},"ma-settings":{"disabled":"false","id":"ma-settings","icon":"","tooltip":"","position":"45"}}},"commission":{"id":"commission","icon":"","tooltip":"","disabled":"false","position":"85","children":{"manage-commission":{"disabled":"false","id":"manage-commission","icon":"","tooltip":"","position":"5"},"commission-receipt":{"disabled":"false","id":"commission-receipt","icon":"","tooltip":"","position":"10"},"commission-applicable-staff":{"disabled":"false","id":"commission-applicable-staff","icon":"","tooltip":"","position":"15"},"commission-applicable-client":{"disabled":"false","id":"commission-applicable-client","icon":"","tooltip":"","position":"20"},"commission-policy":{"disabled":"false","id":"commission-policy","icon":"","tooltip":"","position":"25"},"commission-setting":{"disabled":"false","id":"commission-setting","icon":"","tooltip":"","position":"30"}}},"tasks":{"id":"tasks","icon":"","tooltip":"","disabled":"false","position":"90"},"leads":{"id":"leads","icon":"fa-solid fa-users-line","tooltip":"Modulos de clientes potenciales, te permite, ver, editar y gestionar los clientes potenciales.","disabled":"true","position":"95"},"landingpages-menu":{"id":"landingpages-menu","icon":"","tooltip":"","disabled":"false","position":"100","children":{"landingpages":{"disabled":"false","id":"landingpages","icon":"","tooltip":"","position":"5"},"landingpages-templates":{"disabled":"false","id":"landingpages-templates","icon":"","tooltip":"","position":"25"},"landingpages-leads":{"disabled":"false","id":"landingpages-leads","icon":"","tooltip":"","position":"15"},"landingpages-blocks":{"disabled":"false","id":"landingpages-blocks","icon":"","tooltip":"","position":"20"},"landingpages-setting":{"disabled":"false","id":"landingpages-setting","icon":"","tooltip":"","position":"30"}}},"estimate_request":{"id":"estimate_request","icon":"fa-solid fa-file-prescription","tooltip":"Modulo de estimaciones de pressupuestos","disabled":"true","position":"105"},"sales":{"id":"sales","icon":"","tooltip":"Modulo para realizar todas las operativas que esten relacionadas con las ventas de la empresa de manera eficiente.","disabled":"false","position":"110","children":{"proposals":{"disabled":"true","id":"proposals","icon":"fa-solid fa-file-lines","tooltip":"Realiza una propuesta inicial a un cliente potencial","position":"5"},"estimates":{"disabled":"false","id":"estimates","icon":"fa-solid fa-file-invoice","tooltip":"Realiza un pressupuesto a un cliente habitual.","position":"10"},"invoices":{"disabled":"false","id":"invoices","icon":"fa-solid fa-file-invoice-dollar","tooltip":"Realiza una factura a tus clientes","position":"15"},"payments":{"disabled":"true","id":"payments","icon":"fa-solid fa-circle-dollar-to-slot","tooltip":"Registra los pagos de tus clientes","position":"20"},"credit_notes":{"disabled":"false","id":"credit_notes","icon":"fa-solid fa-file-invoice-dollar","tooltip":"Crea una nota de cr\u00e9dito para tus clientes","position":"25"},"calendar":{"disabled":"false","id":"calendar","icon":"fa-solid fa-calendar-days","tooltip":"","position":"30"},"items":{"disabled":"true","id":"items","icon":"","tooltip":"","position":"35"}}},"customers":{"id":"customers","icon":"","tooltip":"Ve, edita y gestiona todos tus clientes","disabled":"false","position":"115"},"contracts":{"id":"contracts","icon":"","tooltip":"Crea contratos para tus clientes","disabled":"true","position":"120"},"projects":{"id":"projects","icon":"","tooltip":"Gestiona todos los proyectos que tengas en la empresa de manera efectiva mediante listado, gantchart i deja que tus clientes lo vean.","disabled":"false","position":"125"},"expenses":{"id":"expenses","icon":"fa-solid fa-file-invoice-dollar","tooltip":"Registra todos los gastos que tienes dentro de la empresa","disabled":"true","position":"130"},"support":{"id":"support","icon":false,"tooltip":"Modulo de soporte para que tus clientes abran tiquets","disabled":"true","position":"135"},"knowledge-base":{"id":"knowledge-base","icon":false,"tooltip":"Base de conocimiento de la empresa, acumula todo el conocimiento en un solo sitio.","disabled":"true","position":"140"},"utilities":{"id":"utilities","icon":"","tooltip":"Modulo que te permitir\u00e1 hacer varias gestiones adicionales de pueden ser de mucha utilidad como encuestas, copias de seguridad, tickets, exportar datos, entre otros.","disabled":"false","position":"145","children":{"media":{"disabled":"true","id":"media","icon":"","tooltip":"","position":"5"},"bulk-pdf-exporter":{"disabled":"true","id":"bulk-pdf-exporter","icon":"fa-solid fa-file-export","tooltip":"","position":"10"},"announcements":{"disabled":"true","id":"announcements","icon":"","tooltip":"","position":"15"},"goals-tracking":{"disabled":"true","id":"goals-tracking","icon":"fa-solid fa-bullseye","tooltip":"Registra las metas de tu equipo","position":"20"},"activity-log":{"disabled":"true","id":"activity-log","icon":"fa-solid fa-folder-tree","tooltip":"Mira las actividades que han echo los usuarios de la plataforma","position":"25"},"surveys":{"disabled":"true","id":"surveys","icon":"fa-solid fa-square-poll-horizontal","tooltip":"Realiza encuestas entre tus clientes","position":"30"},"utility_backup":{"disabled":"false","id":"utility_backup","icon":"","tooltip":"","position":"35"},"ticket-pipe-log":{"disabled":"true","id":"ticket-pipe-log","icon":"fa-solid fa-ticket-simple","tooltip":"","position":"40"},"csv-export":{"disabled":"false","id":"csv-export","icon":"fa-solid fa-file-export","tooltip":"Exporta los principales documentos a formato CSV","position":"45"}}},"saas":{"id":"saas","icon":"","tooltip":"","disabled":"false","position":"150","children":{"plans":{"disabled":"false","id":"plans","icon":"","tooltip":"","position":"5"},"saas_setting":{"disabled":"false","id":"saas_setting","icon":"","tooltip":"","position":"10"},"saas_activity_log":{"disabled":"false","id":"saas_activity_log","icon":"","tooltip":"","position":"15"},"saas_landing_page_editor":{"disabled":"false","id":"saas_landing_page_editor","icon":"","tooltip":"","position":"20"},"saas_landing_page_builder":{"disabled":"false","id":"saas_landing_page_builder","icon":"","tooltip":"","position":"25"}}},"AIWRITER":{"id":"AIWRITER","icon":"","tooltip":"","disabled":"false","position":"155","children":{"writer":{"disabled":"false","id":"writer","icon":"","tooltip":"","position":"5"},"aiwriter-usage-case":{"disabled":"false","id":"aiwriter-usage-case","icon":"","tooltip":"","position":"10"},"aiwriter-setting":{"disabled":"false","id":"aiwriter-setting","icon":"","tooltip":"","position":"15"}}}}';
    }
}

if (!function_exists('setup_menu_active_json')) {
    function setup_menu_active_json()
    {
        return '{"customers":{"id":"customers","icon":"fa-solid fa-person-military-pointing","tooltip":"Configura los tipos de clientes que tiene la empresa.","disabled":"false","position":"5","children":{"customer-groups":{"disabled":"true","id":"customer-groups","icon":"fa-solid fa-users-gear","tooltip":"Configura los tipos de clientes que tiene la empresa.","deactivated":"false","position":"5"}}},"staff":{"id":"staff","icon":"fa-solid fa-user-plus","tooltip":"A\u00f1ade las personas que necessites al equipo","disabled":"false","position":"10"},"roles":{"id":"roles","icon":"fa-solid fa-person-circle-question","tooltip":"A\u00f1ade o quita los roles que tienen tus empleados para poner o quitar lo que pueden ver, editar o eliminar.","disabled":"false","position":"15"},"finance":{"id":"finance","icon":"fa-solid fa-file-pdf","tooltip":"Configura todo lo relacionado con los presspuestos, impuestos,tipos de monedas, formas de pago o categorias de gastos.","disabled":"false","position":"20","children":{"taxes":{"disabled":"false","id":"taxes","icon":"fa-solid fa-file-invoice-dollar","tooltip":"Configura los impuestos que tiene tu empresa al momento de emitir cualquier factura.","deactivated":"false","position":"5"},"currencies":{"disabled":"false","id":"currencies","icon":"fa-solid fa-coins","tooltip":"Indica los tipos de moneas que va a utilizarse en tu empresa.","deactivated":"false","position":"10"},"payment-modes":{"disabled":"false","id":"payment-modes","icon":"fa-solid fa-money-check","tooltip":"Indica los modos de pago que vas a utilizar, trasnferencias, tarjetas, etc.","deactivated":"false","position":"15"},"expenses-categories":{"disabled":"false","id":"expenses-categories","icon":"fa-solid fa-coins","tooltip":"A\u00f1ade tantas categorias de gastos como necesites para tenerlo todo bien organizado.","deactivated":"false","position":"20"}}},"status_manager":{"id":"status_manager","icon":"fa-solid fa-list-check","tooltip":"Configura los status de tareas o proyectos que mas te convengan.","disabled":"false","position":"25","children":{"task_status_manager":{"disabled":"false","id":"task_status_manager","icon":"fa-solid fa-list-check","tooltip":"Configura los status de las tareas","deactivated":"false","position":"5"},"project_status_manager":{"disabled":"false","id":"project_status_manager","icon":"fa-solid fa-list-check","tooltip":"Configura los status de los proyectos","deactivated":"false","position":"10"}}},"support":{"id":"support","icon":"","tooltip":"Gestiona y configura el soporte de la empresa.","disabled":"false","position":"30","children":{"departments":{"disabled":"true","id":"departments","icon":"","tooltip":"A\u00f1ade o quita los departamentos que tienes dentro del modulo de soporte.","deactivated":"false","position":"5"},"tickets-predefined-replies":{"disabled":"true","id":"tickets-predefined-replies","icon":"","tooltip":"A\u00f1ade o quita respuestas predefinidas de los tiquets.","deactivated":"false","position":"10"},"tickets-priorities":{"disabled":"true","id":"tickets-priorities","icon":"","tooltip":"Indica las categorias de prioridad de los tiquets.","deactivated":"false","position":"15"},"tickets-statuses":{"disabled":"true","id":"tickets-statuses","icon":"","tooltip":"Indica que diferentes estados tienen los tiquets.","deactivated":"false","position":"20"},"tickets-services":{"disabled":"true","id":"tickets-services","icon":"","tooltip":"Iindica que servicios hay dentro del soporte de la empresa","deactivated":"false","position":"25"},"tickets-spam-filters":{"disabled":"true","id":"tickets-spam-filters","icon":"","tooltip":"Indica los filtros que consideras tiquets de spam que no sean reales para ti.","deactivated":"false","position":"30"}}},"leads":{"id":"leads","icon":"fa-solid fa-users","tooltip":"Gestiona la configuraci\u00f3n de los principales clientes potenciales","disabled":"false","position":"35","children":{"leads-sources":{"disabled":"true","id":"leads-sources","icon":"","tooltip":"Indica las principales fuentes de procedencia de tus clientes potenciales.","deactivated":"false","position":"5"},"leads-statuses":{"disabled":"true","id":"leads-statuses","icon":"","tooltip":"Indica que estados principales vas a tener para tus clientes potenciales.","deactivated":"false","position":"10"},"leads-email-integration":{"disabled":"true","id":"leads-email-integration","icon":"","tooltip":"Integra el correo electr\u00f3nica para gestionar correctamente tus clientes potenciales y las comunicaciones.","deactivated":"false","position":"15"},"web-to-lead":{"disabled":"true","id":"web-to-lead","icon":"","tooltip":"Gestiona todos los contactos que tengas des de la pagina web directamente en el ERP","deactivated":"false","position":"20"}}},"menu-options":{"id":"menu-options","icon":"fa-regular fa-pen-to-square","tooltip":"A\u00f1ade o quita los men\u00fas, tanto del men\u00fa principal como de configuraci\u00f3n, que no quieras utilizar o ver.","disabled":"false","position":"40","children":{"main-menu-options":{"disabled":"false","id":"main-menu-options","icon":"fa-solid fa-bars-staggered","tooltip":"A\u00f1ade o quita los men\u00fas del men\u00fa principal que no quieras utilizar o ver.","deactivated":"false","position":"5"},"setup-menu-options":{"disabled":"false","id":"setup-menu-options","icon":"fa-solid fa-bars-staggered","tooltip":"A\u00f1ade o quita los men\u00fas del men\u00fa de configuraci\u00f3n que no quieras utilizar o ver.","deactivated":"false","position":"10"}}},"custom-fields":{"id":"custom-fields","icon":"fa-solid fa-bars-staggered","tooltip":"A\u00f1ade o quita todos los campos que quieras en cualquiera de las secciones existentes del software de gesti\u00f3n","disabled":"false","position":"45"},"email-templates":{"id":"email-templates","icon":"fa-solid fa-envelope-open-text","tooltip":"Crea y edita las plantillas de correo electr\u00f3nico predefinidas.","disabled":"false","position":"50"},"contracts":{"id":"contracts","icon":"","tooltip":"Configura los tipos de contratos de la empresa","disabled":"false","position":"55","children":{"contracts-types":{"disabled":"true","id":"contracts-types","icon":"","tooltip":"Configura los tipos de contratos de la empresa","deactivated":"false","position":"5"}}},"settings":{"id":"settings","icon":"fa-solid fa-sliders","tooltip":"Men\u00fa de configuraci\u00f3n general y personalizaci\u00f3n de diferentes aspectos entre ellos la informaci\u00f3n de la empresa, logotipo, idioma de los perfiles, pasarelas de pago, formato de pdf, entre otros aspectos.","disabled":"false","position":"60","children":{"general":{"disabled":"false","id":"general","icon":"fa-solid fa-house-user","tooltip":"Indica la informaci\u00f3n principal de la empresa como el nombre y el logotipo que saldr\u00e1 en los principales documentos.","deactivated":"false","position":"5"},"company":{"disabled":"false","id":"company","icon":"fa-solid fa-building","tooltip":"Indica la informaci\u00f3n principal de la empresa como la direcci\u00f3n y que informaci\u00f3n quieres que se vea en los documentos.","deactivated":"false","position":"10"},"localization":{"disabled":"false","id":"localization","icon":"fa-solid fa-language","tooltip":"Escoge el idioma por defecto de tu perfil","deactivated":"false","position":"15"},"email":{"disabled":"false","id":"email","icon":"fa-solid fa-envelope-circle-check","tooltip":"Configura tu correo electr\u00f3nico de empresa para que puedas enviar correos electr\u00f3nicos autom\u00e1ticamente.","deactivated":"false","position":"20"},"sales":{"disabled":"false","id":"sales","icon":"fa-solid fa-sack-dollar","tooltip":"Indica las principales configuraci\u00f3nes financieras dentro de los documentos de propuestas, facturas,pressupuestos...","deactivated":"false","position":"25"},"subscriptions":{"disabled":"false","id":"subscriptions","icon":"","tooltip":"Gestiona y configura las subcripciones.","deactivated":"true","position":"30"},"payment_gateways":{"disabled":"false","id":"payment_gateways","icon":"fa-solid fa-credit-card","tooltip":"Configura las principales pasarelas de pago .","deactivated":"false","position":"35"},"clients":{"disabled":"false","id":"clients","icon":"fa-solid fa-user-tie","tooltip":"Configura las principales funciones del modulo de clientes.","deactivated":"false","position":"40"},"tasks":{"disabled":"false","id":"tasks","icon":"fa-solid fa-list-check","tooltip":"Gestiona como quieres ver o gestionar las tareas.","deactivated":"false","position":"45"},"tickets":{"disabled":"false","id":"tickets","icon":"","tooltip":"Configura el modulo de soporte.","deactivated":"true","position":"50"},"leads":{"id":"leads","disabled":"false","icon":"fa-solid fa-users","tooltip":"Gestiona el modulo de clientes potenciales.","deactivated":"false","position":"55"},"calendar":{"disabled":"false","id":"calendar","icon":"fa-solid fa-calendar-days","tooltip":"Gestiona la configuraci\u00f3n principal del modulo de calendario.","deactivated":"true","position":"60"},"sms":{"disabled":"false","id":"sms","icon":"","tooltip":"Configura para que el sistema pueda enviar SMS.","deactivated":"true","position":"65"},"pdf":{"disabled":"false","id":"pdf","icon":"fa-solid fa-file-pdf","tooltip":"Indica como quieres y qu\u00e9 quieres ver en los principales documentos que se crean.","deactivated":"false","position":"70"},"e_sign":{"disabled":"false","id":"e_sign","icon":"fa-solid fa-signature","tooltip":"Configura la firma electr\u00f3nica.","deactivated":"false","position":"75"},"cronjob":{"disabled":"false","id":"cronjob","icon":"","tooltip":"Configura que las tareas repetitivas se realizen autom\u00e1ticamente.","deactivated":"true","position":"80"},"tags":{"disabled":"false","id":"tags","icon":"","tooltip":"Configura las categorias de etiquetas principales.","deactivated":"true","position":"85"},"pusher":{"disabled":"false","id":"pusher","icon":"","tooltip":"","deactivated":"true","position":"90"},"google":{"disabled":"false","id":"google","icon":"","tooltip":"Configura la API de google","deactivated":"true","position":"95"},"misc":{"disabled":"false","id":"misc","icon":"","tooltip":"Configura como ver las tablas del sistema o los permisos de los usuarios que no son administradores.","deactivated":"true","position":"100"}}},"estimate_request":{"id":"estimate_request","icon":"fa-solid fa-file-invoice-dollar","tooltip":"Configura el apartado de solicitud de pressupuestos.","disabled":"false","position":"65","children":{"estimate-request-forms":{"disabled":"true","id":"estimate-request-forms","icon":"","tooltip":"Crea y configura un formulario para la solicitud de pressupuestos.","deactivated":"false","position":"5"},"estimate-request-statuses":{"disabled":"true","id":"estimate-request-statuses","icon":"","tooltip":"Configura el estado de solicitud de un pressupuesto.","deactivated":"false","position":"10"}}},"theme-style":{"id":"theme-style","icon":"fa-solid fa-border-top-left","tooltip":"Canvia el estilo de tu perfil.","disabled":"false","position":"70"},"modules":{"id":"modules","icon":"fa-solid fa-chart-gantt","tooltip":"Gestiona el numero de modulos del sistema.","disabled":"false","position":"75"},"gdpr":{"id":"gdpr","icon":"fa-solid fa-building-shield","tooltip":"Configura el apartado de protecci\u00f3n de datos.","disabled":"false","position":"80"}}';
    }
}

if (!function_exists('moveSettingsTabToSidebarMenu')) {
    function moveSettingsTabToSidebarMenu($fullMenu=false)
    {
        $CI = &get_instance();
        $settingsTabs = $CI->app_tabs->get_settings_tabs();

        $settingsTabsValues = array_values($settingsTabs);

        $newArray = json_decode(get_option('setup_menu_active'), true);

        foreach ($settingsTabsValues as $index => &$subArray) {
            $subArray['icon'] = $subArray['icon'];
            $subArray['parent_slug'] = 'settings';
            $subArray['href'] = admin_url('settings?group=' . $subArray['slug']);
            $subArray['tooltip'] = $newArray['settings']['children'][$subArray['slug']]['tooltip'] ?? "";
            if (!$fullMenu) {
                if (isset($newArray['settings'])) {
                    if ($newArray['settings']['children'][$subArray['slug']]['deactivated'] == 'true') {
                        unset($settingsTabsValues[$index]);
                    }
                }
            }
        }

        return $settingsTabsValues;
    }
}
