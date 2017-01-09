<?php
namespace Model\Forms;
use Library\Request;

class ContactForm{
	private $username = '';
	private $email = '';
	private $message = '';
    private $phrase;

	public function __construct(Request $request){
		$this->username = $request->post('username');
		$this->email = $request->post('email');
		$this->message = $request->post('message');
		$this->phrase = $request->post('phrase');
	}

	public function isValid(){
		return $this->username !== '' &&
				$this->email !== ''&&
				$this->message !== '' &&
				$this->phrase !== '';
	}
	public function getUsername(){
		return $this->username;
	}
	public function getEmail(){
		return $this->email;
	}

    /**
     * @return null
     */
    public function getPhrase()
    {
        return $this->phrase;
    }
	public function getMessage(){
		return $this->message;
	}
}