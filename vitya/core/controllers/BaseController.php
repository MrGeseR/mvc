<?php

namespace core\controllers;

class BaseController
{
    protected $viewFolder;

    public function __construct()
    {

    }

    public function getViewFolder($file)
    {
        $className = explode('\\', get_called_class());
        $className = array_pop($className);
        $className = strtolower(str_replace('Controller', '', $className));
        $this->viewFolder = str_replace('controllers', 'views', dirname($file)) . '/' . $className;
    }

    protected function view($view, $params = [])
    {
        extract($params);
        require_once $this->viewFolder . '/' . $view . '.php';
    }

}