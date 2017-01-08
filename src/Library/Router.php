<?php

namespace Library;

class Router{

    private $routes;
    private $allowedMethods;
    private $apiRequest = false;
    private $CurrentRoute;

    public function __construct($config){
        $this->allowedMethods = $config->get('api_methods');
        $routes_arr = $config->get('routes');
        foreach($routes_arr as $route){
            $route_params = isset($route['params']) ? $route['params'] : array();
            $route_methods = isset($route['methods']) ? $route['methods'] : $this->allowedMethods['default'];
            $this->routes[] = new Route($route['pattern'], $route['controller'], $route['action'],
                                            $route_params, $route_methods);
        }
    }

    public function getCurrentRoute()
    {
        return $this->CurrentRoute;
    }

    public function match(Request $request){

        $path_parts = explode('?', $request->getUri());
        $uri = $path_parts[0];

        if(strpos($uri, '/api/') !== false){
            $this->apiRequest = true;
            $method = strtolower($request->getMethod());
        }
        if(strpos($uri, '/admin') !== false){
            Controller::setLayout('admin_layout.phtml.twig');
        }

        foreach($this->routes as $route){
            $pattern = '@^' . $route->pattern . '$@';
            foreach($route->params as $key => $value){
                $pattern = str_replace('{' . $key . '}' , $value, $pattern);
            }
            if(preg_match($pattern, $uri, $match)){
                if($this->apiRequest){
                    if(!$this->isAllowedMethod($method, $route->pattern)){
                        throw new \Exception('Method not allowed');
                    }
                    if(!in_array($method, $route->methods)){
                        continue;
                    }
                }
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

    public function isAllowedMethod($method, $pattern){
       if(!in_array($method, $this->allowedMethods[$pattern]))
           return false;
       return true;
    }

	public function redirect($to){
		header("Location: {$to}");
		die;
	}
}