<?php
namespace Controller\Admin;
use Library\Controller;
use Library\Request;
use Model\Book;
use Model\Forms\BookAddForm;
use Model\Forms\BookEditForm;
use Library\Pagination\Pagination;
use Model\UploadedFile;

class BookController extends Controller{
    const BOOK_COVER_FILE = 'book_cover';

    public function indexAction(Request $request){

        $this->isAdmin();
        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $booksCount = $repo->getCount(true);
        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $pagination = new Pagination($currentPage, $booksCount, self::PER_PAGE);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $books = $repo->getAllActive($offset, self::PER_PAGE);
        if(!$books && $booksCount){
            $this->redirect('/books/list/1');
        }
        $args = ['books'=>$books, 'pagination' => $pagination, 'page' => $currentPage];
        return $this->render('index.phtml.twig',$args);
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
                $bookCover = new UploadedFile(self::BOOK_COVER_FILE);
                if($bookCover->isJPG()){
                    $bookCover->moveToUploads($form->getId());
                }
                $session->setFlash('Success');
                $this->saveLog('Book id: ' . $form->getId() .  ' changed', [$session->get('user')]);
                $this->redirect('/admin/books/list');
            }
            $session->setFlash('Fill the important fields');
        }

        $repoStyle = $this->container->get('repository_manager')->getRepository('Style');
        $styles = $repoStyle->getAll();
        $repoAuthor = $this->container->get('repository_manager')->getRepository('Author');
        $authors = $repoAuthor->getAllFullNames();

        $args = ['book' => $book, 'authors' => $authors, 'styles' => $styles];
        return $this->render('edit.phtml.twig', $args);
    }

    public function deleteAction(Request $request){
        $this->isAdmin();
        if(null === ($id = $request->get('id'))){
            return 'Error! Book not found';
        }

        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $repo->deleteById($id);
        $this->saveLog('Book with id: ' . $id . ' deleted');
        $this->redirect('/admin/books/list');

    }

    public function showAction(Request $request){
        if(null === ($id = $request->get('id'))){
            return 'Error! Book not found';
        }

        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $book = $repo->getById($id);

        $args = ['book' => $book];
        return $this->render('show.phtml.twig', $args);
    }

    public function addAction(Request $request){
        $this->isAdmin();
        $session = $this->getSession();

        if($request->isPost()){
            $form = new BookAddForm($request);
            $bookCover = new UploadedFile(self::BOOK_COVER_FILE);
            if($bookCover->getError()){
                //todo: ...
                throw new \Exception('Error during uploading picture');
            }
            if($form->isValid() && $bookCover->isJPG()){
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
                $bookCover->moveToUploads($newBookId);
                $session->setFlash('Success');
                $this->saveLog('New book added', [$form->getTitle()], [$session->get('user')]);
                $this->redirect('/admin/books/list');

            }
            $session->setFlash('Fill the important fields');
        }
        $repoStyle = $this->container->get('repository_manager')->getRepository('Style');
        $styles = $repoStyle->getAll();
        $repoAuthor = $this->container->get('repository_manager')->getRepository('Author');
        $authors = $repoAuthor->getAllFullNames();

        $args = ['authors' => $authors, 'styles' => $styles];
        return $this->render('add_new.phtml.twig', $args);
    }
}
