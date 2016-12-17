<?php
namespace Model;
use Library\Request;

class LoginForm{
	private $username = '';
	private $password = '';
	private $remember = '';

	public function __construct(Request $request){
		$this->username = $request->getP('username');
		$this->password = $request->getP('password');
		$this->remember = $request->getP('remember');
	}

	public function isValid(){
		return $this->username !== '' &&
				$this->password !== '';
	}
	public function userExists(){
		//code...
		return true;
	}
	public function getUserName(){
		return $this->username;
	}
	public function getPassword(){
		return $this->password;
	}
	public function rememberUser(){
		return $this->remember == 'on';
	}
}