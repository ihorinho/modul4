<?php

namespace Library;

interface OutputFormatterInterface
{
    public function output($status, $message);
}