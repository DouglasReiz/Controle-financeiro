<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Middleware;

use App\ControleFinanceiro\Service\AuthSession;

/**
 * Middleware de autenticação.
 * Redireciona para /login se não autenticado.
 */
final class RequireAuth
{
    public static function handle(): void
    {
        if (!AuthSession::has()) {
            header('Location: /login');
            exit;
        }
    }
}
