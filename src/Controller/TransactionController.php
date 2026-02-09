<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Http\RequestHandler;

class TransactionController extends AbstractController
{
    private RequestHandler $request;

    public function __construct()
    {
        $this->request = new RequestHandler();
    }

    public function index(): void
    {
        $this->requireAuth();

        $transactions = [
            ['id' => 1, 'date' => '2025-02-08', 'description' => 'Salário', 'value' => 5000.00, 'type' => 'income'],
            ['id' => 2, 'date' => '2025-02-07', 'description' => 'Supermercado', 'value' => 250.50, 'type' => 'expense'],
        ];

        if ($this->request->wantsJson()) {
            $this->json(['success' => true, 'data' => $transactions]);
            return;
        }

        $this->render('transactions/index', ['transactions' => $transactions]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('transactions/form', ['mode' => 'create']);
    }

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
            'value' => $data['value'],
            'type' => $data['type'] ?? 'expense',
        ];

        http_response_code(201);
        $this->json(['success' => true, 'data' => $transaction]);
    }

    public function show(int $id): void
    {
        $this->requireAuth();

        $transaction = ['id' => $id, 'date' => '2025-02-08', 'description' => 'Salário', 'value' => 5000.00, 'type' => 'income'];

        if ($this->request->wantsJson()) {
            $this->json(['success' => true, 'data' => $transaction]);
            return;
        }

        $this->render('transactions/show', ['transaction' => $transaction]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $this->render('transactions/form', ['mode' => 'edit', 'id' => $id]);
    }

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
            'value' => $data['value'] ?? 5000.00,
            'type' => $data['type'] ?? 'income',
        ];

        $this->json(['success' => true, 'data' => $transaction]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isDelete()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $this->json(['success' => true, 'message' => 'Lançamento deletado']);
    }
}
