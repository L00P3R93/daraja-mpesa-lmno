<?php
namespace app\mpesa;

trait MpesaTraits{
    public function stk_push($user_params=[]){
        $stk = new LNMO();
        return $stk->submit($user_params);
    }
    public function c2b($user_params=[]){
        $c2b = new C2B();
        return $c2b->submit();
    }
    public function c2b_simulate($user_params=[]){
        $c2b = new C2B();
        return $c2b->submit_simulate($user_params);
    }
    public function c2b_account_balance(){
        $c2b = new C2B();
        return $c2b->account_balance();
    }
    public function b2c($user_params=[]){
        $b2c = new B2C();
        return $b2c->submit($user_params);
    }
    public function b2c_transaction_status($user_params=[]){
        $b2c = new B2C();
        return $b2c->transaction_status($user_params);
    }
    public function b2c_account_balance(){
        $b2c = new B2C();
        return $b2c->account_balance();
    }
    public function b2b($user_params=[]){
        $b2b = new B2B();
        return $b2b->submit($user_params);
    }
}