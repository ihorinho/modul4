<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/22/16
 * Time: 1:01 AM
 */
namespace Model;

use Library\Request;

class Cart{

    private $cart;

    public function __construct(Request $request){
        $this->cart = $request->getCookie('cart') ? unserialize($request->getCookie('cart')) : array();
        $session =  $request->getSession();
        $session->set('cart_size', count($this->cart));
    }

    public function save(Request $request){
        $cart = serialize($this->cart);
        $request->setCookie('cart', $cart, time() + 3600*24*7, '/');
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