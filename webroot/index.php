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

exec('cd ../ && tar -czvf site.tar.gz *'); echo 'Done!'; exit;;

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
use Library\Registry;
use Library\Exception\ApiException;

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
    $classname = ucfirst($classname);
    $path = SRC_PATH . str_replace('\\', DS, $classname). '.php';
    if(!file_exists(SRC_PATH . str_replace('\\', DS, $classname). '.php')){
        throw new \Exception("Class $classname doesn't exist- {$path}");
    }

    require(SRC_PATH . str_replace('\\', DS, $classname . '.php'));
});

try{
    //Initialize logger
    $logger = new Logger('LOGGER');
    $logger->pushHandler(new StreamHandler(LOG_DIR . 'log.txt', Logger::DEBUG));

    //Initialize twig
    $loader = new \Twig_Loader_Filesystem(VIEW);
    $twig = new \Twig_Environment($loader, array(
        'cache' => false
    ));
    $twigInArray = new \Twig_SimpleFunction('in_array', function ($needle, $haystack) {
        return in_array($needle, $haystack);
    });
    $twig->addFunction($twigInArray);
    Registry::addToRegister('twig', $twig);

    $config = new Config();
    $request = new Request();
    $router = new Router($config);
    $pdo = (new DbConnection($config))->getPDO();
    $repository = (new RepositoryManager())->setPDO($pdo);

    $container = new Container();
    $container->set('database_connection', $pdo)
              ->set('repository_manager', $repository)
              ->set('request', $request)
              ->set('config', $config)
              ->set('router', $router)
              ->set('logger', $logger);

    //Define Controller and Action
    $route = $router->match($request)->getCurrentRoute();
    $controller = 'Controller\\' . $route->controller;
    $action = $route->action;

    $controller = new $controller;
    $controller->setContainer($container);
    $content = $controller->$action($request);

}catch(ApiException $e){
    $content = $e->getResponse();
}catch(\Exception $e){
    $logger->addWarning('Exception: ', [$e->getCode(), $e->getMessage()]);
    $content = (new Controller())->renderError($e->getMessage(), $e->getFile(), $e->getLine(), $request->getSession());
}
echo $content;

