<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Loyalty portal Controller
 */
class Loyalty_portal extends ClientsController
{   
    /**
     * construct
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('loyalty_model');
    }


    /**
     * index
     * @return view
     */
    public function index()
    {   
    	if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('authentication'));
        }
        $data['title']            = _l('loyalty_portal');
        $data['transations'] = $this->loyalty_model->get_transation_by_client(get_client_user_id());
        $data['programs'] = $this->loyalty_model->get_program_by_client(get_client_user_id());
        $data['rd_logs'] = $this->loyalty_model->get_redeem_log_by_client(get_client_user_id());
        $this->data($data);
        $this->view('loyalty_portal/home');
        $this->layout();
    }

    /**
     * { program detail }
     *
     * @param      $program  The program
     * @return json
     */
    public function program_detail($program){
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('authentication'));
        }

        $pg = $this->loyalty_model->get_membership_program($program);

        $html = '';
        $html .= '<span class="label label-warning">Program '.$pg->program_name.'</span><br><br>';
        if($pg->discount == 'card_total'){
            $html .= '<p class="bold">Discount '.$pg->discount_percent.'% for every order.</p>';
        }elseif($pg->discount == 'product_category'){
            $html .= '<table class="table table-bordered table-striped">';
            $html .=    '<tbody>';
            $html .= '<tr>'; 
            $html .= '<td>'._l('product_category').'</td>';
            $html .= '<td>'._l('discount_percent').'</td>';
            $html .= '</tr>';
            foreach($pg->discount_detail as $dt){
                $html .= '<tr>'; 
                $html .= '<td>'.product_category_by_id($dt['rel_id']).'</td>';
                $html .= '<td>'.$dt['percent'].'%</td>';
                $html .= '</tr>';
            }

            $html .=    '</tbody>';
            $html .= '</table>';
        }elseif($pg->discount == 'product'){
            $html .= '<table class="table table-bordered table-striped">';
            $html .=    '<tbody>';
            $html .= '<tr>'; 
            $html .= '<td>'._l('product_loy').'</td>';
            $html .= '<td>'._l('discount_percent').'</td>';
            $html .= '</tr>';
            foreach($pg->discount_detail as $dt){
                $html .= '<tr>'; 
                $html .= '<td>'.product_by_id($dt['rel_id']).'</td>';
                $html .= '<td>'.$dt['percent'].'%</td>';
                $html .= '</tr>';
            }

            $html .=    '</tbody>';
            $html .= '</table>';
        }
         $html .= '<hr>';
        echo json_encode([
            'html' => $html,
        ]);
    }

    /**
     * { redeem inv }
     */
    public function redeem_inv(){
        if($this->input->post()){
            $data = $this->input->post();

            $this->load->model('invoices_model');
            $invoice = $this->invoices_model->get($data['inv_id']);

            $success = $this->loyalty_model->redeem_inv_client($data);

            if($success){
                set_alert('success', _l('successfully_redeemed_points'));
            }else{
                set_alert('success', _l('failed_to_redeem_points'));
            }

            redirect(site_url('invoice/'.$data['inv_id'].'/'.$invoice->hash));
        }
    }

    /**
       * voucher apply 
       * @return  json
       */
    public function voucher_apply(){
        $data = $this->input->post();           
        $return = $this->loyalty_model->apply_voucher_to_portal($data['clientid'],$data['voucher']);

        if(count($return) > 0){
            echo json_encode(['rs' => $return]);
        }else{ 
            echo json_encode(['rs' => '']);
        }
    }

    /**
       * voucher apply 
       * @return  json
       */
    public function get_mbs_discount(){
        $data = $this->input->post();           
        $return = $this->loyalty_model->apply_mbs_program_discount($data['clientid']);

        echo json_encode([$return]);
    }
}