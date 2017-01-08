<?php
namespace Library;

class Response
{
    private $code;
    private $status;
    private $message;
    private $outputFormatter;

    public function __construct($code, $message, OutputFormatterInterface $outputFormatter)
    {
        $this->code = $code;
        $this->status = $this->code < 300 ? 'success' : 'fail';
        $this->message = $message;
        $this->outputFormatter = $outputFormatter;
        http_response_code($code);
    }

    public function __toString()
    {
        return $this
            ->outputFormatter
            ->output($this->status, $this->message);
    }
}