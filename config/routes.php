<?php
use Library\Route;
return array(
    'home' => new Route('/home', 'SiteController', 'indexAction'),
    'books-list' => new Route('/books-list', 'BookController', 'indexAction'),
    'contact-us' => new Route('/contact-us', 'SiteController', 'contactAction'),
    'login' => new Route('/login', 'SecurityController', 'loginAction'),
    'book-show' => new Route('/book-{id}', 'BookController', 'showAction', array('id' => '([1-9]{1}[0-9]*)')),
    'book-test-route' => new Route('/this-is-{test}-{id}-{pat}', 'TestController', 'testAction', array(
                                                                                                'id' => '([1-9]{1}[0-9]*)',
                                                                                                'test' => '([a-zA-Z]+)',
                                                                                                'pat' => '([\+\-\*\/]+)'
                                                                                                )),
    'logout' => new Route('/logout', 'SecurityController', 'logoutAction'),
    'add_to_cart' =>new Route('/cart-add-{id}', 'CartController', 'addAction', array('id' => '([1-9]{1}[0-9]*)')),
    'show_cart' => new Route('/cart-list', 'CartController', 'showAction'),
    'delete_from_cart' => new Route('/cart-delete-{id}', 'CartController', 'deleteAction', array('id' => '([1-9]{1}[0-9]*)')),

//Admin routes
    'admin_index' => new Route('/admin/index', 'Admin\SiteController', 'indexAction'),
    'admin_all_books' => new Route('/admin/books-list', 'Admin\BookController', 'indexAction'),
    'admin/book-show' => new Route('/admin/show-book-{id}', 'Admin\BookController', 'showAction', array('id' => '([1-9]{1}[0-9]*)')),
    'admin_edit_book' => new Route('/admin/edit-book-{id}', 'Admin\BookController', 'editAction',
                                                                array('id' => '([1-9]{1}[0-9]*)')),
    'admin_delete_book' => new Route('/admin/delete-book-{id}', 'Admin\BookController', 'deleteAction',
            array('id' => '([1-9]{1}[0-9]*)'))

);