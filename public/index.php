<?php
ini_set('display_errors', 1);
require_once '../helpers/autoload.php';
require_once '../helpers/dd.php';//
require_once '../core/configs/config.php';

Config::set('ROOT', dirname(__FILE__).'/..');
Config::set('DefaultController', 'user');
Config::set('DefaultAction', 'index');


define('ROOT', dirname(__FILE__).'/..');


$rout = new core\classes\Router();
$url = substr($_SERVER['REQUEST_URI'], 1);//

$rout->parseUrl($url);


