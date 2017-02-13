<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 2/9/17
 * Time: 9:08 PM
 */

namespace Controller;
use Library\Controller;
use Library\Pagination\Pagination;
use Library\Request;

class NewsController extends Controller{

    public function categoryAction(Request $request){

        $category = $request->get('category');
        $newsRepo = $this->container->get('repository_manager')->getRepository('News');
        if(!$newsCount = $newsRepo->getCountCategory($category)){
            $session = $this->getSession();
            $session->setFlash('Немає новин в  цій категорії');
            $this->redirect('/');
        }

        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $pagination = new Pagination($currentPage, $newsCount, self::PER_PAGE);
        $offset = ($currentPage - 1) * self::PER_PAGE;


        $news = $newsRepo->getAllCategory($category, $offset, self::PER_PAGE);
        if(!$news && $newsCount){
            $url = "/news/category/{$category}/page/1";
            $this->redirect($url);
        }
        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);

        $args = ['categoryName' => $news[0]['category'], 'category' => $category,
                'news'=>$news, 'pagination' => $pagination, 'page' => $currentPage, 'advers' => $advers];
        return $this->render('category.phtml.twig',$args);
    }

    public function showAction(Request $request){
        if(null === ($id = $request->get('id'))){
            return 'Error! New not found';
        }

        $newRepo = $this->container->get('repository_manager')->getRepository('News');
        if(!$new = $newRepo->getById($id)){
            $session = $this->getSession();
            $session->setFlash('Новина недоступна');
            $this->redirect('/');
        }

        if($new['analitic'] == 1 && (!$this->isLogged($admin = false))){
            $new['content'] = $newRepo->cutContent($new['content'], self::CUT_CONTENT);
        }
        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);

        $args = ['new' => $new, 'categoryName' => $new['category'], 'advers' => $advers];
        return $this->render('show.phtml.twig', $args);
    }

    public function analiticAction(Request $request){

        $newsRepo = $this->container->get('repository_manager')->getRepository('News');
        if(!$newsCount = $newsRepo->getCountCategory('analitic')){
            $session = $this->getSession();
            $session->setFlash('Немає новин в категорії аналітика');
            $this->redirect('/');
        }

        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $pagination = new Pagination($currentPage, $newsCount, self::PER_PAGE);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $news = $newsRepo->getAllCategory('analitic', $offset, self::PER_PAGE);

        if(!$news && $newsCount){
            $url = "/news/category/{$category}/page/1";
            $this->redirect($url);
        }

        if(!$this->isLogged($admin = false)){
            foreach($news as $new){
                $new['content'] = $newsRepo->cutContent($new['content'], self::CUT_CONTENT);
            }
        }

        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);
        $args = ['categoryName' => 'Аналітика',
            'news'=>$news, 'pagination' => $pagination, 'page' => $currentPage, 'advers' => $advers];
        return $this->render('analitic.phtml.twig', $args);
    }

    public function showTagAction(Request $request){

        $tag = $request->get('tag');
        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $newsRepo = $this->container->get('repository_manager')->getRepository('News');
        $newsCount = $newsRepo->getCountTag($tag);
        $pagination = new Pagination($currentPage, $newsCount, self::PER_PAGE);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $news = $newsRepo->getByTag($tag, $offset, self::PER_PAGE);

        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);
        $args = ['news' => $news, 'tag' => $tag, 'pagination' => $pagination, 'page' => $currentPage, 'advers' => $advers];
        return $this->render('show_by_tag.phtml.twig', $args);
    }

    public function filtersAction(Request $request){
        $session = $this->getSession();
        $fromDate = $request->get('from_date', 0);
        $toDate = $request->get('to_date', 0);
        $category = $request->get('category', 0);
        $tag = $request->get('tag', 0);

        if(($fromDate && $toDate) && (strtotime($toDate) - strtotime($fromDate)) < 0){
            $session->setFlash('Вибрано неправильний часовий інтервал!');
            $this->redirect('/');
        }
        if(!($fromDate or $toDate or $category or $tag)){
            $this->redirect('/');
        }
        $newsRepo = $this->container->get('repository_manager')->getRepository('News');
        $filtersString = $newsRepo->getFilterQueryString($fromDate, $toDate, $category, $tag);
        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $newsCount = $newsRepo->getCountByFilters($filtersString);
        $pagination = new Pagination($currentPage, $newsCount, self::PER_PAGE);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $news = $newsRepo->getByFilters($filtersString, $offset, self::PER_PAGE);
        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);
        $query = $request->getQueryString();
        $args = array('news' => $news, 'pagination' => $pagination, 'advers' => $advers, 'query' =>$query, 'page' => $currentPage);
        return $this->render('show_by_filters.phtml.twig', $args);
    }


}

