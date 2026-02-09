<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\AuthController;
use App\ControleFinanceiro\Controller\AccountController;
use App\ControleFinanceiro\Controller\TransactionController;
use App\ControleFinanceiro\Controller\CategoryController;
use App\ControleFinanceiro\Middleware\RequireAuth;

return [
    // Auth CRUD
    '/login' => ['controller' => AuthController::class, 'action' => 'create'],
    '/register' => ['controller' => AuthController::class, 'action' => 'create'],
    '/auth' => ['controller' => AuthController::class, 'action' => 'update'],
    '/auth/me' => ['controller' => AuthController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],
    '/logout' => ['controller' => AuthController::class, 'action' => 'delete'],

    // Dashboard (consolidado em Auth)
    '/dashboard' => ['controller' => AuthController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],

    // Account CRUD
    '/contas' => ['controller' => AccountController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],
    '/contas/criar' => ['controller' => AccountController::class, 'action' => 'create', 'middleware' => [RequireAuth::class]],
    '/contas/{id}' => ['controller' => AccountController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],
    '/contas/{id}/editar' => ['controller' => AccountController::class, 'action' => 'update', 'middleware' => [RequireAuth::class]],
    '/contas/{id}/deletar' => ['controller' => AccountController::class, 'action' => 'delete', 'middleware' => [RequireAuth::class]],

    // Transaction CRUD
    '/lancamentos' => ['controller' => TransactionController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],
    '/lancamentos/criar' => ['controller' => TransactionController::class, 'action' => 'create', 'middleware' => [RequireAuth::class]],
    '/lancamentos/{id}' => ['controller' => TransactionController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],
    '/lancamentos/{id}/editar' => ['controller' => TransactionController::class, 'action' => 'update', 'middleware' => [RequireAuth::class]],
    '/lancamentos/{id}/deletar' => ['controller' => TransactionController::class, 'action' => 'delete', 'middleware' => [RequireAuth::class]],

    // Category CRUD
    '/categorias' => ['controller' => CategoryController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],
    '/categorias/criar' => ['controller' => CategoryController::class, 'action' => 'create', 'middleware' => [RequireAuth::class]],
    '/categorias/{id}' => ['controller' => CategoryController::class, 'action' => 'read', 'middleware' => [RequireAuth::class]],
    '/categorias/{id}/editar' => ['controller' => CategoryController::class, 'action' => 'update', 'middleware' => [RequireAuth::class]],
    '/categorias/{id}/deletar' => ['controller' => CategoryController::class, 'action' => 'delete', 'middleware' => [RequireAuth::class]],
];
