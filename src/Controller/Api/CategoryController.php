<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller\Api;

use App\ControleFinanceiro\Controller\AbstractController;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * Api/CategoryController - API REST para categorias
 * Métodos: index (listar), store (criar), show (detalhes), update (editar), delete (deletar)
 */
class CategoryController extends AbstractController
{
    private RequestHandler $request;

    public function __construct()
    {
        $this->request = new RequestHandler();
    }

    /**
     * GET /api/categories - Listar todas as categorias
     */
    public function index(): void
    {
        $this->requireAuth();

        $categories = [
            [
                'id' => 1,
                'name' => 'Alimentação',
                'type' => 'expense',
                'icon' => '🍔'
            ],
            [
                'id' => 2,
                'name' => 'Transporte',
                'type' => 'expense',
                'icon' => '🚗'
            ],
            [
                'id' => 3,
                'name' => 'Salário',
                'type' => 'income',
                'icon' => '💰'
            ]
        ];

        $this->json(['success' => true, 'data' => $categories]);
    }

    /**
     * POST /api/categories - Criar nova categoria
     */
    public function store(): void
    {
        $this->requireAuth();

        if (!$this->request->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $data = $this->request->json();

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Nome é obrigatório';
        }
        if (empty($data['type'])) {
            $errors['type'] = 'Tipo é obrigatório';
        }

        if (!empty($errors)) {
            http_response_code(400);
            $this->json(['success' => false, 'errors' => $errors]);
            return;
        }

        $category = [
            'id' => 4,
            'name' => $data['name'],
            'type' => $data['type'],
            'icon' => $data['icon'] ?? '📌'
        ];

        http_response_code(201);
        $this->json(['success' => true, 'data' => $category]);
    }

    /**
     * GET /api/categories/{id} - Detalhes de uma categoria
     */
    public function show(int $id): void
    {
        $this->requireAuth();

        $category = [
            'id' => $id,
            'name' => 'Alimentação',
            'type' => 'expense',
            'icon' => '🍔'
        ];

        $this->json(['success' => true, 'data' => $category]);
    }

    /**
     * PUT /api/categories/{id} - Atualizar categoria
     */
    public function update(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isPut()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $data = $this->request->json();

        $category = [
            'id' => $id,
            'name' => $data['name'] ?? 'Alimentação',
            'type' => $data['type'] ?? 'expense',
            'icon' => $data['icon'] ?? '🍔'
        ];

        $this->json(['success' => true, 'data' => $category]);
    }

    /**
     * DELETE /api/categories/{id} - Deletar categoria
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isDelete()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $this->json(['success' => true, 'message' => 'Categoria deletada com sucesso']);
    }
}
