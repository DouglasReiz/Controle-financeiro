<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller\Api;

use App\ControleFinanceiro\Controller\AbstractController;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * Api/TransactionController - API REST para lançamentos
 * Métodos: index (listar), store (criar), show (detalhes), update (editar), delete (deletar)
 */
class TransactionController extends AbstractController
{
    private RequestHandler $request;

    public function __construct()
    {
        $this->request = new RequestHandler();
    }

    /**
     * GET /api/transactions - Listar todos os lançamentos
     */
    public function index(): void
    {
        $this->requireAuth();

        $transactions = [
            [
                'id' => 1,
                'date' => '2025-02-08',
                'description' => 'Salário',
                'category' => 'Renda',
                'account' => 'Conta Corrente',
                'type' => 'income',
                'value' => 5000.00
            ],
            [
                'id' => 2,
                'date' => '2025-02-07',
                'description' => 'Supermercado',
                'category' => 'Alimentação',
                'account' => 'Débito',
                'type' => 'expense',
                'value' => 250.50
            ]
        ];

        $this->json(['success' => true, 'data' => $transactions]);
    }

    /**
     * POST /api/transactions - Criar novo lançamento
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
        if (empty($data['date'])) {
            $errors['date'] = 'Data é obrigatória';
        }
        if (empty($data['description'])) {
            $errors['description'] = 'Descrição é obrigatória';
        }
        if (empty($data['value'])) {
            $errors['value'] = 'Valor é obrigatório';
        }

        if (!empty($errors)) {
            http_response_code(400);
            $this->json(['success' => false, 'errors' => $errors]);
            return;
        }

        $transaction = [
            'id' => 3,
            'date' => $data['date'],
            'description' => $data['description'],
            'category' => $data['category'] ?? 'Outros',
            'account' => $data['account'] ?? 'Conta Corrente',
            'type' => $data['type'] ?? 'expense',
            'value' => $data['value']
        ];

        http_response_code(201);
        $this->json(['success' => true, 'data' => $transaction]);
    }

    /**
     * GET /api/transactions/{id} - Detalhes de um lançamento
     */
    public function show(int $id): void
    {
        $this->requireAuth();

        $transaction = [
            'id' => $id,
            'date' => '2025-02-08',
            'description' => 'Salário',
            'category' => 'Renda',
            'account' => 'Conta Corrente',
            'type' => 'income',
            'value' => 5000.00
        ];

        $this->json(['success' => true, 'data' => $transaction]);
    }

    /**
     * PUT /api/transactions/{id} - Atualizar lançamento
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

        $transaction = [
            'id' => $id,
            'date' => $data['date'] ?? '2025-02-08',
            'description' => $data['description'] ?? 'Salário',
            'category' => $data['category'] ?? 'Renda',
            'account' => $data['account'] ?? 'Conta Corrente',
            'type' => $data['type'] ?? 'income',
            'value' => $data['value'] ?? 5000.00
        ];

        $this->json(['success' => true, 'data' => $transaction]);
    }

    /**
     * DELETE /api/transactions/{id} - Deletar lançamento
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isDelete()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $this->json(['success' => true, 'message' => 'Lançamento deletado com sucesso']);
    }
}
