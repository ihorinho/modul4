<?php
namespace Model;
use Library\Request;

class LoginForm{
	private $email = '';
	private $password = '';
	private $remember = '';

	public function __construct(Request $request){
		$this->email = $request->getP('email');
		$this->password = $request->getP('password');
		$this->remember = $request->getP('remember');
	}

	public function isValid(){
		return $this->email !== '' &&
				$this->password !== '';
	}
	
	public function getEmail(){
		return $this->email;
	}

	public function getPassword(){
		return $this->password;
	}

	public function rememberUser(){
		return $this->remember == 'on';
	}
}