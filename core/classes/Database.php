<?php

namespace core\classes;

class Database
{
    use RequestAssistant;
    protected $connection;
    protected $tableName;

    public function __construct($obj)
    {
        $this->connection = Connection::getConnection();
        $namespaceLength = strripos(get_class($obj), '\\') + 1;                              // 1 - это символ "\"
        $temp = substr(get_class($obj), $namespaceLength);
        $temp = str_replace('Model', '', $temp);
        $this->tableName = strtolower($temp);
    }







}