<?php

namespace Library;

class DbConnection{

	private static $instance = null;

	private $pdo;

	public function __construct($config){

        $db_config = $config->get('db');

        $dsn = 'mysql: host=' .$db_config['db_host']. '; dbname=' . $db_config['db_name'];
		$this->pdo = new \PDO($dsn, $db_config['db_user'], $db_config['db_password']);
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('set names utf8');
	}

	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getPDO(){

		return $this->pdo;
	}


	private function __clone(){}
	private function __wakeup(){}
}