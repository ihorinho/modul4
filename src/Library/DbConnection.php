<?php

namespace Library;

class DbConnection{

	private static $instance = null;

	private $pdo;

	public function __construct($config){

        $dsn = 'mysql: host=' .$config->db_host . '; dbname=' . $config->db_name;
		$this->pdo = new \PDO($dsn, $config->db_user, $config->db_password);
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