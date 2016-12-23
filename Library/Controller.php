<?php
namespace Library;

class Controller{
	protected $container;
    protected static $layout = 'default_layout.phtml';

	protected function render($view, $args = array()){
        extract($args);
        $session = $this->getSession();
        $classname = trim(str_replace(['Controller', '\\'], ['', DS], get_class($this)), DS);
		$file = VIEW . $classname . DS . $view;
		if(!file_exists($file)){
			throw new \Exception("Template {$file} doesn\'t exist");
		}

		ob_start();
		require $file;
		$content = ob_get_clean();

		ob_start();
		require VIEW . DS .  self::$layout;
		return ob_get_clean();
	}

	public static function renderError($message, $code = null){
		ob_start();
		require VIEW . 'error.phtml';
		$content = ob_get_clean();

		ob_start();
		require VIEW . self::$layout;
		return ob_get_clean();
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
            $router->redirect('/login');
        }

        return true;
    }

    public function getSession(){
        return $this->container->get('request')->getSession();
    }
}