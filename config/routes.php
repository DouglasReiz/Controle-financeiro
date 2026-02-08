<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\IndexController;
use App\ControleFinanceiro\Controller\AuthController;
use App\ControleFinanceiro\Controller\UserController;
use App\ControleFinanceiro\Controller\LogoutController;
use App\ControleFinanceiro\Controller\Api\DashboardController;
use App\ControleFinanceiro\Middleware\RequireAuth;

return [
    '/'         => CreateRoute(AuthController::class, 'indexAction', 'home'),
    '/dashboard' => [
        'controller' => IndexController::class,
        'action' => 'dashboardAction',
        'name' => 'dashboard',
        'middleware' => [RequireAuth::class]
    ],
    '/login'    => CreateRoute(IndexController::class, 'loginAction', 'login.form'),
    '/register' => CreateRoute(UserController::class, 'registerAction', 'register'),
    '/auth'     => CreateRoute(AuthController::class, 'authAction', 'login.post'),
    '/logout'   => CreateRoute(LogoutController::class, 'logoutAction', 'logout'),
    '/api/dashboard/summary' => [
        'controller' => DashboardController::class,
        'action' => 'summaryAction',
        'name' => 'api.dashboard.summary',
        'middleware' => [RequireAuth::class]
    ],
];
