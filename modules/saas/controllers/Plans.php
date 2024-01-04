<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Plans extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Superadmin_model', 'saas_model');
        $this->load->model('taxes_model');

        $this->app_modules->is_inactive('saas') ? access_denied() : '';
        !is_admin() ? access_denied() : '';
    }

    public function index()
    {
        $data['title'] = _l('saas_plans');
        $this->load->view(SUPERADMIN_MODULE.'/plans/manage', $data);
    }

    public function plan($id='')
    {
        $this->load->config('features_limitation_config');
        $data['limitations'] = config_item('limitations');
        $data['taxes']       = $this->taxes_model->get();
        $data['title']       = _l('add_new_saas_plan');

        if (!empty($id)) {
            $data['title']     = _l('edit_saas_plan');
            $data['saas_plan'] = $this->saas_model->get_saas_plan($id);
            $allowedModules = unserialize($data['saas_plan']->allowed_modules ?? '');
        }
        
        $data['allowed_modules'] = (!empty($allowedModules)) ? array_keys($allowedModules) : [];

        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', [
            'expenses_only !=' => 1,
        ]);

        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $modules = $this->app_modules->get();
        $data['modules'] = array_filter($modules, function($module) {
            $moduleNamesToBeFiltered = [ "saas", "menu_setup", "custom_changes", "theme_style", "status_manager" ];
            if (!in_array($module['system_name'], $moduleNamesToBeFiltered)) { return $module; }
        });

        $this->load->view(SUPERADMIN_MODULE . '/plans/plan', $data);
    }

    public function save()
    {
        $posted_data = $this->input->post();
        $this->load->config('features_limitation_config');
        $limitations = array_keys(config_item('limitations'));

        $posted_data['limitations'] = json_encode(array_merge(array_fill_keys($limitations, 0), array_filter($posted_data['limitations'])));

        $posted_data['taxes']  = (!empty($posted_data['taxes'])) ? serialize($posted_data['taxes']) : '';

        $posted_data['custom_recurring'] = 0;
        $posted_data['recurring_type']   = 'month';
        if ('custom' == $posted_data['recurring']) {
            $posted_data['recurring_type']   = $posted_data['repeat_type_custom'];
            $posted_data['custom_recurring'] = 1;
            $posted_data['recurring']        = $posted_data['repeat_every_custom'];
        }
        unset($posted_data['repeat_type_custom']);
        unset($posted_data['repeat_every_custom']);

        $alert_msg = '';

        $modules = [];
        if (isset($posted_data['modules'])) {
            foreach ($posted_data['modules'] as $key => $value) {
                if ($value == 'on') {
                    $modules[$key] = 1;
                }
            }
            $posted_data['allowed_modules'] = $modules;
            unset($posted_data['modules']);
        }

        $posted_data['allowed_modules']['menu_setup'] = '1';
        $posted_data['allowed_modules']['custom_changes'] = '1';
        $posted_data['allowed_modules']['theme_style'] = '1';
        $posted_data['allowed_modules']['status_manager'] = '1';
        
        $posted_data['allowed_modules'] = serialize($posted_data['allowed_modules']);

        if (!empty($posted_data['id'])) {
            $res       = $this->saas_model->update($posted_data);
            $alert_msg = $res ? _l('plan_updated_successfully') : '';
        } else {
            $insert_id = $this->saas_model->add($posted_data);
            $alert_msg = $insert_id ? _l('plan_added_successfully') : '';
        }

        $plan_id = $res ?? $insert_id;

        // Most popular plan
        isset($posted_data['most_popular']) ? $this->saas_model->changeMostPopularPlan($plan_id) : '';

        set_alert(isset($alert_msg) ? 'success' : 'danger', $alert_msg ?? _l('something_went_wrong'));
        handlePlanImageUpload($plan_id);

        redirect(admin_url(SUPERADMIN_MODULE.'/plans'), 'refresh');
    }

    public function delete($id)
    {
        if ($this->saas_model->delete($id)) {
            set_alert('danger', _l('plan_deleted_successfully'));
        }
        redirect(admin_url(SUPERADMIN_MODULE.'/plans'), 'refresh');
    }

    public function plan_management_table()
    {
        if ($this->input->is_ajax_request()) {
            return $this->app->get_table_data(module_views_path(SUPERADMIN_MODULE, 'tables/plan_management_table'));
        }
    }

    public function remove_product_image($id)
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->saas_model->remove_product_image($id);
            echo json_encode($res ? true : false);
        }
    }

    public function assign_plan_to_client_create_tenant($userid)
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $this->superadmin_lib->assignPlanToClientAndInstall($data, $userid);
            echo json_encode(['success' => true]);
        }
        echo json_encode(['success' => false]);
    }

    public function checkDbUser()
    {
        $posted_data = $this->input->post();

        $host         = trim($posted_data['mysql_host']);
        $port         = (int) trim($posted_data['mysql_port']);
        $user         = trim($posted_data['mysql_root_username']);
        $pass         = trim($posted_data['mysql_password']);
        $encrypt_pass = get_instance()->encryption->encrypt($pass);

        update_option('mysql_host', $host);
        update_option('mysql_port', $port);
        update_option('mysql_root_username', $user);
        update_option('mysql_password', $encrypt_pass);

        $test_name = 'test_'.time();

        try {
            $link = @new mysqli($host, $user, $pass, '', $port);

            if ($link->connect_errno) {
                throw new Exception($link->connect_error);
            }

            $link->query("CREATE USER $test_name@$host IDENTIFIED BY 'testuser';");
            $link->query("CREATE DATABASE $test_name;");
            $link->query("DROP USER $test_name@$host;");
            $link->query("DROP DATABASE $test_name;");

            update_option('mysql_verification_message', '');

            $result = [
                'success' => true,
                'message' => 'User has Create Database privileges',
                'color'   => 'success',
            ];
        } catch (Exception $e) {
            update_option('mysql_verification_message', $e->getMessage());

            $result = [
                'success' => false,
                'message' => $e->getMessage(),
                'color'   => 'danger',
            ];
        }

        echo json_encode($result);
    }

    public function changeSaasPlan()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        $postData = $this->input->post();
        $response = $this->superadmin_lib->updatePlan($postData['clientid'], $postData['saas_plan']);
    }
}

/* End of file Plan.php */
/* Location: saas/controllers/Plan.php */
