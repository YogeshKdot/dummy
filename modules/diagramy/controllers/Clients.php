<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use app\services\ValidatesContact;
class Clients extends ClientsController 
{
	
	/**
     * @since  2.3.3
     */
    use ValidatesContact;
	public function __construct()
	{
		parent::__construct();

		hooks()->do_action('after_clients_area_init', $this);
	}
	public function clients_preview()
	{
		if (!has_contact_permission('projects')) 
		{
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }
		$this->load->model('diagramy_model');
		$diagramy_id=$this->uri->segment(4);
		$data['diagramy']=$this->diagramy_model->get_data_by_rel_id('diagramy', ['id'=>$diagramy_id]);
		if (!empty($data['diagramy'])) 
		{
			$this->load->view('client_view_diagramy_template',$data);
		}
		else
		{
			echo "<script>window.close();</script>";
		}
	}

}

/* End of file Clients.php */
/* Location: .//F/projects/this_work_home/perfex-diagramy/modules/diagramy/controllers/Clients.php */