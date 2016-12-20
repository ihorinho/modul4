<?php
namespace Library;
class Request{
	private $get = [];
	private $post = [];

	public function __construct(){
		$this->get = $_GET;
		$this->post = $_POST;
		$this->server = $_SERVER;
	}

	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	public function getIpAddress(){
		return $this->server('REMOTE_ADDR');
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

	public function getP($key){
		$method = $this->method();
		return $this->$method($key) ;;
	}

	public function getUri(){
	    return $this->server('REQUEST_URI');
    }

    public function mergeGet($array){
        $this->get += $array;
    }
}