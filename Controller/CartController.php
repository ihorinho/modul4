<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/22/16
 * Time: 12:56 AM
 */
namespace Controller;

use Library\Controller;
use Library\Request;

class CartController extends Controller{

    public function showAction(Request $request){
        $cart = $this->container->get('cart');
        //todo: replace Exception
        if($cart->isEmpty()){
            throw new  \Exception('Cart is empty!');
        }
        $repo = $this->container->get('repository_manager')->getRepository('Book');

        $books = $repo->getByIdArray($cart->show());

        return $this->render('show_all.phtml', array('books' => $books));
    }

    public function addAction(Request $request){
        $cart = $this->container->get('cart');
        $cart->add($request->get('id'))->save();
        $router = $this->container->get('router');
        $router->redirect('/books-list');
    }

    public function deleteAction(Request $request){
        $cart = $this->container->get('cart');
        $cart->delete($request->get('id'))->save();
        $router = $this->container->get('router');
        $router->redirect('/cart-list');
    }
}