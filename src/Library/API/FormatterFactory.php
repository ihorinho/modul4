<?php

namespace Library\API;

abstract class FormatterFactory
{
    public static function create($format)
    {
        $name = '\\Library\\API\\' . ucfirst($format) . 'Formatter';
        return new $name();
    }
}