<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/23/16
 * Time: 10:23 PM
 */
namespace Controller\Admin;
use Library\Controller;
use Library\Request;
use Model\Forms\ChangePasswordForm;
use Library\Password;

class SecurityController extends Controller{

    public function changeAction(Request $request){

        if(!$this->isAdmin()){
            $this->redirect('/login');
        }
        $form = new ChangePasswordForm($request);
        $session = $this->getSession();
        if($request->isPost()){
            if($form->isValid()){
                $password = new Password($form->getOldPassw());
                $email = $session->get('user');
                $repo = $this->container->get('repository_manager')->getRepository('User');
                if($user = $repo->find($email, $password)){
                    if($form->matchPasswords()){
                        if($repo->save($user->getId(), $form->getNewPassw())){
                            $session->setFlash('Password changed!');
                            $this->redirect('/admin/index');
                        }
                        $session->setFlash('Error! Password not changed!');
                        $this->redirect('/admin/index');
                    }
                    $session->setFlash('Passwords don\'t match!');
                }
                $session->setFlash('Incorrect old password!');
                $this->redirect('/admin/change-pw');
            }
            $session->setFlash('Fill all fields!');
        }

        return $this->render('change_pw.phtml', ['form' => $form]);
    }
}