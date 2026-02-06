<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\IndexController;

$requestUri = $_SERVER['REQUEST_URI'];
$urlParts = explode('?', $requestUri);
$url = $urlParts[0];

function CreateRoute(string $controllerName, string $methodName): array
{
    return [
        'controller' => $controllerName,
        'method' => $methodName,
    ];
}

$routes = [
    '/' => CreateRoute(IndexController::class, 'indexAction'),
    '/login' => CreateRoute(IndexController::class, 'loginAction'),
    '/register' => CreateRoute(IndexController::class, 'registerAction'),
];

if (!isset($routes[$url])) {
    echo "404 - Rota não encontrada";
    exit;
}

$controllerName = $routes[$url]['controller'];
$methodName = $routes[$url]['method'];
new $controllerName()->$methodName();