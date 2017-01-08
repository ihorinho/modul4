<?php

namespace Library\API;

use Library\OutputFormatterInterface;

class HtmlFormatter implements OutputFormatterInterface
{
    public function output($status, $message)
    {
        header('Content-Type: text/html');
        if(is_array($arr = $message)){
            ob_start();
            echo "Books: <hr/>";
            foreach($arr as $books){
                foreach($books as $key => $value){
                    if(is_array($value)){
                        $value = implode(',', $value);
                    }
                    echo $key . ': ' . $value . "<br/>";
                }
                echo "<hr/>";
            }
            $message = ob_get_clean();
        }

        return $message;
    }
}