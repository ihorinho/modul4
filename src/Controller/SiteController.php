<?php
namespace Controller;

use Library\Controller;
use Library\Request;
use Model\Forms\ContactForm;
use Model\Feedback;
use Gregwar\Captcha\CaptchaBuilder;

class SiteController extends Controller{

    public function indexAction(Request $request){
        $newsRepo = $this->container->get('repository_manager')->getRepository('News');
        $news = array();
        $tags = preg_split('/[,]{1}[ ]*/', 'фінанси, фінанси і політика, політика, релігія, культура, мистецтво, економіка, наука, живопис,  екологія, психологія, історія, бізнес, гроші, суспільство, війна, кримінал, бандитизм, спорт, футбол, бокс, баскетбол, теніс');
        $categoryRepo = $this->container->get('repository_manager')->getRepository('Category');
        $categories = $categoryRepo->getAll();

        foreach($categories as $category){
            $news[$category['alias']] = $newsRepo->getLastNewsList($category['id']);
        }
        $carouselNews = $newsRepo->getLastNewsList($category = false, $limit = 4);
        $adverRepo = $this->container->get('repository_manager')->getRepository('Adver');
        $advers = $adverRepo->getAdversBoth(self::ADVERS_COUNT);

        return $this->render('index.phtml.twig', array('categories' => $categories, 'news' => $news,
                                    'carouselNews' => $carouselNews, 'advers' => $advers, 'tags' => $tags));
    }
	public function contactAction(Request $request){
        $builder = new CaptchaBuilder;
        $builder->build();
        $session = $request->getSession();
        $phrase = $session->get('captcha');
        $session->set('captcha', $builder->getPhrase());
        $form = new ContactForm($request);
		$repo = $this->container->get('repository_manager')->getRepository('Feedback');
		if($request->isPost()){
			if($form->isValid()){
				if($phrase == $form->getPhrase()){
                    $feedback = (new Feedback())
                        ->setUsername($form->getUsername())
                        ->setEmail($form->getEmail())
                        ->setMessage($form->getMessage())
                        ->setIpAddress($request->getIpAddress());

                    $repo->save($feedback);
                    $session->setFlash('Feedback saved');
                    $this->redirect('contact-us');
                }
                $session->setFlash('Not correct phrase from picture');
                return $this->render('contacts.phtml.twig', ['form' => $form, 'phrase' => $phrase,'builder' => $builder]);
			}
			$session->setFlash('Fill the fields');
		}
		return $this->render('contacts.phtml.twig', ['form' => $form, 'builder' => $builder, 'phrase' => $phrase]);
	}

    public function notFoundAction(){
        return $this->render('404.phtml.twig', array('upload_path' => UPLOAD_PATH));
    }
}