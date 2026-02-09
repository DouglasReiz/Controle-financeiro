<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

/**
 * AccountController - Responsável por renderizar views de contas
 * Métodos: index (listar), create (formulário novo), show (detalhes), edit (formulário edição)
 * 
 * Nota: Lógica de CRUD fica em Api/AccountController
 * Este controller é apenas para renderizar HTML
 */
class AccountController extends AbstractController
{
    /**
     * GET /contas - Listar todas as contas
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->render('accounts/index');
    }

    /**
     * GET /contas/criar - Formulário para criar nova conta
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->render('accounts/form', ['mode' => 'create']);
    }

    /**
     * GET /contas/{id} - Detalhes de uma conta específica
     */
    public function show(int $id): void
    {
        $this->requireAuth();
        // Quando houver banco, buscar conta aqui
        // $account = $this->accountService->getById($id);
        // if (!$account) {
        //     http_response_code(404);
        //     return;
        // }
        $this->render('accounts/show', ['id' => $id]);
    }

    /**
     * GET /contas/{id}/editar - Formulário para editar conta
     */
    public function edit(int $id): void
    {
        $this->requireAuth();
        // Quando houver banco, buscar conta aqui
        // $account = $this->accountService->getById($id);
        // if (!$account) {
        //     http_response_code(404);
        //     return;
        // }
        $this->render('accounts/form', ['mode' => 'edit', 'id' => $id]);
    }
}
