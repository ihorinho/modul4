<?php
namespace Controller;
use Library\Controller;
use Library\Request;

class BookController extends Controller{

	public function indexAction(){

		$repo = $this->container->get('repository_manager')->getRepository('Book');
		$books = $repo->getAllActive();

		$args = ['books'=>$books];
		return $this->render('index.phtml',$args);
	}
	
	public function showAction(Request $request){
		if(null === ($id = $request->get('id'))){
			return 'Error! Book not found';
		}

		$repo = $this->container->get('repository_manager')->getRepository('Book');
		$book = $repo->getById($id);

		$args = ['book' => $book];
		return $this->render('show.phtml', $args);
	}
}


