<?php
require_once 'autoloader.php';

spl_autoload_register(function ($name) {
    (new helpers\AutoLoader($name));
});