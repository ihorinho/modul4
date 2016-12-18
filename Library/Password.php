<?php

namespace Library;

class Password{

	const SALT = 'Hala, Madrid!';

	private $salt;
	
	private $hashedPassword;

	public function __construct($word, $salt = null){
		$salt = is_null($salt) ? md5(self::SALT) : md5($salt);
		$this->salt = $salt;
		$this->hashedPassword = md5($salt . $word);
	}

	public function __toString(){
		return $this->hashedPassword;
	}
}