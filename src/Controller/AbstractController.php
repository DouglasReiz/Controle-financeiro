<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AuthSession;
use App\ControleFinanceiro\Service\AuthUser;
use App\ControleFinanceiro\Http\RequestHandler;

abstract class AbstractController
{
    protected RequestHandler $request;

    public function __construct()
    {
        $this->request = new RequestHandler();
    }

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

    protected function wantsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return strpos($accept, 'application/json') !== false;
    }

    protected function validateRequired(array $data, array $fields): array
    {
        $errors = [];
        foreach ($fields as $field => $label) {
            if (empty($data[$field])) {
                $errors[$field] = "$label é obrigatório";
            }
        }
        return $errors;
    }
}
