<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 24.12.2016
 * Time: 10:53
 */
namespace Library\Pagination;

class Pagination{
    private $buttons = array();
    private $next;
    private $previous;

    public function __construct($currentPage, $itemsCount, $itemsPerPage){
        $pagesCount = ceil($itemsCount/$itemsPerPage);
        $this->previous  = new Button(1, $currentPage > 1, "Previous" );
        for($i = 1; $i <= $pagesCount; $i++){
            $is_active = $i != $currentPage;
            $this->buttons[] = new Button($i, $is_active);
        }
        $this->next = new Button($pagesCount, $currentPage < $pagesCount, 'Next');
    }

    public function getButtons()
    {
        if(count($this->buttons) <= 3){
            return $this->buttons;
        }
        $buttonsCopy = $this->buttons;
        array_shift($buttonsCopy);
        array_pop($buttonsCopy );
        return $buttonsCopy;
    }

    public function getPrev(){
        return $this->previous;
    }

    public function getNext(){
        return $this->next;
    }

    public function getButtonsCount(){
        return  count($this->buttons);
    }

    public function getLastButton(){
        return count($this->buttons);
    }
}