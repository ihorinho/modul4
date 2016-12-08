<?php
namespace Model;
use Library\Request;

class ContactForm{
	private $username = '';
	private $password = '';
	private $message = '';

	public function __construct(Request $request){
		$this->username = $request->getP('username');
		$this->password = $request->getP('password');
		$this->message = $request->getP('message');
	}

	public function isValid(){
		return $this->username !== '' &&
				$this->password !== ''&&
				$this->message !== '';
	}
	public function getUserName(){
		return $this->username;
	}
	public function getPassword(){
		return $this->password;
	}
	public function getMessage(){
		return $this->message;
	}
}