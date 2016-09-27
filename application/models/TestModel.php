<?php

namespace application\models;

use core\models\BaseModel;

class TestModel extends BaseModel
{
    protected $tableName;

    public function __construct()
    {
        parent::__construct($this);
    }

}