<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/helpers.php';

$routes = require __DIR__ . '/../config/routes.php';

if (!is_array($routes)) {
    die("Erro: O arquivo de rotas não retornou um array válido.");
}

$url = explode('?', $_SERVER['REQUEST_URI'])[0];

if (isset($routes[$url])) {
    $route = $routes[$url];
    $controllerName = $route['controller'];
    $methodName = $route['action'];

    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo "404 - Rota não encontrada";
}

$assetPrefix = '/assets/';
if (strpos($url, $assetPrefix) === 0) {
    $base = realpath(__DIR__ . '/../src/assets');
    $file = realpath(__DIR__ . '/../src' . $url);
    if ($base && $file && strpos($file, $base) === 0 && is_file($file)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $types = [
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
        ];
        $contentType = $types[$ext] ?? 'application/octet-stream';
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
    http_response_code(404);
    echo '404';
    exit;
}