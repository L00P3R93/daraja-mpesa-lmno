<?php
namespace app\mpesa;

class B2C{
    protected $url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
    protected $production_url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
    protected $transaction_url = 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';
    protected $balance_url = 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';
    protected $core;

    public function __construct(){$this->core = new Core();}
    public function submit($user_params=[]){
        return $this->core->request_b2c($this->production_url, $user_params);
    }
    public function transaction_status($user_params=[]){
        return $this->core->request_transaction_status_b2c($this->transaction_url, $user_params);
    }
    public function account_balance(){
        return $this->core->request_account_balance_b2c($this->balance_url);
    }
}