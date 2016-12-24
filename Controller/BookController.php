<?php
namespace Controller;
use Library\Controller;
use Library\Pagination\Pagination;
use Library\Request;

class BookController extends Controller{

	public function indexAction(Request $request){

		$repo = $this->container->get('repository_manager')->getRepository('Book');
		$booksCount = $repo->getCount(true);
        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $pagination = new Pagination($currentPage, $booksCount, self::PER_PAGE);
        $buttons = $pagination->getButtons();
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $books = $repo->getAllActive($offset, self::PER_PAGE);
        if(!$books && $booksCount){
            $this->redirect('/books/list/1');
        }
		$args = ['books'=>$books, 'buttons' => $buttons, 'page' => $currentPage];
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


