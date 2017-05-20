<?php
namespace Controller;

use Library\Controller;
use Library\Request;
use Model\Forms\ContactForm;
use Model\Feedback;
use Gregwar\Captcha\CaptchaBuilder;

class SiteController extends Controller{
    const TOP_NEWS_COUNT = 3;

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
        $userRepo = $this->container->get('repository_manager')->getRepository('User');
        $activeUsers = $userRepo->getActiveCommentators();
        $topNews = $newsRepo->getTop(self::TOP_NEWS_COUNT);
        return $this->render('index.phtml.twig', array('categories' => $categories, 'news' => $news,
                                    'carouselNews' => $carouselNews, 'advers' => $advers, 'tags' => $tags,
                                    'active_users' => $activeUsers, 'topNews' => $topNews));
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

	public function addCommentsAction(Request $request){
	    if($request->isPost()){
            $repo = $this->container->get('repository_manager')->getRepository('Comments');
            if(!$result = $repo->add($request)){
                return 'Fail';
            }
            return 'Дякуємо за коментар!';
        }
    }

    public function deleteCommentsAction(Request $request){
        $repo = $this->container->get('repository_manager')->getRepository('Comments');
        if($commentId = $request->get('id')){
            $repo->delete($commentId);
        }

        echo "delete";
    }

    public function updateRatingAction(Request $request){
        if( (!$id = $request->get('id', 0))
            || (!$newId = $request->get('new_id', 0))){
            return ['status' => 'fail', 'message' => "commentId={$id} newId={$newId}"];
        }

        $rating = (int)$request->get('rating',0);
        $repo = $this->container->get('repository_manager')->getRepository('Comments');
        $repo->updateRating($id, $rating);
        return $this->getCommentsByNewId($newId);

//        return $repo->updateRating($id, $rating) ? 'Success' : 'Fail';
    }

    public function notFoundAction(){
        return $this->render('404.phtml.twig', array('upload_path' => UPLOAD_PATH));
    }

    public function getCommentsByNewId($id) {
        try {
            if (!$id) {
                return json_encode(
                    [
                        'status' => 'fail',
                        'message' => "No id received - {$id}"
                    ]
                );
            }
            header('Content-Type: application/json');
            $response = [];
            $response['status'] = 'success';
            $commentRepo  = $this->container->get('repository_manager')->getRepository('Comments');
            if (!$comments = $commentRepo->getAllByNewId($id)) {
                $response['message'] = 'There are no comments yet';
            } else {
                $commentsHtml = '<div id="comments-block" style="display:none;">';
                foreach ($comments as $comment) {
                    $commentsHtml .= '<div class="comment-container">
                        <div class="comment">' . $comment["message"] . '</div>
                        <p>
                            дата: <em class="comment-date">' . $comment["date"] . '</em> від: <em class="comment-user">' . $comment["user"] . '</em>
                            сподобалось: <button class="btn-danger change-rating" onclick="' . "SiteProcessor.changeRating(this, '-')" . '">-</button> <span class="comment-rating">' . $comment["rating"] . '</span>
                            <button class="btn-info change-rating" onclick="' . "SiteProcessor.changeRating(this, '+')" . '">+</button><span class="comment-id">' . $comment["id"] . '</span>
                        </p>
                    </div>';
                }
                $response['html'] = $commentsHtml;
            }
            return json_encode($response);
        } catch(\Exception $e) {
            return json_encode(
                [
                    'status' => 'fail',
                    'message' => $e->getMessage()
                ]
            );
        }
    }
}