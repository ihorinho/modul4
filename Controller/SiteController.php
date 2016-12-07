<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\ContactForm;

class SiteController extends Controller{

	public function indexAction(Request $request){
		return $this->render('index.phtml');
	}
	public function contactsAction(Request $request){
		$form = new ContactForm($request);
		if($request->isPost()){
			if(!$form->isValid()){
				echo "Fill all fields!!!";
				return $this->render('contacts.phtml', ['form' => $form]); die;
			}
			echo "All Right!!";
		}
		return $this->render('contacts.phtml', ['form' => $form]);
	}
}