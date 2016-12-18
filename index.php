<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);
define('VIEW', ROOT . 'View' . DS);
define('LIB_PATH', ROOT . 'Library' . DS);

use Library\Request;
use Library\Controller;
use Library\Config;
use Library\Session;
use Library\Container;
use Library\RepositoryManager;

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
	Session::start();

	Config::set('db_user', 'root');
	Config::set('db_password', '');
	Config::set('db_name', 'mvc');
	Config::set('db_host', 'localhost');

	$request = new Request();

	$dsn = 'mysql: host=' . Config::get('db_host') . '; dbname=' . Config::get('db_name');
	$pdo = new \PDO($dsn, Config::get('db_user'), Config::get('db_password'));

	$repository = (new RepositoryManager())->setPDO($pdo);

	$container = new Container();
	$container->set('database_connection', $pdo);
	$container->set('repository_manager', $repository);


	$route = explode('/', $request->get('route', 'site/index'));

	$controller = 'Controller\\' . ucfirst($route[0]) . 'Controller';
	$action = $route[1] . 'Action';

	if(!method_exists(new $controller, $action)){
		throw new \Exception('404 Page Not Found');
	}

	$controller = new $controller;
	$controller->setContainer($container);
	$content = $controller->$action($request);

}catch(\Exception $e){
	$content = Controller::renderError($e->getMessage(), $e->getFile());
}
require(VIEW . 'layout.phtml');
