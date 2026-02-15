<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AccountService;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * AccountController - CRUD de Contas (Recurso)
 * 
 * Padrão REST explícito por HTTP method:
 * - GET /contas → read() → Lista todas as contas
 * - GET /contas/criar → showCreateForm() → Formulário de criação
 * - POST /contas → create() → Cria nova conta
 * - GET /contas/{id} → read($id) → Detalhe de uma conta
 * - GET /contas/{id}/editar → showEditForm($id) → Formulário de edição
 * - PUT /contas/{id} → update($id) → Atualiza conta
 * - DELETE /contas/{id} → delete($id) → Deleta conta
 * 
 * Responsabilidades:
 * - Receber request e extrair dados
 * - Delegar validação e lógica ao AccountService
 * - Responder com HTML (views) ou JSON (API)
 * - Gerenciar autenticação via middleware
 * 
 * Padrão: Cada método HTTP tem seu próprio método no controller
 * Sem if statements para verificar método HTTP (roteamento via routes.php)
 */
class AccountController extends AbstractController
{
    private AccountService $accountService;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->accountService = new AccountService();
    }

    /**
     * GET /contas/criar
     * Exibe formulário de criação de conta
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Renderizar view com formulário vazio
     */
    public function showCreateForm(): void
    {
        $this->requireAuth();
        $this->render('accounts/form', ['mode' => 'create']);
    }

    /**
     * POST /contas
     * Cria nova conta
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Extrair dados do request
     * 3. Delegar validação ao AccountService
     * 4. Delegar criação ao AccountService
     * 5. Responder com 201 Created (JSON)
     * 
     * Respostas:
     * - 201 Created: Conta criada com sucesso
     * - 400 Bad Request: Dados inválidos
     */
    public function create(): void
    {
        $this->requireAuth();

        $data = $this->request->json();
        $userId = $this->getAuthUser()->getId();

        $errors = $this->accountService->validateAccountData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $account = $this->accountService->createAccount($data, $userId);
        $this->respondCreated($account);
    }

    /**
     * GET /contas ou GET /contas/{id}
     * Lista todas as contas ou retorna uma específica
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Se {id} fornecido: buscar conta específica
     * 3. Se sem {id}: buscar todas as contas
     * 4. Responder com HTML (view) ou JSON (API)
     * 
     * Respostas:
     * - 200 OK: Dados retornados (HTML ou JSON)
     * - 401 Unauthorized: Não autenticado
     */
    public function read(?int $id = null): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        if ($id) {
            $account = $this->accountService->getAccountById($id, $userId);
            $this->respondResource($account, 'accounts/show', 'account');
            return;
        }

        $accounts = $this->accountService->getAllAccounts($userId);
        $this->respondResourceList($accounts, 'accounts/index', 'accounts');
    }

    /**
     * GET /contas/{id}/editar
     * Exibe formulário de edição de conta
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Buscar conta pelo ID
     * 3. Renderizar view com dados da conta
     */
    public function showEditForm(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        $account = $this->accountService->getAccountById($id, $userId);
        $this->render('accounts/form', ['mode' => 'edit', 'account' => $account]);
    }

    /**
     * PUT /contas/{id}
     * Atualiza conta
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Extrair dados do request
     * 3. Delegar validação ao AccountService
     * 4. Delegar atualização ao AccountService
     * 5. Responder com 200 OK (JSON)
     * 
     * Respostas:
     * - 200 OK: Conta atualizada com sucesso
     * - 400 Bad Request: Dados inválidos
     */
    public function update(int $id): void
    {
        $this->requireAuth();

        $data = $this->request->json();
        $userId = $this->getAuthUser()->getId();

        $errors = $this->accountService->validateAccountData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $account = $this->accountService->updateAccount($id, $data, $userId);
        $this->respondSuccess(['data' => $account]);
    }

    /**
     * DELETE /contas/{id}
     * Deleta conta
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Delegar deleção ao AccountService
     * 3. Responder com 204 No Content
     * 
     * Respostas:
     * - 204 No Content: Deletado com sucesso
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        $userId = $this->getAuthUser()->getId();
        $this->accountService->deleteAccount($id, $userId);

        $this->respondNoContent();
    }
}
