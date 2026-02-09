<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\TransactionService;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * TransactionController - CRUD de Lançamentos
 * 
 * Padrão REST:
 * - GET /lancamentos → read() → 200 HTML (lista) ou 200 JSON
 * - GET /lancamentos/criar → create() → 200 HTML (formulário)
 * - POST /lancamentos/criar → create() → 201 JSON (criado) ou 400 JSON (erro)
 * - GET /lancamentos/{id} → read($id) → 200 HTML (detalhe) ou 200 JSON
 * - GET /lancamentos/{id}/editar → update($id) → 200 HTML (formulário)
 * - PUT /lancamentos/{id}/editar → update($id) → 200 JSON ou 400 JSON (erro)
 * - DELETE /lancamentos/{id}/deletar → delete($id) → 204 No Content ou 400 JSON
 */
class TransactionController extends AbstractController
{
    private TransactionService $transactionService;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->transactionService = new TransactionService();
    }

    /**
     * GET /lancamentos/criar → Exibe formulário
     * POST /lancamentos/criar → Cria novo lançamento
     * 
     * Respostas POST:
     * - 201 Created: Lançamento criado
     * - 400 Bad Request: Dados inválidos
     */
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

    /**
     * GET /lancamentos → Lista todos os lançamentos
     * GET /lancamentos/{id} → Retorna um lançamento específico
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
            $transaction = $this->transactionService->getTransactionById($id, $userId);
            $this->respondResource($transaction, 'transactions/show', 'transaction');
            return;
        }

        $transactions = $this->transactionService->getAllTransactions($userId);
        $this->respondResourceList($transactions, 'transactions/index', 'transactions');
    }

    /**
     * GET /lancamentos/{id}/editar → Exibe formulário
     * PUT /lancamentos/{id}/editar → Atualiza lançamento
     * 
     * Respostas:
     * - 200 OK: Formulário (GET) ou Lançamento atualizado (PUT)
     * - 400 Bad Request: Dados inválidos (PUT)
     * - 405 Method Not Allowed: Método inválido
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        // GET: Exibir formulário de edição
        if ($this->isGetRequest()) {
            $transaction = $this->transactionService->getTransactionById($id, $userId);
            $this->render('transactions/form', ['mode' => 'edit', 'transaction' => $transaction]);
            return;
        }

        // PUT/POST: Atualizar dados
        if (!$this->isUpdateRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $data = $this->request->json();

        $errors = $this->transactionService->validateTransactionData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $transaction = $this->transactionService->updateTransaction($id, $data, $userId);
        $this->respondSuccess(['data' => $transaction]);
    }

    /**
     * DELETE /lancamentos/{id}/deletar → Deleta lançamento
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
        $this->transactionService->deleteTransaction($id, $userId);

        $this->respondNoContent();
    }
}
