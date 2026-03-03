<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

use App\ControleFinanceiro\Repository\AccountRepository;

final class AccountService
{
    private AccountRepository $repository;

    public function __construct()
    {
        $this->repository = new AccountRepository();
    }

    public function validateAccountData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Nome é obrigatório';
        }

        if (empty($data['type'])) {
            $errors['type'] = 'Tipo é obrigatório';
        } elseif (!in_array($data['type'], ['checking', 'savings', 'credit'])) {
            // ⚠️ Era 'investment' no original, mas o HTML usa 'credit' — padronizando
            $errors['type'] = 'Tipo inválido';
        }

        if (isset($data['balance']) && !is_numeric($data['balance'])) {
            $errors['balance'] = 'Saldo deve ser numérico';
        }

        return $errors;
    }

    public function getAllAccounts(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }

    public function getAccountById(int $id, int $userId): ?array
    {
        return $this->repository->findByIdAndUserId($id, $userId);
    }

    public function createAccount(array $data, int $userId): array
    {
        return $this->repository->insert($data, $userId);
    }

    public function updateAccount(int $id, array $data, int $userId): ?array
    {
        return $this->repository->update($id, $data, $userId);
    }

    public function deleteAccount(int $id, int $userId): bool
    {
        return $this->repository->delete($id, $userId);
    }
}
