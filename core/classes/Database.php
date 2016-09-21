<?php

namespace core\classes;

class Database
{
    use RequestAssistant;
    protected $connection;
    protected $tableName;

    public function __construct()
    {
        $this->connection = Connection::getConnection();
    }







}