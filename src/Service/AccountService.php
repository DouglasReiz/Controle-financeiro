<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

final class AccountService
{
    public function validateAccountData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Nome é obrigatório';
        }

        if (empty($data['type'])) {
            $errors['type'] = 'Tipo é obrigatório';
        } elseif (!in_array($data['type'], ['checking', 'savings', 'investment'])) {
            $errors['type'] = 'Tipo inválido';
        }

        if (isset($data['balance']) && !is_numeric($data['balance'])) {
            $errors['balance'] = 'Saldo deve ser numérico';
        }

        return $errors;
    }

    public function getAllAccounts(int $userId): array
    {
        // Mock temporário - substituir por consulta ao banco
        return [
            ['id' => 1, 'name' => 'Conta Corrente', 'type' => 'checking', 'balance' => 3500.00],
            ['id' => 2, 'name' => 'Poupança', 'type' => 'savings', 'balance' => 8750.50],
        ];
    }

    public function getAccountById(int $id, int $userId): ?array
    {
        // Mock temporário - substituir por consulta ao banco
        return ['id' => $id, 'name' => 'Conta Corrente', 'type' => 'checking', 'balance' => 3500.00];
    }

    public function createAccount(array $data, int $userId): array
    {
        // Mock temporário - substituir por insert no banco
        return [
            'id' => 3,
            'name' => $data['name'],
            'type' => $data['type'],
            'balance' => $data['balance'] ?? 0,
        ];
    }

    public function updateAccount(int $id, array $data, int $userId): array
    {
        // Mock temporário - substituir por update no banco
        return [
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['type'],
            'balance' => $data['balance'] ?? 0,
        ];
    }

    public function deleteAccount(int $id, int $userId): bool
    {
        // Mock temporário - substituir por delete no banco
        return true;
    }
}
