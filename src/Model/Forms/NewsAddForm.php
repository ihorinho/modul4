<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/26/16
 * Time: 1:09 AM
 */
namespace Model\Forms;

use Library\Request;

class NewsAddForm{
    private $title;
    private $content;
    private $category;
    private $analitic;
    private $tag;
    private $published;

    public function __construct(Request $request){

        $this->title = $request->post('title');
        $this->content = $request->post('content');
        $this->category = $request->post('category');
        $this->analitic = (int)$request->post('analitic', 0);
        $this->tag = $this->sortTags($request->post('tag'));
        $this->published = date('Y-m-d H:i:s', time());
    }

    public function isValid(){
        return $this->title !== '' &&
        $this->content !== '' &&
        $this->category !== '';
    }

    protected function sortTags($tags){
        $tagsArray = explode(', ', $tags);
        sort($tagsArray);
        return implode(', ', $tagsArray);
    }

    /**
     * @param int $analitic
     */
    public function setAnalitic($analitic)
    {
        $this->analitic = $analitic;
        return $this;
    }

    /**
     * @return int
     */
    public function getAnalitic()
    {
        return $this->analitic;
    }

    /**
     * @param null $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param null $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param bool|string $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return bool|string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param int $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return int
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param null $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }
}