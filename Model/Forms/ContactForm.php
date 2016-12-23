<?php
namespace Model\Forms;
use Library\Request;

class ContactForm{
	private $username = '';
	private $email = '';
	private $message = '';

	public function __construct(Request $request){
		$this->username = $request->post('username');
		$this->email = $request->post('email');
		$this->message = $request->post('message');
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