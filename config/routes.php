<?php

include __DIR__ . "/../vendor/autoload.php";

use App\ControleFinanceiro\Controller\AuthController;
use App\ControleFinanceiro\Controller\AccountController;
use App\ControleFinanceiro\Controller\TransactionController;
use App\ControleFinanceiro\Controller\CategoryController;
use App\ControleFinanceiro\Controller\Api\DashboardController;
use App\ControleFinanceiro\Middleware\RequireAuth;

return [
    // Root redirect
    '/' => ['controller' => AuthController::class, 'action' => 'showLogin', 'method' => 'GET'],

    // Auth (processo, não CRUD)
    '/login' => ['controller' => AuthController::class, 'action' => 'showLogin', 'method' => 'GET'],
    '/register' => ['controller' => AuthController::class, 'action' => 'showRegister', 'method' => 'GET'],
    '/auth' => ['controller' => AuthController::class, 'action' => 'authenticate', 'method' => 'POST'],
    '/auth/register' => ['controller' => AuthController::class, 'action' => 'register', 'method' => 'POST'],
    '/logout' => ['controller' => AuthController::class, 'action' => 'logout', 'method' => 'GET'],
    '/dashboard' => ['controller' => AuthController::class, 'action' => 'dashboard', 'method' => 'GET', 'middleware' => [RequireAuth::class]],

    // API endpoints
    '/api/dashboard/summary' => ['controller' => DashboardController::class, 'action' => 'summary', 'method' => 'GET', 'middleware' => [RequireAuth::class]],

    // Account CRUD
    '/contas' => ['controller' => AccountController::class, 'action' => 'read', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/contas_create' => ['controller' => AccountController::class, 'action' => 'create', 'method' => 'POST', 'middleware' => [RequireAuth::class]],
    '/contas/criar' => ['controller' => AccountController::class, 'action' => 'showCreateForm', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/contas/{id}' => ['controller' => AccountController::class, 'action' => 'read', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/contas/{id}_update' => ['controller' => AccountController::class, 'action' => 'update', 'method' => 'PUT', 'middleware' => [RequireAuth::class]],
    '/contas/{id}_delete' => ['controller' => AccountController::class, 'action' => 'delete', 'method' => 'DELETE', 'middleware' => [RequireAuth::class]],
    '/contas/{id}/editar' => ['controller' => AccountController::class, 'action' => 'showEditForm', 'method' => 'GET', 'middleware' => [RequireAuth::class]],

    // Transaction CRUD
    '/lancamentos' => ['controller' => TransactionController::class, 'action' => 'read', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/lancamentos_create' => ['controller' => TransactionController::class, 'action' => 'create', 'method' => 'POST', 'middleware' => [RequireAuth::class]],
    '/lancamentos/criar' => ['controller' => TransactionController::class, 'action' => 'showCreateForm', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/lancamentos/{id}' => ['controller' => TransactionController::class, 'action' => 'read', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/lancamentos/{id}_update' => ['controller' => TransactionController::class, 'action' => 'update', 'method' => 'PUT', 'middleware' => [RequireAuth::class]],
    '/lancamentos/{id}_delete' => ['controller' => TransactionController::class, 'action' => 'delete', 'method' => 'DELETE', 'middleware' => [RequireAuth::class]],
    '/lancamentos/{id}/editar' => ['controller' => TransactionController::class, 'action' => 'showEditForm', 'method' => 'GET', 'middleware' => [RequireAuth::class]],

    // Category CRUD
    '/categorias' => ['controller' => CategoryController::class, 'action' => 'read', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/categorias_create' => ['controller' => CategoryController::class, 'action' => 'create', 'method' => 'POST', 'middleware' => [RequireAuth::class]],
    '/categorias/criar' => ['controller' => CategoryController::class, 'action' => 'showCreateForm', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/categorias/{id}' => ['controller' => CategoryController::class, 'action' => 'read', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
    '/categorias/{id}_update' => ['controller' => CategoryController::class, 'action' => 'update', 'method' => 'PUT', 'middleware' => [RequireAuth::class]],
    '/categorias/{id}_delete' => ['controller' => CategoryController::class, 'action' => 'delete', 'method' => 'DELETE', 'middleware' => [RequireAuth::class]],
    '/categorias/{id}/editar' => ['controller' => CategoryController::class, 'action' => 'showEditForm', 'method' => 'GET', 'middleware' => [RequireAuth::class]],
];
