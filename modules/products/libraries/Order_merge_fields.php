<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Order_merge_fields extends App_merge_fields
{
    public function build()
    {
        return  [
                    [
                        'name'      => 'Order Id',
                        'key'       => '{order_id}',
                        'available' => ['order'],
                    ],
                    [
                        'name'      => 'Order Date',
                        'key'       => '{order_date}',
                        'available' => ['order'],
                    ],
                    [
                        'name'      => 'Order Status',
                        'key'       => '{status}',
                        'available' => ['order'],
                    ],
                    [
                        'name'      => 'Total',
                        'key'       => '{total}',
                        'available' => ['order'],
                    ],
                    [
                        'name'      => 'Order Created Time',
                        'key'       => '{datecreated}',
                        'available' => ['order'],
                    ],
                    [
                        'name'      => 'Order Items Table with Product and quantity',
                        'key'       => '{order_items}',
                        'available' => ['order'],
                    ],
                    [
                        'name'      => 'Base Currency',
                        'key'       => '{currency}',
                        'available' => ['order'],
                    ],
                ];
    }

    public function format($id)
    {
        $this->ci->load->model('products/order_model');
        $this->ci->load->model(['currencies_model']);
        $fields                     = [];
        $data                       = $this->ci->order_model->get_by_id_order($id);
        $view_data['items']         = $this->ci->order_model->get_order_with_items($id);
        $view_data['base_currency'] = $this->ci->currencies_model->get_base_currency();
        if (empty($data)) {
            $fields['{order_id}']    = '';
            $fields['{order_date}']  = '';
            $fields['{status}']      = '';
            $fields['{total}']       = '';
            $fields['{datecreated}'] = '';
            $fields['{order_items}'] = '';

            return $fields;
        }
        $fields['{order_id}']    = $id;
        $fields['{order_date}']  = $data->order_date;
        $fields['{status}']      = $data->status;
        $fields['{total}']       = $data->total;
        $fields['{datecreated}'] = $data->datecreated;
        $fields['{currency}']    = $view_data['base_currency']->name;
        $fields['{order_items}'] = $this->ci->load->view('products/order_items_view', $view_data, true);

        return $fields;
    }
}
