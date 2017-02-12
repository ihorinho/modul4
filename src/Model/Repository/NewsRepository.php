<?php

namespace Model\Repository;

use Model\News;
use Library\EntityRepository;

class NewsRepository extends EntityRepository{

    public function getCountCategory($category){
        $sqlWHERE = "WHERE c.alias = '{$category}'";
        if($category == 'analitic'){
            $sqlWHERE = "WHERE analitic = 1";
        }
        $sql = "SELECT COUNT(*) FROM news n
                JOIN category c ON  n.category_id = c.id
                $sqlWHERE";
        $sth = $this->pdo->query($sql);
        return (int)$sth->fetchColumn();
    }

    public function getCountTag($tag){
        $sql = "SELECT COUNT(*) FROM news n
                JOIN category c ON  n.category_id = c.id
                WHERE tag LIKE '%$tag%'";
        $sth = $this->pdo->query($sql);
        return (int)$sth->fetchColumn();
    }


    public function getLastNewsList($category_id, $limit = 5){
        $sql = "SELECT n.id as id, title, content, c.name as category, tag, analitic, published, c.alias as category
                FROM news n
                JOIN category c ON n.category_id = c.id
                WHERE n.category_id = $category_id
                ORDER BY published DESC
                LIMIT $limit";
        $sth = $this->pdo->query($sql);

        return $this->getNewsArray($sth);
    }

    public function getAllCategory($category, $offset, $count){
        $sqlWHERE = "WHERE c.alias = '{$category}'";
        if($category == 'analitic'){
            $sqlWHERE = "WHERE analitic = 1";
        }
        $sql = "SELECT n.id as id, title, content, c.name as category, c.alias as alias, tag, analitic, published
                FROM news n
                JOIN category c ON  n.category_id = c.id
                $sqlWHERE
                ORDER BY n.published DESC
                LIMIT $offset,$count";

        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id){

        $sql = "SELECT n.id as id, title, content, c.name as category, n.category_id as category_id,
                      c.alias as alias, tag, analitic, published
                FROM news n
                JOIN category c ON  n.category_id = c.id
                WHERE n.id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array('id' => $id));

        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByTag($tag, $offset, $count){

        $sql = "SELECT n.id as id, title, tag, published, c.alias as category_alias
                FROM news n
                JOIN category c ON  n.category_id = c.id
                WHERE tag LIKE '%$tag%'
                ORDER BY n.published DESC
                LIMIT $offset,$count";
        $sth = $this->pdo->query($sql);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAnalitic(){
        $sql = "SELECT * FROM news WHERE analitic = 1";
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function cutContent($text, $sentenceCount){
        $sentenceArray = explode('.', $text);
        $resultArray = array_slice($sentenceArray, 0, 3);
        return implode('. ', $resultArray);
    }

    public function getAllNews(){

        $sql = "SELECT n.id, n.title, c.name as category, n.analitic, n.published, n.tag
                FROM news n
                JOIN category c ON  n.category_id = c.id
                ORDER BY n.published DESC";
        $sth = $this->pdo->query($sql);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteById($id){

        $sql = "DELETE FROM news WHERE id = :id";
        $sth = $this->pdo->prepare($sql);

        return $sth->execute(array('id' => $id));
    }

    public function add(News $new){
        $sql = "INSERT INTO news
                    SET title = :title, content = :content, category_id = :category_id, analitic = :analitic,
                      tag = :tag, published = :published";
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute(array('title' => $new->getTitle(), 'content' => $new->getContent(),
            'category_id' => $new->getCategoryId(), 'analitic' => $new->isAnalitic(), 'tag' => $new->getTag(),
            'published' => $new->getPublished()));
        if($result === false){
            throw new \Exception('Errors during saving New to DB');
        }

        return $this;
    }

    public function updateNews(News $new){
        $sql = "UPDATE news
                    SET title = :title, content = :content, category_id = :category_id, analitic = :analitic,
                      tag = :tag
                WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute(array('id' => $new->getId(), 'title' => $new->getTitle(), 'content' => $new->getContent(),
            'category_id' => $new->getCategoryId(), 'analitic' => $new->isAnalitic(), 'tag' => $new->getTag()));
        if($result === false){
            throw new \Exception('Errors during saving book to DB');
        }

        return $this;
    }









    public function getAllSorted($sortParam, $session){
        $allowedParams = array('id', 'title', 'price', 'is_active');
        $sortOrder = 'ASC';

        $sql = "SELECT * FROM book";

        if($sortParam && in_array($sortParam, $allowedParams)){
            $sorted = $session->get('sort');
            if($sortParam == $sorted){
                $order = $session->get('order');
                $sortOrder = $order == 'ASC' ? 'DESC' : 'ASC';
            }

            $session->set('sort', $sortParam);
            $session->set('order', $sortOrder);

            $sql .= " ORDER BY $sortParam $sortOrder";
        }

        $sth = $this->pdo->query($sql);

        return $this->getBooksArray($sth);
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

    private function getNewsArray($sth, $single = false){
		$news = array();

		while($row = $sth->fetch(\PDO::FETCH_ASSOC)){
			$new = (new News())
					->setId($row['id'])
					->setTitle($row['title'])
					->setContent($row['content'])
					->setCategoryId((int)$row['category'])
					->setTag($row['tag'])
                    ->setAnalitic($row['analitic'], $this->pdo)
					->setPublished($row['published'], $this->pdo);

			$news[] = $new;
		}

		if($single){
			return $news[0];
		}

		return $news;
	}

    public function getLastInsertId(){
        return $this->pdo->lastInsertId();
    }








    public function getNewsIds(){
        $query = "SELECT id FROM book ORDER BY id";
        $sth = $this->pdo->query($query);
        $fetchedArray = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $result = array();
        foreach($fetchedArray as $row){
            $result[] = $row['id'];
        }
        return $result;
    }
}