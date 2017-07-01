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
				if($user = $repo->find($loginForm->getEmail(), $password, $is_active = 1)){
                    if($user->isAdmin()){
                        $session->set('admin', 1)
                                ->set('user', $user->getEmail())
                                ->setFlash('Hello, admin. You logged in');
                        $this->saveLog('Success logging' , [$user->getEmail()]);
                        $redirect = $session->has('uri') ? $session->get('uri') : '/admin/index';
                        $this->redirect($redirect);
                    }
                    $session->set('user', $user->getEmail())
                          ->setFlash('Success. You logged in');
                    $this->saveLog('Success logging' , [$user->getEmail()]);
                    $this->redirect('/');
				}
                $session->setFlash('User not found!');
                $this->saveLog('User not found', [$loginForm->getEmail()]);
                $this->redirect('/login');
			}
            $session->setFlash('Fill the fileds!');
		}
        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);
	    return $this->render('login.phtml.twig', $args=['loginForm' => $loginForm, 'advers' => $advers]);
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
        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);
        $args = array('registerForm' => $registerForm, 'builder' => $builder, 'advers' => $advers);
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
                       $confirmationCode = md5(md5(uniqid()));
                       if(!$repo->addNew($registerForm->getEmail(), $password, $confirmationCode)){
                           throw new \Exception('Error, new user not created');
                       }
                       $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                           ->setUsername('ihorinho')
                           ->setPassword('igor1990')
                       ;
                       // Create the Mailer using your created Transport
                       $mailer = \Swift_Mailer::newInstance($transport);

                        // Create a message
                       $message = \Swift_Message::newInstance('ihorinho.ho.ua registration confirmation')
                           ->setFrom(array('ihorinho@gmail.com' => 'Igor Pelekhatyy'))
                           ->setTo(array($registerForm->getEmail()))
                           ->addPart('<p>Hello!</p><p>To confirm your registration on ihorinho.ho.ua, please click this link:</p>
                               <p>http://mymvc/confirm?user=' . $registerForm->getEmail() . '&code=' .$confirmationCode . '</p>', 'text/html')
                       ;

                        // Send the message
                       $result = $mailer->send($message);
                       $this->saveLog('Success, user created!' ,[$registerForm->getEmail()]);
                       $session->setFlash('Success, user ' . $registerForm->getEmail() . ' added! To active user, confirm your email');
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

    public function confirmAction(Request $request){
        $session = $this->getSession();
        $user = $request->get('user', null);
        $code = $request->get('code', null);
        if($user === null or
            $code === null){
            $session->setFlash('Bad request for registration confirmation');
            $this->redirect('/');
        }
        $repo = $this->container->get('repository_manager')->getRepository('User');

        if($repo->userExists($user)){
            if($repo->activate($user,$code)){
                $session->setFlash('Success, user ' . $user . ' activated!');
                $this->redirect('/');
            }
            $session->setFlash('Bad request for registration confirmation of' . $user);
            $this->redirect('/');
        }
        $session->setFlash($user . ' not exists!');
        $this->redirect('/');
    }
}
