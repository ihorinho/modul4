<?php
namespace Model\Forms;
use Library\Request;

class ChangePasswordForm{
	private $old_passw = '';
	private $new_passw = '';
	private $repeated_new_passw = '';

	public function __construct(Request $request){
		$this->old_passw = $request->post('old_passw');
		$this->new_passw = $request->post('new_passw');
		$this->repeated_new_passw = $request->post('repeated_new_passw');
	}

	public function isValid(){
		return $this->old_passw !== '' &&
				$this->new_passw !== '' &&
                $this->repeated_new_passw !== '';
	}

    /**
     * @return null|string
     */
    public function getOldPassw()
    {
        return $this->old_passw;
    }

    /**
     * @return null|string
     */
    public function getRepeatedNewPassw()
    {
        return $this->repeated_new_passw;
    }

    public function matchPasswords(){
        return $this->new_passw === $this->repeated_new_passw;
    }

    /**
     * @return string
     */
    public function getNewPassw()
    {
        return $this->new_passw;
    }
}