<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AuthSession;

class LogoutController extends AbstractController
{
    public function logoutAction(): void
    {
        AuthSession::clear();
        header('Location: /login');
        exit;
    }
}
