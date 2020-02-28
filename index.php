<?php

require_once './vendor/autoload.php';
$autoloader = new Composer\Autoload\ClassLoader();
$autoloader->add('', __DIR__.'/Controller');
$autoloader->register();

require_once 'Controller/Controller.php';
require_once 'Controller/IndexController.php';

use App\Controller\Controller;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controllerName = $_POST['controller'];
    $actionName = $_POST['action'];
    unset($_POST['controller']);
    unset($_POST['action']);

    $options = [
        $controllerName,
        $actionName,
        $_POST
    ];
    $controller = new Controller($options);
} else {
    $controller = new Controller();
}

$controller->run();
