<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/26/16
 * Time: 2:07 PM
 */
namespace Model;

class UploadedFile{

    private $file;
    private $name;
    private $tmpName;
    private $type;
    private $error;
    private $size;

    public function __construct($filename){
        if(!isset($_FILES[$filename])){
            throw new \Exception($filename . ' is not sended by form');
        }
        $this->file = $_FILES[$filename];
        $this->name = $this->getFile('name');
        $this->tmpName = $this->getFile('tmp_name');
        $this->size = $this->getFile('size');
        $this->type = $this->getFile('type');
        $this->error = $this->getFile('error');
    }

    public function isJPG(){
        $allowed_types = ['image/jpeg', 'image/pjpeg'];
        return in_array($this->type, $allowed_types);
    }

    public function moveToUploads($name){
        $destination = UPLOAD_PATH . $name . '.jpg';
        move_uploaded_file($this->getTmpName(), $destination);
//        dump(move_uploaded_file($this->getTmpName(), $destination)); die;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getFile($key)
    {
        return $this->file[$key];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

}