<?php
namespace Controller;

use Library\Controller;
use Library\Request;
use Model\Forms\ContactForm;
use Model\Feedback;

class SiteController extends Controller{

    public function indexAction(Request $request){
        return $this->render('index.phtml.twig');
    }
	public function contactAction(Request $request){
		$form = new ContactForm($request);
        $session = $request->getSession();
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
				$this->redirect('contact-us');
			}
			$session->setFlash('Fill the fields');
		}
		return $this->render('contacts.phtml.twig', ['form' => $form]);
	}
}