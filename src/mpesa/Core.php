<?php
namespace app\mpesa;

use app\mpesa\Auth;

class Core{
    protected $auth;
    protected $access_token;
    public function __construct(){$this->auth = new Auth();}

    /**
     * Used for Lipa Na MPESA Online (LMNO)
     * Pushes a payment request to customers Phone
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_stk($url,$user_params=[]){
        $this->access_token = $this->auth->authenticate('sandbox_default', 'sandbox');
        $config_params = [
            'BusinessShortCode' => $this->auth->config->get('mpesa.lnmo.short_code'),
            'Password' => $this->auth->secure_credentials(),
            'Timestamp' => $this->auth->time_stamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'PartyB' => $this->auth->config->get('mpesa.lnmo.short_code'),
            'CallBackURL' => $this->auth->config->get('mpesa.lnmo.callback'),
            'AccountReference' => 'TestCompanyXYZ',
            'TransactionDesc' => 'Payment of XYZ'
        ];
        return $this->request($url, $config_params, $user_params);
    }

    /**
     * Sets up parameters for simulating C2B payment
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_c2b_simulate($url, $user_params=[]){
        $this->access_token = $this->auth->authenticate('c2b', 'production');
        $config_params = [
            'CommandID' => $this->auth->config->get('mpesa.c2b.default_command_id'),
            'Amount' => '10',
            'Msisdn' => '254708374149',
            'BillRefNumber' => 'TRIPPINMAD',
            'ShortCode' => $this->auth->config->get('mpesa.c2b.short_code')
        ];
        return $this->request($url, $config_params, $user_params);
    }

    /**
     * Sets up parameters for registering C2B Callback URLS.
     * Called only ONCE.
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_c2b_register($url, $user_params=[]){
        $this->access_token = $this->auth->authenticate('c2b', 'production');
        $config_params = [
            'ShortCode' => $this->auth->config->get('mpesa.c2b.short_code'),
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $this->auth->config->get('mpesa.c2b.confirmation_url'),
            'ValidationURL' => $this->auth->config->get('mpesa.c2b.validation_url')
        ];
        return $this->request($url, $config_params, $user_params);
    }

    /**
     * Sets Up B2C Parameters
     * Generated access_token based on environment
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_b2c($url, $user_params=[]){
        $this->access_token = $this->auth->authenticate('b2c','production');
        $pass = $this->auth->config->get('mpesa.b2c.security_credential');
        $config_params = [
            'InitiatorName' => $this->auth->config->get('mpesa.b2c.initiator_name'),
            /*'SecurityCredential' => 'gU1mSoy5+lTGYMG1+QUcCDqIxnHV+hY+1eOwGoguZofl47mYjVO5hDfS7Tm6cu1QXGOyfO7wvBA6EcLzVQqqbKpKWllod+4S0JV3qWvBXbc9CcfTPmCajo+KnvAtqXTLNWWMJYXJtuAcVMXpPfGcqPw+t4Fuyk0rnnSyKwcU+69E8eaL6/yiYTZlz4hoN2OinpbX2KE4iBFsuNAaOq+Jeb0/vp7CrtIqyvUeyvSTDl7LWk37KwphhMc+HKfisa9YGygdhx+u3YvxeqjNfuCmsaufUCRSwIY2XGOnC5O0X6MkX3mjGapuTkHsnmCjm04EcJJmhuS9Kl6su9wAW+CcLw==',*/
            'SecurityCredential' => $this->compute_security_credentials($pass),
            'CommandID' => $this->auth->config->get('mpesa.b2c.default_command_id'),
            'PartyA' => $this->auth->config->get('mpesa.b2c.short_code'),
            'QueueTimeOutURL' => $this->auth->config->get('mpesa.b2c.timeout_url'),
            'ResultURL' => $this->auth->config->get('mpesa.b2c.result_url'),
        ];
        return $this->request($url, $config_params, $user_params);
    }

    /**
     * Sets up B2B Parameters
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_b2b($url, $user_params=[]){
        $user_params = [
            'Amount' => '10',
            'PartyA' => '603021', # Shortcode 1
            'PartyB' => '600000', # Shortcode 2
            'AccountReference' => '', # Account Reference mandatory for “BusinessPaybill” CommandID.
        ];
        $config_params = [
            'Initiator' => $this->auth->config->get('mpesa.b2b.initiator_name'),
            'SecurityCredential' => $this->compute_security_credentials_($this->auth->config->get('mpesa.b2b.security_credential')),
            'CommandID' => $this->auth->config->get('mpesa.b2b.default_command_id'), # possible values are: BusinessPayBill, MerchantToMerchantTransfer, MerchantTransferFromMerchantToWorking, MerchantServicesMMFAccountTransfer, AgencyFloatAdvance
            'SenderIdentifierType' => '4', # Type of organization sending the transaction.
            'ReceiverIdentifierType' => '4',
            'Remarks' => 'Funds Transfer',
            'QueueTimeOutURL' => $this->auth->config->get('mpesa.b2b.timeout_url'),
            'ResultURL' => $this->auth->config->get('mpesa.b2b.result'),
        ];
        return $this->request($url, $config_params, $user_params);
    }

    /**
     * Return the status of transaction given the transaction ID
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_transaction_status_b2c($url, $user_params=[]){
        $this->access_token = $this->auth->authenticate('b2c','production');
        //$user_params = ['TransactionID' => ''];
        $pass = $this->auth->config->get('mpesa.b2c.security_credential');
        $config_params = [
            'Initiator' => $this->auth->config->get('mpesa.b2c.initiator_name'),
            'SecurityCredential' => $this->compute_security_credentials($pass),
            'CommandID' => 'TransactionStatusQuery',
            'PartyA' => '509296',
            'IdentifierType' => '4',
            'Remarks' => 'Transaction Status Query',
            'Occasion' => '',
            'QueueTimeOutURL' => $this->auth->config->get('mpesa.transaction_status_b2c.timeout_url'),
            'ResultURL' => $this->auth->config->get('mpesa.transaction_status_b2c.result_url'),
        ];

        return $this->request($url, $config_params, $user_params);
    }
    
    /**
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_account_balance_b2c($url, $user_params=[]){
        $this->access_token = $this->auth->authenticate('b2c','production');
        $pass = $this->auth->config->get('mpesa.b2c.security_credential');
        $config_params = [
            'Initiator' => $this->auth->config->get('mpesa.b2c.initiator_name'),
            'SecurityCredential' => $this->compute_security_credentials($pass),
            'CommandID' => 'AccountBalance',
            'PartyA' => '509296',
            'IdentifierType' => '4',
            'Remarks' => 'Account Balance Query',
            'QueueTimeOutURL' => $this->auth->config->get('mpesa.account_balance_b2c.timeout_url'),
            'ResultURL' => $this->auth->config->get('mpesa.account_balance_b2c.result_url'),
        ];

        return $this->request($url, $config_params, $user_params);
    }

    /**
     * @param $url
     * @param array $user_params
     * @return bool|string
     */
    public function request_account_balance_c2b($url, $user_params=[]){
        $this->access_token = $this->auth->authenticate('c2b','production');
        $pass = $this->auth->config->get('mpesa.c2b.security_credential');
        $config_params = [
            'Initiator' => $this->auth->config->get('mpesa.c2b.initiator_name'),
            'SecurityCredential' => $this->compute_security_credentials($pass),
            'CommandID' => 'AccountBalance',
            'PartyA' => '597716',
            'IdentifierType' => '4',
            'Remarks' => 'Account Balance Query',
            'QueueTimeOutURL' => $this->auth->config->get('mpesa.account_balance_c2b.timeout_url'),
            'ResultURL' => $this->auth->config->get('mpesa.account_balance_c2b.result_url'),
        ];

        return $this->request($url, $config_params, $user_params);
    }

    /**
     * Performs the Base Curl Requests
     * @param string $url
     * @param array $config_params
     * @param array $user_params
     * @return bool|string
     */
    public function request($url='', $config_params=[], $user_params=[]){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization:Bearer '.$this->access_token));

        $curl_post_data = array_merge($config_params, $user_params);
        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        //$response = curl_exec($curl);
        //$response = json_decode($response);
        return curl_exec($curl);
    }

    /**
     * Generate Security Credential for Sandbox Env
     * @param $initiator_pass
     * @return string
     */
    public function compute_security_credentials_($initiator_pass){
        //$public_key = openssl_pkey_get_public(file_get_contents('./sandboxCert.txt'));
        $kf = fopen(dirname(__FILE__)."/sandboxCert.txt", "r") or die("Unable to open file!");
        $pub_key = fread($kf,filesize(dirname(__FILE__)."/sandboxCert.txt")); fclose($kf);
        $public_key = openssl_pkey_get_public($pub_key);
        openssl_public_encrypt($initiator_pass, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);
        return base64_encode($encrypted);
    }

    /**
     * Generate Security Credential for Production Env
     * @param $initiator_pass
     * @return string
     */
    public function compute_security_credentials($initiator_pass){
        $kf = fopen(dirname(__FILE__)."/productionCert.txt", "r") or die("Unable to open file!");
        $pub_key = fread($kf,filesize(dirname(__FILE__)."/productionCert.txt")); fclose($kf);
        $public_key = openssl_pkey_get_public($pub_key);
        openssl_public_encrypt($initiator_pass, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);
        return base64_encode($encrypted);
    }
}