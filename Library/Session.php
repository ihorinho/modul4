<?php

namespace Library;

class Session{

	const FLASH_KEY = 'flash_message';

    public function __construct(){
        $this->start();
    }

	public function start(){
		session_start();
	}

	public function session_destroy(){
		session_destroy();
	}

	public function has($key){
		return isset($_SESSION[$key]);
	}

	public function get($key, $default = null){
		if($this->has($key)){
			return $_SESSION[$key];
		}

		return $default;
	}

	public function set($key, $value){
		$_SESSION[$key] = $value;

        return $this;
	}

	public function remove($key){
		if($this->has($key)){
			unset($_SESSION[$key]);
		}
	}

	public function setFlash($message){
		$this->set(self::FLASH_KEY, $message);

        return $this;
	}

	public function getFlash(){
		$message = $this->get(self::FLASH_KEY);
        $this->remove(self::FLASH_KEY);
		return $message;
	}
}