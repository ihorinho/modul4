<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\LoginForm;
use Library\Password;

class SecurityController extends Controller{
	
	public function loginAction(Request $request){
		$loginForm = new LoginForm($request);
        $router = $this->container->get('router');
        $session = $this->container->get('session');
		if($request->isPost()){
			if($loginForm->isValid()){
				$password = new Password($loginForm->getPassword());
				$repo = $this->container->get('repository_manager')->getRepository('User');
				if($user = $repo->find($loginForm->getEmail(), $password)){
                    $session->setFlash('Success. You logged in');
                    $router->redirect('/admin/index');
				}
                $session->setFlash('User not found!');
                $router->redirect('/login');
			}
            $session->setFlash('Fill the fileds!');
		}
	return $this->render('login.phtml', $args=['loginForm' => $loginForm]);
	}

	public function logoutAction(Request $request){
        $router = $this->container->get('router');
        $session = $this->container->get('session');
        $session->remove('user');
        $router->redirect('/home');
	}

	public function registerAction(Request $request){
		//to do
	}
	
}
