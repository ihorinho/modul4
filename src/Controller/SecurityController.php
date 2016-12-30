<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\Forms\LoginForm;
use Library\Password;

class SecurityController extends Controller{
	
	public function loginAction(Request $request){
		$loginForm = new LoginForm($request);
        $session = $this->getSession();
		if($request->isPost()){
			if($loginForm->isValid()){
				$password = new Password($loginForm->getPassword());
				$repo = $this->container->get('repository_manager')->getRepository('User');
				if($user = $repo->find($loginForm->getEmail(), $password)){
                    $session->set('user', $user->getEmail())
                            ->setFlash('Success. You logged in');
                    $this->saveLog('Success logging' , [$user->getEmail()]);
                    $redirect = $session->has('uri') ? $session->get('uri') : '/admin/index';
                    $this->redirect($redirect);
				}
                $session->setFlash('User not found!');
                $this->saveLog('User not found', [$loginForm->getEmail()]);
                $this->redirect('/login');
			}
            $session->setFlash('Fill the fileds!');
		}
	return $this->render('login.phtml.twig', $args=['loginForm' => $loginForm]);
	}

	public function logoutAction(Request $request){
        $session = $this->getSession();
        $session->clear();
        $session->destroy();
        $this->redirect('/home');
	}

	public function registerAction(Request $request){
		//to do
	}
}
