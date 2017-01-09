<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\Forms\LoginForm;
use Model\Forms\RegisterForm;
use Library\Password;
use Gregwar\Captcha\CaptchaBuilder;

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
        $builder = new CaptchaBuilder;
        $builder->build();
        $session = $this->getSession();
        $phrase = $session->get('captcha');
        $session->set('captcha', $builder->getPhrase());
        $registerForm = new RegisterForm($request);
        $args = array('registerForm' => $registerForm, 'builder' => $builder);
        if($request->isPost()){
            if($registerForm->isValid()){
               if($registerForm->passwordsMatch()){
                   if($phrase == $registerForm->getPhrase()){
                       $password = new Password($registerForm->getPassword());
                       $repo = $this->container->get('repository_manager')->getRepository('User');
                       if($repo->userExists($registerForm->getEmail())){
                           $session->setFlash('User ' . $registerForm->getEmail() . ' already exists!');
                           return $this->render('register.phtml.twig', $args);
                       }
                       if(!$repo->addNew($registerForm->getEmail(), $password)){
                           throw new \Exception('Error, new user not created');
                       }
                       $this->saveLog('Success, user created!' ,[$registerForm->getEmail()]);
                       $session->setFlash('Success, user created!');
                       $this->redirect('/home');
                   }
                   $session->setFlash('Not correct phrase from picture');
                   return $this->render('register.phtml.twig', $args);
               }
                $session->setFlash('Passwords not match');
                return $this->render('register.phtml.twig', $args);
            }
            $session->setFlash('Fill the fileds!');
        }
        return $this->render('register.phtml.twig', $args);
	}
}
