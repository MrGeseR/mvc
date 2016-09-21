<?php

namespace core\models;

use core\classes\Database;

class BaseModel extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionIndex($array = [])
    {
        echo 'Hello, its User Model and its default action!<br>';
        if ($array) {
            echo 'My params:<br>';
            foreach ($array as $key => $value) {
                echo 'Key (' . $key . ') = Value (' . $value . ')<br>';
            }
        } else {
            echo 'I have no params';
        }

    }
}