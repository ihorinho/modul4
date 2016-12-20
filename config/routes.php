<?php
use Library\Route;
return array(
    'home' => new Route('/home', 'SiteController', 'indexAction'),
    'books-list' => new Route('/books-list', 'BookController', 'indexAction'),
    '/contact-us' => new Route('/contact-us', 'SiteController', 'contactAction'),
    'login' => new Route('/login', 'SecurityController', 'loginAction'),
    'book-show' => new Route('/book-{id}', 'BookController', 'showAction', array('id' => '([1-9]{1}[0-9]*)')),
    'book-test-route' => new Route('/this-is-{test}-{id}-{pat}', 'TestController', 'testAction', array(
                                                                                                'id' => '([1-9]{1}[0-9]*)',
                                                                                                'test' => '([a-zA-Z]+)',
                                                                                                'pat' => '([\+\-\*\/]+)'
                                                                                                )),
    'logout' => new Route('/logout', 'SecurityController', 'logoutAction'),

    //Admin routes
    'login' => new Route('/admin/index', 'Admin\SecurityController', 'loginAction'),

);