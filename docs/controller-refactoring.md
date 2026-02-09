# Refatoração de Controllers - Arquitetura MVC com Front Controller

## Análise Atual

### Problemas Identificados

1. **Controllers Mistos**: `IndexController` contém múltiplas entidades (dashboard, transactions, accounts, categories)
2. **Responsabilidades Espalhadas**: Lógica de autenticação em `AuthController` (vazio), `UserController` e `IndexController`
3. **Sem Padrão CRUD**: Métodos nomeados como `*Action` sem seguir convenção clara
4. **Acesso Direto a $_SERVER**: Controllers acessam `$_SERVER['REQUEST_METHOD']` e `$_SERVER['CONTENT_TYPE']`
5. **Falta de Separação**: Renderização de views misturada com lógica de negócio

---

## Arquitetura Proposta

### Princípios

- **Um Controller por Domínio**: Cada entidade tem seu próprio controller
- **Métodos CRUD Claros**: `create()`, `store()`, `show()`, `update()`, `delete()`
- **Sem Lógica de Negócio**: Controllers delegam para Services
- **Sem Acesso Direto a $_SESSION**: Usar `AuthSession` service
- **Responsabilidade Única**: Cada método faz uma coisa bem

### Controllers Necessários

```
src/Controller/
├── AbstractController.php (base)
├── AuthController.php (login, logout, autenticação)
├── AccountController.php (contas)
├── CategoryController.php (categorias)
├── TransactionController.php (lançamentos)
├── DashboardController.php (dashboard)
└── Api/
    ├── AccountController.php (API REST)
    ├── CategoryController.php (API REST)
    └── TransactionController.php (API REST)
```

---

## Estrutura de Cada Controller

### AuthController
**Responsabilidade**: Autenticação e autorização

```php
class AuthController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private RequestHandler $request
    ) {}

    // GET /login - Exibir formulário
    public function create(): void
    {
        $this->render('index/login');
    }

    // POST /login - Processar login
    public function store(): void
    {
        $email = $this->request->post('email');
        $password = $this->request->post('password');

        try {
            $user = $this->authService->authenticate($email, $password);
            AuthSession::set($user);
            $this->json(['success' => true, 'redirect' => '/dashboard']);
        } catch (AuthException $e) {
            http_response_code(401);
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // GET /logout - Logout
    public function delete(): void
    {
        AuthSession::clear();
        header('Location: /login');
        exit;
    }
}
```

### AccountController
**Responsabilidade**: Gerenciar contas (views)

```php
class AccountController extends AbstractController
{
    public function __construct(
        private AccountService $accountService
    ) {}

    // GET /contas - Listar contas
    public function index(): void
    {
        $this->render('accounts/index');
    }

    // GET /contas/criar - Formulário de criação
    public function create(): void
    {
        $this->render('accounts/form');
    }

    // GET /contas/{id} - Detalhes de uma conta
    public function show(int $id): void
    {
        $account = $this->accountService->getById($id);
        if (!$account) {
            http_response_code(404);
            return;
        }
        $this->render('accounts/show', ['account' => $account]);
    }

    // GET /contas/{id}/editar - Formulário de edição
    public function edit(int $id): void
    {
        $account = $this->accountService->getById($id);
        if (!$account) {
            http_response_code(404);
            return;
        }
        $this->render('accounts/form', ['account' => $account]);
    }
}
```

### Api/AccountController
**Responsabilidade**: API REST para contas

```php
class AccountController extends AbstractController
{
    public function __construct(
        private AccountService $accountService,
        private RequestHandler $request
    ) {}

    // GET /api/accounts - Listar todas
    public function index(): void
    {
        $accounts = $this->accountService->getAll();
        $this->json(['success' => true, 'data' => $accounts]);
    }

    // POST /api/accounts - Criar nova
    public function store(): void
    {
        $data = $this->request->json();

        try {
            $account = $this->accountService->create($data);
            http_response_code(201);
            $this->json(['success' => true, 'data' => $account]);
        } catch (ValidationException $e) {
            http_response_code(400);
            $this->json(['success' => false, 'errors' => $e->getErrors()]);
        }
    }

    // GET /api/accounts/{id} - Detalhes
    public function show(int $id): void
    {
        $account = $this->accountService->getById($id);
        if (!$account) {
            http_response_code(404);
            $this->json(['success' => false, 'message' => 'Conta não encontrada']);
            return;
        }
        $this->json(['success' => true, 'data' => $account]);
    }

    // PUT /api/accounts/{id} - Atualizar
    public function update(int $id): void
    {
        $data = $this->request->json();

        try {
            $account = $this->accountService->update($id, $data);
            $this->json(['success' => true, 'data' => $account]);
        } catch (ValidationException $e) {
            http_response_code(400);
            $this->json(['success' => false, 'errors' => $e->getErrors()]);
        } catch (NotFoundException $e) {
            http_response_code(404);
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // DELETE /api/accounts/{id} - Deletar
    public function delete(int $id): void
    {
        try {
            $this->accountService->delete($id);
            $this->json(['success' => true, 'message' => 'Conta deletada']);
        } catch (NotFoundException $e) {
            http_response_code(404);
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
```

---

## AbstractController Refatorado

```php
<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AuthSession;

abstract class AbstractController
{
    /**
     * Renderiza uma view com dados opcionais
     */
    protected function render(string $viewName, array $data = []): void
    {
        extract($data);
        include __DIR__ . "/../View/$viewName.php";
    }

    /**
     * Retorna JSON com headers apropriados
     */
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Obtém usuário autenticado (sem acessar $_SESSION)
     */
    protected function getAuthUser(): ?AuthUser
    {
        return AuthSession::get();
    }

    /**
     * Verifica se usuário está autenticado
     */
    protected function isAuthenticated(): bool
    {
        return AuthSession::has();
    }

    /**
     * Redireciona para login se não autenticado
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }
}
```

---

## RequestHandler (Novo Utilitário)

```php
<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Http;

class RequestHandler
{
    /**
     * Obtém valor de POST
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Obtém valor de GET
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Obtém JSON do body
     */
    public function json(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') === false) {
            return [];
        }
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }

    /**
     * Obtém método HTTP
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Verifica se é POST
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Verifica se é GET
     */
    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    /**
     * Verifica se é PUT
     */
    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    /**
     * Verifica se é DELETE
     */
    public function isDelete(): bool
    {
        return $this->method() === 'DELETE';
    }
}
```

---

## Rotas Refatoradas

```php
<?php

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

    // API REST
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
];
```

---

## Checklist de Refatoração

- [ ] Criar `RequestHandler` para abstrair `$_SERVER`, `$_POST`, `$_GET`
- [ ] Refatorar `AbstractController` com métodos auxiliares
- [ ] Criar `AuthController` com `create()`, `store()`, `delete()`
- [ ] Criar `AccountController` para views
- [ ] Criar `Api/AccountController` para REST
- [ ] Criar `CategoryController` para views
- [ ] Criar `Api/CategoryController` para REST
- [ ] Criar `TransactionController` para views
- [ ] Criar `Api/TransactionController` para REST
- [ ] Criar `DashboardController` para dashboard
- [ ] Atualizar rotas
- [ ] Remover `IndexController` (consolidar em controllers específicos)
- [ ] Remover `UserController` (consolidar em `AuthController`)
- [ ] Remover `LogoutController` (consolidar em `AuthController`)

---

## Benefícios

✅ **Separação de Responsabilidades**: Cada controller cuida de uma entidade  
✅ **Padrão CRUD Consistente**: Métodos nomeados uniformemente  
✅ **Sem Acesso Direto a $_SESSION**: Tudo via `AuthSession`  
✅ **Testabilidade**: Controllers podem ser testados com mocks  
✅ **Escalabilidade**: Fácil adicionar novos controllers  
✅ **Manutenibilidade**: Código previsível e organizado
