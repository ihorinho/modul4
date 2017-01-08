<?php

namespace Library;

use \Symfony\Component\Yaml\Yaml;

class Config{

    private $config = array();

    public function __construct(){
        $dir_handler = opendir(CONFIG_PATH);
        while(false !== ($file = readdir($dir_handler))){
            if(is_file(CONFIG_PATH . $file)){
                $filename_parts = explode('.', $file);
                $extension = (string)array_pop($filename_parts);
                $config_key = $filename_parts[0];
                switch ($extension){
                    case 'yml':
                        $data = Yaml::parse(file_get_contents(CONFIG_PATH . $file));
                        if(empty($data)){
                            throw new \Exception("{$file} is empty");
                        }
                        foreach($data as $key => $value){
                            $this->config[$key] = $value;
                        }
                        break;
                    case 'xml':
                        $XMLObject = simplexml_load_file(CONFIG_PATH . $file, 'SimpleXMLElement', LIBXML_NOWARNING);
                        foreach ($XMLObject as $key => $value){
                            $this->config[$config_key][$key] = $value;
                        }
                        break;
                }
            }
        }
    }

    public function get($key){
        if(!isset($this->config[$key])){
            throw new \Exception("{$key} doesn\'t set in config file");
        }
        return $this->config[$key];
    }

}