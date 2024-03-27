<?php
namespace app\mpesa;

use Closure;

class Config implements \ArrayAccess {
    public $items = [];

    public function __construct($conf = []){
        $config_file = __DIR__.'/../config/conf.php';
        $default_config = [];
        if(is_file($config_file)){$default_config = require $config_file;}
        $default_config = array_merge($default_config, $conf);
        $this->items = $default_config;
    }
    public static function accessible($value){return is_array($value) || $value instanceof \ArrayAccess;}
    public static function exists($array, $key){
        if($array instanceof \ArrayAccess){return $array->offsetExists($key);}
        return array_key_exists($key, $array);
    }
    public function get($key, $default=null){
        $key = str_replace("mpesa.","", $key);
        $array = $this->items;
        if(!static::accessible($array)){return $this->value($default);}
        if(is_null($key)){return $array;}
        if(static::exists($array, $key)){return $array[$key];}
        if(strpos($key, '.') === false){return $array[$key]? : $this->value($default);}
        foreach(explode('.', $key) as $segment){
            if(static::accessible($array) and static::exists($array,$segment)){$array = $array[$segment];}
            else{return $this->value($default);}
        }
        return $array;
    }
    public function all(){return $this->items;}
    public function value($value){return $value instanceof Closure? $value() : $value;}
    public function offsetExists($offset){return $this->has($offset);}
    public function offsetGet($offset){return $this->get($offset);}
    public function offsetSet($offset, $value){$this->set($offset, $value);}
    public function offsetUnset($offset){$this->set($offset, null);}
}