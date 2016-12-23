<?php
namespace Library;

class Cookie{

    public function set($name, $value, $expire = 3600){
        setcookie($name, $value, time() + $expire, '/');
    }

    public function get($name){
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public function delete($name){
        setcookie($name, '', time()-3600, '/');
        unset($_COOKIE[$name]);
    }

    public function has($name){
        return isset($_COOKIE[$name]);
    }
}