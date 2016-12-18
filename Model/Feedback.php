<?php

namespace Model;

class Feedback{
	private $id;
	private $username;
	private $email;
	private $message;
	private $ipAddress;
	private $created;


	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;

		return $this;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;

		return $this;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;

		return $this;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($message){
		$this->message = $message;

		return $this;
	}

	public function getIpAddress(){
		return $this->ipAddress;
	}

	public function setIpAddress($IpAddress){
		$this->ipAddress = $IpAddress;

		return $this;
	}

}