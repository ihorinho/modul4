<?php

namespace Model\Forms;

use Library\Request;

class BookEditForm{
    private $id;
    private $title;
    private $description;
    private $price;
    private $is_active;
    private $styleId;
    private $authors = [];

    public function __construct(Request $request){
        $this->id = $request->post('id');
        $this->title = $request->post('title');
        $this->description = $request->post('description');
        $this->price = $request->post('price');
        $this->is_active = (int)$request->post('is_active', 0);
        $this->styleId = (int)$request->post('style');
        $this->authors = $request->post('authors');
    }
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array|null
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @return int
     */
    public function getStyleId()
    {
        return $this->styleId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active;
    }


    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function isValid(){
        return $this->title !== '' &&
                $this->description !== '' &&
                $this->price !== '' &&
                $this->styleId !== '' &&
                !empty($this->authors);
    }
}