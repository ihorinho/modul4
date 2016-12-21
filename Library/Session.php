<?php

namespace Library;

class Session{

	const FLASH_KEY = 'flash_message';

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
		if(self::has($key)){
			return $_SESSION[$key];
		}

		return $default;
	}

	public function set($key, $value){
		$_SESSION[$key] = $value;

        return $this;
	}

	public function remove($key){
		if(self::has($key)){
			unset($_SESSION[$key]);
		}
	}

	public function setFlash($message){
		$this->set(self::FLASH_KEY, $message);

        return $this;
	}

	public function getFlash(){
		$message = self::get(self::FLASH_KEY);
		self::remove(self::FLASH_KEY);
		return $message;
	}
//todo: SESSION NON STATIC!!!
}