<?php
namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Model\ContactForm;
use Model\Feedback;
use \Symfony\Component\Yaml\Yaml;

class SiteController extends Controller{

    public function indexAction(Request $request){
        $this->isAdmin();
        return $this->render('index.phtml.twig');
    }

    public function contactAction(Request $request){
        $form = new ContactForm($request);
        $session = $this->getSession();
        $repo = $this->container->get('repository_manager')->getRepository('Feedback');
        if($request->isPost()){
            if($form->isValid()){
                $feedback = (new Feedback())
                    ->setUsername($form->getUsername())
                    ->setEmail($form->getEmail())
                    ->setMessage($form->getMessage())
                    ->setIpAddress($request->getIpAddress());

                $repo->save($feedback);
                $session->setFlash('Feedback saved');
                $this->saveLog('Feedback saved', [$form->getEmail()]);
                $this->redirect('contact-us');
            }
            $session->setFlash('Fill the fields');
        }
        return $this->render('contacts.phtml.twig', ['form' => $form]);
    }

    public function advertAction(Request $request){
        $adversRepo = $this->container->get('repository_manager')->getRepository('Adver');
        if($request->isPost()){
            $result = $adversRepo->save($request);
            return $result ? 'Success' : 'Fail';
        }
        $advers = $adversRepo->getAll();
        return $this->render('advers_summary.phtml.twig', ['advers' => $advers]);
    }

    public function addAdvertAction(Request $request){
        $adversRepo = $this->container->get('repository_manager')->getRepository('Adver');
        if($request->isPost()){
            $message = $adversRepo->save($request) ? 'Success' : 'Fail';
            $session = $request->getSession();
            $session->setFlash($message);
            $this->redirect('/admin/index');
        }
        return $this->render('add_advert.phtml.twig', ['advers' => $advers]);
    }

    public function manageMenuAction(Request $request){
        $configData = $this->container->get('config');
        $config = $configData->get('site_config');
        if($request->isPost()){
            $configArray = array('site_config' => array(
               'content_background' => $request->post('content_background', '#fff'),
               'header_background' => $request->post('header_background', '#000'),
               'allow_dropdown' => $request->post('allow_dropdown', 0),
            ));
            $yaml = Yaml::dump($configArray);
            if(!$result = file_put_contents(CONFIG_PATH . self::SITE_CONFIG, $yaml)){
                return 'Fail';
            }
            return 'Success';

        }
        return $this->render('manage_menu.phtml.twig', ['config' => $config]);
    }

    public function viewCommentsAction(Request $request){
        $repo = $this->container->get('repository_manager')->getRepository('Comments');
        if($request->isPost()){
            if(!$result = $repo->save($request)){
                return 'Fail';
            }
            return 'Зміни успішно збережені';
        }
        $comments = $repo->getAll();
        $args = array('comments' => $comments);
        return $this->render('comments.phtml.twig', $args);
    }

    public function watingCommentsAction(Request $request){
        $repo = $this->container->get('repository_manager')->getRepository('Comments');
        if($request->isPost()){
            if(!$result = $repo->save($request)){
                return 'Fail';
            }
            return 'Зміни успішно збережені';
        }
        $comments = $repo->getWaitingPolitic();
        $args = array('comments' => $comments);
        return $this->render('waitng_comments.phtml.twig', $args);
    }
}