<?php
namespace Library;

use Library\API\FormatterFactory;

class Controller{
    const PER_PAGE = 5;
	protected $container;
    protected static $layout = 'default_layout.phtml';

	protected function render($view, $args = array()){
        extract($args);
        $args['session'] = $this->getSession();
        $classname = trim(str_replace(['Controller', '\\'], ['', DS], get_class($this)), DS);
		$tpl_name = $classname . DS . $view;
		if(!file_exists(VIEW . $tpl_name)){
			throw new \Exception("Template " . VIEW . $tpl_name . " doesn\'t exist");
		}

        $twig = Registry::get('twig');

        return $twig->render($tpl_name, $args);
	}

	public function renderError($message, $file, $line, $session){
        $twig = Registry::get('twig');

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
        if($session->get('admin') !== 1){
            $router = $this->container->get('router');
            $session->setFlash('Restricted Area!!! Must login or to be Administrator')->set('uri', $_SERVER['REQUEST_URI']);
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

    public function getOutputFormatter(Request $request){
        $default_format = $this->container->get('config')->get('default_api_format');
        $format = $request->get('format', $default_format);

        return FormatterFactory::create($format);
    }
}