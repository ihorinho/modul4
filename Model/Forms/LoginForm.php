<?php
namespace Model\Forms;
use Library\Request;

class LoginForm{
	private $email = '';
	private $password = '';
	private $remember = '';

	public function __construct(Request $request){
		$this->email = $request->post('email');
		$this->password = $request->post('password');
		$this->remember = $request->post('remember');
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