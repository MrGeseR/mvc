<?php

namespace application\controllers;

use application\models\UserModel;
use core\controllers\BaseController;

class UserController extends BaseController
{
    protected $user;
    protected $viewFolder;

    public function __construct()
    {
        $this->viewFolder = str_replace('controllers', 'views',__DIR__);
        $this->user = new UserModel();
    }

    public function actionIndex($array = [])
    {

        $row = $this->user
            ->where([
                'id' => 3,
                'user' => "jora"
            ])
            ->where(['user' => "qwerty"])
            ->orWhere(['user' => 'asdfg'])
            ->get();

        return $this->view('user', [
            'result' => $row,
            'data' => $this->user,
        ]);
    }



}