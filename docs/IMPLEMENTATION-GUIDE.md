# Guia de Implementação - Refatoração de Controllers

## Fase 1: Preparação

### 1.1 Criar RequestHandler
✅ **Status**: Concluído
- Arquivo: `src/Http/RequestHandler.php`
- Abstrai `$_SERVER`, `$_POST`, `$_GET`, `php://input`

### 1.2 Refatorar AbstractController
✅ **Status**: Concluído
- Arquivo: `src/Controller/AbstractController.php`
- Adiciona métodos auxiliares: `json()`, `getAuthUser()`, `isAuthenticated()`, `requireAuth()`

---

## Fase 2: Criar Controllers de Autenticação

### 2.1 AuthController
✅ **Status**: Concluído
- Arquivo: `src/Controller/AuthController.php`
- Métodos: `create()`, `store()`, `delete()`
- Responsabilidade: Login, logout, autenticação

**Uso**:
```php
// GET /login
$auth = new AuthController();
$auth->create();

// POST /auth
$auth->store();

// GET /logout
$auth->delete();
```

---

## Fase 3: Criar Controllers de Views

### 3.1 AccountController
✅ **Status**: Concluído
- Arquivo: `src/Controller/AccountController.php`
- Métodos: `index()`, `create()`, `show()`, `edit()`
- Responsabilidade: Renderizar views de contas

### 3.2 TransactionController
✅ **Status**: Concluído
- Arquivo: `src/Controller/TransactionController.php`
- Métodos: `index()`, `create()`, `show()`, `edit()`
- Responsabilidade: Renderizar views de lançamentos

### 3.3 CategoryController
✅ **Status**: Concluído
- Arquivo: `src/Controller/CategoryController.php`
- Métodos: `index()`, `create()`, `show()`, `edit()`
- Responsabilidade: Renderizar views de categorias

### 3.4 DashboardController
✅ **Status**: Concluído
- Arquivo: `src/Controller/DashboardController.php`
- Métodos: `index()`
- Responsabilidade: Renderizar dashboard

---

## Fase 4: Criar Controllers API REST

### 4.1 Api/AccountController
✅ **Status**: Concluído
- Arquivo: `src/Controller/Api/AccountController.php`
- Métodos: `index()`, `store()`, `show()`, `update()`, `delete()`
- Responsabilidade: API REST de contas

### 4.2 Api/TransactionController
✅ **Status**: Concluído
- Arquivo: `src/Controller/Api/TransactionController.php`
- Métodos: `index()`, `store()`, `show()`, `update()`, `delete()`
- Responsabilidade: API REST de lançamentos

### 4.3 Api/CategoryController
✅ **Status**: Concluído
- Arquivo: `src/Controller/Api/CategoryController.php`
- Métodos: `index()`, `store()`, `show()`, `update()`, `delete()`
- Responsabilidade: API REST de categorias

---

## Fase 5: Atualizar Rotas

### 5.1 Novo arquivo de rotas
**Arquivo**: `config/routes.php`

```php
<?php

use App\ControleFinanceiro\Controller\AuthController;
use App\ControleFinanceiro\Controller\AccountController;
use App\ControleFinanceiro\Controller\TransactionController;
use App\ControleFinanceiro\Controller\CategoryController;
use App\ControleFinanceiro\Controller\DashboardController;
use App\ControleFinanceiro\Controller\Api;
use App\ControleFinanceiro\Middleware\RequireAuth;

return [
    // Autenticação
    '/login'  => ['controller' => AuthController::class, 'action' => 'create'],
    '/auth'   => ['controller' => AuthController::class, 'action' => 'store'],
    '/logout' => ['controller' => AuthController::class, 'action' => 'delete'],

    // Dashboard
    '/dashboard' => [
        'controller' => DashboardController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],

    // Contas (Views)
    '/contas' => [
        'controller' => AccountController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/criar' => [
        'controller' => AccountController::class,
        'action' => 'create',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/{id}' => [
        'controller' => AccountController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/contas/{id}/editar' => [
        'controller' => AccountController::class,
        'action' => 'edit',
        'middleware' => [RequireAuth::class]
    ],

    // Lançamentos (Views)
    '/lancamentos' => [
        'controller' => TransactionController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/criar' => [
        'controller' => TransactionController::class,
        'action' => 'create',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/{id}' => [
        'controller' => TransactionController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/lancamentos/{id}/editar' => [
        'controller' => TransactionController::class,
        'action' => 'edit',
        'middleware' => [RequireAuth::class]
    ],

    // Categorias (Views)
    '/categorias' => [
        'controller' => CategoryController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/criar' => [
        'controller' => CategoryController::class,
        'action' => 'create',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/{id}' => [
        'controller' => CategoryController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/categorias/{id}/editar' => [
        'controller' => CategoryController::class,
        'action' => 'edit',
        'middleware' => [RequireAuth::class]
    ],

    // API REST - Contas
    '/api/accounts' => [
        'controller' => Api\AccountController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/api/accounts' => [
        'controller' => Api\AccountController::class,
        'action' => 'store',
        'middleware' => [RequireAuth::class]
    ],
    '/api/accounts/{id}' => [
        'controller' => Api\AccountController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/api/accounts/{id}' => [
        'controller' => Api\AccountController::class,
        'action' => 'update',
        'middleware' => [RequireAuth::class]
    ],
    '/api/accounts/{id}' => [
        'controller' => Api\AccountController::class,
        'action' => 'delete',
        'middleware' => [RequireAuth::class]
    ],

    // API REST - Lançamentos
    '/api/transactions' => [
        'controller' => Api\TransactionController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/api/transactions' => [
        'controller' => Api\TransactionController::class,
        'action' => 'store',
        'middleware' => [RequireAuth::class]
    ],
    '/api/transactions/{id}' => [
        'controller' => Api\TransactionController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/api/transactions/{id}' => [
        'controller' => Api\TransactionController::class,
        'action' => 'update',
        'middleware' => [RequireAuth::class]
    ],
    '/api/transactions/{id}' => [
        'controller' => Api\TransactionController::class,
        'action' => 'delete',
        'middleware' => [RequireAuth::class]
    ],

    // API REST - Categorias
    '/api/categories' => [
        'controller' => Api\CategoryController::class,
        'action' => 'index',
        'middleware' => [RequireAuth::class]
    ],
    '/api/categories' => [
        'controller' => Api\CategoryController::class,
        'action' => 'store',
        'middleware' => [RequireAuth::class]
    ],
    '/api/categories/{id}' => [
        'controller' => Api\CategoryController::class,
        'action' => 'show',
        'middleware' => [RequireAuth::class]
    ],
    '/api/categories/{id}' => [
        'controller' => Api\CategoryController::class,
        'action' => 'update',
        'middleware' => [RequireAuth::class]
    ],
    '/api/categories/{id}' => [
        'controller' => Api\CategoryController::class,
        'action' => 'delete',
        'middleware' => [RequireAuth::class]
    ],
];
```

---

## Fase 6: Remover Controllers Antigos

### 6.1 Deletar
- ❌ `src/Controller/IndexController.php` (consolidado em controllers específicos)
- ❌ `src/Controller/UserController.php` (consolidado em `AuthController`)
- ❌ `src/Controller/LogoutController.php` (consolidado em `AuthController`)

### 6.2 Manter
- ✅ `src/Controller/AbstractController.php` (refatorado)
- ✅ `src/Controller/AuthController.php` (novo)
- ✅ `src/Controller/AccountController.php` (novo)
- ✅ `src/Controller/TransactionController.php` (novo)
- ✅ `src/Controller/CategoryController.php` (novo)
- ✅ `src/Controller/DashboardController.php` (novo)
- ✅ `src/Controller/Api/DashboardController.php` (existente, manter)
- ✅ `src/Controller/Api/AccountController.php` (novo)
- ✅ `src/Controller/Api/TransactionController.php` (novo)
- ✅ `src/Controller/Api/CategoryController.php` (novo)

---

## Fase 7: Criar Services (Próxima Etapa)

### 7.1 AuthService
```php
class AuthService
{
    public function authenticate(string $email, string $password): AuthUser
    {
        // Validar credenciais
        // Retornar AuthUser ou lançar exceção
    }
}
```

### 7.2 AccountService
```php
class AccountService
{
    public function getAll(): array { }
    public function getById(int $id): ?Account { }
    public function create(array $data): Account { }
    public function update(int $id, array $data): Account { }
    public function delete(int $id): void { }
}
```

### 7.3 TransactionService
```php
class TransactionService
{
    public function getAll(): array { }
    public function getById(int $id): ?Transaction { }
    public function create(array $data): Transaction { }
    public function update(int $id, array $data): Transaction { }
    public function delete(int $id): void { }
}
```

### 7.4 CategoryService
```php
class CategoryService
{
    public function getAll(): array { }
    public function getById(int $id): ?Category { }
    public function create(array $data): Category { }
    public function update(int $id, array $data): Category { }
    public function delete(int $id): void { }
}
```

---

## Checklist de Implementação

### Fase 1: Preparação
- [x] Criar `RequestHandler`
- [x] Refatorar `AbstractController`

### Fase 2: Autenticação
- [x] Criar `AuthController`

### Fase 3: Views
- [x] Criar `AccountController`
- [x] Criar `TransactionController`
- [x] Criar `CategoryController`
- [x] Criar `DashboardController`

### Fase 4: API REST
- [x] Criar `Api/AccountController`
- [x] Criar `Api/TransactionController`
- [x] Criar `Api/CategoryController`

### Fase 5: Rotas
- [ ] Atualizar `config/routes.php`

### Fase 6: Limpeza
- [ ] Deletar `IndexController`
- [ ] Deletar `UserController`
- [ ] Deletar `LogoutController`

### Fase 7: Services (Próxima)
- [ ] Criar `AuthService`
- [ ] Criar `AccountService`
- [ ] Criar `TransactionService`
- [ ] Criar `CategoryService`

---

## Testes Recomendados

### Teste Manual
```bash
# Login
curl -X POST http://localhost/auth \
  -H "Content-Type: application/json" \
  -d '{"email":"teste@example.com","password":"senha123"}'

# Listar contas
curl -X GET http://localhost/api/accounts \
  -H "Cookie: PHPSESSID=..."

# Criar conta
curl -X POST http://localhost/api/accounts \
  -H "Content-Type: application/json" \
  -d '{"name":"Nova Conta","type":"checking"}'
```

### Teste Unitário
```php
class AuthControllerTest extends TestCase
{
    public function testLoginFormRendersCorrectly()
    {
        $controller = new AuthController();
        // Verificar se render é chamado com 'index/login'
    }

    public function testLoginWithValidCredentials()
    {
        $controller = new AuthController();
        // Verificar se retorna JSON com success=true
    }

    public function testLoginWithInvalidCredentials()
    {
        $controller = new AuthController();
        // Verificar se retorna JSON com success=false
    }
}
```

---

## Conclusão

A refatoração está pronta para implementação. Todos os controllers foram criados seguindo o padrão CRUD com responsabilidade única.

**Próximo passo**: Atualizar rotas e remover controllers antigos.
