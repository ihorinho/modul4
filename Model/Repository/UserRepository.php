<?php

namespace Model\Repository;

use Library\EntityRepository;
use Model\User;

class UserRepository extends EntityRepository{

	public function find($email, $password){
		$sql = "SELECT * FROM user WHERE email = :email AND password = :password";
		$sth = $this->pdo->prepare($sql);
		$sth->execute(compact('email', 'password'));

		$user = $sth->fetch(\PDO::FETCH_ASSOC);

		if($user){
			$user = (new User())->setEmail($user['email'])->setPassword($user['password']);
		}

		return $user;
	}
}