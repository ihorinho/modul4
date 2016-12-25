<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/26/16
 * Time: 1:09 AM
 */
namespace Model\Forms;

use Library\Request;

class BookAddForm{
    private $title;
    private $description;
    private $price;
    private $is_active;
    private $styleId;
    private $authors = [];

    public function __construct(Request $request){
        $this->title = $request->post('title');
        $this->description = $request->post('description');
        $this->price = $request->post('price');
        $this->is_active = (int)$request->post('is_active', 0);
        $this->styleId = (int)$request->post('style');
        $this->authors = $request->post('authors');
    }

    public function isValid(){
        return $this->title !== '' &&
        $this->description !== '' &&
        $this->price !== '' &&
        $this->styleId !== '' &&
        !empty($this->authors);
    }

    /**
     * @return array
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
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
     * @return int
     */
    public function getStyleId()
    {
        return $this->styleId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


}