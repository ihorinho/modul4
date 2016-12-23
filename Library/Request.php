<?php
namespace Library;

use Library\Session;

class Request{
	private $get;
	private $post;
    private $server;
    private $session;

	public function __construct(){
		$this->get = $_GET;
		$this->post = $_POST;
		$this->server = $_SERVER;
        $this->session = new Session();
    }

	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public function method(){
		return $_SERVER['REQUEST_METHOD'] == 'POST' ? 'post' : 'get';
	}

	public function get($key, $default = null){
		return isset($this->get[$key]) ? $this->get[$key] : $default;
	}
	public function post($key, $default = null){
		return isset($this->post[$key]) ? $this->post[$key] : $default;
	}
	public function server($key, $default = null){
		return isset($this->server[$key]) ? $this->server[$key] : $default;
	}

    public function getIpAddress(){
        return $this->server('REMOTE_ADDR');
    }

	public function getUri(){
	    return $this->server('REQUEST_URI');
    }

    public function getSession(){
        return $this->session;
    }

    public function mergeGet($array){
        $this->get += $array;
    }

    public function getCookie($name){
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public function setCookie($name, $value, $expire = 3600){
        setcookie($name, $value, time() + $expire, '/');
    }
}