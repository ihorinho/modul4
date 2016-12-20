<?php

namespace Library;

class Router{

    private $uri;
    private $routes;
    private $controller;
    private $action;
    private $params;
    public function __construct(Request $request){
        $path_parts = explode('?', $request->getUri());
        $this->params = isset($path_parts[1]) ? $path_parts[1] : null;
        $uri = $this->uri = $path_parts[0];
        $routes = $this->routes = require (CONFIG_PATH . 'routes.php');
        $pattern = '@^' . $uri . '$@';

        foreach($routes as $route){
            if(preg_match($pattern, $route[0], $match)){
                $this->controller = $route[1];
                $this->action = $route[2];
                break;
            }
        }

        if(empty($match)){
            throw new \Exception('404 Page Not Found');
        }
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    public function match(){

    }

	public function redirect($to){
		header("Location: {$to}");
		die;
	}
}