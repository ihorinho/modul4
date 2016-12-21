<?php
namespace Library;

class Cookie{
    private $cookie = [];


    public function __construct(){
        $this->cookie = $_COOKIE;
    }

    public function set($name, $value, $expire = ''){
        setcookie($name, $value, $expire);
    }

    public function delete($name){
        setcookie($name, '', time()-3600);
        unset($this->cookie[$name]);
    }

    public function has($name){
        return isset($this->cookie[$name]);
    }
}