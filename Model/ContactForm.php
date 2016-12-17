<?php
namespace Model;
use Library\Request;

class ContactForm{
	private $username = '';
	private $email = '';
	private $message = '';

	public function __construct(Request $request){
		$this->username = $request->getP('username');
		$this->email = $request->getP('email');
		$this->message = $request->getP('message');
	}

	public function isValid(){
		return $this->username !== '' &&
				$this->email !== ''&&
				$this->message !== '';
	}
	public function getUsername(){
		return $this->username;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getMessage(){
		return $this->message;
	}
}