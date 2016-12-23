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
					->setIsActive((int)$row['is_active'])
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

    public function getByIdArray(Array $ids){
        $prepare_ids = array();
        foreach ($ids as $id) {
            $prepare_ids[] = '?';
        }
        $ids_string = implode(',', $prepare_ids);

        $sql = "SELECT * FROM book WHERE id IN($ids_string)";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array_values($ids));

        return $this->getBooksArray($sth);
    }

    public function deleteById($id){

        $sql = "UPDATE book SET is_active = 0 WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array('id' => $id));
    }

    public function save(Book $book){
        $sql = "UPDATE book
                SET title = :title, description = :description, price = :price, is_active = :is_active
                WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array('id' => $book->getId(), 'title' => $book->getTitle(), 'description' => $book->getDescription(),
                            'price' => $book->getPrice(), 'is_active' => $book->IsActive()));
    }

}