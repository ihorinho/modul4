<?php
namespace Model\Forms;
use Library\Request;

class RegisterForm{
	private $email = '';
	private $password = '';
    private $repeatedPassword = '';
	private $remember = '';
    private $phrase;

	public function __construct(Request $request){
		$this->email = $request->post('email');
		$this->password = $request->post('password');
		$this->remember = $request->post('remember');
        $this->repeatedPassword = $request->post('repeatedPassword');
        $this->phrase = $request->post('phrase');
    }

	public function isValid(){
		return $this->email !== '' &&
				$this->password !== '' &&
				$this->repeatedPassword !== '' &&
                $this->phrase !== '';;
	}

    /**
     * @return null|string
     */
    public function getRepeatedPassword()
    {
        return $this->repeatedPassword;
    }
	
	public function getEmail(){
		return $this->email;
	}

	public function getPassword(){
		return $this->password;
	}

    public function getPhrase()
    {
        return $this->phrase;
    }

	public function rememberUser(){
		return $this->remember == 'on';
	}

    public function passwordsMatch(){
        return $this->password === $this->repeatedPassword;
    }
}