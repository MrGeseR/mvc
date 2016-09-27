<?php

namespace core\models;

use core\classes\Database;

class BaseModel extends Database
{
    public function __construct()
    {
        parent::__construct($this);
    }

}