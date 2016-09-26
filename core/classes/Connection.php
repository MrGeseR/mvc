<?php

namespace core\classes;

class Connection
{
    private static $_pdo;//

    private function __construct()
    {
    }

    private static function setConnection()
    {
        $params = require_once '../core/configs/db.php';
        \PDO::MYSQL_ATTR_INIT_COMMAND;
        $dsn = "mysql:host=" . $params['host'] . ";dbname=" . $params['database'];
        $opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        self::$_pdo = new \PDO($dsn, $params['user'], $params['password'], $opt);
    }

    public static function getConnection()
    {
        if (self::$_pdo === null) {
            self::setConnection();
        }
        return self::$_pdo;
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }


}