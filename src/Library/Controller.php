<?php
namespace Library;

use Library\API\FormatterFactory;

class Controller{
    const PER_PAGE = 5;
    const CUT_CONTENT = 3;
    const ADVERS_COUNT = 4;
    const SITE_CONFIG = 'main.yml';

    protected $container;
    protected static $layout = 'default_layout.phtml';

	protected function render($view, $args = array()){
        extract($args);
        $args['session'] = $this->getSession();
        $config = $this->container->get('config');
        $args['config'] = $config->get('site_config');
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

    protected function isAdmin($authorization = true){
        $session = $this->getSession();
        if(($session->get('admin') !== 1) && $authorization){
            $router = $this->container->get('router');
            $session->setFlash('Restricted Area!!! Must login or to be Administrator')->set('uri', $_SERVER['REQUEST_URI']);
            $this->saveLog('Unauthorized user try to enter admin panel');
            $router->redirect('/login');
        }

        if($session->get('admin') !== 1){
            return false;
        }

        return true;
    }

    public function isLogged($admin = true){
        $session = $this->getSession();
        return $this->isAdmin($admin) or $session->get('user', 0);
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