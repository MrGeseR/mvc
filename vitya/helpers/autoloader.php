<?php

namespace helpers;

class AutoLoader
{
    public function __construct($name)
    {
        set_include_path(__DIR__ . '/..');
        $className = substr(strrchr($name, '\\'),1);
        $temp = substr($name, 0,strrpos($name,'\\'));
        $dir = str_replace( '\\', '/', $temp);
        require_once $dir . '/' . $className . '.php';
    }
}
