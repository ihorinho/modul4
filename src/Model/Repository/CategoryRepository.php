<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 2/9/17
 * Time: 7:59 PM
 */

namespace Model\Repository;
use Library\EntityRepository;

class CategoryRepository extends EntityRepository{

    public function getAll(){

        $sql = "SELECT * FROM category";
        $sth = $this->pdo->query($sql);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function add($name, $alias){
        $sql = "INSERT INTO category (name, alias) VALUES('$name', '$alias')";
        if(false == $this->pdo->query($sql)){
            throw new \Exception('Error while adding new category');
        }
        return true;
    }
}