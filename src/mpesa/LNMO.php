<?php
namespace app\mpesa;

class LNMO{
    protected $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    protected Core $core;
    public function __construct(){$this->core = new Core();}

    public function submit($user_params=[]){
        return $this->core->request_stk($this->url, $user_params);
    }
}