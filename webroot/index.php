<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS . '..' . DS);
define('SRC_PATH', ROOT . 'src' . DS);
define('VIEW', SRC_PATH . 'View' . DS);
define('LIB_PATH', SRC_PATH . 'Library' . DS);
define('CONFIG_PATH', ROOT . 'config' . DS);
define('UPLOAD_PATH', ROOT . 'webroot' . DS . 'upload' . DS . 'avatars' . DS);
define('LOG_DIR', ROOT . 'log' . DS);
define ('VENDOR_PATH', ROOT . 'vendor' . DS);

require VENDOR_PATH . 'autoload.php';

use Library\Request;
use Library\Controller;
use Library\Config;
use Library\Container;
use Library\RepositoryManager;
use Library\DbConnection;
use Library\Router;
use Model\Cart;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

//TODO: replace functions to saparate file
function dump($data, $die = true){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
  if($die){
      die;
  }
}

function clearString($string){
    return trim(strip_tags($string));
}

//Autoload 
spl_autoload_register(function($classname){
	$path = SRC_PATH . str_replace('\\', DS, $classname). '.php';
	if(!file_exists(SRC_PATH . str_replace('\\', DS, $classname). '.php')){
		throw new \Exception("Class $classname doesn't exist- {$path}");
	}

	require(SRC_PATH . str_replace('\\', DS, $classname . '.php'));
});

try{
    $config = new Config();
	$request = new Request();
    $router = new Router();
    $cart = new Cart($request);
    $logger = new Logger('LOGGER');
    $logger->pushHandler(new StreamHandler(LOG_DIR . 'log.txt', Logger::DEBUG));
    $pdo = (new DbConnection($config))->getPDO();
    $repository = (new RepositoryManager())->setPDO($pdo);

	$container = new Container();
	$container->set('database_connection', $pdo)
	          ->set('repository_manager', $repository)
              ->set('request', $request)
              ->set('config', $config)
              ->set('router', $router)
              ->set('logger', $logger)
              ->set('cart', $cart);

    //Define Controller and Action
    $route = $router->match($request)->getCurrentRoute();
    $controller = 'Controller\\' . $route->controller;
    $action = $route->action;

//	if(!method_exists(new $controller, $action)){
//		throw new \Exception('404 Page Not Found');
//	}

	$controller = new $controller;
	$controller->setContainer($container);
	$content = $controller->$action($request);

}catch(\Exception $e){
    $logger->addWarning('Exception: ', [$e->getCode(), $e->getMessage()]);
    $content = (new Controller())->renderError($e->getMessage(), $e->getFile(), $e->getLine(), $request->getSession());
}
echo $content;
