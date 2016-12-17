<?php

namespace Library;

use Library\DbConnection;

class FeedbackMapper{

	public function save($feedback){
		$pdo = DbConnection::getInstance()->getPDO();

		$sql = "INSERT INTO feedback(username, email, message, ip_address)
				VALUES (:username, :email, :message, :ip_address)";
		$sth = $pdo->prepare($sql);
		$sth->execute(array('username' => $feedback->getUsername(),
							'email' =>  $feedback->getEmail(),
							'message' =>  $feedback->getMessage(),
							'ip_address' =>  $feedback->getIpAddress()
						));
	}
}