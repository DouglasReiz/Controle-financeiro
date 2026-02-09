<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

class AccountController extends AbstractController
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

            $account = [
                'id' => 3,
                'name' => $data['name'],
                'type' => $data['type'],
                'balance' => $data['balance'] ?? 0,
            ];

            http_response_code(201);
            $this->json(['success' => true, 'data' => $account]);
            return;
        }

        $this->render('accounts/form', ['mode' => 'create']);
    }

    public function read(?int $id = null): void
    {
        $this->requireAuth();

        if ($id) {
            $account = ['id' => $id, 'name' => 'Conta Corrente', 'type' => 'checking', 'balance' => 3500.00];

            if ($this->wantsJson()) {
                $this->json(['success' => true, 'data' => $account]);
                return;
            }

            $this->render('accounts/show', ['account' => $account]);
            return;
        }

        $accounts = [
            ['id' => 1, 'name' => 'Conta Corrente', 'type' => 'checking', 'balance' => 3500.00],
            ['id' => 2, 'name' => 'Poupança', 'type' => 'savings', 'balance' => 8750.50],
        ];

        if ($this->wantsJson()) {
            $this->json(['success' => true, 'data' => $accounts]);
            return;
        }

        $this->render('accounts/index', ['accounts' => $accounts]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();

        if ($this->request->isPut() || $this->request->isPost()) {
            $data = $this->request->json();

            $account = [
                'id' => $id,
                'name' => $data['name'] ?? 'Conta Corrente',
                'type' => $data['type'] ?? 'checking',
                'balance' => $data['balance'] ?? 3500.00,
            ];

            $this->json(['success' => true, 'data' => $account]);
            return;
        }

        $this->render('accounts/form', ['mode' => 'edit', 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isDelete() && !$this->request->isPost()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $this->json(['success' => true, 'message' => 'Conta deletada']);
    }
}
