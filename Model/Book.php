<?php

namespace Model;

use Model\Repository\AuthorRepository;
use Model\Repository\StyleRepository;


class Book{
	private $id;
    private $title;
    private $author;
    private $author_ids = [];
    private $description;
    private $is_active;
    private $price;
    private $style_id;
    private $style;

    /**
     * @return mixed
     */
    public function getStyleId()
    {
        return $this->style_id;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function IsActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $style
     */
    public function setStyle($style_id, $pdo)
    {
        $repo = new StyleRepository();
        $repo->setPDO($pdo);
        $styleObj = $repo->getByID($style_id);
        $this->style = $styleObj->getName();
        $this->style_id = $styleObj->getId();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStyle()
    {
       return $this->style;
    }

    /**
     * @param array $author_ids
     */
    public function setAuthorIds($author_ids)
    {
        $this->author_ids = $author_ids;
        return $this;

    }

    /**
     * @param mixed $style_id
     */
    public function setStyleId($style_id)
    {
        $this->style_id = $style_id;
        return $this;

    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($book_id, $pdo)
    {
        $repo = new AuthorRepository();
        $repo->setPDO($pdo);
        $authors = $repo->getArrayByBookId($book_id);
        if(!$authors){
            $this->author = 'Unknown';
            return $this;
        }
        $authorArray = array();
        foreach($authors as $author){
            $authorArray[] = $author->getFirstName() . " " .  $author->getLastName();
            $this->author_ids[] = $author->getId();
        }

        $this->author = implode(', ', $authorArray);

        return $this;
    }

    /**
     * @return array
     */
    public function getAuthorIds()
    {
        return $this->author_ids;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

}