<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS . '..' . DS);
define('VIEW', ROOT . 'View' . DS);
define('LIB_PATH', ROOT . 'Library' . DS);
define('CONFIG_PATH', ROOT . 'config' . DS);

use Library\Request;
use Library\Controller;
use Library\Config;
use Library\Session;
use Library\Container;
use Library\RepositoryManager;
use Library\DbConnection;
use Library\Router;

//Autoload 
spl_autoload_register(function($classname){
	$path = ROOT . str_replace('\\', DS, $classname). '.php';
	if(!file_exists(ROOT . str_replace('\\', DS, $classname). '.php')){
		throw new \Exception("Class $classname doesn't exist- {$path}");
	}

	require(ROOT . str_replace('\\', DS, $classname . '.php'));
});

//Define Controller and Action

try{
    $session = new Session();
    $session->start();
    $config = new Config(CONFIG_PATH . 'db.xml');
	$request = new Request();
    $router = new Router($request);
	$pdo = (new DbConnection($config))->getPDO();
	$repository = (new RepositoryManager())->setPDO($pdo);

	$container = new Container();
	$container->set('database_connection', $pdo)
	          ->set('repository_manager', $repository)
              ->set('config', $config)
              ->set('session', $session);

//	$route = explode('/', $request->get('route', 'site/index'));
//
//	$controller = 'Controller\\' . ucfirst($route[0]) . 'Controller';
//	$action = $route[1] . 'Action';
    $controller = 'Controller\\' . $router->getController();
    $action = $router->getAction();

	if(!method_exists(new $controller, $action)){
		throw new \Exception('404 Page Not Found');
	}

	$controller = new $controller;
	$controller->setContainer($container);
	$content = $controller->$action($request);

}catch(\Exception $e){
	$content = Controller::renderError($e->getMessage(), $e->getFile());
}

echo $content;
