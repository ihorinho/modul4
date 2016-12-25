<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 24.12.2016
 * Time: 0:55
 */
namespace Library\Pagination;

class Button
{
    private $page;
    private $text;
    private $is_active;

    public function __construct($page, $is_active = true, $text = null)
    {
        $this->page = $page;
        $this->text = is_null($text) ? $page : $text;
        $this->is_active = $is_active;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    public function getText(){
        return $this->text;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->is_active;
    }
}