<?php
namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Model\ContactForm;
use Model\Feedback;

class SiteController extends Controller{

    public function indexAction(Request $request){
        $this->isAdmin();
        return $this->render('index.phtml');
    }

    public function contactAction(Request $request){
        $form = new ContactForm($request);
        $session = $this->container->get('session');
        $router = $this->container->get('router');
        $repo = $this->container->get('repository_manager')->getRepository('Feedback');
        if($request->isPost()){
            if($form->isValid()){
                $feedback = (new Feedback())
                    ->setUsername($form->getUsername())
                    ->setEmail($form->getEmail())
                    ->setMessage($form->getMessage())
                    ->setIpAddress($request->getIpAddress());

                $repo->save($feedback);
                $session->setFlash('Feedback saved');
                $router->redirect('contact-us');
            }
            $session->setFlash('Fill the fields');
        }
        return $this->render('contacts.phtml', ['form' => $form]);
    }
}