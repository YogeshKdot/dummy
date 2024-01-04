<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Email_verification_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'TENANT EMAIL',
                'key'       => '{TENANT_EMAIL}',
                'available' => [
                    '',
                ],
                'templates' => [
                    'we-found-your-tenant-url',
                ],
            ],
            [
                'name'      => 'TENANTS LOGIN URL',
                'key'       => '{TENANTS_LOGIN_URL}',
                'available' => [
                    '',
                ],
                'templates' => [
                    'we-found-your-tenant-url',
                ],
            ],
        ];
    }

    /**
     * Merge field for email verification.
     *
     * @param int    $clientid
     * @param string $client_email
     *
     * @return array
     */
    public function format($clientid='', $client_email = '')
    {
        $fields = [];
        $this->ci->db->where(['userid' => $clientid]);
        $tenants = $this->ci->db->get(db_prefix().'client_plan')->row();

        $fields['{TENANT_EMAIL}']   = $client_email;

        $fields['{TENANTS_LOGIN_URL}'] = parse_url(base_url())['scheme'].'://'.$tenants->tenants_name.'.'.getDomain().'/admin';

        return $fields;
    }
}
