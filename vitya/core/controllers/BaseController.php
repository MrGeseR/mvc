<?php

namespace core\controllers;

class BaseController
{
    protected $viewFolder;

    public function __construct($file, $obj)//@todo без $obj вынести из конструктора
    {
        $temp = explode('\\', get_class($obj));//@todo temp
        $temp = array_pop($temp);
        $temp = strtolower(str_replace('Controller', '', $temp));
        $className = get_class($obj);//@todo удалять не нужные переменные
        $this->viewFolder = str_replace('controllers', 'views', dirname($file)) . '/' . $temp;
//        $this->viewFolder = str_replace('controllers', 'views',__FILE__);
    }

    protected function view($view, $params = [])
    {
        extract($params);
        require_once $this->viewFolder . '/' . $view . '.php';
    }

}