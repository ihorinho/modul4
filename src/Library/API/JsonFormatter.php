<?php

namespace Library\API;

use Library\OutputFormatterInterface;

class JsonFormatter implements OutputFormatterInterface
{
    public function output($status, $message)
    {
        header('Content-Type: application/json');
        return json_encode(compact('status', 'message'));
    }
}