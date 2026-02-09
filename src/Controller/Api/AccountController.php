<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller\Api;

use App\ControleFinanceiro\Controller\AbstractController;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * Api/AccountController - API REST para contas
 * Métodos: index (listar), store (criar), show (detalhes), update (editar), delete (deletar)
 * 
 * Retorna JSON, não renderiza views
 */
class AccountController extends AbstractController
{
    private RequestHandler $request;

    public function __construct()
    {
        $this->request = new RequestHandler();
    }

    /**
     * GET /api/accounts - Listar todas as contas
     */
    public function index(): void
    {
        $this->requireAuth();

        // Mock temporário. Douglas, pode quebrar isso quando quiser
        $accounts = [
            [
                'id' => 1,
                'name' => 'Conta Corrente',
                'type' => 'checking',
                'balance' => 3500.00,
                'institution' => 'Banco XYZ'
            ],
            [
                'id' => 2,
                'name' => 'Poupança',
                'type' => 'savings',
                'balance' => 8750.50,
                'institution' => 'Banco XYZ'
            ]
        ];

        $this->json(['success' => true, 'data' => $accounts]);
    }

    /**
     * POST /api/accounts - Criar nova conta
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

        // Validação básica
        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Nome é obrigatório';
        }
        if (empty($data['type'])) {
            $errors['type'] = 'Tipo é obrigatório';
        }
        if (empty($data['institution'])) {
            $errors['institution'] = 'Instituição é obrigatória';
        }

        if (!empty($errors)) {
            http_response_code(400);
            $this->json(['success' => false, 'errors' => $errors]);
            return;
        }

        // Mock: retornar conta criada
        $account = [
            'id' => 3,
            'name' => $data['name'],
            'type' => $data['type'],
            'balance' => $data['balance'] ?? 0,
            'institution' => $data['institution']
        ];

        http_response_code(201);
        $this->json(['success' => true, 'data' => $account]);
    }

    /**
     * GET /api/accounts/{id} - Detalhes de uma conta
     */
    public function show(int $id): void
    {
        $this->requireAuth();

        // Mock: retornar conta
        $account = [
            'id' => $id,
            'name' => 'Conta Corrente',
            'type' => 'checking',
            'balance' => 3500.00,
            'institution' => 'Banco XYZ'
        ];

        $this->json(['success' => true, 'data' => $account]);
    }

    /**
     * PUT /api/accounts/{id} - Atualizar conta
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

        // Validação básica
        $errors = [];
        if (isset($data['name']) && empty($data['name'])) {
            $errors['name'] = 'Nome não pode estar vazio';
        }

        if (!empty($errors)) {
            http_response_code(400);
            $this->json(['success' => false, 'errors' => $errors]);
            return;
        }

        // Mock: retornar conta atualizada
        $account = [
            'id' => $id,
            'name' => $data['name'] ?? 'Conta Corrente',
            'type' => $data['type'] ?? 'checking',
            'balance' => $data['balance'] ?? 3500.00,
            'institution' => $data['institution'] ?? 'Banco XYZ'
        ];

        $this->json(['success' => true, 'data' => $account]);
    }

    /**
     * DELETE /api/accounts/{id} - Deletar conta
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->request->isDelete()) {
            http_response_code(405);
            $this->json(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        // Mock: deletar conta
        $this->json(['success' => true, 'message' => 'Conta deletada com sucesso']);
    }
}
