<?php

namespace Library;

abstract class EntityRepository{
	protected $pdo;

	public function setPDO(\PDO $pdo){
		$this->pdo = $pdo;

		return $this;
	}
}