<?php

namespace Library;

abstract class Config{
	private static $items = array();

	public static function get($key){
		if(isset(self::$items[$key])){
			return self::$items[$key];
		}
		return null;
	}

	public static function set($key, $value){
		self::$items[$key] = $value;
	}
}