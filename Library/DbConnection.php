<?php

namespace Library;

use Library\Config;

class DbConnection{

	private static $instance = null;

	private $pdo;

	public function __construct(){

		$dsn = 'mysql: host=' . Config::get('db_host') . '; dbname=' . Config::get('db_name');
		$this->pdo = new \PDO($dsn, Config::get('db_user'), Config::get('db_password'));
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new DbConnection();
		}

		return self::$instance;
	}

	public function getPDO(){

		return $this->pdo;
	}


	private function __clone(){}
	private function __wakeup(){}
}