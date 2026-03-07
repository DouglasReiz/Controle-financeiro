<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

use App\ControleFinanceiro\Repository\CategoryRepository;

final class CategoryService
{
    private CategoryRepository $repository;

    public function __construct()
    {
        $this->repository = new CategoryRepository();
    }

    public function validateCategoryData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Nome é obrigatório';
        }

        if (empty($data['type'])) {
            $errors['type'] = 'Tipo é obrigatório';
        } elseif (!in_array($data['type'], ['income', 'expense', 'transfer'])) {
            $errors['type'] = 'Tipo inválido';
        }

        return $errors;
    }

    public function getAllCategories(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }

    public function getCategoryById(int $id, int $userId): ?array
    {
        return $this->repository->findByIdAndUserId($id, $userId);
    }

    public function createCategory(array $data, int $userId): array
    {
        return $this->repository->insert($data, $userId);
    }

    public function updateCategory(int $id, array $data, int $userId): ?array
    {
        return $this->repository->update($id, $data, $userId);
    }

    public function deleteCategory(int $id, int $userId): bool
    {
        return $this->repository->delete($id, $userId);
    }
}
