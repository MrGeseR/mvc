<?php

class Config
{
    private static $_data = [];

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function get($key, $default = null)
    {
        if (self::$_data[$key]) {
            return self::$_data[$key];
        }
        return $default;
    }

    public static function set($key, $value)
    {
        if(!isset(self::$_data[$key])) {
            self::$_data[$key] = $value;
        } else {
            die($key . 'is already set!');
        }
    }

    private function __sleep()
    {

    }

    private function __wakeup()
    {

    }
}