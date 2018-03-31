<?php
require_once __DIR__ ."/vendor/autoload.php";
session_start();
$path = parse_url($_SERVER['REQUEST_URI']);
$routes =  explode('/', $path['path']);
$controller_name = "main";
$method_name = 'index';
$query = $path['query'];
if (!empty($routes[1])) {
    $controller_name = $routes[1];
}
if (!empty($routes[2])) {
    $method_name = $routes[2];
}
$classname = '\project1\\'.ucfirst($controller_name);
try {
    if (class_exists($classname)) {
        $controller = new $classname();
    } else {
        throw new Exception();
    }
    if (method_exists($controller, $method_name)) {
        $controller->$method_name($query);
    } else {
        throw new Exception();
    }
} catch (Exception $e) {
    require_once 'views/error.php';
}
