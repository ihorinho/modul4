<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/31/16
 * Time: 12:47 AM
 */
namespace Library;

abstract class Registry{
    private static $register = array();

    public static function addToRegister($key, $value){
       self::$register[$key] = $value;
    }

    public static function get($key){
        if(!array_key_exists($key, self::$register))
            return false;
        return self::$register[$key];
    }
}