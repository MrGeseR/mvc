<?php

namespace application\models;

use core\models\BaseModel;

class UserModel extends BaseModel
{
    protected $tableName;


    public function __construct()
    {
        parent::__construct($this);

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