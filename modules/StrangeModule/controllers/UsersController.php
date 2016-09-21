<?php

namespace modules\StrangeModule\controllers;

use modules\StrangeModule\models\UsersModel;

class UsersController
{
    protected $user;

    public function __construct()
    {
        $this->user = new UsersModel();
    }

    public function actionIndex($array = [])
    {
        $this->user->actionIndex($array);

    }

    public function action($method, $props)
    {
        return $this->user->$method($props);
    }

}