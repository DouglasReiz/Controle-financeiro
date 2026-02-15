<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/helpers.php';


$routes = require __DIR__ . '/../config/routes.php';

// Verificação de segurança (para evitar o erro que você recebeu)
if (!is_array($routes)) {
    die("Erro: O arquivo de rotas não retornou um array válido.");
}

$url = explode('?', $_SERVER['REQUEST_URI'])[0];

// Use o isset que é mais seguro
if (isset($routes[$url])) {
    $route = $routes[$url];
    $controllerName = $route['controller'];
    $methodName = $route['action']; // Verifique se no helpers é 'action' ou 'method'

    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo "404 - Rota não encontrada";
}