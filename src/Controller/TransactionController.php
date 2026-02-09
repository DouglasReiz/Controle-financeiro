<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\TransactionService;
use App\ControleFinanceiro\Http\RequestHandler;

class TransactionController extends AbstractController
{
    private TransactionService $transactionService;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->transactionService = new TransactionService();
    }

    public function create(): void
    {
        $this->requireAuth();

        if ($this->request->isPost()) {
            $data = $this->request->json();
            $userId = $this->getAuthUser()->getId();

            $errors = $this->transactionService->validateTransactionData($data);
            if (!empty($errors)) {
                $this->respondValidationError($errors);
                return;
            }

            $transaction = $this->transactionService->createTransaction($data, $userId);
            $this->respondCreated($transaction);
            return;
        }

        $this->render('transactions/form', ['mode' => 'create']);
    }

    public function read(?int $id = null): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        if ($id) {
            $transaction = $this->transactionService->getTransactionById($id, $userId);
            $this->respondResource($transaction);
            return;
        }

        $transactions = $this->transactionService->getAllTransactions($userId);
        $this->respondResourceList('transactions/index', $transactions);
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

        $errors = $this->transactionService->validateTransactionData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $transaction = $this->transactionService->updateTransaction($id, $data, $userId);
        $this->respondSuccess(['data' => $transaction]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->isDeleteRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $userId = $this->getAuthUser()->getId();
        $this->transactionService->deleteTransaction($id, $userId);

        $this->respondSuccess(['message' => 'Lançamento deletado']);
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

        $this->render('transactions/show', ['transaction' => $resource]);
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

        $this->render($viewName, ['transactions' => $resources]);
    }
}
