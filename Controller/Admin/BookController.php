<?php
namespace Controller\Admin;
use Library\Controller;
use Library\Request;
use Model\Book;
use Model\Forms\BookEditForm;

class BookController extends Controller{

    public function indexAction(){

        $this->isAdmin();
        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $books = $repo->getAllActive();

        $args = ['books'=>$books];
        return $this->render('index.phtml',$args);
    }

    public function editAction(Request $request){
        $this->isAdmin();
        if(null === ($id = $request->get('id'))){
            return 'Error! Book not found';
        }
        $session = $this->getSession();
        $router = $this->container->get('router');
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
                $router->redirect('/admin/books/list');

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
        $router = $this->container->get('router')->redirect('/admin/books/list');

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
