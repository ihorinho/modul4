<?php

namespace Model\Repository;

use Library\EntityRepository;

class FeedbackRepository extends EntityRepository{

	public function save($feedback){

		$sql = "INSERT INTO feedback(username, email, message, ip_address)
				VALUES (:username, :email, :message, :ip_address)";
		$sth = $this->pdo->prepare($sql);
		$sth->execute(array('username' => $feedback->getUsername(),
							'email' =>  $feedback->getEmail(),
							'message' =>  $feedback->getMessage(),
							'ip_address' =>  $feedback->getIpAddress()
						));
	}
}