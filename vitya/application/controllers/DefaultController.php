<?php

namespace application\controllers;

class DefaultController
{
    public $defaultModel;
    public function __construct()
    {
        $this->defaultModel = new \application\models\DefaultModel();//@todo удалить
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