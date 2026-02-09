<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\CategoryService;
use App\ControleFinanceiro\Http\RequestHandler;

class CategoryController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->categoryService = new CategoryService();
    }

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

    public function read(?int $id = null): void
    {
        $this->requireAuth();
        $userId = $this->getAuthUser()->getId();

        if ($id) {
            $category = $this->categoryService->getCategoryById($id, $userId);
            $this->respondResource($category);
            return;
        }

        $categories = $this->categoryService->getAllCategories($userId);
        $this->respondResourceList('categories/index', $categories);
    }

    public function update(int $id): void
    {
        $this->requireAuth();

        if (!$this->isUpdateRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

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

    public function delete(int $id): void
    {
        $this->requireAuth();

        if (!$this->isDeleteRequest()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $userId = $this->getAuthUser()->getId();
        $this->categoryService->deleteCategory($id, $userId);

        $this->respondSuccess(['message' => 'Categoria deletada']);
    }

    /**
     * Verifica se é requisição de atualização (PUT ou POST)
     */
    private function isUpdateRequest(): bool
    {
        return $this->request->isPut() || $this->request->isPost();
    }

    /**
     * Verifica se é requisição de deleção (DELETE ou POST)
     */
    private function isDeleteRequest(): bool
    {
        return $this->request->isDelete() || $this->request->isPost();
    }

    /**
     * Responde com um recurso individual (HTML ou JSON)
     */
    private function respondResource(array $resource): void
    {
        if ($this->wantsJson()) {
            $this->respondSuccess(['data' => $resource]);
            return;
        }

        $this->render('categories/show', ['category' => $resource]);
    }

    /**
     * Responde com lista de recursos (HTML ou JSON)
     */
    private function respondResourceList(string $viewName, array $resources): void
    {
        if ($this->wantsJson()) {
            $this->respondSuccess(['data' => $resources]);
            return;
        }

        $this->render($viewName, ['categories' => $resources]);
    }
}
