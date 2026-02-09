<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AuthSession;
use App\ControleFinanceiro\Service\AuthUser;
use App\ControleFinanceiro\Http\RequestHandler;

abstract class AbstractController
{
    protected RequestHandler $request;

    public function __construct(RequestHandler $request)
    {
        $this->request = $request;
    }

    protected function render(string $viewName, array $data = []): void
    {
        extract($data);
        include __DIR__ . "/../View/$viewName.php";
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function requireAuth(): void
    {
        if (!AuthSession::has()) {
            if ($this->wantsJson()) {
                $this->respondUnauthorized();
                exit;
            }
            header('Location: /login');
            exit;
        }
    }

    protected function getAuthUser(): ?AuthUser
    {
        return AuthSession::get();
    }

    protected function wantsJson(): bool
    {
        return $this->request->wantsJson();
    }

    /**
     * Responde com erro de validação (400)
     * Padroniza formato de resposta para erros de validação
     */
    protected function respondValidationError(array $errors): void
    {
        $this->json(['success' => false, 'errors' => $errors], 400);
    }

    /**
     * Responde com sucesso de criação (201)
     * Padroniza formato de resposta para recursos criados
     */
    protected function respondCreated(array $data): void
    {
        $this->json(['success' => true, 'data' => $data], 201);
    }

    /**
     * Responde com sucesso de operação (200)
     * Padroniza formato de resposta para operações bem-sucedidas
     */
    protected function respondSuccess(array $data = []): void
    {
        $response = ['success' => true];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        $this->json($response);
    }

    /**
     * Responde com erro de método não permitido (405)
     * Padroniza formato de resposta para métodos HTTP inválidos
     */
    protected function respondMethodNotAllowed(): void
    {
        $this->json(['success' => false, 'message' => 'Método não permitido'], 405);
    }

    /**
     * Responde com erro de não autenticado (401)
     * Padroniza formato de resposta para requisições não autenticadas
     */
    protected function respondUnauthorized(): void
    {
        $this->json(['success' => false, 'message' => 'Não autenticado'], 401);
    }
}
