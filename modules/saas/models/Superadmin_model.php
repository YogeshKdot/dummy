<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Superadmin_model extends Custom_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        // serialize payment modes data
        $data['allowed_payment_modes'] = isset($data['allowed_payment_modes']) ? serialize($data['allowed_payment_modes']) : serialize([]);

        $columns = $this->db->list_fields(db_prefix().'plan_management');

        $this->load->dbforge();

        foreach ($data as $column => $colData) {
            if (!in_array($column, $columns) && false !== strpos($column, 'price_')) {
                $field = [
                    $column => [
                        'type'  => 'decimal(15,'.get_decimal_places().')',
                        'null'  => true,
                        'AFTER' => 'price',
                    ],
                ];
                $this->dbforge->add_column('plan_management', $field);
            }
        }

        return $this->insertRow(db_prefix().'plan_management', $data);
    }

    public function update($data)
    {
        $data['most_popular']  = (isset($data['most_popular'])) ? 1 : 0;
        $data['trial']         = (isset($data['trial'])) ? 1 : 0;
        // serialize payment modes data
        $data['allowed_payment_modes'] = isset($data['allowed_payment_modes']) ? serialize($data['allowed_payment_modes']) : serialize([]);

        $columns = $this->db->list_fields(db_prefix().'plan_management');

        $this->load->dbforge();

        foreach ($data as $column => $colData) {
            if (!in_array($column, $columns) && false !== strpos($column, 'price_')) {
                $field = [
                    $column => [
                        'type'  => 'decimal(15,'.get_decimal_places().')',
                        'null'  => true,
                        'AFTER' => 'price',
                    ],
                ];
                $this->dbforge->add_column('plan_management', $field);
            }
        }

        $res = $this->updateRow(db_prefix().'plan_management', $data, ['id' => $data['id']]);

        return ($res) ? $data['id'] : false;
    }

    public function edit_plan_image($data, $planId)
    {
        $res = $this->updateRow(db_prefix().'plan_management', $data, ['id' => $planId]);

        return ($res) ? $planId : false;
    }

    public function delete($planId)
    {
        return $this->deleteRow(db_prefix().'plan_management', ['id' => $planId]);
    }

    public function get_saas_plan($planId = '')
    {
        if (!empty($planId)) {
            $where = ['id' => $planId];
        }
        $result = $this->getRows(db_prefix().'plan_management', $where ?? []);
        if (class_exists('\modules\saas\core\Apiinit')) {
            \modules\saas\core\Apiinit::the_da_vinci_code(SUPERADMIN_MODULE);
        }

        return (!empty($planId)) ? $result[array_key_first($result)] : $result;
    }

    public function remove_product_image($planId)
    {
        return $this->updateRow(db_prefix().'plan_management', ['plan_image' => ''], ['id' => $planId]);
    }

    public function changeMostPopularPlan($plan_id)
    {
        if (class_exists('\modules\saas\core\Apiinit')) {
            \modules\saas\core\Apiinit::ease_of_mind(SUPERADMIN_MODULE);
        }

        return $this->db->set('most_popular', '(CASE WHEN id = '.$plan_id.' THEN 1 ELSE 0 END)', false)
            ->update(db_prefix().'plan_management');
    }

    public function validateTenantsName($where)
    {
        $count = $this->getCount(db_prefix().'client_plan', $where);

        return ($count > 0) ? false : true;
    }

    public function clear_saas_log()
    {
        return $this->db->truncate(db_prefix().'saas_activity_log');
    }

    /**
     * @param  int ID
     * @param  int Status ID
     * @param mixed $id
     * @param mixed $status
     *
     * @return bool
     *              Update tenant status Active/Inactive
     */
    public function change_tenant_status($id, $status)
    {
        $inactive_date  = strtotime('now');
        $tenant_data    = $this->db->get_where(db_prefix().'client_plan', ['userid' => $id])->row();

        $this->db->where('userid', $id);
        $this->db->update('client_plan', [
            'is_active'     => $status,
            'inactive_date' => $inactive_date,
        ]);

        if ($this->db->affected_rows() > 0) {
            if ('0' == $status) {
                $contactEmail = $this->db->get_where(db_prefix() . 'contacts', ['is_primary' => '1', 'userid' => $tenant_data->userid])->row()->email;
                send_mail_template('tenant_is_deactivated', SUPERADMIN_MODULE, $tenant_data->userid, $contactEmail);
            }

            $message = ('0' == $status) ? 'tenants_deactive' : 'tenants_active';
            $log     = _l($message, $tenant_data->id).' '._l('tenant_name', $tenant_data->tenants_name);

            saas_activity_log($log);

            return true;
        }

        return false;
    }

    public function save_legal_settings($posted_data)
    {
        $this->load->model(['payment_modes_model', 'settings_model']);

        if (
            '1' == $posted_data['settings']['gdpr_show_terms_and_conditions_in_footer'] ||
            '1' == $posted_data['settings']['gdpr_show_terms_of_use_in_footer']
        ) {
            $posted_data['settings']['enable_gdpr'] = '1';
        }

        $success = $this->settings_model->update($posted_data);

        return [
            'type'    => $success ? 'success' : '',
            'message' => $success ? _l('legal_settings_updated') : '',
        ];
    }
}
