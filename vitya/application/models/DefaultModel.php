<?php

namespace application\models;

class DefaultModel
{
    public function __construct()
    {

    }

    public function actionDefault()
    {
        echo 'This is default method of default model';
    }
    public function actionFirst()
    {
        echo 'This if First method of default model';
    }
}