<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 13.02.2017
 * Time: 13:11
 */

namespace Model\Repository;
use Library\EntityRepository;
use Library\Request;

class CommentsRepository extends EntityRepository{

    public function getAll(){
        $sql = "SELECT * FROM comments
                ORDER BY date DESC";
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function save(Request $request){
        $sql = "UPDATE comments 
                SET message = :message, rating = :rating, visible = :visible
                WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        return $result = $sth->execute(array('id' => $request->post('id'),
                                            'message' => $request->post('message'),
                                            'visible' => $request->post('visible', 0),
                                            'rating' => $request->post('rating',0)));
    }

    public function getWaitingPolitic(){
        $sql = "SELECT cm.id as id, n.title as title, message, visible, user, c.name as category, date, rating  
                FROM comments cm
                JOIN news n ON n.id = cm.new_id
                JOIN category c ON c.id = n.category_id
                WHERE c.alias = 'politic' AND cm.visible = 0
                ORDER BY cm.date DESC
                ";
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
}