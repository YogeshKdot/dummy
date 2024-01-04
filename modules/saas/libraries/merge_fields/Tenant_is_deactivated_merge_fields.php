<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Tenant_is_deactivated_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'NO OF DAYS LEFT',
                'key'       => '{NO_OF_DAYS_LEFT}',
                'available' => [
                    '',
                ],
                'templates' => [
                    'tenant-is-deactivated',
                ]
            ],
            [
                'name'      => 'DELETION DATE',
                'key'       => '{DELETION_DATE}',
                'available' => [
                    '',
                ],
                'templates' => [
                    'tenant-is-deactivated',
                ]
            ]
        ];
    }

    /**
     * Merge field for tenanat expiration.
     *
     * @param int    $clientid
     *
     * @return array
     */
    public function format($clientid='')
    {
        $fields = [];
        $this->ci->db->where(['userid' => $clientid]);
        $clientPlan = $this->ci->db->get(db_prefix().'client_plan')->row();

        $inactive_date = \Carbon\Carbon::createFromTimestamp($clientPlan->inactive_date);
        $deletionDate  = $inactive_date->addDays(get_option('inactive_tenants_limit'))->toDateString();
        $difference    = $inactive_date->diffInDays(\Carbon\Carbon::now());

        $fields['{NO_OF_DAYS_LEFT}'] = $difference;
        $fields['{DELETION_DATE}']   = $deletionDate;

        return $fields;
    }
}
