<?php
namespace Controller;
use Library\Controller;

class SiteController extends Controller{

	public function indexAction(Request $request){
		return $this->render('index.phtml');
	}
	public function contactsAction(Request $request){
		return $this->render('contacts.phtml');
	}
}