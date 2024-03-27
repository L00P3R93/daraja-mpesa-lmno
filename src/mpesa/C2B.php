<?php
namespace app\mpesa;

class C2B{
    protected $production_url = 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl';
    protected $balance_url = 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';

    protected $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
    protected $url_simulate = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

    protected $core;

    /**
     * C2B constructor.
     */
    public function __construct(){$this->core = new Core();}
    
    /**
     * Calls request to register C2B URLs
     * @return bool|string
     */
    public function submit(){
        return $this->core->request_c2b_register($this->production_url);
    }
    
    /**
     * Simulates C2B Payment
     * @param array $user_params
     * @return bool|string
     */
    public function submit_simulate($user_params=[]){
        return $this->core->request_c2b_simulate($this->url_simulate, $user_params);
    }

    /**
     * Queries For C2B Paybill Account Balance
     * @return bool|string
     */
    public function account_balance(){
        return $this->core->request_account_balance_c2b($this->balance_url);
    }
}