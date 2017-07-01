<?php

namespace Model;

class User{

    private $id;

	private $email;

	private $password;

	private $admin;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email){
		$this->email = $email;

		return $this;
	}

	public function getEmail(){

		return $this->email;
	}

	public function setPassword($password){
		$this->password = $password;

		return $this;
	}

	public function getPassword(){

		return $this->password;
	}

	public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    public function isAdmin()
    {
        return $this->admin === 777 ? true : false;
    }
}