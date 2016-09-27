<?php

namespace modules\StrangeModule\models;

use core\models\BaseModel;

class UsersModel extends BaseModel
{
    protected $tableName;

    public function __construct()
    {
        parent::__construct();
       $this->tableName = 'users';
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare('SELECT * FROM ' . $this->tableName);
        $stmt->execute();
        $row = $stmt->fetchAll();
        return $row;
    }

    public function get()
    {
        $stmt = $this->connection->prepare('SELECT ' . $this->selectItems . ' FROM ' . $this->tableName . '' . $this->conditions . '' . $this->order);
        $stmt->execute();
        $row = $stmt->fetchAll();
        $this->selectItems = '*';
        $this->conditions = '';
        $this->order = '';
        return $row ;
    }

    public function test()
    {
        $this->whereIdAndUser(1, 'asdfg');
        $row = $this->get();
        return $row;
    }
}