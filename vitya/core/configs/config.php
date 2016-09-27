<?php

class Config
{
    private static $_data = [];

    private function __construct()
    {

    }

    protected function __clone()
    {
        // TODO: Implement __clone() method.
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
        self::$_data[$key] = $value;
    }

    private function __sleep()
    {

    }

    private function __wakeup()
    {

    }
}