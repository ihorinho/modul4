<?php

namespace Model;
use Library\DbConnection;
use Model\Style;

class StyleRepository{

	public function getById($id){
		$pdo = DbConnection::getInstance()->getPDO();
		$sql = "SELECT * FROM style WHERE id = :id";
		$sth = $pdo->prepare($sql);
		$sth->execute(array('id' => $id));
		$row = $sth->fetch(\PDO::FETCH_ASSOC);

		$style = (new Style())
				->setId($row['id'])
				->setName($row['name']);
				
		return $style;
	}
}