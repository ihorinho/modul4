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

    public function add(Request $request){
        $sql = "INSERT INTO comments 
                SET  new_id= :new_id, user= :user, date= :date,
                    message = :message, rating = :rating, visible = :visible,
                    parent_id = :parent_id";
        $sth = $this->pdo->prepare($sql);
        $date = date('Y-m-d H:i:s', time());
        return $result = $sth->execute(array('new_id' => $request->post('new_id'),
            'message' => $request->post('message'),
            'user' => $request->post('user'),
            'date' => $date,
            'parent_id' => $request->post('parent_id', 0),
            'visible' => $request->post('visible', 0),
            'rating' => $request->post('rating',0)));
    }

    public function getAllByNewId($new_id){
        $sql = "SELECT * FROM comments
                WHERE new_id = $new_id
                ORDER BY rating DESC";
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateRating($id, $rating){
        $sql = "UPDATE comments 
                SET rating = :rating
                WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        return $sth->execute(array('id' => $id, 'rating' => $rating));
    }

    public function getByUser($email, $offset, $limit){
        $sql = "SELECT * FROM comments
                WHERE user = '$email'
                ORDER BY rating DESC
                LIMIT $offset,$limit";
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCountByUser($email){
        $sql = "SELECT COUNT(*) FROM comments
                WHERE user = '$email'";
        $sth = $this->pdo->query($sql);
        return (int)$sth->fetchColumn();
    }


}