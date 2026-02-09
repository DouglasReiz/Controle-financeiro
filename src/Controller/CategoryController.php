<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\CategoryService;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * CategoryController - CRUD de Categorias
 * 
 * Padrão REST:
 * - GET /categorias → read() → 200 HTML (lista) ou 200 JSON
 * - GET /categorias/criar → create() → 200 HTML (formulário)
 * - POST /categorias/criar → create() → 201 JSON (criado) ou 400 JSON (erro)
 * - GET /categorias/{id} → read($id) → 200 HTML (detalhe) ou 200 JSON
 * - GET /categorias/{id}/editar → update($id) → 200 HTML (formulário)
 * - PUT /categorias/{id}/editar → update($id) → 200 JSON ou 400 JSON (erro)
 * - DELETE /categorias/{id}/deletar → delete($id) → 204 No Content ou 400 JSON
 */
class CategoryController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->categoryService = new CategoryService();
    }

    /**
     * GET /categorias/criar → Exibe formulário
     * POST /categorias/criar → Cria nova categoria
     * 
     * Respostas POST:
     * - 201 Created: Categoria criada
     * - 400 Bad Request: Dados inválidos
     */
    public function create(): void
    {
        $this->requireAuth();

        if ($this->request->isPost()) {
            $data = $this->request->json();
            $userId = $this->getAuthUser()->getId();

            $errors = $this->categoryService->validateCategoryData($data);
            if (!empty($errors)) {
                $this->respondValidationError($errors);
                return;
            }

            $category = $this->categoryService->createCategory($data, $userId);
            $this->respondCreated($category);
            return;
        }

        $this->render('categories/form', ['mode' => 'create']);
    }

    /**
     * GET /categorias → Lista todas as categorias
     * GET /categorias/{id} → Retorna uma categoria específica
     * 
     * Respostas:
     * - 200 OK: Dados retornados
     * - 401 Unauthorized: Não autenticado
     */
    public function read(?int $id = null): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        if ($id) {
            $category = $this->categoryService->getCategoryById($id, $userId);
            $this->respondResource($category, 'categories/show', 'category');
            return;
        }

        $categories = $this->categoryService->getAllCategories($userId);
        $this->respondResourceList($categories, 'categories/index', 'categories');
    }

    /**
     * GET /categorias/{id}/editar → Exibe formulário
     * PUT /categorias/{id}/editar → Atualiza categoria
     * 
     * Respostas:
     * - 200 OK: Formulário (GET) ou Categoria atualizada (PUT)
     * - 400 Bad Request: Dados inválidos (PUT)
     * - 405 Method Not Allowed: Método inválido
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        // GET: Exibir formulário de edição
        if ($this->isGetRequest()) {
            $category = $this->categoryService->getCategoryById($id, $userId);
            $this->render('categories/form', ['mode' => 'edit', 'category' => $category]);
            return;
        }

        // PUT/POST: Atualizar dados
        if (!$this->isUpdateRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $data = $this->request->json();

        $errors = $this->categoryService->validateCategoryData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $category = $this->categoryService->updateCategory($id, $data, $userId);
        $this->respondSuccess(['data' => $category]);
    }

    /**
     * DELETE /categorias/{id}/deletar → Deleta categoria
     * 
     * Respostas:
     * - 204 No Content: Deletado com sucesso
     * - 405 Method Not Allowed: Método inválido
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->isDeleteRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $userId = $this->getAuthUser()->getId();
        $this->categoryService->deleteCategory($id, $userId);

        $this->respondNoContent();
    }
}
