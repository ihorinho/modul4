<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\LoginForm;

class SecurityController extends Controller{
	
	public function loginAction(Request $request){
		if($request->isPost()){
			$loginForm = new LoginForm($request);
			if($loginForm->isValid()){
				if($loginForm->userExists()){
					echo "Logged in!" . '<br>';
					echo "Redirect to Home page";
					die;
				}
				echo "User not found!!!!";
			}
			echo "Fill all fields!!!";
		}
	return $this->render('login.phtml');
	}
	
}
