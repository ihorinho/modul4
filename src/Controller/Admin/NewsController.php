<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 2/11/17
 * Time: 4:47 PM
 */

namespace Controller\Admin;
use Library\Controller;
use Library\Request;
use Model\Forms\NewsAddForm;
use Model\News;
use Model\Forms\NewsEditForm;
use Library\Pagination\Pagination;
use Model\UploadedFile;


class NewsController extends Controller{

    const BOOK_COVER_FILE = 'book_cover';

    public function indexAction(Request $request){
        $this->isAdmin();
        $session = $request->getSession();
        $session->set('uri', $_SERVER['REQUEST_URI']);
        $repo = $this->container->get('repository_manager')->getRepository('News');
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

    public function summaryAction(Request $request){

        $this->isAdmin();
        $session = $request->getSession();
        $session->set('uri', $_SERVER['REQUEST_URI']);
        $repo = $this->container->get('repository_manager')->getRepository('News');
        $news = $repo->getAllNews();

        $args = ['news'=>$news];
        return $this->render('summary_table.phtml.twig',$args);
    }

    public function addNewsAction(Request $request){
        $this->isAdmin();
        $session = $this->getSession();

        if($request->isPost()){
            $form = new NewsAddForm($request);
            $newsCover = new UploadedFile(self::BOOK_COVER_FILE);
            if($newsCover->getError()){
                //todo: ...
                throw new \Exception('Error during uploading picture');
            }
            if($form->isValid() && $newsCover->isJPG()){
                $newNews = (new News())->setTitle($form->getTitle())
                    ->setContent($form->getContent())
                    ->setCategoryId($form->getCategory())
                    ->setAnalitic($form->getAnalitic())
                    ->setTag($form->getTag())
                    ->setPublished($form->getPublished());

                $repoNews = $this->container->get('repository_manager')->getRepository('News');
                $repoNews->add($newNews);
                $newNewsId = $repoNews->getLastInsertId();
                $newsCover->moveToUploads($newNewsId);
                $session->setFlash('Новина успішно додана');
                $this->saveLog('New is added', [$form->getTitle()], [$session->get('user')]);
                $this->redirect('/admin/news/summary');
            }
            $session->setFlash('Заповніть всі поля');
        }
        $categoryRepo = $this->container->get('repository_manager')->getRepository('Category');
        $categories = $categoryRepo->getAll();
        return $this->render('add_new.phtml.twig', array('categories' => $categories));
    }

    public function editNewsAction(Request $request){
        $this->isAdmin();
        if(null === ($id = $request->get('id'))){
            return 'Новина не знайдена!';
        }
        $session = $this->getSession();
        $newsRepo = $this->container->get('repository_manager')->getRepository('News');
        $new = $newsRepo->getById($id);

        if($request->isPost()){
            $form = new NewsEditForm($request);
            if($form->isValid()){
                $editedNews = (new News())->setId($id)
                    ->setTitle($form->getTitle())
                    ->setContent($form->getContent())
                    ->setCategoryId($form->getCategory())
                    ->setAnalitic($form->getAnalitic())
                    ->setTag($form->getTag());
                $newsRepo->updateNews($editedNews);
                if($bookCover = new UploadedFile(self::BOOK_COVER_FILE, $important = false)){
                    if($bookCover->isJPG()){
                        $bookCover->moveToUploads($form->getId());
                    }
                }
                $session->setFlash('Зміни успішно збережені');
                $this->saveLog('News id: ' . $form->getId() .  ' changed', [$session->get('user')]);
                $this->redirect('/admin/news/summary');
            }
            $session->setFlash('Заповніть всі необхідні поля');
        }

        $categoryRepo = $this->container->get('repository_manager')->getRepository('Category');
        $categories = $categoryRepo->getAll();

        $args = ['new' => $new, 'categories' => $categories];
        return $this->render('edit_news.phtml.twig', $args);
    }

    public function deleteAction(Request $request){
        $this->isAdmin();
        if(null === ($id = $request->get('id'))){
            return 'Error! New not found';
        }
        $repo = $this->container->get('repository_manager')->getRepository('News');
        $repo->deleteById($id);
        $this->saveLog('New with id: ' . $id . ' deleted');

        $this->redirect('/admin/news/summary');
    }

    public function viewCategoriesAction(Request $request){

        $categoryRepo = $this->container->get('repository_manager')->getRepository('Category');
        $categories = $categoryRepo->getAll();

        return $this->render('categories.phtml.twig', array('categories' => $categories));
    }

    public function addCategoryAction(Request $request){

        if($request->isPost()){
            $session = $request->getSession();
            $categoryRepo = $this->container->get('repository_manager')->getRepository('Category');
            $categoryName = $request->post('name');
            $categoryRepo->add($categoryName, $request->post('alias'));
            $session->setFlash('Додано нову категорію: ' . $categoryName);
            $this->redirect('/admin/index');
        }

        return $this->render('add_category.phtml.twig');
    }

} 