<?php

namespace application\models;

use core\models\BaseModel;

class UserModel extends BaseModel
{
    protected $tableName;
    public $row;

    public function __construct()
    {
        parent::__construct();
        $namespaceLength = strripos(get_class($this), '\\') + 1;                              // 1 - это символ "\"
        $temp = substr(get_class($this), $namespaceLength);
        $temp = str_replace('Model', 's', $temp);
        $this->tableName = strtolower($temp);
    }

    public function index(){

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
        $this->row = $row;
        return $row;
    }

    public function test()
    {
        $this->whereIdAndUser(1, 'asdfg');
        return $this->get();
    }
}