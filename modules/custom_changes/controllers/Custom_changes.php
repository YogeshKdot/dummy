<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * custom changes controler
 */
class Custom_changes extends AdminController {

	public function __construct() 
	{
		parent::__construct();
		($this->app_modules->is_inactive('custom_changes')) ? access_denied() : '' ;
		$this->load->model('clients_model');
	}

	public function import_customer()
	{
		if (!has_permission('customers', '', 'create')) {
            access_denied('customers');
        }

        if(!class_exists('XLSXReader_fin')){
            require_once(module_dir_path(CUSTOM_CHANGES_MODULE).'/assets/plugins/XLSXReader/XLSXReader.php');
        }
        require_once(module_dir_path(CUSTOM_CHANGES_MODULE).'/assets/plugins/XLSXWriter/xlsxwriter.class.php');

        $dbFields = $this->db->list_fields(db_prefix() . 'contacts');
        foreach ($dbFields as $key => $contactField) {
            if ($contactField == 'phonenumber') {
                $dbFields[$key] = 'contact_phonenumber';
            }
        }

        $dbFields = array_merge($dbFields, $this->db->list_fields(db_prefix() . 'clients'));

        $this->load->library('import/import_customer', [], 'import');

        $this->import->setDatabaseFields($dbFields)
                     ->setCustomFields(get_custom_fields('customers'));


        if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
        	$this->import->setSimulation($this->input->post('simulate'))
		                          ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
		                          ->setFilename($_FILES['file_csv']['name'])
		                          ->perform();

            $data['total_rows_post'] = $this->import->totalRows();

            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
            }
        }

        $data['groups']    = $this->clients_model->get_groups();
        $data['title']     = _l('import');
        $data['bodyclass'] = 'dynamic-create-groups';
        $this->load->view('admin/clients/import', $data);

	}
}

