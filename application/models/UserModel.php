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
        $prepareWhere = '';
        $executeWhere = [];
        $countWhere = 1;
        foreach ($this->conditions as $key => $value) {
            $executeWhere[$key] = $value;
            if ($countWhere === 1) {
                $where .= ' WHERE ' . $key . $value;
                $countWhere++;
            }
            $where .= ' AND ' . $key . '=:' . $key;
        }
        $orWhere = $this->alterConditions ? ' OR WHERE ' : '';
        $prepareOrWhere = '';
//        $executeOrWhere = [];
        foreach ($this->alterConditions as $key => $value) {
            $prepareOrWhere .= $key . '=:' . $key;
            if (isset($executeWhere[$key])) {
                $key;
            }
            $executeWhere[$key] = $value;
        }
        $stmt = $this->connection->prepare('SELECT ' . $this->selectItems . ' FROM ' . $this->tableName .
            $where . $orWhere . $this->order);
        dd($stmt);
        $stmt->execute($executeWhere);
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