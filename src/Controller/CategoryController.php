<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\CategoryService;
use App\ControleFinanceiro\Http\RequestHandler;

/**
 * CategoryController - CRUD de Categorias (Recurso)
 * 
 * Padrão REST explícito por HTTP method:
 * - GET /categorias → read() → Lista todas as categorias
 * - GET /categorias/criar → showCreateForm() → Formulário de criação
 * - POST /categorias → create() → Cria nova categoria
 * - GET /categorias/{id} → read($id) → Detalhe de uma categoria
 * - GET /categorias/{id}/editar → showEditForm($id) → Formulário de edição
 * - PUT /categorias/{id} → update($id) → Atualiza categoria
 * - DELETE /categorias/{id} → delete($id) → Deleta categoria
 * 
 * Responsabilidades:
 * - Receber request e extrair dados
 * - Delegar validação e lógica ao CategoryService
 * - Responder com HTML (views) ou JSON (API)
 * - Gerenciar autenticação via middleware
 * 
 * Padrão: Cada método HTTP tem seu próprio método no controller
 * Sem if statements para verificar método HTTP (roteamento via routes.php)
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
     * GET /categorias/criar
     * Exibe formulário de criação de categoria
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Renderizar view com formulário vazio
     */
    public function showCreateForm(): void
    {
        $this->requireAuth();
        $this->render('categories/form', ['mode' => 'create']);
    }

    /**
     * POST /categorias
     * Cria nova categoria
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Extrair dados do request
     * 3. Delegar validação ao CategoryService
     * 4. Delegar criação ao CategoryService
     * 5. Responder com 201 Created (JSON)
     * 
     * Respostas:
     * - 201 Created: Categoria criada com sucesso
     * - 400 Bad Request: Dados inválidos
     */
    public function create(): void
    {
        $this->requireAuth();

        $data = $this->request->json();
        $userId = $this->getAuthUser()->getId();

        $errors = $this->categoryService->validateCategoryData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $category = $this->categoryService->createCategory($data, $userId);
        $this->respondCreated($category);
    }

    /**
     * GET /categorias ou GET /categorias/{id}
     * Lista todas as categorias ou retorna uma específica
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Se {id} fornecido: buscar categoria específica
     * 3. Se sem {id}: buscar todas as categorias
     * 4. Responder com HTML (view) ou JSON (API)
     * 
     * Respostas:
     * - 200 OK: Dados retornados (HTML ou JSON)
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
     * GET /categorias/{id}/editar
     * Exibe formulário de edição de categoria
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Buscar categoria pelo ID
     * 3. Renderizar view com dados da categoria
     */
    public function showEditForm(int $id): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        $category = $this->categoryService->getCategoryById($id, $userId);
        $this->render('categories/form', ['mode' => 'edit', 'category' => $category]);
    }

    /**
     * PUT /categorias/{id}
     * Atualiza categoria
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Extrair dados do request
     * 3. Delegar validação ao CategoryService
     * 4. Delegar atualização ao CategoryService
     * 5. Responder com 200 OK (JSON)
     * 
     * Respostas:
     * - 200 OK: Categoria atualizada com sucesso
     * - 400 Bad Request: Dados inválidos
     */
    public function update(int $id): void
    {
        $this->requireAuth();

        $data = $this->request->json();
        $userId = $this->getAuthUser()->getId();

        $errors = $this->categoryService->validateCategoryData($data);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $category = $this->categoryService->updateCategory($id, $data, $userId);
        $this->respondSuccess(['data' => $category]);
    }

    /**
     * DELETE /categorias/{id}
     * Deleta categoria
     * 
     * Fluxo:
     * 1. Verificar autenticação (middleware)
     * 2. Delegar deleção ao CategoryService
     * 3. Responder com 204 No Content
     * 
     * Respostas:
     * - 204 No Content: Deletado com sucesso
     */
    public function delete(int $id): void
    {
        $this->requireAuth();

        $userId = $this->getAuthUser()->getId();
        $this->categoryService->deleteCategory($id, $userId);

        $this->respondNoContent();
    }
}
