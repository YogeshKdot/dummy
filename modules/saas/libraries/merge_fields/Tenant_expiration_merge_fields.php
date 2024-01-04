<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Tenant_expiration_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'TENANT EXPIRATION DATE',
                'key'       => '{TENANT_EXPIRATION_DATE}',
                'available' => [
                    '',
                ],
                'templates' => [
                    'tenant-expiration-reminder',
                ],
            ],
        ];
    }

    /**
     * Merge field for tenanat expiration.
     *
     * @param int    $clientid
     * @param string $client_email
     *
     * @return array
     */
    public function format($clientid='')
    {
        $fields = [];
        $this->ci->db->where(['userid' => $clientid]);
        $tenants = $this->ci->db->get(db_prefix().'client_plan')->row();

        $date = \Carbon\Carbon::create($tenants->trial_start_time);
        $date->addDays($tenants->trial_days);

        $fields['{TENANT_EXPIRATION_DATE}']   = date('Y-m-d', strtotime($date));

        return $fields;
    }
}
