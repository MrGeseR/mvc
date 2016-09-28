<?php
ini_set('display_errors', 1);
require_once '../core/configs/config.php';
Config::set('time_start', microtime());
require_once '../helpers/helpers.php';

Config::set('ROOT', str_replace('public','',dirname(__FILE__)));
Config::set('DefaultController', 'user');//@todo в файле
Config::set('DefaultAction', 'actionIndex');//@todo в файле

$route = new core\classes\Router();
$url = substr($_SERVER['REQUEST_URI'], 1);

$route->parseUrl();


