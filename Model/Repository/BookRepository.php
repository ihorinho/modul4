<?php

namespace Model\Repository;

use Model\Book;
use Library\EntityRepository;

class BookRepository extends EntityRepository{

	private function getBooksArray($sth, $single = false){
		$books = array();

		while($row = $sth->fetch(\PDO::FETCH_ASSOC)){
			$book = (new Book())
					->setId($row['id'])
					->setTitle($row['title'])
					->setDescription($row['description'])
					->setIsActive($row['is_active'])
					->setPrice($row['price'])
					->setStyle($row['style_id'], $this->pdo);

			$books[] = $book;
		}

		if($single){
			return $books[0];
		}

		return $books;
	}

	public function getAll(){

		$sql = "SELECT * FROM book";
		$sth = $this->pdo->query($sql);

		return $this->getBooksArray($sth);
	}

	public function getAllActive(){

		$sql = "SELECT * FROM book WHERE is_active = :num";
		$sth = $this->pdo->prepare($sql); 
		$sth->execute(array('num' => 1));

		return $this->getBooksArray($sth);
	}

	public function getById($id){

		$sql = "SELECT * FROM book WHERE id = :id";
		$sth = $this->pdo->prepare($sql);
		$sth->execute(array('id' => $id));

		return $this->getBooksArray($sth, true);
	}
}