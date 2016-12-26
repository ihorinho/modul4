<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/25/16
 * Time: 6:42 PM
 */
namespace Model\Repository;

use Library\EntityRepository;
use Model\Author;

class AuthorRepository extends EntityRepository{

    public function getAllFullNames(){
        $sql = "SELECT id, first_name, last_name FROM author
                ORDER BY first_name, last_name";
        $sth = $this->pdo->query($sql);
        $authorsArray  = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $authorsFullNameArray = array();
        foreach ($authorsArray as $author) {
            $authorsFullNameArray[$author['id']] = $author['first_name'] . " " . $author['last_name'];
        }
        return $authorsFullNameArray;
    }

    public function getArray($queryResult){
        foreach($queryResult as $author){
            $authors[]  = (new Author())->setId($author['id'])
                ->setFirstName($author['first_name'])
                ->setLastName($author['last_name'])
                ->setDateBirth($author['date_birth'])
                ->setDateDeath($author['date_death']);
        }
        return $authors;
    }

    public function getArrayByBookId($id){
        $authors = array();

        $sql = "SELECT * FROM book_author ba
            JOIN author a ON
            ba.author_id = a.id
            WHERE ba.book_id = $id";

        $sth = $this->pdo->query($sql);
        $authorsArray = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($authorsArray)){
            return array();
        }

        return $this->getArray($authorsArray);
    }

}