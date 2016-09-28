<?php

namespace application\models;

use core\models\BaseModel;

class UserModel extends BaseModel
{
    protected $tableName;


    public function __construct()
    {
        $this->getTableName();
        $this->connection();

    }

    public function index()
    {

    }



    public function test()
    {
        $this->whereIdAndUser(1, 'asdfg');
        return $this->get();
    }
}