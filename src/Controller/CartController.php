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
            $session = $this->getSession();
            $session->setFlash('Your cart is empty!');
            $this->redirect('/home');
        }
        $repo = $this->container->get('repository_manager')->getRepository('Book');

        $books = $repo->getByIdArray($cart->show());

        return $this->render('show_all.phtml', array('books' => $books));
    }

    public function addAction(Request $request){
        $cart = $this->container->get('cart');
        $cart->add($request->get('id'))->save($request);
        $this->redirect("/books/list/$page");
    }

    public function deleteAction(Request $request){
        $cart = $this->container->get('cart');
        $cart->delete($request->get('id'))->save($request);
        $this->redirect('/cart/list');
    }

    //TODO: maybe OrderController
    public function orderAction(Request $request)
    {
        /**
         * 1. Order table in DB  (id, user-data(?), email, order_item_id, created, status)
         * 2. OrderItem table (id, product_id, price)
         * 3. Form, Model
         **/
    }
}