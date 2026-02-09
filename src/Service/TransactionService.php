<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

final class TransactionService
{
    public function validateTransactionData(array $data): array
    {
        $errors = [];

        if (empty($data['date'])) {
            $errors['date'] = 'Data é obrigatória';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Descrição é obrigatória';
        }

        if (empty($data['value'])) {
            $errors['value'] = 'Valor é obrigatório';
        } elseif (!is_numeric($data['value']) || $data['value'] <= 0) {
            $errors['value'] = 'Valor deve ser maior que zero';
        }

        if (isset($data['type']) && !in_array($data['type'], ['income', 'expense'])) {
            $errors['type'] = 'Tipo deve ser income ou expense';
        }

        return $errors;
    }

    public function getAllTransactions(int $userId): array
    {
        // Mock temporário - substituir por consulta ao banco
        return [
            ['id' => 1, 'date' => '2025-02-08', 'description' => 'Salário', 'value' => 5000.00, 'type' => 'income'],
            ['id' => 2, 'date' => '2025-02-07', 'description' => 'Supermercado', 'value' => 250.50, 'type' => 'expense'],
        ];
    }

    public function getTransactionById(int $id, int $userId): ?array
    {
        // Mock temporário - substituir por consulta ao banco
        return ['id' => $id, 'date' => '2025-02-08', 'description' => 'Salário', 'value' => 5000.00, 'type' => 'income'];
    }

    public function createTransaction(array $data, int $userId): array
    {
        // Mock temporário - substituir por insert no banco
        return [
            'id' => 3,
            'date' => $data['date'],
            'description' => $data['description'],
            'value' => $data['value'],
            'type' => $data['type'] ?? 'expense',
        ];
    }

    public function updateTransaction(int $id, array $data, int $userId): array
    {
        // Mock temporário - substituir por update no banco
        return [
            'id' => $id,
            'date' => $data['date'],
            'description' => $data['description'],
            'value' => $data['value'],
            'type' => $data['type'] ?? 'expense',
        ];
    }

    public function deleteTransaction(int $id, int $userId): bool
    {
        // Mock temporário - substituir por delete no banco
        return true;
    }
}
