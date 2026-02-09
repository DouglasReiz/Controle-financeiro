<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

/**
 * TransactionController - Responsável por renderizar views de lançamentos
 * Métodos: index (listar), create (formulário novo), show (detalhes), edit (formulário edição)
 */
class TransactionController extends AbstractController
{
    /**
     * GET /lancamentos - Listar todos os lançamentos
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->render('transactions/index');
    }

    /**
     * GET /lancamentos/criar - Formulário para criar novo lançamento
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->render('transactions/form', ['mode' => 'create']);
    }

    /**
     * GET /lancamentos/{id} - Detalhes de um lançamento específico
     */
    public function show(int $id): void
    {
        $this->requireAuth();
        $this->render('transactions/show', ['id' => $id]);
    }

    /**
     * GET /lancamentos/{id}/editar - Formulário para editar lançamento
     */
    public function edit(int $id): void
    {
        $this->requireAuth();
        $this->render('transactions/form', ['mode' => 'edit', 'id' => $id]);
    }
}
