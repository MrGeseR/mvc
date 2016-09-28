<?php

namespace core\classes;


final class Router
{
    protected $routes;

    public function __construct()
    {
        $this->routes = include \Config::get('ROOT').'configs/routes.php';
    }

    private function getActionName($name)
    {
        return 'action' . ucfirst($name);
    }

    private function getController($name, $module = '')
    {
        $controllerName = ucfirst($name) . 'Controller';
        if (!$module) {
            return '\\application\\controllers\\' . $controllerName;
        } else {
            $moduleName = $this->getModuleName($module);
            return '\\modules\\' . $moduleName . '\\controllers\\' . $controllerName;
        }
    }

    private function getModuleName($name)
    {
        return ucfirst($name) . 'Module';
    }

    private function getParams($controller, $action, $params)
    {
        $newParams = [];
        $action = $this->getActionName($action);
        $a = new \ReflectionMethod($controller, $action);
        $wanted = $a->getParameters();
        $count = count($wanted);
        for ($i = 0; $i < $count; $i++) {
            if (isset($params[$wanted[$i]->name])) {
                $newParams[] = $params[$wanted[$i]->name];
            } else {
                die('U must enter ' . $wanted[$i]->name);
            }
        }
        $controller->$action(...$newParams);
    }

    private function methods($parts, $params = [])
    {
        $count = count($parts);

        switch ($count) {
            case 0:
                $this->defaultAction($params);
                break;
            case 1:
                $this->controller($parts, $params);
                break;
            case 2:
                $this->controllerWithAction($parts, $params);
                break;
            case 3:
                $this->module($parts, $params);
                break;
        }
    }

    public function parseUrl()
    {
        $url = substr($_SERVER['REQUEST_URI'], 1);
        if (strpos($url, '?')) {
            $uri = parse_url($url)['path'];
            parse_str(parse_url($url)['query'], $params);
        }

        $uri = $uri ?? $url;//
        $params = $params ?? [];
        if ($uri == '') {
            $parts = [];
        } else {
            $parts = explode('/', $uri);
        }
        $this->methods($parts, $params);
    }

    private function defaultAction($params = [])
    {
        $controller = $this->getController($this->routes['DefaultController']);
        $controllerObject = new $controller();
        $this->getParams($controllerObject, ($this->routes['DefaultAction']), $params);
    }

    private function controller($parts, $params = [])
    {
        $controller = $this->getController($parts[0]);
        $controllerObject = new $controller();
        $this->getParams($controllerObject, ($this->routes['DefaultAction']), $params);
    }

    private function controllerWithAction($parts, $params = [])
    {
        $controller = $this->getController($parts[0]);
        $controllerObject = new $controller();
        $this->getParams($controllerObject, $parts[1], $params);
    }

    private function module($parts, $params = [])
    {
        $controller = $this->getController($parts[1], $parts[0]);
        $controllerObject = new $controller();
        $this->getParams($controllerObject, $parts[2], $params);
    }
}