<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\IndexController;
use \App\ControleFinanceiro\Controller\AuthController;
use \App\ControleFinanceiro\Controller\UserController;

$requestUri = $_SERVER['REQUEST_URI'];
$urlParts = explode('?', $requestUri);
$url = $urlParts[0];



return [
    '/'         => CreateRoute(AuthController::class, 'indexAction', 'home'),
    '/login'    => CreateRoute(IndexController::class, 'loginAction', 'login.form'),
    '/register' => CreateRoute(UserController::class, 'register', 'register'),
    '/auth'     => CreateRoute(AuthController::class, 'login', 'login.post'),
];