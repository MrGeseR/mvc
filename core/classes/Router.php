<?php

namespace core\classes;


final class Router
{
    public function __construct()
    {
        $this->routes = include '../configs/routes.php'; // переделать под конфиг
    }

    private function getActionName($name)
    {
        return $actionName = 'action'.ucfirst($name);
    }

    private function getControllerName($name)
    {
        return $ControllerName = ucfirst($name) . 'Controller';
    }

    private function getModuleName($name)
    {
        return $ModuleName = ucfirst($name) . 'Module';
    }


    public function parseUrl($url)
    {
        if (strpos($url, '?')) {
            $uri = parse_url($url)['path'];
            parse_str(parse_url($url)['query'], $params);
        }
        $uri = $uri ?? $url;
        $params = $params ?? [];
        if ($uri == '') {
            $parts = [];
        } else {
            $parts = explode('/', $uri);
        }

        $count = count($parts);

        switch ($count) {
            case 0:
                $this->defaultAction($params);
                break;
            case 1:
                $this->model($parts, $params);
                break;
            case 2:
                $this->modelWithAction($parts, $params);
                break;
            case 3:
                $this->module($parts, $params);
                break;
        };

    }

    private function defaultAction($params = [])
    {
        $controllerName = 'DefaultController';
        $controller = '\\application\\controllers\\' . $controllerName;
        $controllerObject = new $controller();
        $controllerObject->actionIndex($params);
    }

    private function model($parts, $params = [])
    {
        $controllerName = $this->getControllerName($parts[0]);
        $controller = '\\application\\controllers\\' . $controllerName;
        $controllerObject = new $controller();
        $controllerObject->actionIndex($params);
    }

    private function modelWithAction($parts, $params = [])
    {
        $controllerName = $this->getControllerName($parts[0]);
        $controller = \Config::get('controllers') . $controllerName;
        $controllerObject = new $controller();
        $actionName = $this->getActionName($parts[1]);
        $controllerObject->$actionName($params);
    }

    private function module($parts, $params = [])
    {
        $moduleName = $this->getModuleName($parts[0]);
        $controllerName = $this->getControllerName($parts[1]);
        $controller = '\\modules\\' . $moduleName . '\\controllers\\' . $controllerName;
        $controllerObject = new $controller();
        $actionName = $this->getActionName($parts[2]);
        $controllerObject->$actionName($params);
    }
}