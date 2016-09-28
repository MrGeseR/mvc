<?php

namespace helpers;

class AutoLoader
{
    public function __construct($name)
    {
        set_include_path(\Config::get('ROOT'));
        $className = substr(strrchr($name, '\\'), 1);
        $temp = substr($name, 0, strrpos($name, '\\'));
        $dir = str_replace('\\', '/', $temp);
        require_once $dir . '/' . $className . '.php';
    }
}


spl_autoload_register(function ($name) {
    (new AutoLoader($name));
});

if (!function_exists('dd')) {
    function dd(...$params)
    {
        var_dump(...$params);
        die;
    }
}