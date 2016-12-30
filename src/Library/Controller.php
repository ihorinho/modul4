<?php
namespace Library;

class Controller{
    const PER_PAGE = 12;
	protected $container;
    protected static $layout = 'default_layout.phtml';

	protected function render($view, $args = array()){
        extract($args);
        $args['session'] = $this->getSession();
        $classname = trim(str_replace(['Controller', '\\'], ['', DS], get_class($this)), DS);
		$tpl_name = $classname . DS . $view;
//		if(!file_exists($file)){
//			throw new \Exception("Template {$file} doesn\'t exist");
//		}

        $loader = new \Twig_Loader_Filesystem(VIEW);
        $twig = new \Twig_Environment($loader, array(
            'cache' => false
        ));
        $twigInArray = new \Twig_SimpleFunction('in_array', function ($needle, $haystack) {
            return in_array($needle, $haystack);
        });
        $twig->addFunction($twigInArray);

        return $twig->render($tpl_name, $args);
	}

	public function renderError($message, $file, $line, $session){
        $loader = new \Twig_Loader_Filesystem(VIEW);
        $twig = new \Twig_Environment($loader, array(
            'cache' => false
        ));

        return $twig->render('error.phtml.twig', array('message' => $message, 'file' => $file,
                                                        'line' => $line, 'session' => $session));
	}


	public function setContainer($container){
		$this->container = $container;

		return $this;
	}

    public static function setLayout($layout){
        self::$layout = $layout;
    }

    protected function isAdmin(){
        $session = $this->getSession();
        if(!$session->has('user')){
            $router = $this->container->get('router');
            $session->setFlash('Restricted Area!!! Must login')->set('uri', $_SERVER['REQUEST_URI']);
            $this->saveLog('Unauthorized user try to enter admin panel');
            $router->redirect('/login');
        }

        return true;
    }

    public function getSession(){
        return $this->container->get('request')->getSession();
    }

    public function saveLog($message, $args1=array(0), $args2=array()){
        $this->container->get('logger')->addInfo($message, $args1, $args2);
    }

    public function redirect($to){
        $router = $this->container->get('router');
        $router->redirect($to);
    }
}