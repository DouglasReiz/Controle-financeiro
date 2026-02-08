<?php

/**
 * Middleware de autenticação: usa AuthSession como fonte da verdade.
 * Bloqueia acesso a rotas protegidas quando o usuário não está autenticado.
 */

use App\ControleFinanceiro\Service\AuthSession;

if (!function_exists('requireAuth')) {
    function requireAuth(): void
    {
        if (!AuthSession::has()) {
            header('Location: ' . route('login.form'));
            exit;
        }
    }
}
