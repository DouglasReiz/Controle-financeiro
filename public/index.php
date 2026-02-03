<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\IndexController;


$url = explode('?', $_SERVER['REQUEST_URI'][0]);

function CreateRoute(string $controllerName, string $methodName): array
{
    return [
        'controller' => $controllerName,
        'method' => $methodName,
    ];
}

$routes = [
    '/' => CreateRoute(IndexController::class, 'indexAction'),
];

$controllerName = $routes[$url]['controller'];
$methodName = $routes[$url]['method'];

new $controllerName()-> $methodName();