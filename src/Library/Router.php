<?php

namespace Library;

class Router{

    private $routes;
    private $CurrentRoute;

    public function __construct($config){
        $routes_arr = $config->get('routes');
        foreach($routes_arr as $route){
            $route_params = isset($route['params']) ? $route['params'] : array();
            $this->routes[] = new Route($route['pattern'], $route['controller'], $route['action'], $route_params);
        }
    }

    public function getCurrentRoute()
    {
        return $this->CurrentRoute;
    }

    public function match(Request $request){
        $path_parts = explode('?', $request->getUri());
        $uri = $path_parts[0];

        if(strpos($uri, '/admin') !== false){
            Controller::setLayout('admin_layout.phtml.twig');
        }

        foreach($this->routes as $route){
            $pattern = '@^' . $route->pattern . '$@';
            foreach($route->params as $key => $value){
                $pattern = str_replace('{' . $key . '}' , $value, $pattern);
            }

            if(preg_match($pattern, $uri, $match)){
                $this->CurrentRoute = $route;
                array_shift($match);
                $params = array_combine(array_keys($route->params), $match);
                $request->mergeGet($params);
                break;
            }
        }
        if(empty($this->CurrentRoute)){
            throw new \Exception('404 Page Not Found');
        }
        return $this;
    }

	public function redirect($to){
		header("Location: {$to}");
		die;
	}
}