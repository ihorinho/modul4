<?php

namespace Model;

use Model\StyleRepository;

class Book{
	private $id;
    private $title;
    private $description;
    private $is_active;
    private $price;
    private $style;

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
    public function setStyle($style_id)
    {
        $repo = new StyleRepository();
        $this->style = $repo->getByID($style_id);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStyle($show_style = false)
    {
        if($show_style){
        	return $this->style->getName();
        }
        return $this->style;
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
}