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
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
     * Responde com erro de validação (400 Bad Request)
     * Padrão REST: Requisição malformada ou dados inválidos
     * 
     * @param array $errors Mapa de campo => mensagem de erro
     */
    protected function respondValidationError(array $errors): void
    {
        $this->json([
            'success' => false,
            'errors' => $errors,
        ], 400);
    }

    /**
     * Responde com sucesso de criação (201 Created)
     * Padrão REST: Recurso criado com sucesso
     * 
     * @param array $data Dados do recurso criado
     */
    protected function respondCreated(array $data): void
    {
        $this->json([
            'success' => true,
            'data' => $data,
        ], 201);
    }

    /**
     * Responde com sucesso de operação (200 OK)
     * Padrão REST: Operação bem-sucedida
     * 
     * @param array $data Dados da resposta (opcional)
     */
    protected function respondSuccess(array $data = []): void
    {
        $response = ['success' => true];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        $this->json($response, 200);
    }

    /**
     * Responde com erro de método não permitido (405 Method Not Allowed)
     * Padrão REST: Método HTTP não suportado para este recurso
     */
    protected function respondMethodNotAllowed(): void
    {
        $this->json([
            'success' => false,
            'message' => 'Método não permitido',
        ], 405);
    }

    /**
     * Responde com erro de não autenticado (401 Unauthorized)
     * Padrão REST: Requisição requer autenticação
     */
    protected function respondUnauthorized(): void
    {
        $this->json([
            'success' => false,
            'message' => 'Não autenticado',
        ], 401);
    }

    /**
     * Responde com erro de não autorizado (403 Forbidden)
     * Padrão REST: Usuário autenticado mas sem permissão
     */
    protected function respondForbidden(): void
    {
        $this->json([
            'success' => false,
            'message' => 'Acesso negado',
        ], 403);
    }

    /**
     * Responde com erro de recurso não encontrado (404 Not Found)
     * Padrão REST: Recurso não existe
     */
    protected function respondNotFound(): void
    {
        $this->json([
            'success' => false,
            'message' => 'Recurso não encontrado',
        ], 404);
    }

    /**
     * Responde com erro genérico do servidor (500 Internal Server Error)
     * Padrão REST: Erro inesperado no servidor
     * 
     * @param string $message Mensagem de erro (opcional)
     */
    protected function respondServerError(string $message = 'Erro interno do servidor'): void
    {
        $this->json([
            'success' => false,
            'message' => $message,
        ], 500);
    }

    /**
     * Responde com erro de autenticação falha (401 Unauthorized)
     * Padrão REST: Credenciais inválidas
     * 
     * @param string $message Mensagem de erro
     */
    protected function respondAuthenticationFailed(string $message = 'Credenciais inválidas'): void
    {
        $this->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }

    /**
     * Verifica se é requisição de atualização (PUT ou POST)
     * Abstrai padrão repetido em controllers CRUD
     */
    protected function isUpdateRequest(): bool
    {
        return $this->request->isPut() || $this->request->isPost();
    }

    /**
     * Verifica se é requisição de deleção (DELETE ou POST)
     * Abstrai padrão repetido em controllers CRUD
     */
    protected function isDeleteRequest(): bool
    {
        return $this->request->isDelete() || $this->request->isPost();
    }

    /**
     * Responde com um recurso individual (HTML ou JSON)
     * Abstrai padrão repetido em controllers CRUD
     * 
     * @param array $resource Dados do recurso
     * @param string $viewName Nome da view para renderizar (ex: 'accounts/show')
     * @param string $dataKey Chave para passar dados à view (ex: 'account')
     */
    protected function respondResource(array $resource, string $viewName, string $dataKey): void
    {
        if ($this->wantsJson()) {
            $this->respondSuccess(['data' => $resource]);
            return;
        }

        $this->render($viewName, [$dataKey => $resource]);
    }

    /**
     * Responde com lista de recursos (HTML ou JSON)
     * Abstrai padrão repetido em controllers CRUD
     * 
     * @param array $resources Lista de recursos
     * @param string $viewName Nome da view para renderizar (ex: 'accounts/index')
     * @param string $dataKey Chave para passar dados à view (ex: 'accounts')
     */
    protected function respondResourceList(array $resources, string $viewName, string $dataKey): void
    {
        if ($this->wantsJson()) {
            $this->respondSuccess(['data' => $resources]);
            return;
        }

        $this->render($viewName, [$dataKey => $resources]);
    }

    /**
     * Extrai valor do request (JSON ou POST)
     * Abstrai padrão repetido em AuthController
     * 
     * @param string $key Chave do campo
     * @param string $default Valor padrão se não encontrado
     */
    protected function extractFromRequest(string $key, string $default = ''): string
    {
        $data = $this->request->json();
        return $data[$key] ?? $this->request->post($key, $default);
    }

    /**
     * Verifica se é requisição GET
     * Útil para métodos que precisam renderizar formulários
     */
    protected function isGetRequest(): bool
    {
        return $this->request->isGet();
    }

    /**
     * Responde com sucesso sem conteúdo (204 No Content)
     * Padrão REST: Operação bem-sucedida, sem dados para retornar
     * Usado em DELETE bem-sucedido
     */
    protected function respondNoContent(): void
    {
        http_response_code(204);
    }
}
