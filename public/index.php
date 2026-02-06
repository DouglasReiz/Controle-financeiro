<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\IndexController;

$requestUri = $_SERVER['REQUEST_URI'];
$urlParts = explode('?', $requestUri);
$url = $urlParts[0];

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
