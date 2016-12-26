<?php

namespace Library;

class Config{

    public function __construct(){
        $file = CONFIG_PATH . 'db.xml';
        if(!is_file($file)){
            throw new \Exception('Congfig file doesn\'t exist');
        }

        $XMLObject = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOWARNING);

        foreach ($XMLObject as $key => $value){
            $this->$key = (string)$value;
        }
    }

    public function __get($value){
        throw new \Exception("{$value} doesn\'t set in config file");
    }

}