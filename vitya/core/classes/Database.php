<?php

namespace core\classes;

class Database
{
    use RequestAssistant;

    protected $connection;
    protected $tableName;

    public function __construct($obj)//@todo убрать $obj === $this
    {
        //@todo get_class($obj)===get_called_class()
        $this->connection = Connection::getConnection();
        //@todo в отдельный метод
        $namespaceLength = strripos(get_class($obj), '\\') + 1; // 1 - это символ "\"
        $temp = substr(get_class($obj), $namespaceLength);
        $temp = str_replace('Model', '', $temp);
        $this->tableName = strtolower($temp);
    }

}