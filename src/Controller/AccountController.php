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

    public function read(?int $id = null): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        if ($id) {
            $account = $this->accountService->getAccountById($id, $userId);
            $this->respondResource($account);
            return;
        }

        $accounts = $this->accountService->getAllAccounts($userId);
        $this->respondResourceList('accounts/index', $accounts);
    }

    public function update(int $id): void
    {
        $this->requireAuth();

        if (!$this->isUpdateRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

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

    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->isDeleteRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $userId = $this->getAuthUser()->getId();
        $this->accountService->deleteAccount($id, $userId);

        $this->respondSuccess(['message' => 'Conta deletada']);
    }

    /**
     * Verifica se é requisição de atualização (PUT ou POST)
     */
    private function isUpdateRequest(): bool
    {
        return $this->request->isPut() || $this->request->isPost();
    }

    /**
     * Verifica se é requisição de deleção (DELETE ou POST)
     */
    private function isDeleteRequest(): bool
    {
        return $this->request->isDelete() || $this->request->isPost();
    }

    /**
     * Responde com um recurso individual (HTML ou JSON)
     */
    private function respondResource(array $resource): void
    {
        if ($this->wantsJson()) {
            $this->respondSuccess(['data' => $resource]);
            return;
        }

        $this->render('accounts/show', ['account' => $resource]);
    }

    /**
     * Responde com lista de recursos (HTML ou JSON)
     */
    private function respondResourceList(string $viewName, array $resources): void
    {
        if ($this->wantsJson()) {
            $this->respondSuccess(['data' => $resources]);
            return;
        }

        $this->render($viewName, ['accounts' => $resources]);
    }
}
