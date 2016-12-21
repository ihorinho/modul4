<?php
namespace Library;

class Cookie{

    public function set($name, $value, $expire = time()+3600){
        setcookie($name, $value, $expire);
    }

    public function delete($name){
        setcookie($name, '', time()-3600);
        unset($_COOKIE[$name]);
    }

    public function has($name){
        return isset($_COOKIE[$name]);
    }
}