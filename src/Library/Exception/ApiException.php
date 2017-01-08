<?php

namespace Library\Exception;

class ApiException extends \Exception{
    public function getResponse(){
        return json_encode(['status' => 'fail', 'message' => $this->getMessage()]);
    }
}