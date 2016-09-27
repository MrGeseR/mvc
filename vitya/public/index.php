<?php
ini_set('display_errors', 1);
require_once '../core/configs/config.php';
Config::set('time_start', microtime());
require_once '../helpers/autoload.php';
require_once '../helpers/dd.php';//

Config::set('ROOT', dirname(__FILE__).'/..');
Config::set('DefaultController', 'user');//@todo в файле
Config::set('DefaultAction', 'index');//@todo в файле

$route = new core\classes\Router();
$url = substr($_SERVER['REQUEST_URI'], 1);//@todo переместить в класс

$route->parseUrl($url);


