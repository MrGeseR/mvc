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

    public function index()
    {

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
        $where = '';
        $orWhere = '';
        if ($this->conditions) {
            $where = ' WHERE ';
            $countWhere = 1;
            foreach ($this->conditions as $value) {
                if ($countWhere === 1) {
                    $where .= $value;
                    $countWhere++;
                    continue;
                }
                $where .= ' AND ' . $value;
            }
        }
        if ($this->alterConditions) {
            $orWhere = ' OR ';
            $countOrWhere = 1;
            foreach ($this->alterConditions as $value) {
                if ($countOrWhere === 1) {
                    $orWhere .= $value;
                    $countOrWhere++;
                    continue;
                }
                $orWhere .= ' AND ' . $value;
            }
        }
        $stmt = $this->connection->prepare('SELECT ' . $this->selectItems . ' FROM ' . $this->tableName .
            $where . $orWhere . $this->order);
        foreach ($this->binding as $key=>$value){
            $stmt->bindValue($key, $value);
        }
        $stmt->execute($this->binding);
        $row = $stmt->fetchAll();
        $this->selectItems = '*';
        $this->conditions = [];
        $this->alterConditions = [];
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