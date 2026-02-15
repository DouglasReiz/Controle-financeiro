<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

final class CategoryService
{
    public function validateCategoryData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Nome é obrigatório';
        }

        if (empty($data['type'])) {
            $errors['type'] = 'Tipo é obrigatório';
        } elseif (!in_array($data['type'], ['income', 'expense'])) {
            $errors['type'] = 'Tipo deve ser income ou expense';
        }

        return $errors;
    }

    public function getAllCategories(int $userId): array
    {
        // Mock temporário - substituir por consulta ao banco
        return [
            ['id' => 1, 'name' => 'Alimentação', 'type' => 'expense', 'icon' => '🍔'],
            ['id' => 2, 'name' => 'Transporte', 'type' => 'expense', 'icon' => '🚗'],
            ['id' => 3, 'name' => 'Salário', 'type' => 'income', 'icon' => '💰'],
        ];
    }

    public function getCategoryById(int $id, int $userId): ?array
    {
        // Mock temporário - substituir por consulta ao banco
        return ['id' => $id, 'name' => 'Alimentação', 'type' => 'expense', 'icon' => '🍔'];
    }

    public function createCategory(array $data, int $userId): array
    {
        // Mock temporário - substituir por insert no banco
        return [
            'id' => 4,
            'name' => $data['name'],
            'type' => $data['type'],
            'icon' => $data['icon'] ?? '📌',
        ];
    }

    public function updateCategory(int $id, array $data, int $userId): array
    {
        // Mock temporário - substituir por update no banco
        return [
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['type'],
            'icon' => $data['icon'] ?? '📌',
        ];
    }

    public function deleteCategory(int $id, int $userId): bool
    {
        // Mock temporário - substituir por delete no banco
        return true;
    }
}
