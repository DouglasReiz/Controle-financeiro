<?php

/**
 * Retorna a URL de uma rota baseada no nome definido em routes.php
 */

if (!function_exists('CreateRoute')) {
    function CreateRoute($controller, $action, $name = null)
    {
        return [
            'controller' => $controller,
            'action' => $action,
            'name' => $name
        ];
    }
}

function route($name)
{
    static $routes = null;
    if ($routes === null) {
        $routes = include __DIR__ . '/routes.php';
    }

    foreach ($routes as $path => $params) {
        if (isset($params['name']) && $params['name'] === $name) {
            return $path;
        }
    }

    // Caso o nome não exista, você pode lançar um erro ou retornar para a home
    return '/';
}