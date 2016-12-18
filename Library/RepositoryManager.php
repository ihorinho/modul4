<?php

namespace Library;

class RepositoryManager{

	private $repositories = array();
	private $pdo;

	public function setPDO(\PDO $pdo){
		$this->pdo = $pdo;

		return $this;
	}

	public function getRepository($entity){
		if(empty($this->repositories[$entity])){
			$repository = "\\Model\\Repository\\{$entity}Repository";

			$repository = new $repository();
			$repository->setPDO($this->pdo);
			$this->repositories[$entity] = $repository;
		}

		return $this->repositories[$entity];
	}
}