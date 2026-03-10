<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

use App\ControleFinanceiro\Repository\TransactionRepository;

final class TransactionService
{
    private TransactionRepository $repository;

    public function __construct()
    {
        $this->repository = new TransactionRepository();
    }

    public function validateTransactionData(array $data): array
    {
        $errors = [];

        if (empty($data['date'])) {
            $errors['date'] = 'Data é obrigatória';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Descrição é obrigatória';
        }

        if (empty($data['value']) || !is_numeric($data['value']) || $data['value'] <= 0) {
            $errors['value'] = 'Valor deve ser maior que zero';
        }

        if (empty($data['type']) || !in_array($data['type'], ['income', 'expense'])) {
            $errors['type'] = 'Tipo deve ser income ou expense';
        }

        if (empty($data['account_id']) || !is_numeric($data['account_id'])) {
            $errors['account_id'] = 'Conta é obrigatória';
        }

        if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
            $errors['category_id'] = 'Categoria é obrigatória';
        }

        return $errors;
    }

    public function getAllTransactions(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }

    public function getTransactionById(int $id, int $userId): ?array
    {
        return $this->repository->findByIdAndUserId($id, $userId);
    }

    public function createTransaction(array $data, int $userId): array
    {
        return $this->repository->insert($data, $userId);
    }

    public function updateTransaction(int $id, array $data, int $userId): ?array
    {
        return $this->repository->update($id, $data, $userId);
    }

    public function deleteTransaction(int $id, int $userId): bool
    {
        return $this->repository->delete($id, $userId);
    }
}
