<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Teampassword_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Contact name',
                'key'       => '{contact_name}',
                'available' => [
                    'teampassword',
                ],
            ],
            [
                'name'      => 'Type (Email, Server, Bank Account,...)',
                'key'       => '{type}',
                'available' => [
                    'teampassword',
                ],
            ],
            [
                'name'      => 'Share link',
                'key'       => '{share_link}',
                'available' => [
                    'teampassword',
                ],
            ],
            [
                'name'      => 'Object name',
                'key'       => '{obj_name}',
                'available' => [
                    'teampassword',
                ],
            ],
        ];
    }

    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($share_id)
    {
        $this->ci->load->model('team_password/team_password_model');


        $fields = [];

        $this->ci->db->where('id', $share_id);

        $share = $this->ci->db->get(db_prefix() . 'tp_share')->row();


        if (!$share) {
            return $fields;
        }

        if($share->not_in_the_system == 'off'){
            $contact = $this->ci->team_password_model->get_contact_by_email($share->client);
        }else{
            $contact = '';
        }

        if($contact != ''){
            $fields['{contact_name}']                    = $contact->firstname.' '. $contact->lastname;
        }else{
            $fields['{contact_name}']                    = $share->client;
        }

        $fields['{type}']                  =   _l($share->type) ;
        $fields['{share_link}']                  = site_url('team_password/team_password_client/view_share_client/' . $share->hash.'/'.$share->type);
        $fields['{obj_name}']                  =  $this->ci->team_password_model->get_name_obj($share->type,$share->share_id);

        return $fields;
    }
}
