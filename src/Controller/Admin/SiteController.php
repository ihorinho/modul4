<?php
namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Model\ContactForm;
use Model\Feedback;

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
}