<?php
namespace app\mpesa;

class B2B{
    protected $url = '';
    protected $core;

    public function __construct(){$this->core = new Core();}
    public function submit($user_params=[]){
        return $this->core->request_b2b($this->url, $user_params);
    }

}