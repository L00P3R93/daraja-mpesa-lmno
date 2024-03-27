<?php
namespace app\mpesa;
class Auth{
    public $config;
    protected $sandbox_auth_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    protected $production_auth_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    protected $headers = ['Content-Type: application/json;charset=utf-8'];
    public $time_stamp;
    public function __construct(){
        $this->config = new Config();
        $this->time_stamp = date("YmdHis");
    }
    public function authenticate($app_name, $env='sandbox'){
        try{
            if($env == 'production') $auth_url = $this->production_auth_url;
            else $auth_url = $this->sandbox_auth_url;
            
            $credentials = $this->get_credentials($app_name);
            
            $curl = curl_init($auth_url);
            
            /*curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Basic '.$credentials]);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);*/
            
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_HEADER,FALSE);
            curl_setopt($curl, CURLOPT_USERPWD, $credentials);

            $result = curl_exec($curl);
            if (curl_errno($curl)) { 
               throw new \Exception(curl_error($curl));
            }
            curl_close($curl);
            $result = json_decode($result);
            return $result->access_token;
        }catch(\Exception $e){return $e;}
    }
    public function get_credentials($active_app){
        $apps = $this->config->get('mpesa.apps');
        if(!isset($apps[$active_app])){throw new \Exception("No active apps listed.");}
        $key = $apps[$active_app]['consumer_key'];
        $secret = $apps[$active_app]['consumer_secret'];
        if(empty($key) or empty($secret)){throw new \Exception("No Consumer Key and Secret Set.");}
        //return base64_encode($key.':'.$secret);
        return $key.':'.$secret;
    }
    public function secure_credentials(){
        $apps = $this->config->get('mpesa.apps');
        $short_code = $this->config->get('mpesa.lnmo.short_code');
        $pass_key = $this->config->get('mpesa.lnmo.passkey');
        return base64_encode($short_code.$pass_key.$this->time_stamp);
    }
}