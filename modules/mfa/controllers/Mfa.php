<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * MFA Controller
 */
class mfa extends AdminController {

	/**
	 * Constructs a new instance.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('mfa_model');
	}

	/**
	 * { settings }
	 * 
	 * @return view
	 */
	public function settings(){
		if (!is_admin()) {
			access_denied('MFA');
		}

        $this->load->model('roles_model');
		$data['title']                 = _l('mfa_setting');

        $data['tab'] = $this->input->get('group');
        if($data['tab'] == ''){
            $data['tab'] = 'mfa_general';
        }

        $data['tabs'][] = ['name' => 'mfa_general', 'icon' => '<i class="fa fa-cog menu-icon"></i>'];
        $data['tabs'][] = ['name' => 'mfa_google_authenticator', 'icon' => '<i class="fa fa-google menu-icon"></i>'];
        $data['tabs'][] = ['name' => 'mfa_whatsapp', 'icon' => '<i class="fa fa-whatsapp menu-icon"></i>'];
        $data['tabs'][] = ['name' => 'mfa_sms', 'icon' => '<i class="fa fa-commenting menu-icon"></i>'];

        $data['roles'] = $this->roles_model->get();

		$this->load->view('settings/manage', $data);
	}

	/**
	 * { mfa setting by admin }
	 * 
	 * @return redirect
	 */
	public function mfa_setting($group){
		
			$data = $this->input->post();
			$success = $this->mfa_model->mfa_setting($data, $group);
			if($success){
				set_alert('success', _l('mfa_updated_successfully'));
			}else{
				set_alert('warning', $mess = _l('no_data_has_been_updated'));
			}
			redirect(admin_url('mfa/settings?group='.$group));
		
	}

	/**
	 * { MFA management for staff }
	 * 
	 * @return view
	 */
	public function mfa_management(){
		if(!is_staff_logged_in()){
			access_denied('MFA');
		}
		$this->load->model('staff_model');
		$data['staff'] = $this->staff_model->get(get_staff_user_id());
		$data['title'] = _l('mfa_management');
		$this->load->view('mfa_management/manage', $data);
	}

	/**
	 * { MFA manage for staff }
	 * 
	 * @return redirect
	 */
	public function mfa_manage(){
		if($this->input->post()){
			$data = $this->input->post();
			$staff = get_staff_user_id();
			$success = $this->mfa_model->mfa_staff_info($staff, $data);
			if($success){
				set_alert('success', _l('mfa_updated_successfully'));
			}else{
				set_alert('warning', $mess = _l('no_data_has_been_updated'));
			}
			redirect(admin_url('mfa/mfa_management'));
		}
	}

	/**
	 * Creates a secret key.
	 * 
	 * @return json
	 */
	public function create_secret_key(){
		if(!class_exists('PHPGangsta_GoogleAuthenticator')){
			require_once MFA_PATH.'assets/plugins/PHPGangsta/GoogleAuthenticator.php';
		}

		$auth = new PHPGangsta_GoogleAuthenticator();
		$secret_key = $auth->createSecret();

		echo json_encode([
			'secret_key' => $secret_key
		]);

	}

	/**
	 * { delete history }
	 * @return json
	 */
	public function delete_history(){
		$success = '';
		if(!is_admin()){
			$success = false;
		}

		$success = $this->mfa_model->delete_history();
		$message = '';
		if($success){
			$message = _l('clear_logs_successfully');
		}else{
			$message = _l('clear_fail_no_logs_are_deleted');
		}

		echo json_encode([
			'success' => $success,
			'message' => $message
		]);
	}

	/**
	 * { function_description }
	 */
	public function mfa_reports(){
		$this->load->model('staff_model');

		$data['title'] = _l('mfa_report');
		$data['staff'] = $this->staff_model->get();

		$this->load->view('reports/manage',$data);
	}

	/**
	 * { history login table rp }
	 */
	public function history_login_table_rp(){
		if ($this->input->is_ajax_request()) { 
			 $select = [
                'id', 
                'staff',
                'type', 
                'status',
                'time'
            ];

            $where =[];
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'mfa_history_login.time');

            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('staff')) {
                $staffs  = $this->input->post('staff');
                $_staffs = [];
                if (is_array($staffs)) {
                    foreach ($staffs as $staff) {
                        if ($staff != '') {
                            array_push($_staffs, $staff);
                        }
                    }
                }
                if (count($_staffs) > 0) {
                    array_push($where, 'AND '.db_prefix().'mfa_history_login.staff IN (' . implode(', ', $_staffs) . ')');
                }
            }

            if(!is_admin()){
            	array_push($where, 'AND '.db_prefix().'mfa_history_login.staff = '. get_staff_user_id());
            }

            
            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'mfa_history_login';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'mfa_history_login.staff',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                db_prefix() . 'staff.firstname',
                db_prefix() . 'staff.lastname'
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];


            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['id'];

                $row[] = '<a href="' . admin_url('staff/profile/' . $aRow['staff']) . '" target="_blank">' . $aRow['firstname'].' '. $aRow['lastname'] . '</a>';

                $row[] = _l('mfa_'.$aRow['type']);

                $class = '';
                if($aRow['status'] == 'success'){
                	$class = 'text-success';
                }

                $row[] = '<span class="'.$class.'">'._l('mfa_'.$aRow['status']).'</span>';

                $row[] = _dt($aRow['time']);

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
		}
	}

	/**
	 * { history login table rp }
	 */
	public function history_send_code_table_rp(){
		if ($this->input->is_ajax_request()) { 
			 $select = [
                'id', 
                'staff',
                'type', 
                'status',
                'mess',
                'time'
            ];

            $where =[];
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'mfa_send_code_logs.time');

            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('staff_sc')) {
                $staffs  = $this->input->post('staff_sc');
                $_staffs = [];
                if (is_array($staffs)) {
                    foreach ($staffs as $staff) {
                        if ($staff != '') {
                            array_push($_staffs, $staff);
                        }
                    }
                }
                if (count($_staffs) > 0) {
                    array_push($where, 'AND '.db_prefix().'mfa_send_code_logs.staff IN (' . implode(', ', $_staffs) . ')');
                }
            }

            if(!is_admin()){
            	array_push($where, 'AND '.db_prefix().'mfa_send_code_logs.staff = '. get_staff_user_id());
            }

            
            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'mfa_send_code_logs';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'mfa_send_code_logs.staff',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                db_prefix() . 'staff.firstname',
                db_prefix() . 'staff.lastname'
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];


            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['id'];

                $row[] = '<a href="' . admin_url('staff/profile/' . $aRow['staff']) . '" target="_blank">' . $aRow['firstname'].' '. $aRow['lastname'] . '</a>';

                $row[] = _l('mfa_'.$aRow['type']);

                $class = '';
                if($aRow['status'] == 'success'){
                	$class = 'text-success';
                }

                $row[] = '<span class="'.$class.'">'._l('mfa_'.$aRow['status']).'</span>';

                $row[] = $aRow['mess'];

                $row[] = _dt($aRow['time']);

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
		}
	}

	/**
     * Gets the where report period.
     *
     * @param      string  $field  The field
     *
     * @return     string  The where report period.
     */
    private function get_where_report_period($field = 'date')
    {
        $months_report      = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $from_date . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            }
        }

        return $custom_date_select;
    }

    /**
	 * { get data permit }
	 * 
	 * @return json
	 */
	public function login_per_month_rp(){
		$year_report      = $this->input->post('year');
		$login_status      = $this->input->post('login_status');
		$staff_login      = $this->input->post('staff_login');
		echo json_encode($this->mfa_model->login_per_month_rp($year_report, $login_status, $staff_login));
		die();
	}

    /**
     * Sends a test message.
     */
    public function send_test_message(){
        if($this->input->post()){
            $data = $this->input->post();
            $success = $this->mfa_model->send_test_message($data);

            $mess = '';
            if($success == true){
                $mess = _l('send_message_successfully');
            }else{
                $mess = _l('send_message_fail');
            }

            echo json_encode([
                'success' => $success,
                'message' => $mess
            ]);
        }
    }

    /**
     * Creates a qr code.
     */
    public function create_qr_code($secret_key){
        if(!class_exists('PHPGangsta_GoogleAuthenticator')){
            require_once MFA_PATH.'assets/plugins/PHPGangsta/GoogleAuthenticator.php';
        }

        $auth = new PHPGangsta_GoogleAuthenticator();
        $site = site_url();
        $title = get_staff_full_name(get_staff_user_id());
        $qr_code_url = $auth->getQRCodeGoogleUrl($title, $secret_key, $site);
        
        $html = '<img src="'.$qr_code_url.'" >';

        echo json_encode([
            'html' => $html,
        ]);
    }

    /**
     * { list users of role }
     *
     * @param        $role   The role
     * @return json
     */
    public function list_users_of_role($role){
        $role_name = '';
        $html = '';

        $this->db->where('role', $role);
        $users = $this->db->get(db_prefix().'staff')->result_array();

        if($role == 0){
            $role_name = _l('users_have_not_role_on_crm');

            $this->db->where('role = 0 or role IS NULL');
            $users = $this->db->get(db_prefix().'staff')->result_array();

        }else{
            $this->db->where('roleid', $role);
            $r = $this->db->get(db_prefix().'roles')->row();
        }

        if(isset($r)){
            $role_name = $r->name;
        }

        if(count($users) > 0){
            $html .= '<table class="table table-bordered table-striped">
                        <thead><tr><th>'._l('mfa_full_name').'</th><th>'._l('mfa_phonenumber').'</th></tr></thead><tbody>';
            foreach($users as $user){
                $html .= '<tr>
                        <td>
                            <a href="'.admin_url('staff/profile/'.$user['staffid']).'">'.get_staff_full_name($user['staffid']).'</a>
                        </td>
                        <td>
                            '.$user['phonenumber'].'
                        </td>
                    </tr>';
            }

            $html .= '</tbody></table>';
        }

        echo json_encode([
            'html' => $html,
            'role_name' => $role_name
        ]);
    }
}