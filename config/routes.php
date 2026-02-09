<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\AuthController;
use App\ControleFinanceiro\Controller\AccountController;
use App\ControleFinanceiro\Controller\TransactionController;
use App\ControleFinanceiro\Controller\CategoryController;
use App\ControleFinanceiro\Controller\DashboardController;
use App\ControleFinanceiro\Middleware\RequireAuth;

return [
    // Auth
    '/login'    => ['controller' => AuthController::class, 'action' => 'create'],
    '/auth'     => ['controller' => AuthController::class, 'action' => 'store'],
    '/register' => ['controller' => AuthController::class, 'action' => 'createRegister'],
    '/register-store' => ['controller' => AuthController::class, 'action' => 'storeRegister'],
    '/logout'   => ['controller' => AuthController::class, 'action' => 'delete'],

    // Dashboard
    '/dashboard' => [
        'controller' => DashboardController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],

    // Accounts
    '/contas' => [
        'controller' => AccountController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/criar' => [
        'controller' => AccountController::class,
        'action' => 'create',
        'middleware' => [RequireAuth::class]
    ],
    '/contas' => [
        'controller' => AccountController::class,
        'action' => 'store',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/{id}' => [
        'controller' => AccountController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/{id}/editar' => [
        'controller' => AccountController::class,
        'action' => 'edit',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/{id}' => [
        'controller' => AccountController::class,
        'action' => 'update',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/{id}' => [
        'controller' => AccountController::class,
        'action' => 'delete',
        'middleware' => [RequireAuth::class]
    ],

    // Transactions
    '/lancamentos' => [
        'controller' => TransactionController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/criar' => [
        'controller' => TransactionController::class,
        'action' => 'create',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos' => [
        'controller' => TransactionController::class,
        'action' => 'store',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/{id}' => [
        'controller' => TransactionController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/{id}/editar' => [
        'controller' => TransactionController::class,
        'action' => 'edit',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/{id}' => [
        'controller' => TransactionController::class,
        'action' => 'update',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/{id}' => [
        'controller' => TransactionController::class,
        'action' => 'delete',
        'middleware' => [RequireAuth::class]
    ],

    // Categories
    '/categorias' => [
        'controller' => CategoryController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/criar' => [
        'controller' => CategoryController::class,
        'action' => 'create',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias' => [
        'controller' => CategoryController::class,
        'action' => 'store',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/{id}' => [
        'controller' => CategoryController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/{id}/editar' => [
        'controller' => CategoryController::class,
        'action' => 'edit',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/{id}' => [
        'controller' => CategoryController::class,
        'action' => 'update',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/{id}' => [
        'controller' => CategoryController::class,
        'action' => 'delete',
        'middleware' => [RequireAuth::class]
    ],
];
