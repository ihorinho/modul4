<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\LoginForm;
use Library\Router;
use Library\Password;

class SecurityController extends Controller{
	
	public function loginAction(Request $request){
		$loginForm = new LoginForm($request);
        $session = $this->container->get('session');
		if($request->isPost()){
			if($loginForm->isValid()){
				$password = new Password($loginForm->getPassword());
				$repo = $this->container->get('repository_manager')->getRepository('User');
				if($user = $repo->find($loginForm->getEmail(), $password)){
                    $session->setFlash('Success. You logged in');
					Router::redirect('/mymvc');
				}
                $session->setFlash('User not found!');
				Router::redirect('index.php?route=security/login');
			}
            $session->setFlash('Fill the fileds!');
		}
	return $this->render('login.phtml', $args=['loginForm' => $loginForm]);
	}

	public function logoutAction(Request $request){
        $session = $this->container->get('session');
        $session->remove('user');
		Router::redirect('/mymvc');
	}

	public function registerAction(Request $request){
		//to do
	}
	
}
