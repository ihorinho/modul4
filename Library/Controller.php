<?php
namespace Library;

class Controller{
	protected $container;

	protected function render($view, $args = array()){
		extract($args);
		$file = VIEW . str_replace(['Controller', '\\'], '', get_class($this)) . DS . $view;
		if(!file_exists($file)){
			throw new \Exception('Template doesn\'t exist');
		}
		ob_start();
		require $file;
		return ob_get_clean();
	}

	public static function renderError($message, $code = null){
		ob_start();
		require VIEW . 'error.phtml';
		return ob_get_clean();
	}


	public function setContainer($container){
		$this->container = $container;

		return $this;
	}
}