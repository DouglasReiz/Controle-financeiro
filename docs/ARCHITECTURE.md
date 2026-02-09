# Arquitetura de Controllers

## Organização

4 controllers, um por domínio:

```
src/Controller/
├── AuthController.php
├── AccountController.php
├── TransactionController.php
└── CategoryController.php
```

## Padrão CRUD

Cada controller responde HTML ou JSON conforme necessário:

```php
class AccountController extends AbstractController
{
    public function index(): void { }      // GET /contas ou /api/accounts
    public function create(): void { }     // GET /contas/criar
    public function store(): void { }      // POST /contas ou /api/accounts
    public function show(int $id): void { } // GET /contas/{id}
    public function edit(int $id): void { } // GET /contas/{id}/editar
    public function update(int $id): void { } // PUT /contas/{id}
    public function delete(int $id): void { } // DELETE /contas/{id}
}
```

## Regras Obrigatórias

❌ **Não permitido**:
- Acessar `$_SESSION` diretamente → Use `AuthSession`
- Acessar `$_POST`, `$_GET` diretamente → Use `RequestHandler`
- Lógica de negócio pesada → Delegue para Services
- Múltiplas responsabilidades por método

✅ **Permitido**:
- Orquestrar fluxo
- Validação básica
- Renderizar views ou retornar JSON
- Chamar services

## Controllers

### AuthController
- `create()` → GET /login
- `store()` → POST /auth
- `delete()` → GET /logout

### AccountController
- `index()` → GET /contas (HTML) ou /api/accounts (JSON)
- `create()` → GET /contas/criar
- `store()` → POST /contas ou /api/accounts
- `show(id)` → GET /contas/{id}
- `edit(id)` → GET /contas/{id}/editar
- `update(id)` → PUT /contas/{id}
- `delete(id)` → DELETE /contas/{id}

### TransactionController
- Mesmo padrão que AccountController

### CategoryController
- Mesmo padrão que AccountController

## Responder HTML ou JSON

Use `Accept` header ou query param:

```php
public function index(): void
{
    $this->requireAuth();
    $data = $this->service->getAll();
    
    // JSON se Accept: application/json
    if ($this->request->wantsJson()) {
        $this->json(['success' => true, 'data' => $data]);
        return;
    }
    
    // HTML por padrão
    $this->render('accounts/index', ['accounts' => $data]);
}
```

## Próximas Etapas

Quando a API crescer muito, separe em `Api/` namespace.
Não antes.
