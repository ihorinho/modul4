<?php
namespace Controller;
use Library\Controller;
class BookController extends Controller{

	public function indexAction(){

		$books = ['book1', 'book2'];
		$author = 'Max Kolomax';
		$args = ['books'=>$books, 'author' => $author];
		return $this->render('index.phtml',$args);
	}
	public function showAction(){
		return 'books/show page';
	}
}


