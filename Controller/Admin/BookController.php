<?php
namespace Controller\Admin;
use Library\Controller;
use Library\Request;
use Model\Book;
use Model\Forms\BookEditForm;
use Library\Pagination\Pagination;

class BookController extends Controller{

    public function indexAction(Request $request){

        $this->isAdmin();
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

    public function editAction(Request $request){
        $this->isAdmin();
        if(null === ($id = $request->get('id'))){
            return 'Error! Book not found';
        }
        $session = $this->getSession();
        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $book = $repo->getById($id);

        if($request->isPost()){
            $form = new BookEditForm($request);
            if($form->isValid()){
                $editedBook = (new Book())->setTitle($form->getTitle())
                                          ->setDescription($form->getDescription())
                                          ->setId($form->getId())
                                          ->setPrice($form->getPrice())
                                          ->setIsActive($form->getIsActive());
                $repo->save($editedBook);
                $session->setFlash('Success');
                $this->redirect('/admin/books/list');

            }
            $session->setFlash('Fill the important fields');
        }

        $args = ['book' => $book];
        return $this->render('edit.phtml', $args);
    }

    public function deleteAction(Request $request){
        $this->isAdmin();
        if(null === ($id = $request->get('id'))){
            return 'Error! Book not found';
        }

        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $repo->deleteById($id);
        $this->redirect('/admin/books/list');

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
