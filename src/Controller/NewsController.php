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
        $newsCount = $newsRepo->getCountCategory($category);
        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $pagination = new Pagination($currentPage, $newsCount, self::PER_PAGE);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $news = $newsRepo->getAllCategory($category, $offset, self::PER_PAGE);

        if(!$news && $newsCount){
            $url = "/news/category/{$category}/page/1";
            $this->redirect($url);
        }
        $args = ['category_alias' => $category, 'categoryName' => $news[0]['category'],
                'news'=>$news, 'pagination' => $pagination, 'page' => $currentPage];
        return $this->render('category.phtml.twig',$args);
    }

    public function showAction(Request $request){
        if(null === ($id = $request->get('id'))){
            return 'Error! New not found';
        }

        $newRepo = $this->container->get('repository_manager')->getRepository('News');
        $new = $newRepo->getById($id);

        $args = ['new' => $new];
        return $this->render('show.phtml.twig', $args);
    }

    public function showTagAction(Request $request){

        $tag = $request->get('tag');
        $currentPage = $request->get('page') > 1 ? $request->get('page') : 1;
        $newsRepo = $this->container->get('repository_manager')->getRepository('News');
        $newsCount = $newsRepo->getCountTag($tag);
        $pagination = new Pagination($currentPage, $newsCount, self::PER_PAGE);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $news = $newsRepo->getByTag($tag, $offset, self::PER_PAGE);

        $args = ['news' => $news, 'tag' => $tag, 'pagination' => $pagination, 'page' => $currentPage];
        return $this->render('show_by_tag.phtml.twig', $args);
    }
}

