<?php

namespace application\controllers;

use application\models\TestModel;
use core\controllers\BaseController;

class TestController extends BaseController
{
    public function __construct()
    {
        parent::__construct(__FILE__,$this);
    }

    public function actionIndex($from, $to)
    {
        $data = (new TestModel())
            ->select(['id', 'name', 'age', 'text', 'created_at', 'updated_at'])
            ->where('id', '!=', 100000)
            ->whereBetween('id', $from, $to)
            ->orderBy('id', 'desc')
            ->get();
        return $this->view('index', [
            'response' => $data,
        ]);
    }
}