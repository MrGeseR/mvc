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
        parent::__construct(__FILE__,$this);//@todo ??????
        $this->user = new UserModel();
    }

    public function actionParams($id, $name)
    {
            echo 'Id = '.$id;
            echo '   Name = '.$name;
    }



    public function actionIndex($id)
    {
        $row = $this->user
//
            ->where('id', '=', $id)
//            ->orWhere(['id' => 1])
            ->get();

        return $this->view('user', [
            'result' => $row,
            'data' => $this->user,
        ]);
    }



}