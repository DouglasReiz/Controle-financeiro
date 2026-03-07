<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Http\RequestHandler;
use App\ControleFinanceiro\Repository\AccountRepository;
use App\ControleFinanceiro\Repository\CategoryRepository;
use App\ControleFinanceiro\Service\TransactionService;

class TransactionController extends AbstractController
{
    private TransactionService  $transactionService;
    private AccountRepository   $accountRepository;
    private CategoryRepository  $categoryRepository;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->transactionService = new TransactionService();
        $this->accountRepository  = new AccountRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    // Retorna contas + categorias do usuário para popular os selects do formulário
    public function formData(): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        $this->json([
            'success' => true,
            'data' => [
                'accounts'   => $this->accountRepository->findAllByUserId($userId),
                'categories' => $this->categoryRepository->findAllByUserId($userId),
            ]
        ]);
    }

    public function showCreateForm(): void
    {
        $this->requireAuth();
        $this->render('transactions/form', ['mode' => 'create']);
    }

    public function create(): void
    {
        $this->requireAuth();
        $data   = $this->request->json();
        $userId = $this->getAuthUser()->getId();

        $errors = $this->transactionService->validateTransactionData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $transaction = $this->transactionService->createTransaction($data, $userId);
        $this->respondCreated($transaction);
    }

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

    public function showEditForm(int $id): void
    {
        $this->requireAuth();
        $userId      = $this->getAuthUser()->getId();
        $transaction = $this->transactionService->getTransactionById($id, $userId);
        $this->render('transactions/form', ['mode' => 'edit', 'transaction' => $transaction]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $data   = $this->request->json();
        $userId = $this->getAuthUser()->getId();

        $errors = $this->transactionService->validateTransactionData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $transaction = $this->transactionService->updateTransaction($id, $data, $userId);
        $this->respondSuccess($transaction);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();
        $this->transactionService->deleteTransaction($id, $userId);
        $this->respondNoContent();
    }
}