<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AccountService;
use App\ControleFinanceiro\Http\RequestHandler;

class AccountController extends AbstractController
{
    private AccountService $accountService;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->accountService = new AccountService();
    }

    /**
     * GET /contas/criar → Exibe formulário
     * POST /contas/criar → Cria nova conta
     * 
     * Respostas POST:
     * - 201 Created: Conta criada
     * - 400 Bad Request: Dados inválidos
     */
    public function create(): void
    {
        $this->requireAuth();

        if ($this->request->isPost()) {
            $data = $this->request->json();
            $userId = $this->getAuthUser()->getId();

            $errors = $this->accountService->validateAccountData($data);
            if (!empty($errors)) {
                $this->respondValidationError($errors);
                return;
            }

            $account = $this->accountService->createAccount($data, $userId);
            $this->respondCreated($account);
            return;
        }

        $this->render('accounts/form', ['mode' => 'create']);
    }

    /**
     * GET /contas → Lista todas as contas
     * GET /contas/{id} → Retorna uma conta específica
     * 
     * Respostas:
     * - 200 OK: Dados retornados
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
     * GET /contas/{id}/editar → Exibe formulário
     * PUT /contas/{id}/editar → Atualiza conta
     * 
     * Respostas:
     * - 200 OK: Formulário (GET) ou Conta atualizada (PUT)
     * - 400 Bad Request: Dados inválidos (PUT)
     * - 405 Method Not Allowed: Método inválido
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        // GET: Exibir formulário de edição
        if ($this->isGetRequest()) {
            $account = $this->accountService->getAccountById($id, $userId);
            $this->render('accounts/form', ['mode' => 'edit', 'account' => $account]);
            return;
        }

        // PUT/POST: Atualizar dados
        if (!$this->isUpdateRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $data = $this->request->json();

        $errors = $this->accountService->validateAccountData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $account = $this->accountService->updateAccount($id, $data, $userId);
        $this->respondSuccess(['data' => $account]);
    }

    /**
     * DELETE /contas/{id}/deletar → Deleta conta
     * 
     * Respostas:
     * - 204 No Content: Deletado com sucesso
     * - 405 Method Not Allowed: Método inválido
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->isDeleteRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $userId = $this->getAuthUser()->getId();
        $this->accountService->deleteAccount($id, $userId);

        $this->respondNoContent();
    }
}
