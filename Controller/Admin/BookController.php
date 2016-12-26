<?php
namespace Controller\Admin;
use Library\Controller;
use Library\Request;
use Model\Book;
use Model\Forms\BookAddForm;
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
        $repoBook = $this->container->get('repository_manager')->getRepository('Book');
        $book = $repoBook->getById($id);

        if($request->isPost()){
            $form = new BookEditForm($request);
            if($form->isValid()){
                $editedBook = (new Book())->setTitle($form->getTitle())
                                          ->setDescription($form->getDescription())
                                          ->setId($form->getId())
                                          ->setPrice($form->getPrice())
                                          ->setStyleId($form->getStyleId())
                                          ->setAuthorIds($form->getAuthors())
                                          ->setIsActive($form->getIsActive());
                $repoBook->updateBook($editedBook)
                         ->deleteBookAuthor($editedBook->getId())
                         ->insertBookAuthor($editedBook->getId(), $editedBook->getAuthorIds());
                $session->setFlash('Success');
                $this->redirect('/admin/books/list');
            }
            $session->setFlash('Fill the important fields');
        }

        $repoStyle = $this->container->get('repository_manager')->getRepository('Style');
        $styles = $repoStyle->getAll();
        $repoAuthor = $this->container->get('repository_manager')->getRepository('Author');
        $authors = $repoAuthor->getAllFullNames();

        $args = ['book' => $book, 'authors' => $authors, 'styles' => $styles];
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

    public function addAction(Request $request){
        $this->isAdmin();
        $session = $this->getSession();

        if($request->isPost()){
            $form = new BookAddForm($request);
            if($form->isValid()){
                $newBook = (new Book())->setTitle($form->getTitle())
                                       ->setDescription($form->getDescription())
                                       ->setPrice($form->getPrice())
                                       ->setStyleId($form->getStyleId())
                                       ->setAuthorIds($form->getAuthors())
                                       ->setIsActive($form->getIsActive());
                $repoBook = $this->container->get('repository_manager')->getRepository('Book');
                $repoBook->insertBook($newBook);
                $newBookId = $repoBook->getLastInsertId();
                $repoBook->insertBookAuthor($newBookId,$newBook->getAuthorIds());
                $session->setFlash('Success');
                $this->redirect('/admin/books/list');

            }
            $session->setFlash('Fill the important fields');
        }
        $repoStyle = $this->container->get('repository_manager')->getRepository('Style');
        $styles = $repoStyle->getAll();
        $repoAuthor = $this->container->get('repository_manager')->getRepository('Author');
        $authors = $repoAuthor->getAllFullNames();

        $args = ['authors' => $authors, 'styles' => $styles];
        return $this->render('add_new.phtml', $args);
    }
}
