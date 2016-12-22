<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/22/16
 * Time: 1:01 AM
 */
namespace Model;

use Library\Cookie;

class Cart{

    private $cart;

    public function __construct(Cookie $cookie){
        $this->cart = $cookie->has('cart') ? unserialize($cookie->get('cart')) : array();
    }

    public function save(){
        $cart = serialize($this->cart);
        setcookie('cart', $cart, time()+ 3600*24*7);
    }

    public function add($id){
        $this->cart[$id] = $id;

        return $this;
    }

    public function delete($id){
        unset($this->cart[$id]);

        return $this;
    }

    public function show(){
        return $this->cart;
    }
}