<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_account_bonus_points_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Contact name',
                'key'       => '{contact_name}',
                'available' => [
                    'loyalty',
                ],
            ],
            [
                'name'      => 'Points rewards',
                'key'       => '{points_received}',
                'available' => [
                    'loyalty',
                ],
            ],
        ];
    }

    /**
     * Merge field for audit report
     * @param  mixed $data 
     * @return array
     */
    public function format($data)
    {        
        if($data && isset($data->contact_name) && isset($data->points_received)){
            $fields['{contact_name}'] = $data->contact_name;
            $fields['{points_received}'] = $data->points_received;
            return $fields;
        }
    }
}
