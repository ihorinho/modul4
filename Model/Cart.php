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
        $this->cart = $cookie->get('cart') ? unserialize($cookie->get('cart')) : array();
        $_SESSION['cart_size'] = count($this->cart);
    }

    public function save(){
        $cart = serialize($this->cart);
        setcookie('cart', $cart, time()+ 3600*24*7, '/');
    }

    public function add($id){
        $this->cart[$id] = (int)$id;

        return $this;
    }

    public function delete($id){
        unset($this->cart[$id]);

        return $this;
    }

    public function show(){
        return $this->cart;
    }

    public function isEmpty(){
        return empty($this->cart);
    }
}