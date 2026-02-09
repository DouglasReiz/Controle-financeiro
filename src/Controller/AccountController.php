<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Http\RequestHandler;

class AccountController extends AbstractController
{
    private RequestHandler $request;

    public function __construct()
    {
        $this->request = new RequestHandler();
    }

    public function index(): void
    {
        $this->requireAuth();
        
        // Mock: quando houver banco, buscar de verdade
        $accounts = [
            ['id' => 1, 'name' => 'Conta Corrente', 'type' => 'checking', 'balance' => 3500.00],
            ['id' => 2, 'name' => 'Poupança', 'type' => 'savings', 'balance' => 8750.50],
        ];

        if ($this->request->wantsJson()) {
            $this->json(['success' => true, 'data' => $accounts]);
            return;
        }

        $this->render('accounts/index', ['accounts' => $accounts]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('accounts/form', ['mode' => 'create']);
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

        // Mock: quando houver banco, salvar de verdade
        $account = [
            'id' => 3,
            'name' => $data['name'],
            'type' => $data['type'],
            'balance' => $data['balance'] ?? 0,
        ];

        http_response_code(201);
        $this->json(['success' => true, 'data' => $account]);
    }

    public function show(int $id): void
    {
        $this->requireAuth();

        // Mock: quando houver banco, buscar de verdade
        $account = ['id' => $id, 'name' => 'Conta Corrente', 'type' => 'checking', 'balance' => 3500.00];

        if ($this->request->wantsJson()) {
            $this->json(['success' => true, 'data' => $account]);
            return;
        }

        $this->render('accounts/show', ['account' => $account]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $this->render('accounts/form', ['mode' => 'edit', 'id' => $id]);
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

        // Mock: quando houver banco, atualizar de verdade
        $account = [
            'id' => $id,
            'name' => $data['name'] ?? 'Conta Corrente',
            'type' => $data['type'] ?? 'checking',
            'balance' => $data['balance'] ?? 3500.00,
        ];

        $this->json(['success' => true, 'data' => $account]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isDelete()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        // Mock: quando houver banco, deletar de verdade
        $this->json(['success' => true, 'message' => 'Conta deletada']);
    }
}
