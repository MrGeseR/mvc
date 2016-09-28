<?php

namespace core\classes;

class Database
{
    use RequestAssistant;

    protected $connection;
    protected $tableName;

    public function __construct()
    {
    }

    public function getTableName()
    {
        $namespaceLength = strripos(get_called_class(), '\\') + 1; // 1 - это символ "\"
        $temp = substr(get_called_class(), $namespaceLength);
        $temp = str_replace('Model', '', $temp);
        return $this->tableName = strtolower($temp);
    }

    public function connection()
    {
        $this->connection = Connection::getConnection();
    }

}