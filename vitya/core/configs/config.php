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

    public static function set($key, $value)//@todo не должно переписывать
    {
        self::$_data[$key] = $value;
    }

    private function __sleep()
    {

    }

    private function __wakeup()
    {

    }
}