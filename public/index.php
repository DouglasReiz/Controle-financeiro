<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/helpers.php';

use App\ControleFinanceiro\Http\RequestHandler;

try {
    $routes = require __DIR__ . '/../config/routes.php';

    if (!is_array($routes)) {
        die("Erro: O arquivo de rotas não retornou um array válido.");
    }

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($url !== '/' && substr($url, -1) === '/') {
        $url = rtrim($url, '/');
    }

    if (isset($routes[$url])) {
        $route = $routes[$url];
        
        // Executar middleware se existir
        if (isset($route['middleware']) && is_array($route['middleware'])) {
            foreach ($route['middleware'] as $middlewareClass) {
                $middlewareClass::handle();
            }
        }

        $controllerName = $route['controller'];
        $methodName = $route['action'];

        // Injeção de dependência: RequestHandler
        $request = new RequestHandler();
        $controller = new $controllerName($request);
        $controller->$methodName();
    } else {
        http_response_code(404);
        echo "404 - Rota não encontrada: " . htmlspecialchars($url);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Erro: " . $e->getMessage();
    error_log("Exception: " . $e->getMessage());
    error_log("Stack: " . $e->getTraceAsString());
}
