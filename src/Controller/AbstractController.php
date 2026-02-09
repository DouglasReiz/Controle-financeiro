<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AuthSession;
use App\ControleFinanceiro\Service\AuthUser;

abstract class AbstractController
{
    protected function render(string $viewName, array $data = []): void
    {
        extract($data);
        include __DIR__ . "/../View/$viewName.php";
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function getAuthUser(): ?AuthUser
    {
        return AuthSession::get();
    }

    protected function isAuthenticated(): bool
    {
        return AuthSession::has();
    }

    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }
}
