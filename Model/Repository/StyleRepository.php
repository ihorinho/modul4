<?php

namespace Model\Repository;
use Model\Style;
use Library\EntityRepository;

class StyleRepository extends EntityRepository{

	public function getById($id){

		$sql = "SELECT * FROM style WHERE id = :id";
		$sth = $this->pdo->prepare($sql);
		$sth->execute(array('id' => $id));
		$row = $sth->fetch(\PDO::FETCH_ASSOC);

		$style = (new Style())
				->setId($row['id'])
				->setName($row['name']);
				
		return $style;
	}

    public function getAll(){
        $sql = "SELECT * FROM style";
        $sth = $this->pdo->query($sql);
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $styleArray = array();
        foreach($result as $style){
            $styleArray[] = (new Style())
                ->setId($style['id'])
                ->setName($style['name']);
        }

        return $styleArray;
    }
}