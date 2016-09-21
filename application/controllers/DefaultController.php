<?php

namespace application\controllers;

//use application\models\DefaultModel;

class DefaultController
{
    public $defaultModel;
    public function __construct()
    {
        $this->defaultModel = new \application\models\DefaultModel();
    }
    public function actionIndex()
    {
        $this->defaultModel->actionDefault();
    }

    public function actionFirst()
    {
        $this->defaultModel->actionFirst();
    }
}