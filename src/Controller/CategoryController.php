<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

class CategoryController extends AbstractController
{
    public function create(): void
    {
        $this->requireAuth();

        if ($this->request->isPost()) {
            $data = $this->request->json();

            $errors = $this->validateRequired($data, [
                'name' => 'Nome',
                'type' => 'Tipo'
            ]);

            if (!empty($errors)) {
                http_response_code(400);
                $this->json(['success' => false, 'errors' => $errors]);
                return;
            }

            $category = [
                'id' => 4,
                'name' => $data['name'],
                'type' => $data['type'],
                'icon' => $data['icon'] ?? '📌',
            ];

            http_response_code(201);
            $this->json(['success' => true, 'data' => $category]);
            return;
        }

        $this->render('categories/form', ['mode' => 'create']);
    }

    public function read(?int $id = null): void
    {
        $this->requireAuth();

        if ($id) {
            $category = ['id' => $id, 'name' => 'Alimentação', 'type' => 'expense', 'icon' => '🍔'];

            if ($this->wantsJson()) {
                $this->json(['success' => true, 'data' => $category]);
                return;
            }

            $this->render('categories/show', ['category' => $category]);
            return;
        }

        $categories = [
            ['id' => 1, 'name' => 'Alimentação', 'type' => 'expense', 'icon' => '🍔'],
            ['id' => 2, 'name' => 'Transporte', 'type' => 'expense', 'icon' => '🚗'],
            ['id' => 3, 'name' => 'Salário', 'type' => 'income', 'icon' => '💰'],
        ];

        if ($this->wantsJson()) {
            $this->json(['success' => true, 'data' => $categories]);
            return;
        }

        $this->render('categories/index', ['categories' => $categories]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();

        if ($this->request->isPut() || $this->request->isPost()) {
            $data = $this->request->json();

            $category = [
                'id' => $id,
                'name' => $data['name'] ?? 'Alimentação',
                'type' => $data['type'] ?? 'expense',
                'icon' => $data['icon'] ?? '🍔',
            ];

            $this->json(['success' => true, 'data' => $category]);
            return;
        }

        $this->render('categories/form', ['mode' => 'edit', 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isDelete() && !$this->request->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $this->json(['success' => true, 'message' => 'Categoria deletada']);
    }
}
