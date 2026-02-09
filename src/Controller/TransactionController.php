<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\TransactionService;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * TransactionController - CRUD de Lançamentos (Recurso)
 * 
 * Padrão REST explícito por HTTP method:
 * - GET /lancamentos → read() → Lista todos os lançamentos
 * - GET /lancamentos/criar → showCreateForm() → Formulário de criação
 * - POST /lancamentos → create() → Cria novo lançamento
 * - GET /lancamentos/{id} → read($id) → Detalhe de um lançamento
 * - GET /lancamentos/{id}/editar → showEditForm($id) → Formulário de edição
 * - PUT /lancamentos/{id} → update($id) → Atualiza lançamento
 * - DELETE /lancamentos/{id} → delete($id) → Deleta lançamento
 * 
 * Responsabilidades:
 * - Receber request e extrair dados
 * - Delegar validação e lógica ao TransactionService
 * - Responder com HTML (views) ou JSON (API)
 * - Gerenciar autenticação via middleware
 * 
 * Padrão: Cada método HTTP tem seu próprio método no controller
 * Sem if statements para verificar método HTTP (roteamento via routes.php)
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
     * GET /lancamentos/criar
     * Exibe formulário de criação de lançamento
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Renderizar view com formulário vazio
     */
    public function showCreateForm(): void
    {
        $this->requireAuth();
        $this->render('transactions/form', ['mode' => 'create']);
    }

    /**
     * POST /lancamentos
     * Cria novo lançamento
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Extrair dados do request
     * 3. Delegar validação ao TransactionService
     * 4. Delegar criação ao TransactionService
     * 5. Responder com 201 Created (JSON)
     * 
     * Respostas:
     * - 201 Created: Lançamento criado com sucesso
     * - 400 Bad Request: Dados inválidos
     */
    public function create(): void
    {
        $this->requireAuth();

        $data = $this->request->json();
        $userId = $this->getAuthUser()->getId();

        $errors = $this->transactionService->validateTransactionData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $transaction = $this->transactionService->createTransaction($data, $userId);
        $this->respondCreated($transaction);
    }

    /**
     * GET /lancamentos ou GET /lancamentos/{id}
     * Lista todos os lançamentos ou retorna um específico
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Se {id} fornecido: buscar lançamento específico
     * 3. Se sem {id}: buscar todos os lançamentos
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
            $transaction = $this->transactionService->getTransactionById($id, $userId);
            $this->respondResource($transaction, 'transactions/show', 'transaction');
            return;
        }

        $transactions = $this->transactionService->getAllTransactions($userId);
        $this->respondResourceList($transactions, 'transactions/index', 'transactions');
    }

    /**
     * GET /lancamentos/{id}/editar
     * Exibe formulário de edição de lançamento
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Buscar lançamento pelo ID
     * 3. Renderizar view com dados do lançamento
     */
    public function showEditForm(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        $transaction = $this->transactionService->getTransactionById($id, $userId);
        $this->render('transactions/form', ['mode' => 'edit', 'transaction' => $transaction]);
    }

    /**
     * PUT /lancamentos/{id}
     * Atualiza lançamento
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Extrair dados do request
     * 3. Delegar validação ao TransactionService
     * 4. Delegar atualização ao TransactionService
     * 5. Responder com 200 OK (JSON)
     * 
     * Respostas:
     * - 200 OK: Lançamento atualizado com sucesso
     * - 400 Bad Request: Dados inválidos
     */
    public function update(int $id): void
    {
        $this->requireAuth();

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

    /**
     * DELETE /lancamentos/{id}
     * Deleta lançamento
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Delegar deleção ao TransactionService
     * 3. Responder com 204 No Content
     * 
     * Respostas:
     * - 204 No Content: Deletado com sucesso
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        $userId = $this->getAuthUser()->getId();
        $this->transactionService->deleteTransaction($id, $userId);

        $this->respondNoContent();
    }
}
