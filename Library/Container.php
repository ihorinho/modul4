<?php

namespace Library;

class Container{

	private $services = array();

	public function get($key){
		if(!isset($this->services[$key]))
			throw new \Exception("Entity {$key} not found");
		return $this->services[$key];
	}

	public function set($key, $entity){
		$this->services[$key] = $entity;

		return $this;
	}
}