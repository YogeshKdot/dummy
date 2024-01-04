<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rave extends App_Controller
{
    /**
     * REQUIRED FUNCTION
     * make_payment method
     * 
     */
    public function make_payment()
    {
        check_invoice_restrictions($this->input->get('invoiceid'), $this->input->get('hash'));

        $this->load->model('invoices_model');
        $invoice = $this->invoices_model->get($this->input->get('invoiceid'));

        load_client_language($invoice->clientid);

        $data['invoice'] = $invoice;
        $data['amount']  = $this->session->userdata('rave_total');
        $data['PBFPubKey']     = $this->rave_gateway->public_key();
        $data['txref']      = $this->rave_gateway->tx_ref($data);
        $data['country']      = $this->rave_gateway->country_cd();

        $this->load->model('clients_model');
        $contacts = $this->clients_model->get_contacts($data['invoice']->clientid);
         if (count($contacts) == 1) {
            $contact    = $contacts[0];
            $firstname = $contact['firstname'] ;
            $data['firstname']   =  $contact['firstname'];
            $lastname = $contact['lastname'];
            $data['lastname']    =  $contact['lastname'];

            if ($contact['email']) {
                $email = $contact['email'];
                $data['email']       = $email;
            }
            if ($contact['phonenumber']) {
                $phonenumber = $contact['phonenumber'];
                $data['phonenumber'] = $phonenumber;
            }
        }
           
        if (is_client_logged_in()) {
            $contact = $this->clients_model->get_contact(get_contact_user_id());
        } else {
            if (total_rows(db_prefix().'contacts', ['userid' => $invoice->clientid]) == 1) {
                $contact = $this->clients_model->get_contact(get_primary_contact_user_id($invoice->clientid));
            }
        }

        if (isset($contact) && $contact) {
            $data['customer_firstname']   = $contact->firstname;
            $data['customer_lastname']    = $contact->lastname;
            $data['customer_email']       = $contact->email;
            $data['customer_phone']       = $contact->phonenumber;
        }
        
        echo $this->get_html($data);
    }
    /**
     * REQUIRED FUNCTION
     * @param  array $data
     * @return payment modal
     */
 
    public function get_html($data)
    {
       ob_start(); ?>
       <?php echo payment_gateway_head(_l('payment_for_invoice') . ' ' . html_escape(format_invoice_number($data['invoice']->id))); ?>
         <body class="gateway-prave" onload="submit()">
           <div class="container">
              <div class="col-md-8 col-md-offset-2 mtop30">
                 <div class="mbot30 text-center">
                    <?php echo payment_gateway_logo(); ?>
                 </div>
                 <div class="row">
                    <div class="panel_s">
                       <div class="panel-body">
                          <h3 class="no-margin">
                             <b><?php echo _l('payment_for_invoice'); ?> </b>
                             <a href="<?php echo html_escape(site_url('invoice/' . $data['invoice']->id . '/' . $data['invoice']->hash)); ?>">
                             <b><?php echo html_escape(format_invoice_number($data['invoice']->id)); ?></b>
                             </a>
                          </h3>
                          <h4><?php echo _l('payment_total', html_escape(app_format_money($data['amount'], $data['invoice']->currency_name))); ?></h4>
                          <hr />
                          <?php if (html_escape($data['amount']) > 0) { ?>
                          <input id="ravepay" type="submit" class="btn btn-info" value="<?php echo _l('submit_payment'); ?>" onclick = "payWithRave()" />
                              <?php } else {
                                set_alert('warning','Invalid Payment Request');
                                redirect(html_escape(site_url('invoice/' . $data['invoice']->id . '/' .$data['invoice']->hash)));
                                } ?> 
                       </div>
                    </div>
                 </div>
              </div>
           </div>
           <?php echo payment_gateway_scripts(); ?>
          <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
           <script>
      function submit()
        {
            document.getElementById("ravepay").click(); 
            document.submitForm.submit(); // Submits the form without the button
        }

      const API_publicKey = "<?php echo html_escape($data['PBFPubKey']); ?>";

       function payWithRave() {
          var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo html_escape($data['customer_email']) ?>",
            amount: <?php echo html_escape($data['amount']) ?>,
            customer_firstname: "<?php echo html_escape($data['customer_firstname']) ?>",
            customer_lastname: "<?php echo html_escape($data['customer_lastname']) ?>",
            customer_phone: "<?php echo html_escape($data['customer_phone']) ?>",
            currency: "<?php echo html_escape($data['invoice']->currency_name) ?>",
            country: "<?php echo html_escape($data['country']) ?>",
            txref: "<?php echo html_escape($data['txref']) ?>",
            meta: [{
                metaname: "Description",
                metavalue: "AP1234"
            }],
            onclose: function() {
            },
            callback: function(response) {
                var txref = response.tx.txRef; // collect txRef returned and pass to a  server page to complete status check.
                if (
                    response.tx.chargeResponseCode == "00" ||
                    response.tx.chargeResponseCode == "0"
                ) {
                    // redirect to a success page
              location.replace("<?php echo html_escape(site_url('rave/verify?invoiceid=' . $data['invoice']->id . '&hash=' . $data['invoice']->hash)); ?>"+"&txref="+txref)
                } else {
                    // redirect to a failure page.
              location.replace("<?php echo html_escape(site_url('rave/failure?invoiceid=' . $data['invoice']->id . '&hash=' .$data['invoice']->hash)); ?>")
                }

                x.close(); //  close the modal immediately after payment.
            }
          });
        }
          </script>
           <?php echo payment_gateway_footer(); ?>
        <?php
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    public function verify()
    
    {
       $invoice_id =  $this->input->get('invoiceid');
       $invoice_hash =  $this->input->get('hash');
       $ref =  $this->input->get('txref');
        
       check_invoice_restrictions($invoice_id, $invoice_hash);
        
       $this->load->model('invoices_model');
       $invoice = $this->invoices_model->get($invoice_id);

       $currency = $invoice->currency_name;

       $query = array(
            "SECKEY" => $this->rave_gateway->secret_key(),
            "txref" => $ref
        );

       $data_string = json_encode($query);

        $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $err = curl_error($ch);
        curl_close($ch);   
      $resp = json_decode($response, true);
      
      if ($err){
        log_activity(' error in ravepay : ' .$err);
      }

      if ($resp['status'] == 'error') {
        log_activity('Payment in ravepay : ' .var_export($resp));
        set_alert('danger', 'Payment was UnSUcessful');
      } else {
        $paymentStatus = $resp['data']['status'];
        $chargeResponsecode = $resp['data']['chargecode'];
        $chargeAmount = $resp['data']['amount'];
        $chargeCurrency = $resp['data']['currency'];
        $chargenarration = $resp['data']['narration'];

        if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeCurrency == $currency) && ($paymentStatus== "successful")) {
            $success = $this->rave_gateway->addPayment([
                                'amount'        => $chargeAmount ,
                                'invoiceid'     => $invoice_id,
                                'paymentmethod' => $chargenarration,
                                'transactionid' => $ref,
                          ]);
                        set_alert($success ? 'success' : 'danger', _l($success ? 'online_payment_recorded_success' : 'online_payment_recorded_success_fail_database'));
                            
        } else {
            //Dont Give Value and return to Failure page
            set_alert('danger',  'Invalid Transaction'.var_export($err));
        }
        }
     $this->session->unset_userdata('rave_total');
   redirect(site_url('invoice/' . $invoice_id . '/' . $invoice_hash));
     }

  public function failure()
  {
      $invoiceid = $this->input->get('invoiceid');
      $hash      = $this->input->get('hash');

      set_alert('warning', _l('Transaction was unsuccessful'));

      $this->session->unset_userdata('rave_total');

      redirect(site_url('invoice/' . $invoiceid . '/' . $hash));
  }

}
