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
        dump($user); die;

		if($user){
			$user = (new User())
                ->setId($user['id'])
                ->setEmail($user['email'])
                ->setPassword($user['password']);
		}

		return $user;
	}

    public function save($id, $password){
        $sql = "UPDATE user
                SET password = :password
                WHERE id = $id";
        $sth = $this->pdo->prepare($sql);
        return $sth->execute(['password' => $password]);
    }
}