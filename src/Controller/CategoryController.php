<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

/**
 * CategoryController - Responsável por renderizar views de categorias
 * Métodos: index (listar), create (formulário novo), show (detalhes), edit (formulário edição)
 */
class CategoryController extends AbstractController
{
    /**
     * GET /categorias - Listar todas as categorias
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->render('categories/index');
    }

    /**
     * GET /categorias/criar - Formulário para criar nova categoria
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->render('categories/form', ['mode' => 'create']);
    }

    /**
     * GET /categorias/{id} - Detalhes de uma categoria específica
     */
    public function show(int $id): void
    {
        $this->requireAuth();
        $this->render('categories/show', ['id' => $id]);
    }

    /**
     * GET /categorias/{id}/editar - Formulário para editar categoria
     */
    public function edit(int $id): void
    {
        $this->requireAuth();
        $this->render('categories/form', ['mode' => 'edit', 'id' => $id]);
    }
}
