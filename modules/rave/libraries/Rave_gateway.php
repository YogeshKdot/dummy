<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rave_gateway extends App_gateway
{   

    public function __construct()
    {
                $this->ci = &get_instance();
        /**
         * REQUIRED
         * Gateway unique id
         * The ID must be alpha/alphanumeric
         */
        $this->setId('rave');

        /**
         * REQUIRED
         * Gateway name
         */
        $this->setName('Rave');

        /**
         * Add gateway settings
        */
        $this->setSettings(
        [
            [
                'name'      => 'rave_public_key',
                'encrypted' => true,
                'label'     => 'Public Key',
                ],
            [
                'name'      => 'rave_Secret_key',
                'encrypted' => true,
                'label'     => 'Secret key',
                ], 
                [
                'name'      => 'rave_test_public_key',
                'encrypted' => true,
                'label'     => 'Test Public Key',
                ],
                [
                'name'      => 'rave_test_Secret_key',
                'encrypted' => true,
                'label'     => 'test Secret key',
                ],
            [
                'name'             => 'currencies',
                'label'            => 'settings_paymentmethod_currencies',
                'default_value'    => 'NGN,USD,EUR,GBP,UGX,TGS,KES',
                ],
            [
                'name'             => 'country',
                'label'            => 'COUNTRY CODE',
                'default_value'    => 'NG',
                ],
            [
                'name'          => 'test_mode_enabled',
                'type'          => 'yes_no',
                'default_value' => 1,
                'label'         => 'settings_paymentmethod_testing_mode',
                ],
            ]
        );

        hooks()->add_action('before_render_payment_gateway_settings', 'rave_notice');
    }

    /**
     * REQUIRED FUNCTION
     * @param  array $data
     * @return mixed
     */
 
        public function process_payment($data)
    { 
         $this->ci->session->set_userdata(['rave_total' => number_format($data['amount'], 2, '.', '')]);
        redirect(site_url('rave/make_payment?invoiceid=' . $data['invoiceid'] . '&hash=' . $data['invoice']->hash));
    }
    /**
     * Gets public key for all environments
     * @param  array $data
     * @return mixed
     */
 
    public function public_key(){
        return $this->getSetting('test_mode_enabled') == '1' ? $this->decryptSetting('rave_test_public_key') : $this->decryptSetting('rave_public_key');
    }
    /**
     * Gets secret key for all environments
     * @param  array $data
     * @param  array $data
     * @return mixed
     */
 
    public function secret_key(){
        return $this->getSetting('test_mode_enabled') == '1' ? $this->decryptSetting('rave_test_Secret_key') : $this->decryptSetting('rave_Secret_key');
    }
    /**
     * gentransaction referrence FUNCTION
     * @param  array $data
     * @return mixed
     */
    public function tx_ref($data){
       $tx_ref = format_invoice_number($data['invoice']->id).'-'.time();
       $tx_ref = str_replace('/', '',$tx_ref);
       return $tx_ref;
    }

    public function country_cd(){
       return $this->getSetting('country');
    }
}

function rave_notice($gateway)
{
    if ($gateway['id'] == 'rave') {
        echo '<p class="text-warning">country code notice</p>';
        echo '<p class="alert alert-warning bold">kindly note that the only allowable count code in rave are GH,KE,ZA,TZ,NG if none is set it defaultts to NG. you cannot combine two country codes. For more information <a class="btn btn-info" href="https://developer.flutterwave.com/docs/multicurrency-payments" target="_BLANK">Read HERE</a></p>';
    }
}
