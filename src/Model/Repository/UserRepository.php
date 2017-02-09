<?php

namespace Model\Repository;

use Library\EntityRepository;
use Model\User;

class UserRepository extends EntityRepository{

	public function find($email, $password, $is_active){
		$sql = "SELECT * FROM user WHERE email = :email AND password = :password AND is_active = :is_active";
		$sth = $this->pdo->prepare($sql);
		$sth->execute(compact('email', 'password', 'is_active'));

		$user = $sth->fetch(\PDO::FETCH_ASSOC);
		if(!$user){
            return false;
        }

        $user = (new User())
            ->setId($user['id'])
            ->setEmail($user['email'])
            ->setPassword($user['password'])
            ->setAdmin( (int) $user['admin']);
		return $user;
	}

    public function save($id, $password){
        $sql = "UPDATE user
                SET password = :password
                WHERE id = $id";
        $sth = $this->pdo->prepare($sql);
        return $sth->execute(['password' => $password]);
    }

    public function addNew($email, $password, $code){
        $sql = "INSERT INTO user
                SET email = :email, password = :password, is_active = :code";
        $sth = $this->pdo->prepare($sql);
        return $sth->execute(['email' => $email, 'password' => $password, 'code' => $code]);
    }

    public function userExists($email){
        $sql = "SELECT count(*)
                FROM user
                WHERE email = :email";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(['email' => $email]);
        $result = $sth->fetchColumn();
       return (int)$result;
    }

    public function activate($user, $code){
        $sql = "UPDATE user
                SET is_active = 1
                WHERE email = :user AND is_active = :code";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(['user' => $user, 'code' => $code]);
        return $sth->rowCount();
    }
}