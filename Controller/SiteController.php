<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\ContactForm;
use Model\Feedback;
use Model\FeedbackMapper;
use Library\Session;
use Library\Router;

class SiteController extends Controller{

	public function indexAction(Request $request){
		return $this->render('index.phtml');
	}
	public function contactsAction(Request $request){
		$form = new ContactForm($request);
		$mapper = new FeedbackMapper();
		if($request->isPost()){
			if($form->isValid()){
				$feedback = (new Feedback())
						->setUsername($form->getUsername())
						->setEmail($form->getEmail())
						->setMessage($form->getMessage())
						->setIpAddress($request->getIpAddress());

				$mapper->save($feedback);
				Session::setFlash('Feedback saved');
				Router::redirect('/mymvc/index.php?route=site/contacts');
			}
			Session::setFlash('Fill the fields');
		}
		return $this->render('contacts.phtml', ['form' => $form]);
	}
}