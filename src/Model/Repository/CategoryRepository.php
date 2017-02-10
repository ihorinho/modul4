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
}