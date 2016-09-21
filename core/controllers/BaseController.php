<?php

namespace core\controllers;

class BaseController
{
    protected $viewFolder;

    public function __construct()
    {
        dd($this->viewFolder);
    }

    protected function view($view, $params = [])
    {
        require_once $this->viewFolder .'/'. $view . '.php';
    }
}